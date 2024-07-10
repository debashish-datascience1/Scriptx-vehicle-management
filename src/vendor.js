/* eslint-disable prettier/prettier */
// Importing vue in first line / without it file won't work
import Vue from "vue";

// Bootstrap
import BootstrapVue from "bootstrap-vue"; // Bootstrap
import "bootstrap/dist/css/bootstrap.css"; // Bootstrap
import "bootstrap-vue/dist/bootstrap-vue.css";
Vue.use(BootstrapVue);

// Custom font icons made for vue
import "./assets/fonts/style.css"; // Font icons

// Animate css
require("vue2-animate/dist/vue2-animate.min.css");

// Vue select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
Vue.component("v-select", vSelect);

// Glide slider
import VueGlide from "vue-glide-js";
import "vue-glide-js/dist/vue-glide.css";
Vue.use(VueGlide);

// Aglie slider
import VueAgile from "vue-agile";
Vue.use(VueAgile);

// Moment
window.moment = require("moment");
Vue.use(require("vue-moment"));

// Vue date time picker  ( Flatpicker )
import VueFlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
Vue.use(VueFlatPickr);

// Vee validate ( form validation plugin )
import VeeValidate from "vee-validate";
Vue.use(VeeValidate, {
    events: 'blur'
});

// Google Maps api
import * as VueGoogleMaps from "vue2-google-maps";

Vue.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyAcHjF-MpeFu_ER-B7ouTDl0wGrGK1E744",
        libraries: "places" // necessary for places input
    }
});
