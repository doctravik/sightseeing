import { mapActions, mapGetters, mapMutations } from 'vuex';
import UrlParser from './../helpers/UrlParser';
import QueryString from './../helpers/QueryString';
import Pagination from './../pagination/components/Pagination.vue';

export default {
    computed: {
        /**
         * Import getters from vuex;
         */
        ...mapGetters({
            places: 'places/places',
            filters: 'places/filters',
            paginator: 'places/paginator'
        }),

        hasPaginator() {
            return this.paginator && this.places.length > 0;
        }
    },

    data() {
        return {
            showFilters: false
        }
    },

    mounted() {
        this.init();
        this.listenHistoryApi();
    },

    methods: {
        /**
         * Import mutations from vuex;
         */
        ...mapMutations({
            addFilter: 'places/addFilter',
            setFilters: 'places/setFilters',
            removeFilter: 'places/removeFilter',
        }),

        /**
         * Init Octopus.
         */
        init() {
            this.transformQueriesToFilters();
            this.fetchItemsFromDb();
        },

        /**
         * Listen popstate event of History Api.
         * It provides functionality for'Back' and 'Forward' buttons of web browser during ajax requests.
         *
         * @return {void}
         */
        listenHistoryApi() {
            window.addEventListener('popstate', e => {
                this.init();
            });
        },

        /**
         * Fetch data for the list from db
         * Custom implentetation for the specific entity.
         *
         * @return {void}
         */
        fetchItemsFromDb() {
            //
        },

        /**
         * Apply filters.
         */
        applyFilters() {
            this.removeFilter('page');
            this.transformFiltersToQueries();
            this.fetchItemsFromDb();
        },

        /**
         * Fetch data from the db for the given page.
         *
         * @param  {int} page
         */
        paginate(page) {
            this.addFilter(['page', page]);
            this.transformFiltersToQueries();
            this.fetchItemsFromDb();
        },

        /**
         * Copy url queries to the filters.
         * Synchronize ajax request's params with url queries after reload page.
         */
        transformQueriesToFilters() {
            this.setFilters(
                new UrlParser(location.search).getParams()
            );
        },

        /**
         * Copy filters to the url params.
         * Push params to the history state (History Api).
         * Synchronize url queries with ajax request's params.
         */
        transformFiltersToQueries() {
            let queries = QueryString.from(this.filters);

            if (queries) {
                history.pushState(null, null, `?${queries}`);
            } else {
                history.pushState(null, null, location.href.split("?")[0]);
            }
        },

        /**
         * Show or hide filters menu
         */
        toggleFilters() {
            this.showFilters ? this.showFilters = false : this.showFilters = true;
        },
    },

    components: { Pagination }
}
