export default {
    props: {
        body: null,
        type: null,
        wait: null
    },

    computed: {
        isActive() {
            return this.message;
        },
    },

    /**
     * Created event of component.
     */
    created() {
        if (this.body) {
            this.show(this.body);
        }

        this.watch();
    },

    data() {
        return {
            /**
             * Message text.
             *
             * @type {string}
             */
            message: this.body,

            /**
             * Delay for displaying message.
             *
             * @type {integer}
             */
            delay: this.wait,

            /**
             * Theme (color) of the message.
             *
             * @type {string}
             */
            theme: this.type
        }
    },

    methods: {
        /**
         * Listen notifiation event.
         */
        watch() {
            events.$on('flash', this.show);
        },

        /**
         * Show flash notification.
         *
         * @param  {string} options.message
         * @param  {string} options.type
         * @param  {integer} options.wait
         */
        show({ message, type = this.type, wait = this.wait }) {
            this.setMessage(message);
            this.setTheme(type);
            this.setDelay(wait);

            this.hide();
        },

        /**
         * Hide flash notification.
         */
        hide() {
            setTimeout(() => {
                this.reset();
            }, this.delay || 0);
        },

        /**
         * Restore default configurations.
         */
        reset() {
            this.setMessage(this.body);
        },

        /**
         * Setter for message.
         */
        setMessage(message) {
            this.message = message;
        },

        /**
         * Setter for theme property.
         */
        setTheme(theme) {
            this.theme = theme;
        },

        /**
         * Setter for delay.
         */
        setDelay(delay) {
            this.delay = delay;
        },
    }
}
