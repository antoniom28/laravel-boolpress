<template>
  <div v-if="!load" class="profile-box">
      <div class="profile align-items-center">
          <div class="image">
            <div class="image-box">
                <img :src="'../storage/'+user.avatar" alt="">
            </div>
        </div>
        <div class="content-box">
            <div class="content">
                <div class="header justify-between align-items-center">
                    <p> {{user.name}} </p>
                    <div class="message-box put-border"> Message </div>
                    <div class="user-box put-border">
                        <svg style="width:13px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M224 256c70.7 0 128-57.31 128-128s-57.3-128-128-128C153.3 0 96 57.31 96 128S153.3 256 224 256zM274.7 304H173.3C77.61 304 0 381.6 0 477.3c0 19.14 15.52 34.67 34.66 34.67h378.7C432.5 512 448 496.5 448 477.3C448 381.6 370.4 304 274.7 304z"/></svg> 
                        <svg style="width:10px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M438.6 105.4C451.1 117.9 451.1 138.1 438.6 150.6L182.6 406.6C170.1 419.1 149.9 419.1 137.4 406.6L9.372 278.6C-3.124 266.1-3.124 245.9 9.372 233.4C21.87 220.9 42.13 220.9 54.63 233.4L159.1 338.7L393.4 105.4C405.9 92.88 426.1 92.88 438.6 105.4H438.6z"/></svg>
                    </div>
                    <div class="menu-box"> 
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M120 256C120 286.9 94.93 312 64 312C33.07 312 8 286.9 8 256C8 225.1 33.07 200 64 200C94.93 200 120 225.1 120 256zM280 256C280 286.9 254.9 312 224 312C193.1 312 168 286.9 168 256C168 225.1 193.1 200 224 200C254.9 200 280 225.1 280 256zM328 256C328 225.1 353.1 200 384 200C414.9 200 440 225.1 440 256C440 286.9 414.9 312 384 312C353.1 312 328 286.9 328 256z"/></svg>    
                    </div>
                </div>

                <div class="main justify-between align-items-center">
                    <p>Post : 0</p>
                    <p>0 follower</p>
                    <p>0 profiles followed</p>
                </div>

                <div class="footer">
                    Bio
                </div>
            </div>
        </div>
      </div>

      <div class="user-posts">
          <div v-for="(div,index1) in Math.ceil(user.posts.length/3)" :key="'a'+index1" class="image-row">
              <div @click="showPost(post)" v-for="(post,index2) in user.posts.slice(3*index1 , 3*(index1 + 1))" :key="'b'+index2" class="image">
                <!--  <router-link :to="{name : 'single-post' , params:{post:post,slug:post.slug}}"> 
                    <img v-if="post.image" :src="'../storage/'+post.image" alt="">
                  </router-link> -->
                    <img v-if="post.image" :src="'../storage/'+post.image" alt="">
              </div>
              
          </div>
      </div>

      <div @click="postFromUser = null" v-if="postFromUser" class="opacity-box">
         <div class="exit">
             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"/></svg>
         </div>
      </div>

  
      <div v-if="postFromUser" class="post-from-user">
        <SinglePost @updateComments="getUser" :mainUser="mainUser" :postFromUser="getPostFromUser"/>
      </div>
  </div>
</template>

<script>
import SinglePost from './SinglePost.vue';

export default {
    name: "SearchedUser",
    data(){
        return{
            postFromUser: null,
            user : null,
            load : false,
        }
    },
    props: {
        mainUser: Object,
    },
    components:{
        SinglePost,
    },
    created: function(){
        this.load = true;
        this.getUser();
    },
    computed:{
        getPostFromUser(){
            return this.postFromUser;
        }
    },
    methods:{
        showPost(post){
            this.postFromUser = null;
            setTimeout(() => {
                this.postFromUser = post;
            }, 0);
        },
        getUser(){
            axios.get('../api/user/'+this.$route.params.slug)
            .then((response)=>{
                console.log(response.data,'deve essee un object');
                this.user = response.data;
                this.load = false;
            });
        }
    },
}
</script>

<style lang="scss" scoped>
.opacity-box{
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: 10000;
    background-color: rgba(0, 0, 0, 0.5);

    .exit{
        position: absolute;
        right: 30px;
        height: 28px;
        fill: white;
        cursor: pointer;
        top: 15px;
    }
}

.post-from-user{
    position: fixed;
    max-width: 900px;
    width: 90%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10001;
}

.profile{
    margin: 0 auto;
    width: 90%;
    max-width: 800px;
    height: 200px;
    overflow: hidden;

    .image{
        width: 35%;
        height: 100%;
        position: relative;

        .image-box{
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 1px solid black;
            overflow: hidden;
            display: flex;
            justify-content: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50% , -50%);

            img{
                flex-shrink: 0;
                min-width: 100%;
                min-height: 100%
            }
        }
    }
}

.user-posts{
    margin: 0 auto;
    width: 90%;
    max-width: 800px;
    min-height: 300px;

    .image-row{
        display: flex;
        justify-content: flex-start;
        width: 100%;
        margin: 15px 0;

        .image{
            position: relative;
            overflow: hidden;
            margin-left: 2.5%;
            display: flex;
            justify-content: center;
            width: 30%;
            height: 0;
            padding-bottom: 30%;
            cursor: pointer;

            img{
                min-width: 100%;
                height: 100%;
                flex-shrink: 0;
                position: absolute;
            }
        }
    }
    
}

.content-box {
    height: 100%;
    width: 65%;

    .content{
        height: 80%;
        width: 100%;
        margin-top: 8%;

        .header{
            width: 80%;
            height: 25%;
        }

        .main{
            width: 60%;
            height: 25%;
        }

        .footer{
            width: 40%;
            margin-top: 15px;
            height: calc(50% - 15px);
        }
    }
}

.put-border{
    text-align: center;
    width: 80px;
    padding: 5px 0;
    border: 1px solid #d4d4d4;
}
</style>