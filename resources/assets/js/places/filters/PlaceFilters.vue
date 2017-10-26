<template>
    <div>
        <div class="place-filters">
            <location-filter class="place-filter" placeholder="Address"></location-filter>

            <div class="place-filter">
                <div class="field is-grouped is-grouped-multiline">
                    <div class="field is-grouped margin-right-auto">
                        <distance-filter placeholder="Search radius, km" class="filter__distance">
                        </distance-filter>

                        <div class="control">
                            <button class="button is-primary" @click="apply">Find</button>
                        </div>
                    </div>

                    <div class="control">
                        <button class="button" @click="toggle">
                            <span class="icon">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                            </span>
                            <span>Filters</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <reset-filters @reset="apply"></reset-filters>

        <div class="level is-mobile is-marginless">
            <div class="level-left">
                <span><b v-text="placesCount"></b> places found</span>
            </div>
            <div class="level-right">
                <sort-filter id="sort" label="Sort by"
                    @apply="apply">
                </sort-filter>
            </div>
        </div>

        <transition name="drop">
            <div class="filters" v-show="isActive">
                <div class="filter">
                    <div class="columns">
                        <div class="column is-7 is-offset-2">
                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label" for="name">Name</label>
                                </div>
                                <div class="field-body">
                                    <name-filter placeholder="Place name" id="name"
                                        :is-requesting="isRequesting"
                                        @apply="apply">
                                    </name-filter>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label is-normal">
                                    <label class="label" for="country">Country</label>
                                </div>
                                <div class="field-body">
                                    <country-filter id="country" class="field"
                                        @apply="apply()">
                                    </country-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import SortFilter from './SortFilter';
    import NameFilter from './NameFilter';
    import ResetFilters from './ResetFilters';
    import Active from './../../mixins/Active';
    import CountryFilter from './CountryFilter';
    import LocationFilter from './LocationFilter';
    import DistanceFilter from './DistanceFilter';

    export default {
        props: ['errors', 'isRequesting'],

        computed: {
            ...mapGetters({
                placesCount: 'places/count',
                filters: 'places/filters'
            }),
        },

        methods: {
            apply() {
                this.$emit('apply');
            },
        },

        components: {
            LocationFilter,
            CountryFilter,
            DistanceFilter,
            NameFilter,
            SortFilter,
            ResetFilters
        },

        mixins: [Active]
    }
</script>

<style scoped>
    .drop-enter-active, .drop-leave-active {
        transition: all 0.3s ease;
    }

    .drop-enter, .drop-leave-to {
        max-height: 0;
    }
</style>
