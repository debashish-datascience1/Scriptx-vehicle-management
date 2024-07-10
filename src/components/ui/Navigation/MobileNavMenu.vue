<template>
  <div>
    <div class="menu">
      <div class="menu-container">
        <div class="logo">
          <img :src="logo" />
        </div>
        <div class="menu-items">
          <ul>
            <router-link to="/">
              <li @click="$store.commit('toggleActiveLeft')">{{ $t("nav.home") }}</li>
            </router-link>
            <router-link to="/about">
              <li @click="$store.commit('toggleActiveLeft')">{{ $t("nav.about") }}</li>
            </router-link>
            <router-link to="/contact">
              <li @click="$store.commit('toggleActiveLeft')">{{ $t("nav.contact") }}</li>
            </router-link>
          </ul>
        </div>
        <div class="close" @click="$store.commit('toggleActiveLeft')">
          <i class="icon fleet-close"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    logo() {
      return this.$store.state.logo;
    }
  }
};
</script>

<style lang="scss" scoped>
.menu {
  height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  padding: 20px 40px;
  position: relative;
}
.logo {
  margin-bottom: 40px;
}

.menu-items li {
  list-style-type: none;
  padding-left: 20px;
  margin-bottom: 60px;
  font-weight: 500;
}

.close {
  position: absolute;
  right: 40px;
  top: 40px;
}
</style>
