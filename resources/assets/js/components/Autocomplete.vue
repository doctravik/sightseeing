<template>
    <input type="text" :value="value"
        @focus="initAutocomplete"
        @input="update($event.target.value)">
</template>

<script>
    export default {
        props: ['value'],

        computed: {
            /**
             * Check if google api is loaded.
             *
             * @return {Boolean}
             */
            googleApiWasLoaded() {
                return this.$root.googleApiWasLoaded;
            },

            /**
             * Check if autocomplete service of google api is loaded.
             *
             * @return {Boolean}
             */
            autocompleteWasLoaded() {
                return this.googleApiWasLoaded && !!this.autocomplete;
            },
        },

        mounted() {
            this.initAutocomplete();
        },

        data() {
            return {
                /**
                 * Google autocomplete api.
                 *
                 * @type {Object|null}
                 */
                autocomplete: null,
            }
        },

        methods: {
            /**
             * Load autocomplete.
             */
            initAutocomplete() {
                if (this.autocompleteWasLoaded) {
                    return;
                }

                if (this.googleApiWasLoaded) {
                    this.autocomplete = new google.maps.places.Autocomplete(this.$el);
                    this.autocomplete.addListener('place_changed', this.updatePlace);
                }
            },

            /**
             * Update map with new place.
             */
            updatePlace() {
                this.setPlace(this.getPlace());

                this.$emit('autocomplete', this.place);
            },

            /**
             * Fetch place from autocomplete.
             *
             * @return mixed
             */
            getPlace() {
                let place = this.autocomplete.getPlace();

                if (this.hasValidPlace(place)) {
                    return place;
                }

                return null;
            },

            /**
             * Check if the place is a geo object.
             *
             * @param  {Object} place
             * @return {Boolean}
             */
            hasValidPlace(place) {
                return !! (place && place.geometry);
            },

            /**
             * Setter for place.
             *
             * @param {Object} place
             */
            setPlace(place) {
                this.place = place;
            },

            /**
             * Update component value (custom event).
             *
             * @param  {mixed} value
             */
            update(value) {
                this.$emit('input', value);
            },
        }
    }
</script>
