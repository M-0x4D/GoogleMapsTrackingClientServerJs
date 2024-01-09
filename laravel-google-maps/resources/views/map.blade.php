<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .text-center {
            text-align: center;
        }

        #map {
            width: "100%";
            height: 600px;
        }
    </style>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.js"></script> --}}

</head>

<body>
    <h1 class="text-center">Laravel Google Maps</h1>
    <div id="map"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.0.1/socket.io.js"></script>

    <script>
        const socket = io('http://127.0.0.1:9876');

        // Event handler for 'connect' event
        socket.on('connect', () => {
            console.log('Connected to Socket.IO server');
            socket.emit('message');
        });

        // Event handler for 'message' event
        socket.on('message', (data) => {
            const latLng = data;
            console.log('Received message:', data);

            const iconBase =
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/";
            const icons = {
                parking: {
                    icon: iconBase + "parking_lot_maps.png",
                },
                library: {
                    icon: iconBase + "library_maps.png",
                },
                info: {
                    icon: iconBase + "info-i_maps.png",
                },
            };


            function updateMarker(location) {
                // initMarker.setMap(null);
                const marker = new google.maps.Marker({
                    position: {
                        lat: location.lat,
                        lng: location.lng,
                    },
                    map: map,
                    title: 'Driver Location',
                    icon: icons.library.icon,
                    
                });

                // Center the map on the updated marker
                map.panTo(location);
                // setTimeout(() => {

                //     marker.setVisible(false); // maps API hide call
                // }, 3000);


            }

            updateMarker(latLng);

        });

        // var test = latLong;

        const googleKey = "YOUR_GOOGLE_API_KEY";
        const path = `https://maps.googleapis.com/maps/api/js?key=${googleKey}&callback=initMap`;

        // Create a new script element
        let scriptElement = document.createElement('script');

        // Set the src attribute using template literals
        scriptElement.src = `${path}`;

        // Set the async attribute
        scriptElement.async = true;

        // Append the script element to the document body
        document.body.appendChild(scriptElement);
    </script>
    <script>
        let map, activeInfoWindow, markers = [];

        /* ----------------------------- Initialize Map ----------------------------- */
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 30.0444,
                    lng: 31.2357,
                },
                zoom: 13
            });

            // replace image/svgMarker with car image while tracking
            const iconBase =
                "https://developers.google.com/maps/documentation/javascript/examples/full/images/";
            const icons = {
                parking: {
                    icon: iconBase + "parking_lot_maps.png",
                },
                library: {
                    icon: iconBase + "library_maps.png",
                },
                info: {
                    icon: iconBase + "info-i_maps.png",
                },
            };

            // const carMarker = new google.maps.Marker({
            //     position: {
            //         lat: test.lat,
            //         lng: test.lng,
            //     },
            //     map,
            //     icon: icons.library.icon,
            // });

            map.addListener("click", function(event) {
                mapClicked(event);
            });


            initMarkers();
        }

        /* --------------------------- Initialize Markers --------------------------- */
        function initMarkers() {
            const initialMarkers = <?php echo json_encode($initialMarkers); ?>;

            for (let index = 0; index < initialMarkers.length; index++) {

                const markerData = initialMarkers[index];
                const marker = new google.maps.Marker({
                    position: markerData.position,
                    label: markerData.label,
                    draggable: markerData.draggable,
                    map
                });
                markers.push(marker);

                const infowindow = new google.maps.InfoWindow({
                    content: `<b>${markerData.position.lat}, ${markerData.position.lng}</b>`,
                });
                marker.addListener("click", (event) => {
                    if (activeInfoWindow) {
                        activeInfoWindow.close();
                    }
                    infowindow.open({
                        anchor: marker,
                        shouldFocus: false,
                        map
                    });
                    activeInfoWindow = infowindow;
                    markerClicked(marker, index);
                });

                marker.addListener("dragend", (event) => {
                    markerDragEnd(event, index);
                });
            }
        }

        /* ------------------------- Handle Map Click Event ------------------------- */
        function mapClicked(event) {
            // console.log(map);
            console.log(event.latLng.lat(), event.latLng.lng());
        }

        /* ------------------------ Handle Marker Click Event ----------------------- */
        function markerClicked(marker, index) {
            // console.log(map);
            console.log(marker.position.lat());
            console.log(marker.position.lng());
        }

        /* ----------------------- Handle Marker DragEnd Event ---------------------- */
        function markerDragEnd(event, index) {
            // console.log(map);
            console.log(event.latLng.lat());
            console.log(event.latLng.lng());
        }


        // const marker = new google.maps.Marker({
        //     position: {
        //         lat: 28.625043,
        //         lng: 79.810135
        //     },
        //     label: {
        //         color: "white",
        //         text: "P4"
        //     },
        //     draggable: true,
        //     map
        // });
        // markers.push(marker);


        // const geocoder = new google.maps.Geocoder();
        // const latlng = new google.maps.LatLng(28.625043, 79.810135);
        // const request = {
        //     latLng: latlng
        // }
        // geocoder.geocode(request, results => {
        //     if (results.length) {
        //         console.log(results[0].formatted_address)
        //     }
        // });
    </script>


   

</body>

</html>
