export const setCourses = (state, courses) => {
  state.courses = courses;
}

export const addCourses = (state, courses) => {
  state.courses.push(...courses);
}

export const setSelectedCourse = (state, course) => {
  state.selectedCourse = course;
}

export const setCurrentUser = (state, user) => {
  state.currentUser = user;
  localStorage.setItem('user', JSON.stringify(user));
}

export const showSnackbar = (state, settings) => {
  state.snackBar = settings;
}

export const reviewCourse = (state, review) => {
  state.selectedCourse.reviews.unshift(review);
  state.selectedCourse.isReviewedByUser = true;
}

