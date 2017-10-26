<template>
    <div>
        <info-window @init="initInfoWindow" :place="place" v-if="place"></info-window>
    </div>
</template>

<script>
    import InfoWindow from './InfoWindow';

    export default {
        props: ['place', 'map', 'icon'],

        mounted() {
            this.create();
            this.$watch('place', this.refresh);
        },

        /**
         * Destroy marker before component's destroying
         */
        beforeDestroy() {
            this.destroy();
        },

        data() {
            return {
                marker: null,
                infoWindow: null
            }
        },

        methods: {
            /**
             * Create marker object.
             */
            create() {
                this.marker = new google.maps.Marker({
                    icon: this.icon,
                    map: this.map,
                    title: this.place.name,
                    position: this.place.geometry.location,
                    id: this.place.id
                });

                this.marker.addListener('click', () => {
                    this.infoWindow.open(this.map, this.marker);
                });

                this.$emit('init', this.marker);
            },

            /**
             * Get info window from child component.
             *
             * @param  {Object} infoWindow
             */
            initInfoWindow(infoWindow) {
                this.infoWindow = infoWindow;
            },

            /**
             * Make marker hidden on the map.
             */
            hide() {
                this.marker.setMap(null);
            },

            /**
             * Remove marker from the map.
             */
            delete() {
                this.marker = null;
            },

            /**
             * Hide and destroy marker object
             */
            destroy() {
                this.hide();
                this.delete();
            },

            /**
             * Update marker on the map.
             */
            refresh() {
                this.destroy();
                this.create();
            }
        },

        components: { InfoWindow }
    }
</script>
