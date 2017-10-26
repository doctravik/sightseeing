<template>
    <div class="control">
        <div v-if="hasPlaces">
            <place v-for="place in places" :key="place.id" :place="place"></place>
        </div>
        <div class="is-size-5" v-else>No places were found with such constrains.</div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Place from './Place';

    export default {
        computed: {
            ...mapGetters({
                places: 'places/places'
            }),

            hasPlaces() {
                return this.places && this.places.length > 0;
            }
        },

        components: { Place }
    }
</script>
