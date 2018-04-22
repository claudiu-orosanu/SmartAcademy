import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '../components/HelloWorld'
import Courses from '../components/courses/Courses'
import Course from '../components/courses/Course'
import CreateCourse from '../components/courses/CreateCourse'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: HelloWorld
    },
    {
      path: '/courses',
      name: 'Courses',
      component: Courses
    },
    {
      path: '/courses/create',
      name: 'CreateCourse',
      component: CreateCourse
    },
    {
      path: '/courses/:id',
      name: 'Course',
      component: Course,

    }
  ]
});
