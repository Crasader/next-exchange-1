<template>
    <div class="boxed boxed--border bg-white">
        <h4>Articles</h4>
        <div v-if="articles.length > 0" class="articles-list">
            <ul>
                <li v-for="article in articles">
                    <article-item :article="article"/>
                </li>
            </ul>
        </div>
        <h5 v-else>Sadly, there is no articles found, yet</h5>
    </div>
</template>

<script>
  import ArticleItem from './ArticleView'
  import {fetchData} from "../../helpers";
  import CreateArticleView from "./CreateArticleView";

  export default {
    name: "articles-root",
    components: {
      CreateArticleView,
      ArticleItem
    },
    data() {
      return {
        articles: []
      }
    },
    created() {
      const userId = location.pathname.split('/')[2] || 0;
      axios.get(`/profile/${userId}/articles?include=author,comments.author`)
        .then(fetchData)
        .then((articles) => {
          this.articles = articles;
        })
    }
  }
</script>

<style scoped>

</style>