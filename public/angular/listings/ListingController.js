
angular.module('app.listings', [])

.controller('ListingsController', function($scope, Restangular){
	
	var getListings = function(){

		$scope.listings = Restangular.one('user').getList('listings').$object;

	}

	getListings();

});