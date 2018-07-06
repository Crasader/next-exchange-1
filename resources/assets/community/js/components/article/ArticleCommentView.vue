<template>
    <div class="article-comment">
        <div class="article-comment-author-avatar">
            <img :src="comment.author.avatar_url" alt="">
        </div>
        <div class="article-info-block">
            <div class="heading">
                <span class="article-author-name">{{ comment.author.name }}</span>
                <span>{{ comment.human_date }}</span>
            </div>
            <div class="comment-body">{{ comment.text }}</div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "article-comment-view",
    props: {
      comment: {
        type: Object,
        required: true
      }
    }
  }
</script>

<style type="text/scss" lang="scss">
    .article-comment {
        display: flex;
        flex-direction: row;
        margin: 20px;
        justify-content: flex-start;
        border-bottom: 2px solid #EEEEEE;
    }

    .article-info-block {
        margin-bottom: 10px;
        width: 95%;
    }

    .article-info-block .heading {
        display: flex;
        justify-content: space-between;
    }

    .article-comment-author-avatar img {
        margin-right: 10px;
        max-width: 50px;
        border-radius: 50%;
    }

    .comment-body {
        word-wrap: break-word;
    }
</style>