<template>
  <div class="form-input">
    <v-select :searchable="false" :placeholder="'Select vehicle'" :options="selectItems"></v-select>
  </div>
</template>

<script>
export default {
  props: {
    placeholder: String,
    selectItems: Array
  }
};
</script>

<style lang="scss">
.form-input {
  margin-bottom: 40px;
}

.vs__dropdown-toggle {
  font-size: 14px;
  border-radius: 30px !important;
  padding: 0px 30px !important  ;
  padding-right: 5px;
  background-color: white;
  box-shadow: 0px 2px 3px rgba(2, 0, 28, 0.05);
  border: 0;
  width: 100%;
  border: 1px solid transparentize($primary-color, 0.8) !important;
  transition: all 0.2s ease;
}
.vs__clear {
  display: none;
}
ul.vs__dropdown-menu {
  display: block;
  border-radius: 4px;
  position: absolute;
  top: calc(100% + 10px) !important;
  border: 1px solid transparentize($primary-color, 0.8) !important;
  border: none;
  left: 0;
  z-index: 1000;
  padding: 0;
  margin: 0;
  width: 100%;
  max-height: 350px;
  min-width: 160px;
  overflow-y: auto;
  text-align: left;
  list-style: none;
  background: #fff;
}

.vs__search {
  margin-top: 0px !important;
  padding: 0 !important;
  max-height: 38px !important;
}

.vs--open .vs__dropdown-toggle {
  border-radius: 30px !important;
}

.vs__selected {
  margin: 0px !important;
}

.vs__dropdown-option--highlight {
  background-color: transparentize($primary-color, 0.97) !important;
  color: $primary-color;
}

.vs__dropdown-option {
  padding: 10px 20px !important;
  font-size: 14px !important;
  font-weight: 500 !important;
  transition: all 0.2s ease !important;
  &:hover {
    color: $primary-color !important;
    cursor: pointer;
  }
}

.vs__search {
  margin-top: 0px;
  padding: 0px;
  &::placeholder {
    opacity: 0.5;
    font-weight: 500;
  }
}
</style>
