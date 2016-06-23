angular.module('app.payments', [])

.controller('PaymentController', function($scope, $stateParams, $http, stripe, $state, Restangular){

	var getMessage = function(){
    $scope.results = Restangular.one('messages', $stateParams.id).get().$object;
	}

  $scope.payment = {
    card: {}
  }


 $scope.charge = function () {
    console.log($scope.payment);
    return stripe.card.createToken($scope.payment.card)
      .then(function (token) {
        console.log('token created for card ending in ', token.card.last4);
        var payment = angular.copy($scope.payment);
        payment.card = void 0;
        payment.token = token.id;
        payment.amount = $scope.results.total;
        payment.booking_id = $scope.results.id;
        return $http.post('/api/v1/payments', payment);
      })
      .then(function (payment) {
        $state.go('inbox', {id: $stateParams['id']});
        
      })
      .catch(function (err) {
        if (err.type && /^Stripe/.test(err.type)) {
          console.log('Stripe error: ', err.message);
        }
        else {
          console.log('Other error occurred, possibly with your API', err.message);
        }
      });
  };

	var initialize = function(){
		getMessage();
	};

	initialize();

});