<template>
    <input type="text" :value="value" @input="update($event.target.value)">
</template>

<script>
    import _debounce from 'lodash.debounce';

    export default {
        props: ['value', 'wait'],

        data() {
            return {
                delay: this.wait ? this.wait : 1000,
                isTyping: false,
                debounce: null
            }
        },

        mounted() {
            this.setDebounce();
        },

        methods: {
            update(value) {
                this.isTyping = true;
                this.$emit('input', value);
                this.debounce();
            },

            setDebounce() {
                this.debounce = _debounce(this.invoke.bind(this), this.delay);
            },

            invoke() {
                this.isTyping = false;
                this.$emit('debounce');
            }
        }
    }
</script>
