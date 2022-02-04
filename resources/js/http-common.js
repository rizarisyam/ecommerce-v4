import axios from "axios";

export const HTTP = axios.create({
    baseURL: `${window.location.origin}/`,
    timeout: 1000,
    headers: {
        "Content-Type": "application/json",
    },
});
