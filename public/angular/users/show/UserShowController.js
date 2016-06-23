angular.module('app.usershow', [])

.controller('UserShowController', function($scope, $stateParams, Restangular){

	var User = Restangular.one('user', $stateParams['id']);

	var getUser = function(){
		$scope.user = User.get().$object;
	};

	var initialize = function(){
		getUser();
	};

	initialize();

});
