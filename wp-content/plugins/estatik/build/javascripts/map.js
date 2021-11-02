( function() {
    'use strict';

    /**
     * Google map class.
     *
     * @param selector
     * @param lon
     * @param lat
     * @param zoom
     * @returns {EsGoogleMap}
     */
    var EsGoogleMap = function(selector, lon, lat, zoom) {
        this.selector = selector;
        this.zoom = zoom || 16;
        this.lon = parseFloat(lon);
        this.lat = parseFloat(lat);
        this.markers = [];

        /**
         * Initialize google maps method.
         * @return object
         */
        this.init = function() {

            this.instance = new google.maps.Map(this.selector, {
                zoom: this.zoom,
                center: {lat: this.lat, lng: this.lon}
            });

            google.maps.event.trigger(this.selector, 'resize');

            return this;
        };

        /**
         *
         * Set marker to the map.
         *
         * @param position
         * @param draggable
         * @returns {EsGoogleMap}
         */
        this.setMarker = function(position, draggable) {
            position = {lat: this.lat, lng: this.lon} || position;
            draggable = draggable || false;

            var marker = new google.maps.Marker({
                position: position,
                map: this.instance,
                draggable: draggable
            });

            if ( draggable ) {
                var geocoder = new google.maps.Geocoder();

                google.maps.event.addListener( marker, 'dragend', function() {
                    geocoder.geocode( {'latLng': marker.getPosition() }, function (results, status) {
                        if ( status === google.maps.GeocoderStatus.OK ) {
                            if ( results[0] ) {
                                document.querySelector('#es-latitude-input').value = marker.getPosition().lat();
                                document.querySelector('#es-longitude-input').value = marker.getPosition().lng();
                                document.querySelector('#es-address-input').value = results[0].formatted_address;
                                document.querySelector('#es-address_components-input').value = JSON.stringify( results[0].address_components );
                            }
                        }
                    } );
                });
            }


            this.markers.push( marker );

            return this;
        };

        /**
         *
         * Get address info using coordinates.
         *
         * @param lat
         * @param lon
         * @param callback
         */
        this.getGeocoderInfo = function(lat, lon, callback) {
            var geocoder = new google.maps.Geocoder();
            var latLon = new google.maps.LatLng(lat, lon);

            geocoder.geocode({
                latLng: latLon
            }, callback);
        };

        return this;
    };

    window.EsGoogleMap = EsGoogleMap;
} )();
