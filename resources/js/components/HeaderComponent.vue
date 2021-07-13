<template>
  <header>
    <h1>Vue Laravel Template</h1>
  </header>
</template>

<script>
export default {
  name: "HeaderComponent",
};
</script>

<style lang="scss" scoped>
@import "../../sass/variables";

header {
  width: 100%;
  height: 60px;
  background: $teal;
  display: flex;
  padding: 0 30px;
  margin-bottom: 24px;
  align-items: center;

  h1 {
    font-size: 24px;
    color: $black;
    margin: 0;
  }
}
</style>
