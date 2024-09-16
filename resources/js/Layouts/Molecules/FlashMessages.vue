<template>
    <div>
        <!-- Success Alert -->
        <transition name="fade" @after-leave="resetShow">
            <div v-if="successMessage && show" class="flex items-center justify-between mb-8 max-w-3xl bg-blue-100 border border-blue-300 rounded-md p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="text-blue-800 text-sm font-medium">{{ successMessage }}</div>
                </div>
                <button type="button" class="ml-2 p-1" @click="hideAlert">
                    <svg class="w-4 h-4 text-blue-600 hover:text-blue-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 9L4 3m12 0L10 9m0 0L4 15m12 0L10 9" stroke="currentColor" stroke-width="2" fill="none" /></svg>
                </button>
            </div>
        </transition>

        <!-- Error Alert -->
        <transition name="fade" @after-leave="resetShow">
            <div v-if="errorMessage && show" class="flex items-center justify-between mb-8 max-w-3xl bg-red-100 border border-red-300 rounded-md p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="text-red-800 text-sm font-medium">
                        {{ errorMessage || `There ${Object.keys($page.props.errors).length === 1 ? 'is' : 'are'} ${Object.keys($page.props.errors).length} form error${Object.keys($page.props.errors).length === 1 ? '' : 's'}.` }}
                    </div>
                </div>
                <button type="button" class="ml-2 p-1" @click="hideAlert">
                    <svg class="w-4 h-4 text-red-600 hover:text-red-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 9L4 3m12 0L10 9m0 0L4 15m12 0L10 9" stroke="currentColor" stroke-width="2" fill="none" /></svg>
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
            alertTimer: null, // Add a reference to the timer
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
            // Clear any existing timer before setting a new one
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
