<template>
    <div>
        <!-- Success Alert -->
        <transition name="fade" @after-leave="resetShow">
            <div v-if="successMessage && show" class="fixed top-4 inset-x-0 mx-auto flex items-center justify-between bg-blue-100 border border-blue-300 rounded-lg p-4 shadow-lg max-w-lg w-full">
                <div class="flex items-center">
                    <div class="text-blue-800 text-sm font-semibold">{{ successMessage }}</div>
                </div>
                <button type="button" class="ml-2 p-1" @click="hideAlert">
                    <svg class="w-5 h-5 text-blue-600 hover:text-blue-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 9L4 3m12 0L10 9m0 0L4 15m12 0L10 9" stroke="currentColor" stroke-width="2" fill="none" />
                    </svg>
                </button>
            </div>
        </transition>

        <!-- Error Alert -->
        <transition name="fade" @after-leave="resetShow">
            <div v-if="errorMessage && show" class="fixed top-4 inset-x-0 mx-auto flex items-center justify-between bg-red-100 border border-red-300 rounded-lg p-4 shadow-lg max-w-lg w-full">
                <div class="flex items-center">
                    <div class="text-red-800 text-sm font-semibold">
                        {{ errorMessage || `There ${Object.keys($page.props.errors).length === 1 ? 'is' : 'are'} ${Object.keys($page.props.errors).length} form error${Object.keys($page.props.errors).length === 1 ? '' : 's'}.` }}
                    </div>
                </div>
                <button type="button" class="ml-2 p-1" @click="hideAlert">
                    <svg class="w-5 h-5 text-red-600 hover:text-red-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 9L4 3m12 0L10 9m0 0L4 15m12 0L10 9" stroke="currentColor" stroke-width="2" fill="none" />
                    </svg>
                </button>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    data() {
        return {
            show: true,
            successMessage: this.$page.props.flash.success || '',
            errorMessage: this.$page.props.flash.error || '',
            alertTimer: null,
        }
    },
    watch: {
        '$page.props.flash': {
            handler() {
                this.successMessage = this.$page.props.flash.success || '';
                this.errorMessage = this.$page.props.flash.error || '';
                if (this.successMessage || this.errorMessage) {
                    this.showAlert();
                }
            },
            deep: true,
        },
        '$page.props.errors': {
            handler(newErrors) {
                if (Object.keys(newErrors).length > 0) {
                    this.errorMessage = null;
                    this.showAlert();
                }
            },
            deep: true,
        },
    },
    methods: {
        showAlert() {
            this.show = true;
            if (this.alertTimer) {
                clearTimeout(this.alertTimer);
            }
            this.alertTimer = setTimeout(() => {
                this.hideAlert();
            }, 6000); // Show alert for 6 seconds
        },
        hideAlert() {
            this.show = false;
        },
        resetShow() {
            this.show = false;
        }
    },
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
