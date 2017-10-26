export default {
    props: ['isActive'],

    methods: {
        /**
         * Close modal (hook).
         */
        close() {
            this.hide();
        },

        /**
         * Hide modal.
         */
        hide() {
            this.$emit('hide');
        },

        /**
         * Hit submit button of the modal.
         */
        submit() {
            this.$emit('submit');
        },
    }
}
