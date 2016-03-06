angular
  .module('starter.services', [])
  .constant("API_URL", "https://secure-journey-34689.herokuapp.com")

.factory('Chats', function() {
  // Might use a resource here that returns a JSON array

  // Some fake testing data
  var chats = [{
    id: 0,
    name: 'Ben Sparrow',
    lastText: 'You on your way?',
    face: 'img/ben.png'
  }, {
    id: 1,
    name: 'Max Lynx',
    lastText: 'Hey, it\'s me',
    face: 'img/max.png'
  }, {
    id: 2,
    name: 'Adam Bradleyson',
    lastText: 'I should buy a boat',
    face: 'img/adam.jpg'
  }, {
    id: 3,
    name: 'Perry Governor',
    lastText: 'Look at my mukluks!',
    face: 'img/perry.png'
  }, {
    id: 4,
    name: 'Mike Harrington',
    lastText: 'This is wicked good ice cream.',
    face: 'img/mike.png'
  }];

  return {
    all: function() {
      return chats;
    },
    remove: function(chat) {
      chats.splice(chats.indexOf(chat), 1);
    },
    get: function(chatId) {
      for (var i = 0; i < chats.length; i++) {
        if (chats[i].id === parseInt(chatId)) {
          return chats[i];
        }
      }
      return null;
    }
  };
})

.factory('Escolas', function(API_URL, $http) {
  return {
    all: function() {
      return $http.get({
        url: API_URL + "/schools.json"
      });
    }
  }
})


.factory('Events', function(API_URL, $http) {
  // Might use a resource here that returns a JSON array
  //
  // Some fake testing data
  var fakeEvent = {
    id: 0,
    name: 'Mussum ipsum cacilds',
    lastText: 'EE Escola Municipal Tim Bernners Lee',
    contentText: 'Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo.',
    date: '3 segundos',
    face: 'img/tool.png',
    map: 'img/mapa.png'
  };

  function $get(url) {
    return $http({
      url: API_URL + url,
      transformResponse: appendTransform($http.defaults.transformResponse, function(value) {
        return fixEvent(value);
      })
    });
  }

  function fixEvent(event) {
    if (Array.isArray(event)) {
      return event.map(fixEvent);
    }

    return Object.assign(event, fakeEvent, {
      id: event.id,
      name: event.title,
      contentText: event.description,
      // date: new Date()
    });
  }

  return {
    all: function() {
      return $get("/shares.json");
    },
    today: function() {
      return $get("/shares.json?created_at=" + new Date().toISOString());
    },
    popular: function() {
      return $get("/shares/popular.json");
    },
    // remove: function(event) {
    //   events.splice(events.indexOf(event), 1);
    // },
    get: function(eventId) {
      return $get("/shares/" + eventId + ".json");
    }
  };

});

function appendTransform(defaults, transform) {

  // We can't guarantee that the default transformation is an array
  defaults = angular.isArray(defaults) ? defaults : [defaults];

  // Append the new transformation to the defaults
  return defaults.concat(transform);
}
