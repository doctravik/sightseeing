import Form from './../helpers/Form';

export default {
    computed: {
        errors() {
            return this.form.errors;
        },
    },

    data() {
        return {
            form: new Form({}),
        }
    },

    methods: {
        /**
         * Reset form's values.
         */
        resetForm() {
            this.resetErrors();
            this.resetHtmlForm();
        },

        /**
         * Clear all errors.
         */
        resetErrors() {
            this.errors.clear();
        },

        /**
         * Restore html form element's default value.
         * Usefull for restoring file input value
         * and ensuring proper working of onchange event of file input.
         */
        resetHtmlForm() {
            if (this.$refs.form) {
                this.$refs.form.reset();
            }
        },
    },
}
