<template>
  <section class="container dashboard">
    <h2 class="container__title">Dashboard</h2>
    <p class="container__message">Hello! This is dashboard.</p>
    <router-link v-bind:to="{ name: 'home' }">
      <button class="btn container__button">Home</button>
    </router-link>
  </section>
</template>

<script>
export default {
  name: "DashboardComponent",
};
</script>

<style lang="scss" scoped>
@import "../../sass/variables";

.dashboard {
  .container__title {
    color: $blue;
  }
  .container__button {
    background: $red;
  }
}
</style>
