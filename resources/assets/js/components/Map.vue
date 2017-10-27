<template>
    <div class="map">
        <marker-item v-for="sight in sights" :key="sight.id" :place="sight" :map="map"
            @init="initMarker">
        </marker-item>
        <marker-item v-if="place && map" :place="place" :map="map" :icon="icon()"></marker-item>
    </div>
</template>

<script>
    import MarkerItem from './Marker';

    export default {
        props: ['place', 'sights'],

        mounted() {
            this.create();
            this.render();
            this.$watch('place', this.render);
        },

        watch: {
            sights() {
                this.resetMarkers();
            }
        },

        data() {
            return {
                map: null,
                options: {
                    center: {
                        lat: 50.450100,
                        lng: 30.523400
                    },
                    zoom: 7
                },
                markerCluster: null,
                markers: []
            }
        },

        methods: {
            /**
             * Create map.
             */
            create() {
                this.map = new google.maps.Map(this.$el, this.options);
            },

            /**
             * Render place on the map.
             */
            render() {
                if (this.place) {
                    this.map.setCenter(this.place.geometry.location);
                } else {
                    this.map.setCenter(this.options.center);
                }

                this.resetMarkers();
            },

            /**
             * Reset markers.
             */
            resetMarkers() {
                this.clearMarkers();
                this.clearMarkerCluster();
            },

            /**
             * Create custom icon.
             *
             * @return {Object}
             */
            icon() {
                return {
                    url: 'https://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png',
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                };
            },

            /**
             * Push marker to array of markers.
             *
             * @param  {Object} marker
             */
            initMarker(marker) {
                if (this.isNotUniqueMarker(marker)) {
                    this.destroyMarker(marker);
                    return;
                }

                this.markers.push(marker);

                if (this.markers.length == this.sights.length) {
                    this.clusterifyMarkers();
                }
            },

            /**
             * Hide and destroy marker on the map.
             *
             * @param  {Object} marker
             */
            destroyMarker(marker) {
                marker.setMap(null);
                marker = null;
            },

            /**
             * Check if the marker is unique.
             *
             * @param  {Object} marker
             * @return {Boolean}
             */
            isNotUniqueMarker(marker) {
                return this.markers.some(item => item.id == marker.id);
            },

            /**
             * Group markers.
             */
            clusterifyMarkers() {
                this.markerCluster = new MarkerClusterer(this.map, this.markers, {
                    imagePath: (this.$root.storage + 'app/m')
                });
            },

            /**
             * Clear marker cluster.
             */
            clearMarkerCluster() {
                if (this.markerCluster) {
                    this.markerCluster.clearMarkers();
                }
            },

            /**
             * Remove markers from the map.
             */
            clearMarkers() {
                this.markers = [];
            }
        },

        components: { MarkerItem }
    }
</script>
