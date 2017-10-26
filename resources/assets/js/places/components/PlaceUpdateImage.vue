<template>
    <div class="modal" :class="{ 'is-active': isActive }">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <div class="modal-card-title">Place info</div>
                <button class="delete" aria-label="close" @click="close"></button>
            </header>
            <section class="modal-card-body">
                <form ref="form">
                    <message type="is-danger" :body="message"></message>

                    <div class="columns level">
                        <div class="column auto">
                            <div class="field">
                                <div class="control">
                                    <div class="file has-name is-boxed">
                                        <label class="file-label margin-auto">
                                            <input class="file-input" type="file" name="image" ref="image"
                                                @change="appendImage($event.target)">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="fa fa-upload"></i>
                                                </span>
                                                <span class="file-label">Choose an image…</span>
                                            </span>
                                            <span class="file-name" v-if="imageFileName"
                                                v-text="imageFileName">
                                            </span>
                                            <p class="help is-danger" v-if="errors.has('image')"
                                                v-text="errors.first('image')">
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="column" v-show="form.image || place.image">
                            <div v-if="form.image">
                                <preview-image class="field" :image="form.image"></preview-image>
                                <div class="has-text-centered">
                                    <a @click.prevent="reset">Back to default</a>
                                </div>
                            </div>
                            <div v-else class="image-preview">
                                <img :src="place.image" alt="Place photo">
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <footer class="modal-card-foot">
                <button class="button" @click="close">Cancel</button>

                <div class="margin-left-auto">
                    <button v-if="place.image" class="button is-link" @click="remove">Remove</button>
                    <button class="button is-success" @click="upload">Upload</button>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import Form from './../../helpers/Form';
    import Modal from './../../mixins/Modal';
    import FormMixin from './../../mixins/Form';
    import Message from './../../components/Message';
    import MessageMixin from './../../mixins/Message';
    import PreviewImage from './../../components/PreviewImage';

    export default {
        props: ['isActive', 'place'],

        computed: {
            googleApiWasLoaded() {
                return this.$root.googleApiWasLoaded;
            },

            imageFileName() {
                return this.form.image && this.form.image.name;
            }
        },

        data() {
            return {
                form: new Form({
                    image: null
                }),
            }
        },

        methods: {
            /**
             * Import update Place's image.
             */
            ...mapActions({
                uploadImage: 'places/uploadImage',
                deleteImage: 'places/deleteImage'
            }),

            /**
             * Upload image.
             */
            upload() {
                this.submit();
            },

            /**
             * Submit upload form.
             */
            submit() {
                return this.uploadImage({
                    form: this.form,
                    placeSlug: this.place.slug
                }).then(response => {
                    this.setImage(response.data.image);
                    this.close();
                }).catch(errors => {
                    this.setMessage(errors.response.data.flash);
                });
            },

            /**
             * Delete image from db.
             */
            remove() {
                this.deleteImage({
                        form: this.form,
                        placeSlug: this.place.slug
                    }).then(response => {
                        this.setImage(null);
                        this.close();
                    }).catch(errors => {
                        this.setMessage(errors.response.data.flash)
                    });
            },

            /**
             * Change image of place.
             *
             * @param {string} image path
             */
            setImage(image) {
                this.place.image = image;
            },

            /**
             * Close modal.
             */
            close() {
                this.hide();
                this.reset();
            },

            /**
             * Reset modal.
             */
            reset() {
                this.resetForm();
            },

            /**
             * Reset form.
             */
            resetForm() {
                this.form.reset();
                this.resetErrors();
                this.resetMessage();
                this.resetHtmlForm();
            },

            /**
             * Append image file to the form.
             *
             * @param  {HtmlElement} input
             */
            appendImage(input) {
                if (input.files && input.files[0]) {
                    this.form.image = input.files[0];
                }

                this.resetErrors();
            },
        },

        components: { PreviewImage, Message },

        mixins: [Modal, MessageMixin, FormMixin]
    }
</script>
