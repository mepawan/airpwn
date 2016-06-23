angular.module('app.editlisting', ['ui.calendar'])
.controller('EditListController', function($scope, $state, $http, $upload, $stateParams, $rootScope, $anchorScroll, $location, Restangular, uiCalendarConfig){

  var id = $stateParams['id'];
  var getList = function(){
		$scope.list = Restangular.one('listings', id).get().$object;
	};

  var bookings = function(){
      $http.get('api/v1/bookings/' + id).success(function(data){
        $scope.bookings = data;
      });
  }

  $scope.list = {
    'images': []
  };


  $scope.scrollTo = function(id){
    $location.hash(id);
    $anchorScroll();
  }

  $scope.updateList = function(){
    if (!id){
      console.log($scope.list);
      var Listings = Restangular.all('listings');
      Listings.post($scope.list);
      $location.path('/listings');
    } else {
      $http.put('api/v1/listings/' + id, $scope.list);
      $state.reload();
    }
  }
  
  $scope.$watch('files', function() {
      if (!$scope.files) return;
      $scope.files.forEach(function(file){
        $scope.upload = $upload.upload({
          url: "https://api.cloudinary.com/v1_1/world-lens/image/upload",
          data: {upload_preset: 'gxramofi'},
          file: file,
          skipAuthorization: true
        }).progress(function (e) {
          file.progress = Math.round((e.loaded * 100.0) / e.total);
          file.status = "Uploading... " + file.progress + "%";
          if(!$scope.$$phase) {
            $scope.$apply();
          }
        }).success(function (data, status, headers, config) {
          $scope.list.images.push(data)
          if(!$scope.$$phase) {
            $scope.$apply();
          }
        }).error(function(data, status, headers, config){
          console.log('error');
        });
      });
  });

  $scope.removeImage = function(index){
    $scope.list.images.splice(index, 1);
  }

  $scope.getLocation = function(val) {
      return $.ajax('http://maps.googleapis.com/maps/api/geocode/json?address=' + val + '&sensor=false', {
        method: 'GET',
        type: 'json'
      }).then(function(response){
        return response.results.map(function(item){
          return item.formatted_address;
        });
      });
  };

  /* Modify the look and fill of the dropzone when files are being dragged over it */
  $scope.dragOverClass = function($event) {
      var items = $event.dataTransfer.items;
      var hasFile = false;
      if (items != null) {
        for (var i = 0 ; i < items.length; i++) {
          if (items[i].kind == 'file') {
            hasFile = true;
            break;
          }
        }
      } else {
        hasFile = true;
      }
      return hasFile ? "dragover" : "dragover-err";
    };

    function renderCalendar(){

        $('#calendar').fullCalendar({

            height: 450,
            editable: true,
            header:{
              left: 'title',
              center: '',
              right: 'today prev,next'
            },

            selectable: true,
            select: function(start, end, jsEvent, view){
              var url = location.hash.slice(1);
              var id = url.substring(url.lastIndexOf('/') + 1);
              $http.post('/api/v1/listings/block/' + id, {
                checkin: start._d,
                checkout: end._d
              });
              location.reload();
            },
            events: function(start, end, timezone, callback) {
                var url = location.hash.slice(1);
                var id = url.substring(url.lastIndexOf('/') + 1);
                $.ajax({
                    method: 'GET',
                    url: 'api/v1/bookings/' + id,
                    dataType: 'json',
                    success: function(doc) {
                        var events = [];
                        $(doc).each(function() {
                            if ($(this).attr('status') == 'Booked'){
                               events.push({
                                start: new Date(parseInt($(this).attr('checkin'))).toUTCString(),
                                end: new Date(parseInt($(this).attr('checkout'))).toUTCString(),
                                title: $(this).attr('name'),
                                url: '#/inbox/' + $(this).attr('id'),
                                allDay: true
                              });
                            } else if ($(this).attr('status') == 'Blocked') {
                              events.push({
                                start: new Date(parseInt($(this).attr('checkin'))).toUTCString(),
                                end: new Date(parseInt($(this).attr('checkout'))).toUTCString(),
                                title: $(this).attr('status'),
                                allDay: true
                              });
                            }
                           
                        });
                        callback(events);
                    }
                });
            },
          eventClick: function(calEvent, jsEvent, view) {
              if (calEvent.title == 'Blocked'){
                console.log(calEvent);
                console.log(calEvent.start._id);
                console.log(view);
                $(this).css('border-color', 'red');
              }
          }
      });
    }

  var initialize = function(){
    renderCalendar();
    if (id) {
      $scope.status = true;
      getList();
    }
  };

  initialize();
});