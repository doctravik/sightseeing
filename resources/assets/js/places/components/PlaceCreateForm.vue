<template>
    <div class="columns">
        <div class="column is-10-tablet is-offset-1-tablet is-7-desktop is-offset-2-desktop">
            <div class="panel">
                <div class="panel-heading">
                    <div class="level is-mobile">
                        <div class="level-left">
                            <span class="has-text-black">Create new place</span>
                        </div>
                        <div class="level-right">
                            <span class="tag has-text-primary has-text-weight-normal is-medium"
                                v-text="currentStep">
                            </span>
                        </div>
                    </div>
                </div>

                <div class="panel-block">
                    <div class="control" >
                        <form ref="htmlForm">
                            <div v-show="isStep(1)">
                                <div class="field is-horizontal">
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control">
                                                <autocomplete name="address" id="address" placeholder="Address"
                                                    :class="{ 'is-danger': errors.has('address') }"
                                                    class="input"
                                                    v-model="form.address"
                                                    @autocomplete="autocomplete">
                                                </autocomplete>
                                            </div>
                                            <p class="help is-danger" v-if="hasAddressError()"
                                                v-text="getAddressError()">
                                            </p>
                                        </div>

                                        <div class="field is-narrow">
                                            <div class="control">
                                                <input type="text" name="country" id="country" placeholder="Country"
                                                    v-model="form.country" @keyup.enter="submit"
                                                    :class="['input', {'is-danger': errors.has('country')}]">
                                            </div>
                                            <p class="help is-danger" v-if="errors.has('country')"
                                                v-text="errors.first('country')">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <google-map v-if="$root.googleApiWasLoaded" :place="place"></google-map>
                            </div>

                            <div v-show="isStep(2)">
                                <div class="field">
                                    <div class="control">
                                        <label class="label" for="name">Name</label>
                                        <input type="text" name="name" id="name" placeholder="name"
                                            v-model="form.name"
                                            @keyup.enter="submit"
                                            class="input" :class="{ 'is-danger': errors.has('name') }">
                                    </div>
                                    <p class="help is-danger" v-if="errors.has('name')"
                                        v-text="errors.first('name')">
                                    </p>
                                </div>

                                <div class="field">
                                    <div class="control">
                                        <label class="label" for="description">Description</label>
                                        <textarea name="description" id="description" placeholder="description" rows="10"
                                            v-model="form.description"
                                            class="textarea" :class="{ 'is-danger': errors.has('description') }">
                                        </textarea>
                                    </div>
                                    <p class="help is-danger" v-if="errors.has('description')"
                                        v-text="errors.first('description')">
                                    </p>
                                </div>
                            </div>

                            <div v-show="isStep(3)" class="columns level">
                                <div class="column auto">
                                    <div class="field">
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
                                                        <span class="file-name" v-if="imageFileName" v-text="imageFileName"></span>
                                                        <p class="help is-danger" v-if="errors.has('image')"
                                                            v-text="errors.first('image')">
                                                        </p>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               <div class="column" v-if="form.image">
                                    <preview-image class="field" :image="form.image"></preview-image>
                                    <div class="has-text-centered">
                                        <a class="has-text-danger" @click.prevent="resetImage">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel-block">
                    <div class="control level is-mobile">
                        <div class="level-left">
                            <button class="button" v-show="hasPreviousStep()"
                                @click="decrementStep()">Prev
                            </button>
                        </div>

                        <div class="level-right">
                            <div class="level-item" v-show="hasNextStep()">
                                <button class="button"
                                    @click="incrementStep()">Next
                                </button>
                            </div>

                            <div class="level-item margin-right-1" v-show="isStep(3)">
                                <button class="button"
                                    @click="preview">Preview
                                </button>
                            </div>

                            <div class="level-item" v-show="isStep(3)">
                                <button class="button is-primary"
                                    @keyup.enter="submit" @click="submit">Create
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <modal :form="form" :is-active="showPreview" @hide="showPreview=false" @submit="submit">
                    <div slot="image">
                        <preview-image v-if="form.image" :image="form.image" class="align-start justify-start"></preview-image>
                        <div v-else class="has-text-centered">
                            <span class="icon is-large">
                                <i class="fa fa-file-image-o fa-3x" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                </modal>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import Form from './../../helpers/Form';
    import Quiz from './../../helpers/Quiz';
    import QuizMixin from './../../mixins/Quiz';
    import Modal from './../../components/Modal';
    import PlaceForm from './../mixins/PlaceForm';
    import PreviewImage from './../../components/PreviewImage';

    export default {
        computed: {
            imageFileName() {
                return this.form.image && this.form.image.name;
            },

            currentStep() {
                return 'Step ' + this.quiz.current;
            }
        },

        data() {
            return {
                place: null,
                form: new Form({
                    address: null,
                    country: null,
                    description: null,
                    name: null,
                    latitude: null,
                    longitude: null,
                    image: null
                }),
                showPreview: false,
                quiz: new Quiz(1, 3)
            }
        },

        methods: {
            ...mapActions({
                storePlace: 'places/storePlace'
            }),

            submit() {
                this.storePlace(this.form).then(() => {
                    this.reset();
                }).catch(errors => {
                    this.moveQuizToError();
                });
            },

            /**
             * Success handler.
             */
            reset() {
                this.hidePreview();
                this.resetQuiz();
                this.resetForm();
                this.resetPlace();
            },

            /**
             * Turn off preview mode.
             */
            hidePreview() {
                this.showPreview = false;
            },

            /**
             * Turn on preview mode.
             */
            preview() {
                this.showPreview = true;
            },

            /**
             * Set place null value.
             */
            resetPlace() {
                this.setPlace(null);
            },

            /**
             * Change place object to refresh map that should has appropriate watcher.
             *
             * @param {object} place
             */
            setPlace(place) {
                this.place = place;
            },

            /**
             * Reset form.
             */
            resetForm() {
                this.resetHtmlForm();
                this.form.reset();
            },

            /**
             * Restore default image.
             */
            resetImage() {
                this.resetHtmlForm();
                this.form.image = null;
                this.errors.remove('image');
            },

            /**
             * Move quiz to the step where first error occured.
             */
            moveQuizToError() {
                if (this.hasAddressError() || this.errors.has('country')) {
                    this.quiz.setCurrent(1);
                    return
                }

                if (this.errors.has('name') || this.errors.has('description')) {
                    this.quiz.setCurrent(2);
                }
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

        components: { Modal, PreviewImage },

        mixins: [PlaceForm, QuizMixin]
    }
</script>
