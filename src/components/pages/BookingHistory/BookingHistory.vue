<template>
  <div>
    <AppNavigation />
    <section class="booking-scoped">
      <b-container>
        <h3 class="text-center font-weight-bold mb-4 text-capitalize">
          {{ $t("nav.bookingHistory") }}
        </h3>
        <b-form-group>
          <!-- <h6>Search Bookings by date</h6> -->
          <date-range-picker ref="picker" v-model="dateRange" opens="center">
            <div
              style="
                min-width: 300px;
                margin: 0;
                margin-top: 2px;
                text-align: center;
                display: block;
              "
              slot="input"
              slot-scope="picker"
            >
              <div
                v-if="picker.startDate"
                class="d-flex justify-content-center"
              >
                <span
                  >{{ picker.startDate | moment(dateFormat) }} &nbsp;To &nbsp;
                  {{ picker.endDate | moment(dateFormat) }}</span
                >
                <span
                  class="reset-icon d-flex justify-content-center align-items-center"
                  style="
                    z-index: 10;
                    position: absolute;
                    bottom: 0px;
                    right: 15px;
                    cursor: pointer;
                    height: 100%;
                  "
                  @click="resetSearch"
                >
                  <i class="icon fleet-close"></i>
                </span>
              </div>
              <div v-else style="text-align: center">
                {{ $t("bookingHistory.searchText") }}
              </div>
            </div>
          </date-range-picker>
        </b-form-group>

        <b-row class="mt-5">
          <b-col sm="12">
            <!-- Search results -->
            <div v-if="searchResults.length">
              <h6 class="mb-5">
                <h1>{{ $t("bookingHistory.searchResult") }}</h1>
                {{ $t("bookingHistory.searchTitle") }}
                {{ dateRange.startDate | moment(dateFormat) }}
                {{ $t("bookingHistory.to") }}
                {{ dateRange.endDate | moment(dateFormat) }}
              </h6>
              <BookingHistoryItem
                v-for="booking in searchResults"
                :id="booking.id"
                :payments="paymentTypes"
                :key="booking.id"
                :date="booking.created_date_formatted"
                :time="booking.created_time"
                :journey_date="booking.journey_date"
                :journey_time="booking.journey_time"
                :from="booking.pickup_addr"
                :to="booking.dest_addr"
                :tripTime="booking.time"
                :kilometers="booking.distance"
                :amount="booking.amount"
                :persons="booking.no_of_persons"
                :vehicleType="booking.vehicle_type"
                :status="booking.ride_status"
                :receipt="booking.receipt"
                :paid="booking.paid"
              />
            </div>
            <!-- shows no booking found when search is not found, second condition is to check whether no bookings only displayed after user has selected some value -->
            <div v-if="notFound">
              <h6 class="mb-5 text-center">No bookings found</h6>
            </div>
            <!-- All bookings / shown by default -->
            <div v-if="showAll">
              <BookingHistoryItem
                v-for="booking in BookingHistory"
                :id="booking.id"
                :payments="paymentTypes"
                :key="booking.id"
                :date="booking.created_date_formatted"
                :journey_date="booking.journey_date"
                :journey_time="booking.journey_time"
                :time="booking.created_time"
                :from="booking.pickup_addr"
                :to="booking.dest_addr"
                :tripTime="booking.time"
                :kilometers="booking.distance"
                :amount="booking.amount"
                :persons="booking.no_of_persons"
                :vehicleType="booking.vehicle_type"
                :status="booking.ride_status"
                :receipt="booking.receipt"
                :paid="booking.paid"
              />
            </div>
          </b-col>
        </b-row>
      </b-container>
    </section>
    <AppFooter />
  </div>
</template>

<script>
import api from "@/api.js";
import BaseInput from "@/components/ui/Elements/BaseInput.vue";
import BookingHistoryItem from "./BookingHistoryItem.vue";
// DateRange picker
import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
export default {
  components: {
    DateRangePicker,
    BaseInput,
    BookingHistoryItem,
  },
  data() {
    return {
      BookingHistory: [],
      paymentTypes: [],
      dateFormat: null,
      showAll: true,
      notFound: false,
      dateRange: {
        startDate: "",
        endDate: "",
      },
    };
  },
  methods: {
    getPaymentTypes() {
      this.axios.get(`${api.url}/payment-methods`).then((res) => {
        console.log(res.data);
        this.paymentTypes = res.data;
      });
    },
    resetSearch() {
      this.dateRange.startDate = "";
      this.dateRange.endDate = "";
      this.showAll = true;
      this.notFound = false;
    },
  },
  watch: {
    searchResults: function (value, old) {
      if (value.length == 0 && this.dateRange.startDate > 1) {
        this.notFound = true;
      } else if (value.length >= 1) {
        this.showAll = false;
        this.notFound = false;
      }
    },
    notFound: function (value, old) {
      if (value == true) {
        this.showAll = false;
      }
    },
  },
  computed: {
    // Returns bookings array filtered by created_date from the given dateRange
    searchResults() {
      return this.BookingHistory.filter((item) => {
        let format = this.dateFormat;
        let date = item.created_date_formatted;
        let formatted = moment(date, format).isBetween(
          moment(this.dateRange.startDate, format),
          moment(this.dateRange.endDate, format),
          null,
          "[]"
        );
        let same = moment(date, format).isSame(
          moment(this.dateRange.startDate, format),
          "day"
        );
        // console.log(formatted);
        return formatted + same;
      });
    },
  },
  created() {
    this.getPaymentTypes();
    //
    this.axios
      .post(`${api.url}/booking-history/${this.$store.state.user_id}`, {
        api_token: this.$store.state.api_token,
      })
      .then((response) => {
        console.log(response.data);
        this.BookingHistory = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    this.axios
      .get(`${api.url}/company-info`, {
        // api_token: this.$store.state.api_token
      })
      .then((response) => {
        this.dateFormat = response.data.date_format;
        // console.log(response.data.date_format);
      })
      .catch(function (error) {
        console.log(error);
      });
  },
  // updated() {
  //   this.checkEmpty();
  // }
};
</script>

<style lang="scss">
.booking-scoped {
  margin: 150px 0;
}

.vue-daterange-picker {
  margin: 0 auto;
  display: block !important;
  max-width: 350px;
}
.search-box {
  max-width: 600px;
  margin: 0 auto;
}
</style>
