<template>
    <div class="post-box">
        <div class="header align-items-center justify-between">
            <div class="left align-items-center">
               <div class="profile-pic">
                   <img v-if="post.user && post.user.avatar" :src="'storage/'+post.user.avatar">
                   <div v-else class="image-404"></div>
               </div>
               <div v-if="post.user" class="profile-name">
                   {{post.user.name}}
               </div>
               <div v-else class="profile-name">
                   utente generato dal seed
               </div>
            </div>
            <div class="right">
                <router-link :to="{name : 'single-post' , params:{post:post,slug:post.slug}}">Vai al Post</router-link>
            </div>
        </div>

        <div class="main">
            <img v-if="post.image" :src="'storage/'+post.image">
            <div v-else class="image-404 align-items-center justify-center">
                <h1>{{post.title}}</h1>
            </div>
        </div>

        <div class="footer">
            <div class="content">
                {{post.content}}
                <a 
                    v-for="(tag,index) in post.tags"
                    :key="index"
                    :href="'/admin/tags/'+tag.id"
                    class="hashtag"
                >
                    #{{tag.name}}
                </a>
            </div>
            <div class="comments">
                <div
                    v-for="(comment,index) in comments.length > 5 ? comments.slice(0, 5*page) : comments"
                    :key="index"
                >
                    <h3 class="user-comment"> {{comment.name}} </h3>
                    <span style="color:black">: {{comment.content}} </span>
                </div>
                <h5 class="load-comments" v-if="5*page < comments.length" @click="page++"> Read more comments </h5>
            </div>
        </div>

        <div class="add-comment align-items-center">
            <div class="comment align-items-center justify-around">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256zM256 432C332.1 432 396.2 382 415.2 314.1C419.1 300.4 407.8 288 393.6 288H118.4C104.2 288 92.92 300.4 96.76 314.1C115.8 382 179.9 432 256 432V432zM133.5 114.7C125.6 110.4 116 116.2 116 125.1C116 127.9 116.1 130.6 118.8 132.8L154.8 176L118.8 219.2C116.1 221.4 116 224.1 116 226.9C116 235.8 125.6 241.6 133.5 237.3L223.4 189.4C234.1 183.7 234.1 168.3 223.4 162.6L133.5 114.7zM396 125.1C396 116.2 386.4 110.4 378.5 114.7L288.6 162.6C277.9 168.3 277.9 183.7 288.6 189.4L378.5 237.3C386.4 241.6 396 235.8 396 226.9C396 224.1 395 221.4 393.2 219.2L357.2 176L393.2 132.8C395 130.6 396 127.9 396 125.1V125.1z"/></svg>
                <input @keyup.enter="addComment" v-model="commentText" type="text" placeholder="Add a comment">
            </div>
           <div @click="addComment" class="publish align-items-center">
                Publish
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Post",
    props:{
        post: Object,
        mainUser: Object,
    },
    data(){
        return{
            page: 1,
            commentText : "",
            comments : this.post.comments,
        }
    },
    methods:{
        controlInput(){
            let temp = this.commentText;
            temp = temp.replace(/\s+/g, '');
            if(temp == null || temp == "" || temp == " ")
                return false;
        },
        addComment(){
            let control = this.controlInput();
            if(control == false)
                return;
            console.log(this.mainUser.name);
            axios.post('api/new-comment',{content: this.commentText, name:this.mainUser.name, user_id: this.mainUser.id, post_id: this.post.id})
            .then((response)=>{
                this.commentText = "";
                console.log(response.data);
                this.updatePost();
            });
        },
        updatePost(){
            axios.get('api/posts/p/'+this.post.slug)
            .then((response)=>{
                //console.log(response.data[0].comments);
                this.comments = response.data.comments;
            });
        },
    },
}
</script>

<style lang="scss" scoped>
img{
    width: 100%;
}
.user-comment{
    display: inline-block;
    color: black;
}

.load-comments{
    cursor: pointer;
    margin-left: 7px;
}

.post-box{
    overflow: hidden;
    border-radius: 4px;
    border: 1px solid rgb(212, 212, 212);
    width: 600px;
    margin: 30px auto;
    background-color: white;

    .header{
        height: 60px;
        padding: 0 20px;

        .image-404{
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: red;
        }

        .profile-pic{
            width: 30px;
            height: 30px;
            border-radius: 50%;
            position: relative;
            margin-right: 15px;

            img{
                border-radius: 50%;
                position: absolute;
                height: 100%;
                top:50%;
                left: 50%;
                transform: translate(-50% , -50%);
            }
        }
    }

    .hashtag{
        margin: 0 2px;
        text-decoration: none;
    }

    .footer{
        padding: 0 20px;
        overflow: auto;
        max-height: 300px;

        .content , .comments{
            margin: 8px 0;
        }

        .comments{
            overflow-wrap: anywhere;
            line-height: 1.5;
            color: grey;
        }
    }

    .add-comment{
            border-top: 1px solid rgb(212, 212, 212);
            height: 50px;

            .comment{
                width: 90%;

                input{
                    height: 25px;
                    font-size: 1.1em;
                    width: 90%;
                }
            }

            .publish{
                width: 10%;
                color: skyblue;

                &:hover{
                    cursor: pointer;
                    transform: scale(1.1);
                }
            }
        }
}

.main .image-404{
    width: 100%;
    height: 300px;
    background-color: red;
}
</style>