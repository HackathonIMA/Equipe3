angular.module('starter.controllers', [])

.controller('InitCtrl', function($scope, $state) {
  $scope.comecar = function() {
    $state.go('tab.events');
  };

  $scope.login = function() {
    $state.go('login');
  };
})

.controller('LoginCtrl', function($rootScope, $scope, $state) {
  $scope.login = function() {
    $rootScope.logged = true;
    $state.go('tab.events');
  };

  $scope.userRegister = function() {
    $state.go('tab.userRegister');
  };

  $scope.logout = function() {
    $rootScope.logged = false;
    $state.go('init');
  };
})

.controller('EventsCtrl', function($rootScope, $scope, $state, Events, Escolas) {
  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //

    function escolaByEvent(escolas, event) {
      escolas = escolas || [];
      var escola = null;
      for (var i = 0; i < escolas.length; i++) {
        if (escolas[i].id == event.school_id) {
          escola = escolas[i];
        }
      }

      return escola;
    }

    function escolaByEvents(escolas, events) {
      for (var i = 0; i < events.length; i++) {
        events[i].school = escolaByEvent(escolas, events[i]);
      }
    }

  $scope.$on('$ionicView.enter', function(e) {
    Events.all().success(function(data) {
      $scope.eventsRecentes = data;

      Escolas.all().success(function(data) {
        escolaByEvents(data, $scope.eventsRecentes);
      });
    });

    Events.popular().success(function(data) {
      $scope.eventsPopulares = data;
      console.log(data);

      Escolas.all().success(function(data) {
        escolaByEvents(data, $scope.eventsPopulares);
      });
    });
  });

  $scope.remove = function(events) {
    Events.remove(events);
  };

  $scope.shareRegister = function() {
    $state.go('tab.share');
  };

  $scope.userRegister = function() {
    $state.go('tab.userRegister');
  };

  $scope.share = function() {
    if ($rootScope.logged) {
      $scope.shareRegister();
    } else {
      $scope.userRegister();
    }
  };
})

.controller('EventDetailCtrl', function($scope, $stateParams, Events, Escolas) {

  function escolaByEvent(escolas, event) {
    escolas = escolas || [];
    var escola = null;
    for (var i = 0; i < escolas.length; i++) {
      if (escolas[i].id == event.school_id) {
        escola = escolas[i];
      }
    }

    return escola;
  }

  Events.get($stateParams.eventId).success(function(data) {
    $scope.event = data;

    Escolas.all().success(function(data) {
      $scope.event.school = escolaByEvent(data, $scope.event);
    });
  });
})

.controller('UserRegisterCtrl', function($rootScope, $scope, $state, Escolas) {
  $scope.$on('$ionicView.enter', function(e) {
    Escolas.all().success(function(data) {
      $scope.escolas = data;
    })
  });

  $scope.userRegister = function() {
    $rootScope.logged = true;
    $state.go('tab.events');
  };
})

.controller('ShareCtrl', function($scope, $state) {
  $scope.shareRegister = function() {
    $state.go('tab.events');
  };
});
