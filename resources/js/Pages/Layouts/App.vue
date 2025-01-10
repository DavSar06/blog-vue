<template>
    <div>
        <h1>Posts</h1>
        <div v-if="posts.length">
            <div v-for="post in posts" :key="post.id" class="card mb-4">
                <a :href="'/post/' + post.id">
                    <img :src="'/storage/' + post.image" class="card-img-top" alt="Post image" />
                </a>
                <div class="card-body">
                    <h2 class="card-title">{{ post.title }}</h2>
                    <p class="card-text">{{ post.body }}</p>
                </div>
            </div>
        </div>
        <div v-else>
            <p>No posts found</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            posts: [],
        };
    },
    mounted() {
        this.fetchPosts();
    },
    methods: {
        fetchPosts() {
            axios
                .get('http://127.0.0.1:8000/api/posts')
                .then((response) => {
                    this.posts = response.data;
                })
                .catch((error) => {
                    console.error('There was an error!', error);
                });
        },
    },
};
</script>

