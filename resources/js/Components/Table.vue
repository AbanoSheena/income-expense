<script setup>
import { defineProps, ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import alertify from 'alertifyjs';
import Modal from './Modal.vue';
import CategoryDropdown from '@/Components/CategoryDropdown.vue';

const props = defineProps({
    incomes: {
        type: Array,
        required: true
    },
    expenses: {
        type: Array,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    modelValue: {
        type: String,
        default: ''
    }
});

const form_category = ref({
    category: ''
});

const searchQuery = ref('');

const categories = ['Income', 'Expense'];

const incomeForm = ref({
    id: '',
    description: '',
    category: '',
    amount: '',
    date_received: ''
});

const expenseForm = ref({
    id: '',
    description: '',
    category: '',
    amount: '',
    date_received: ''
});

const totalIncome = computed(() => {
    return props.incomes.reduce((sum, income) => sum + parseFloat(income.amount), 0);
});

const totalExpense = computed(() => {
    return props.expenses.reduce((sum, expense) => sum + parseFloat(expense.amount), 0);
});

const revenue = computed(() => totalIncome.value - totalExpense.value);

// filtering items
const filteredItems = computed(() => {
    let items = [];
    if (form_category.value.category === 'Income') {
        items = props.incomes;
    } else if (form_category.value.category === 'Expense') {
        items = props.expenses;
    } else {
        items = [...props.incomes, ...props.expenses];
    }

    if (searchQuery.value) {
        return items.filter(item => 
            item.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            item.category.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            item.amount.toString().includes(searchQuery.value) ||
            item.date_received.includes(searchQuery.value)
        );
    }

    return items;
});

// Delete income
const deleteIncome = async (id) => {
    if (confirm('Are you sure you want to delete this income?')) {
        try {
            console.log('Deleting income with ID:', id);
            const response = await axios.delete(route('income.destroy', {income:  id}));
            console.log('Delete Income Response:', response);
            if (response.data.success) {
                alertify.success(response.data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } catch (error) {
            console.error('Delete Income Error:', error);
            alertify.error('Failed to delete income.');
        }
    }
};

// Delete expense
const deleteExpense = async (id) => {
    if (confirm('Are you sure you want to delete this expense?')) {
        try {
            const response = await axios.delete(route('expense.destroy', {expense: id}));
            if (response.data.success) {
                alertify.success(response.data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        } catch (error) {
            alertify.error('Failed to delete expense.');
        }
    }
};

// Edit income (open modal)
const editIncome = (income) => {
    console.log("Before update: ", showIncomeEditModal.value);

    incomeForm.value = { ...income }; 
    showIncomeEditModal.value = true;

    console.log("After update:", showIncomeEditModal.value);
};
// Edit expense (open modal)
const editExpense = (expense) => {
    console.log("Before update: ", showExpenseEditModal.value);

    expenseForm.value = { ...expense };
    showExpenseEditModal.value = true;

    console.log("After update:", showExpenseEditModal.value);
};

// Update income
const updateIncome = async () => {
    try {
        const response = await axios.put(route('income.update', { income: incomeForm.value.id }), incomeForm.value);
        if (response.data.success) {
            alertify.success(response.data.message);
            showIncomeEditModal.value = false;
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    } catch (error) {
        alertify.error('Failed to update income.');
        console.error(error);
    }
};


// Update expense
const updateExpense = async () => {
    try {
        const response = await axios.put(route('expense.update', { expense: expenseForm.value.id }), expenseForm.value);
        if (response.data.success) {
            alertify.success(response.data.message);
            showExpenseEditModal.value = false;
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    } catch (error) {
        alertify.error('Failed to update expense.');
        console.error(error);
    }
};

const showIncomeEditModal = ref(false);
const showExpenseEditModal = ref(false);
</script>

<template>
    <div class="cardIncome">
        <div class="card-header">
            <h2 class="text-center">Income</h2>
            <h3 class="text-center">₱{{ totalIncome.toLocaleString() }}</h3>
        </div>
    </div>
    <div class="cardExpense">
        <div class="card-header">
            <h2 class="text-center">Expense</h2>
            <h3 class="text-center">₱{{ totalExpense.toLocaleString() }}</h3>
        </div>
    </div>
    <div class="cardRevenue">
        <div class="card-header">
            <h2 class="text-center">Revenue</h2>
            <h3 class="text-center">₱{{ revenue.toLocaleString() }}</h3>
        </div>
    </div>

    
    <div class="content-search">
        <input v-model="searchQuery" type="text" class="search" placeholder="Search">
    </div>
    <div class="dropdown-category">
        <CategoryDropdown v-model="form_category.category" :categories="categories" />
    </div>
    <div class="table-container">
        <table class="min-w-full bg-white border border-gray-300 " id="table" style="margin-top: 10%;">
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
                <tr v-for="item in filteredItems" :key="item.id" class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6">{{ item.id }}</td>
                    <td class="py-3 px-6">{{ item.description }}</td>
                    <td class="py-3 px-6">{{ item.category }}</td>
                    <td class="py-3 px-6">{{ item.amount }}</td>
                    <td class="py-3 px-6">{{ item.date_received }}</td>
                    <td class="py-3 px-6 text-center">
                        <PrimaryButton 
                            @click="item.category === 'Income' ? editIncome(item) : editExpense(item)" 
                            class="mr-2">
                            Edit
                        </PrimaryButton>
                        <DangerButton 
                            @click="item.category === 'Income' ? deleteIncome(item.id) : deleteExpense(item.id)">
                            Delete
                        </DangerButton>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal Income-->
    <Modal :show="showIncomeEditModal" @close="showIncomeEditModal = false">
        <template #title-header>
            <h4 class="mx-auto text-center">Edit Income</h4>
        </template>
        <template #body>
            <form @submit.prevent="updateIncome">
                <input v-model="incomeForm.description" type="text" placeholder="Description" class="input-field">
                <input v-model="incomeForm.category" type="text" placeholder="Category" class="input-field">
                <input v-model="incomeForm.amount" type="number" placeholder="Amount" class="input-field">
                <input v-model="incomeForm.date_received" type="date" placeholder="Date" class="input-field">
                <button type="submit" class="btn-save">Update</button>
            </form>
        </template>
        <template #footer>
            
        </template>
    </Modal>

    <!-- Edit Modal Expense -->
    <Modal :show="showExpenseEditModal" @close="showExpenseEditModal = false">
        <template #title-header>
            <h4 class="mx-auto text-center">Edit Expense</h4>
        </template>
        <template #body>
            <form @submit.prevent="updateExpense">
                <input v-model="expenseForm.description" type="text" placeholder="Description" class="input-field">
                <input v-model="expenseForm.category" type="text" placeholder="Category" class="input-field">
                <input v-model="expenseForm.amount" type="number" placeholder="Amount" class="input-field">
                <input v-model="expenseForm.date_received" type="date" placeholder="Date" class="input-field">
                <button type="submit" class="btn-save">Update</button>
            </form>
        </template>
    </Modal>
</template>

<style scoped>
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
.dropdown-category {
    position: absolute;
    top: 415px;
    left: 800px;
    width: 180px;
}
.search {
    width: 630px;
    height: 30px;
    margin-left: -20px;
    position: absolute;
    top: 420px;
    border-radius: 6px;
}
.cardIncome{
    width: 40%;
    height: 70px;
    position: absolute;
    top: 250px;
    left: 0px;
    background-color: #4CAF50;
    color: white;
    margin-left: 10%;
}
.cardExpense{
    width: 40%;
    height: 70px;
    position: absolute;
    top: 250px;
    right: 0px;
    background-color: #f44336;
    color: white;
    margin-right: 10%;
}
.cardRevenue{
    width: 80%;
    height: 70px;
    position: absolute;
    top: 320px;
    left: 0px;
    background-color: #2196F3;
    color: white;
    margin-left: 10%;
}

</style>
