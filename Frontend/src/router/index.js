import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '../components/HelloWorld'
import Courses from '../components/courses/Courses'
import Course from '../components/courses/Course'
import CreateCourse from '../components/courses/CreateCourse/CreateCourse'
import Login from '../components/auth/Login'
import Register from '../components/auth/Register'
import RecoverPassword from '../components/auth/RecoverPassword'
import ResetPassword from '../components/auth/ResetPassword'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/register',
      name: 'Register',
      component: Register
    },
    {
      path: '/recoverPassword',
      name: 'RecoverPassword',
      component: RecoverPassword
    },
    {
      path: '/password/reset',
      name: 'ResetPassword',
      component: ResetPassword
    },
    {
      path: '/courses',
      name: 'Courses',
      component: Courses
    },
    {
      path: '/courses/create',
      name: 'CreateCourse',
      component: CreateCourse,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/courses/:id',
      name: 'Course',
      component: Course,
      meta: {
        requiresAuth: true
      }
    }
  ]
})

export default router;
