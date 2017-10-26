export default {
    props: {
        show: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            /**
             * @type {Boolean}
             */
            isActive: this.show,
        }
    },

    methods: {
        /**
         * Toogle active property.
         */
        toggle() {
            this.isActive = !this.isActive;
        }
    }
}
