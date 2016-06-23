angular.module('app.room', [])
.controller('RoomController', function($scope, $stateParams, Restangular, $state){

  $scope.checkin  = new Date(parseInt($stateParams['checkin']));
  $scope.checkout = new Date(parseInt($stateParams['checkout']));
  
  var listing = Restangular.one('listings', $stateParams['id']);
	
  var getList = function(){
		$scope.list = listing.get().$object;
  };

  $scope.getTotalPrice = function(){
    var oneDay = 24*60*60*1000;
    return Math.round(Math.abs(($scope.checkin - $scope.checkout)) / (oneDay)) * $scope.list['price_cents'];
  }


  $scope.book = function(){
    var booking = {
      'listings_id': $stateParams['id'],
      'checkin': Date.parse($scope.checkin),
      'checkout': Date.parse($scope.checkout),
      'host_id': $scope.list['user_id'],
      'status' : 'Pending',
      'total'  : $scope.getTotalPrice()
    };

    var Bookings = Restangular.all('bookings');
    Bookings.post(booking);

    $state.go('trips', {'success': 'true'});
  }

  $scope.open = function($event, open) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope[open] = true;
  };
  

  var initialize = function(){
    getList();
  };

  initialize();
});
