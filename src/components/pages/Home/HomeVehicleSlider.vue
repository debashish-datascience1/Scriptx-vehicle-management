<template>
  <div>
    <!-- <b-button v-b-modal.modal-1>Launch demo modal</b-button> -->

    <b-container dir="ltr">
      <vue-glide class="row w-100 m-0 p-0" :options="options" v-if="vehicles.length" ref="slider">
        <vue-glide-slide v-for="vehicle in vehicles" :key="vehicle.id">
          <div class="vehicle-card" v-b-modal="modalId(vehicle.id)">
            <div class="vehicle-card-image">
              <img :src="vehicle.vehicle_image" alt class="vehicle-image" />
              <div class="vehicle-meta">{{vehicle.average}} / 100 mpg</div>
            </div>
            <div class="vehicle-details">
              <h5>{{vehicle.make + " " + vehicle.model}}</h5>
              <p></p>
            </div>
            <b-modal
              centered
              :id="'modal'+ vehicle.id "
              :hide-footer="true"
              :title="`${vehicle.make} ${vehicle.model}`"
              size="lg"
            >
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <img class="detail-image" :src="vehicle.vehicle_image" alt />
                </div>
                <div class="col-lg-8 col-md-12">
                  <div class="row">
                    <div class="col-lg-4 col-6">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.make")}}</p>
                        <h6>{{vehicle.make}}</h6>
                      </div>
                    </div>
                    <div class="col-lg-4 col-6">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.model")}}</p>
                        <h6>{{vehicle.model}}</h6>
                      </div>
                    </div>
                    <div class="col-lg-4 col-6">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.vehicleType")}}</p>
                        <h6>{{vehicle.vehicle_type}}</h6>
                      </div>
                    </div>
                    <div class="col-lg-4 col-6">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.color")}}</p>
                        <h6>{{vehicle.color}}</h6>
                      </div>
                    </div>
                    <div class="col-lg-4 col-6">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.vehicleYear")}}</p>
                        <h6>{{vehicle.year}}</h6>
                      </div>
                    </div>
                    <div class="col-lg-4 col-6">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.engineType")}}</p>
                        <h6>{{vehicle.engine_type}}</h6>
                      </div>
                    </div>
                    <div class="col-lg-4 col-6">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.seatingCapacity")}}</p>
                        <h6>{{vehicle.no_of_persons}}</h6>
                      </div>
                    </div>
                    <div class="col-lg-6 col-12">
                      <div class="detail">
                        <p>{{$t("home.vehicleDetailLabels.average")}}</p>
                        <h6>{{vehicle.average}}</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </b-modal>
          </div>
        </vue-glide-slide>
        <template slot="control">
          <button data-glide-dir="<" class="glide-slide-arrow">
            <i class="icon fleet-chevron-left"></i>
          </button>
          <button data-glide-dir=">" class="glide-slide-arrow">
            <i class="icon fleet-chevron-right"></i>
          </button>
        </template>
      </vue-glide>
    </b-container>
  </div>
</template>

<script>
import api from "@/api.js";
export default {
  data() {
    return {
      vehicles: [],
      vehicleDetails: {},
      options: {
        type: "slider",
        animationDuration: 1000,
        animationTimingFunc: "ease",
        bound: true,
        breakpoints: {
          1024: {
            perView: 2
          },
          600: {
            perView: 1
          }
        }
        // modalShow: false,
        // perView: 2
        // bound: true
      }
    };
  },
  updated() {
    this.$refs.slider.glide.settings.perView =
      this.vehicles.length <= 2 ? 2 : 3;
    // console.log(this.$refs.slider.glide.settings.perView);
  },
  methods: {
    async getData() {
      await this.axios
        .get(`${api.url}/vehicles`)
        .then(response => {
          this.vehicles = response.data;
          console.log(response.data);
        })
        .catch(error => {
          console.log(error);
        });
    },
    modalId(i) {
      return "modal" + i;
    }
  },
  mounted() {
    // console.log(this.$refs.test.glideSlider);
    // console.log(this.$refs.slider);
  },
  async created() {
    this.getData().then(() => {
      console.log(this.vehicles.length);
      this.options.perView = this.vehicles.length <= 2 ? 2 : 3;
    });

    // await this.getData();
    // this.axios
    //   .get(`${api.url}/vehicles`)
    //   .then(response => {
    //     this.vehicles = response.data;
    //     console.log(response.data);
    //   })
    //   .catch(error => {
    //     console.log(error);
    //   });
  }
};
</script>

<style lang="scss">
// Not scoped due to the nature of glide slider component
[data-glide-el="controls"] {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

// editable
.glide-slide-arrow {
  width: 50px;
  height: 50px;
  border-radius: 50px;
  background: white;
  border: 2px solid $primary-color;
  margin: 0px 10px;
  margin-top: 40px;
  transition: all 0.3s;
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0.8;
  transition: all 0.3s ease;

  &:hover {
    box-shadow: 0px 3px 10px rgba(2, 0, 28, 0.2);
    opacity: 1;
  }

  i {
    font-weight: 400;
    font-size: 20px;
  }
}

// Vehicle card ( sliding element )
.vehicle-card {
  width: 100%;
  height: 100%;
  background-color: #34404b;
  border-radius: 5px;
  -webkit-transition: all 0.3s ease;
  transition: all 0.3s ease;
  overflow: auto;
  margin: 15px 0;
  max-width: 320px;
  margin: 0 auto;
  // @include for-size(mobile) {
  //   position: relative !important;
  //   left: 10px !important;
  // }
  &:focus,
  &:active {
    outline: 0 !important;
    box-shadow: none !important;
  }
}

.vehicle-image {
  border-radius: 5px;
  border-bottom-left-radius: 0px;
  border-bottom-right-radius: 0px;
  height: 200px;
  width: 320px;
  object-fit: cover;
}

.vehicle-card-image .vehicle-meta {
  position: absolute;
  bottom: 10px;
  right: 10px;
  font-size: 12px;
  font-weight: 600;
  // opacity: 0.5;
  color: #ccc;
}

.vehicle-card-image {
  position: relative;
}

.vehicle-details {
  @include flex-row-center;
  padding: 25px 0px;
  color: white;
  text-align: center;

  h5 {
    font-weight: 600;
    margin-bottom: 0px;
  }
}
</style>


<style lang="scss" scoped>
.detail {
  margin-bottom: 30px;
  p {
    opacity: 0.6;
    margin-bottom: 6px;
    font-size: 13px;
  }
  h6 {
    font-weight: 600;
    color: $text-color;
    font-size: 14px;
  }
  @include for-size(tablet) {
    margin: 15px 0;
  }
}

.detail-image {
  border-radius: 6px;
  width: 100%;
  object-fit: cover;
}
</style>