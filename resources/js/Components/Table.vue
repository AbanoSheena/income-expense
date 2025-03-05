<script setup>
import { defineProps, ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import alertify from 'alertifyjs';

const props = defineProps({
    incomes: Array
});

const form = useForm({
    id: '',
    description: '',
    category: '',
    amount: ''
});

// Delete income
const deleteIncome = (id) => {
    if (confirm('Are you sure you want to delete this income?')) {
        router.delete(route('incomes.destroy', id), {
            onSuccess: () => alertify.success('Income deleted successfully!'),
            onError: () => alertify.error('Failed to delete income.')
        });
    }
};

// Edit income (open modal)
const editIncome = (income) => {
    form.id = income.id;
    form.description = income.description;
    form.category = income.category;
    form.amount = income.amount;
    showEditModal.value = true;
};

// Update income
const updateIncome = () => {
    router.put(route('incomes.update', form.id), form, {
        onSuccess: () => {
            alertify.success('Income updated successfully!');
            showEditModal.value = false;
        },
        onError: () => alertify.error('Failed to update income.')
    });
};

const showEditModal = ref(false);
</script>

<template>
    <slot name="table">
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
    </slot>

    <!-- Edit Modal -->
    <Modal :show="showEditModal" @close="showEditModal = false">
        <template #title-header>
            <h4 class="mx-auto text-center">Edit Income</h4>
        </template>
        <template #body>
            <form @submit.prevent="updateIncome">
                <input v-model="form.description" type="text" placeholder="Description" class="input-field">
                <input v-model="form.category" type="text" placeholder="Category" class="input-field">
                <input v-model="form.amount" type="number" placeholder="Amount" class="input-field">
                <button type="submit" class="btn-save">Update</button>
            </form>
        </template>
    </Modal>
</template>

<style scoped>
.input-field {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.btn-save {
    background-color: dodgerblue;
    color: white;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
}
</style>
