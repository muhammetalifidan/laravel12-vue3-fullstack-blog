<template>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <router-link
                to="/posts"
                class="text-indigo-600 hover:text-indigo-500"
            >
                ← Yazılara Geri Dön
            </router-link>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">
                    Yeni Yazı Oluştur
                </h1>
            </div>

            <div v-if="error" class="bg-red-50 border-l-4 border-red-400 p-4">
                <p class="text-red-700">{{ error }}</p>
            </div>

            <form @submit.prevent="submitForm" class="p-6 space-y-6">
                <div>
                    <label
                        for="title"
                        class="block text-sm font-medium text-gray-700"
                        >Başlık</label
                    >
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        :class="{ 'border-red-300': errors.title }"
                        required
                    />
                    <p v-if="errors.title" class="mt-1 text-sm text-red-600">
                        {{ errors.title }}
                    </p>
                </div>

                <div>
                    <label
                        for="content"
                        class="block text-sm font-medium text-gray-700"
                        >İçerik</label
                    >
                    <textarea
                        id="content"
                        v-model="form.content"
                        rows="8"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        :class="{ 'border-red-300': errors.content }"
                        required
                    ></textarea>
                    <p v-if="errors.content" class="mt-1 text-sm text-red-600">
                        {{ errors.content }}
                    </p>
                </div>

                <div>
                    <label
                        for="image"
                        class="block text-sm font-medium text-gray-700"
                        >Kapak Görseli</label
                    >
                    <input
                        id="image"
                        type="file"
                        ref="imageInput"
                        @change="handleImageChange"
                        accept="image/jpeg,image/png,image/jpg"
                        class="mt-1 block w-full"
                        :class="{ 'border-red-300': errors.image }"
                        required
                    />
                    <p v-if="errors.image" class="mt-1 text-sm text-red-600">
                        {{ errors.image }}
                    </p>

                    <div v-if="imagePreview" class="mt-2">
                        <img
                            :src="imagePreview"
                            alt="Preview"
                            class="h-32 w-auto object-cover"
                        />
                    </div>
                </div>

                <div>
                    <label
                        for="published_at"
                        class="block text-sm font-medium text-gray-700"
                        >Yayın Tarihi</label
                    >
                    <input
                        id="published_at"
                        v-model="form.published_at"
                        type="date"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        :class="{ 'border-red-300': errors.published_at }"
                        required
                    />
                    <p
                        v-if="errors.published_at"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.published_at }}
                    </p>
                </div>

                <div>
                    <label
                        for="status"
                        class="block text-sm font-medium text-gray-700"
                        >Durum</label
                    >
                    <select
                        id="status"
                        v-model="form.status"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        :class="{ 'border-red-300': errors.status }"
                        required
                    >
                        <option value="draft">Taslak</option>
                        <option value="published">Yayında</option>
                    </select>
                    <p v-if="errors.status" class="mt-1 text-sm text-red-600">
                        {{ errors.status }}
                    </p>
                </div>

                <div>
                    <label
                        for="categories"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >Kategoriler</label
                    >
                    <v-select
                        v-model="form.category_ids"
                        :options="categories"
                        :reduce="(category) => category.id"
                        label="name"
                        multiple
                        :class="{ 'is-invalid': errors.category_ids }"
                        placeholder="Kategorileri seçin veya arayın"
                        class="category-select"
                        :append-to-body="true"
                    />
                    <p
                        v-if="errors.category_ids"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.category_ids }}
                    </p>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        {{ submitting ? "Gönderiliyor..." : "Kaydet" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        vSelect,
    },
    setup() {
        const router = useRouter();
        const categories = ref([]);
        const submitting = ref(false);
        const error = ref("");
        const imagePreview = ref(null);
        const imageInput = ref(null);

        const form = reactive({
            title: "",
            content: "",
            image: null,
            published_at: new Date().toISOString().substr(0, 10),
            status: "draft",
            category_ids: [],
        });

        const errors = reactive({});

        const fetchCategories = async () => {
            try {
                const response = await axios.get("/api/v1/categories");
                if (response.data && response.data.data) {
                    categories.value = response.data.data;
                }
            } catch (err) {
                console.error("Error fetching categories:", err);
                error.value = "Kategoriler yüklenirken bir hata oluştu.";
            }
        };

        const handleImageChange = (event) => {
            const file = event.target.files[0];
            if (file) {
                form.image = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.value = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        };

        const submitForm = async () => {
            submitting.value = true;
            error.value = "";

            Object.keys(errors).forEach((key) => {
                errors[key] = "";
            });

            const formData = new FormData();
            formData.append("title", form.title);
            formData.append("content", form.content);
            formData.append("published_at", form.published_at);
            formData.append("status", form.status);

            if (form.image) {
                formData.append("image", form.image);
            }

            form.category_ids.forEach((id) => {
                formData.append("category_ids[]", id);
            });

            try {
                const response = await axios.post("/api/v1/posts", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                });

                if (response.data && response.data.status) {
                    router.push({
                        path: `/posts/`,
                        query: { created: true },
                    });
                }
            } catch (err) {
                console.error("Error creating post:", err);

                if (err.response) {
                    if (
                        err.response.status === 422 &&
                        err.response.data.errors
                    ) {
                        Object.keys(err.response.data.errors).forEach((key) => {
                            errors[key] = err.response.data.errors[key][0];
                        });
                    } else {
                        error.value =
                            err.response.data.message ||
                            "Yazı oluşturulurken bir hata oluştu.";
                    }
                } else {
                    error.value = "Sunucuya bağlanırken bir hata oluştu.";
                }
            } finally {
                submitting.value = false;
            }
        };

        onMounted(() => {
            fetchCategories();
        });

        return {
            categories,
            form,
            errors,
            submitting,
            error,
            imagePreview,
            imageInput,
            handleImageChange,
            submitForm,
        };
    },
};
</script>

<style>
.category-select {
    position: relative;
}

.category-select .vs__dropdown-menu {
    position: absolute;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    z-index: 50;
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    background: white;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
    margin-top: 4px;
}

.vs__selected {
    background-color: #ebf4ff;
    border-color: #4f46e5;
    color: #4f46e5;
    padding: 2px 8px;
    margin: 2px;
    border-radius: 4px;
}

.vs--multiple .vs__selected {
    display: flex;
    align-items: center;
}

.vs__dropdown-option--highlight {
    background: #818cf8;
    color: white;
}

.vs__dropdown-option {
    padding: 8px 12px;
}

.vs__dropdown-option--selected {
    background: #f3f4f6;
}

.vs__search::placeholder {
    color: #9ca3af;
}

.vs__search,
.vs__search:focus {
    color: #1f2937;
}

.vs__dropdown-toggle {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 4px 8px;
}

.vs--open .vs__dropdown-toggle {
    border-color: #4f46e5;
}
</style>
