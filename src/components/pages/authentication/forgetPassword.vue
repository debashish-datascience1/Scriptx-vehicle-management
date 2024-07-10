<template>
  <div class="bg-image-full">
    <b-container>
      <div class="form-container">
        <form action @submit.prevent="forgetPassword">
          <router-link to="/">
            <img :src="logo" class="mb-5 d-block mx-auto logo" alt />
          </router-link>

          <BaseSectionTitle :title="$t('forgetPassword.title')" :left="true" />
          <h6>{{$t('forgetPassword.text')}}</h6>
          <div class="contact-form mt-5">
            <BaseInput
              :title="$t('form.email')"
              v-model="email"
              v-validate="'required|email'"
              name="email"
            >
              <slot>
                <span v-show="errors.has('email')" class="error">{{$t('formErrors.required')}}</span>
              </slot>
            </BaseInput>

            <!-- <span class="error mb-5" v-if="loginFailed">These credentials do not match our records.</span> -->
            <BaseButton :label="$t('forgetPassword.getLink')" />

            <small class="text-center w-100 d-inline-block mt-5">
              {{$t('form.noAccount')}}
              <router-link to="/register" class="link semibold">{{$t('form.register')}}</router-link>
            </small>
          </div>
        </form>
      </div>
    </b-container>
  </div>
</template>

<script>
import api from "@/api.js";
import BaseInput from "@/components/ui/Elements/BaseInput";
import BaseButton from "@/components/ui/Elements/BaseButton";
import auth from "@/auth.js";
export default {
  components: {
    BaseInput,
    BaseButton
  },
  data() {
    return {
      email: ""
    };
  },
  computed: {
    logo() {
      return this.$store.state.logo;
    }
  },
  methods: {
    makeToast(append = false) {
      this.$bvToast.toast(this.$t("forgetPassword.failed"), {
        toaster: "b-toaster-top-center",
        title: this.errorMessage,
        autoHideDelay: 5000,
        appendToast: append,
        variant: "danger"
      });
    },
    forgetPassword() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.axios
            .post(`${api.url}/forgot-password`, {
              email: this.email
            })
            .then(response => {
              this.errorMessage = response.data.message;
              if (response.data.success === "1") {
                this.$bvToast.toast(this.$t("forgetPassword.success"), {
                  toaster: "b-toaster-top-center",
                  title: this.$t("forgetPassword.success2"),
                  autoHideDelay: 5000,
                  appendToast: false,
                  variant: "success"
                });
              } else {
                this.$bvToast.toast(this.$t("forgetPassword.failed"), {
                  toaster: "b-toaster-top-center",
                  title: this.errorMessage,
                  autoHideDelay: 5000,
                  appendToast: false,
                  variant: "danger"
                });
              }
            });
        }
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.container {
  @include for-size(mobile) {
    padding: 0px;
  }
}

.logo {
  max-width: 200px;
  max-height: 60px;
  object-fit: contain;
}

.bg-image-full {
  @include flex-row-center;
  height: 100vh;
  width: 100vw;
  background: url("../../../assets/images/fleet-blur-hero.jpg");
}

.form-container {
  max-width: 500px;
  margin: 0 auto;
  background: rgba(249, 249, 251, 0.95);
  padding-left: 40px;
  padding-right: 40px;
  padding-top: 25px;
  padding-bottom: 30px;
  width: 100%;
  border-radius: 4px;
  @include for-size(mobile) {
    border-radius: 0px;
    height: 100vh;
    background: white;
  }
}

.link {
  border-bottom: 1px solid #00cc37;
}

.semibold {
  font-weight: 600;
}
</style>
    