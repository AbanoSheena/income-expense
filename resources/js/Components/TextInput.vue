<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Description</th>
                    <th class="py-3 px-6 text-left">Category</th>
                    <th class="py-3 px-6 text-left">Amount</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                <tr v-for="income in props.incomes" :key="income.id" class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6">{{ income.id }}</td>
                    <td class="py-3 px-6">{{ income.description }}</td>
                    <td class="py-3 px-6">{{ income.category }}</td>
                    <td class="py-3 px-6">{{ income.amount }}</td>
                    <td class="py-3 px-6 text-center">
                        <PrimaryButton @click="editIncome(income)" class="mr-2">Edit</PrimaryButton>
                        <DangerButton @click="deleteIncome(income.id)">Delete</DangerButton>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
    />
</template>
