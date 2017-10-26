<template>
    <div class="field">
        <label class="label" v-if="label" v-text="label" :for="id"></label>
        <div :class="['control', {'is-loading': isRequesting}]">
            <input-debounce class="input" v-model="value" :placeholder="placeholder" :id="id"
                @debounce="handle">
            </input-debounce>
        </div>
        <p class="help is-danger" v-if="errors.has(name)"
            v-text="errors.first(name)">
        </p>
    </div>
</template>

<script>
    import Filter from './../../mixins/Filter';
    import InputDebounce from './../../components/InputDebounce';

    export default {
        props: ['isRequesting'],

        data() {
            return {
                name: 'name',
            }
        },

        components: { InputDebounce },

        mixins: [Filter]
    }
</script>
