export default {
    data() {
        return {
            /**
             * Message.
             *
             * @type {string|null}
             */
            message: null
        }
    },

    methods: {
        /**
         * Setter for message.
         *
         * @param {string} message
         */
        setMessage(message) {
            this.message = message;
        },

        /**
         * Reset response message.
         */
        resetMessage() {
            this.setMessage(null);
        },
    }
}
