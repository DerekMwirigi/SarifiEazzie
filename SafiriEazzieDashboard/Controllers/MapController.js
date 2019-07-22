MapController = {
    displayLocations : function (locationModels){
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: new google.maps.LatLng(-33.92, 151.25),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infowindow = new google.maps.InfoWindow()
        var marker, i
        locationModels.forEach(locationModel => {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locationModel.lat, locationModel.lon),
                map: map
            })
            //   google.maps.event.addListener(marker, 'click', (function(marker, i) {
            //     return function() {
            //       infowindow.setContent(locationModel.name);
            //       infowindow.open(map, marker);
            //     }
            //   })(marker, i));
        })
    }
}