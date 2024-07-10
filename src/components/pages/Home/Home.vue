<template>
  <div>
    <AppNavigation />
    <AppHero :heroImage="homeImage">
      <b-container slot="content">
        <b-row>
          <b-col lg="5" class="order-1 order-lg-0">
            <HomeBookingForm />
          </b-col>
          <b-col lg="7" class="mb-5 mb-sm-0">
            <h1>{{ $t("home.title") }}</h1>
            <h4>{{phone}}</h4>
          </b-col>
        </b-row>
      </b-container>
    </AppHero>
    <BaseSectionTitle :title="$t('home.sections.ourVehicle')" />
    <HomeVehicleSlider />
    <BaseSectionTitle :title="$t('home.sections.ourServices')" />
    <HomeServiceSlider />
    <BaseSectionTitle :title="$t('home.sections.testimonials')" />
    <HomeTestimonialSlider />
    <AppFooter />
  </div>
</template>


<script>
import AppHero from "@/components/ui/Layout/AppHero.vue";

import HomeBookingForm from "./HomeBookingForm";
import HomeVehicleSlider from "./HomeVehicleSlider";
import HomeServiceSlider from "./HomeServiceSlider";
import HomeTestimonialSlider from "./HomeTestimonialSlider";
import api from "@/api";
export default {
  name: "App",
  components: {
    AppHero,
    HomeVehicleSlider,
    HomeServiceSlider,
    HomeTestimonialSlider,
    HomeBookingForm
  },
  data() {
    return {
      phone: ""
    };
  },
  computed: {
    homeImage() {
      return this.$store.state.heroImage;
    }
  },
  mounted() {
    this.axios.get(`${api.url}/company-info`).then(response => {
      this.phone = response.data.company_phone;
    });
  }
};
</script>

<style lang="scss">
@import "@/assets/scss/global.scss";
</style>