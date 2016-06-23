angular.module('app.reservations', [])

.controller('ReservationsController', function($scope, Restangular, $stateParams, $location, $state){
	
	var getReservations = function(){
		$scope.results = Restangular.one('user').getList('reservations').$object;
	}

	var getTrips = function(){
		$scope.results = Restangular.one('user').getList('trips').$object;
	}

	$scope.inbox = function(id){
    	$state.go('inbox/:id', {'id': id});
	}

	$scope.request = $stateParams['success'];

	if ($location.$$url == '/reservations'){
		getReservations();
		$scope.pageName = 'Reservations';
		$scope.recipient = 'Guest';
	} else {
		getTrips();
		$scope.pageName = 'Trips';
		$scope.recipient = 'Host';
	}

});