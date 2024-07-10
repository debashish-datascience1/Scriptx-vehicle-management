<template>
  <AppHeader v-if="$resize && $mq.above(vsize.tablet)" />
  <MobileNav v-else />
  <!-- <leftMenu></leftMenu> -->
</template>

<script>
import AppHeader from "@/components/ui/Layout/AppHeader";
import MobileNav from "./MobileNav";
export default {
  components: {
    AppHeader,
    MobileNav
  }
};
</script>

<style>
</style>
