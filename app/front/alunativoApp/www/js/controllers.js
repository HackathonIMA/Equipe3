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

.controller('DashCtrl', function($scope) {})

.controller('ChatsCtrl', function($scope, Chats) {
  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //
  //$scope.$on('$ionicView.enter', function(e) {
  //});

  $scope.chats = Chats.all();
  $scope.remove = function(chat) {
    Chats.remove(chat);
  };
})

.controller('ChatDetailCtrl', function($scope, $stateParams, Chats) {
  $scope.chat = Chats.get($stateParams.chatId);
})

.controller('EventsCtrl', function($scope, Events) {
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
  // $scope.hit =
})

.controller('EventDetailCtrl', function($scope, $stateParams, Events) {
  $scope.event = Events.get($stateParams.eventId);
})


.controller('AccountCtrl', function($scope) {
  $scope.settings = {
    enableFriends: true
  };
});
