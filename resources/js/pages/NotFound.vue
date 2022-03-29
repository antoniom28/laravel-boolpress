<template>
  <div class="container">
<h1> Spiacenti, questa pagina non è disponibile </h1>
<p>
È possibile che il link che hai seguito sia corrotto o che la pagina sia stata rimossa.
<router-link :to="{name : 'home'}"> Torna su Isntagram. </router-link>
</p>
  </div>
</template>

<script>
export default {
    name: "NotFound",
}
</script>

<style lang="scss" scoped>
.container{
    font-size: 1.1em;
    height: calc(100vh - 60px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    >*{
        margin: 10px 0;
    }
}
</style>