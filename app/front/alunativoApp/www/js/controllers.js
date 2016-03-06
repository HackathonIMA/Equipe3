angular.module('starter.controllers', [])

.controller('InitCtrl', function($scope, $state) {
    $scope.comecar = function () {
        $state.go('tab.events');
    };

    $scope.login = function () {
        $state.go('login');
    };
})

.controller('LoginCtrl', function($rootScope, $scope, $state) {
    $scope.login = function () {
        $rootScope.logged = true;
        $state.go('tab.events');
    };

     $scope.logout = function () {
        $rootScope.logged = false;
        $state.go('init');
    };
})

.controller('EventsCtrl', function($rootScope, $scope, $state, Events) {
  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //
  //$scope.$on('$ionicView.enter', function(e) {
  //});

  $scope.events = Events.all();
  $scope.remove = function(events) {
    Events.remove(events);
  };

   $scope.shareRegister = function() {
        $state.go('tab.share');
    };

  $scope.userRegister = function () {
        $state.go('tab.userRegister');
    };

  $scope.share = function() {
    if ($rootScope.logged){
        $scope.shareRegister();
    } else {
        $scope.userRegister();
    }
  };

})

.controller('EventDetailCtrl', function($scope, $stateParams, Events) {
  $scope.event = Events.get($stateParams.eventId);
})

.controller('UserRegisterCtrl', function($rootScope, $scope, $state) {
     $scope.userRegister = function () {
        $rootScope.logged = true;
        $state.go('tab.events');
    };
})

.controller('ShareCtrl', function($scope, $state) {
     $scope.shareRegister = function () {
        $state.go('tab.events');
    };
})

;
