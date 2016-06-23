<!DOCTYPE html>
<html>
<head>
    <title>Ummaspot</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/vendor.css">
    <link rel="stylesheet" href="style/custom.css">
</head>
<body ng-app="app">
	<div ng-controller="index">
		<ui-view></ui-view>
		<footer>
            <section class="container copyright">
                <div class="row">
                    <div class="col-md-6">
                        Copyright Ummaspot &copy; 2015. All Rights Reserved.
                    </div>
                    <div class="col-md-4 pull-left">
                        <ul>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </section>
		</footer>
	</div>

	<!-- Application Dependencies -->
    <script src="assets/bower/jquery/dist/jquery.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <!-- jquery file upload related. only needed if jquery file upload is used
    <script src="assets/bower/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="assets/bower/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
    <script src="assets/bower/blueimp-file-upload/js/jquery.fileupload.js"></script>
     -->
    <!-- angular dependencies -->
    <script type="text/javascript" src="assets/bower/angular/angular.js"></script>
    <script type="text/javascript" src="assets/bower/angular-bootstrap/ui-bootstrap.js"></script>
    <script type="text/javascript" src="assets/bower/angular-ui-router/release/angular-ui-router.js"></script>
    <script type="text/javascript" src="assets/bower/angularjs-slider/dist/rzslider.min.js"></script>
    <script type="text/javascript" src="assets/bower/restangular/src/restangular.js"></script>
    <script type="text/javascript" src="assets/bower/satellizer/satellizer.js"></script>
    <script type="text/javascript" src="assets/bower/angular-ui-calendar/src/calendar.js"></script>
    <script type="text/javascript" src="assets/bower/angular-bootstrap/ui-bootstrap-tpls.js"></script>
    <script type="text/javascript" src="assets/bower/lodash/lodash.js"></script>
    <script type="text/javascript" src="assets/bower/moment/moment.js"></script>
    <script type="text/javascript" src="assets/bower/fullcalendar/dist/fullcalendar.js"></script>
    <script type="text/javascript" src="assets/bower/angular-stripe/release/angular-stripe.js"></script>
    <script type="text/javascript" src="assets/bower/angular-credit-cards/release/angular-credit-cards.js"></script>

    <!-- cloudinary angular plugin -->
    <script src="assets/bower/cloudinary_ng/js/angular.cloudinary.js"></script>
    
      <!-- angular f -->
    <script src="assets/bower/ng-file-upload/angular-file-upload-shim.min.js"></script>
    <!-- angular file upload -->
    <script src="assets/bower/ng-file-upload/angular-file-upload.min.js"></script> 

    <!-- Application Scripts -->
    <script type="text/javascript" src="angular/app.js"></script>
    <script type="text/javascript" src="angular/index.js"></script>
    <script type="text/javascript" src="angular/home/HomeController.js"></script>
    <script type="text/javascript" src="angular/inbox/InboxController.js"></script>
    <script type="text/javascript" src="angular/inbox/InboxDetailController.js"></script>
    <script type="text/javascript" src="angular/listings/ListingController.js"></script>
    <script type="text/javascript" src="angular/listings/edit/EditListController.js"></script>
    <script type="text/javascript" src="angular/listings/room/RoomController.js"></script>
    <script type="text/javascript" src="angular/reservations/ReservationsController.js"></script>
    <script type="text/javascript" src="angular/search/SearchController.js"></script>
	<script type="text/javascript" src="angular/users/show/UserShowController.js"></script>
	<script type="text/javascript" src="angular/users/edit/UserEditController.js"></script>
    <script type="text/javascript" src="angular/payments/PaymentController.js"></script>  
</body>
</html>