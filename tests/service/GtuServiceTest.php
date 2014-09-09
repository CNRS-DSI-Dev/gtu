<?php
/**
 * ownCloud - gtuservicetest.php
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
 namespace  OCA\Gtu\Service;
 


 use OCA\Gtu\Db\Gtu;
 use OCA\Gtu\Db\UserGtuValidation; 
 use PHPUnit_Framework_TestSuite;
 use OCP\AppFramework\Db\DoesNotExistException;

/**
 * Unit test for GtuService
 * @author MDE
 *
 */
class GtuServiceTest extends \PHPUnit_Framework_TestCase { 
 	
 	private $service; 
 	private $appConfig;
 	private $userGtuValidationMapper;
 	private $userManager;
 	
 	protected function setUp(){
 		// $this->appConfig = $this->getMockBuilder('OC\AppConfig')
 		// 						->disableOriginalConstructor()
			// 					->getMock();


 		$this->appConfig = new LocalAppConfig();

 		$this->userGtuValidationMapper = $this->getMockBuilder('OCA\Gtu\Db\UserGtuValidationMapper')
 								->disableOriginalConstructor()
								->getMock();
		$this->userManager = $this->getMockBuilder('OC\User\Manager')
 								->disableOriginalConstructor()
								->getMock();

 		$this->session = $this->getMockBuilder('OC\Session\Memory')
 								->disableOriginalConstructor()
								->getMock();

 		$this->service = new GtuService(
 			$this->appConfig, 
 			$this->userGtuValidationMapper, 
 			$this->userManager, 
 			$this->session);
 

 	}
 	
 	
 	/**
 	 * No GTU recorded
 	 */
 	public function test_IsLastGtuAgreed_NoRecordedGTU() {
 		//___GIVEN____
		$this->generateAppConfig( array('version' => '-1', 'text' => null, 'url' => null) );
 		$user = $this->getMockBuilder('\OC\User\User')->disableOriginalConstructor()->getMock();
 		
 		// $this->appConfig->expects($this->any())
	 	// 	->method('getValue')
	 	// 	->with('gtu','version')
	 	// 	->willReturn(-1);

	 	$this->session->expects($this->once())
	 		->method('get')
	 		->with('gtu_ok')
	 		->willReturn( false);
 		
 		
 		//___WHEN___

 		$value =$this->service->isLastGtuAgreed($user);
 		
 		
 		//__THEN__
 		$this->assertTrue($value);
 	}
 	
 	/**
 	 * User has validated the last GTU, so it's OK
 	 */
 	public function test_IsLastGtuAgreed_NoSignature() {
 		//___GIVEN____
 		$this->generateAppConfig( array('version' => '3', 'text' => 'blabla', 'url' => 'http://nowhere') );
 		$user = $this->generateUser();
 		
 		$ugtValidation = new UserGtuValidation();
 	
 		// when no record in DB, DoesNotExistException is thrown
 		$this->userGtuValidationMapper
 			->expects($this->any())
 			->method('findByUid')
 			->will($this->throwException(new DoesNotExistException('NoSignature') ));
 			
 			
 		//___WHEN___
 		$isOk = $this->service->isLastGtuAgreed($user);
 			
 		//__THEN__
 		$this->assertFalse($isOk);
 	}
 	

 	/**
 	 * User has not validated the last GTU
 	 */	
 	public function test_IsLastGtuAgreed_UserHasNotAgreed() {
 		//___GIVEN____
 		$this->generateAppConfig(array('version' => '3', 'text' => 'blabla', 'url' => 'http://nowhere'));
 		$user = $this->generateUser();
 		
 		$valid = new UserGtuValidation();
 		$valid->gtuVersion = 1;
 		
 		// Session must not be used !	
 		$this->session->expects($this->never())
	 		->method('set')
	 		->with('gtu_ok', 'uid@domain');

 		$this->userGtuValidationMapper->expects($this->any())
 		->method('findByUid')
 		->will($this->returnValue($valid) );
 		//___WHEN___
 		$isOk = $this->service->isLastGtuAgreed($user);
 	
 		//__THEN__
 		$this->assertFalse($isOk);
 	}
 	
 	/**
 	 * User has not validated the last GTU
 	 */	
 	public function test_IsLastGtuAgreed_Ok() {
 		//___GIVEN____
 		$this->generateAppConfig(array('version' => '3', 'text' => 'blabla', 'url' => 'http://nowhere'));
 		$user = $this->generateUser();
 		
 		$valid = new UserGtuValidation();
 		$valid->gtuVersion = 3;
 	
 		// it's expected that session 'gtu_ok' value is set to '1'
 		$this->session->expects($this->once())
	 		->method('get')
	 		->with('gtu_ok')->willReturn( false );

 		$this->session->expects($this->once())
	 		->method('set')
	 		->with('gtu_ok', 'uid@domain');
	 		
 		$this->userGtuValidationMapper->expects($this->any())
 		->method('findByUid')
 		->will($this->returnValue($valid) );
 		//___WHEN___
 		$isOk = $this->service->isLastGtuAgreed($user);
 	
 		//__THEN__
 		$this->assertTrue($isOk);
 	}

 	public function test_IsLastGtuAgreed_Ok_speeded_by_session_gtu_ok() {
 		 		//___GIVEN____
 		$this->session->expects($this->once())
	 		->method('get')
	 		->with('gtu_ok')->willReturn( 'uid@domain' );

 		$user = $this->generateUser();

 		//___WHEN___
 		$isOk = $this->service->isLastGtuAgreed($user);
 	
 		//__THEN__
 		$this->assertTrue($isOk);
 		$this->assertEquals(0, $this->appConfig->callCount);

 	}

 	/**
 	 * undocumented function
 	 *
 	 * @return void
 	 * @author 
 	 **/
 	function testValidateGTU() {
 		//__GIVEN__
 		$this->generateAppConfig(array('version' => '3', 'text' => 'blabla', 'url' => 'http://nowhere'));
 		$user = $this->generateUser(); 		

 		$this->userGtuValidationMapper->expects($this->once())
 			->method('updateValidation')
 			->with('uid@domain', 3);

 		$this->session->expects($this->once())
 			->method('set')
 			->with('gtu_ok', 'uid@domain');

		$this->service->validateGTU($user);
 	
 	}

 	/**
 	 * undocumented function
 	 *
 	 * @return void
 	 * @author 
 	 **/
 	function generateAppConfig($config){
 /*		$this->appConfig->expects($this->any())
 		->method('getValue')
 		->with('gtu', 'version', '-1')
 		->willReturn($config['version']);

 		$this->appConfig->expects($this->any())
 		->method('getValue')
 		->with('gtu', 'text')
 		->willReturn($config['text']);

 		$this->appConfig->expects($this->any())
 		->method('getValue')
 		->with('gtu', 'url')
 		->willReturn($config['url']);			 		
 */	
 		$this->appConfig->data = $config;

 	}

 	function generateUser() {
 		$user = $this->getMockBuilder('\OC\User\User')->disableOriginalConstructor()->getMock();
 		$user->expects($this->any())->method('getUID')->will($this->returnValue('uid@domain'));
 		return $user;

 	}
 	
 }

 class LocalAppConfig {

 	var $data;
 	var $callCount = 0;

 	function getValue($appName, $key, $default=null) {
 		$this->callCount++;
 		if ( isset($this->data[$key])) {
 			return $this->data[ $key ];
 		}
 		return $default;
 	}
 }