<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import { useForm } from '@inertiajs/vue3';
import alertify from 'alertifyjs';
import 'alertifyjs/build/css/alertify.min.css';
import Table from '@/Components/Table.vue';
import { defineProps } from 'vue';
import axios from 'axios';

alertify.set('notifier', 'position', 'top-center');

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

const showModal = ref(false);
const showModalExpense = ref(false);

const form = ref({
    description: '',
    category: '',
    amount: '',
    date_received: ''
});

const formExpense = ref({
    description: '',
    category: '',
    amount: 0,
    date_received: ''
});

const submitFormExpense = () => {
    // console.log("Form Data:", formExpense.value);
    axios.post(route('expense.store', formExpense.value))
        .then(response => {
            if (response.data.success) {
                showModalExpense.value = false;
                alertify.success(response.data.message);
                formExpense.value = {
                    description: '',
                    category: '',
                    amount: '',
                    date_received: ''
                };
                // Optionally, you can update the expenses list here
            }
        })
        .catch(error => {
            alertify.error('Failed to add expense. Please try again.');
        });
};

const submitForm = () => {
    // console.log("Form Data:", formExpense.value);
    axios.post(route('income.store', form.value))
        .then(response => {
            if (response.data.success) {
                showModal.value = false;
                alertify.success(response.data.message);
                form.value = {
                    description: '',
                    category: '',
                    amount: '',
                    date_received: ''
                };
                // Optionally, you can update the expenses list here
            }
        })
        .catch(error => {
            alertify.error('Failed to add income. Please try again.');
        });
};

console.log('Incomes:', props.incomes);
console.log('Expenses:', props.expenses);

</script>


<script>
export default {
    name: 'Dashboard',
    components: {
        PrimaryButton,
        DangerButton,
        Modal,
        Dropdown,
        Table,
    },
    methods: {
        close() {
            this.$emit('close')
        }
    },
    // props: {
    //     incomes: {
    //         default: []
    //     },
    //     expenses: {
    //         default: []
    //     }
    // },
    data() {
        return {
            showModal: false,
            showModalExpense: false
        }
    },
}


</script>



<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="mx-auto text-center py-12">
            <PrimaryButton class="m-1" @click="showModal = true">Add Income</PrimaryButton>
            <DangerButton class="m-1" @click="showModalExpense = true">Add Expense</DangerButton>
        </div>

<!-- INCOME -->
        <Modal :show="showModal" @close="showModal = false">
            <template #title-header>
                <h4 class="mx-auto text-center">Add Income</h4>
            </template>
            <template #body>
                <form @submit.prevent="submitForm">
                    <textarea v-model="form.description" class="desc" placeholder="Description"></textarea>
                    <input v-model="form.category" type="text" placeholder="Category" class="category">
                    <input v-model="form.amount" type="number" class="amount" placeholder="Amount">
                    <input v-model="form.date_received" type="date" name="date" id="date" class="date">
                    <button type="submit" class="btn-save">Save</button>
                </form>
                
            </template>

        </Modal>
<!-- EXPENSE -->
        <Modal :show="showModalExpense" @close="showModalExpense = false">
            <template #title-header>
                <h4 class="mx-auto text-center">Add Expense</h4>
            </template>
            <template #body>
                <form @submit.prevent="submitFormExpense">
                    <textarea v-model="formExpense.description" class="desc" placeholder="Description"></textarea>
                    <input v-model="formExpense.category" type="text" class="category" placeholder="Category">
                    <input v-model="formExpense.amount" type="number" class="amount" placeholder="Amount">
                    <input v-model="formExpense.date_received" type="date" name="date" id="date" class="date">
                    <button type="submit" class="btn-save">Save</button>
                </form>
                
            </template>
            <template #footer>
                
            </template>
            
        </Modal>

        <!-- TABLE -->

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <!-- You're logged in! -->
                        <Table :incomes="incomes" :expenses="expenses" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.container {
    width: 800px;
    height: 500px;
    padding: 20px;
}
.desc{
    width: 630px;
    height: 100px;
    position: absolute;
    top: 60px;
}
.category{
    width: 630px;
    height: 30px;
    position: absolute;
    top: 172px;
}
.amount{
    position: absolute;
    top: 230px;
    margin-left: 208px;
}
.btn-save{
    position: absolute;
    top: 312px;
    background-color: dodgerblue;
    border-radius: 6px;
    width: 80px;
    height: 28px;
    color: white;
    text-align: center;
}
.date{
    position: absolute;
    top: 232px;
}
</style>