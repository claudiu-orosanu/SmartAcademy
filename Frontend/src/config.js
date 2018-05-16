import router from "./router";

export const apiUrl = 'http://smart-academy.test/api/v1';
export const backendUrl = 'http://smart-academy.test';

export function init(store, router) {
  router.beforeEach((to, from ,next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const isAuthenticated = store.getters.isAuthenticated;

    if(requiresAuth && !isAuthenticated) {
      next('/login');
    }
    else if (to.path == '/login' && isAuthenticated) {
      next('/');
    }
    else {
      next();
    }
  })


  const axios = require("axios");

  // set default headers for all http requests
  axios.defaults.headers.common['Accept'] = 'application/json';

  // set authorization header
  axios.interceptors.request.use(
    request => {
      if(store.state.currentUser) {
        request.headers['Authorization'] = 'Bearer ' + store.state.currentUser.token;
      }
      return request;
    }
  )

  axios.interceptors.response.use(
    response => {
      return response;
    },
    error => {

      if(error.response.status == 401) {
        store.commit('setCurrentUser', null);
        router.push('/login');
      }

      return Promise.reject(error);
  })


}
