<?php
/**
 * ownCloud - gtuservice.php
 *
 * @author Marc DeXeT
 * @copyright 2014 DSI CNRS https://www.dsi.cnrs.fr
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Gtu\Service;

use OCP\AppFramework\Db\DoesNotExistException;
use OC\User\User;
/**
 *
 * @author MDE
 *
 */
class GtuService {

	private $appConfig;
	private $userGtuValidationMapper;
 	//@deprecated
	private $userManager;

	private $session;
	private $currentGtu;

	public function __construct($appConfig,$userGtuValidationMapper,$userManager, $session) {
		$this->appConfig = $appConfig;
		$this->userGtuValidationMapper = $userGtuValidationMapper;
		$this->userManager = $userManager;
		$this->session = $session;
	}


 	/**
 	 *
 	 * @param User $user
 	 * @return boolean
 	 */
 	public function isLastGtuAgreed(User $user) {
 		if ( $this->isAdmin($user) ) return TRUE;
 		$uid = $user->getUID();
 		if ( $this->session->get('gtu_ok') === $uid ) return TRUE;
 		$gtu = $this->getActiveGTU();

 		// if no GTU, lastGTU is agreed
 		if ( $gtu['version'] < 0 ) {
 			return true;
 		}

 		$value = TRUE;
 		try {
 			$vald 	= $this->userGtuValidationMapper->findByUid($uid);
 			if ( $vald->gtuVersion < $gtu['version'] ) {
 				$value = FALSE;
 			}
 		}catch(DoesNotExistException $dnee){
 			\OCP\Util::writeLog('GtuService', print_r($dnee->getMessage(), true),\OCP\Util::DEBUG);
 			$value = FALSE;
 		}
 		if ( $value ) {
 			$this->session->set('gtu_ok', $uid);
 		}
 		return $value;
 	}


 	public function getActiveGTU() {
		if ( $this->currentGtu === null ) {
			$ac = $this->appConfig;

			$version = (int) $ac->getValue('gtu', 'version', '-1');
			if ( $version > 0 ) {
				$text = $ac->getValue('gtu', 'text');
				$url = $ac->getValue('gtu', 'url');
				$array =  array('version' => $version, 'text' => $text, 'url' => $url);
			} else {
				$array = array('version' => -1, 'text' => null, 'url' => null);
			}
			$this->currentGtu= $array;
		}
		return $this->currentGtu;
 	}


 	public function getScreenParams() {
 		$ac = $this->appConfig;
 		$msg = $ac->getValue('gtu', 'msg', 'change me in settings');
 		$start_page_url = $ac->getValue('gtu', 'start_page_url', \OC_Util::getDefaultPageUrl());
 		$start_page_message = $ac->getValue('gtu', 'start_page_message', 'change me in settings');
 		return array('msg' => $msg, 'start_page_url' => $start_page_url, 'start_page_message' => $start_page_message);
 	}

 	public function getGtuParams() {
 		$array = array_merge($this->getActiveGTU(), $this->getScreenParams());
 		return $array;
 	}


 	public function setGtuParams($version, $text, $url,$msg, $start_page_message, $start_page_url) {
 		if ( ! is_int($version) ) {
 			throw new Exception("version ${version} is not an valid integer", 1);
 		}
		$ac = $this->appConfig;
		$ac->setValue('gtu', 'version', strval($version));
		$ac->setValue('gtu', 'text', $text);
		$ac->setValue('gtu', 'url', $url);
 		$ac->setValue('gtu', 'msg', $msg);
 		$ac->setValue('gtu', 'start_page_url', $start_page_url);
 		$ac->setValue('gtu', 'start_page_message', $start_page_message);
 		return true;

 	}


 	public function validateGTU($user) {
 		$gtu = $this->getActiveGTU();
 		$uid = $user->getUID();
 		if (  isset($uid) ) {
 			$this->userGtuValidationMapper->updateValidation($uid, 	$gtu['version']);
 			$this->session->set('gtu_ok', $uid);
 		}
 	}


 	function isAdmin(User $user) {
 		if ( ! isset($user) ) return FALSE;
 		return \OC_User::isAdminUser($user->getUID());
 	}

 }
