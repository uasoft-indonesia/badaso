import store from "./../store/store";

export default (error) => {
  if (error.response) {
    const status = error.response.status;
    if (status === 400) {
      const data = error.response.data;
      data.status = status;
      return Promise.reject(data);
    } else if (status === 401) {
      store.commit("badaso/SET_AUTH_ISSUE", {
        unauthorized: true,
      });
      const data = error.response.data;
      const message = error.response.data.message;
      data.status = status;
      data.message = message;
      return Promise.reject(data);
    } else if (status === 402 || status === 412) {
      const data = error.response.data;
      data.status = status;
      store.commit("badaso/SET_KEY_ISSUE", {
        invalid: true,
        message: data.message ? data.message : "",
      });
      return Promise.reject(data);
    } else if (status === 500) {
      const data = error.response.data;
      const message = error.response.data.message;
      data.status = status;
      data.message = message;
      return Promise.reject(data);
    } else if (status === 503) {
      const data = error.response.data;
      const message = error.response.data.message;
      data.status = status;
      data.message = message;
      return Promise.reject(data);
    }
  }

  if (!navigator.onLine) {
    // eslint-disable-next-line prefer-promise-reject-errors
    return Promise.reject({
      message:
        "Data cannot load because internet of you not connected. Please to you connect internet again!",
      errors: [],
    });
  }

  // eslint-disable-next-line prefer-promise-reject-errors
  return Promise.reject({});
};
