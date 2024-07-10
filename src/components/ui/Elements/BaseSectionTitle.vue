<template>
  <div class="section-title">
    <h3 class="font-weight-bold my-5" :class="{ 'text-left' : left }">{{ title }}</h3>
    <span class="underline"></span>
  </div>
</template>

<script>
export default {
  props: {
    left: {
      type: Boolean,
      default: false
    },
    title: {
      type: String
    }
  }
};
</script>

<style lang="scss" scoped>
.section-title {
  margin: 60px 0;
  position: relative;
  h3 {
    font-size: 26px;
    font-weight: 600px;
    text-align: center;
    text-transform: capitalize;
  }
  .underline {
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    display: inline-block;
    height: 3px;
    width: 70px;
    background-color: #00cc37;
  }
  h3.text-left + .underline {
    left: 0px;
    transform: none;
    width: 30px;
  }
}

body[dir="rtl"] .section-title .underline {
  // right: 0px;
  // transform: none;
}
</style>
