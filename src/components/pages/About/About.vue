<template>
  <div>
    <AppNavigation />
    <!-- HEADER -->
    <AppHero :heroImage="aboutImage">
      <div class="container hero-container" slot="content">
        <div class="hero-content-overlay">
          <h2 class="bold text-capitalize">{{ $t("about.title") }}</h2>
          <h6 class="medium regular">{{ $t("about.subTitle") }}</h6>
        </div>
      </div>
    </AppHero>
    <!--  -->
    <!-- About info  -->
    <b-container>
      <BaseSectionTitle :title="aboutData.title" />
      <div class="row mt-5 w-100 m-0 p-0">
        <div class="col-sm-12">
          <img
            src="../../../assets/images/fleet-about-bgstrip.jpg"
            alt
            class="img-fluid border-radius-4"
          />
        </div>
        <div class="col-sm-12">
          <div class="content-shadowed">
            <p>{{aboutData.description}}</p>
          </div>
        </div>
      </div>
    </b-container>
    <!--  -->
    <!-- About tiles -->
    <b-container class="my-5 py-5">
      <b-row class="w-100 m-0 p-0">
        <b-col sm="6">
          <div class="background-grey border-radius-4 p-4 flex-col-center text-center h-100">
            <img src="@/assets/images/fleet-about-city.png" alt />
            <h3
              class="font-weight-bold text-capitalize"
            >{{aboutData.cities}}+ {{ $t("about.cities") }}</h3>
            <p>{{ $t("about.cityText") }}</p>
          </div>
        </b-col>
        <b-col sm="6" class="mt-5 mt-sm-0">
          <div class="background-grey border-radius-4 p-4 flex-col-center text-center h-100">
            <img src="@/assets/images/fleet-about-vehicles.png" alt />
            <h3
              class="font-weight-bold text-capitalize"
            >{{aboutData.vehicles}}+ {{ $t("about.vehicles") }}</h3>
            <p>{{ $t("about.vehicleText") }}</p>
          </div>
        </b-col>
      </b-row>
    </b-container>
    <!-- /About tiles ends -->
    <!-- Minds behind it  -->
    <b-container dir="ltr">
      <BaseSectionTitle :title=" $t('about.testimonialTitle') " />
      <div class="col-sm-12 minds-slider">
        <agile :options="options" v-if="team.length">
          <div class="slide" v-for="person in team" :key="person.id">
            <div class="col-sm-12">
              <div
                class="testimonial-about mind-slide border-radius-4 text-center background-darkgrey text-white px-4 py-5"
              >
                <div class="testimonial-image-about">
                  <img :src="person.image" alt />
                </div>
                <h6 class="mb-1 mt-3">{{person.name}}</h6>
                <p class="regular">( {{person.designation}} )</p>
                <p class="mind-description">{{person.description}}</p>
              </div>
            </div>
          </div>
        </agile>
      </div>
    </b-container>
    <!--  -->
    <AppFooter />
  </div>
</template>

<script>
import api from "@/api.js";
import AppHero from "@/components/ui/Layout/AppHero";
export default {
  data() {
    return {
      aboutData: {},
      team: [],
      options: {
        infinite: true,
        autoplay: true,
        navButtons: false,
        speed: 1000,
        // slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        bound: true,
        responsive: [
          {
            breakpoint: 500,
            settings: {
              slidesToShow: 1
            }
          },
          {
            breakpoint: 700,
            settings: {
              slidesToShow: 2
            }
          },
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3
            }
          }
        ]
      }
    };
  },
  components: {
    AppHero
  },

  computed: {
    aboutImage() {
      return this.$store.state.aboutHero;
    }
  },
  mounted() {
    this.axios.get(`${api.url}/about`).then(response => {
      this.aboutData = response.data;
      this.team = response.data.team;
    });
  }
};
</script>


<style lang="scss">
.minds-slider .agile__dots {
  margin-top: 40px;
  width: 100%;
  @include flex-row-center();
}
.minds-slider .agile__dot {
  width: 12px;
  height: 12px;
  margin: 0 8px;
  background: $grey-color;
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
}

.content-shadowed {
  background: #f3f3f3;
  padding: 40px 30px;
  box-shadow: 0px 20px 40px -10px rgba(2, 0, 28, 0.1);
  border-bottom-left-radius: 4px;
  border-bottom-right-radius: 4px;
  p {
    font-size: 14px;
    line-height: 1.6em;
  }
}

.border-radius-4 {
  border-radius: 4px;
}

.background-grey {
  background-color: lighten(#ebecee, 3);
  transition: all 0.3s ease;
  &:hover {
    background-color: lighten(#ebecee, 1);
  }
}

.background-darkgrey {
  background-color: #34404b;
}

.testimonial-image-about {
  height: 80px;
  width: 80px;
  border-radius: 40px;
  overflow: hidden;
  margin: 0 auto;

  img {
    height: inherit;
    width: inherit;
    object-fit: cover;
  }
}

.mind-slide {
  // padding: 30px 0px;
  min-height: 400px;
  max-height: 400px;
  overflow: hidden;
  transition: all 0.3s ease;
  &:hover {
    box-shadow: 0px 15px 20px -4px rgba(2, 0, 28, 0.15);
  }
}

.mind-description {
  opacity: 0.6;
  line-height: 1.4em;
  font-size: 14px;
}
</style>
