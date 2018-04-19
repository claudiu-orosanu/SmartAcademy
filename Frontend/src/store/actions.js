import axios from 'axios';
import config from '../config';

/**
 * Call api backend and get courses.
 *
 * @param commit
 * @param state
 * @param payload
 */
export const getPaginatedCourses = ({ commit, state }, payload) => {

  // make api call to get the paginated courses
  axios.get(`${config.apiUrl}/courses`, {
    params: {
      page: payload.page,
      itemsPerPage: payload.itemsPerPage,
      search: payload.searchTerm,
      categories: payload.categories
    },
  })
    .then(response => {
      let courses = response.data.data;
      if (courses.length) {
        commit('addCourses', courses);
        payload.infHandlerState.loaded();
      } else {
        payload.infHandlerState.complete();
      }
    })
    .catch(err => {
      console.log(err);
    })
}

/**
 * Delete the courses from store.
 *
 * @param commit
 */
export const clearCourses = ({ commit }) => {
  commit('setCourses', []);
}

/**
 * Sets the course selected by the user.
 *
 * @param commit
 * @param course
 */
export const setSelectedCourse = ({ commit }, course) => {
  commit('setSelectedCourse', course);
}

/**
 * Gets the course that the user clicked on, from the backend API.
 *
 * @param commit
 * @param courseId
 */
export const getSelectedCourse = ({ commit }, courseId) => {

  // make api call to get the selected course
  axios.get(`${config.apiUrl}/courses/` + courseId)
    .then(response => {
      commit('setSelectedCourse', response.data);
    })
    .catch(err => {
      console.log(err);
    })
}

/**
 * Resets the selected course.
 *
 * @param commit
 * @param courseId
 */
export const clearSelectedCourse = ({ commit }) => {
  commit('setSelectedCourse', {})
}



