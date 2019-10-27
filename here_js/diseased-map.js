var app_id = 'UdRH6PlISTlADYsW6mzl';
    app_code = 'lfrrTheP9nBedeJyy1NtIA';

var onError  = () => {}
var platform = new H.service.Platform({
    app_id  : app_id,
    app_code: app_code,
    useHTTPS: true
});

var pixelRatio    = window.devicePixelRatio || 1;
var defaultLayers = platform.createDefaultLayers({
    lg      : 'rus',
    tileSize: pixelRatio === 1 ? 256      : 512,
    ppi     : pixelRatio === 1 ? undefined: 320
});

var map = new H.Map(
    document.getElementById('here-map'),
    defaultLayers.normal.map,
    {
      zoom  : 8,
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
var pano        = ui.getControl('panorama');

var group = new H.map.Group();
map.addObject(group);

group.addEventListener('tap', function (evt) {
    if(!!functionOnDataGet) {
        functionOnDataGet(evt);
    }
});

function addPlaceToMap(diseased) {
    let position;
    let icon     = new H.map.DomIcon(`<div style="
        margin-top : -50px;
        margin-left: -25px;
        width      : 50px;
        height     : 50px;
        background : url('${!!diseased.avatar ? diseased.avatar : "http://hackathon.tw1.ru/here_js/map_marker.png"}') no-repeat center / cover;" 
        class="circle responsive-img"></div>`);
    position = {
        lat: diseased.locationHash.latitude[2],
        lng: diseased.locationHash.longitude[2]
    };

    let marker      = new H.map.DomMarker(position, {icon: icon});
        marker.data = diseased;
    group.addObject(marker);
}

fetch(`http://hackathon.tw1.ru/diseased/get-requests.php`).then(res => {
    return res.json();
}).then(json => {
    for(let i in json.data) {
        json.data[i].locationHash = decodeGeoHash(json.data[i].locationHash)
        
        addPlaceToMap(json.data[i]);
    }
});

var functionOnDataGet;

const initHereMap = (onMarkerClick, center) => {
    functionOnDataGet = onMarkerClick;
    map.setCenter(center);
}