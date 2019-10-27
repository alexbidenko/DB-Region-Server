
var platform = new H.service.Platform({
    app_id  : 'UdRH6PlISTlADYsW6mzl',
    app_code: 'lfrrTheP9nBedeJyy1NtIA',
    useHTTPS: true
});

function geocodeByCoord(platform, lat, lng) {
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

var geocodeList = [];

function onSuccessByCoord(result) {
    if (!!result.Response.View[0]) {
        var location = result.Response.View[0].Result[0];
        
        let find = false;
        for(let i in geocodeList) {
            if(geocodeList[i].county == location.Location.Address.County) {
                find = true;
                geocodeList[i].count++;
            }
        }

        if(!find) {
            geocodeList.push({
                county: location.Location.Address.County,
                count : 1
            });
        }

        let newCount = 0;
        for(let i in geocodeList) {
            newCount += geocodeList[i].count;
        }
            
        if(requestsCount == newCount) {
            functionOnDataGet(geocodeList);
        }
    }
}
function onError() {}

let requestsCount;

fetch(`http://hackathon.tw1.ru/diseased/get-requests.php?completed=true`).then(res => {
    return res.json();
}).then(json => {
    requestsCount = json.data.length;
    for(let i in json.data) {
        json.data[i].locationHash = decodeGeoHash(json.data[i].locationHash);
        geocodeByCoord(platform, json.data[i].locationHash.latitude[2], json.data[i].locationHash.longitude[2]);
    }
});

var functionOnDataGet;

const getVolRegions = (onDataGet) => {
    functionOnDataGet = onDataGet;
}