<template>
  <div class="form-input">
    <flat-pickr
      @input="$emit('input', $event.target.value)"
      :v-model="model"
      class="date-input"
      placeholder="Select date"
      :value="value"
      :config="config"
    ></flat-pickr>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      default: ""
    },
    model: String
  },
  data() {
    return {
      config: {
        disableMobile: true
      }
    };
  }
};
</script>

<style lang="scss" scoped>
.form-input {
  position: relative;
  margin-bottom: 40px;
}
.date-input {
  font-size: 14px;
  border-radius: 50px;
  font-weight: 500;
  padding: 10px 30px;
  padding-right: 35px;
  background-color: white;
  box-shadow: 0px 2px 3px rgba(2, 0, 28, 0.05);
  border: 0;
  width: 100%;
  border: 1px solid transparentize($primary-color, 0.8);
  transition: all 0.2s ease;
  &:focus {
    outline: 0;
    border: 1px solid $primary-color;
  }

  &:focus ~ .input-addon {
    opacity: 1;
  }
}
</style>
