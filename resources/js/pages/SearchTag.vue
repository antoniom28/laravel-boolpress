<template>
    <div v-if="load && posts != null" class="container">
        <div class="header align-items-center">
            <div class="image-box justify-center align-items-center">
                <div class="image justify-center align-items-center">
                    <img :src="'../storage/'+posts[0].image" alt="">
                </div>
            </div>
            <div class="content">
                <h1> #{{tag}} </h1>
                Post : <strong> {{posts.length}} </strong>
            </div>
            
        </div>

        <div class="main">
            <h3> Post più popolari </h3>
            <div class="tag-posts">
                <div v-for="(div,index1) in Math.ceil(posts.length/3)" :key="'a'+index1" class="image-row">
                    <div @click="showPost(post)" v-for="(post,index2) in posts.slice(3*index1 , 3*(index1 + 1))" :key="'b'+index2" class="image">
                        <router-link :to="{name : 'single-post' , params:{post:post,slug:post.slug}}">
                            <img v-if="post.image" :src="'../storage/'+post.image" alt="">
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-else-if="load" class="justify-center align-items-center">
        <h2> NON ESISTONO POST CON QUESTO TAG </h2>
    </div>
</template>

<script>
export default {
    name: "SearchTag",
    data(){
        return{
           tag: this.$route.params.tagname,
           posts: null,
           load: false,
        }
    },
    created: function(){
        this.getPostFromTag();
    },
    methods:{
        getPostFromTag(){
            console.log(this.tag , 'axios');
            axios.get('../api/tags/'+this.tag)
            .then((response)=>{
                console.log(response.data);
                if(response.data != false)
                    this.posts = response.data;
                this.load = true;
            })
            .catch((error)=>{
                console.log(error);
            });
        },
    }
}
</script>

<style lang="scss" scoped>
.header{
    width: 100%;
    height: 180px;
    display: flex;

    .image-box{
        width: 180px;
        height: 180px;

        .image{
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;

            img{
                min-width: 100%;
                height: 100%;
                flex-shrink: 0;
            }
        }
    }
}

.main{
    padding: 0 20px;

    
}

.tag-posts{
    margin: 0 auto;
    width: 90%;
    max-width: 1100px;
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
            width: 30%;
            height: 0;
            padding-bottom: 30%;
            cursor: pointer;

            a{
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
            }

            img{
                min-width: 100%;
                height: 100%;
                flex-shrink: 0;
                position: absolute;
            }
        }
    }
    
}
</style>