angular.module('app', [
    'ui.bootstrap',
    'ui.router',
    'restangular',
    'index',
    'app.search',
    'app.home',
    'app.usershow',
    'satellizer',
    'app.useredit',
    'app.inbox',
    'app.listings',
    'app.reservations',
    'app.inboxdetail',
    'app.room',
    'app.editlisting',
    'app.payments',
    'cloudinary',
    'angularFileUpload',
    'angular-stripe',
    'credit-cards'
])


.config(['$stateProvider', '$urlRouterProvider', 'RestangularProvider', '$authProvider', 'stripeProvider',
    function($stateProvider, $urlRouterProvider, RestangularProvider, $authProvider, stripeProvider) {
        $stateProvider.
            state('home', {
            	url: '/',
                templateUrl: 'angular/home/_home.html',
                controller: 'HomeController'
            }).
            state('access', {
                url: '/access?redirect',
                templateUrl: 'angular/_shared/login.html'
            }).
            state('user/:id', {
            	url: '/users/:id',
            	templateUrl: 'angular/users/show/_user-detail.html',
                controller: 'UserShowController'
            }).
            state('user/edit/profile', {
                url: '/users/edit/profile',
                templateUrl: 'angular/users/edit/_user-edit.html',
                controller: 'UserEditController',
                 resolve: {
                  authenticated: authenticate
                }
            }).
            state('search/:location', {
                url: '/search/:location?checkin&checkout',
                templateUrl: 'angular/search/_search.html',
                controller: 'SearchController'
            }).
            state('inbox/:id', {
                url: '/inbox/:id',
                templateUrl: 'angular/inbox/_inbox-detail.html',
                controller: 'InboxDetailController',
                 resolve: {
                  authenticated: authenticate
                }
            }).
         
            state('listings', {
                url: '/listings',
                templateUrl: 'angular/listings/_listings.html',
                controller: 'ListingsController'
            }).
            state('listings/:id', {
                url: '/listings/:id?checkin&checkout',
                templateUrl: 'angular/listings/room/_room.html',
                controller: 'RoomController'
            }).
            state('listing/new', {
                url: '/listing/new',
                templateUrl: 'angular/listings/edit/_edit-listing.html',
                controller: 'EditListController',
                 resolve: {
                  authenticated: authenticate
                }
            }).
            state('listing/edit/:id', {
                url: '/listing/edit/:id',
                templateUrl: 'angular/listings/edit/_edit-listing.html',
                controller: 'EditListController',
                 resolve: {
                  authenticated: authenticate
                }
            }).
            state('reservations', {
                url: '/reservations',
                templateUrl: 'angular/reservations/_reservations.html',
                controller: 'ReservationsController',
                resolve: {
                  authenticated: authenticate
                }
            }).
            state('trips', {
                url: '/trips?success',
                templateUrl: 'angular/reservations/_reservations.html',
                controller: 'ReservationsController',
                resolve: {
                  authenticated: authenticate
                }
            }).
            state('payments/:id', {
                url: '/payments/:id',
                templateUrl: 'angular/payments/_payment.html',
                controller: 'PaymentController',
                resolve: {
                    authenticated: authenticate
                }
            });

        $urlRouterProvider.otherwise('/');

        RestangularProvider.setBaseUrl('/api/v1');

        stripeProvider.setPublishableKey('pk_test_Zv1o5Or0wriYZL1F5umOJeA6');

        $authProvider.facebook({
          clientId: '399588066919160'
        });

        $authProvider.google({
          clientId: '631036554609-v5hm2amv4pvico3asfi97f54sc51ji4o.apps.googleusercontent.com'
        });

        function authenticate($q, $location, $auth) {
            var deferred = $q.defer();

            if (!$auth.isAuthenticated()) {
                $location.path('/');
            } else {
                deferred.resolve();
            }

            return deferred.promise;
      }
}]);
