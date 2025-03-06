<script setup>
import { defineProps, ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import alertify from 'alertifyjs';
import Modal from './Modal.vue';

const props = defineProps({
    incomes: {
        type: Array,
        required: true
    },
    expenses: {
        type: Array,
        required: true
    }
});

const displayForm = ref({
    id: '',
    description: '',
    category: '',
    amount: '',
    date_received: ''
});

// Delete income
const deleteIncome = (id) => {
    if (confirm('Are you sure you want to delete this income?')) {
        axios.delete(route('income.destroy', id))
            .then(response => {
                if (response.data.success) {
                    alertify.success(response.data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    
                }
            })
            .catch(error => {
                alertify.error('Failed to delete income.');
            });
    }
};

// Delete expense
const deleteExpense = (id) => {
    if (confirm('Are you sure you want to delete this expense?')) {
        axios.delete(route('expense.destroy', id))
            .then(response => {
                if (response.data.success) {
                    alertify.success(response.data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    
                }
            })
            .catch(error => {
                alertify.error('Failed to delete expense.');
            });
    }
};

// Edit income (open modal)
const editIncome = (income) => {
    console.log("Before update: ",showEditModal.value);
    displayForm.value.id = income.id;
    displayForm.value.description = income.description;
    displayForm.value.category = income.category;
    displayForm.value.amount = income.amount;
    displayForm.value.date_received = income.date_received;
    showEditModal.value = true;

    console.log("After update:", showEditModal.value);
};

// Update income
const updateIncome = () => {
    axios.put(route('income.update', displayForm.value.id), displayForm.value)
        .then(response => {
            if (response.data.success) {
                alertify.success(response.data.message);
                showEditModal.value = false;
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            alertify.error('Failed to update income.');
        });
};

// Update expense
const updateExpense = () => {
    axios.put(route('expense.update', displayForm.value.id), displayForm.value)
        .then(response => {
            if (response.data.success) {
                alertify.success(response.data.message);
                showEditModal.value = false;
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            alertify.error('Failed to update expense.');
        });
};

const showEditModal = ref(false);
</script>

<script>
// export default {
//   props: {
//     isVisible: {
//       type: Boolean,
//       default: false
//     }
//   },
//   methods: {
//     close() {
//       this.$emit('close')
//     }
//   }
// }
</script>

<template>
    <!-- <slot name="table"> -->
        <div class="table-container">
            <table class="min-w-full bg-white border border-gray-300 ">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Description</th>
                        <th class="py-3 px-6 text-left">Category</th>
                        <th class="py-3 px-6 text-left">Amount</th>
                        <th class="py-3 px-6 text-left">Date</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <tr v-for="income in incomes" :key="income.id" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ income.id }}</td>
                        <td class="py-3 px-6">{{ income.description }}</td>
                        <td class="py-3 px-6">{{ income.category }}</td>
                        <td class="py-3 px-6">{{ income.amount }}</td>
                        <td class="py-3 px-6">{{ income.date_received }}</td>
                        <td class="py-3 px-6 text-center">
                            <PrimaryButton @click="editIncome(income)" class="mr-2">Edit</PrimaryButton>
                            <DangerButton @click="deleteIncome(income.id)">Delete</DangerButton>
                        </td>
                    </tr>
                    <tr v-for="expense in expenses" :key="expense.id" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ expense.id }}</td>
                        <td class="py-3 px-6">{{ expense.description }}</td>
                        <td class="py-3 px-6">{{ expense.category }}</td>
                        <td class="py-3 px-6">{{ expense.amount }}</td>
                        <td class="py-3 px-6">{{ expense.date_received }}</td>
                        <td class="py-3 px-6 text-center">
                            <PrimaryButton @click="editIncome(expense)" class="mr-2">Edit</PrimaryButton>
                            <DangerButton @click="deleteExpense(expense.id)">Delete</DangerButton>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <!-- </slot> -->

    <!-- Edit Modal Income-->
    <Modal :show="showEditModal" @close="showEditModal = false">
        <template #title-header>
            <h4 class="mx-auto text-center">Edit Income</h4>
        </template>
        <template #body>
            <form @submit.prevent="updateIncome">
                <input v-model="displayForm.description" type="text" placeholder="Description" class="input-field">
                <input v-model="displayForm.category" type="text" placeholder="Category" class="input-field">
                <input v-model="displayForm.amount" type="number" placeholder="Amount" class="input-field">
                <input v-model="displayForm.date_received" type="date" placeholder="Date" class="input-field">
                <button type="submit" class="btn-save">Update</button>
            </form>
        </template>
    </Modal>

    <!-- Edit Modal Expense -->
    <Modal :show="showEditModal" @close="showEditModal = false">
        <template #title-header>
            <h4 class="mx-auto text-center">Edit Income</h4>
        </template>
        <template #body>
            <form @submit.prevent="updateExpense">
                <input v-model="displayForm.description" type="text" placeholder="Description" class="input-field">
                <input v-model="displayForm.category" type="text" placeholder="Category" class="input-field">
                <input v-model="displayForm.amount" type="number" placeholder="Amount" class="input-field">
                <input v-model="displayForm.date_received" type="date" placeholder="Date" class="input-field">
                <button type="submit" class="btn-save">Update</button>
            </form>
        </template>
    </Modal>
</template>

<style scoped>
/* .table-container {
    overflow-x: auto;
    
} */
.input-field {
    width: 85%;
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
    position: absolute;
    top: 56%;
    left: 45%;
}
</style>
