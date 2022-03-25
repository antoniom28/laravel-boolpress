<template>
  <div class="main-container">
      <Header />
      <Main :mainUser="mainUser"/>
    <!--  <Footer /> -->
  </div>
</template>

<script>
import Header from './components/Header.vue';
import Main from './components/Main.vue';
import Footer from './components/Footer.vue';
export default {
    name: 'App',
    components: {
        Header,
        Main,
        Footer,
    },
    data(){
      return{
        mainUser : null,
      }
    },
    created: function(){
      this.getUser();
    },
    methods:{
      async getUser() {
      let response = await this.makeAxiosCall(`../admin/user`);
      console.log(response.data);
      this.mainUser = response.data;
    },
    makeAxiosCall(url) {
      console.log(`call of 2axos`,url);
      return axios.get(url, {
        params: {
        },
      });
    },
    },
}
</script>

<style>
-main-container{
    background-color: rgba(250,250,250);
}
</style>