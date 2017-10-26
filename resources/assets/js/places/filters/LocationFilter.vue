<template>
    <div>
        <label class="label" v-if="label" v-text="label" :for="id"></label>
        <div class="control is-expanded filter__address">
            <autocomplete :id="id" :placeholder="placeholder" class="input"
                v-model="address"
                @autocomplete="applyFilter">
            </autocomplete>

            <span v-if="latitude" class="is-size-7">
                <span class="has-text-grey">latitude:&nbsp;</span>
                <span class="has-text-primary" v-text="latitude.toFixed(6) + ', '"></span>
            </span>

            <span v-if="longitude" class="is-size-7">
                <span class="has-text-grey">longitude:&nbsp;</span>
                <span class="has-text-primary" v-text="longitude.toFixed(6)"></span>
            </span>

            <p class="help is-danger" v-if="hasLocationError()"
                v-text="getLocationError()">
            </p>
        </div>
    </div>
</template>

<script>
    import Filter from './../../mixins/Filter';
    import Autocomplete from './../../components/Autocomplete';

    export default {
        watch: {
            'filters.latitude': function(newValue) {
                if (newValue) {
                    this.latitude = parseFloat(newValue);
                } else {
                    this.latitude = null;
                    this.address = null;
                }
            },

            'filters.longitude': function(newValue) {
                this.longitude = newValue ? parseFloat(newValue) : null;
            },
        },

        data() {
            return {
                address: null,
                latitude: null,
                longitude: null,
                name: 'place',
            }
        },

        methods: {
            /**
             * Apply filter.
             *
             * @param  {Object} place
             */
            applyFilter(place) {
                if (place) {
                    this.value = place;
                    this.handle();
                }
            },

            /**
             * Save filter to the global filter's store.
             */
            updateFilters() {
                this.addFilter(['longitude', this.value.geometry.location.lng()]);
                this.addFilter(['latitude', this.value.geometry.location.lat()]);
            },

            /**
             * Check if the web response has latitude ot longitude errors.
             *
             * @return {Boolean}
             */
            hasLocationError() {
                return this.errors.has('longitude')
                    || this.errors.has('latitude');
            },

            /**
             * Get latitude or longitude web response's error.
             *
             * @return {String}
             */
            getLocationError() {
                if (this.errors.has('longitude')) {
                    return this.errors.first('longitude');
                }

                if (this.errors.has('latitude')) {
                    return this.errors.first('latitude');
                }
            },
        },

        components: {Autocomplete},

        mixins: [Filter]
    }
</script>
