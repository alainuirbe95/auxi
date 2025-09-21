const Home = {
    template: '#homepage'
}

const About = {
    template: '#about'
}

const router = new VueRouter({
    base: 'blogigniter/spa',
    mode: 'history',
    routes: [
        {
            path:'/',
            component: ListPost
        },
         {
            path:'/:category/:post',
            component: DetailPost
        }
    ],
    linkActiveClass: 'active',
    linkExactActiveClass: 'current'
});

var app = new Vue({
    el: "#app",
    router
})