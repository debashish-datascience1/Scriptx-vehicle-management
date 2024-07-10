<template>
  <div>
    <transition name="slide-left">
      <MobileNavMenu v-if="leftShown" />
    </transition>

    <transition name="slide-right">
      <MobileNavUser v-if="rightShown" />
    </transition>

    <div class="container-fluid" v-if="!leftShown && !rightShown">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center animated fadeIn slowest">
          <div class="left-trigger" @click="$store.commit('toggleActiveLeft')">
            <i class="icon fleet-arrow-left"></i>
          </div>
          <a href="index.html" class="top-navbar_brand">
            <img :src="logo" id="logo" />
          </a>
          <div class="right-trigger" @click="$store.commit('toggleActiveRight')">
            <i class="icon fleet-arrow-right"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MobileNavMenu from "./MobileNavMenu";
import MobileNavUser from "./MobileNavUser";
export default {
  components: {
    MobileNavMenu,
    MobileNavUser
  },
  computed: {
    leftShown() {
      return this.$store.state.leftActive;
    },
    rightShown() {
      return this.$store.state.rightActive;
    },
    logo() {
      return this.$store.state.logo;
    }
  }
};
</script>

<style scoped lang="scss">
.container {
  position: absolute;
  top: 0px;
  left: 50%;
  transform: translateX(-50%);
  padding-top: 15px;
  padding-bottom: 30px;
}
i:hover {
  cursor: pointer;
}

#logo {
  max-width: 200px;
  max-height: 60px;
  object-fit: contain;
}
</style>
