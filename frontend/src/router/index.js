import Vue from 'vue'
import VueRouter from 'vue-router'

//import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import ApplicationList from '../views/application/List.vue'
import ApplicationCreate from '../views/application/Create.vue'
import UserList from '../views/user/List.vue'
import UserCreate from '../views/user/Create.vue'
import store from '../store/index'
 
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: store.getters.isAdmin ? UserList : ApplicationList,
    meta: {
      requireAuthentication: true 
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: {
      requireAuthentication: false 
    }
  },
  {
    path: '/applications',
    name: 'ApplicationList',
    component: ApplicationList,
    meta: {
      requireAuthentication: true 
    }
  },
  {
    path: '/applications/create',
    name: 'ApplicationCreate',
    component: ApplicationCreate,
    meta: {
      requireAuthentication: true 
    }
  },
  {
    path: '/users',
    name: 'UserList',
    component: UserList,
    meta: {
      requireAuthentication: true 
    }
  },
  {
    path: '/users/create',
    name: 'UserCreate',
    component: UserCreate,
    meta: {
      requireAuthentication: true 
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

router.beforeEach(function(to, from, next){
  if (to.meta.requireAuthentication && !store.getters.isAuthenticated)
    next('/login');
  next();
});

export default router