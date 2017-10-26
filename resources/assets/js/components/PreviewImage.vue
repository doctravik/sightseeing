<template>
    <div v-if="isImage" class="image-preview">
        <img ref="viewport" alt="Place photo">
    </div>
</template>

<script>
    export default {
        props: ['image', 'alignment'],

        computed: {
            isImage() {
                return this.image && this.image.type.match('image.*');
            }

        },

        mounted() {
            this.init();
            this.read(this.image);
        },

        watch: {
            image(value) {
                this.read(value);
            }
        },


        data() {
            return {
                reader: null
            }
        },

        methods: {
            /**
             * Init FileReader object and bind event listener.
             */
            init() {
                this.reader = new FileReader();

                this.reader.onload = (e) => {
                    if (this.$refs.viewport) {
                        this.$refs.viewport.src = e.target.result;
                    }
                }
            },

            /**
             * Read image.
             *
             * @param  {string} value [image url]
             */
            read(value) {
                if (value instanceof File || value instanceof Blob) {
                   this.reader.readAsDataURL(value);
                }
            }
        }
    }
</script>
