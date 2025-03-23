<template>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-8 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Blog Yönetim Sistemi
                </h1>
                <p class="text-lg text-gray-600">
                    Hoş geldiniz! Bu platform, blog yazılarınızı yönetmeniz için
                    tasarlanmıştır.
                </p>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-600"
                    >Son güncelleme: {{ currentDate }}</span
                >
                <div v-if="authState.isAuthenticated">
                    <router-link
                        to="/posts/create"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Yeni Yazı Oluştur
                    </router-link>
                </div>
                <div v-else>
                    <router-link
                        to="/login"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Giriş Yap
                    </router-link>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                v-for="(feature, index) in features"
                :key="index"
                class="bg-white shadow-md rounded-lg overflow-hidden"
            >
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ feature.title }}
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-600">
                        {{ feature.description }}
                    </p>
                </div>
                <div class="bg-gray-50 px-6 py-3">
                    <router-link
                        :to="feature.link"
                        class="text-sm text-indigo-600 hover:text-indigo-500"
                    >
                        {{ feature.linkText }} &rarr;
                    </router-link>
                </div>
            </div>
        </div>

        <div
            v-if="authState.isAuthenticated && recentPosts.length > 0"
            class="bg-white shadow-md rounded-lg overflow-hidden mb-8"
        >
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Son Yazılar</h2>
            </div>

            <div class="divide-y divide-gray-200">
                <div
                    v-for="post in recentPosts"
                    :key="post.id"
                    class="px-6 py-4"
                >
                    <div class="flex justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ post.title }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ post.published_at }}
                            </p>
                        </div>
                        <router-link
                            :to="`/posts/${post.id}`"
                            class="text-sm text-indigo-600 hover:text-indigo-500"
                        >
                            Görüntüle
                        </router-link>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3">
                <router-link
                    to="/posts"
                    class="text-sm text-indigo-600 hover:text-indigo-500"
                >
                    Tüm yazıları görüntüle &rarr;
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive, inject, onMounted } from "vue";
import axios from "axios";

export default {
    setup() {
        const authState = inject("authState");
        const recentPosts = ref([]);
        const currentDate = new Date().toLocaleDateString("tr-TR");

        const features = reactive([
            {
                title: "Blog Yazıları",
                description:
                    "Blog yazılarınızı oluşturabilir, düzenleyebilir ve yönetebilirsiniz.",
                link: "/posts",
                linkText: "Yazıları görüntüle",
            },
            {
                title: "Kategoriler",
                description:
                    "İçeriklerinizi kategorilere ayırarak daha düzenli bir blog oluşturabilirsiniz.",
                link: "/categories",
                linkText: "Kategorileri görüntüle",
            },
            {
                title: "Yorumlar",
                description:
                    "Okuyucularınızın geri bildirimlerini takip edebilir ve yönetebilirsiniz.",
                link: "/posts",
                linkText: "Yorumları görüntüle",
            },
        ]);

        const fetchRecentPosts = async () => {
            if (authState.isAuthenticated) {
                try {
                    const response = await axios.get("/api/v1/posts?limit=5");
                    if (response.data && response.data.data) {
                        recentPosts.value = response.data.data.slice(0, 3);
                    }
                } catch (error) {
                    console.error("Error fetching recent posts:", error);
                }
            }
        };

        onMounted(() => {
            fetchRecentPosts();
        });

        return {
            authState,
            features,
            recentPosts,
            currentDate,
        };
    },
};
</script>
