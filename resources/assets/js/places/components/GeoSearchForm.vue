<template>
    <geo-place-filters :show="true"
        @apply="fetchSights"
        @preview-place="previewPlace">
    </geo-place-filters>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';
    import QueryString from './../../helpers/QueryString';
    import GeoPlaceFilters from './../filters/GeoPlaceFilters';

    export default {
        computed: {
            ...mapGetters({
                filters: 'places/filters'
            })
        },

        methods: {
            ...mapMutations({
                setErrors: 'places/setErrors',
                clearErrors: 'places/clearErrors'
            }),

            fetchSights(filters) {
                let qs = QueryString.from(this.filters);

                axios.get('/api/geo-search' +  (qs ? `?${qs}` : ''))
                    .then(response => {
                        this.$emit('set-sights', response.data.data);
                        this.clearErrors();
                    }).catch(errors => {
                        this.setErrors(errors.response.data.errors);
                    });
            },

            previewPlace(place) {
                this.$emit('preview-place', place);
            }
        },

         components: { GeoPlaceFilters }
    }
</script>
