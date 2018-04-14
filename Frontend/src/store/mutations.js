export const setCourses = (state, payload) => {
  state.courses = payload;
}

export const addCourses = (state, payload) => {
  state.courses.push(...payload);
}

