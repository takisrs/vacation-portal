<template>
    <header class="header">
        <img class="img-fluid header__logo" alt="Vacation Portal" :src="`${publicPath}logo.svg`">
        <h1 class="header__title">Vacation Portal</h1>
        <div class="user-data" v-if="auth">
            <span>{{ userData.userName }}</span> ({{ userData.userType == 1 ? 'user' : 'admin' }})
            <button v-if="auth" @click="logout" class="btn btn--danger ml--1">logout</button>
        </div>
    </header>
</template>

<script>
export default {
    data () {
        return {
            publicPath: process.env.BASE_URL
        }
    },
    computed: {
        auth() {
            return this.$store.getters.isAuthenticated;
        },
        userData() {
            return this.$store.getters.userData;
        },
    },

    methods: {
        logout(){
            this.$store.dispatch('logout');
        }
    },
}
</script>

<style lang="scss" scoped>
    .user-data{
        span{
            font-weight:bold;
        }
    }
</style>