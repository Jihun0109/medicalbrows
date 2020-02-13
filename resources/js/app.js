/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

import moment from "moment";
import VueRouter from "vue-router";
import swal from "sweetalert2";
window.swal = swal;

const toast = swal.mixin({
    //sweetalert2.github.io
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

Vue.use(VueRouter);

let routes = [{
        path: "/admin/reservations",
        component: require("./components/Admin/Reservations/Reservations.vue").default
    },
    {
        path: "/admin/logs",
        component: require("./components/Admin/Logs.vue").default
    },
    {
        path: "/admin/settings-clinic",
        component: require("./components/Admin/Settings/SettingsClinic.vue")
            .default
    },
    {
        path: "/admin/settings-rank",
        component: require("./components/Admin/Settings/SettingsRank.vue")
            .default
    },
    {
        path: "/admin/settings-staff",
        component: require("./components/Admin/Settings/SettingsStaff.vue")
            .default
    },
    {
        path: "/admin/settings-staff-rank",
        component: require("./components/Admin/Settings/SettingsStaffRank.vue")
            .default
    },
    {
        path: "/admin/settings-menu",
        component: require("./components/Admin/Settings/SettingsMenu.vue")
            .default
    },
    {
        path: "/admin/settings-tax",
        component: require("./components/Admin/Settings/SettingsTax.vue")
            .default
    },
    {
        path: "/admin/settings-shift",
        component: require("./components/Admin/Settings/SettingsShift.vue")
            .default
    },
    {
        path: "/admin/settings-rank-schedule",
        component: require("./components/Admin/Settings/SettingsRankSchedule.vue")
            .default
    },
    {
        path: "/admin/settings-account",
        component: require("./components/Admin/Settings/SettingsAccount.vue")
            .default
    },
    {
        path: "/admin/settings-order",
        component: require("./components/Admin/Settings/SettingsOrder.vue")
            .default
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

import { Form, HasError, AlertError } from "vform";

window.Form = Form;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.filter("upText", function(data) {
    return data.CharAt(0).toUpperCase() + Text.slice(1);
});

Vue.filter("upText", function(data) {
    return data.moment(data).format("MMM Do YY"); //Refer to momentjs.com  for detail
});

Vue.filter("isVacation", function(data) {
    return data === 1 ? "閉鎖" : " ";
});

Vue.filter("isActive", function(data) {
    return data === 1 ? "アクティブ" : "閉鎖";
});

Vue.filter("percentageSign", function(data) {
    return data + " " + "%";
});


const app = new Vue({
    el: "#app",
    router
});
