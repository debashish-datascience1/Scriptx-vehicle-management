<template>
  <div id="app">
    <div class="wrapper">
      <!-- <keep-alive> -->
      <router-view></router-view>
      <!-- </keep-alive> -->
      <!-- <router-view></router-view> -->
    </div>
  </div>
</template>

<script>
import AppNavigation from "@/components/ui/Navigation/AppNavigation.vue";
import AppFooter from "@/components/ui/Layout/AppFooter.vue";
import auth from "@/auth.js";

export default {
  name: "App",
  components: {
    AppNavigation,
    AppFooter
  },
  mounted() {
    this.$store.dispatch("logo_update");
  }
};
</script>

<style lang="scss">
@import "@/assets/scss/global.scss";
</style>

