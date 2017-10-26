<template>
    <div class="modal" :class="{ 'is-active': isActive }">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Place info</p>
                <button class="delete" aria-label="close" @click="hide"></button>
            </header>
            <section class="modal-card-body">
                <div class="columns">
                    <div class="column is-8">
                        <p class="title is-3" v-text="form.name"></p>
                        <p class="subtitle is-5" v-text="form.address"></p>
                        <p v-text="form.description"></p>
                    </div>
                    <div class="column is-4">
                        <slot name="image"></slot>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" @click="submit">Save</button>
                <button class="button" @click="hide">Cancel</button>
            </footer>
        </div>
    </div>
</template>

<script>
    import Modal from './../mixins/Modal';

    export default {
        props: ['form'],

        mixins: [Modal]
    }
</script>
