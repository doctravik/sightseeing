<template>
    <div>
        <geo-search-form @preview-place="previewPlace" @set-sights="setSights">
        </geo-search-form>

        <google-map v-if="googleApiWasLoaded" :place="place" :sights="sights"
            style="min-height: 100Vh">
        </google-map>
    </div>
</template>

<script>
    import GeoSearchForm from './GeoSearchForm';
    import GoogleMap from './../../components/Map';

    export default {
        computed: {
            googleApiWasLoaded() {
                return this.$root.googleApiWasLoaded;
            }
        },

        data() {
            return {
                place: null,
                sights: []
            }
        },

        methods: {
            previewPlace(place) {
                this.place = place;
            },

            setSights(sights) {
                this.sights = sights;
            }
        },

        components: { GeoSearchForm, GoogleMap }
    }
</script>
