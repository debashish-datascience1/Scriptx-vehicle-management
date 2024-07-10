<template>
  <div class="bg-image-full">
    <b-container>
      <div class="form-container">
        <form action @submit.prevent="login">
          <router-link to="/">
            <img :src="logo" class="mb-5 d-block mx-auto logo" alt />
          </router-link>
          <BaseSectionTitle :title="$t('form.login')" :left="true" />
          <div class="contact-form">
            <BaseInput
              :label="$t('form.email')"
              v-model="username"
              v-validate="'required|email'"
              name="email"
              data-vv-name="email"
            >
              <slot>
                <span v-show="errors.has('email')" class="error">{{
                  $t("formErrors.required")
                }}</span>
              </slot>
            </BaseInput>
            <BaseInput
              name="password"
              :label="$t('form.password')"
              type="password"
              v-model="password"
              v-validate="'required'"
            >
              <slot>
                <span v-show="errors.has('password')" class="error">{{
                  $t("formErrors.required")
                }}</span>
              </slot>
            </BaseInput>
            <span class="error mb-5" v-if="loginFailed">{{
              $t("formErrors.loginError")
            }}</span>
            <button type="submit" class="form-action-button">
              {{ $t("form.signIn") }}
              <i class="icon fleet-arrow-right"></i>
            </button>
            <small class="text-center w-100 d-inline-block mt-2">
              <router-link to="/forget-password" class="medium opacity-5">{{
                $t("form.forgotPassword")
              }}</router-link>
            </small>
            <small class="text-center w-100 d-inline-block mt-5">
              {{ $t("form.noAccount") }}
              <router-link to="/register" class="link semibold">{{
                $t("form.register")
              }}</router-link>
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
    BaseButton,
  },
  mixins: [toast],
  data() {
    return {
      username: "",
      password: "",
      loginFailed: false,
    };
  },
  computed: {
    logo() {
      return this.$store.state.logo;
    },
  },
  methods: {
    login() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.axios
            .post(`${api.url}/user-login`, {
              username: this.username,
              password: this.password,
            })
            .then((response) => {
              let res = response.data.userinfo;
              let token = res.api_token;
              let user_name = res.user_name;
              let user_id = res.user_id;
              let success = response.data.success;
              if (success === "1") {
                // Stores credentials in localstorage
                auth.login(user_name, token, user_id);
                // Tells vuex to update the data from localstorage
                let payload = {
                  token: token,
                  user_name: user_name,
                  user_id: user_id,
                  userLogged: true,
                };
                this.$store.dispatch("login", payload);
                // Redirect to homepage
                if (this.$route.params.book) {
                  this.$router.push({
                    name: "home",
                    params: { getSession: true },
                  });
                } else {
                  this.$router.push("/");
                }
              } else {
                this.loginFailed = true;
              }
            })
            .catch(function(error) {
              // console.log(error);
            });
        } else {
        }
      });
    },
  },
  mounted() {
    // If user completed registration then show the toast
    if (this.$route.params.success) {
      this.toast(
        "b-toaster-top-center",
        false,
        "success",
        this.$t("form.registerSuccess"),
        this.$t("form.registerSuccess2")
      );
      // If user tries to book cab without logged in
    } else if (this.$route.params.book) {
      this.toast(
        "b-toaster-top-center",
        false,
        "warning",
        this.$t("formErrors.pleaseLogin"),
        this.$t("formErrors.pleaseLogin2")
      );
    } else if (this.$route.params.password_reset) {
      this.$bvToast.toast(this.$t("form.passwordReset"), {
        toaster: "b-toaster-top-center",
        title: this.$t("form.passwordReset2"),
        autoHideDelay: 5000,
        appendToast: false,
        variant: "success",
      });
    }
  },
};
</script>

<style lang="scss" scoped>
.logo {
  max-width: 200px;
  max-height: 60px;
  object-fit: contain;
}

.container {
  @include for-size(mobile) {
    padding: 0px;
  }
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
