var app = angular.module('apiService', ['ngResource']);

app.factory('Gtu', ['$resource','$location', function($resource, $location){
	return $resource('/core/index.php/apps/gtu/api/', {});
}])