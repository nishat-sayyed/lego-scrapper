<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Example Component</div>

          <div v-for="lego in legos" :key="lego.id" class="card-body">
            {{ lego.name }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      legos: [],
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      fetch("/api/legos")
        .then((response) => response.json())
        .then((response) => (this.legos = response));
    },
  },
};
</script>
