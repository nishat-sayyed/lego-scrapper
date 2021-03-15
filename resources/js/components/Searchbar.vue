<template>
  <div :class="[styles, 'text-gray-600']">
    <span class="font-semibold"> Search </span>
    <input
      class="border-2 border-gray-300 bg-white h-10 px-2 mt-2 mr-2 rounded-lg text-sm focus:outline-none"
      type="search"
      name="search"
      v-model="inputVal"
    />
  </div>
</template>

<script>
export default {
  props: ["value", "styles"],
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
