<template>
  <div class="booking-form">
    <!-- Buttons -->
    <div class="tab-buttons">
      <button
        class="tab-button text-capitalize"
        type="button"
        @click="activeBtn = 'bookNow'"
        :class="{active: activeBtn === 'bookNow' }"
      >{{ $t("home.bookNow") }}</button>
      <button
        class="tab-button text-capitalize"
        type="button"
        @click="activeBtn = 'bookLater'"
        :class="{active: activeBtn === 'bookLater' }"
      >{{ $t("home.bookLater") }}</button>
    </div>
    <!-- /Buttons -->
    <!-- BOOK NOW -->
    <BookNow v-if="activeBtn === 'bookNow'" />
    <!-- BOOK LATER  -->
    <BookLater v-if="activeBtn === 'bookLater'" />
  </div>
</template>

<script>
import BaseInput from "@/components/ui/Elements/BaseInput";
import BaseTextarea from "@/components/ui/Elements/BaseTextarea";
import BaseDatepicker from "@/components/ui/Elements/BaseDatepicker";
import BaseButton from "@/components/ui/Elements/BaseButton";
import BaseTimepicker from "@/components/ui/Elements/BaseTimepicker";
import BookNow from "./BookNow.vue";
import BookLater from "./BookLater.vue";

export default {
  components: {
    BookNow,
    BookLater,
    BaseInput,
    BaseTextarea,
    BaseDatepicker,
    BaseTimepicker,
    BaseButton
  },
  data() {
    return {
      activeBtn: "bookNow"
    };
  }
};
</script>

<style lang="scss" >
.booking-form {
  background-color: white;
  border-radius: 4px;
}

.tab-buttons {
  display: flex;
  height: 60px;
}

form.booking-form {
  padding: 15px 25px;
}

.tab-button {
  background-color: transparent;
  display: inline-block;
  height: 100%;
  width: 100%;
  font-weight: 700;
  font-size: 20px;
  border: 0;
  border-radius: 4px;
  color: $text-color;
  transition: all 0.2s ease;

  &.active {
    background-color: $text-color;
    color: white;
  }
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

// Vselect

.vue-select .vs__dropdown-toggle {
  font-size: 14px;
  border-radius: 30px !important;
  padding: 0px 30px !important;
  padding-right: 10px !important;
  background-color: white;
  box-shadow: 0px 2px 3px rgba(2, 0, 28, 0.05);
  border: 0;
  width: 100%;
  border: 1px solid transparentize($primary-color, 0.8) !important;
  transition: all 0.2s ease;
}
.vue-select .vs__clear {
  display: none;
}
vue-select ul.vs__dropdown-menu {
  display: block;
  border-radius: 4px;
  position: absolute;
  top: calc(100% + 10px) !important;
  border: 1px solid transparentize($primary-color, 0.8) !important;
  border: none;
  left: 0;
  z-index: 1000;
  padding: 0 !important;
  margin: 0;
  width: 100%;
  max-height: 350px;
  min-width: 160px;
  overflow-y: auto;
  text-align: left;
  list-style: none;
  background: #fff;
}

.vue-select .vs__search {
  margin-top: 0px !important;
  padding: 0 !important;
  max-height: 38px !important;
}

.vs--open .vue-select .vs__dropdown-toggle {
  border-radius: 30px !important;
}

.vue-select .vs__selected {
  margin: 0px !important;
}

.vue-select .vs__dropdown-option--highlight {
  background-color: transparentize($primary-color, 0.97) !important;
  color: $primary-color;
}

.vue-select .vs__dropdown-option {
  padding: 10px 20px !important;
  font-size: 14px !important;
  font-weight: 500 !important;
  transition: all 0.2s ease !important;
  &:hover {
    color: $primary-color !important;
    cursor: pointer;
  }
}

.vue-select .vs__search {
  margin-top: 0px;
  padding: 0px;
  &::placeholder {
    opacity: 0.5;
    font-weight: 500;
  }
}

.vs--single.vs--open .vs__selected {
  position: relative;
  opacity: 1;
}
</style>
