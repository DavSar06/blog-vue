<template>
    <div class="row">
        <div class="col-lg-8">
            <!-- Search Results will be rendered here -->
            <div v-if="posts != null && posts.length > 0" id="search-results">
                <div v-for="post in posts" :key="post.id" class="card mb-4">
                    <a :href="`/post/${post.id}`">
                        <img class="card-img-top" :src="`storage/${post.image}`" alt="..." />
                    </a>
                    <div class="card-body">
                        <div class="small text-muted">
                            Posted on {{ formatDate(post.created_at) }} by {{ post.user.name || 'Unknown User' }}
                        </div>
                        <h2 class="card-title">{{ post.title }}</h2>
                        <p class="card-text">{{ post.body.slice(0, 100) }}...</p>
                        <div class="d-flex">
                            <a class="btn me-2 btn-primary" :href="`/post/${post.id}`">Read more →</a>
                            <div v-if="isPostOwner(post)">
                                <a class="btn me-2 btn-primary" :href="`/post/${post.id}/edit`">Edit →</a>
                                <form @submit.prevent="deletePost(post.id)" style="display:inline;">
                                    <button class="btn btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else>
                <p>No posts found.</p>
            </div>

            <!-- Pagination links (will be updated dynamically) -->
            <div v-if="paginationLinks" id="pagination-links">
                <ul class="pagination">
                    <li v-for="(link, index) in paginationLinks" :key="index" class="page-item" :class="{'disabled': !link.url}">
                        <a class="page-link" href="#" @click.prevent="fetchPosts(link.url)" v-html="link.label"></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">Search</div>
                <div class="card-body">
                    <div class="input-group">
                        <input
                            class="form-control"
                            v-model="searchQuery"
                            type="text"
                            placeholder="Enter search term..."
                            aria-label="Enter search term..."
                            aria-describedby="button-search"
                        />
                        <button class="btn btn-primary" @click="searchPosts">Go!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            posts: [], // Posts fetched from API
            searchQuery: "", // Search query
            paginationLinks: "", // Pagination links HTML
            authId: 0,
            currentPage: 1,
        };
    },
    methods: {
        // Format the date as "Month Day, Year"
        formatDate(date) {
            return new Date(date).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },

        // Check if the current user is the owner of the post
        isPostOwner(post) {
            return post.user_id == this.authId;
        },

        // Fetch posts from the API
        fetchPosts(url = "/api/posts") {
            axios
                .get(url)
                .then((response) => {
                    console.log(response.data);
                    this.posts = response.data.data;
                    this.authId = document.getElementById("authId").value;
                    this.paginationLinks = response.data.links; // Assuming pagination links come with the posts
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        // Delete a post by its ID
        deletePost(postId) {
            axios
                .delete(`/api/post/${postId}`)
                .then(() => {
                    this.fetchPosts(); // Re-fetch posts after deletion
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        // Perform search for posts
        searchPosts() {
            if (this.searchQuery.length > -1) {
                axios
                    .get("/api/post/search", {
                        params: { query: this.searchQuery },
                    })
                    .then((response) => {
                        this.posts = response.data;
                        this.paginationLinks = '';
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        },
    },

    mounted() {
        this.fetchPosts(); // Fetch posts when the component is mounted
    },
};
</script>
