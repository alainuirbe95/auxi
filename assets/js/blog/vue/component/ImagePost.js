Vue.component('img-post', {
    template: '<img :src="imagePost()" :alt="p.title" :title="p.title">',
    props: {
        p: Object
    },
    methods: {
        imagePost() {
            if (this.p.image != "")
                return BASE_URL + 'uploads/post/' + this.p.image;
            return "";
        }
    }
})
