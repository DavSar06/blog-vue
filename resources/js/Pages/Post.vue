<template>
    <div>
        <!-- Loading state -->
        <div v-if="isLoading" class="text-center">
            <p>Loading...</p>
        </div>

        <!-- Error state -->
        <div v-if="hasError" class="alert alert-danger">
            <p>Failed to load the post. Please try again later.</p>
        </div>

        <!-- Post content -->
        <article v-else>
            <!-- Control buttons -->
            <div v-if="isPostOwner(post)" class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a class="btn btn-outline-warning" :href="`/post/${post.id}/edit`">Edit →</a>
                <form @submit.prevent="deletePost(post.id)">
                    <button class="btn btn-outline-danger">Delete</button>
                </form>
            </div>

            <!-- Post header -->
            <header class="mb-4">
                <h1 class="fw-bolder mb-1">{{ post?.title }}</h1>
                <div class="text-muted fst-italic mb-2">
                    Posted on {{ formatDate(post?.created_at) }} by {{ post?.user?.name || 'Unknown User' }}
                </div>
            </header>

            <!-- Preview image -->
            <figure class="mb-4">
                <img class="img-fluid rounded" :src="getImageUrl(post?.image)" alt="Post Image" />
            </figure>

            <!-- Post content -->
            <section class="mb-5">
                <p class="fs-5 mb-4">{{ post?.body }}</p>
            </section>
        </article>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            authId: 0,
            post: null, // Post data
            isLoading: true, // Loading state
            hasError: false, // Error state
            authUserId: null, // Authenticated user's ID
        };
    },
    methods: {
        isPostOwner(post) {
            return post.user_id == this.authId;
        },

        // Fetch post data from API
        fetchPost() {
            const postId = this.$route.params.id; // Get post ID from the route

            if (!postId) {
                this.hasError = true;
                this.isLoading = false;
                console.error("Post ID is missing");
                return;
            }

            axios
                .get(`/api/posts/${postId}`)
                .then((response) => {
                    this.post = response.data;
                    this.authId = document.getElementById("authId").value;
                    this.isLoading = false;
                    this.hasError = false;
                })
                .catch((error) => {
                    console.error("Error fetching post:", error);
                    this.hasError = true;
                    this.isLoading = false;
                });
        },

        // Format the date as "Month Day, Year"
        formatDate(date) {
            return new Date(date).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        },

        // Get the full image URL from storage
        getImageUrl(imagePath) {
            return imagePath ? `/storage/${imagePath}` : ""; // Adjust the URL as needed
        },

        // Delete a post by its ID
        deletePost(postId) {
            axios
                .delete(`/api/post/${postId}`)
                .then(() => {
                    alert("Post deleted successfully!");
                    this.$router.push("/"); // Redirect to the homepage after deletion
                })
                .catch((error) => {
                    console.error("Error deleting post:", error);
                    alert("Failed to delete the post.");
                });
        },
    },
    watch: {
        // Watch for changes in the route parameters
        '$route.params.id'(newId) {
            this.fetchPost(); // Re-fetch post when route changes
        },
    },
    mounted() {
        this.fetchPost(); // Fetch post when the component is mounted
    },
};
</script>

<style>
/* Add custom styles if needed */
</style>
