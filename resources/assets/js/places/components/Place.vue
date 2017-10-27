<template>
    <article class="media">
        <figure class="media-left is-visible-tablet margin-right-2" ref="image">
            <p class="image width-256">
                <img v-if="place.image" :src="place.image" alt="Place image">
                <img v-else :src="template" alt="Image template">
            </p>
        </figure>
        <div class="media-content">
        <div class="content">
            <div class="level is-mobile is-top">
                <div class="level-left">
                    <p>
                        <a :href="'/places/' + place.slug" class="has-text-info">
                            <strong class="is-size-5" v-text="place.name"></strong>
                        </a>
                        <br>
                        <span class="has-text-grey" v-text="place.address"></span>
                    </p>
                </div>
                <div class="level-right" v-if="place && $root.isAuthenticated">
                    <favorite :place="place"></favorite>
                </div>
            </div>

            <p v-if="place.distance">
                <span class="tag is-success">
                    <b v-text="place.distance"></b><span>&nbsp;km to the search point</span>
                </span>
            </p>

            <p class="image width-256 is-hidden-tablet">
                <img :src="place.image" alt="Place image">
            </p>

            <p>
                <ellipsis :text="place.description" max="1000" :isEscaped="false"></ellipsis>
            </p>

            <p>
                <a href="#" @click.prevent="showPlaceOnMap=true">View</a>
                <place-preview-map :place="place" :is-active="showPlaceOnMap"
                    @hide="showPlaceOnMap=false">
                </place-preview-map>

                <span v-if="isAuthorized">
                    <span>&nbsp;&bullet;&nbsp;</span>
                    <a href="#" @click.prevent="showPlaceUpdateModal=true">Edit</a>
                    <place-update-content :place="place" :is-active="isAuthorized && showPlaceUpdateModal"
                        @hide="showPlaceUpdateModal=false">
                    </place-update-content>

                    <span>&nbsp;&bullet;&nbsp;</span>
                    <a href="#" @click.prevent="showImageUpdateModal=true">Upload</a>
                    <place-update-image :place="place" :is-active="isAuthorized && showImageUpdateModal"
                        @hide="showImageUpdateModal=false">
                    </place-update-image>

                    <span>&nbsp;&bullet;&nbsp;</span>
                    <a href="#" @click.prevent="destroyPlace()">Delete</a>
                </span>
            </p>
          </div>
        </div>
    </article>
</template>

<script>
    import Favorite from './Favorite';
    import { mapActions } from 'vuex';
    import PlacePreviewMap from './PlacePreviewMap';
    import PlaceUpdateImage from './PlaceUpdateImage';
    import Ellipsis from './../../components/Ellipsis';
    import PlaceUpdateContent from './PlaceUpdateContent';

    export default {
        props: ['place'],

        computed: {
            isAuthorized() {
                return this.authorize('isAdmin') || this.authorize('isAuthor', this.place);
            },

            template() {
                return this.$root.storage + 'app/template.svg';
            }
        },

        data() {
            return {
                showPlaceOnMap: false,
                showPlaceUpdateModal: false,
                showImageUpdateModal: false,
            }
        },

        methods: {
            ...mapActions({
                deletePlace: 'places/deletePlace'
            }),

            destroyPlace() {
                this.deletePlace(this.place.slug).then(response => {
                    flash('Place was succefully deleted!');
                }).catch(errors => {
                    flash(errors.response.data.message, 'is-danger');
                });
            },
        },

        components: { PlacePreviewMap, PlaceUpdateContent, PlaceUpdateImage, Ellipsis, Favorite }
    }
</script>
