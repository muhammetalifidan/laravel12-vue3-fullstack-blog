import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import axios from "axios";
import "./echo";

axios.defaults.baseURL = "/";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.headers.common["Accept"] = "application/json";

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem("auth_token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem("auth_token");
            router.push("/login");
        }
        return Promise.reject(error);
    }
);

const app = createApp(App);
app.use(router);
app.mount("#app");
