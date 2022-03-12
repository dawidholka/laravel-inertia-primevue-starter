<template>
    <Head title="Role Management"></Head>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toast />
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button
                                label="New"
                                icon="pi pi-plus"
                                class="p-button-success mr-2"
                                @click="openNew"
                            />
                            <Button
                                label="Delete"
                                icon="pi pi-trash"
                                class="p-button-danger"
                                @click="confirmDeleteSelected"
                                :disabled="
                                    !selectedModels || !selectedModels.length
                                "
                            />
                        </div>
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="models"
                    :lazy="true"
                    dataKey="id"
                    v-model:selection="selectedModels"
                    :paginator="true"
                    :rows="10"
                    :loading="loading"
                    :total-records="totalRecords"
                    v-model:filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} models"
                    responsiveLayout="scroll"
                    :selectAll="selectAll"
                    @select-all-change="onSelectAllChange"
                    @row-select="onRowSelect"
                    @row-unselect="onRowUnselect"
                    @page="onPage($event)"
                    @sort="onSort($event)"
                >
                    <template #header>
                        <div
                            class="flex flex-column md:flex-row md:justify-content-between md:align-items-center"
                        >
                            <h5 class="m-0">Manage Role</h5>
                            <span class="block mt-2 md:mt-0 p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText
                                    @change="onFilter($event)"
                                    v-model="filters['global'].value"
                                    placeholder="Search..."
                                />
                            </span>
                        </div>
                    </template>

                    <Column
                        selectionMode="multiple"
                        headerStyle="width: 3rem"
                    ></Column>
                    <Column
                        field="code"
                        header="Code"
                        :sortable="true"
                        headerStyle="width:5%; min-width:10rem;"
                    >
                        <template #body="slotProps">
                            <span class="p-column-title">Id</span>
                            {{ slotProps.data.id }}
                        </template>
                    </Column>
                    <Column
                        field="name"
                        header="Name"
                        :sortable="true"
                        headerStyle="width:40%; min-width:10rem;"
                    >
                        <template #body="slotProps">
                            <span class="p-column-title">Name</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>
                    <Column
                        field="guard_name"
                        header="Guard Name"
                        :sortable="true"
                        headerStyle="width:40%; min-width:10rem;"
                    >
                        <template #body="slotProps">
                            <span class="p-column-title">Guard Name</span>
                            {{ slotProps.data.guard_name }}
                        </template>
                    </Column>
                    <Column headerStyle="width:15%;min-width:10rem;">
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-pencil"
                                class="p-button-rounded p-button-success mr-2"
                                @click="editModel(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-warning mt-2"
                                @click="confirmDeleteModel(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>

                <Dialog
                    v-model:visible="modelDialog"
                    :style="{ width: '450px' }"
                    header="Role Detail"
                    :modal="true"
                    class="p-fluid"
                >
                    <div class="field">
                        <label for="name">Name</label>
                        <InputText
                            id="name"
                            v-model.trim="model.name"
                            required="true"
                            autofocus
                            :class="{ 'p-invalid': submitted && !model.name }"
                        />
                        <small class="p-invalid" v-if="submitted && !model.name"
                            >Name is required.</small
                        >
                    </div>
                    <div class="field">
                        <label for="description">Guard Name</label>
                        <InputText
                            id="guard_name"
                            v-model.trim="model.guard_name"
                            required="true"
                            autofocus
                            :class="{ 'p-invalid': submitted && !model.guard_name }"
                        />
                        <small
                            class="p-invalid"
                            v-if="submitted && !model.guard_name"
                            >Guard name is required.</small
                        >
                    </div>
                    <template #footer>
                        <Button
                            label="Cancel"
                            icon="pi pi-times"
                            class="p-button-text"
                            @click="hideDialog"
                        />
                        <Button
                            label="Save"
                            icon="pi pi-check"
                            class="p-button-text"
                            @click="saveModel"
                        />
                    </template>
                </Dialog>

                <Dialog
                    v-model:visible="deleteModelDialog"
                    :style="{ width: '450px' }"
                    header="Confirm"
                    :modal="true"
                >
                    <div class="flex align-items-center justify-content-center">
                        <i
                            class="pi pi-exclamation-triangle mr-3"
                            style="font-size: 2rem"
                        />
                        <span v-if="model"
                            >Are you sure you want to delete
                            <b>{{ model.name }}</b
                            >?</span
                        >
                    </div>
                    <template #footer>
                        <Button
                            label="No"
                            icon="pi pi-times"
                            class="p-button-text"
                            @click="deleteModelDialog = false"
                        />
                        <Button
                            label="Yes"
                            icon="pi pi-check"
                            class="p-button-text"
                            @click="deleteModel"
                        />
                    </template>
                </Dialog>

                <Dialog
                    v-model:visible="deleteModelsDialog"
                    :style="{ width: '450px' }"
                    header="Confirm"
                    :modal="true"
                >
                    <div class="flex align-items-center justify-content-center">
                        <i
                            class="pi pi-exclamation-triangle mr-3"
                            style="font-size: 2rem"
                        />
                        <span v-if="model"
                            >Are you sure you want to delete the selected
                            models?</span
                        >
                    </div>
                    <template #footer>
                        <Button
                            label="No"
                            icon="pi pi-times"
                            class="p-button-text"
                            @click="deleteModelsDialog = false"
                        />
                        <Button
                            label="Yes"
                            icon="pi pi-check"
                            class="p-button-text"
                            @click="deleteSelectedModels"
                        />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from "../../../Layouts/AppLayout";
import { FilterMatchMode } from "primevue/api";
import { Head } from "@inertiajs/inertia-vue3";
import Checkbox from "primevue/checkbox";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Message from "primevue/message";
import Toolbar from "primevue/toolbar";
import Toast from "primevue/toast";
import FileUpload from "primevue/fileupload";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputNumber from "primevue/inputnumber";
import Dropdown from "primevue/dropdown";
import RadioButton from "primevue/radiobutton";
import Dialog from "primevue/dialog";
import DatatableService from "../../../Services/DatatableService";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

export default {
    name: "Index",
    layout: AppLayout,
    components: {
        Head,
        Checkbox,
        Button,
        InputText,
        Password,
        Message,
        Toolbar,
        Toast,
        FileUpload,
        DataTable,
        Column,
        InputNumber,
        Dropdown,
        RadioButton,
        Dialog,
    },
    data() {
        return {
            searchFilter: "",
            filters: {
                global: {
                    value: null,
                    matchMode: FilterMatchMode.CONTAINS,
                },
                status: { value: null, matchMode: FilterMatchMode.EQUALS },
            },
            lazyParams: {
                global: {
                    value: null,
                    matchMode: FilterMatchMode.CONTAINS,
                },
                status: { value: null, matchMode: FilterMatchMode.EQUALS },
            },
            loading: true,
            totalRecords: 0,
            selectedModel: null,
            model: {},
            modelDialog: false,
            models: null,
            deleteModelDialog: false,
            deleteModelsDialog: false,
            selectedModels: null,
            submitted: false,
            selectAll: false,
        };
    },
    datatableService: null,
    created() {
        this.datatableService = new DatatableService();
        this.initFilters();
    },
    mounted() {
        this.loading = true;
        this.lazyParams = {
            first: 0,
            rows: this.$refs.dt.rows,
            sortField: "id",
            sortOrder: -1,
            filters: this.filters,
        };
        this.loadLazyData();
    },
    methods: {
        loadLazyData() {
            this.loading = true;
            this.datatableService
                .getData(this.route("role.index"), this.lazyParams)
                .then((data) => {
                    this.models = data.data;
                    this.totalRecords = data.total;
                    this.loading = false;
                });
        },
        onSelectAllChange(event) {
            const selectAll = event.checked;
            if (selectAll) {
                this.datatableService.getData(this.route("role.index"),this.lazyParams).then((data) => {
                    this.selectAll = true;
                    this.selectedModels = data.data;
                });
            } else {
                this.selectAll = false;
                this.selectedModels = [];
            }
        },
        onRowSelect() {
            this.selectAll = this.selectedModels.length === this.totalRecord;
        },
        onRowUnselect() {
            this.selectAll = false;
        },
        onPage(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {
            this.lazyParams = event;
            this.loadLazyData();
        },
        onFilter(event) {
            this.loadLazyData();
        },
        openNew() {
            this.model = {};
            this.submitted = false;
            this.modelDialog = true;
        },
        hideDialog() {
            this.modelDialog = false;
            this.submitted = false;
        },
        saveModel() {
            this.submitted = true;
            if (this.model) {
                if (this.model.id) {
                    axios
                        .put("api/role/" + this.model.id, this.model)
                        .then((response) => {
                            this.$toast.add({
                                severity: "success",
                                summary: "Successful",
                                detail: "Role Updated",
                                life: 3000,
                            });
                            this.loadLazyData();
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$toast.add({
                                severity: "error",
                                summary: "Error",
                                detail: "Role not updated",
                                life: 3000,
                            });
                        });
                }
                // If empty then add new record
                else {
                    axios
                        .post("api/role", this.model)
                        .then((response) => {
                            this.model = response.data.data;
                            this.models.unshift(this.model);
                            this.$toast.add({
                                severity: "success",
                                summary: "Successful",
                                detail: "Role Created",
                                life: 3000,
                            });
                        })
                        .catch((error) => {
                            this.$toast.add({
                                severity: "error",
                                summary: "Error",
                                detail: "Role not created",
                                life: 3000,
                            });
                        });
                }
                this.modelDialog = false;
                this.model = {};
            }
        },
        editModel(model) {
            this.model = { ...model };
            this.modelDialog = true;
        },
        confirmDeleteModel(model) {
            this.model = model;
            this.deleteModelDialog = true;
        },
        deleteModel() {
            axios
                .delete("api/role/" + this.model.id)
                .then((response) => {
                    this.$toast.add({
                        severity: "success",
                        summary: "Successful",
                        detail: "Role Deleted",
                        life: 3000,
                    });
                    this.deleteModelDialog = false;
                    this.model = {};
                    this.loadLazyData();
                })
                .catch((error) => {
                    this.$toast.add({
                        severity: "error",
                        summary: "Error",
                        detail: "Role not deleted",
                        life: 3000,
                    });
                });
        },
        confirmDeleteSelected() {
            this.deleteModelsDialog = true;
        },
        deleteSelectedModels() {
            this.models = this.models.filter(
                (val) => !this.selectedModels.includes(val)
            );
            this.deleteModelsDialog = false;
            this.selectedModels = null;
            this.$toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Models Deleted",
                life: 3000,
            });
        },
        initFilters() {
            this.filters = {
                global: { value: null, matchMode: FilterMatchMode.CONTAINS },
            };
        },
    },
};
</script>

<style scoped lang="scss">
</style>
