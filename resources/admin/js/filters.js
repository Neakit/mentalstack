import Vue from "vue";

Vue.filter('dateFormat', function (value) {
    if(value) {
        const dt = new Date(value);
        let month = dt.getMonth() + 1;
        month = month < 10 ? `0${month}` : month;
        return dt.getDate() + "-" + month + "-" + dt.getFullYear();
    }
    else return '';
});
