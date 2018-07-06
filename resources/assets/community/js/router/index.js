import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

import InboxView from '../components/profile/InboxView'
import ProfileView from '../components/profile/ProfileView'
import ProjectsView from '../components/project/ProjectRoot'
import ProjectCreateView from '../components/project/CreateProjectView'
import PublicProfileView from '../components/profile/PublicProfileView'

const router = new Router({
  linkActiveClass: 'active',
  mode: 'history',
  routes: [
    {
      path: '/profile',
      name: 'profile',
      component: ProfileView
    },
    {
      path: '/profile/inbox',
      name: 'inbox',
      component: InboxView
    },
    {
      path: '/profile/projects',
      name: 'projects',
      component: ProjectsView
    },
    {
      path: '/profile/:id',
      name: 'public_profile',
      component: PublicProfileView
    },
    {
      path: '/project/create',
      name: 'project_create',
      component: ProjectCreateView
    }
  ]
});


export default router