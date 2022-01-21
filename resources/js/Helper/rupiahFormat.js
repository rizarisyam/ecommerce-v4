/**
 *
 * @param {Number} number
 */
const ruapiahFormat = (number) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(number);
};

export default ruapiahFormat;
