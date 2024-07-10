<template>
  <div>
    <div class="bg-service my-5">
      <b-container dir="ltr">
        <vue-glide
          class="row service-slider w-100 m-0 p-0"
          :options="options"
          v-if="services.length"
        >
          <vue-glide-slide v-for="service in services" :key="service.id">
            <div class="service-block text-center text-white py-5">
              <div class="service-image">
                <img :src="service.image" alt />
                <h6 class="my-3">{{service.title}}</h6>
                <p>{{service.description}}</p>
              </div>
            </div>
          </vue-glide-slide>
          <template slot="control">
            <button data-glide-dir="<" class="service-slide-arrow__left">
              <i class="icon fleet-chevron-left"></i>
            </button>
            <button data-glide-dir=">" class="service-slide-arrow__right">
              <i class="icon fleet-chevron-right"></i>
            </button>
          </template>
        </vue-glide>
      </b-container>
    </div>
  </div>
</template>

<script>
import api from "@/api.js";
// service slider image

export default {
  data() {
    return {
      services: [],
      options: {
        autoplay: 1500,
        animationDuration: 1000,
        animationTimingFunc: "ease",
        autoplaySpeed: 300,
        type: "carousel",
        perView: 3,
        bound: true,
        breakpoints: {
          1024: {
            perView: 2
          },
          600: {
            perView: 1
          }
        }
      }
    };
  },
  beforeCreate() {
    this.axios.get(`${api.url}/our-services`).then(response => {
      this.services = response.data;
    });
  }
};
</script>

<style lang="scss">
.glide__slides {
  margin-bottom: 0px !important;
}
.bg-service {
  background-color: transparentize($primary-color, 0.01);
  position: relative;
  z-index: 1;
}
.bg-service::after {
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  background-image: url("../../../assets/images/fleet-service-bg.png");
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  z-index: -1;
}
.service-block {
  max-width: 90%;
  margin: 0 auto;
  h6 {
    font-weight: 600;
  }
  p {
    font-size: 14px;
  }
}

.service-slider {
  position: relative;
}

.service-slide-arrow {
  position: absolute;
  background: transparent;
  border: 0;
  color: white;

  i {
    font-size: 25px;
    font-weight: 500;
  }

  &:hover {
    cursor: pointer;
  }
  &__left {
    @extend .service-slide-arrow;
    top: 50%;
    transform: translateY(-50%);
    left: -30px;
  }
  &__right {
    @extend .service-slide-arrow;
    top: 50%;
    transform: translateY(-50%);
    right: -30px;
  }

  @include for-size(mobile) {
    &__left {
      left: -10px;
    }
    &__right {
      right: -10px;
    }
  }
}
</style>
