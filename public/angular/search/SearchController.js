

angular.module('app.search', ['rzModule'])

.controller('SearchController', function($scope, $stateParams, $state, Restangular){

  $scope.location = $stateParams['location'];
  $scope.checkin  = new Date(parseInt($stateParams['checkin']));
  $scope.checkout = new Date(parseInt($stateParams['checkout']));

  $scope.checkPlace = function(id){
    $state.go('listings/:id', {'id': id, 'checkin': Date.parse($scope.checkin), 'checkout': Date.parse($scope.checkout)});
	}

  $scope.changeDate = function(loc, checkin, checkout){
    $state.go('search/:location', {
      'location': loc,
      'checkin' : Date.parse(checkin),
      'checkout' : Date.parse(checkout)
    });
  }

	//$scope.results = Restangular.one('search', $scope.location, $stateParams['checkin'], $stateParams['checkout']).getList().$object;
  $scope.results = Restangular.one('search').one('location', $scope.location).one('checkin', $stateParams['checkin']).one('checkout', $stateParams['checkout']).getList().$object;


  $scope.priceSlider = {
    min: 0,
    max: 1000,
    ceil: 1000,
    floor: 0
	};

  $scope.open = function($event, open) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope[open] = true;
  };

  $scope.translate = function(value) {
    return '$' + value;
	}

  $scope.filterRoomType = function(type){
    $scope.home_type = type;
  }

  $scope.min = function(actual, expected) {
    return actual >= expected;
  };

  $scope.max = function(actual, expected) {
    return actual <= expected;
  };

});
