import store from "./../store/store";

export default (error) => {
  if (error.response) {
    let status = error.response.status;
    if (status === 400) {
      let data = error.response.data;
      data.status = status;
      return Promise.reject(data);
    } else if (status === 401) {
      store.commit("badaso/SET_AUTH_ISSUE", {
        unauthorized: true,
      });
    } else if (status === 402 || status === 412) {
      let data = error.response.data;
      data.status = status;
      store.commit("badaso/SET_KEY_ISSUE", {
        invalid: true,
        message: data.message ? data.message : "",
      });
      return Promise.reject(data);
    }
  }

  if (!navigator.onLine) {
    return Promise.reject({
      message:
        "Data cannot load because internet of you not connected. Please to you connect internet again!",
      errors: [],
    });
  }

  return Promise.reject({});
};
