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


</script>


<script>

alertify.set('notifier', 'position', 'top-center');

const showModal = ref(false);
const showModalExpense = ref(false);

const form = useForm({
    description: '',
    category: '',
    amount: '',
    date_received: ''
});

const formExpense = useForm({
    description: '',
    category: '',
    amount: '',
    date_received: ''
});

const submitFormExpense = () => {
    formExpense.post(route('expense.store'), {
        onSuccess: () => {
            showModalExpense.value = false; 
            alertify.success('Expense added successfully!');
            formExpense.reset();
        },
        onError: () => {
            alertify.error('Failed to add expense. Please try again.');
        }
    });
};

const submitForm = () => {
    form.post(route('income.store'), {
        onSuccess: () => {
            showModal.value = false; 
            alertify.success('Income added successfully!');
            form.reset();
        },
        onError: () => {
            alertify.error('Failed to add income. Please try again.');
        }
    });
};

export default {
    name: 'Dashboard',
    components: {
        PrimaryButton,
        DangerButton,
        Modal,
        Dropdown,
    },
    methods: {
        close() {
            this.$emit('close')
        }
    },
    // props: {
    //     isVisible: {
    //     type: Boolean,
    //     default: false
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
                <!-- <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded close-btn" @click="showModal = false">
                    Close
                </button> -->
            </template>
            <template #body>
                <form @submit.prevent="submitForm">
                    <textarea v-model="form.description" class="desc" placeholder="Description"></textarea>
                    <input v-model="form.category" type="text" placeholder="Category" class="category">
                    <input v-model="form.amount" type="number" class="amount" placeholder="Amount">
                    <input v-model="form.date_received" type="date" name="date" id="date" class="date">
                    <button type="submit" class="btn-save">Save</button>
                </form>
                <!-- <input type="text"> -->
                <!--  -->
                
            </template>
            <template #footer>
                
            </template>
            
        </Modal>
<!-- EXPENSE -->
        <Modal :show="showModalExpense" @close="showModalExpense = false">
            <template #title-header>
                <h4 class="mx-auto text-center">Add Expense</h4>
                <!-- <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded close-btn" @click="showModal = false">
                    Close
                </button> -->
            </template>
            <template #body>
                <form @submit.prevent="submitFormExpense">
                    <textarea v-model="formExpense.description" class="desc" placeholder="Description"></textarea>
                    <input v-model="formExpense.category" type="text" class="category" placeholder="Category">
                    <input v-model="formExpense.amount" type="number" class="amount" placeholder="Amount">
                    <input v-model="formExpense.date_received" type="date" name="date" id="date" class="date">
                    <button type="submit" class="btn-save">Save</button>
                </form>
                <!-- <input type="text"> -->
                <!--  -->
                
            </template>
            <template #footer>
                
            </template>
            
        </Modal>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        You're logged in!
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