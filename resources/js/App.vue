<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <router-link
                                to="/"
                                class="text-xl font-bold text-indigo-600"
                                >Muhammet Ali Fidan</router-link
                            >
                        </div>
                        <div
                            class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8"
                        >
                            <router-link
                                to="/"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="[
                                    $route.name === 'home'
                                        ? 'border-indigo-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                ]"
                                >Ana Sayfa</router-link
                            >
                            <router-link
                                to="/posts"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="[
                                    $route.name === 'posts'
                                        ? 'border-indigo-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                ]"
                                >Yazılar</router-link
                            >
                            <router-link
                                to="/categories"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="[
                                    $route.name === 'categories'
                                        ? 'border-indigo-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                ]"
                                >Kategoriler</router-link
                            >
                            <router-link
                                v-if="isAuthenticated"
                                to="/posts/create"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="[
                                    $route.name === 'post-create'
                                        ? 'border-indigo-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                ]"
                                >Yazı Oluştur</router-link
                            >
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div v-if="isAuthenticated" class="ml-3 relative">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-700">{{
                                    user
                                        ? user.first_name + " " + user.last_name
                                        : ""
                                }}</span>
                                <button
                                    @click="logout"
                                    class="px-3 py-1 text-sm text-white bg-red-500 hover:bg-red-600 rounded"
                                >
                                    Çıkış
                                </button>
                            </div>
                        </div>
                        <div v-else class="flex items-center space-x-4">
                            <router-link
                                to="/login"
                                class="text-sm text-gray-700 hover:text-indigo-600"
                                >Giriş</router-link
                            >
                            <router-link
                                to="/register"
                                class="px-3 py-1 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded"
                                >Kayıt Ol</router-link
                            >
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

export default {
    setup() {
        const router = useRouter();
        const user = ref(null);
        const isAuthenticated = computed(() => {
            return !!localStorage.getItem("auth_token");
        });

        const logout = async () => {
            try {
                await axios.delete("/logout");
                localStorage.removeItem("auth_token");
                user.value = null;
                router.push("/login");
            } catch (error) {
                console.error("Çıkış yapılırken hata oluştu:", error);
            }
        };

        onMounted(async () => {
            if (isAuthenticated.value) {
                try {
                    const response = await axios.get("/user");
                    user.value = response.data.data;
                } catch (error) {
                    console.error("Kullanıcı bilgileri alınamadı:", error);
                    if (error.response && error.response.status === 401) {
                        localStorage.removeItem("auth_token");
                        router.push("/login");
                    }
                }
            }
        });

        return {
            user,
            isAuthenticated,
            logout,
        };
    },
};
</script>
