<script setup>
import { RouterView } from 'vue-router';
import { useAuthStore } from './stores/auth';
import { onMounted } from 'vue';

const authStore = useAuthStore();

onMounted(() => {
    authStore.checkAuth();
});
</script>

<template>
    <div id="app" class="min-h-screen bg-gray-100">
        <RouterView />
    </div>
</template>