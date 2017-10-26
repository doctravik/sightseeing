<template>
    <div class="control" v-if="hasFilters">
        <div v-if="type=='link'">
            <a v-text="label" class="has-text-weight-semibold"
                @click.prevent="destroy()">
            </a>
        </div>
        <div v-else class="tags has-addons">
            <span class="tag is-grey has-text-dark" v-text="label"></span>
            <a class="tag is-delete" @click.prevent="destroy()"></a>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';

    export default {
        props: {
            /**
             * Label for filter.
             *
             * @type {String|Null}
             */
            label: null,

            /**
             * Filter's name.
             *
             * @type {String|Array|Null}
             */
            filter: null,

            /**
             * Filter's type (default - tag and link).
             *
             * @type {String|Null}
             */
            type: null,
        },

        computed: {
            ...mapGetters({
                filters: 'places/filters',
            }),

            /**
             * Normalize filter to array.
             *
             * @return {Array}
             */
            suite() {
                if (this.filter instanceof Array) {
                    return this.filter;
                } else {
                    return [this.filter];
                }
            },

            /**
             * Check if reset button should be visible (check intersections with global filter's store).
             *
             * @return {Boolean}
             */
            hasFilters() {
                return this.suite.some(filter => this.filters[filter]);
            }
        },

        methods: {
            ...mapMutations({
                resetErrors: 'places/resetErrors',
                removeFilters: 'places/removeFilters',
            }),

            /**
             * Destroy filter.
             */
            destroy() {
                this.clearError()

                this.clearFilter();

                this.reset();
            },

            /**
             * Hook to clear error.
             */
            clearError() {
                this.resetErrors(this.suite);
            },

            /**
             * Hook to remove filter/filters.
             */
            clearFilter() {
                this.removeFilters(this.suite);
            },

            /**
             * Notify parent about the destroying of the filter.
             */
            reset() {
                this.$emit('reset');
            },
        }
    }
</script>
