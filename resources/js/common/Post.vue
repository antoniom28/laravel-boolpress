<template>
    <div class="post-box">
        <div class="header align-items-center justify-between">
            <div class="left align-items-center">
               <router-link class="searched-user" :to="{name : 'searched-user' , params:{slug:post.user.slug} }">
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
               </router-link>
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
            <div class="like-box">
                <svg v-if="!yourLike" @click="addLike" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M244 84L255.1 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 0 232.4 0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84C243.1 84 244 84.01 244 84L244 84zM255.1 163.9L210.1 117.1C188.4 96.28 157.6 86.4 127.3 91.44C81.55 99.07 48 138.7 48 185.1V190.9C48 219.1 59.71 246.1 80.34 265.3L256 429.3L431.7 265.3C452.3 246.1 464 219.1 464 190.9V185.1C464 138.7 430.4 99.07 384.7 91.44C354.4 86.4 323.6 96.28 301.9 117.1L255.1 163.9z"/></svg>
                <svg v-else class="your-like" @click="addLike" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84.02L256 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 .0003 232.4 .0003 190.9L0 190.9z"/></svg>
                <p v-if="likeNum > 0">
                    piace a {{likeNum}} <span style="cursor:pointer" @click="showLikeBox = true"> <u>persone</u> </span>
                </p>
            </div>
            <div class="content">
                {{post.content}}
                <router-link 
                    v-for="(tag,index) in post.tags"
                    :key="index"
                    class="hashtag" 
                    :to="{name : 'search-tag' , params:{tagname:tag.name , tag:tag} }"
                >
                    #{{tag.name}}
                </router-link>
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

        <div @click="showLikeBox = false" v-show="showLikeBox == true" class="hidden-close-sub create"></div>
        <div v-if="showLikeBox" class="like-list">
            <div class="profile-box align-items-center"
                v-for="(like,index) in likes"
                :key="'like'+index"
            >
                <router-link class="searched-user" :to="{name : 'searched-user' , params:{slug:like.userslug} }">
                    <div class="avatar">
                        <img :src="'storage/'+like.avatar" alt="">
                    </div>
                    <div class="profile">
                        {{like.username}}
                    </div>
                </router-link>
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
            showLikeBox: false,
            likeNum: 0,
            yourLike: false,
            page: 1,
            likes: this.post.likes,
            commentText : "",
            comments : this.post.comments,
        }
    },
    created: function(){
        let control = this.post.likes;
        this.likeNum = control.length;
        console.log(control.length);
        for(let i=0; i<control.length; i++){
            if(control[i].user_id == this.mainUser.id && control[i].post_id == this.post.id){
                console.log('hai messo like');
                this.yourLike = true;
            }
        }
    },
    methods:{
        addLike(){
            console.log(this.mainUser);
            axios.post('api/new-like' , {user_id: this.mainUser.id , username: this.mainUser.name , userslug: this.mainUser.slug , avatar: this.mainUser.avatar , post_id: this.post.id })
            .then((response)=>{
                console.log(response.data.added);
                this.yourLike = response.data.added;
                this.updatePost();
            });
        },
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
                console.log(response.data.likes.length);
                this.comments = response.data.comments;
                this.likes = response.data.likes;
                this.likeNum = response.data.likes.length;
            });
        },
    },
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

.like-list{
    background-color: white;
    z-index: 10001;
    overflow: auto;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -48%);
    border-radius: 20px;
    padding: 20px 0;
    max-height: 480px;
    width: 350px;

    .profile-box{
        width: 100%;
        padding: 0 15px;
        height: 50px;
        margin: 5px 0;
        cursor: pointer;

        a{
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        &:hover{
            background-color: rgb(209, 209, 209);
        }
        
        .profile{
            margin: 0 10px;
        }

        .avatar{
            border-radius: 50%;
            overflow: hidden;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;

            img{
                min-height: 100%;
                min-width: 100%;
                flex-shrink: 0;
            }
        }
    }
}

img{
    width: 100%;
}

.your-like{
    fill: red;
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

        a{
            display: flex;
            align-items: center;
            text-decoration: none;
        }

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

        .content , .comments, .like-box{
            margin: 8px 0;
        }

        .like-box svg{
            cursor: pointer;
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