<template>
    <div id="app">
        <router-view :key="routeUniqueKey" v-if="$authenticatedUser" />
    </div>
</template>

<script>
  export default {
    name: "app",
    computed: {
      routeUniqueKey () {
        return this.$route.name + this.$route.fullPath
      }
    }
  }
</script>

<style scoped>

</style>