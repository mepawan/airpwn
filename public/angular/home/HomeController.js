
angular.module('app.home', [])

.controller('HomeController', function($scope, $state, $stateParams, Restangular){

	$scope.search = function(loc, checkin, checkout){
		$state.go('search/:location', {
			'location': loc,
			'checkin' : Date.parse(checkin),
			'checkout' : Date.parse(checkout)
		});
	}

  var fiveDays = new Date(new Date().getTime()+(5*24*60*60*1000));

  $scope.searchCity = function(loc){
    $state.go('search/:location', {
      'location': loc,
      'checkin': Date.parse(new Date()),
      'checkout': Date.parse(fiveDays)
    })
  }

  $scope.cities = ['Chicago', 'Los Angeles', 'New York', 'San Francisco'];

	$scope.today = function() {
    $scope.checkin = new Date();
    $scope.checkout = '';
  };

  $scope.open = function($event, open) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope[open] = true;
  };

  $scope.today();

});

