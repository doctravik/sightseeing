<template>
    <div>
        <button v-if="isFavorite" class="button is-warning" @click="unlike" title="Remove from favorites">
            <span class="icon is-small">
                <i class="fa fa-star" aria-hidden="true"></i>
            </span>
        </button>
        <button v-else class="button is-light" @click="like" title="Add to favorites">
            <span class="icon is-small">
                <i class="fa fa-star" aria-hidden="true"></i>
            </span>
        </button>
    </div>
</template>

<script>
    import api from './../../helpers/Api';

    export default {
        props: ['place'],

        created() {
            this.isFavorite = this.isLiked();
        },

        data() {
            return {
                isFavorite: false,
                endpoint: '/api/places/' + this.place.slug + '/favorites'
            }
        },

        methods: {
            isLiked() {
                return this.place.users.filter(user => {
                    return this.$root.user.id == user.id;
                }).length > 0;
            },

            like() {
                api.post(this.endpoint)
                    .then(response => {
                        this.isFavorite = true;
                    });
            },

            unlike() {
                api.delete(this.endpoint)
                    .then(response => {
                        this.isFavorite = false;
                    });
            }
        }
    }
</script>
