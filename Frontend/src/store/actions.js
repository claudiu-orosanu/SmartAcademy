import axios from 'axios';
import {apiUrl, backendUrl} from '../config';
import constants from '../constants';

/**
 * Call api backend and get courses.
 *
 * @param commit
 * @param state
 * @param payload
 */
export const getPaginatedCourses = ({commit, state}, payload) => {

  return new Promise((resolve, reject) => {

    // make api call to get the paginated courses
    axios.get(`${apiUrl}/courses`, {
      params: {
        page: payload.page,
        itemsPerPage: payload.itemsPerPage,
        search: payload.searchTerm,
        categories: payload.categories
      }
    })
      .then(response => {
        let courses = response.data.data;
        if (courses.length) {

          if (payload.searchOrFilterInProgress) {
            commit('setCourses', []);
          }

          commit('addCourses', courses);
          payload.infHandlerState.loaded();
        } else {
          payload.infHandlerState.complete();
        }

        resolve();
      })
      .catch(err => {
        reject(err);
      })
  });


}

/**
 * Delete the courses from store.
 *
 * @param commit
 */
export const clearCourses = ({commit}) => {
  commit('setCourses', []);
}

/**
 * Sets the course selected by the user.
 *
 * @param commit
 * @param course
 */
export const setSelectedCourse = ({commit}, course) => {
  commit('setSelectedCourse', course);
}

/**
 * Gets the course that the user clicked on, from the backend API.
 *
 * @param commit
 * @param courseId
 */
export const getSelectedCourse = ({commit}, courseId) => {

  return new Promise((resolve, reject) => {

    // make api call to get the selected course
    axios.get(`${apiUrl}/courses/` + courseId, {
      params: {
        XDEBUG_SESSION_START: 'PHPSTORM'
      }
    })
      .then(response => {
        commit('setSelectedCourse', response.data);
        resolve();
      })
      .catch(err => {
        reject(err);
      })

  });
}

/**
 * Resets the selected course.
 *
 * @param commit
 */
export const clearSelectedCourse = ({commit}) => {
  commit('setSelectedCourse', {})
}

/**
 * Creates a course.
 *
 * @param commit
 * @param course
 */
export const createCourse = ({commit}, course) => {

  return new Promise((resolve, reject) => {

    const fd = new FormData();
    fd.append('name', course.name);
    fd.append('description', course.description);
    fd.append('category', constants.courseCategoriesMapping[course.category]);
    fd.append('price', course.price);
    fd.append('image', course.image);

    for (let i in course.sectionsData) {
      fd.append('sectionNames[]', course.sectionsData[i].name);
      fd.append('videos[]', course.sectionsData[i].video);
      fd.append('documents[]', course.sectionsData[i].pdf);
      fd.append('exams[]', JSON.stringify(course.sectionsData[i].exam));
    }

    axios.post(`${apiUrl}/courses`, fd, {
      params: {
        XDEBUG_SESSION_START: 'PHPSTORM'
      }
    })
      .then((response) => {
        resolve(response);
      })
      .catch(err => {
        let errors = _.flatten(Object.values(err.response.data.errors));
        reject(errors);
      });
  });
}


/**
 * Authentication actions
 */
export const login = ({commit}, credentials) => {
  return new Promise((resolve, reject) => {
    axios.post(`${apiUrl}/auth/login`, credentials)
      .then(response => {

        let user = Object.assign({}, response.data.user, {
          token: response.data.access_token
        });

        commit('setCurrentUser', user);
        resolve();
      })
      .catch(err => {
        let errors;
        if (err.response.status == 401)
          errors = ['Wrong credentials! Please make sure you typed correctly!'];
        else {
          errors = _.flatten(Object.values(err.response.data.errors));
        }
        reject(errors);
      });
  });
}

export const register = ({commit}, credentials) => {
  return new Promise((resolve, reject) => {
    axios.post(`${apiUrl}/auth/register`, credentials)
      .then(response => {
        resolve(response);
      })
      .catch(err => {
        let errors = _.flatten(Object.values(err.response.data.errors));
        reject(errors);
      });
  });
}

export const logout = ({commit, state}) => {
  return new Promise((resolve, reject) => {
    axios.post(`${apiUrl}/auth/logout`)
      .then(response => {
        commit('setCurrentUser', null);
        resolve(response);
      })
      .catch(err => {
        reject(err);
      });
  });
}

export const recoverPassword = ({commit}, credentials) => {
  return new Promise((resolve, reject) => {
    axios.post(`${apiUrl}/auth/resetPassword`, credentials)
      .then(response => {
        resolve(response);
      })
      .catch(err => {
        reject(err);
      });
  });
}

export const resetPassword = ({commit}, credentials) => {
  return new Promise((resolve, reject) => {
    axios.post(`${backendUrl}/password/reset`, credentials)
      .then(response => {
        resolve(response);
      })
      .catch(err => {
        reject(err);
      });
  });
}

/**
 * Handles test submit
 */
export const submitTest = ({commit}, payload) => {
  return new Promise((resolve, reject) => {
    axios.post(`${apiUrl}/courses/${payload.courseId}/submitTest`, payload.testData, {
      params: {
        sectionNumber: payload.sectionNumber
      }
    })
      .then(response => {
        resolve(response);
      })
      .catch(err => {
        reject(err);
      });
  });
}

/**
 * Handles course enrollment
 */
export const enroll = ({commit, state}, courseId) => {
  return new Promise((resolve, reject) => {
    axios.post(`${apiUrl}/courses/${courseId}/enroll`, {}, {
      params: {
        XDEBUG_SESSION_START: 'PHPSTORM'
      }
    })
      .then(response => {
        resolve(response);
      })
      .catch(err => {
        reject(err);
      });
  });
}

/**
 * Handles course review
 */
export const reviewCourse = ({commit, state}, payload) => {
  return new Promise((resolve, reject) => {
    axios.post(`${apiUrl}/courses/${payload.courseId}/review`, {
      score: payload.score,
      text: payload.text
    }, {
      params: {
        XDEBUG_SESSION_START: 'PHPSTORM'
      }
    })
      .then(response => {
        commit('reviewCourse', response.data.data);
        resolve(response);
      })
      .catch(err => {
        reject(err);
      });
  });
}



