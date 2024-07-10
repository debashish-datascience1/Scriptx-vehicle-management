<template>
  <div class="form-input">
    <div class="input-group">
      <input
        ref="input"
        @input="$emit('input', $event.target.value)"
        @blur="checkLabel; $emit('blur')"
        :required="required"
        :placeholder="placeholder"
        :name="name"
        :value="value"
        :type="type"
        :readonly="readonly"
        :id="id"
      />
      <label for class="label" :class="{'label-top' : labelActive }" @click="focus">{{label}}</label>
      <span class="input-addon">
        <i class="icon" :class="iconClass"></i>
      </span>
    </div>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      default: ""
    },
    readonly: {
      type: String,
      default: null
    },
    name: {
      type: String,
      default: null
    },
    placeholder: {
      type: String,
      default: null
    },
    type: {
      type: String,
      default: "Text"
    },
    id: {
      type: String,
      default: null
    },
    required: String,
    label: String,
    iconClass: String
  },
  data() {
    return {
      labelActive: false
    };
  },
  methods: {
    checkLabel() {
      if (this.$refs.input.value.length != 0) {
        this.labelActive = true;
      } else {
        this.labelActive = false;
      }
    },
    focus() {
      let el = this.$refs.input;
      el.focus();
      this.labelActive = true;
    },
    focusOut() {
      this.checkLabel();
    }
  },
  mounted() {
    this.checkLabel();
  },
  updated() {
    this.checkLabel();
  }
};
</script>

<style lang="scss">
.form-input {
  position: relative;
  margin-bottom: 40px;
}

.input-group {
  position: relative;
}
input,
input.form-control,
div.form-control,
.form-control[readonly],
.form-control input {
  font-size: 14px !important;
  color: black !important;
  border-radius: 50px;
  font-weight: 500;
  padding: 10px 30px;
  padding-right: 40px;
  background-color: white !important;
  box-shadow: 0px 2px 3px rgba(2, 0, 28, 0.05);
  border: 0;
  width: 100%;
  border: 1px solid transparentize($primary-color, 0.8);
  transition: all 0.2s ease;
  min-height: 38px;
  &:focus {
    outline: 0;
    border: 1px solid $primary-color;
    box-shadow: none;
  }

  &:focus ~ .input-addon {
    opacity: 1;
  }
}

body[dir="rtl"] .label {
  left: unset;
  right: 50px;
}

.label {
  position: absolute;
  top: 50%;
  left: 30px;
  transform: translateY(-50%);
  opacity: 0.5;
  transition: all 200ms ease;
  font-size: 14px;
  font-weight: 500;
  &:hover {
    cursor: text;
  }
  &-top {
    top: -20px;
    transform: none;
    font-size: 12px;
    color: $primary-color;
    opacity: 1;
  }
}

.input-addon {
  position: absolute;
  top: calc(50% - 1px);
  transform: translateY(-50%);
  right: 15px;
  opacity: 0.5;
  width: 20px;
  height: 20px;
  transition: all 0.2s ease;
  i {
    font-size: 24px;
    color: $primary-color;
  }
}
</style>

