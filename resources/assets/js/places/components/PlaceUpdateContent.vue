<template>
    <div class="modal" :class="{ 'is-active': isActive }">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <div class="modal-card-title">Place info</div>
                <button class="delete" aria-label="close" @click="close"></button>
            </header>
            <section class="modal-card-body">
                <div v-show="isStep(1)">
                    <div class="field">
                        <div class="control">
                            <autocomplete name="address" id="address" placeholder="Address"
                                :class="{ 'is-danger': errors.has('address') }" class="input"
                                v-model="form.address"
                                @autocomplete="autocomplete">
                            </autocomplete>
                            <p class="help is-danger" v-if="hasAddressError()"
                                v-text="getAddressError()">
                            </p>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input type="text" name="country" id="country" placeholder="Country"
                                v-model="form.country" @keyup.enter="submit"
                                :class="['input', {'is-danger': errors.has('country')}]">
                        </div>
                        <p class="help is-danger" v-if="errors.has('country')"
                            v-text="errors.first('country')">
                        </p>
                    </div>

                    <google-map v-if="googleApiWasLoaded && isActive" :place="editedPlace"></google-map>
                </div>

                <div v-show="isStep(2)">
                    <message type="is-danger" :body="message"></message>

                    <div class="field">
                        <div class="control">
                            <label class="label" for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="name" v-model="form.name"
                                class="input" :class="{ 'is-danger': errors.has('name') }" @keyup.enter="submit">
                        </div>
                        <p class="help is-danger" v-if="errors.has('name')"
                            v-text="errors.first('name')">
                        </p>
                    </div>

                    <div class="field">
                        <div class="control">
                            <label class="label" for="description">Description</label>

                            <textarea class="textarea" :class="{ 'is-danger': errors.has('description')}"
                                v-model="form.description" placeholder="description" rows="10">
                            </textarea>
                        </div>
                        <p class="help is-danger" v-if="errors.has('description')"
                            v-text="errors.first('description')">
                        </p>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-info" v-show="isStep(2)" @click="decrementStep()">Prev</button>

                <div class="margin-left-auto">
                    <button class="button is-info" v-show="isStep(1) && form.address"
                        @click="incrementStep()">Next
                    </button>
                    <button class="button" @click="close" v-show="isStep(2)">Cancel</button>
                    <button class="button is-success" @click="submit" v-show="isStep(2)">Update</button>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import Quiz from './../../helpers/Quiz';
    import Form from './../../helpers/Form';
    import Modal from './../../mixins/Modal';
    import QuizMixin from './../../mixins/Quiz';
    import PlaceForm from './../mixins/PlaceForm';
    import Message from './../../components/Message';
    import MessageMixin from './../../mixins/Message';
    import DatabasePlace from './../helpers/DatabasePlace';

    export default {
        props: ['place'],

        created() {
           this.reloadForm();
        },

        data() {
            return {
                form: new Form({
                    address: null,
                    country: null,
                    description: null,
                    name: null,
                    latitude: null,
                    longitude: null,
                }),
                editedPlace: this.place
            }
        },

        methods: {
            /**
             * Import updatePlace action from vuex.
             */
            ...mapActions({
                updatePlace: 'places/updatePlace',
                uploadImage: 'places/uploadImage',
                getPlaces: 'places/getPlaces'
            }),

            /**
             * Submit form.
             */
            submit() {
                return this.updatePlace({
                    form: this.form,
                    placeSlug: this.place.slug
                }).then(() => {
                    this.hide();
                    this.resetQuiz();
                    this.getPlaces();
                    this.resetMessage();
                }).catch(errors => {
                    this.setMessage(errors.response.data.flash)
                    this.moveQuizToError()
                });
            },

            /**
             * Move quiz to the step when first error occured.
             */
            moveQuizToError() {
                if (this.hasAddressError() || this.errors.has('country')) {
                    this.quiz.setCurrent(1);
                }
            },

            /**
             * Close modal.
             */
            close() {
                this.hide();
                this.reset();
            },

            /**
             * Reset all.
             */
            reset() {
                this.resetPlace();
                this.resetForm();
                this.resetQuiz();
                this.resetMessage();
            },

            /**
             * Change editedPlace to refresh map.
             *
             * @param {object} place
             */
            setPlace(place) {
                this.editedPlace = place;
            },

            /**
             * Fill form with default values when user cancels editing.
             */
            resetForm() {
                this.resetErrors();
                this.reloadForm();
            },

            /**
             * Back to the default (not updated) place.
             */
            resetPlace() {
                this.setPlace(this.place);
            },

            /**
             * Fill form with default place's data.
             */
            reloadForm() {
                this.fillForm(DatabasePlace.adapt(this.place));
            }
        },

        components: {Message},

        mixins: [PlaceForm, Modal, QuizMixin, MessageMixin]
    }
</script>
