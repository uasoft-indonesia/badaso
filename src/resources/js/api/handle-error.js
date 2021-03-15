import store from "./../store/store";

export default (error) => {
  let status = error.response.status
  if (status === 400) {
    let data = error.response.data;
    data.status = status
    return Promise.reject(data);
  } else if (status === 401) {
    localStorage.clear();
    window.location.reload();
  } else if (status === 402 || status === 412) {
    let data = error.response.data;
    data.status = status
    store.commit('SET_LICENCE_ISSUE', {
      invalid: true,
      message: data.message ? data.message : ''
    })
    return Promise.reject(data);
  }

  return Promise.reject({});
};
