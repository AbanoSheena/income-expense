<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    
});

const emit = defineEmits(['close']);
const dialog = ref();
const showSlot = ref(props.show);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
            showSlot.value = true;

            dialog.value?.showModal();
        } else {
            document.body.style.overflow = '';

            setTimeout(() => {
                dialog.value?.close();
                showSlot.value = false;
            }, 200);
        }
    },
);

// watch(
//     () => props.show,
//     (newValue) => {
//         document.body.style.overflow = newValue ? 'hidden' : '';
//     }
// );

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape') {
        e.preventDefault();

        if (props.show) {
            close();
        }
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);

    document.body.style.overflow = '';
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});


</script>

<script>
export default {
  props: {
    isVisible: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    close() {
      this.$emit('close')
    }
  }
}
</script>

<template>
    <dialog
        v-show="show" 
        class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
        ref="dialog"
    >
        <div
            class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
            scroll-region
        >
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-show="show"
                    class="fixed inset-0 transform transition-all"
                    @click="close"
                >
                    <div
                        class="absolute inset-0 bg-gray-500 opacity-75"
                    />
                </div>
            </Transition>

            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div
                    v-show="show"
                    class="mb-6 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:mx-auto sm:w-full"
                    :class="maxWidthClass"
                >

                <div class="container" v-if="show" @click.self="close">
                        <div class="header-title">
                            <slot name="title-header">
                                <h4 class="title-modal">Default Modal Title</h4>
                            </slot>
                            <slot name="body">
                                <textarea class="desc" placeholder="Description"></textarea>
                                <input  type="text" placeholder="Category" class="category">
                                <input  type="number" class="amount" placeholder="Amount">
                                <input  type="date" name="date" id="date" class="date">
                            </slot>
                            <slot name="footer">
                                <button class="absolute top-2 right-2" @click="close">✖</button>
                            </slot>
                            
                        </div>
                        <slot name="body"></slot>
                </div>
                    <!-- <slot v-if="showSlot" /> -->
                </div>
            </Transition>
        </div>
    </dialog>
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
</style>