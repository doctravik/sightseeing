<template>
    <transition name="drop">
        <div class="notification flash" :class="theme" v-show="isActive" v-text="message"></div>
    </transition>
</template>

<script>
    import Flash from './../mixins/Flash';

    export default {
        mixins: [Flash]
    }
</script>

<style scoped>
    .flash {
        text-align: center;
        position: fixed;
        top: 0;
        left: 0;
        opacity: .9;
        width: 100%;
    }

    .drop-enter-active, .drop-leave-active {
        transition: top .3s
    }

    .drop-enter, .drop-leave-to {
        top: -65px
    }
</style>
