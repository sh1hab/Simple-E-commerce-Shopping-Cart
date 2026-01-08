<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';

const props = defineProps({
    cartItems: Array,
    total: Number,
});

const updateQuantity = (cartItemId, quantity) => {
    const form = useForm({ quantity });
    form.patch(route('cart.update', cartItemId), {
        preserveScroll: true,
    });
};

const removeItem = (cartItemId) => {
    if (confirm('Are you sure you want to remove this item?')) {
        router.delete(route('cart.destroy', cartItemId), {
            preserveScroll: true,
        });
    }
};

const checkout = () => {
    if (confirm('Proceed with checkout?')) {
        router.post(route('cart.checkout'));
    }
};
</script>

<template>
    <Head title="Shopping Cart" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Shopping Cart
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="cartItems.length === 0" class="text-center py-12">
                            <p class="text-gray-500">Your cart is empty.</p>
                            <a
                                :href="route('products.index')"
                                class="mt-4 inline-block rounded-md bg-blue-600 px-6 py-2 text-sm font-medium text-white hover:bg-blue-700"
                            >
                                Continue Shopping
                            </a>
                        </div>

                        <div v-else>
                            <div class="space-y-4">
                                <div
                                    v-for="item in cartItems"
                                    :key="item.id"
                                    class="flex items-center gap-4 rounded-lg border border-gray-200 p-4"
                                >
                                    <div class="h-20 w-20 flex-shrink-0 rounded bg-gray-100 overflow-hidden">
                                        <img :src="item.image || 'https://via.placeholder.com/150'" :alt="item.product_name" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">
                                            {{ item.product_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            ${{ parseFloat(item.price).toFixed(2) }} each
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm text-gray-700">Qty:</label>
                                        <input
                                            type="number"
                                            :value="item.quantity"
                                            @change="(e) => updateQuantity(item.id, e.target.value)"
                                            min="1"
                                            :max="item.stock_available"
                                            class="w-20 rounded-md border-gray-300"
                                        />
                                    </div>
                                    <div class="w-24 text-right font-semibold">
                                        ${{ parseFloat(item.subtotal).toFixed(2) }}
                                    </div>
                                    <button
                                        @click="removeItem(item.id)"
                                        class="ml-4 rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                                    >
                                        Remove
                                    </button>
                                    </div>
                                </div>

                            <div class="mt-6 border-t border-gray-200 pt-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                                    <span class="text-2xl font-bold text-gray-900">
                                        ${{ parseFloat(total).toFixed(2) }}
                                    </span>
                                </div>
                                <button
                                    @click="checkout"
                                    class="mt-4 w-full rounded-md bg-green-600 px-6 py-3 text-base font-medium text-white hover:bg-green-700"
                                >
                                    Proceed to Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> 