<template>
    <div id="main">
        <div class = "tw-sticky tw-pin-t tw-z-50 tw-bg-white tw-border-b tw-border-grey-lighter tw-shadow tw-mb-4">
            <Header />
        </div>
        <div class="tw-flex tw-justify-center tw-py-10">
            <div class = "tw-container">
                <router-view></router-view>
            </div>
        </div>
        <div class = "tw-pin-b">
            <Footer />
        </div>
    </div>
</template>

<script>
    import Header from './Header.vue';
    import Footer from './Footer.vue'

    export default {
        name: 'main-app',
        components: {
            Header,
            Footer,
        }
    }
</script>