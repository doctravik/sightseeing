<template>
    <div>
        <label class="label" v-if="label" v-text="label" :for="id"></label>
        <div class="control has-icons-left is-expanded">
            <div class="select is-fullwidth">
                <select :id="id" v-model="value" @change="handle">
                    <option selected value=''>Select</option>
                    <option v-for="country in countries" :value="country"
                        v-text="country">
                    </option>
                </select>
            </div>
            <div class="icon is-small is-left">
                <i class="fa fa-globe"></i>
            </div>
            <p class="help is-danger" v-if="errors.has(name)"
                v-text="errors.first(name)">
            </p>
        </div>
    </div>
</template>

<script>
    import countries from 'country-list';
    import Filter from './../../mixins/Filter';

    export default {
        data() {
            return {
                name: 'country',

                value: '',

                /**
                 * List of all countries.
                 *
                 * @type {array}
                 */
                countries: countries().getNames(),
            }
        },

        methods: {
            watch(newValue) {
                this.value = newValue ? newValue : '';
            }
        },

        mixins: [Filter]
    }
</script>
