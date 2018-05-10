export const courses = state => state.courses;
export const selectedCourse = state => state.selectedCourse;
export const currentUser = state => state.currentUser;
export const isAuthenticated = state => !!state.currentUser;
export const snackBar = state => state.snackBar;

export const isAdmin = state => {
  if(state.currentUser && state.currentUser.role) {
    return state.currentUser.role === 'admin';
  }
  return false;
}

export const isTeacher = state => {
  if(state.currentUser && state.currentUser.role) {
    return state.currentUser.role === 'teacher';
  }
  return false;
}
