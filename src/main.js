import Vue from "vue";

// Router
import Routes from "./routes";
import VueRouter from "vue-router";
Vue.use(VueRouter);

const router = new VueRouter({
  // mode: "history",
  routes: Routes,
  // base: process.env.VUE_APP_PATH
});

import auth from "./auth.js";

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!auth.checkAuth()) {
      next({
        path: "/login",
        query: { redirect: to.fullPath },
      });
    } else {
      next();
    }
  } else {
    next(); // make sure to always call next()!
  }
});

// Axios
import axios from "axios";
import VueAxios from "vue-axios";
Vue.use(VueAxios, axios);

// VueX
import { store } from "./store/store";

// All vendor libraries and dependencies
import { vendor } from "./vendor.js";
import { GlobalComponents } from "./GlobalComponents.js";

function getMeta(metaName) {
  const metas = document.getElementsByTagName("meta");

  for (let i = 0; i < metas.length; i++) {
    if (metas[i].getAttribute("name") === metaName) {
      return metas[i].getAttribute("content");
    }
  }
  return "";
}

// load scripts
import LoadScript from "vue-plugin-load-script";

Vue.use(LoadScript);

// translation
import VueI18n from "vue-i18n";
import en from "./translations/en";
import es from "./translations/es";
import ar from "./translations/ar";
Vue.use(VueI18n);

const messages = {
  en: en,
  es: es,
  ar: ar,
};

const i18n = new VueI18n({
  locale: getMeta("lang"),
  fallbackLocale: "en",
  messages,
});

// end translation

// Media queries
import { MediaQueries } from "vue-media-queries";
const mediaQueries = new MediaQueries();
Vue.use(mediaQueries);

// Global mixin which stores responsive mediqqueres to use with vue-media-queries
Vue.mixin({
  data: function() {
    return {
      vsize: {
        mobile: "450px",
        tablet: "992px",
        laptop: "1250px",
      },
    };
  },
});

/// Calendar / Datepicker

import VCalendar from "v-calendar";

Vue.use(VCalendar, {
  componentPrefix: "vc",
});

import App from "./App.vue";

new Vue({
  render: (h) => h(App),
  i18n,
  store: store,
  router: router,
  mediaQueries: mediaQueries,
}).$mount("#app");

Vue.config.productionTip = false;
