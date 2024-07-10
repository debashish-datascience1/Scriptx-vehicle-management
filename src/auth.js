// Authentication functions
var auth = {
  login(username, token, user_id) {
    window.localStorage.setItem("username", username);
    window.localStorage.setItem("token", token);
    window.localStorage.setItem("user_id", user_id);
  },
  logout() {
    window.localStorage.clear();
  },
  checkAuth() {
    if (window.localStorage.getItem("token") != null) {
      return true;
    } else {
      return false;
    }
  }
};

export default auth;
