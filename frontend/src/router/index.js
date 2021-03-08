import Vue from "vue";
import VueRouter from "vue-router";

import store from "../store/index";

// components
import Login from "../views/Login.vue";
import ApplicationList from "../views/application/List.vue";
import ApplicationForm from "../views/application/Form.vue";
import UserList from "../views/user/List.vue";
import UserForm from "../views/user/Form.vue";

Vue.use(VueRouter);

// register routes
const routes = [
	{
		path: "/",
		beforeEnter: (to, from, next) => {
			next(store.getters.isAdmin ? { name: "UserList" } : { name: "ApplicationList" });
		},
		meta: {
			requireAuthentication: true,
		},
	},
	{
		path: "/login",
		name: "Login",
		component: Login,
		meta: {
			requireAuthentication: false,
		},
	},
	{
		path: "/applications",
		name: "ApplicationList",
		component: ApplicationList,
		meta: {
			requireAuthentication: true,
		},
	},
	{
		path: "/applications/create",
		name: "ApplicationCreate",
		component: ApplicationForm,
		meta: {
			requireAuthentication: true,
		},
	},
	{
		path: "/applications/:id/approve",
		name: "ApplicationApprove",
		beforeEnter: (to) => {
			store.dispatch("updateApplication", { action: "approve", id: to.params.id });
		},
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
		},
	},
	{
		path: "/applications/:id/reject",
		name: "ApplicationReject",
		beforeEnter: (to) => {
			store.dispatch("updateApplication", { action: "reject", id: to.params.id });
		},
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
		},
	},
	{
		path: "/users",
		name: "UserList",
		component: UserList,
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
		},
	},
	{
		path: "/users/create",
		name: "UserCreate",
		component: UserForm,
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
		},
	},
	{
		path: "/users/edit/:id",
		name: "UserEdit",
		component: UserForm,
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
		},
	},
];

const router = new VueRouter({
	mode: "history",
	routes,
});

router.beforeEach(function(to, from, next) {
	store.dispatch("checkAutoLogin").then(() => {
		if (to.meta.requireAdmin && !store.getters.isAdmin) {
			store.commit("setMessage", { message: "Admin privileges required to perform this action. Please login!", class: "error" });
			next({
				path: "/login",
				query: { redirect: to.fullPath },
			});
		}

		if (to.meta.requireAuthentication && !store.getters.isAuthenticated) {
			next({
				path: "/login",
				query: { redirect: to.fullPath },
			});
		} else {
			next();
		}
	});
});

export default router;
