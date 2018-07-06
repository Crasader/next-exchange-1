<template>
    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="boxed boxed--lg boxed--border bg-white">
                        <div class="text-block text-center">
                            <img class="image--md" :src="user.avatar_url" :alt="user.name">
                            <br>
                            <span class="h5"><strong>{{ user.name }}</strong></span>
                            <br>
                            <span>{{ user.about_me }}</span>
                            <span class="label">{{ user.title }}</span>
                        </div>
                        <div class="text-block">
                            <div class="profile-stats-block" style="border-bottom: 1px solid #ececec">
                                <div class="profile-stat">
                                    <p><strong>{{ user.accepted_connections_count }}</strong></p>
                                    <p>Connections</p>
                                </div>
                                <div class="profile-stat">
                                    <p><strong>{{ user.followers_count }}</strong></p>
                                    <p>Followers</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <router-link :to="{name: 'project_create'}" class="btn btn-success btn-sm">Add Project</router-link>
                        <a href="" class="btn btn-primary btn-sm">Edit profile</a>
                    </div>
                    <div class="boxed boxed--lg boxed--border bg-white">
                        <h4><strong>My stats</strong></h4>
                        <div class="profile-stats-block" style="border-bottom: 1px solid #ececec">
                            <div class="profile-stat">
                                <p><strong>{{ user.likes_count }}</strong></p>
                                <p>Likes</p>
                            </div>
                            <div class="profile-stat">
                                <p><strong>{{ user.articles_count }}</strong></p>
                                <p>Articles</p>
                            </div>
                            <div class="profile-stat">
                                <p><strong>{{ user.projects_count }}</strong></p>
                                <p><a href="">Projects</a></p>
                            </div>
                            <div class="profile-stat">
                                <p><strong>{{ user.followers_count }}</strong></p>
                                <p>Views</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="unsolvedRequests.length > 0" class="boxed boxed--lg boxed--border bg-white">
                        <h6><strong>Pending connections requests</strong></h6>
                        <ul class="clearfix row row--list text-center">
                            <li v-for="connection in unsolvedRequests" class="col-sm-3 col-xs-6">
                                <router-link :to="{name: 'public_profile', params: {id: connection.id}}">
                                    <img alt="avatar" :src="connection.avatar_url" class="image--sm" />
                                    <span class="block">{{ connection.name }}</span>
                                </router-link>
                                <div class="btn-group">
                                    <button @click="onUpdateConnectionPress(connection, true, 'accepted')">Accept</button>
                                    <button @click="onUpdateConnectionPress(connection, false, 'declined')">Decline</button>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-md-8">
                    <create-article-view />
                    <articles-root/>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
  import ArticlesRoot from "../article/ArticlesRoot";
  import CreateArticleView from "../article/CreateArticleView";
  import {fetchData} from "../../helpers";

  export default {
    name: "profile-view",
    components: {
      ArticlesRoot,
      CreateArticleView
    },
    data () {
      return {
        user: this.$authenticatedUser,
        pendingConnections: []
      }
    },
    created () {
      axios.get('/api/user/connections?status=pending')
        .then(fetchData)
        .then(collection => {
          this.pendingConnections = collection.map(c => {
              c.solved = false;
              return c;
          });
        })
    },
    methods: {
      onUpdateConnectionPress (connection, status, verb) {
        axios.put(`/api/user/connections/${connection.id}/update`, {status: status})
          .then(response => {
            if (response.status === 202) {
              connection.solved = true;
              alert(`Connection request has been ${verb}`);
            }
          })
      }
    },
    computed: {
      unsolvedRequests () {
        return this.pendingConnections.filter(r => !r.solved)
      }

    }
  }
</script>

<style scoped>

</style>