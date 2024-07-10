<template>
  <div class="vld-parent">
    <loading
      :active.sync="isLoading"
      :can-cancel="false"
      :is-full-page="false"
      :opacity="0.8"
    ></loading>
    <div class="form my-4 ">
      <!-- Booking status -->
      <div
        class="alert alert-success"
        v-if="bookingSuccess === true"
        role="alert"
      >
        {{ $t("form.bookSuccess") }}
      </div>
      <div
        class="alert alert-danger"
        v-if="bookingSuccess === false"
        role="alert"
      >
        {{ $t("form.bookFail") }}
      </div>

      <!-- /Booking status -->
      <!-- Booking Form -->
      <form action @submit.prevent="book" class="booking-form">
        <BaseInput
          :label="$t('home.form.pickupAddr')"
          :iconClass="'fleet-pickup'"
          :placeholder="''"
          v-model="pickup_addr"
          v-validate="'required'"
          data-vv-name="pickup"
          id="pickup_addr"
        >
          <slot>
            <span v-show="errors.has('pickup')" class="error">
              {{ $t("formErrors.required") }}
            </span>
          </slot>
        </BaseInput>
        <BaseInput
          :label="$t('home.form.dropAddr')"
          :iconClass="'fleet-drop'"
          v-model="dest_addr"
          :placeholder="''"
          v-validate="'required'"
          name="dest"
          data-vv-name="dest"
          id="dest_addr"
        >
          <slot>
            <span v-show="errors.has('dest')" class="error">
              {{ $t("formErrors.required") }}
            </span>
          </slot>
        </BaseInput>
        <BaseInput
          :type="'number'"
          :min="0"
          :label="$t('home.form.noPerson')"
          :iconClass="'fleet-person'"
          v-model="no_of_person"
          v-validate="'required'"
          name="person"
        >
          <slot>
            <span v-show="errors.has('person')" class="error">
              {{ $t("formErrors.required") }}
            </span>
          </slot>
        </BaseInput>
        <div class="form-input">
          <v-select
            class="vue-select"
            v-model="vehicle"
            :searchable="false"
            :placeholder="$t('home.form.selectVehicle')"
            label="vehicle_type"
            :options="vehicles"
            name="vehicles"
            v-validate="'required'"
          ></v-select>
          <span v-show="errors.has('vehicles')" class="error">
            {{ $t("formErrors.required") }}
          </span>
        </div>
        <BaseTextarea
          :label="$t('home.form.other')"
          :rows="3"
          v-model="remarks"
        />
        <!-- Submit -->
        <button type="submit" class="form-action-button">
          {{ $t("home.form.book") }}
          <i class="icon fleet-arrow-right"></i>
        </button>
      </form>
      <!-- /Booking Form -->
    </div>
  </div>
</template>

<script>
import api from "@/api.js";
import auth from "@/auth.js";
import BaseInput from "@/components/ui/Elements/BaseInput";
import BaseTextarea from "@/components/ui/Elements/BaseTextarea";
import BaseButton from "@/components/ui/Elements/BaseButton";
import Loading from "vue-loading-overlay";

import "vue-loading-overlay/dist/vue-loading.css";

export default {
  components: {
    BaseInput,
    BaseTextarea,
    BaseButton,
    Loading,
  },
  data() {
    return {
      bookingSuccess: null,
      pickup_addr: "",
      dest_addr: "",
      no_of_person: "",
      vehicle: "",
      remarks: "",
      vehicles: [],
      isLoading: false,
    };
  },
  mounted() {
    this.axios.get(`${api.url}/vehicle-types`).then((response) => {
      this.isLoading = true;
      // console.log(response);
      this.vehicles = response.data;
      this.isLoading = false;
    });
    if (this.$route.params.getSession) {
      const getSession = async () => {
        this.pickup_addr = await sessionStorage.getItem("pickupAddr");
        this.dest_addr = await sessionStorage.getItem("destAddr");
        this.no_of_person = await sessionStorage.getItem("persons");
        this.vehicle = await JSON.parse(sessionStorage.getItem("vehicleType"));
        this.remarks = await sessionStorage.getItem("remarks");
        this.booking_date = await new Date(
          sessionStorage.getItem("booking_date")
        );
        this.time = await sessionStorage.getItem("time");
      };
      getSession();
    }
    if (api.map.length) {
      this.$loadScript(
        `https://maps.googleapis.com/maps/api/js?key=${
          api.map
        }&libraries=places`
      )
        .then(() => {
          var pickup_addr = document.getElementById("pickup_addr");
          var dest_addr = document.getElementById("dest_addr");

          this.autocomplete = new google.maps.places.Autocomplete(pickup_addr);
          this.autocomplete2 = new google.maps.places.Autocomplete(dest_addr);

          this.autocomplete.addListener("place_changed", () => {
            let place = this.autocomplete.getPlace();
            this.pickup_addr = place.formatted_address;
          });
          this.autocomplete2.addListener("place_changed", () => {
            let place = this.autocomplete2.getPlace();
            this.dest_addr = place.formatted_address;
          });
        })
        .catch(() => {
          console.log("Google maps api not loaded properly.");
        });
    }

    //
  },

  methods: {
    book() {
      if (auth.checkAuth()) {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.isLoading = true;
            this.axios
              .post(`${api.url}/book-now`, {
                booking_type: 0,
                source_address: this.pickup_addr,
                dest_address: this.dest_addr,
                no_of_persons: this.no_of_person,
                vehicle_typeid: this.vehicle.id,
                user_id: this.$store.state.user_id,
                note: this.remarks,
                api_token: this.$store.state.api_token,
              })
              .then((response) => {
                if (response.data.success === "1") {
                  this.bookingSuccess = true;
                  [
                    this.pickup_addr,
                    this.dest_addr,
                    this.no_of_person,
                    this.vehicle,
                    this.remarks,
                  ] = "";
                  this.isLoading = false;
                } else {
                  this.bookingSuccess = false;
                  this.isLoading = false;
                }
              });
          } else {
            this.isLoading = false;
          }
        });
      } else {
        sessionStorage.setItem("pickupAddr", this.pickup_addr);
        sessionStorage.setItem("destAddr", this.dest_addr);
        sessionStorage.setItem("persons", this.no_of_person);
        sessionStorage.setItem("vehicleType", JSON.stringify(this.vehicle));
        sessionStorage.setItem("remarks", this.remarks);

        this.isLoading = false;
        this.$router.push({
          name: "login",
          params: { book: true },
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.alert-success {
  border-radius: 0px;
  font-weight: 600;
}
</style>
