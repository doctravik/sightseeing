<template>
    <div class="search">
        <transition name="left">
            <div class="panel search-panel" v-show="isActive">
                <div class="panel-heading level is-mobile is-marginless">
                    <div class="level-left">Search sights</div>
                    <div class="level-right">
                        <a href="/" class="button">
                            <span>Back to site</span>
                        </a>
                    </div>
                </div>
                <div class="panel-block">
                    <div class="control search-form">
                        <location-filter class="field" label="Address" id="address"
                            @apply="applyLocation" placeholder="Search point">
                        </location-filter>

                        <distance-filter class="field" placeholder="km" label="Radius" id="distance">
                        </distance-filter>

                        <country-filter class="field" label="Country" id="country"
                            @apply="applyCountry">
                        </country-filter>

                        <reset-filters>
                            <reset-filter :filter="['latitude', 'longitude', 'distance']"
                                v-if="filters.latitude && filters.longitude"
                                label="Address & distance"
                                @reset="resetLocation">
                            </reset-filter>

                            <reset-filter label="Address"
                                :filter="['latitude', 'longitude']">
                            </reset-filter>

                            <reset-filter label="Distance"
                                filter="distance">
                            </reset-filter>

                            <reset-filter filter="country" label="Country"
                                @reset="resetCountry">
                            </reset-filter>

                            <reset-filter label="Clear all filters" type="link"
                                :filter="removedFilters" @reset="clearPlaces">
                            </reset-filter>
                        </reset-filters>

                        <div class="field is-grouped">
                            <div class="control">
                                <button class="button is-primary" @click="apply">Find</button>
                            </div>
                            <div class="control">
                                <button class="button is-link" @click="removeAllFilters"
                                    v-if="hasAnyFilters || errors.any()">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="btn-minimize">
            <div v-show="isActive" class="search-panel__button min" @click="toggle">
                <i class="fa fa-caret-left" aria-hidden="true"></i>
            </div>
        </transition>

        <transition name="btn-maximize">
            <div v-show="!isActive" class="search-panel__button max" @click="toggle">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
            </div>
        </transition>
    </div>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';
    import Country from './../../geo/Country';
    import Active from './../../mixins/Active';
    import geocoder from './../../geo/Geocoder';
    import ResetFilters from './ResetFilters';
    import CountryFilter from './CountryFilter';
    import LocationFilter from './LocationFilter';
    import DistanceFilter from './DistanceFilter';
    import ResetFilter from './../../components/ResetFilter';

    export default {
        computed: {
            ...mapGetters({
                errors: 'places/errors',
                filters: 'places/filters',
            }),

            hasAnyFilters() {
                return !! (this.filters.longitude || this.filters.latitude ||
                    this.filters.distance || this.filters.country);
            },
        },

        data() {
            return {
                removedFilters: ['latitude', 'longitude', 'distance', 'country'],
                locationPlace: null,
                countryPlace: null
            }
        },

        methods: {
            ...mapMutations({
                clearErrors: 'places/clearErrors',
                clearFilters: 'places/clearFilters'
            }),

            /**
             * Notify parent about apply filters.
             */
            apply() {
                this.$emit('apply');
            },

            /**
             * Handle applying location's filter.
             *
             * @param  {object} place
             */
            applyLocation(place) {
                this.locationPlace = place;
                this.preview(place);
            },

            /**
             * Handle applying country's filter.
             *
             * @param  {string} country
             */
            applyCountry(country) {
                if (country != Country.from(this.locationPlace)) {
                    this.renderCountry(country);
                }
            },

            /**
             * Render country on the map.
             *
             * @param  {string} country
             */
            renderCountry(country) {
                (new geocoder()).findByAddress(country)
                    .then(places => {
                        this.countryPlace = places[0];
                        this.preview(this.countryPlace);
                    });
            },

            /**
             * Render map with new place.
             */
            preview(place) {
                this.$emit('preview-place', place);
            },

            /**
             * Reset all filters.
             */
            removeAllFilters() {
                this.clearFilters();
                this.clearErrors();
                this.clearPlaces();
            },

            /**
             * Render location place when country filter is removed.
             */
            resetCountry() {
                this.countryPlace = null;
                this.preview(this.locationPlace);
            },

            /**
             * Render country place when location filter is removed.
             */
            resetLocation() {
                this.locationPlace = null;
                this.preview(this.countryPlace);
            },

            /**
             * Reset place values.
             */
            clearPlaces() {
                this.preview(null);
                this.countryPlace = null;
                this.locationPlace = null;
            }
        },

        components: {LocationFilter, DistanceFilter, CountryFilter, ResetFilters, ResetFilter},

        mixins: [Active]
    }
</script>

<style scoped>
    .left-enter-active, .left-leave-active {
        transition: left .3s;
    }

    .left-enter, .left-leave-to {
        left: -350px;
    }

    .btn-minimize-enter-active, .btn-minimize-leave-active {
        transition: left .3s;
    }

    .btn-minimize-enter, .btn-minimize-leave-to {
        left: 1px;
    }

    .btn-maximize-enter-active, .btn-maximize-leave-active {
        transition: left .3s;
    }

    .btn-maximize-enter, .btn-maximize-leave-to {
        left: 351px;
    }
</style>
