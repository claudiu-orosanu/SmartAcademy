export const setCourses = (state, courses) => {
  state.courses = courses;
}

export const addCourses = (state, courses) => {
  state.courses.push(...courses);
}

export const setSelectedCourse = (state, course) => {
  state.selectedCourse = course;
}

