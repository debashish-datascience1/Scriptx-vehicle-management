<template>
  <div class="bg-image-full">
    <b-container>
      <div class="form-container">
        <form action @submit.prevent="resetPassword">
          <router-link to="/">
            <img :src="logo" class="mb-5 d-block mx-auto logo" alt />
          </router-link>

          <BaseSectionTitle :title="$t('forgetPassword.reset')" :left="true" />
          <div class="contact-form mt-5">
            <BaseInput
              :label="''"
              v-model="email"
              v-validate="'required'"
              name="email"
              type="email"
              no-autocomplete
              readonly="readonly"
            >
              <slot>
                <span v-show="errors.has('email')" class="error">{{$t("formErrors.required")}}</span>
              </slot>
            </BaseInput>
            <BaseInput
              :label="$t('form.password')"
              v-model="password"
              v-validate="'required|min:6'"
              name="password"
              type="password"
              ref="password"
            >
              <slot>
                <span v-show="errors.has('password')" class="error">{{$t("formErrors.required")}}</span>
              </slot>
            </BaseInput>
            <BaseInput
              :label="$t('form.confirmPassword')"
              v-model="confirm_password"
              v-validate="'confirmed:password'"
              name="confirm_password"
              type="password"
              data-vv-as="password"
            >
              <slot>
                <span
                  v-show="errors.has('confirm_password')"
                  class="error"
                >{{$t("formErrors.confirmPassword")}}</span>
              </slot>
            </BaseInput>

            <!-- <span class="error mb-5" v-if="loginFailed">These credentials do not match our records.</span> -->
            <BaseButton :label="$t('forgetPassword.resetPass')" />
            <!-- <small class="text-center w-100 d-inline-block mt-2">
              <a href class="medium opacity-5">Forgot password ?</a>
            </small>-->

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
import { toast } from "@/mixins/mixins";
import auth from "@/auth.js";
export default {
  components: {
    BaseInput,
    BaseButton
  },
  mixins: [toast],
  data() {
    return {
      email: "",
      password: "",
      confirm_password: "",
      token: ""
    };
  },
  methods: {
    resetPassword() {
      this.$validator.validateAll().then(result => {
        if (result) {
          this.axios
            .post(`${api.url}/reset-password`, {
              email: this.email,
              password: this.password,
              password_confirmation: this.confirm_password,
              token: this.token
            })
            .then(response => {
              if (response.data.success == "1") {
                this.$router.push({
                  name: "login",
                  params: { password_reset: true }
                });
              } else {
                this.$bvToast.toast(this.$t("formErrors.registerError"), {
                  toaster: "b-toaster-top-center",
                  title: response.data.message,
                  autoHideDelay: 5000,
                  appendToast: false,
                  variant: "danger"
                });
              }
            });
        }
      });
    }
  },
  mounted() {
    let reset_token = this.$route.params.token;
    let reset_email = this.$route.query.email;
    this.email = reset_email;
    this.token = reset_token;
    if (reset_token == undefined) {
      this.$router.push("/");
    }
  },
  computed: {
    logo() {
      return this.$store.state.logo;
    }
  }
};
</script>

<style lang="scss" scoped>
.bg-image-full {
  @include flex-row-center;
  height: 100vh;
  width: 100vw;
  background: url("../../../assets/images/fleet-blur-hero.jpg");
}

.logo {
  max-width: 200px;
  max-height: 60px;
  object-fit: contain;
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
}

.link {
  border-bottom: 1px solid #00cc37;
}

.semibold {
  font-weight: 600;
}
</style>
    