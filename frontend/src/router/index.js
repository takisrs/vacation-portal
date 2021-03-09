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
			title: "Home"
		},
	},
	{
		path: "/login",
		name: "Login",
		component: Login,
		meta: {
			requireAuthentication: false,
			title: "Login"
		},
	},
	{
		path: "/applications",
		name: "ApplicationList",
		component: ApplicationList,
		meta: {
			requireAuthentication: true,
			title: "Applications"
		},
	},
	{
		path: "/applications/create",
		name: "ApplicationCreate",
		component: ApplicationForm,
		meta: {
			requireAuthentication: true,
			title: "Create application"
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
			title: "Approve application"
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
			title: "Reject application"
		},
	},
	{
		path: "/users",
		name: "UserList",
		component: UserList,
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
			title: "Users"
		},
	},
	{
		path: "/users/create",
		name: "UserCreate",
		component: UserForm,
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
			title: "Add User"
		},
	},
	{
		path: "/users/edit/:id",
		name: "UserEdit",
		component: UserForm,
		meta: {
			requireAuthentication: true,
			requireAdmin: true,
			title: "Edit User"
		},
	},
];

const router = new VueRouter({
	mode: "history",
	routes,
});

router.beforeEach(function(to, from, next) {
	document.title = to.meta.title;
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
