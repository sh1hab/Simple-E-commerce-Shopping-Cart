<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';
import { useFlashMessages } from '@/composables/useFlashMessages';

defineProps({
    products: Array,
});

const { success, error } = useFlashMessages();

const form = useForm({
    product_id: null,
    quantity: 1,
});

const addToCart = (productId) => {
    form.product_id = productId;
    form.post(route('cart.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Products
                </h2>
                <Link
                    :href="route('cart.index')"
                    class="flex items-center gap-2 rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                >
                    <ShoppingCart :size="20" />
                    View Cart
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            <div
                                v-for="product in products"
                                :key="product.id"
                                class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-shadow hover:shadow-md"
                            >
                                <div class="aspect-square bg-gray-100 overflow-hidden">
                                    <img 
                                        :src="product.image || 'https://via.placeholder.com/500'" 
                                        :alt="product.name"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ product.name }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ product.description }}
                                    </p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="text-xl font-bold text-gray-900">
                                            ${{ parseFloat(product.price).toFixed(2) }}
                                        </span>
                                        <span 
                                            class="text-sm"
                                            :class="product.stock_quantity <= 10 ? 'text-red-600 font-semibold' : 'text-gray-500'"
                                        >
                                            Stock: {{ product.stock_quantity }}
                                        </span>
                                    </div>
                                    <button
                                        @click="addToCart(product.id)"
                                        :disabled="form.processing || product.stock_quantity === 0"
                                        class="mt-4 w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50 transition-colors"
                                    >
                                        {{
                                            product.stock_quantity === 0
                                                ? 'Out of Stock'
                                                : 'Add to Cart'
                                        }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>