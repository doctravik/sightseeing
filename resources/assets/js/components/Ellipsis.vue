<template>
    <span>
        <span v-if="isEscaped" v-text="content"></span>
        <span v-else v-html="content"></span>
        <a class="has-text-info" v-show="!isNormal && toggled" @click.prevent="toggle" v-text="button"></a>
    </span>
</template>

<script>
    export default {
        props: {
            /**
             * Text for handling.
             */
            text: { default: null },

            /**
             * Max length of the visible text.
             */
            max: { default: 250 },

            /**
             * Escape output or not.
             */
            isEscaped: {
                default: true,
            },

            /**
             * Check if the text can be toggled.
             */
            toggled: { default: true }
        },

        computed: {
            /**
             * Check if the text has length that is less than max.
             * No need to limit text.
             *
             * @return {Boolean}
             */
            isNormal() {
                return this.text.length <= this.max;
            },

            /**
             * Get short version of text.
             *
             * @return {String}
             */
            short() {
                if (this.isNormal) {
                    return this.text;
                }

                return this.text.substr(0, this.max) + '...';
            },

            /**
             * Get full version of text.
             *
             * @return {String}
             */
            full() {
                return this.text;
            },

            /**
             * Dynamic title of button.
             *
             * @return {String}
             */
            button() {
                return this.isShow ? 'hide' : 'show';
            },

            /**
             * Dynamic content.
             *
             * @return {String}
             */
            content() {
                return this.isShow ? this.full : this.short;
            }
        },

        data() {
            return {
                isShow: false,
            }
        },

        methods: {
            toggle() {
                this.isShow = !this.isShow;
            }
        }
    }
</script>
