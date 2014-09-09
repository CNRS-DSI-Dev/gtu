<?php
/**
 * ownCloud - News
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 */

namespace OCA\Gtu\AppInfo;

use \OCP\AppFramework\App;
use OCA\Gtu\Service\GtuService;
use OCA\Gtu\Db\UserGtuValidationMapper;
use OCA\Gtu\Controller\PageController;
use OCA\Gtu\Controller\GtuApiController;
use OCA\Gtu\Hook\GtuHooks;


/**
 * undocumented class
 *
 * @package OCA\Gtu\AppInfo
 * @author 
 **/
class GtuApp extends App {

	public function __construct(array $urlParams=array()){
		parent::__construct('gtu', $urlParams);

		$container = $this->getContainer();
		
		// Controller
		// $container->registerService('PageController', function ($c) {
		// 	return  new PageController(
		// 		$c->query('Request'),
		// 		$c->getServer()->getUserSession()->getUser(),
		// 		$c->query('GtuService'),
		// 		$c->getServer()->getSession());

		// });
		$container->registerService('GtuApiController', function ($c) {
			return  new GtuApiController(
				$c->query('Request'),
				$c->getServer()->getUserSession()->getUser(),
				$c->query('GtuService'));
			
		});

		
		// Mapper
		$container->registerService('gtuMapper',function($c) {
			return new GtuMapper(
				$c->query('ServerContainer')->getDb()
				);
		});
		
		// Mapper
		$container->registerService('userGtuValidationMapper',function($c) {
			return new UserGtuValidationMapper(
				$c->query('ServerContainer')->getDb()
				);
		});		
		
		// Service
		$container->registerService('GtuService', function ($c) {
			return  new GtuService(
				$c->query('ServerContainer')->getAppConfig(), 
				$c->query('userGtuValidationMapper'),
				$c->getServer()->getUserManager(),
				$c->getServer()->getSession());
		});


		$container->registerService('GtuHooks', function ($c) {
			return  new GtuHooks(
				$c->query('userGtuValidationMapper'),
				$c->getServer()->getSession());
		});


		$container->registerService('Interceptor', function ($c) {
			return  new Interceptor(
				$c->isLoggedIn(),
				$c->query('GtuService'),
				$c->getServer()->getAppConfig()
				);
		});


	}

	public function getAppConfig() {
		return $this->getContainer()->getServer()->getAppConfig();
	}


	public function getLogger() {
		return $this->getContainer()->query('Logger');
	}
	
	public function getUserSession() {
		return $this->getContainer()->getServer()->getUserSession();
	}
	
	public function getUser() {
		return $this->getContainer()->getServer()->getUserSession()->getUser();
	}

	public function getUserManager() {
		return $this->getContainer()->getServer()->getUserManager();
	}

	public function getUrlGenerator() {
		return $this->getContainer()->getServer()->getUrlGenerator();
	}
	
}

