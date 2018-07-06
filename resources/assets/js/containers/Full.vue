<template>
    <section class="ptb40 bg--secondary">
        <div class="container">
            <router-view></router-view>
        </div>
    </section>
</template>

<script>

export default {
  name: 'full',
  components: {},
  data () {
    return {}
  },
  computed: {
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched
    }
  }
}
</script>
