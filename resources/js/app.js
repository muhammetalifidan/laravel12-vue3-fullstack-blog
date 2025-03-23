import "./bootstrap";
import { createApp } from "vue";
import router from "./router";
import App from "./App.vue";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import axios from "axios";
axios.defaults.baseURL = "/api/v1";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.headers.common["Accept"] = "application/json";

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found");
}

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

app.component("v-select", vSelect);
app.use(router);
app.mount("#app");
