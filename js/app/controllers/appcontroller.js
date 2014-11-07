/**
*
*/
//use 'strict';
var app = angular.module('Gtu',[]).
	config(['$httpProvider', function($httpProvider) {
		// Always send the CSRF token by default
		$httpProvider.defaults.headers.common.requesttoken = oc_requesttoken;

	}]);


// app.controller('AppCtrl', ['$scope','$http','$filter', function($scope, $http, $filter){
	
// 	var loc = OC.generateUrl('apps/gtu/api/gtu');
// 	$scope.status = 'loading';
// 	$scope.notValidated =  true;
	
// 	// http://stackoverflow.com/a/16387215/1306771
// 	$http.get(loc).
// 		success( function(data, status, headers, config){
// 			$scope.status = 'Current GTU';
// 			$scope.gtuList = data;
// 			$scope.gtu = $filter('filter')(data, {'toUse': '1'})[0];
// 			var a=1
// 		}).
//     	error( function(data, status, headers, config) {
//       		$scope.status = 'error getting last GTU';
//     	}
//     );



// 	$scope.validate = function() {
// 		$scope.status = 'validation in progress';
// 		$http.post(loc).
// 			success( function(data, status, headers, config){
// 				$scope.status = 'last GTU validated';
// 				$scope.notValidated =  false;
// 			}).
//     		error( function(data, status, headers, config) {
//       			$scope.status = 'Validation error';
//     		});

// 	}
// }]);
app.controller('EditAppCtrl', ['$scope','$http', function($scope, $http){
	$scope.button = 'Save';

	// load current GTU

	$http.get( OC.generateUrl('apps/gtu/api/params') ).
	success( function(data, status, headers, config){
		$scope.version=data.version;
		$scope.text = data.text;
		$scope.url = data.url;
		$scope.msg = data.msg;
		$scope.start_page_message = data.start_page_message;
		$scope.start_page_url = data.start_page_url;

	}).error(function(data, status, headers, config){
		$scope.status = 'Error '+status;
	});

	// function to post a new version
	$scope.doPost = function(){

		$http.post(OC.generateUrl('apps/gtu/api/params'), 
			{ version: $scope.version, 
				text: $scope.text, 
				url: $scope.url, 
				msg: $scope.msg, 
				start_page_message: $scope.start_page_message, 
				start_page_url: $scope.start_page_url} ).
		success( function(data, status, headers, config){
			$scope.status = 'Saved';
		}).
		error( function(data, status, headers, config) {
			$scope.status = 'error getting last GTU';
		});
	}

}]);




/**
* Controller for GTU management
*/

app.controller('ValidateAppCtrl', ['$scope','$http', function($scope, $http){
	var loc = OC.generateUrl('apps/gtu/api/agreement');
	$scope.status = '';
	$scope.notValidated =  true;
	$scope.show = false;
	
	// http://stackoverflow.com/a/16387215/1306771
	$http.get(loc).
		success( function(data, status, headers, config){
			$scope.status = 'En attente de validation';
			$scope.gtu =data;
			$scope.show = true;
		}).
    	error( function(data, status, headers, config) {
      		$scope.status = 'error getting last GTU';
    	}
    );



	$scope.validate = function() {
		$scope.status = 'Validation en cours';
		$http.post(loc).
			success( function(data, status, headers, config){
				$scope.status = 'Validation enregistrée';
				$scope.notValidated =  false;
			}).
    		error( function(data, status, headers, config) {
      			$scope.status = 'Validation error';
    		});
	}
}]);