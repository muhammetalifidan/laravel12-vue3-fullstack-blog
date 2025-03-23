<template>
    <div class="max-w-7xl mx-auto px-4 py-8">
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
            v-else-if="error"
            class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md mb-6"
        >
            <p class="text-red-700">{{ error }}</p>
        </div>

        <template v-else-if="post">
            <div class="mb-6">
                <router-link
                    to="/posts"
                    class="text-indigo-600 hover:text-indigo-500"
                >
                    ← Yazılara Geri Dön
                </router-link>
            </div>

            <div class="bg-white shadow rounded-lg mb-6">
                <div class="h-64 bg-gray-200 overflow-hidden">
                    <img
                        v-if="post.image"
                        :src="post.image"
                        :alt="post.title"
                        class="w-full h-full object-cover"
                    />
                    <div
                        v-else
                        class="w-full h-full flex items-center justify-center"
                    >
                        <svg
                            class="h-16 w-16 text-gray-400"
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
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            v-for="category in post.categories"
                            :key="category.id"
                            class="inline-flex px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800"
                        >
                            {{ category.name }}
                        </span>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ post.title }}
                    </h1>

                    <div class="text-sm text-gray-500 mb-6">
                        <span
                            >Yazar: {{ post.user }}</span
                        >
                        <span class="mx-2">•</span>
                        <time :datetime="post.published_at">{{
                            post.published_at
                        }}</time>
                    </div>

                    <div class="mb-6 whitespace-pre-line">
                        {{ post.content }}
                    </div>

                    <div
                        class="border-t border-gray-200 pt-4 flex justify-between items-center"
                    >
                        <div class="text-sm text-gray-500">
                            {{ post.comments_count }} Yorum
                        </div>

                        <div
                            v-if="authState.isAuthenticated && canEditPost"
                            class="flex space-x-2"
                        >
                            <router-link
                                :to="`/posts/${post.id}/edit`"
                                class="px-3 py-1 border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Düzenle
                            </router-link>
                            <button
                                @click="confirmDelete"
                                class="px-3 py-1 border rounded text-red-700 bg-red-100 hover:bg-red-200"
                            >
                                Sil
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-medium">
                        Yorumlar ({{ post.comments_count }})
                    </h2>
                </div>

                <div
                    v-if="authState.isAuthenticated"
                    class="p-6 border-b border-gray-200"
                >
                    <form @submit.prevent="submitComment">
                        <div>
                            <label
                                for="comment"
                                class="block text-sm font-medium text-gray-700"
                                >Yorum Ekle</label
                            >
                            <div class="mt-1">
                                <textarea
                                    id="comment"
                                    name="body"
                                    rows="3"
                                    v-model="commentForm.body"
                                    :class="{
                                        'border-red-300': commentErrors.body,
                                    }"
                                    class="w-full border rounded-md"
                                    placeholder="Yorumunuzu yazın..."
                                ></textarea>
                                <p
                                    v-if="commentErrors.body"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ commentErrors.body }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button
                                type="submit"
                                :disabled="commentSubmitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md"
                            >
                                {{
                                    commentSubmitting
                                        ? "Gönderiliyor..."
                                        : "Yorumu Gönder"
                                }}
                            </button>
                        </div>
                    </form>
                </div>

                <div v-else class="p-6 border-b border-gray-200 bg-gray-50">
                    <div class="text-center">
                        <p class="text-sm text-gray-700 mb-3">
                            Yorum yapmak için giriş yapmalısınız.
                        </p>
                        <router-link
                            to="/login"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md"
                        >
                            Giriş Yap
                        </router-link>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    <div
                        v-if="post.comments.length === 0"
                        class="p-6 text-center text-gray-500"
                    >
                        <p>Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                    </div>

                    <div
                        v-for="comment in post.comments"
                        :key="comment.id"
                        class="p-6"
                    >
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center"
                                >
                                    <span
                                        class="text-indigo-800 font-medium text-sm"
                                    >
                                        {{
                                            getInitials(
                                                comment.user
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium">
                                        {{ comment.user }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ comment.created_at }}
                                    </p>
                                </div>
                                <div class="mt-2 text-sm">
                                    <p>{{ comment.body }}</p>
                                </div>

                                <div
                                    v-if="
                                        authState.isAuthenticated &&
                                        canEditComment(comment)
                                    "
                                    class="mt-2 flex space-x-2"
                                >
                                    <button
                                        @click="editComment(comment)"
                                        class="text-xs text-gray-500 hover:text-indigo-600"
                                    >
                                        Düzenle
                                    </button>
                                    <button
                                        @click="deleteComment(comment.id)"
                                        class="text-xs text-gray-500 hover:text-red-600"
                                    >
                                        Sil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="showEditModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50"
            >
                <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="font-medium">Yorumu Düzenle</h3>
                    </div>
                    <div class="px-6 py-4">
                        <textarea
                            v-model="editCommentForm.body"
                            rows="4"
                            class="w-full rounded-md border-gray-300"
                        ></textarea>
                        <p
                            v-if="editCommentErrors.body"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ editCommentErrors.body }}
                        </p>
                    </div>
                    <div
                        class="px-6 py-3 bg-gray-50 flex justify-end space-x-3 rounded-b-lg"
                    >
                        <button
                            @click="showEditModal = false"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            İptal
                        </button>
                        <button
                            @click="updateComment"
                            :disabled="editCommentSubmitting"
                            class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md"
                        >
                            {{
                                editCommentSubmitting
                                    ? "Kaydediliyor..."
                                    : "Kaydet"
                            }}
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
                        <p class="text-sm">{{ deleteModalContent }}</p>
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
                            @click="confirmDeleteAction"
                            :disabled="deleteSubmitting"
                            class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-md"
                        >
                            {{ deleteSubmitting ? "Siliniyor..." : "Sil" }}
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import { ref, reactive, inject, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

export default {
    props: {
        id: {
            type: [String, Number],
            required: true,
        },
    },

    setup(props) {
        const route = useRoute();
        const router = useRouter();
        const authState = inject("authState");

        const post = ref(null);
        const comments = ref([]);
        const loading = ref(true);
        const error = ref(null);

        const commentForm = reactive({
            body: "",
        });
        const commentErrors = reactive({});
        const commentSubmitting = ref(false);

        const editCommentForm = reactive({
            id: null,
            body: "",
        });
        const editCommentErrors = reactive({});
        const editCommentSubmitting = ref(false);
        const showEditModal = ref(false);

        const showDeleteModal = ref(false);
        const deleteModalContent = ref("");
        const deleteSubmitting = ref(false);
        const deleteTarget = reactive({
            type: null,
            id: null,
        });

        const canEditPost = computed(() => {
            if (!authState.isAuthenticated || !post.value || !authState.user) {
                return false;
            }

            return (
                post.value.user.id === authState.user.id ||
                authState.user.role === "admin"
            );
        });

        const fetchPost = async () => {
            loading.value = true;
            error.value = null;

            try {
                const response = await axios.get(`/api/v1/posts/${props.id}`);
                if (
                    response.data &&
                    response.data.status &&
                    response.data.data
                ) {
                    post.value = response.data.data;
                } else {
                    error.value = "Yazı bilgileri alınamadı.";
                }
            } catch (err) {
                console.error("Error fetching post:", err);
                if (err.response) {
                    if (err.response.status === 404) {
                        error.value = "Yazı bulunamadı.";
                    } else {
                        error.value =
                            err.response.data.message ||
                            "Yazı yüklenirken bir hata oluştu.";
                    }
                } else {
                    error.value = "Sunucuya bağlanırken bir hata oluştu.";
                }
            } finally {
                loading.value = false;
            }
        };

        const fetchComments = async () => {
            try {
                const response = await axios.get(
                    `/api/v1/posts/${props.id}/comments`
                );
                if (
                    response.data &&
                    response.data.status &&
                    response.data.data
                ) {
                    comments.value = response.data.data;
                }
            } catch (err) {
                console.error("Error fetching comments:", err);
            }
        };

        const submitComment = async () => {
            commentSubmitting.value = true;
            Object.keys(commentErrors).forEach(
                (key) => (commentErrors[key] = "")
            );

            try {
                const response = await axios.post(
                    `/api/v1/posts/${props.id}/comments`,
                    commentForm
                );
                if (
                    response.data &&
                    response.data.status &&
                    response.data.data
                ) {
                    comments.value.unshift(response.data.data);
                    commentForm.body = "";
                }
            } catch (err) {
                console.error("Error submitting comment:", err);
                if (
                    err.response &&
                    err.response.status === 422 &&
                    err.response.data.errors
                ) {
                    Object.keys(err.response.data.errors).forEach((key) => {
                        commentErrors[key] = err.response.data.errors[key][0];
                    });
                }
            } finally {
                commentSubmitting.value = false;
            }
        };

        const canEditComment = (comment) => {
            if (!authState.isAuthenticated || !authState.user) {
                return false;
            }

            return (
                comment.user.id === authState.user.id ||
                authState.user.role === "admin"
            );
        };

        const editComment = (comment) => {
            editCommentForm.id = comment.id;
            editCommentForm.body = comment.body;
            showEditModal.value = true;
        };

        const updateComment = async () => {
            editCommentSubmitting.value = true;
            Object.keys(editCommentErrors).forEach(
                (key) => (editCommentErrors[key] = "")
            );

            try {
                const response = await axios.put(
                    `/api/v1/posts/${props.id}/comments/${editCommentForm.id}`,
                    {
                        body: editCommentForm.body,
                    }
                );

                if (
                    response.data &&
                    response.data.status &&
                    response.data.data
                ) {
                    const index = comments.value.findIndex(
                        (c) => c.id === editCommentForm.id
                    );
                    if (index !== -1) {
                        comments.value[index] = response.data.data;
                    }
                    showEditModal.value = false;
                }
            } catch (err) {
                console.error("Error updating comment:", err);
                if (
                    err.response &&
                    err.response.status === 422 &&
                    err.response.data.errors
                ) {
                    Object.keys(err.response.data.errors).forEach((key) => {
                        editCommentErrors[key] =
                            err.response.data.errors[key][0];
                    });
                }
            } finally {
                editCommentSubmitting.value = false;
            }
        };

        const deleteComment = (commentId) => {
            deleteTarget.type = "comment";
            deleteTarget.id = commentId;
            deleteModalContent.value =
                "Bu yorumu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.";
            showDeleteModal.value = true;
        };

        const confirmDelete = () => {
            deleteTarget.type = "post";
            deleteTarget.id = props.id;
            deleteModalContent.value =
                "Bu yazıyı silmek istediğinizden emin misiniz? Tüm yorumlar da silinecektir. Bu işlem geri alınamaz.";
            showDeleteModal.value = true;
        };

        const confirmDeleteAction = async () => {
            deleteSubmitting.value = true;

            try {
                if (deleteTarget.type === "post") {
                    await axios.delete(`/api/v1/posts/${deleteTarget.id}`);
                    router.push("/posts");
                } else if (deleteTarget.type === "comment") {
                    await axios.delete(
                        `/api/v1/posts/${props.id}/comments/${deleteTarget.id}`
                    );
                    comments.value = comments.value.filter(
                        (c) => c.id !== deleteTarget.id
                    );
                    showDeleteModal.value = false;
                }
            } catch (err) {
                console.error("Error deleting:", err);
            } finally {
                deleteSubmitting.value = false;
            }
        };

        const getInitials = (firstName, lastName) => {
            return `${firstName?.charAt(0) || ""}${
                lastName?.charAt(0) || ""
            }`.toUpperCase();
        };

        onMounted(() => {
            fetchPost();
            fetchComments();
        });

        return {
            post,
            comments,
            loading,
            error,
            authState,
            commentForm,
            commentErrors,
            commentSubmitting,
            editCommentForm,
            editCommentErrors,
            editCommentSubmitting,
            showEditModal,
            showDeleteModal,
            deleteModalContent,
            deleteSubmitting,
            canEditPost,
            fetchPost,
            fetchComments,
            submitComment,
            canEditComment,
            editComment,
            updateComment,
            deleteComment,
            confirmDelete,
            confirmDeleteAction,
            getInitials,
        };
    },
};
</script>
