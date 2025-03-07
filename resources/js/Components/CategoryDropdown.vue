<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    modelValue: {
        type: String,
        default: ''
    }
});


const emit = defineEmits(['update:modelValue']);
const selectedCategory = ref(props.modelValue);

const selectCategory = (category) => {
    selectedCategory.value = category;
    emit('update:modelValue', category);
};
</script>

<template>
    <div class="relative">
        <select 
            v-model="selectedCategory" 
            @change="selectCategory($event.target.value)" 
            class="dropdown-select"
        >
            <option disabled value="">Select a category</option>
            <option v-for="category in categories" :key="category" :value="category">
                {{ category }}
            </option>
        </select>
    </div>
</template>

<style scoped>
.dropdown-select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: white;
    cursor: pointer;
}
</style>
