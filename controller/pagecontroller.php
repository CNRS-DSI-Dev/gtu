<?php
/**
 * ownCloud - pagecontroller.php
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
use OCP\AppFramework\Http\TemplateResponse;
/**
 * 
 * @author MDE
 *
 */
class PageController extends Controller {
	
	protected $userId;
	protected $gtuService;
	
	public function __construct($request,$userId, $gtuService) {
		parent::__construct('gtu', $request);
		$this->userId = $userId;	
		$this->gtuService = $gtuService;
		
	}

	
	/**
	 * @return \OCP\AppFramework\Http\TemplateResponse
	 */
	public function index() {
		return new TemplateResponse(
				$this->appName, 
				"main", array()
		);
	}	
	/**
	 * @NoCSRFRequired
	 * @NoAdminRequired
	 * @PublicPage
	 * @return \OCP\AppFramework\Http\TemplateResponse
	 */
	public function validate() {
		$params = array(
			'msg' => '@@@@ Message général à écrire @@@@',
			'start_page_url' => \OC_Util::getDefaultPageUrl(), 
			'start_page_message' => '@@@@ message de retour à home à écrire @@@@');
		return new TemplateResponse(
				$this->appName,
				"validate",
				$params
		);
	}	
}