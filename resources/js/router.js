import Vue from "vue";
		import VueRouter from "vue-router";

		Vue.use(VueRouter);

		import Home from "./pages/Home";
		import SinglePost from "./pages/SinglePost";

		const router = new VueRouter({
		    mode: "history",
		    routes: [
		        {
		            path: "/",
		            name: "home",
		            component: Home
		        },
				{
		            path: "/p/:slug",
		            name: "single-post",
		            component: SinglePost
		        },
		    ]
		});

		export default router
