import Vue from 'vue'
import Router from 'vue-router'
import Courses from '../components/courses/Courses'
import MyCourses from '../components/courses/MyCourses'
import Course from '../components/courses/Course'
import CourseDetails from '../components/courses/CourseDetails'
import CreateCourse from '../components/courses/CreateCourse/CreateCourse'
import Login from '../components/auth/Login'
import Register from '../components/auth/Register'
import TeacherRegister from '../components/auth/TeacherRegister'
import RecoverPassword from '../components/auth/RecoverPassword'
import ResetPassword from '../components/auth/ResetPassword'
import MyProfile from '../components/user/MyProfile'
import Profile from '../components/user/Profile'
import Dashboard from '../components/Dashboard'
import Teachers from '../components/Teachers'

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
      path: '/registerTeacher',
      name: 'TeacherRegister',
      component: TeacherRegister
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
      path: '/myCourses',
      name: 'MyCourses',
      component: MyCourses
    },
    {
      path: '/courses/create',
      name: 'CreateCourse',
      component: CreateCourse,
      meta: { requiresAuth: true }
    },
    {
      path: '/courses/:id',
      name: 'Course',
      component: Course,
      meta: { requiresAuth: true }
    },
    {
      path: '/courses/:id/details',
      name: 'CourseDetails',
      component: CourseDetails
    },
    {
      path: '/myProfile',
      name: 'MyProfile',
      component: MyProfile,
      meta: { requiresAuth: true }
    },
    {
      path: '/users/:id',
      name: 'Profile',
      component: Profile,
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      meta: { requiresAuth: true }
    },
    {
      path: '/teachers',
      name: 'Teachers',
      component: Teachers,
      meta: { requiresAuth: true }
    }
  ]
})

export default router;
