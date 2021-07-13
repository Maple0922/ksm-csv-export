<template>
  <section class="container home">
    <h2 class="container__title">Home</h2>
    <p class="container__message">Hello! This is home.</p>
    <router-link v-bind:to="{ name: 'dashboard' }">
      <button class="btn container__button">Dashboard</button>
    </router-link>
  </section>
</template>

<script>
export default {
  name: "HomeComponent",
};
</script>

<style lang="scss" scoped>
@import "../../sass/variables";

.home {
  .container__title {
    color: $red;
  }
  .container__button {
    background: $blue;
  }
}
</style>
