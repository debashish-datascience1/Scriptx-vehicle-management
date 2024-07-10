<template>
  <div dir="ltr">
    <div class="container px-sm-5 testimonial-slider">
      <agile :options="options" v-if="testimonials.length">
        <div class="slide" v-for="testimonial in testimonials" :key="testimonial.id">
          <div class="row pb-3 pb-sm-5">
            <div class="col-lg-4 flex-col-center">
              <div class="testimonial-image-block mx-auto">
                <div class="shadow-overlay"></div>
                <img :src="testimonial.image" alt="testimonial-image" class="testimonial-image" />
                <div class="testimonial-name">
                  <h6>{{testimonial.name}}</h6>
                </div>
                <div class="quote-round">
                  <i class="icon fleet-quote"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-8 d-flex flex-column justify-content-center align-items-center">
              <div
                class="testimonial-content w-100 text-center text-lg-left mt-5 mt-sm-0"
              >{{ testimonial.description }}</div>
            </div>
          </div>
        </div>
        <template slot="dots"></template>
      </agile>
    </div>
  </div>
</template>

<script>
import api from "@/api.js";
export default {
  data() {
    return {
      options: {
        navButtons: false,
        speed: 1500,
        fade: true,
        dots: true,
        slidesToShow: 1,
        arrows: false
      },
      testimonials: []
    };
  },
  beforeCreate() {
    this.axios.get(`${api.url}/testimonials`).then(response => {
      this.testimonials = response.data;
    });
  }
};
</script>

<style lang="scss">
.testimonial-slider .agile__dots {
  width: 100%;
  @include flex-row-center();
}
.testimonial-slider .agile__dot {
  width: 12px;
  height: 12px;
  margin: 0 8px;
  border: 2px solid #02001c;
  background-color: transparent;
  border-radius: 15px;
  &.agile__dot--current {
    background-color: $primary-color;
    border-color: $primary-color;
  }
  button {
    opacity: 0;
  }
}
</style>


<style lang="scss" scoped>
.testimonial-image-block .shadow-overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  -webkit-box-shadow: inset 0 -150px 150px -120px #000;
  box-shadow: inset 0 -150px 150px -120px #000;
  border-radius: 6px;
}
.testimonial-image-block {
  width: 260px;
  height: 230px;
  border-radius: 6px;
  position: relative;
}

.testimonial-image-block .testimonial-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 6px;
}
.testimonial-image-block .testimonial-name {
  color: white;
  position: absolute;
  bottom: 0px;
  right: 50px;
}

.testimonial-image-block .quote-round {
  display: flex;
  justify-content: center;
  align-items: center;
  position: absolute;
  bottom: -20px;
  right: -30px;
  width: 68px;
  height: 68px;
  border-radius: 40px;
  background-color: #00cc37;
  box-shadow: 0px 3px 10px rgba(2, 0, 28, 0.2);
  .fleet-quote {
    color: white;
    font-size: 30px;
  }
}

.testimonial-content {
  text-align: left;
  font-size: 16px;
  line-height: 1.8em;
  @include for-size(mobile) {
    font-size: 14px;
    min-height: 150px;
  }
}
</style>
