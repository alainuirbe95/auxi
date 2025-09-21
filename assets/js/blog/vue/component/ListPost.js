const ListPost = {
    name: "ListPost",
    template: `
    <div class="list-posts">
        <div v-for="post in posts.posts" class="card post">
            <div class="card-header bg-danger"></div>
            <router-link :to="'/' + post.c_url_clean +'/' + post.url_clean">
            <img-post :p="post"></img-post>
            <h3>{{ post.title }}</h3>
            <p>{{ post.description }}</p>
            </router-link>
        </div>
    </div>`,
    data: function () {
        return {
            posts: []
        }
    },
    created() {
        this.post()
    },
    methods: {
        post() {
            fetch(BASE_URL + '/spa/jpost_list')
                    .then(response => response.json())
                    .then(res => this.posts = res)
        }
    }
}