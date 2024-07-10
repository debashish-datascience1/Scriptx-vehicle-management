// Importing vue in first line / without it file won't work
import Vue from "vue";

////////////////////////////////// GLOBALLY DEFINED COMPONENTS ////////////////////////////////

import AppNavigation from "@/components/ui/Navigation/AppNavigation.vue";
import AppFooter from "@/components/ui/Layout/AppFooter.vue";
import BaseSectionTitle from "@/components/ui/Elements/BaseSectionTitle.vue";

Vue.component("AppNavigation", AppNavigation);
Vue.component("AppFooter", AppFooter);
Vue.component("BaseSectionTitle", BaseSectionTitle);
