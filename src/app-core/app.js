/***********************************************************************************
MAIN MODULE
***********************************************************************************/

var databaseManager = angular.module('databaseManager', ['ngRoute']);

/***********************************************************************************
ROUTING
***********************************************************************************/

databaseManager.config(function($routeProvider){
	$routeProvider.when('/companies', {
		templateUrl: 'src/styling/templates/companies/companies.html'
	}).when('/mailing', {
		templateUrl: 'src/styling/templates/mailing/mailing.html'
	}).otherwise({
		redirectTo: '/'
	})
})

/***********************************************************************************
SETUP CONTROLLER / LANGUAGE & LOGIN
***********************************************************************************/

databaseManager.controller('setupCtrl', function($scope, $http, $window){

	$scope.showContent = false;

	var cachedLanguageData;

	if(cachedLanguageData){
		$scope.language = cachedLanguageData;
	} else {
		$http({
			method: 'GET',
			url: 'settings/language.json'
		}).success(function(data){
			cachedLanguageData = data;
			$scope.language = data;
		})
	}

	$scope.login = function(input){
		$http({
			method: 'POST',
			url: 'src/app-core/login.php',
			data: input
		}).success(function(data){
			if(data == 1){
				$scope.showContent = true;
				$window.location.href = $window.location.href + 'companies';
			} else {
				alert($scope.language.main.alert.loginunsuccessful);
			}
		})
	}

	$scope.logout = function(){

		$scope.showContent = false;

		$http({
			method: 'GET',
			url: 'src/app-core/logout.php',
		}).success(function(){
			$window.location.href = '';
		})

	}


});