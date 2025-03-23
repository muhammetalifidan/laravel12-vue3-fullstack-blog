<template>
    <div
        class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2
                    class="mt-6 text-center text-3xl font-extrabold text-gray-900"
                >
                    Yeni hesap oluşturun
                </h2>
            </div>

            <div
                v-if="error"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                role="alert"
            >
                <span class="block sm:inline">{{ error }}</span>
            </div>

            <form class="mt-8 space-y-6" @submit.prevent="submitRegister">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="first_name" class="sr-only">Ad</label>
                        <input
                            id="first_name"
                            name="first_name"
                            v-model="form.first_name"
                            :class="{ 'border-red-300': errors.first_name }"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Ad"
                            required
                        />
                        <p
                            v-if="errors.first_name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.first_name }}
                        </p>
                    </div>

                    <div>
                        <label for="last_name" class="sr-only">Soyad</label>
                        <input
                            id="last_name"
                            name="last_name"
                            v-model="form.last_name"
                            :class="{ 'border-red-300': errors.last_name }"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Soyad"
                            required
                        />
                        <p
                            v-if="errors.last_name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.last_name }}
                        </p>
                    </div>

                    <div>
                        <label for="phone" class="sr-only">Telefon</label>
                        <input
                            id="phone"
                            name="phone"
                            v-model="form.phone"
                            v-mask="'(###) ### ## ##'"
                            :class="{ 'border-red-300': errors.phone }"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Telefon Numarası"
                            required
                        />
                        <p
                            v-if="errors.phone"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.phone }}
                        </p>
                    </div>

                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            v-model="form.email"
                            :class="{ 'border-red-300': errors.email }"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Email adresi"
                            required
                        />
                        <p
                            v-if="errors.email"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.email }}
                        </p>
                    </div>

                    <div>
                        <label for="password" class="sr-only">Şifre</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            v-model="form.password"
                            :class="{ 'border-red-300': errors.password }"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Şifre"
                            required
                        />
                        <p
                            v-if="errors.password"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ errors.password }}
                        </p>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <span
                            v-if="loading"
                            class="absolute left-0 inset-y-0 flex items-center pl-3"
                        >
                            <svg
                                class="animate-spin h-5 w-5 text-white"
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
                        </span>
                        {{ loading ? "Kayıt yapılıyor..." : "Kayıt Ol" }}
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <router-link
                            to="/login"
                            class="font-medium text-indigo-600 hover:text-indigo-500"
                        >
                            Zaten hesabınız var mı? Giriş yapın
                        </router-link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { ref, reactive, inject } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { mask } from 'vue-the-mask';

export default {
    directives: {
        mask
    },

    setup(props, { emit }) {
        const router = useRouter();
        const fetchUserData = inject("fetchUserData");

        const form = reactive({
            first_name: "",
            last_name: "",
            phone: "",
            email: "",
            password: "",
        });

        const loading = ref(false);
        const error = ref("");
        const errors = reactive({});

        const submitRegister = async () => {
            loading.value = true;
            error.value = "";

            Object.keys(errors).forEach((key) => {
                errors[key] = "";
            });

            try {
                const response = await axios.post("/api/v1/register", form);

                if (
                    response.data &&
                    response.data.data &&
                    response.data.data.token
                ) {
                    localStorage.setItem(
                        "auth_token",
                        response.data.data.token
                    );

                    await fetchUserData();

                    emit("register-success");

                    router.push("/");
                } else {
                    error.value =
                        "Kayıt başarısız oldu. Lütfen tekrar deneyin.";
                }
            } catch (err) {
                console.error("Register error:", err);

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
                            "Kayıt sırasında bir hata oluştu.";
                    }
                } else {
                    error.value = "Sunucuya bağlanırken bir hata oluştu.";
                }
            } finally {
                loading.value = false;
            }
        };

        return {
            form,
            loading,
            error,
            errors,
            submitRegister,
        };
    },
};
</script>
