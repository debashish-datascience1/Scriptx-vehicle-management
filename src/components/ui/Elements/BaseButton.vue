<template>
  <button type="submit" @click="buttonClick">
    {{ label }}
    <i class="icon fleet-arrow-right"></i>
  </button>
</template>

<script>
export default {
  props: {
    buttonClick: {
      type: Function,
      default: () => {},
    },
    label: {
      type: String,
      default: "Book",
    },
  },
};
</script>

<style lang="scss" scoped>
button {
  @include flex-row-center;
  border: 0px;
  background-color: $primary-color;
  color: white;
  font-size: 20px;
  height: 50px;
  width: 200px;
  border-radius: 100px;
  font-weight: 700;
  text-transform: capitalize;
  transition: all 0.3s ease;
  margin: 0 auto;
  &:hover {
    background-color: darken($primary-color, 40);
  }
  i {
    color: white;
    font-weight: 800;
    margin-left: 10px;
  }
}
</style>

<style lang="scss">
.form-action-button {
  @include flex-row-center;
  border: 0px;
  background-color: $primary-color;
  color: white;
  font-size: 20px;
  height: 50px;
  width: 200px;
  border-radius: 100px;
  font-weight: 700;
  text-transform: capitalize;
  transition: all 0.3s ease;
  margin: 0 auto;
  i {
    color: white;
    font-weight: 800;
    margin-left: 10px;
  }
}
</style>
