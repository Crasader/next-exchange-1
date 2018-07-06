<template>
    <div class="article">
        <div class="article-header">
            <img class="article-author-avatar" :src="article.author.avatar_url" alt="">
            <span class="article-author-name">{{article.author.name}}</span>
            <span class="article-date">{{article.human_date}}</span>
        </div>
        <div class="article-text" style="">
            <div class="ql-editor" v-html="article.text">
            </div>
            <div v-if="article.image_url" class="article-image">
                <img :src="article.image_url" alt="">
            </div>
        </div>
        <div class="article-footer">
            <span class="like-article" @click="toggleArticleLike">
                <i :class="{'fa fa-heart-o': !article.is_liked, 'fa fa-heart': article.is_liked}"></i>
                {{article.likes_count}}
            </span>
            <span>
                <i class="fa fa-comment-o"></i>
                {{article.comments_count}}
            </span>
            <span><i class="fa fa-share-alt"></i></span>
        </div>
        <div class="article-comments">
            <ul>
                <li v-for="comment in comments">
                    <article-comment-view :comment="comment" />
                </li>
            </ul>
            <div class="input-comment">
                <img class="avatar" src="/img/default-avatar.png" alt="">
                <div class="publish-block">
                    <textarea v-model="comment" rows="1" type="text" placeholder="Add comment"></textarea>
                    <button v-show="comment.length > 0" @click="createComment" class="article-button">Publish</button>
                </div>
                <span class="text-danger" v-show="createCommentError">{{createCommentError}}</span>
            </div>
        </div>
    </div>
</template>

<script>
  import ArticleCommentView from "./ArticleCommentView";
  import {fetchData} from "../../helpers";
  import Img from "bootstrap-vue/es/components/image/img";

  export default {
      components: {Img, ArticleCommentView},
    name: "article-view",
    props: {
      article: {
        type: Object,
        required: true
      }
    },
    data () {
      return {
        createCommentError: '',
        comment: ''
      }
    },
    methods: {
      createComment () {
        const text = this.comment;
        this.comment = '';
        this.createCommentError = '';
        axios.post(`/article/${this.article.id}/comments`, {text: text})
          .then(fetchData)
          .then(comment => {
            this.article.comments.unshift(comment);
            this.article.comments_count++;
          })
          .catch((e) => {
            this.createCommentError = e.body.message;
            this.comment = text;
          })
      },
      toggleArticleLike () {
        axios.post(`/article/${this.article.id}/like`)
          .then(response => {
            this.article.likes_count = response.data.count;
            this.article.is_liked = response.data.is_liked
          })
      }
    },
    computed: {
      comments () {
        return this.article.comments.reverse();
      }
    }
  }
</script>

<style type="text/scss" lang="scss">
    .article {
        padding: 20px;
        box-shadow: 0px 10px 31px 0px rgba(0,0,0,0.75);
        margin-bottom: 30px;
    }

    .article.article-header {
        border-bottom: 2px solid #EEEEEE;
        margin: 10px;
    }

    .article-author-avatar {
        max-height: 70px;
        border-radius: 50%;
    }

    .article-author-name {
        font-weight: bold;
    }

    .article-text {
        margin: 10px;
        border-bottom: 2px solid #EEEEEE;
    }

    .article-footer {
        margin: 10px;
        border-bottom: 1px solid #EEEEEE;
    }

    .article .input-comment {
        padding: 20px;
    }

    .input-comment {
        display: flex;
        textarea {
            background: 0 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            resize: none;
            margin: 0;
            padding: 5px;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            box-shadow: none;
        }

        .avatar {
            height: 40px;
            border-radius: 50%;
        }
    }

    .like-article {
        cursor: pointer;
    }

    .publish-block {
        width: 100%;
    }

    .article-image {
        img {
            max-width: 100%;
        }
    }

    .article-button {
        background-color: #0073b1;
        border: 0;
        border-radius: 2px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-weight: 600;
        font-family: inherit;
        height: 24px;
        line-height: 24px;
        overflow: hidden;
        outline-width: 2px;
        padding: 0 16px;
        position: relative;
        text-align: center;
        text-decoration: none;
        transition-duration: 167ms;
        vertical-align: middle;
        z-index: 0;
    }

</style>