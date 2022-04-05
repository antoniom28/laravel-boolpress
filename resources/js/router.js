import Vue from "vue";
		import VueRouter from "vue-router";

		Vue.use(VueRouter);

		import Home from "./pages/Home";
		import SinglePost from "./pages/SinglePost";
		import SearchedUser from "./pages/SearchedUser";
		import SearchTag from "./pages/SearchTag";
		import NotFound from "./pages/NotFound";

		const router = new VueRouter({
		    mode: "history",
		    routes: [
				{
		            path: "/",
		            name: "home",
		            component: Home
		        },
		        {
		            path: "/u/:slug", //with slug
		            name: "searched-user",
		            component: SearchedUser
		        },
				{
		            path: "/p/:slug",
		            name: "single-post",
		            component: SinglePost
		        },
				{
		            path: "/tags/:tagname",
		            name: "search-tag",
		            component: SearchTag
		        },
				{
		            path: "*",
		            name: "page-404",
		            component: NotFound
		        },
		    ]
		});

		export default router
