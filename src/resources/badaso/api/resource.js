import axios from "axios";
import beforeRequest from "./before-request";
import handleError from "./handle-error";

function createResource() {
  const instance = axios.create({
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  instance.interceptors.request.use(
    (config) => {
      const token = localStorage.getItem("token");
      if (token) config.headers.Authorization = "Bearer " + token;
      beforeRequest(config);

      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

  instance.interceptors.response.use(
    (response) => {
      return Promise.resolve(response.data);
    },
    (error) => {
      return handleError(error);
    }
  );

  return instance;
}

export default createResource();

const web = createResource();
web.interceptors.request.use((config) => {
  config.baseURL = import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX;
  return config;
});

export { web };
