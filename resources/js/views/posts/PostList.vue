<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Blog Yazıları</h1>
            <div v-if="authState.isAuthenticated">
                <router-link
                    to="/posts/create"
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
                    Yeni Yazı
                </router-link>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg mb-6 p-4">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0 md:space-x-4"
            >
                <div class="flex-1">
                    <label for="search" class="sr-only">Ara</label>
                    <div class="relative rounded-md shadow-sm">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            v-model="filters.search"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-4 pr-10 py-2 sm:text-sm border-gray-300 rounded-md"
                            placeholder="Yazı ara..."
                            @input="debouncedSearch"
                        />
                        <div
                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
                <div
                    class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3"
                >
                    <div>
                        <select
                            v-model="filters.category"
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            @change="fetchPosts"
                        >
                            <option value="">Tüm Kategoriler</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <select
                            v-model="filters.sort"
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            @change="fetchPosts"
                        >
                            <option value="newest">En Yeni</option>
                            <option value="oldest">En Eski</option>
                            <option value="most_commented">
                                En Çok Yorumlanan
                            </option>
                        </select>
                    </div>
                </div>
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

        <!-- Empty State -->
        <div
            v-else-if="posts.length === 0"
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
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                Yazı bulunamadı
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Henüz yayınlanmış bir yazı yok veya arama kriterlerinize uygun
                yazı bulunamadı.
            </p>
            <div v-if="authState.isAuthenticated" class="mt-6">
                <router-link
                    to="/posts/create"
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
                    Yeni Yazı Oluştur
                </router-link>
            </div>
        </div>

        <div
            v-else
            class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3"
        >
            <div
                v-for="post in posts"
                :key="post.id"
                class="bg-white shadow rounded-lg overflow-hidden"
            >
                <div class="h-48 bg-gray-200 overflow-hidden">
                    <img
                        v-if="post.image"
                        :src="post.image"
                        :alt="post.title"
                        class="w-full h-full object-cover"
                    />
                    <div
                        v-else
                        class="w-full h-full flex items-center justify-center bg-gray-200"
                    >
                        <svg
                            class="h-12 w-12 text-gray-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center space-x-2 mb-2">
                        <span
                            v-for="category in post.categories"
                            :key="category.id"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                        >
                            {{ category.name }}
                        </span>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-900 mb-2">
                        {{ post.title }}
                    </h2>

                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ truncateContent(post.content) }}
                    </p>

                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <span class="mr-1">Yazar:</span>
                            <span class="font-medium text-gray-900"
                                >{{ post.user }}</span
                            >
                        </div>
                        <span class="mx-2">•</span>
                        <time :datetime="post.published_at">{{
                            formatDate(post.published_at)
                        }}</time>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg
                                class="h-5 w-5 mr-1 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span>{{ post.comments_count || 0 }} Yorum</span>
                        </div>

                        <router-link
                            :to="`/posts/${post.id}`"
                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Devamını Oku
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="totalPages > 1"
            class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-6 rounded-lg shadow"
        >
            <div
                class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
            >
                <div>
                    <p class="text-sm text-gray-700">
                        <span>Gösteriliyor</span>
                        <span class="font-medium"> {{ pagination.from }} </span>
                        <span>-</span>
                        <span class="font-medium"> {{ pagination.to }} </span>
                        <span>toplam</span>
                        <span class="font-medium">
                            {{ pagination.total }}
                        </span>
                        <span>yazı</span>
                    </p>
                </div>
                <div>
                    <nav
                        class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                        aria-label="Pagination"
                    >
                        <button
                            @click="goToPage(currentPage - 1)"
                            :disabled="currentPage === 1"
                            :class="[
                                currentPage === 1
                                    ? 'opacity-50 cursor-not-allowed'
                                    : 'hover:bg-gray-50',
                            ]"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                        >
                            <span class="sr-only">Önceki</span>
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>

                        <template v-for="page in displayedPages" :key="page">
                            <span
                                v-if="page === '...'"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                            >
                                ...
                            </span>
                            <button
                                v-else
                                @click="goToPage(page)"
                                :class="[
                                    page === currentPage
                                        ? 'bg-indigo-50 border-indigo-500 text-indigo-600'
                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                ]"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                            >
                                {{ page }}
                            </button>
                        </template>

                        <button
                            @click="goToPage(currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            :class="[
                                currentPage === totalPages
                                    ? 'opacity-50 cursor-not-allowed'
                                    : 'hover:bg-gray-50',
                            ]"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500"
                        >
                            <span class="sr-only">Sonraki</span>
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, inject, onMounted, computed } from "vue";
import axios from "axios";

export default {
    setup() {
        const authState = inject("authState");
        const posts = ref([]);
        const categories = ref([]);
        const loading = ref(true);
        const searchTimeout = ref(null);

        const currentPage = ref(1);
        const perPage = ref(9);
        const pagination = reactive({
            total: 0,
            from: 0,
            to: 0,
            last_page: 1,
        });

        const filters = reactive({
            search: "",
            category: "",
            sort: "newest",
        });

        const totalPages = computed(() => pagination.last_page);

        const displayedPages = computed(() => {
            const range = [];
            const totalPagesToShow = 5;
            const halfPagesToShow = Math.floor(totalPagesToShow / 2);

            let startPage = Math.max(1, currentPage.value - halfPagesToShow);
            let endPage = Math.min(
                totalPages.value,
                startPage + totalPagesToShow - 1
            );

            if (endPage - startPage + 1 < totalPagesToShow) {
                startPage = Math.max(1, endPage - totalPagesToShow + 1);
            }

            if (startPage > 1) {
                range.push(1);
                if (startPage > 2) {
                    range.push("...");
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                range.push(i);
            }

            if (endPage < totalPages.value) {
                if (endPage < totalPages.value - 1) {
                    range.push("...");
                }
                range.push(totalPages.value);
            }

            return range;
        });

        const fetchPosts = async () => {
            loading.value = true;
            try {
                const queryParams = new URLSearchParams({
                    page: currentPage.value,
                    per_page: perPage.value,
                    sort: filters.sort,
                });

                if (filters.search) {
                    queryParams.append("search", filters.search);
                }

                if (filters.category) {
                    queryParams.append("category", filters.category);
                }

                const response = await axios.get(
                    `/api/v1/posts?${queryParams.toString()}`
                );

                if (response.data && response.data.data) {
                    posts.value = response.data.data;

                    if (response.data.meta) {
                        pagination.total = response.data.meta.total;
                        pagination.from = response.data.meta.from;
                        pagination.to = response.data.meta.to;
                        pagination.last_page = response.data.meta.last_page;
                    }
                }
            } catch (error) {
                console.error("Error fetching posts:", error);
            } finally {
                loading.value = false;
            }
        };

        const fetchCategories = async () => {
            try {
                const response = await axios.get("/api/v1/categories");
                if (response.data && response.data.data) {
                    categories.value = response.data.data;
                }
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        };

        const goToPage = (page) => {
            if (page < 1 || page > totalPages.value) return;
            currentPage.value = page;
            fetchPosts();
        };

        const truncateContent = (content) => {
            if (!content) return "";
            return content.length > 150
                ? content.substring(0, 150) + "..."
                : content;
        };

        const formatDate = (dateString) => {
            if (!dateString) return "";
            const date = new Date(dateString);
            return new Intl.DateTimeFormat("tr-TR", {
                year: "numeric",
                month: "long",
                day: "numeric",
            }).format(date);
        };

        const debouncedSearch = () => {
            if (searchTimeout.value) {
                clearTimeout(searchTimeout.value);
            }

            searchTimeout.value = setTimeout(() => {
                currentPage.value = 1;
                fetchPosts();
            }, 500);
        };

        onMounted(() => {
            fetchCategories();
            fetchPosts();
        });

        return {
            authState,
            posts,
            categories,
            loading,
            filters,
            currentPage,
            pagination,
            totalPages,
            displayedPages,
            fetchPosts,
            goToPage,
            truncateContent,
            formatDate,
            debouncedSearch,
        };
    },
};
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
