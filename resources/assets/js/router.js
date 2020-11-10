import Vue from 'vue'
//import store from './state/store'
import VueRouter from 'vue-router'
import dash from './components/Dashboard.vue'
//import Settings from './components/Settings.vue'
//import Campaigns from './components/Campaigns.vue'
//import Campaign from './components/Campaign.vue'
//import Searches from './components/Searches.vue'
//import Contacts from './components/Contacts.vue'
//import Templates from './components/Messeging.vue'


const Bus = new Vue({})

window.Bus = Bus

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  linkActiveClass: 'active',
  routes: [
    {
      path: '/home',
      component: dash,
      meta: {
        auth: true,
        home: true
      }
    },
//    {
//      path: '/settings',
//      component: Settings,
//      meta: {
//        auth: true,
//        settings: true
//      }
//    },
//    {
//      path: '/campaigns',
//      component: Campaigns,
//      meta: {
//        auth: true,
//        campaigns: true
//      }
//    },
//    {
//      path: '/campaign/:id',
//      component: Campaign,
//      meta: {
//        auth: true,
//        campaigns: true
//      }
//    },
//    {
//      path: '/search-results/:id/:name',
//      component: Searches,
//      meta: {
//        auth: true,
//        campaigns: true
//      }
//    },
//    {
//      path: '/contacts',
//      component: Contacts,
//      meta: {
//        auth: true,
//        contacts: true
//      }
//    },
//    {
//      path: '/templates',
//      component: Templates,
//      meta: {
//        auth: true,
//        contacts: true
//      }
//    },
  ]
  
  
    

// router.beforeEach((to, from, next) => {
//   axios.get('checkAuth').then(response => {
//     if (response.status === 200) {
//       next()
//     }
//   }).catch(error => {
//     window.location.href = '/logout'
//   })

//   setTimeout(() => {
//     if (to.meta.auth && store.state.user.is_admin) {
//       next()
//     } else {
//       next({
//         path: '/'
//       })
//     }
//   }, 500)
 })

router.afterEach((to, from) => {
  if (to.name === 'home') {
    setTimeout(() => {
      Bus.$emit('loadSettings')
    }, 500)
  }
})

export default router
