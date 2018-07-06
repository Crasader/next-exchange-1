<template>
    <main>
        <iframe v-show="project.official_video_url " :src="project.official_video_url" height="300px" width="100%"></iframe>
        <nav>
            <div class="nav nav-tabs project-nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-overview-tab" data-toggle="tab" href="#nav-overview" role="tab" aria-controls="nav-overview" aria-selected="true"><strong>Overview</strong></a>
                <a class="nav-item nav-link" id="nav-team-tab" data-toggle="tab" href="#nav-team" role="tab" aria-controls="nav-team" aria-selected="false"><strong>Team</strong></a>
                <a class="nav-item nav-link" id="nav-links-tab" data-toggle="tab" href="#nav-links" role="tab" aria-controls="nav-links" aria-selected="false"><strong>Links</strong></a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                <p><strong>Project name: </strong>{{project.name}}</p>
                <p><strong>Coin symbol: </strong>{{project.symbol}}</p>
                <p><strong>Launch date: </strong>{{project.launch_date}}</p>
                <p><strong>Total supply: </strong>{{project.total_supply_token}}</p>
                <p><strong>Price per coin: </strong>${{project.initial_price}}</p>
                <p v-show="project.full_description"><strong>Description: </strong>{{project.full_description}}</p>
            </div>
            <div class="tab-pane fade" id="nav-team" role="tabpanel" aria-labelledby="nav-team-tab">
                <ul class="clearfix row row--list text-center">
                    <li v-for="member in members" class="col-sm-3 col-xs-6">
                        <a :href="'/profile/' + member.id">
                            <img alt="avatar" :src="member.avatar_url" class="image--sm" />
                            <span class="block"><strong>{{member.name}}</strong></span>
                        </a>
                        <small v-show="member.projectRoles[0]">{{member.projectRoles[0].display_name}}</small>
                    </li>
                </ul>
            </div>
            <div class="tab-pane fade" id="nav-links" role="tabpanel" aria-labelledby="nav-links-tab">
                <p v-show="project.website_url">
                    <a :href="project.website_url">
                        <i class="fa fa-globe"></i>
                        Website
                    </a>
                </p>
                <p v-show="project.whitepaper_url">
                    <a :href="project.whitepaper_url">
                        <i class="fa fa-file"></i>
                        Whitepaper
                    </a>
                </p>
                <p v-show="project.twitter_url">
                    <a :href="project.twitter_url">
                        <i class="fa fa-twitter"></i>
                        Twitter
                    </a>
                </p>
                <p v-show="project.facebook_url">
                    <a :href="project.facebook_url">
                        <i class="fa fa-facebook"></i>
                        Facebook
                    </a>
                </p>
                <p v-show="project.telegram_url">
                    <a :href="project.telegram_url">
                        <i class="fa fa-telegram"></i>
                        Telegram
                    </a>
                </p>
                <p v-show="project.bitcointalk_url">
                    <a :href="project.bitcointalk_url">
                        <i class="fa fa-bitcoin"></i>
                        Bitcointalk
                    </a>
                </p>
            </div>
        </div>
    </main>
</template>

<script>
  import {fetchData} from "../../helpers";

  export default {
    name: "project-view",
    props: {
      project: {
        type: Object,
        required: true
      }
    },
    data () {
      return {
        members: []
      }
    },
    created () {
      axios.get(`/project/${this.project.id}/members`)
        .then(fetchData)
        .then(members => {
          this.members = members
        })
    }
  }
</script>

<style lang="scss" type="text/scss">
    .project-nav-tabs a {
        color: #aebacd;
    }
</style>