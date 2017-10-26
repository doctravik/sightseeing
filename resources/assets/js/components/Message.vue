<template>
    <article v-if="isActive" class="message" :class="theme">
        <div class="message-body" v-text="message"></div>
    </article>
</template>

<script>
    import Flash from './../mixins/Flash';

    export default {
        methods: {
            /**
             * Listen notification event.
             */
            watch() {
                this.$watch('body', this.show);
                this.$watch('type', this.setTheme);
                this.$watch('delay', this.setDelay);
            },

            /**
             * Show flash notification.
             *
             * @param  {string} message
             */
            show(message) {
                this.message = message;
            },
        },

        mixins: [Flash]
    }
</script>
