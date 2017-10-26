<template>
    <div class=" filter__tags">
        <div class="field is-grouped is-grouped-multiline level">
            <slot>
                <reset-filter filter="author" label="Author" @reset="reset"></reset-filter>

                <reset-filter :filter="['latitude', 'longitude', 'distance']"
                    v-if="filters.latitude && filters.longitude" label="Address & distance">
                </reset-filter>

                <reset-filter :filter="['latitude', 'longitude']" label="Address"></reset-filter>
                <reset-filter filter="distance" label="Distance"></reset-filter>
                <reset-filter filter="name" label="Name" @reset="reset"></reset-filter>
                <reset-filter filter="country" label="Country" @reset="reset"></reset-filter>
                <reset-filter filter="sort" label="Sort" @reset="reset"></reset-filter>

                <reset-filter :filter="removedFilters" label="Clear all filters" type="link"
                    @reset="reset">
                </reset-filter>
            </slot>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import ResetFilter from './../../components/ResetFilter';

    export default {
        computed: {
            ...mapGetters({
                errors: 'places/errors',
                filters: 'places/filters'
            }),
        },

        data() {
            return {
                removedFilters: [
                    'author',
                    'latitude',
                    'longitude',
                    'distance',
                    'country',
                    'name',
                    'sort'
                ]
            }
        },

        methods: {
            reset() {
                this.$emit('reset');
            },
        },

        components: {ResetFilter}
    }
</script>
