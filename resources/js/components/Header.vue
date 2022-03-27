<template>
  <header>
      <div class="fill-menu"></div>

      <div class="main-menu">
        <div class="menu-left">
          <h1><router-link class="title" :to="{name : 'home'}">Boolgram</router-link></h1>
        </div>

        <div class="menu-right">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input 
            @click="searchBox = true" 
            v-model="inputText" 
            @keyup="controlInput" 
            type="text" placeholder="Search"
          >
          <div @click="searchBox = false" v-show="searchBox == true" class="hidden-close-sub"></div>
          <div v-show="searchBox == true" class="sub-search">
            <ul>
              <li
                @click="searchBox = false; inputText = ''; prevInput = ''"
                class="align-items-center"
                v-for="(user,index) in usersSearched"
                :key="index"
              >
              <router-link class="searched-user" :to="{name : 'searched-user' , params:{slug:user.slug} }">
                <div class="image">
                  <img v-if="user.avatar" :src="'../storage/'+user.avatar">
                  <div v-else class="image-404"></div>
                </div>
                <span>{{user.name}}</span>
              </router-link>
              </li>
            </ul>
          </div>
          <!-- <a class="icon" href="/admin/posts/create"> -->
           <a style="cursor:pointer" @click="createPostBox(true)" class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M384 32C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96C0 60.65 28.65 32 64 32H384zM224 368C237.3 368 248 357.3 248 344V280H312C325.3 280 336 269.3 336 256C336 242.7 325.3 232 312 232H248V168C248 154.7 237.3 144 224 144C210.7 144 200 154.7 200 168V232H136C122.7 232 112 242.7 112 256C112 269.3 122.7 280 136 280H200V344C200 357.3 210.7 368 224 368z"/></svg>
          </a>
          <a class="icon" href="/admin">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M224 256c70.7 0 128-57.31 128-128s-57.3-128-128-128C153.3 0 96 57.31 96 128S153.3 256 224 256zM274.7 304H173.3C77.61 304 0 381.6 0 477.3c0 19.14 15.52 34.67 34.66 34.67h378.7C432.5 512 448 496.5 448 477.3C448 381.6 370.4 304 274.7 304z"/></svg>
          </a>
        </div>

        <div @click="createPost = false" v-show="createPost == true" class="hidden-close-sub create"></div>
        <PostCreate :mainUser="mainUser" @turnToPost="turnToPost" v-if="createPost" />
      </div>
  </header>
</template>

<script>
import PostCreate from './PostCreate.vue';
export default {
    name: 'Header',
    data(){
      return{
        createPost : false,
        searchBox : null,
        inputText: "",
        prevInput: '',
        usersSearched : [],
      }
    },
    props:{
      mainUser: Object,
    },
    components:{
      PostCreate,
    },
    methods:{
      turnToPost(){
        this.createPost = false;
      },
      createPostBox(can){
        this.createPost = can;
      },
      controlInput(){
        if(this.inputText != "" && this.inputText != this.prevInput){
          
          this.getUsers();
        } else{
          this.usersSearched = [];
        }

        this.prevInput = this.inputText;
      },
      async getUsers() {
      let response = await this.makeAxiosCall(`../api/users/${this.inputText}`);
      console.log(response.data);
      this.usersSearched = response.data;
    },
    makeAxiosCall(url) {
      console.log(`call of 1axos`);
      return axios.get(url, {
        params: {
        },
      });
    },
    }
}
</script>

<style lang="scss" scoped>
.hidden-close-sub{
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
}

.hidden-close-sub.create{
  z-index: 10000;
  background-color: rgba(0, 0, 0, 0.5);
}

a.searched-user{
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

.image{
  margin: 0 15px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
}
img{
  height: 100%;
  width: 100%;
  border-radius: 50%;
}

.image-404{
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: red;
}

.fill-menu{
  height: 60px;
}
.main-menu{
  position: fixed;
  border-bottom: 1px solid #d4d4d4;
  padding: 0 30px;
  top: 0;
  left: 0;
  z-index: 9999;
  width: 100%;
  height: 60px;
  background-color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

a.title{
  text-decoration: none;
  color: black;
}

a.icon{
  margin-left: 15px;
  width: 20px;
  height: 20px;
}

.menu-right{
  display: flex;
  justify-content: space-between;
  align-items: center;
}

input{
  position: relative;
  z-index: 9999;
  border-radius: 6px;
  margin: 0 10px;
  padding: 2px 15px;
  background-color: #d4d4d4;
  height: 30px;
}

.sub-search{
  padding: 20px 10px;
  position: fixed;
  height: 300px;
  border-radius: 30px;
  overflow: hidden;
  z-index: 9999;
  width: 300px;
  left: calc(50% - 150px);
  top: 60px;
  background-color: rgba(250,250,250);
  border: 1px solid black;

  li{
    background-color: white;
    height: 60px;

    &:hover{
      background-color: #f4f4f4;
      cursor: pointer;
    }
  }
}
</style>