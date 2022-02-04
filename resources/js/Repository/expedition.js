import API from "@/http-common";

export default {
    getById(id) {
        API.get(route("api.expeditions.show", id))
            .then((res) => {
                console.log(res);
            })
            .catch((err) => {
                console.log(err);
            });
    },
};
