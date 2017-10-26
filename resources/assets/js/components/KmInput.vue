<template>
    <input ref="input" :value="value"
        @input="update($event.target.value)"
        @focus="select"
        @blur="format">
</template>

<script>
    export default {
        props: ['value'],

        methods: {
            /**
             * Update component value.
             *
             * @param  {String} value
             */
            update(value) {
                this.$emit('input', this.setValue(this.toNumber(value)));
            },

            /**
             * Select input text
             *
             * @param  {Event} event
             */
            select: function (event) {
                setTimeout(() => {
                    this.setValue(this.value);
                    event.target.select()
                }, 0)
            },

            /**
             * Apply formatting to the input text.
             */
            format() {
                if (this.value) {
                    this.setValue(this.value + ' km');
                }
            },

            /**
             * Update input's value.
             *
             * @param {String} value
             */
            setValue(value) {
                if (value) {
                    return this.$refs.input.value = value;
                } else {
                    return this.$refs.input.value = ''
                }
            },

            /**
             * Filtering input. Replace not number value to the empty string.
             *
             * @param  {String} value
             * @return {String}
             */
            toNumber(value) {
                if (value) {
                    return value.replace(/[^\d]+/gi, '');
                }

                return value;
            }
        }
    }
</script>
