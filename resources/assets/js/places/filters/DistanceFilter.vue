<template>
    <div class="control">
        <label class="label" v-if="label" v-text="label" :for="id"></label>
        <div class="control is-expanded">
            <km-input class="input" :placeholder="placeholder" :id="id"
                v-model="value" @keyup.enter="apply" @input="handle">
            </km-input>

            <p class="help is-danger" v-if="errors.has(name)"
                v-text="errors.first(name)">
            </p>
        </div>
    </div>
</template>

<script>
    import Filter from './../../mixins/Filter';
    import KmInput from './../../components/KmInput';

    export default {
        data() {
            return {
                name: 'distance',
            }
        },

        components: {KmInput},

        mixins: [Filter]
    }
</script>
