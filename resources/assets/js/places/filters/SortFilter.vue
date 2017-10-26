<template>
    <div class="level-item">
        <label v-if="label" :for="id">{{ label }}&nbsp;&nbsp;</label>
        <div class="select is-size-6">
            <select v-model="value" @change="handle" :id="id">
                <option value="country">Country</option>
                <option value="distance">Distance</option>
                <option value="name">Name</option>
                <option value="popular">Popular</option>
            </select>
        </div>
    </div>
</template>

<script>
    import Filter from './../../mixins/Filter';

    export default {
        data() {
            return {
                /**
                 * Filter's name.
                 *
                 * @type {String}
                 */
                name: 'sort',

                /**
                 * Filter's value.
                 *
                 * @type {String}
                 */
                value: 'popular',

                /**
                 * Default value.
                 *
                 * @type {String}
                 */
                default: 'popular',
            }
        },

        methods: {
            /**
             * Watch global sort filter's change.
             *
             * @param  {String} newValue
             */
            watch(newValue) {
                this.value = this.exists(newValue) ? newValue : this.default;
            },

            /**
             * Check if the sort's filter exists in the allowed list of options.
             *
             * @param  {string} filter
             * @return {boolean}
             */
            exists(filter) {
                return ['name', 'distance', 'country'].some(option => option == filter);
            }
        },

        mixins: [Filter]
    }
</script>
