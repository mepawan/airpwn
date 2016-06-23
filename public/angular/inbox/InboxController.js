angular.module('app.inbox', [])

.controller('InboxController', function($scope, $state){

	// $scope.checkInbox = function(id){
	// 	$state.go('inbox/:id', { 'id' : id });
	// }
	
	var getMessages = function(){

		$scope.messages = [
			{
				"id": "1",
				"content" : "Blah Blah blahasdf",
				"price_cents" : "193",
				"from_id" 	: "Ted",
				"unread" : "1",
				"status": "Approved",
				"created_at": "July 1"
			},
			{
				"id": "2",
				"content" : "Asd SDF sdfsdfa asdfa sf ",
				"price_cents" : "93",
				"from_id" 	: "Sarah",
				"status": "Closed",
				"unread" : "0",
				"created_at": "June 2"
			}
		]

		
	};

	getMessages();

});
