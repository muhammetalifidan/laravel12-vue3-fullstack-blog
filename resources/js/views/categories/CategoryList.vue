<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kategoriler</h1>
            <div v-if="authState.isAuthenticated && isAdmin">
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg
                        class="-ml-1 mr-2 h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Yeni Kategori
                </button>
            </div>
        </div>

        <div v-if="loading" class="flex justify-center py-12">
            <svg
                class="animate-spin h-10 w-10 text-indigo-600"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>
        </div>

        <div
            v-else-if="categories.length === 0"
            class="bg-white shadow rounded-lg p-8 text-center"
        >
            <svg
                class="mx-auto h-12 w-12 text-gray-400"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                Kategori bulunamadı
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Henüz kategori oluşturulmamış.
            </p>
            <div v-if="authState.isAuthenticated && isAdmin" class="mt-6">
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg
                        class="-ml-1 mr-2 h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Yeni Kategori Oluştur
                </button>
            </div>
        </div>

        <div v-else class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            ID
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Kategori Adı
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Slug
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Oluşturulma Tarihi
                        </th>
                        <th
                            v-if="authState.isAuthenticated && isAdmin"
                            scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="category in categories" :key="category.id">
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ category.id }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                        >
                            {{ category.name }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ category.slug }}
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                        >
                            {{ category.created_at }}
                        </td>
                        <td
                            v-if="authState.isAuthenticated && isAdmin"
                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                        >
                            <button
                                @click="editCategory(category)"
                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                            >
                                Düzenle
                            </button>
                            <button
                                @click="confirmDelete(category)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Sil
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-medium">
                        {{ isEditing ? "Kategori Düzenle" : "Yeni Kategori" }}
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Kategori Adı
                            </label>
                            <input
                                type="text"
                                id="name"
                                v-model="form.name"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                :class="{ 'border-red-300': errors.name }"
                            />
                            <p
                                v-if="errors.name"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.name }}
                            </p>
                        </div>
                    </form>
                </div>
                <div
                    class="px-6 py-3 bg-gray-50 flex justify-end space-x-3 rounded-b-lg"
                >
                    <button
                        @click="closeModal"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    >
                        İptal
                    </button>
                    <button
                        @click="submitForm"
                        :disabled="submitting"
                        class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md"
                    >
                        {{ submitting ? "Kaydediliyor..." : "Kaydet" }}
                    </button>
                </div>
            </div>
        </div>

        <div
            v-if="showDeleteModal"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-medium">Silme İşlemini Onaylayın</h3>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm">
                        <span class="font-medium">{{
                            selectedCategory?.name
                        }}</span>
                        kategorisini silmek istediğinizden emin misiniz? Bu
                        işlem geri alınamaz ve kategoriye bağlı yazılar
                        kategorisiz kalacaktır.
                    </p>
                </div>
                <div
                    class="px-6 py-3 bg-gray-50 flex justify-end space-x-3 rounded-b-lg"
                >
                    <button
                        @click="showDeleteModal = false"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    >
                        İptal
                    </button>
                    <button
                        @click="deleteCategory"
                        :disabled="deleteSubmitting"
                        class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-md"
                    >
                        {{ deleteSubmitting ? "Siliniyor..." : "Sil" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, inject, computed, onMounted } from "vue";
import axios from "axios";

export default {
    setup() {
        const authState = inject("authState");
        const categories = ref([]);
        const loading = ref(true);
        const error = ref(null);

        const showModal = ref(false);
        const isEditing = ref(false);
        const selectedCategory = ref(null);
        const form = reactive({
            name: "",
        });
        const errors = reactive({});
        const submitting = ref(false);

        const showDeleteModal = ref(false);
        const deleteSubmitting = ref(false);

        const isAdmin = computed(() => {
            if (!authState.isAuthenticated || !authState.user) {
                return false;
            }
            console.log(authState.user);

            return authState.user.role === "admin";
        });

        const fetchCategories = async () => {
            loading.value = true;
            try {
                const response = await axios.get("/api/v1/categories");
                if (response.data && response.data.data) {
                    categories.value = response.data.data;
                }
            } catch (err) {
                console.error("Error fetching categories:", err);
                error.value = "Kategoriler yüklenirken bir hata oluştu.";
            } finally {
                loading.value = false;
            }
        };

        const openCreateModal = () => {
            isEditing.value = false;
            form.name = "";
            Object.keys(errors).forEach((key) => (errors[key] = ""));
            showModal.value = true;
        };

        const editCategory = (category) => {
            isEditing.value = true;
            selectedCategory.value = category;
            form.name = category.name;
            Object.keys(errors).forEach((key) => (errors[key] = ""));
            showModal.value = true;
        };

        const closeModal = () => {
            showModal.value = false;
            selectedCategory.value = null;
        };

        const confirmDelete = (category) => {
            selectedCategory.value = category;
            showDeleteModal.value = true;
        };

        const submitForm = async () => {
            submitting.value = true;
            Object.keys(errors).forEach((key) => (errors[key] = ""));

            try {
                let response;

                if (isEditing.value) {
                    response = await axios.put(
                        `/api/v1/categories/${selectedCategory.value.id}`,
                        form
                    );

                    if (
                        response.data &&
                        response.data.status &&
                        response.data.data
                    ) {
                        const index = categories.value.findIndex(
                            (c) => c.id === selectedCategory.value.id
                        );
                        if (index !== -1) {
                            categories.value[index] = response.data.data;
                        }
                    }
                } else {
                    response = await axios.post("/api/v1/categories", form);

                    if (
                        response.data &&
                        response.data.status &&
                        response.data.data
                    ) {
                        categories.value.push(response.data.data);
                    }
                }

                closeModal();
            } catch (err) {
                console.error("Error saving category:", err);

                if (
                    err.response &&
                    err.response.status === 422 &&
                    err.response.data.errors
                ) {
                    Object.keys(err.response.data.errors).forEach((key) => {
                        errors[key] = err.response.data.errors[key][0];
                    });
                } else {
                    error.value =
                        err.response?.data?.message ||
                        "Kategori kaydedilirken bir hata oluştu.";
                }
            } finally {
                submitting.value = false;
            }
        };

        const deleteCategory = async () => {
            deleteSubmitting.value = true;

            try {
                await axios.delete(
                    `/api/v1/categories/${selectedCategory.value.id}`
                );

                categories.value = categories.value.filter(
                    (c) => c.id !== selectedCategory.value.id
                );

                showDeleteModal.value = false;
                selectedCategory.value = null;
            } catch (err) {
                console.error("Error deleting category:", err);
                error.value =
                    err.response?.data?.message ||
                    "Kategori silinirken bir hata oluştu.";
            } finally {
                deleteSubmitting.value = false;
            }
        };

        onMounted(() => {
            fetchCategories();
        });

        return {
            authState,
            isAdmin,
            categories,
            loading,
            error,
            showModal,
            isEditing,
            selectedCategory,
            form,
            errors,
            submitting,
            showDeleteModal,
            deleteSubmitting,
            fetchCategories,
            openCreateModal,
            editCategory,
            closeModal,
            confirmDelete,
            submitForm,
            deleteCategory,
        };
    },
};
</script>
