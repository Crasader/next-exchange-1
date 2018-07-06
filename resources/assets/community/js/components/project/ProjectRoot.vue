<template>
    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <main class="row">
                <div class="col-md-5">
                    <div class="next-table">
                        <div class="next-table-content__left-labels">
                            <div class="col-4">Project name</div>
                            <div class="col-2 text-right">Symbol</div>
                            <div class="col-3 text-right">Launch</div>
                            <div class="col-3 text-right">Price</div>
                        </div>
                        <div class="next-table-content__left-funds-scroll">
                            <div id="top-sorted">
                                <div>
                                    <div class="next-table-content__left-currencies">
                                        <ul>
                                            <li v-for="project in projects"
                                                class="row project-item"
                                                @click="selectProject(project)"
                                                :class="{'active': project.id === selectedProject.id}">
                                                <div class="col-4 col-md-offset-2">{{project.name}}</div>
                                                <div class="col-2 text-right">{{project.symbol}}</div>
                                                <div class="col-3 text-right">{{project.launch_date}}</div>
                                                <div class="col-3 text-right">${{project.initial_price}}</div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div v-if="selectedProject.id" class="boxed boxed--border bg-white">
                        <project-view :project="selectedProject"/>
                    </div>
                </div>
            </main>
        </div>
    </section>
</template>

<script>
  import {fetchData} from "../../helpers";
  import ProjectView from "./ProjectView";

  export default {
    components: {ProjectView},
    name: "project-root",
    data() {
      return {
        projects: [],
        selectedProject: {}
      }
    },
    created() {
      axios.get('/profile/projects/json')
        .then(fetchData)
        .then(data => {
          this.projects = data;
          this.selectProject(this.projects[0])
        })
    },
    methods: {
      selectProject(project) {
        return this.selectedProject = project
      }
    }
  }
</script>

<style type="text/scss" lang="scss" scoped>
    .project-item {
        font-size: 0.8em;
    }

    .project-item.active {
        background-color: #f6f9fc;
    }

    .next-table {
        margin: 0 0 0 0;
    }
</style>