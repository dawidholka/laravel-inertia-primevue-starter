<template>
        <div class="p-grid">
            <div class="p-col-12">
                <div class="card">
                    <Toolbar>
                        <template v-slot:start>
                            <div class="my-2">
                                Heheheh
                                <Button label="New" icon="pi pi-plus" class="p-button-success mr-2" @click="openNew" />
                                <Button label="Delete" icon="pi pi-trash" class="p-button-danger" />
                            </div>
                        </template>
                    </Toolbar>
                </div>
            </div>
            <div class="p-col-12">
                <div class="card">
                    <DataTable
                        ref="dt"
                        :value="datatable.data"
                        :lazy="true"
                        data-key="id"
                        :paginator="true"
                        :rows="10"
                        :loading="datatable.loading"
                        :total-records="datatable.totalRecords"
                        v-model:filters="datatable.filters"
                        paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        :rows-per-page-options="[5,10,25]"
                        current-page-report-template="Wyświetlanie od {first} do {last} z {totalRecords} elementów"
                        @page="onPage($event)"
                        @sort="onSort($event)"
                        @filter="onSort($event)"
                    >
                        <template #header>
                            <div class="table-header">
                                <h5 class="p-m-0">
                                    Użytkownicy
                                </h5>
                            </div>
                        </template>
                        <Column field="id" header="ID" :sortable="true">
                            <template #body="slotProps">
                                {{ slotProps.data.id }}
                            </template>
                        </Column>
                        <Column field="name" header="Nazwa" :sortable="true">
                            <template #body="slotProps">
                                {{ slotProps.data.name }}
                            </template>
                        </Column>
                        <Column field="email" header="E-mail" :sortable="true">
                            <template #body="slotProps">
                                {{ slotProps.data.email }}
                            </template>
                        </Column>
                        <Column header="Opcje" style="width: 150px;">
                            <template #body="slotProps">
                                <Button
                                    icon="pi pi-pencil"
                                    class="p-button-success p-button-sm mr-1"
                                    @click="edit(slotProps.data.id)"
                                />
                                <Button icon="pi pi-trash" class="p-button-sm p-button-danger"
                                        @click="showDeleteDialog(slotProps.data)"
                                />
                            </template>
                        </Column>
                        <template #empty>
                            Brak dodanych użytkowników.
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>

        <DeleteDialog
            ref="deleteDialog"
            v-model:visible="deleteDialog"
            :loading="deletingModel"
            @delete="onDelete"
        />
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import DataTable from "primevue/datatable";
import {FilterMatchMode} from "primevue/api";
import DatatableService from "../../Services/DatatableService";
import Menubar from "primevue/menubar";
import Column from "primevue/column";
import Button from "primevue/button";
import DeleteDialog from "../../Components/DeleteDialog";
import Toolbar from 'primevue/toolbar';

export default {
    name: "Index",
    layout: AppLayout,
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
        Button,
        Toolbar,
        DeleteDialog
    },
    data() {
        return {
            datatable: {
                loading: true,
                totalRecords: 0,
                data: null,
                filters: {
                    'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
                    'status': {value: null, matchMode: FilterMatchMode.EQUALS},
                },
                lazyParams: {}
            },
            menuItems: [
                {
                    label: 'Dodaj użytkownika',
                    icon: 'pi pi-fw pi-plus',
                    command: () => {
                        this.$inertia.get(this.route('users.create'));
                    },
                },
            ],
            importDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
            selectedModel: null,
            deleteDialog: false,
            deletingModel: false,
        }
    },
    datatableService: null,
    created() {
        this.datatableService = new DatatableService();
    },
    mounted() {
        this.datatable.loading = true;
        this.datatable.lazyParams = {
            first: 0,
            rows: this.$refs.dt.rows,
            sortField: 'id',
            sortOrder: -1,
            filters: this.datatable.filters
        };
        this.loadLazyData();
    },
    methods: {
        loadLazyData() {
            this.datatable.loading = true;
            this.datatableService.getData(this.route('user.index'), this.datatable.lazyParams).then(data => {
                this.datatable.data = data.data;
                this.datatable.totalRecords = data.total;
                this.datatable.loading = false;
            });
        },
        onPage(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
        },
        edit(id) {
            this.$inertia.get(this.route('users.edit', id));
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('users.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
