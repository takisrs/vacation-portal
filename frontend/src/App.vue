<template>
  <div id="app">
    <div class="container">

      <Header/>

      <main class="wrapper" role="main">
        <div class="errors" v-if="messages.length > 0">
          <template v-for="(msg, index) in messages">
          <Alert :message="msg.message" :className="msg.class" :key="index"/>
          </template>
        </div>
        <router-view/>
      </main>

      <Footer/>

    </div>
</div>
</template>

<script>
import Alert from '@/components/Alert.vue';
import Header from '@/components/Header.vue';
import Footer from '@/components/Footer.vue';

export default {
  computed: {
    auth() {
      return this.$store.getters.isAuthenticated;
    },
    messages() {
      return this.$store.getters.messages;
    }
  },
  components: {
    Alert,
    Header,
    Footer
  },
  created(){
    if (!this.$store.getters.isAuthenticated)
      this.$store.dispatch('checkAutoLogin');
  }
}
</script>

<style lang="scss">
.wrapper{
  padding:2rem 0;
}

.errors{
  position:absolute;
  left:0;
  right:0;
  display:flex;
  align-items: center;
  flex-direction: column;
  z-index:10;
}
</style>
