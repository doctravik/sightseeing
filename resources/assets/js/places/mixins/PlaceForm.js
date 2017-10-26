import Form from './../../mixins/Form';
import Autocomplete from './../../components/Autocomplete';
import DatabasePlace from './../helpers/DatabasePlace';
import AutocompletedPlace from './../helpers/AutocompletedPlace';

export default {
    computed: {
        googleApiWasLoaded() {
            return this.$root.googleApiWasLoaded;
        },
    },

    data() {
        return {
            addressErrors: ['address', 'latitude', 'longitude']
        }
    },

    methods: {
        /**
         * Handle place's autocomplete event.
         *
         * @param  {object} google map place
         */
        autocomplete(place) {
            if (place) {
                this.setPlace(place);
                this.fillForm(AutocompletedPlace.adapt(place));
            }
        },

        /**
         * Change place to refresh map (should be watched).
         *
         * @param {object} place
         */
        setPlace(place) {
            //
        },

        /**
         * Fill form with new values.
         *
         * @param  {object} place
         */
        fillForm(place) {
            for (let property in place) {
                if (this.form.hasOwnProperty(property)) {
                    this.form[property] = place[property];
                }
            }
        },

        /**
         * Check if form has location's errors during validation.
         *
         * @return {Boolean}
         */
        hasAddressError() {
            return this.addressErrors.some(error => {
                return this.errors.has(error);
            });
        },

        /**
         * Get the first validation's errors.
         *
         * @return {string}
         */
        getAddressError() {
            if (this.errors.has('address')) {
                return this.errors.first('address');
            }

            if (this.errors.has('longitude')) {
                return this.errors.first('longitude');
            }

            if (this.errors.has('latitude')) {
                return this.errors.first('latitude');
            }
        },
    },

    components: { Autocomplete },

    mixins: [Form]
}
