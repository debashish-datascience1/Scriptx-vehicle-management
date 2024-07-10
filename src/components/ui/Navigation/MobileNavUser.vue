<template>
  <div>
    <div class="menu">
      <div class="menu-container">
        <div class="user">
          <img :src="userIcon" alt />
        </div>
        <h6 class="user_name">{{userName}}</h6>
        <hr />
        <div class="menu-items">
          <ul>
            <router-link v-if="!loggedIn" to="/login">
              <li @click="$store.commit('toggleActiveRight')">
                <span>
                  <i class="icon fleet-login"></i>
                </span>
                {{ $t("nav.login") }}
              </li>
            </router-link>
            <router-link v-if="loggedIn" to="/booking-history">
              <li @click="$store.commit('toggleActiveRight')">
                <span>
                  <i class="icon fleet-booking-history"></i>
                </span>
                {{ $t("nav.bookingHistory") }}
              </li>
            </router-link>
            <li v-if="loggedIn" @click="logout(); $store.commit('toggleActiveRight');">
              <span>
                <i class="icon fleet-logout"></i>
              </span>
              {{ $t("nav.logout") }}
            </li>
          </ul>
        </div>
        <div class="close" @click="$store.commit('toggleActiveRight')">
          <i class="icon fleet-close"></i>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import auth from "@/auth.js";
export default {
  computed: {
    userName() {
      return this.$store.state.userName;
    },
    userIcon() {
      return this.$store.state.userIcon;
    },
    logout() {
      auth.logout();
      this.$router.push("/");
      document.location.reload();
    },
    loggedIn() {
      return this.$store.state.userLogged;
    }
  }
};
</script>

<style lang="scss" scoped>
.user {
  display: inline-block;
  background-color: white;
  width: 80px;
  height: 80px;
  // border: 1px solid $primary-color;
  border-radius: 50px;
  overflow: hidden;
  img {
    width: 80px;
    height: 80px;
  }
}

.user_name {
  margin-top: 25px;
  font-weight: 600;
}

.menu {
  height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  padding: 20px 40px;
  position: relative;

  .menu-container {
    width: 100%;
  }
}

.menu-items {
  margin-top: 40px;
  li {
    list-style-type: none;
    // padding-left: 20px;
    margin-bottom: 40px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;

    i {
      font-size: 20px;
      margin-right: 10px;
    }
  }
}

.close {
  position: absolute;
  right: 40px;
  top: 40px;
}
</style>
