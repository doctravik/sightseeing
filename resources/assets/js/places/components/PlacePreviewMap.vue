<template>
    <div class="modal" :class="{ 'is-active': isActive }">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <div class="modal-card-title" v-text="place.name"></div>
                <button class="delete" aria-label="close" @click="close"></button>
            </header>
            <section class="modal-card-body">
                <google-map v-if="$root.googleApiWasLoaded && isActive" :place="place"></google-map>
            </section>
            <footer class="modal-card-foot">
                <div class="margin-left-auto">
                    <button class="button is-success" @click="close">Close</button>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
    import Modal from './../../mixins/Modal';

    export default {
        props: ['place'],

        mixins: [Modal]
    }
</script>
