export const toast = {
  methods: {
    toast(toaster, append = false, variant, title, desc) {
      this.$bvToast.toast(`${desc}`, {
        title: title,
        toaster: toaster,
        solid: true,
        appendToast: append,
        variant: `${variant}`
      })
    }
  }
};
