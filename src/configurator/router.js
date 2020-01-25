import Vue from "vue";
import VueRouter from "vue-router";
import screens from "./screens";

Vue.use(VueRouter);

const routes = [
  {
    name: "config",
    path: "/config/:id",
    component: screens.Config
  },
  {
    name: "template",
    path: "/template/:id",
    component: screens.Template
  },
  {
    name: "theme",
    path: "/theme/:id",
    component: screens.Theme
  },
  {
    name: "settings",
    path: "/settings",
    component: screens.Settings
  },
  { path: '*', redirect: '/config/default' }
];

export default new VueRouter({
  routes
});
