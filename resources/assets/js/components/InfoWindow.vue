<template>
    <article class="content">
        <h3 class="has-text-weight-bold is-marginless" v-text="name"></h3>
        <h5 class="has-text-grey" v-text="place.address"></h5>
        <div class="media">
            <div class="media-left" v-if="place.image">
                <div class="image max-width-128">
                    <img :src="place.image" alt="Place image">
                </div>
            </div>
            <div class="media-content has-text-weight-normal">
                <p v-if="place.description">
                    <ellipsis :text="place.description" class="has-text-black" max="250"
                        :isEscaped="false" :toggled="false">
                    </ellipsis>
                </p>
                <p v-if="place.slug" class="is-flex align-center">
                    <span class="icon has-text-grey">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                    </span>
                    <a :href="'/places/' + place.slug" class="">
                        <span>Details</span>
                    </a>
                </p>
            </div>
        </div>

    </article>
</template>

<script>
    import Ellipsis from './Ellipsis';

    export default {
        props: ['place'],

        computed: {
            hasImage() {
                return !! (this.place && this.place.image);
            },

            name() {
                return this.place.name ||
                    (this.place.address_components[0] && this.place.address_components[0].long_name);
            }
        },

        mounted() {
            this.infoWindow = new google.maps.InfoWindow({content: this.$el});

            this.$emit('init', this.infoWindow);
        },

        data() {
            return {
                infoWindow: null
            }
        },

        components: {Ellipsis}
    }
</script>
