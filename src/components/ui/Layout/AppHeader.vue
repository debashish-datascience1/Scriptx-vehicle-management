<template>
  <header>
    <div class="container">
      <nav class="top-navbar">
        <router-link to="/" class="top-navbar_brand">
          <img :src="logo" class="d-none d-sm-block" id="logo" />
          <img :src="logo" class="d-block d-sm-none" />
        </router-link>
        <!-- Mobile toggle -->
        <button class="top-navbar_toggle toggle-open" data-target="topnav">
          <i class="fa fa-bars"></i>
        </button>
        <!-- Navigation links -->
        <div class="top-navbar_navigation" id="topnav">
          <button class="top-navbar_toggle toggle-close" data-close="topnav">
            <i class="fa fa-times"></i>
          </button>
          <ul class="top-navbar_links">
            <li class="active">
              <router-link to="/">{{ $t("nav.home") }}</router-link>
            </li>
            <li>
              <router-link to="/about">{{ $t("nav.about") }}</router-link>
            </li>
            <li>
              <router-link to="/contact">{{ $t("nav.contact") }}</router-link>
            </li>
          </ul>
        </div>
        <h6 class="medium login-text">
          <div class="user-dropdown" v-if="userLogged">
            <button
              class="ud-button p-0 d-none d-lg-flex"
              @click="toggleUd"
              v-on-clickaway="closeUd"
            >
              <span class="ud-user">
                <img :src="userIcon" alt />
              </span>
              <span class="d-none d-lg-inline-block">{{userName}}</span>
            </button>
            <div class="ud d-none d-lg-block" :class="{ active : isDropdownActive }" id="ud">
              <router-link to="booking-history" class="ud_item js-changable-icon">
                <span class="dropdown-nav-icon">
                  <i class="icon fleet-booking-history"></i>
                </span>
                {{ $t("nav.bookingHistory") }}
              </router-link>

              <a class="ud_item js-changable-icon" href @click="logout()">
                <span class="dropdown-nav-icon">
                  <i class="icon fleet-logout"></i>
                </span>
                {{ $t("nav.logout") }}
              </a>
            </div>
          </div>
          <div class="login" v-else>
            <router-link to="/login" class="d-flex">
              <span class="d-none d-lg-flex text-capitalize">{{ $t("nav.login") }}</span>
              <i class="icon fleet-arrow-right"></i>
            </router-link>
          </div>
        </h6>
      </nav>
    </div>
  </header>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";
import auth from "@/auth.js";
export default {
  mixins: [clickaway],
  data() {
    return {
      isDropdownActive: false
    };
  },
  methods: {
    toggleUd() {
      this.isDropdownActive = !this.isDropdownActive;
    },
    closeUd() {
      this.isDropdownActive = false;
    },
    logout() {
      auth.logout();
      document.location.reload();
    }
  },
  computed: {
    userLogged() {
      return this.$store.state.userLogged;
    },
    userName() {
      return this.$store.state.userName;
    },
    logo() {
      return this.$store.state.logo;
    },
    userIcon() {
      return this.$store.state.userIcon;
    }
  },
  events: {
    hideIt: function(event) {
      this.toggleUd();
    }
  },
  directives: {
    clickOutside: {
      bind() {
        this.event = event => this.vm.$emit(this.expression, event);
        this.el.addEventListener("click", this.stopProp);
        document.body.addEventListener("click", this.event);
      },
      unbind() {
        this.el.removeEventListener("click", this.stopProp);
        document.body.removeEventListener("click", this.event);
      },
      stopProp(event) {
        event.stopPropagation();
      }
    }
  }
};
</script>

<style lang="scss" scoped>
header {
  position: absolute;
  top: 0;
  left: 0;
  height: auto;
  padding-top: 10px;
  padding-bottom: 30px;
  z-index: 5;
  width: 100%;
}

#logo {
  min-width: 200px;
  max-width: 200px;
  max-height: 60px;
  object-fit: contain;
  object-position: left;
}

.top-navbar {
  @include flex-row-center();
  justify-content: space-between;
  padding: 0 0px;
  margin: 0 auto;
  height: 100%;
}

.top-navbar_toggle {
  background-color: transparent;
  border: 0;
  outline: 0;
  display: none;
}

.login-text {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 204px;
  margin-bottom: 0px;
  i {
    margin-left: 8px;
    font-size: 20px;
    color: $text-color;
    font-weight: 600;
    padding-top: 1px;
  }
}

.top-navbar_navigation {
  @include flex-row-center;
  height: 100%;
}

.top-navbar_links {
  display: flex;
  list-style-type: none;
  margin-bottom: 0px;
  height: 100%;
  padding-left: 0px;
  li {
    display: inline-block;
    height: 100%;
    padding: 0 30px;
    position: relative;
    text-transform: capitalize;

    &:hover a::before {
      width: 20%;
      opacity: 1;
    }

    .router-link-exact-active::before {
      width: 20%;
    }

    &.active {
      font-weight: 600;
    }

    a {
      @include flex-row-center;
      height: 100%;
      text-align: center;
      color: $text-color;
      font-weight: 500;
      font-size: 16px;
    }

    a::before {
      @include psuedo;
      @include horizontal-align;
      background-color: $primary-color;
      bottom: -2px;
      width: 0px;
      height: 2px;
      transition: all 0.3s ease;
      opacity: 1;
    }
  }
}

.mobile-nav-logo {
  display: none;
}

.ud-button {
  @include flex-row-center;
  background-color: transparent;
  padding: 10px;
  border-radius: 6px;
  outline: 0;
  box-shadow: none;
  border: 0;
  z-index: 95;
  position: relative;
  transition: all 0.3s ease;
}

.ud-user {
  width: 44px;
  height: 44px;
  display: inline-block;
  // background-color: white;
  // border: 1px solid #00cc37;
  border-radius: 22px;
  margin-right: 10px;
  overflow: hidden;
  img {
    width: 100%;
    height: 100%;
  }
}

.user-dropdown {
  position: relative;
}

.ud {
  background-color: white;
  border-radius: 4px;
  box-shadow: 0px 4px 16px -10px rgba(52, 64, 75, 0.5);
  position: absolute;
  top: 100%;
  left: -20px;
  width: 210px;
  height: auto;
  z-index: 99999;
  transform: translateY(10px) scale(0.9) translateZ(0);
  transition: all 0.2s ease;
  backface-visibility: hidden;
  opacity: 0;
  // visibility: hidden;

  &.active {
    opacity: 1;
  }
}

.ud_item {
  font-size: 14px;
  font-weight: 500;
  padding: 15px;
  transition: background-color 0.2s linear;
  width: 100%;
  display: block;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  i {
    font-weight: 400;
    display: inline-block;
  }
  &:hover {
    background-color: $primary-color;
    color: white;
    font-weight: 600;
    i {
      color: white;
    }
  }
}

.dropdown-nav-icon {
  margin-right: 10px;
}
</style>
