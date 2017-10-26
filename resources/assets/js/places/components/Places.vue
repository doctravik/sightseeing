<template>
    <div>
        <div class="panel panel-cs">
            <div class="panel-heading">
                <place-filters :is-requesting="isRequesting"
                    @apply="applyFilters">
                </place-filters>
            </div>
            <div class="panel-block">
                <place-list></place-list>
            </div>
        </div>

        <pagination v-if="hasPaginator" :paginator="paginator"
            @update="paginate">
        </pagination>
    </div>
</template>

<script>
    import { mapActions, mapMutations } from 'vuex';

    import PlaceList from './PlaceList';
    import Octopus from './../../mixins/Octopus';
    import PlaceFilters from './../filters/PlaceFilters';

    export default {
        data() {
            return {
                isRequesting: false
            }
        },

        methods: {
            ...mapActions({
                getPlaces: 'places/getPlaces'
            }),

            ...mapMutations({
                setErrors: 'places/setErrors',
                clearErrors: 'places/clearErrors'
            }),

            fetchItemsFromDb() {
                this.isRequesting = true;
                this.getPlaces().then(response => {
                    this.isRequesting = false;
                    this.clearErrors();
                }).catch(errors => {
                    this.isRequesting = false;
                    this.setErrors(errors.response.data.errors);
                });
            }
        },

        components: {
            PlaceList,
            PlaceFilters,
        },

        mixins: [ Octopus ]
    }
</script>
