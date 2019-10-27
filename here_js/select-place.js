
var platform = new H.service.Platform({
    app_id  : 'UdRH6PlISTlADYsW6mzl',
    app_code: 'lfrrTheP9nBedeJyy1NtIA',
    useHTTPS: true
});

var pixelRatio    = window.devicePixelRatio || 1;
var defaultLayers = platform.createDefaultLayers({
    lg      : 'rus',
    tileSize: pixelRatio === 1 ? 256      : 512,
    ppi     : pixelRatio === 1 ? undefined: 320
});

// Instantiate (and display) a map object:
var map = new H.Map(
    document.getElementById('here-map'),
    defaultLayers.normal.map,
    {
      zoom  : 11,
      center: { lat: 44.73, lng: 37.76 }
    }, 
    {
        pixelRatio: pixelRatio
    });
    
var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

var ui = H.ui.UI.createDefault(map, defaultLayers, 'ru-RU');

var mapSettings = ui.getControl('mapsettings');
var zoom        = ui.getControl('zoom');
var scalebar    = ui.getControl('scalebar');

mapSettings.setAlignment('bottom-right');
zoom.setAlignment('bottom-right');
scalebar.setAlignment('bottom-right');

var bubble;
var groupe = new H.map.Group();
map.addObject(groupe);

function openBubble(position, text){
    if(!bubble){
        bubble =  new H.ui.InfoBubble(
        position,
        {content: text});
        ui.addBubble(bubble);
    } else {
        bubble.setPosition(position);
        bubble.setContent(text);
        bubble.open();
    }
}

map.addEventListener('tap', function (evt) {
    if(!is_focus) {
    var coord = map.screenToGeo(evt.currentPointer.viewportX,
                    evt.currentPointer.viewportY);

            if(!!bubble) {
                bubble.close();
            }

            let position = {
                lat: coord.lat,
                lng: coord.lng
            };
            AddPlacePoint(position);
                
            geocodeByCoord(platform, coord.lat, coord.lng);
    }
    is_focus = false;
});

function geocodeByCoord(platform, lat, lng) {
    if (navigator.geolocation) {

    var geocoder            = platform.getGeocodingService(),
        geocodingParameters = {
            prox      : lat + ',' + lng + ',1',
            mode      : 'retrieveAddresses',
            maxresults: 1
        };
  
    geocoder.reverseGeocode(
        geocodingParameters,
        onSuccessByCoord,
        onError
    );
    
    }
}

function onSuccessByCoord(result) {
    if (!!result.Response.View[0]) {
        var location = result.Response.View[0].Result[0];

        if(functionOnDataGet != null) {
        functionOnDataGet(location);
        }
        
        marker.label = location.Location.Address.Label;
    }
}

let is_focus = false;
    
    groupe.addEventListener('tap', function (evt) {
        openBubble(evt.target.getPosition(), evt.target.label);

        is_focus = true;
    });

let marker;

function AddPlacePoint(position) {
    if(!marker) {
    marker = new H.map.Marker(position);
    groupe.addObject(marker);
    } else {
        marker.setPosition(position);
    }
}

function onError() {}

var functionOnDataGet;

const initHereMap = (onDataGet, center) => {
    functionOnDataGet = onDataGet;
    map.setCenter(center);
}