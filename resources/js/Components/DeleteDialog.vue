<template>
    <Dialog
        v-model:visible="dialogVisible"
        :style="{width: '450px'}"
        header="Potwierdzenie"
        :modal="true"
        :closable="false"
    >
        <div class="confirmation-content">
            <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem"></i>
            <span>
                {{ message }}
            </span>
        </div>
        <template #footer>
            <Button
                label="Anuluj"
                icon="pi pi-times"
                class="p-button-text"
                @click="onClose"
            />
            <Button
                label="Zapisz"
                icon="pi pi-check"
                class="p-button-text"
                :loading="loading"
                @click="onSubmit"
            />
        </template>
    </Dialog>
</template>

<script>
import Dialog from "primevue/dialog";
import Button from "primevue/button";

export default {
    name: "DeleteDialog",
    components: {
        Dialog,
        Button
    },
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
        loading: {
            type: Boolean,
            default: false
        },
        message: {
            type: String,
            default: 'Na pewno chcesz kontynuować?'
        }
    },
    emits: ['update:visible', 'delete'],
    data() {
        return {
            dialogVisible: this.visible,
        };
    },
    updated() {
        if (this.visible) {
            this.dialogVisible = this.visible;
        }
    },
    methods: {
        onSubmit() {
            this.$emit('delete', true);
        },
        onClose() {
            this.$emit('update:visible', false);
            this.dialogVisible = false;
        }
    }
}
</script>

<style scoped>

</style>
