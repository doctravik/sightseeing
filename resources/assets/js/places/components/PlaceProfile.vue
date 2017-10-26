<template>
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <article class="content" v-if="place">
                <div class="level is-top">
                    <div class="level-left">
                        <div>
                            <h1 class="title"><b v-text="place.name"></b></h1>
                            <h3 class="subtitle has-text-grey" v-text="place.address"></h3>
                        </div>
                    </div>

                    <div class="level-right" v-if="$root.isAuthenticated">
                        <favorite v-if="place" :place="place"></favorite>
                    </div>
                </div>
                <p class="has-text-grey">
                    <span>Edited by
                        <a v-if="hasAuthor" :href="'/places?author=' + author.slug">
                            <b v-text="author.name"></b>
                        </a>
                        <b v-else class="has-text-grey-dark">guest</b>
                    </span>&nbsp;
                    <span v-text="place.updated_at"></span>
                </p>

                <p v-if="place.image">
                    <img :src="place.image" alt="Place image">
                </p>

                <p v-html="place.description"></p>
            </article>

            <google-map v-if="$root.googleApiWasLoaded" :place="place" class="height-500">
            </google-map>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite';

    export default  {
        props: ['placeSlug'],

        computed: {
            hasAuthor() {
                return !! (this.place && this.place.author);
            },

            author() {
                return this.place.author;
            }
        },

        created() {
            this.loadPlace();
        },

        data() {
            return {
                isLoading: true,
                place: null,
                endpoint: '/api/places/' + this.placeSlug
            }
        },

        methods: {
            loadPlace() {
                axios.get(this.endpoint)
                    .then(response => {
                        this.place = response.data.data;
                    }).catch(error => {
                        flash(error.response.data.flash, 'is-danger');
                    });
            }
        },

        components: {Favorite}
    }
</script>
