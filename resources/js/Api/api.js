import axios from "axios";

const instance = axios.create({
    baseURL: "localhost/api/",
    timeout: 1000,
    headers: {
        "Content-Type": "application/javascript",
    },
});

export default instance;
