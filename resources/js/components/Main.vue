<template>
  <main>
    <router-view :key="$route.path" :mainUser="mainUser"></router-view>
  </main>
</template>

<script>
export default {
    name: 'Main',
    props:{
      mainUser : Object,
    }
}
</script>

<style lang="scss" scoped>
</style>