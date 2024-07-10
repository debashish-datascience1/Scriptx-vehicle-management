<template>
  <div class="bg-image-full">
    <b-container>
      <div class="form-container">
        <form action v-on:submit.prevent="register">
          <router-link to="/">
            <img :src="logo" class="mb-5 d-block mx-auto logo" alt />
          </router-link>
          <BaseSectionTitle :title="$t('form.createAcc')" :left="true" />
          <div class="contact-form">
            <div class="row">
              <div class="col-lg-6">
                <BaseInput
                  :label="$t('form.firstName')"
                  v-model="values.firstName"
                  name="first_name"
                  v-validate="'required'"
                >
                  <slot>
                    <span v-show="errors.has('first_name')" class="error">{{
                      $t("formErrors.required")
                    }}</span>
                  </slot>
                </BaseInput>
              </div>
              <div class="col-lg-6">
                <BaseInput
                  :label="$t('form.lastName')"
                  name="last_name"
                  v-model="values.lastName"
                  v-validate="'required'"
                >
                  <slot>
                    <span v-show="errors.has('last_name')" class="error">{{
                      $t("formErrors.required")
                    }}</span>
                  </slot>
                </BaseInput>
              </div>
            </div>
            <BaseInput
              :label="$t('form.email')"
              v-model="values.emailAddress"
              v-validate="'required|email'"
              name="email"
              type="email"
            >
              <slot>
                <span v-show="errors.has('email')" class="error">{{
                  $t("formErrors.required")
                }}</span>
              </slot>
            </BaseInput>
            <BaseInput
              :label="$t('form.address')"
              v-model="values.address"
              name="address"
            ></BaseInput>
            <BaseInput
              :label="$t('form.mobile')"
              v-model="values.mobileNo"
              name="mobile"
              type="number"
              v-validate="'required|numeric|min:10|max:10'"
            >
              <slot>
                <span v-show="errors.has('mobile')" class="error">{{
                  $t("formErrors.required")
                }}</span>
              </slot>
            </BaseInput>
            <BaseInput
              name="password"
              type="password"
              :label="$t('form.password')"
              v-validate="'required|min:6'"
              v-model="values.password"
            >
              <slot>
                <span v-show="errors.has('email')" class="error">{{
                  $t("formErrors.required")
                }}</span>
              </slot>
            </BaseInput>
            <BaseInput
              name="password_confirmation"
              :label="$t('form.confirmPassword')"
              type="password"
              data-vv-as="password"
              v-validate="{ is: values.password }"
              v-model="values.confirmPassword"
            >
              <slot>
                <span v-show="errors.has('password_confirmation')" class="error"
                  >{{ $t("formErrors.confirmPassword") }}.</span
                >
              </slot>
            </BaseInput>

            <div class="radio-group">
              <input
                type="radio"
                id="male"
                name="gender"
                value="1"
                class="custom-radio"
                v-model="values.gender"
              />
              <label for="male" class="custom-radio-label mx-3">{{
                $t("form.male")
              }}</label>
              <input
                type="radio"
                id="female"
                name="gender"
                value="0"
                class="custom-radio"
                v-model="values.gender"
              />
              <label for="female" class="custom-radio-label mx-3">{{
                $t("form.female")
              }}</label>
            </div>
            <button type="submit" class="form-action-button">
              {{ $t("form.signUp") }}
              <i class="icon fleet-arrow-right"></i>
            </button>
            <small class="text-center w-100 d-inline-block mt-3">
              {{ $t("form.haveAcc") }}
              <router-link to="/login" class="link semibold">{{
                $t("form.login")
              }}</router-link>
              <!-- <a href="login.html" class="link semibold">Login</a> -->
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

export default {
  components: {
    BaseInput,
    BaseButton,
  },
  data() {
    return {
      errorMessage: "",
      values: {
        firstName: "",
        lastName: "",
        emailAddress: "",
        address: "",
        mobileNo: "",
        password: "",
        confirmPassword: "",
        gender: "1",
      },
    };
  },
  computed: {
    logo() {
      return this.$store.state.logo;
    },
  },
  methods: {
    makeToast(append = false) {
      this.$bvToast.toast(this.$t("formErrors.registerError"), {
        toaster: "b-toaster-top-center",
        title: this.errorMessage,
        autoHideDelay: 5000,
        appendToast: append,
        variant: "danger",
      });
    },
    register() {
      this.$validator.validateAll().then((result) => {
        if (result) {
          this.axios
            .post(`${api.url}/user-register`, {
              first_name: this.values.firstName,
              last_name: this.values.lastName,
              emailid: this.values.emailAddress,
              address: this.values.address,
              mobno: this.values.mobileNo,
              password: this.values.password,
              confirm_password: this.values.confirmPassword,
              gender: this.values.gender,
            })
            .then((response) => {
              this.errorMessage = response.data.message;
              if (response.data.success === "1") {
                this.$router.push({
                  name: "login",
                  params: { success: true },
                });
              } else {
                this.makeToast();
              }
            })
            .catch(function(error) {
              console.log(error);
            });
          return;
        } else {
        }
      });
    },
  },
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
  min-height: 100vh;
  height: 100%;
  width: 100%;
  background: url("../../../assets/images/fleet-blur-hero.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  @include for-size(mobile) {
    background: none;
  }
}

.form-container {
  max-width: 600px;
  margin: 50px auto;
  background: rgba(249, 249, 251, 0.95);
  padding-left: 40px;
  padding-right: 40px;
  padding-top: 25px;
  padding-bottom: 30px;
  width: 100%;
  border-radius: 4px;
  @include for-size(mobile) {
    border-radius: 0px;
    height: 100%;
    background: white;
    margin-top: 10px;
  }
}

.link {
  border-bottom: 1px solid #00cc37;
}

.semibold {
  font-weight: 600;
}

.custom-radio-label {
  display: inline-block;
  padding-bottom: 7px;
  border-bottom: 2px solid transparent;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.3s ease;
  opacity: 0.5;

  &:hover {
    cursor: pointer;
  }
}

.custom-radio {
  display: none;
}

.custom-radio:checked + label {
  border-bottom: 2px solid $primary-color;
  opacity: 1;
}
</style>
