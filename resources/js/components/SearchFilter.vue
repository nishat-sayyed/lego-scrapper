<template>
  <div :class="styles">
    <span class="font-semibold">{{ title }}</span>
    <div class="flex flex-row">
      <div
        v-for="(option, index) in options"
        :key="option.label + index"
        :class="[index !== options.length - 1 ? 'mr-2' : '']"
      >
        <input
          type="radio"
          class="form-radio h-3 w-3 text-red-600"
          :id="option.value + title"
          :value="option.value"
          v-model="inputVal"
        />
        <label :for="option.value + title">{{ option.label }}</label>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title", "options", "value", "styles"],
  computed: {
    inputVal: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("input", val);
      },
    },
  },
};
</script>
