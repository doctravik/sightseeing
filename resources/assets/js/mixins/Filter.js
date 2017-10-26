import { mapGetters, mapMutations } from 'vuex';

export default {
    props: {
        id: {
            type: String,
            default: null
        },

        label: {
            type: String,
            default: null,
        },

        placeholder: {
            type: String,
            default: null
        },
    },

    mounted() {
        this.$watch(`filters.${this.name}`, this.watch);
    },

    computed: {
        ...mapGetters({
            errors: 'places/errors',
            filters: 'places/filters'
        }),
    },

    data() {
        return {
            name: null,
            value: null
        }
    },

    methods: {
        ...mapMutations({
            addFilter: 'places/addFilter',
            removeFilter: 'places/removeFilter'
        }),

        /**
         * Watch for the filter's changing.
         *
         * @param  {String} newValue
         */
        watch(newValue) {
            this.$data.value = this.normalize(newValue);
        },

        /**
         * Hook to normalize the new filter's value.
         *
         * @param  {String} value
         * @return {String}
         */
        normalize(value) {
            return value;
        },

        /**
         * Handling the process of applying filter.
         */
        handle() {
            this.updateFilters();

            this.apply();
        },

        /**
         * Hook for customization the process of updating global filter.
         */
        updateFilters() {
            if (this.value) {
                this.addFilter([this.name, this.value]);
            } else {
                this.removeFilter(this.name);
            }
        },

        /**
         * Notification parent about immediate applying filter.
         */
        apply() {
            this.$emit('apply', this.value);
        },
    },
}
