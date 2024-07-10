import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import VueAxios from "vue-axios";
import api from "../api";

import auth from "@/auth.js";

Vue.use(Vuex);
Vue.use(VueAxios, axios);

// Data will be accessible from all the components

// Application logo

import userIcon from "@/assets/images/user.png";
import heroImage from "@/assets/images/fleet-hero.jpg";
import aboutHero from "@/assets/images/fleet-about-hero.jpg";
import contactHero from "@/assets/images/fleet-contactus-hero.jpg";

export const store = new Vuex.Store({
  state: {
    api_token: window.localStorage.getItem("token"),
    user_id: window.localStorage.getItem("user_id"),
    userLogged: auth.checkAuth(),
    leftActive: false,
    rightActive: false,
    userName: window.localStorage.getItem("username"),
    logo: "",
    userIcon: userIcon,
    heroImage: heroImage,
    aboutHero: aboutHero,
    contactHero: contactHero
    // User dropdown / userNav links
  },
  mutations: {
    // open and close mobile menus
    toggleActiveLeft: state => {
      return (state.leftActive = !state.leftActive);
    },
    toggleActiveRight: state => {
      return (state.rightActive = !state.rightActive);
    },
    login: state => {
      state.api_token = window.localStorage.getItem("token");
      state.userName = window.localStorage.getItem("username");
      state.user_id = window.localStorage.getItem("user_id");
      state.userLogged = true;
    },
    set_vehicles: (state, payload) => {
      state.vehicles = payload;
    },
    set_logo: (state, payload) => {
      state.logo = payload;
    }
  },
  actions: {
    login: (context, payload) => {
      context.commit("login", payload);
    },

    vehicles_update({ commit }) {
      axios
        .get("http://localhost/fleet-backend/frontend/vehicle-types")
        .then(response => {
          let res = response.data;
          let payload = res;
          commit("set_vehicles", payload);
        });
    },
    logo_update({ commit }) {
      axios.get(`${api.url}/company-info`).then(response => {
        let payload = response.data.company_logo;
        commit("set_logo", payload);
      });
    }
  }
});
