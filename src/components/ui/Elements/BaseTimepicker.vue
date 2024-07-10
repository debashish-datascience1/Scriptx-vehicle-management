<template>
  <div class="form-input">
    <flat-pickr
      v-model="model"
      @onChange="$emit('input', $event.target.value)"
      class="date-input"
      placeholder="Select Time"
      :config="config"
    ></flat-pickr>
  </div>
</template>

<script>
export default {
  props: {
    model: String
  },
  data() {
    return {
      config: {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i:s",
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

</style>
