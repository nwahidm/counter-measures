export const formatDateTime = (value, format) => {
    return moment(value).format(format);
};

export const capitalizeFirstLetter = (str) => {
    return str.replace(/_/g, ' ').replace(/\b\w/g, firstLetter => firstLetter.toUpperCase());
}
