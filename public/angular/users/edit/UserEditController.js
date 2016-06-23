angular.module('app.useredit', [])

.controller('UserEditController', function($scope, Restangular, $state, $http, $upload){


	var user = Restangular.one('user').get().$object;

	$scope.user = user;

	$scope.saveUser = function(){
		$http.put('/api/v1/user', $scope.user);
		$state.reload();
		$scope.alerts.push({
            content: 'You have successfully updated your user information.',
            animation: 'fadeZoomFadeDown',
            type: 'success',
            duration: 3
        });
	};


	$scope.upload = function(){

		 $upload.upload({
		  url: "https://api.cloudinary.com/v1_1/world-lens/upload",
		  data: {upload_preset: 'gxramofi'},
		  file: $scope.file,
		  skipAuthorization: true
		}).success(function (data, status, headers, config) {
			$scope.user['avatar'] = data['url'];
		  if(!$scope.$$phase) {
		    $scope.$apply();
		  }
		}).error(function(data, status, headers, config){
		  console.log('error' + status + ' data' + headers);
		});

	};
	

});
