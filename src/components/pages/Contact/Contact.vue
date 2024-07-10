<template>
  <div>
    <AppNavigation />
    <!-- HEADER -->
    <AppHero :heroImage="contactImage">
      <div class="container hero-container" slot="content">
        <div class="hero-content-overlay">
          <h1 class="font-weight-bold">{{ $t("contact.title") }}</h1>
          <h6 class="medium regular">{{ $t("contact.subTitle") }}</h6>
        </div>
      </div>
    </AppHero>
    <!--  -->
    <b-container fluid class="p-0">
      <div class="row no-gutters contact-block-row">
        <div class="col-xl-3 col-sm-6">
          <div class="contact-block">
            <i class="icon fleet-headphone"></i>
            <h6 class="contact-block_title">{{company_info.customer_support}}</h6>
            <p class="contact-block_label">{{ $t("contact.customerCare") }}</p>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="contact-block">
            <i class="icon fleet-book"></i>
            <h6 class="contact-block_title">{{ $t("contact.haveQuery") }}</h6>
            <p class="contact-block_label">
              {{ $t("contact.articles") }}
              <a
                :href="company_info.faq_link"
                class="link"
              >{{$t("contact.clickHere") }}</a>
            </p>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="contact-block">
            <i class="icon fleet-mail"></i>
            <h6 class="contact-block_title">{{company_info.contact_email}}</h6>
            <p class="contact-block_label">{{$t("contact.email") }}</p>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="contact-block">
            <i class="icon fleet-steering"></i>
            <h6 class="contact-block_title">{{$t("contact.driveFleet") }}</h6>
            <p class="contact-block_label">
              {{$t("contact.join") }}
              <a
                :href="company_info.driver_login_url"
                class="link"
              >{{$t("contact.clickHere") }}</a>
            </p>
          </div>
        </div>
      </div>
    </b-container>
    <b-container class="my-5">
      <BaseSectionTitle :title="$t('contact.form.title')" />
      <form action @submit.prevent="sendResponse">
        <h6
          class="text-success font-weight-bold text-center mb-4"
          v-if="responseSent"
        >{{$t("contact.formSuccess")}}</h6>
        <div class="contact-form">
          <div class="row">
            <div class="col-sm-6">
              <BaseInput
                :label="$t('form.name')"
                name="name"
                v-model="name"
                data-vv-name="name"
                v-validate="'required'"
              >
                <slot>
                  <span v-show="errors.has('name')" class="error">{{$t("formErrors.required")}}</span>
                </slot>
              </BaseInput>
            </div>
            <div class="col-sm-6">
              <BaseInput
                :label="$t('form.email')"
                name="email"
                v-model="email"
                data-vv-name="email"
                v-validate="'required|email'"
              >
                <slot>
                  <span v-show="errors.has('email')" class="error">{{$t("formErrors.required")}}</span>
                </slot>
              </BaseInput>
            </div>
            <div class="col-sm-12">
              <BaseTextarea
                :label="$t('form.message')"
                name="message"
                v-model="message"
                v-validate="'required'"
                data-vv-name="message"
                :rows="3"
              >
                <slot>
                  <span v-show="errors.has('message')" class="error">{{$t("formErrors.required")}}</span>
                </slot>
              </BaseTextarea>
              <BaseButton :label="$t('form.send')" />
            </div>
          </div>
        </div>
      </form>
    </b-container>

    <!-- <section class="my-5 pb-5">
      <div style="width: 100%;position: relative;">
        <iframe
          width="100%"
          height="500"
          src="https://maps.google.com/maps?width=100&amp;height=500&amp;hl=en&amp;q=hyvikk%20solutions+(hyvikk%20solutions)&amp;ie=UTF8&amp;t=p&amp;z=16&amp;iwloc=B&amp;output=embed"
          frameborder="0"
          scrolling="no"
          marginheight="0"
          marginwidth="0"
        ></iframe>
      </div>
    </section>-->
    <!-- <GmapMap
      ref="mapRef"
      :center="{lat:this.locationData.lat, lng:this.locationData.lng  }"
      :zoom="15"
      map-type-id="terrain"
      style="width: 100%; height: 500px"
    >
      <GmapMarker ref="myMarker" :position="locationData" :clickable="true" />
    </GmapMap>-->
    <iframe
      v-if="company_info.gmap_api_key"
      width="100%"
      height="500"
      frameborder="0"
      style="border:0"
      :src="mapUrl"
      allowfullscreen
    ></iframe>
    <AppFooter />
  </div>
</template>

<script>
import api from "@/api.js";
import AppHero from "@/components/ui/Layout/AppHero";
import BaseInput from "@/components/ui/Elements/BaseInput";
import BaseTextarea from "@/components/ui/Elements/BaseTextarea";
import BaseButton from "@/components/ui/Elements/BaseButton";
import { encode } from "punycode";
export default {
  data() {
    return {
      name: "",
      email: "",
      message: "",
      address: "hyvikk solution bhavnagar",
      responseSent: null,
      locationData: {},
      company_info: {}
    };
  },
  created() {
    /// COMPANY INFORMATION API
    this.axios
      .get(`${api.url}/company-info`)
      .then(response => {
        this.company_info = response.data;
      })
      .catch(error => {
        console.log(error);
      });
  },
  methods: {
    sendResponse() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.axios
            .post(`${api.url}/message-us`, {
              name: this.name,
              email: this.email,
              message: this.message
            })
            .then(response => {
              this.responseSent = true;
              [this.name, this.email, this.message] = "";
            });
        } else {
        }
      });
    }
  },
  components: {
    AppHero,
    BaseInput,
    BaseTextarea,
    BaseButton
  },

  computed: {
    contactImage() {
      return this.$store.state.contactHero;
    },
    mapUrl() {
      let api = this.company_info.gmap_api_key;
      let adr = this.company_info.company_address;
      let addr = encodeURI(adr);
      let url = `https://www.google.com/maps/embed/v1/place?key=${api}&q=${adr}`;
      return url;
    }
  }
};
</script>


<style lang="scss">
</style>

<style lang="scss" scoped>
.hero-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: stretch;
  color: white;
}

.hero-content-overlay,
.hero-content-overlay--light {
  transform: translateY(-100px);
  width: 110%;
  text-align: center;
  padding: 30px 10px;
  position: relative;
  z-index: 1;
  @include for-size(mobile) {
    width: 108%;
  }
}
.hero-content-overlay::after,
.hero-content-overlay--light::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  background-image: url("../../../assets/images/fleet-hero-gradient.png");
  background-size: cover;
  background-position: center;
  z-index: -1;
  opacity: 0.3;
}

.contact-block {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 20px 40px;
  background-color: rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
  i {
    font-size: 60px;
    color: #34404b;
    opacity: 0.7;
  }
  &_title {
    font-size: 22px;
    margin-top: 15px;
    font-weight: 600;
  }
  &_label {
    font-size: 14px;
    opacity: 0.8;
  }
  &:hover {
    background-color: rgba(255, 255, 255, 0.5);
  }
}

.contact-block-row {
  position: relative;
  margin-top: -180px;

  @include for-size(laptop) {
    margin-top: 40px;
  }
}

.contact-form {
  max-width: 620px;
  margin: 0 auto;
}
</style>
