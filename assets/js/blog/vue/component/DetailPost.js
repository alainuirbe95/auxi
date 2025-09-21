const DetailPost = {
    name: "DetailPost",
    template: `
    <div v-if="detail.post" class="card detail-post">
        <div class="card-header">
            <img-post :p="detail.post"></img-post>
        </div>
        <div class="card-body">
            <social-links></social-links>
            <h1>{{ detail.post.title }}</h1>
            <div v-html="detail.post.content"></div>
            <category-post :p="detail.post"></category-post>
        </div>
    </div>`,
    data: function () {
        return {
            detail: []
        }
    },
    created() {
        this.post()
    },
    methods: {
        post() {
            fetch(BASE_URL + '/spa/jpost_view/' + this.$route.params.category + "/" + this.$route.params.post)
                    .then(response => response.json())
                    .then(res => this.detail = res)
        },
        imagePost(image) {
            return BASE_URL + 'uploads/post/' + image;
        }
    }
}