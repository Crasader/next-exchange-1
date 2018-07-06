<template>
    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-md-offset-2 ">
                    <div class="boxed boxed--lg boxed--border bg-white">
                        <div class="text-block text-center">
                            <img class="image--md" :src="user.avatar_url" :alt="user.name">
                            <br>
                            <span class="h5">{{ user.name }}</span>
                            <br>
                            <span>{{ user.about_me }}</span>
                            <span class="label">{{ user.title }}</span>
                        </div>
                        <div v-if="!isSameUser" class="text-center">
                            <div class="btn-block">
                                <button @click="onFollowPress" class="btn btn--small btn--outline">
                                    {{followText}}
                                </button>
                                <button @click="onLikePress" class="btn btn--small btn--outline">
                                    {{likeText}}
                                </button>
                            </div>
                        </div>
                        <div v-if="!isSameUser && user.is_connected" class="text-center">
                            <div class="btn-block">
                                <button @click="onSendMessagePress" class="btn btn--small btn--outline">
                                    Send message
                                </button>
                            </div>
                        </div>
                        <div class="text-block clearfix text-center">
                            <ul class="row row--list">
                                <li class="col-sm-4">
                                    <span class="type--fine-print block"><b>Location</b></span>
                                    <span>{{ user.location }}&nbsp;</span>
                                </li>
                                <li class="col-sm-4">
                                    <span class="type--fine-print block"><b>Member Since</b></span>
                                    <span>{{ user.member_since }}</span>
                                </li>
                                <li class="col-sm-4">
                                    <span class="type--fine-print block"><b>Contact</b></span>

                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="boxed boxed--border bg-white">
                        <ul class="row row--list clearfix text-center">
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Likes</span>
                                <span class="h3">{{ user.likes_count }}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Articles</span>
                                <span class="h3">{{ user.articles_count }}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Projects</span>
                                <span class="h3">{{ user.projects_count }}</span>
                            </li>
                            <li class="col-sm-3 col-xs-6">
                                <span class="h6 type--uppercase type--fade">Following</span>
                                <span class="h3">{{ user.followers_count }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="boxed boxed--border bg-white">
                        <h4>
                            Connections
                            <button v-show="!isSameUser && !user.is_connected" @click="onSendConnectionRequestPress">Send request...</button>
                        </h4>
                        <template v-if="user.acceptedConnections && user.acceptedConnections.length > 0">
                            <ul class="clearfix row row--list text-center">
                                <li v-for="connection in user.acceptedConnections" class="col-sm-3 col-xs-6">
                                    <router-link :to="{name: 'public_profile', params: {id: connection.id}}">
                                        <img alt="avatar" :src="connection.avatar_url" class="image--sm" />
                                        <span class="block">{{ connection.name }}</span>
                                    </router-link>
                                </li>
                            </ul>
                            <a href="#" class="type--fine-print pull-right">View All</a>
                        </template>
                        <template v-else="">
                            No connections found
                        </template>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <create-article-view v-if="isSameUser" />
                            <articles-root />
                        </div>
                        <div class="col-md-4">
                            <div class="boxed boxed--border bg-white">
                                <h3>Next to that a column with a button ‘add my project’ or ‘edit my profile’
                                    Besides of that I want a my stats, my coins and my projects</h3>
                            </div>
                        </div>
                    </div>

                    <!--<div class="boxed boxed&#45;&#45;border">-->
                        <!--<h4>Recent Activity</h4>-->
                        <!--<ul>-->
                            <!--<li class="clearfix">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-md-2 col-xs-3 text-center">-->
                                        <!--<div class="icon-circle">-->
                                            <!--<i class="icon icon&#45;&#45;lg material-icons">mode_edit</i>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="col-md-8 col-xs-7">-->
                                        <!--<span class="type&#45;&#45;fine-print">21st August, 2017</span>-->
                                        <!--<a href="#" class="block color&#45;&#45;primary">Making the whitepaper</a>-->
                                        <!--<p>-->
                                            <!--Making the white-paper for the ICO launch in September.-->
                                        <!--</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                                <!--<hr>-->
                            <!--</li>-->
                            <!--<li class="clearfix">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-md-2 col-xs-3 text-center">-->
                                        <!--<div class="icon-circle">-->
                                            <!--<i class="icon icon&#45;&#45;lg material-icons">comment</i>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-8 col-xs-7">-->
                                        <!--<span class="type&#45;&#45;fine-print">14th August, 2017</span>-->
                                        <!--<a href="#" class="block color&#45;&#45;primary">New website!</a>-->
                                        <!--<p>-->
                                            <!--Launched the new website and ico landing page at <a href="https://ico.corporatefinancehouse.com">https://ico.corporatefinancehouse.com</a>-->
                                        <!--</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                                <!--<hr>-->
                            <!--</li>-->
                        <!--</ul>-->
                        <!--<a href="#" class="type&#45;&#45;fine-print pull-right">View All</a>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  import ArticlesRoot from "../article/ArticlesRoot";
  import CreateArticleView from "../article/CreateArticleView";
  import {fetchData, isAdmin} from "../../helpers";

  const setVar = (context, key) => (val) => context[key] = val;

  export default {
    name: "public-profile-view",
    components: {
      ArticlesRoot,
      CreateArticleView,
    },
    data () {
      return {
        user: {}
      }
    },
    created () {
      const id = this.$route.params.id;
      axios.get(`/api/user/profile/${id}?include=acceptedConnections`)
        .then(fetchData)
        .then(setVar(this, 'user'));
    },
    methods: {
      onLikePress () {
        axios.post(`/profile/${this.user.id}/like`)
          .then((res) => {
            this.user.is_liked = res.data.liked;
            this.user.is_liked ? this.user.likes_count++ : this.user.likes_count--
          })
      },
      onFollowPress () {
        axios.post(`/profile/${this.user.id}/follow`)
          .then((res) => {
            this.user.is_followed = res.data.followed;
            this.user.is_followed ? this.user.followers_count++ : this.user.followers_count--
          })
      },
      onSendConnectionRequestPress () {
        axios.post(`/api/user/${this.user.id}/connections/send`)
            .then(response => {
              if (response.status === 202) {
                this.user.is_connected = true;
                alert('Good work, connection request successfully send (this will be replaced with toast)');
                console.log('Connection request has been send');
              }
            })
      },
      onSendMessagePress () {
        if (this.user.has_private_conversation) {
          return this.$router.push({name: 'inbox'});
        }
        axios.post(`/api/user/${this.user.id}/conversation`)
          .then(response => {
            if (response.status === 201) {
              this.$router.push({name: 'inbox'});
            }
          })
      }
    },
    computed: {
      authUserIsAdmin () {
        return isAdmin(this.$authenticatedUser)
      },
      isSameUser () {
        return this.$authenticatedUser.id === parseInt(this.$route.params.id);
      },
      followText () {
        return this.user.is_followed ? 'Unfollow' : 'Follow';
      },
      likeText () {
        return this.user.is_liked ? 'Unline' : 'Like';
      }
    }
  }
</script>

<style scoped>

</style>