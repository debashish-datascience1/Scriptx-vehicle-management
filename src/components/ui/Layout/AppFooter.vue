<template>
  <footer class="footer">
    <div class="container container-large mt-0 mt-lg-3">
      <div class="row w-100 m-0 p-0 d-flex flex-row">
        <!-- Logo -->
        <div class="col-lg-3 col-sm-12 footer-col mb-3 mb-lg-0 order-1">
          <div class="footer_logo">
            <img :src="footerData.company_logo" alt class="d-block mx-auto" />
          </div>
        </div>
        <!-- About and address -->
        <div class="col-lg-2 col-sm-4 footer-col order-2">
          <h6 class="footer_title">{{ $t("footer.address") }}</h6>
          <p class="footer_description">{{footerData.company_address}}</p>
        </div>
        <div class="col-lg-3 col-sm-12 footer-col text-center text-lg-left mb-3 order-1 order-lg-1">
          <h6 class="footer_title">{{ $t("footer.aboutUs") }}</h6>
          <p class="footer_description">{{footerData.about_breif}}</p>
        </div>
        <!-- Contact-->
        <div class="col-lg-2 col-sm-4 footer-col order-4">
          <h6 class="footer_title">{{ $t("footer.contactUs") }}</h6>
          <p>
            <a href class="footer_link">{{footerData.contact_email}}</a>
            <br />
            <a href class="footer_link">{{footerData.company_phone}}</a>
          </p>
        </div>
        <!-- useful links -->
        <div class="col-lg-2 col-sm-4 footer-col order-5">
          <h6 class="footer_title">{{ $t("footer.usefulLinks.title") }}</h6>
          <ul class="list-unstyled">
            <li>
              <router-link
                class="footer_link"
                to="/booking-history"
              >{{ $t("footer.usefulLinks.booking") }}</router-link>
              <!-- <a class="footer_link" href></a> -->
            </li>
            <li>
              <router-link
                class="footer_link"
                to="/contact"
              >{{ $t("footer.usefulLinks.contactUs") }}</router-link>
            </li>
            <li>
              <a
                class="footer_link"
                :href="footerData.cancellation"
              >{{ $t("footer.usefulLinks.cancellation") }}</a>
            </li>
            <li>
              <a class="footer_link" :href="footerData.terms">{{ $t("footer.usefulLinks.terms") }}</a>
            </li>
            <li>
              <a
                class="footer_link"
                :href="footerData.privacy_policy"
              >{{ $t("footer.usefulLinks.privacy") }}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer-social relative my-4">
        <div class="social-icons">
          <div class="social-icons-inner">
            <a :href="footerData.facebook" class="social-icon-round">
              <i class="icon fleet-facebook"></i>
            </a>
            <a :href="footerData.twitter" class="social-icon-round">
              <i class="icon fleet-twitter"></i>
            </a>
            <a :href="footerData.instagram" class="social-icon-round">
              <i class="icon fleet-instagram"></i>
            </a>
          </div>
        </div>
        <hr />
      </div>
    </div>
  </footer>
</template>

<script>
import api from "@/api.js";
export default {
  data() {
    return {
      footerData: {}
    };
  },
  mounted() {
    this.axios.get(`${api.url}/company-info`).then(response => {
      this.footerData = response.data;
    });
  }
};
</script>

<style lang="scss" scoped>
.footer {
  margin-top: 50px;
  background-color: #34404b;
  color: white;
  padding: 20px 0;
  &_logo img {
    max-width: 200px;
    max-height: 60px;
    object-fit: cover;
  }
  &_title {
    font-size: 14px;
    margin-bottom: 10px;
    font-weight: 600;
  }
  &_description,
  &_link,
  li {
    font-size: 12px;
    font-weight: 500;
    line-height: 1.6em;
  }
  &_link,
  &_description {
    opacity: 0.4;
  }
  &_link {
    transition: all 0.2s ease;
    &:hover {
      color: white;
      opacity: 1;
    }
  }
  hr {
    border-color: #d8dede;
    opacity: 0.5;
    margin-top: -20px;
    z-index: 1;
  }
}

p.footer-about > p {
  font-size: 12px !important;
}
.social-icons {
  background-color: #34404b;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  justify-content: center;
  align-items: center;
}

.footer-social .social-icon-round {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  border-radius: 25px;
  border: 1px solid #d8dede;
  margin: 0 15px;
  transition: all 0.3s ease;
  &:hover {
    background-color: transparentize($primary-color, 0.6);
  }
  i {
    color: white;
    transition: all 0.3s ease;
  }
}
.social-icons-inner {
  z-index: 5;
  display: inline-flex;
  background-color: #34404b;
}
</style>
