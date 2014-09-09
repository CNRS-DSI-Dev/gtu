<?php
/**
 * ownCloud - gtuapicontroller.php
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
namespace OCA\Gtu\Controller;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;

/**
*
*/
class GtuApiController extends Controller {

	protected $user;
	protected $gtuService;
	
	
	public function __construct($request,$user, $gtuService) {
		parent::__construct('gtu', $request);
		$this->user = $user;
		$this->gtuService = $gtuService;
	
	}


	/**
	* @Ajax
	*/
	public function get() {
		return new JSONResponse( $this->gtuService->getGtuParams());
	}

	/**
	* @Ajax
	*/
	public function post() {
		$params = $this->request->post;
		try{
		 	$this->gtuService->setGtuParams(
		 		$params['version'], 
		 		$params['text'], 
		 		$params['url'],
		 		$params['msg'], 
		 		$params['start_page_message'], 
		 		$params['start_page_url']);
			return new JSONResponse( array('ok'));
		} catch(Exception $e) {
			return new JSONResponse( array('error' => $e.getMessage()), \OCP\AppFramework\Http::STATUS_BAD_REQUEST);
		}
	}


	/**
	* finds active GTU
	* @Ajax
    * @NoAdminRequired
    * @PublicPage
	*/
	public function findActive() {
		return new JSONResponse( $this->gtuService->getActiveGTU() );
	}

	/**
	* finds active GTU + screen params
	* @Ajax
    * @NoAdminRequired
    * @PublicPage
	*/
	public function findGtuParams() {
		return new JSONResponse( $this->gtuService->getActiveGTU() );
	}

	/**
	* @Ajax
	* @NoCSRFRequired
    * @NoAdminRequired
    * @PublicPage
    */
	public function isAgreementRequired() {
		return new JSONResponse( $this->gtuService->isLastGtuAgreed($user) );
	}

	/**
	* http://localhost/core/index.php/apps/gtu/%5Bobject%20Object%5D
	* @Ajax
	* @NoCSRFRequired
    * @NoAdminRequired
    * @PublicPage
	*/
	public function postAgreement() {
		\OCP\Util::writeLog('GtuApiController', "potsAgreement for {$this->user->getUID()}",\OCP\Util::DEBUG);
		$this->gtuService->validateGTU($this->user);
		return new JSONResponse(array('validation' => 'ok'));
	}

 }