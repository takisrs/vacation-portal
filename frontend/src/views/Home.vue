<template>
  <div>User email: {{ userData.userEmail }}</div>
</template>

<script>
export default {
  data() {
    return {
    }
  },
  computed: {
    userData() {
      return this.$store.getters.userData;
    },
  },
  methods: {

  },
  created() {

  }

};
</script>
