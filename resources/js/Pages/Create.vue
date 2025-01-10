<template>
    <div>
        <!-- Error Messages -->
        <div v-if="errors.length" class="alert alert-danger">
            <ul>
                <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
        </div>

        <h2 class="title d-flex justify-content-center">Publish your blog</h2>

        <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <!-- Title Input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="title">Title</label>
                <input
                    required
                    type="text"
                    id="title"
                    class="form-control"
                    v-model="formData.title"
                />
            </div>

            <!-- Body Textarea -->
            <div class="form-outline mb-4">
                <label class="form-label" for="body">Body</label>
                <textarea
                    required
                    id="body"
                    rows="4"
                    class="form-control"
                    v-model="formData.body"
                ></textarea>
            </div>

            <!-- Image Upload -->
            <div class="mb-3">
                <label for="imageInput" class="form-label">Choose an Image</label>
                <input
                    required
                    class="form-control"
                    type="file"
                    id="imageInput"
                    accept="image/*"
                    @change="handleFileChange"
                />
            </div>

            <!-- Image Preview -->
            <div class="mt-3">
                <p class="text-muted">Preview:</p>
                <img
                    v-if="imagePreview"
                    :src="imagePreview"
                    class="image-preview"
                    alt="Preview"
                />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block my-4">
                Publish
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            formData: {
                title: "",
                body: "",
                image: null,
            },
            errors: [], // Store validation errors
            imagePreview: null, // For image preview
        };
    },
    methods: {
        // Handle file selection and preview
        handleFileChange(event) {
            const file = event.target.files[0];
            this.formData.image = file;

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result; // Show image preview
                };
                reader.readAsDataURL(file);
            } else {
                this.imagePreview = null;
            }
        },

        // Submit the form data
        async submitForm() {
            this.errors = []; // Reset errors
            const form = new FormData();

            form.append("title", this.formData.title);
            form.append("body", this.formData.body);
            form.append("image", this.formData.image);
            form.append("authId", document.getElementById("authId").value);

            try {
                const response = await axios.post("/api/publish", form, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }).then((response) => {
                    window.location.href = `http://127.0.0.1:8000/post/${response.data}`;
                });
            } catch (error) {
                // Handle validation errors
                if (error.response && error.response.data.errors) {
                    this.errors = Object.values(error.response.data.errors).flat();
                } else {
                    console.error("Error submitting post:", error);
                }
            }
        },
    },
};
</script>

<style>
.image-preview {
    width: 100%;
    height: auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    object-fit: cover;
}
</style>
