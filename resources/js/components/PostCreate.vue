<template>
  <div class="container">
     <!-- <div class="opacity-box"></div> -->
      <div class="create-box">
          <div v-if="!successfullyCreated" class="title align-items-center justify-center">
              <h3>Create a new post</h3>
              <div @click="imageError == false ? formBox = true : null" v-if="image && !formBox" class="go-ahead">
                  Avanti
              </div>
              <div v-else-if="formBox && !postCreated" @click="createPost" class="submit go-ahead">
                  Create
              </div>
              <div v-else-if="postCreated" @click="turnToPost" class="go-ahead">
                  X
              </div>
          </div>

          <div v-else-if="successfullyCreated" class="title align-items-center justify-center">
              <h3>Post Created !! </h3>
              <div @click="turnToPost" class="go-ahead">
                  X
              </div>
          </div>
          
          <div v-if="!successfullyCreated" class="create-form">
              <div v-if="!image" class="load-image align-items-center justify-center">
                <svg class="new-image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256zM256 368C269.3 368 280 357.3 280 344V280H344C357.3 280 368 269.3 368 256C368 242.7 357.3 232 344 232H280V168C280 154.7 269.3 144 256 144C242.7 144 232 154.7 232 168V232H168C154.7 232 144 242.7 144 256C144 269.3 154.7 280 168 280H232V344C232 357.3 242.7 368 256 368z"/></svg>
                <h3>Trascina le foto e i video qui</h3>
                <input @change="onImageChange" type="file">
             </div>

            <div v-else-if="image" class="load-image align-items-center justify-center">
                <img :src="image" alt="">
            </div>

            <div v-if="formBox" class="form-box">
                <div class="main-form">
                    <div class="header profile">
                        <div class="image">
                            <img :src="'../storage/'+mainUser.avatar" alt="">
                        </div>
                        <h3> {{mainUser.name}} </h3>
                    </div>

                    <div class="main content">
                        <input v-model="title" type="text" placeholder="Title..">
                        <hr>
                        <textarea v-model="content" name="" id="" placeholder="Your Content.." cols="30" rows="10"></textarea>
                        <div class="emoji-and-count">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M256 352C293.2 352 319.2 334.5 334.4 318.1C343.3 308.4 358.5 307.7 368.3 316.7C378 325.7 378.6 340.9 369.6 350.6C347.7 374.5 309.7 400 256 400C202.3 400 164.3 374.5 142.4 350.6C133.4 340.9 133.1 325.7 143.7 316.7C153.5 307.7 168.7 308.4 177.6 318.1C192.8 334.5 218.8 352 256 352zM208.4 208C208.4 225.7 194 240 176.4 240C158.7 240 144.4 225.7 144.4 208C144.4 190.3 158.7 176 176.4 176C194 176 208.4 190.3 208.4 208zM304.4 208C304.4 190.3 318.7 176 336.4 176C354 176 368.4 190.3 368.4 208C368.4 225.7 354 240 336.4 240C318.7 240 304.4 225.7 304.4 208zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 48C141.1 48 48 141.1 48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48z"/></svg>
                            <div id="count-content">
                                {{updateContentCount}} / 1000
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="hashtag">
                        <input v-model="hashtag" type="text" placeholder="add HashTag">
                    </div>
                </div>
            </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    name: "PostCreate",
    data(){
        return{
            title: "",
            content: "",
            hashtag: "",
            image: null,
            formBox: false,
            formData : null,
            postCreated: false,
            imageError: false,
            successfullyCreated: false,
        }
    },
    props:{
        mainUser: Object,
    },
    computed:{
        updateContentCount(){
            return this.content.length
        },
    },
    methods:{
        successClear(){
            this.title= "";
            this.content= "";
            this.hashtag= "";
            this.image= null;
            this.formBox= false;
            this.formData = null;
            this.postCreated= false;
            this.imageError= false;
        },
        turnToPost(){
            this.$emit('turnToPost');
        },
        onImageChange(e) {
            this.imageError = false;
            let file = e.target.files[0];
            if(file.type != 'image/jpeg' && file.type != 'image/png'  && file.type != 'image/jpg'){
                this.imageError = true;
                return;
            }
            this.image = URL.createObjectURL(file);

            this.formData = new FormData();
            this.formData.append("image", file);
            console.log(this.formData);
        },
        createPost(){
            if(this.imageError == true)
                return;
            if(!this.title || this.title == "" || this.title == " "
            && !this.content || this.content == "" || this.content == " ")
                return;
            this.formData.append("title", this.title);
            this.formData.append("content", this.content);
            this.formData.append("user_id", this.mainUser.id);
            this.formData.append("tag", this.hashtag);

        axios.post('api/new-post', this.formData , {  
            headers: {
                "Content-Type": "multipart/form-data",
            },
            }).then((response) => {
                console.log(response);
                this.postCreated = true;
                this.successClear();
                this.successfullyCreated = true;
            }).catch((imageError) => {
            });
        },
    },
}
</script>

<style lang="scss" scoped>
.title{
    text-align: center;
    border-bottom: 1px solid grey;
    height: 50px;
    position: relative;
}

.create-form{
    overflow: hidden;
    height: calc(100% - 50px);
    display: flex;

    svg.new-image{
        width: 100px;
        fill: #aae6ff;
        margin: 20px 0;
    }

    .load-image input{
        margin: 20px 0;
    }
}

.main-form{
    padding: 0 10px;
}

.form-box{
    width: 350px;

    .header{
        height: 50px;
        display: flex;
        justify-content: flex-start;
        align-items: center;

        .image{
            width: 30px;
            height: 30px;
            margin-right: 10px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;

            img{
                min-width: 100%;
                min-height: 100%;
                flex-shrink: 0;
            }
        }
    }

    .main{
        .emoji-and-count {
            display: flex;
            height: 40px;
            justify-content: space-between;
            align-items: center;

            #count-content{
                padding-right: 5px;
                font-size: 0.85em;
            }

            svg{
                fill:gray;
            }
        }

        input{
            width: 100%;
            font-size: 1.1em;
            height: 40px;
        }

        textarea{
            width: 100%;
            font-size: 1.3em;
            margin-top: 10px;
            padding: 10px 0 ;
            height: 170px;
            border: unset;
            resize: none;

            &:focus-visible{
                outline: unset;
            }
        }
    }

    .footer{
        border-top: 1px solid grey;
        border-bottom: 1px solid grey;
        height: 50px;

        .hashtag{
            padding: 0 10px;
            height: 50px;

            input{
                height: 50px;
            }
        }
    }
}

.load-image{
    overflow: hidden;
    height: 100%;
    width: 100%;
    max-width: 450px;
    flex-direction: column;

    img{
        min-height: 100%;
        min-width: 100%;
        flex-shrink: 1;
    }
}

.go-ahead{
    position: absolute;
    right: 20px;
    color: skyblue;
    cursor: pointer;
}



.create-box{
    background-color: white;
    z-index: 10001;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50% , -50%);
    border-radius: 20px;
    height: 450px;
    min-width: 400px;
}
</style>