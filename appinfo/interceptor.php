<?php
/**
 * ownCloud - 
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


namespace OCA\Gtu\AppInfo;

/**
* Interceptor
*
*/
class Interceptor {
	var $isLoggedIn;
	var $gtuService;
	var $appConfig;


	function __construct($isLoggedIn, $gtuService, $appConfig){
		$this->isLoggedIn 	= $isLoggedIn;
		$this->gtuService 	= $gtuService;
		$this->appConfig 	= $appConfig;
	}


	function run($user) {
		if ( $this->isLoggedIn ) {

			if ( ! $this->gtuService->isLastGtuAgreed( $user ) ) {

 		//ne laisse pas passer les apps.
				if ( ! $this->isAllowed() && $this->isRequiredToDisplayGTU()) {
					\OC_Template::printGuestPage('gtu', 'validate', array(
						'msg' 					=> $this->appConfig->getValue('gtu', 'msg'),
						'start_page_url' 		=> $this->appConfig->getValue('gtu', 'start_page_url', \OC_Util::getDefaultPageUrl()), 
						'start_page_message' 	=> $this->appConfig->getValue('gtu', 'start_page_message') )
					);
					exit();
				}
			}
		}
	}
//--- Helpers - 
	function path($url) {
		$urlArray = parse_url($url);
		return $urlArray['path'];
	}

	function isAllowed() {
		$requestedPath = $this->path($_SERVER['REQUEST_URI']);
		$isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

		if ( $this->endsWith($requestedPath, '.js') ) return true;
		if ( $this->endsWith($requestedPath, '.css')) return true;
		if ( $this->endsWith($requestedPath, 'apps/gtu/api/agreement')) return true;

		return false;
	}

	function isRequiredToDisplayGTU() {
		$requestedPath = $this->path($_SERVER['REQUEST_URI']);
		if ( $this->endsWith($requestedPath, '.php')) return false;
		return true;
	}

	function endsWith($text, $suffix) {
		return substr($text, -strlen($suffix)) === $suffix;
	}

}
?>