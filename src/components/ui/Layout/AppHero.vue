<template>
  <div class="page-header" :style="{
    backgroundImage: 'url(\'' + heroImage + '\')',
    }">
    <slot name="content"></slot>
  </div>
</template>

<script>
export default {
  props: {
    heroImage: String
  },
  computed: {}
};
</script>

<style lang="scss" scoped>
.page-header {
  padding-top: 150px;
  min-height: 680px;
  height: auto;
  background-size: cover;
  background-position: fixed;

  @include for-size(mobile) {
    padding-top: 150px;
    min-height: 600px;
    height: 100%;
  }
}
h1 {
  font-weight: 600;
}
h4 {
  color: $primary-color;
}
</style>
