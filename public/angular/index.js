
angular.module('index', [])

.controller('index', function($scope, $auth, $modal, $http, $location){

	$scope.alerts = [];
	$scope.closeAlert = function(index) {
	    $scope.alerts.splice(index, 1);
	};
	$scope.layout = 'login'

	$scope.login = function(user){
		$auth.login({email: user.email, password: user.password})
		.then(function() {
          $scope.modalInstance.dismiss('cancel');
        })
        .catch(function(response) {
          $scope.alerts.push({
            content: response.data.message,
            animation: 'fadeZoomFadeDown',
            type: 'danger',
            duration: 3
          });
        });
	}

	$scope.signup = function(user) {
      $auth.signup({
        name: user.name,
        email: user.email,
        password: user.password
      })
      .then(function(){
        $scope.alerts.push({
          type: 'success',
          content: 'Your account has been successfully created. Please log in.'
        })
      })
      .catch(function(response) {
        if (typeof response.data.message === 'object') {
          $scope.alerts.push({
            content: response.data.message,
            animation: 'fadeZoomFadeDown',
            type: 'danger',
            duration: 3
          });
        } else {
          $scope.alerts.push({
            content: response.data.message,
            animation: 'fadeZoomFadeDown',
            type: 'danger',
            duration: 3
          });
        }
      });
      $scope.layout == 'login';
  };

  $scope.logout = function(){
    $auth.logout();
    $http.get('/auth/logout');
    $location.path('/');
  }

	$scope.authenticate = function(provider){
		$auth.authenticate(provider);
	}

	$scope.isAuthenticated = function(){
	  return $auth.isAuthenticated();
	}

	// LoginModal

	$scope.cancel = function () {
	  $scope.modalInstance.dismiss('cancel');
	};

	
	$scope.loginModal = function (size) {
	  $scope.modalInstance = $modal.open({
	    animation: $scope.animationsEnabled,
	    templateUrl: 'angular/_shared/login.html',
	    size: size,
	    scope: $scope
	  });
	}; 

	// global date formats
	$scope.formats = ['dd-MMMM-yyyy', 'MM/dd/yyyy', 'dd.MM.yyyy', 'shortDate'];
  	$scope.format = $scope.formats[1];

  	$scope.dateOptions = {
    	formatYear: 'yy',
    	startingDay: 1
  	};

  	$scope.DATE_TODAY = new Date();

});