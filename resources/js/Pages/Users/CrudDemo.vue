<template>
    <Head title="User Management"></Head>
	<div class="grid">
		<div class="col-12">
			<div class="card">
				<Toast/>
				<Toolbar class="mb-4">
					<template v-slot:start>
						<div class="my-2">
							<Button label="New" icon="pi pi-plus" class="p-button-success mr-2" @click="openNew" />
							<Button label="Delete" icon="pi pi-trash" class="p-button-danger" />
						</div>
					</template>
				</Toolbar>

				<DataTable ref="dt" :value="models" v-model:selection="selectedModel" dataKey="id" :paginator="true" :rows="10" :filters="filters"
							paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
							currentPageReportTemplate="Showing {first} to {last} of {totalRecords} models" responsiveLayout="scroll">
					<template #header>
						<div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
							<h5 class="m-0">Manage User</h5>
							<span class="block mt-2 md:mt-0 p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Search..." />
                            </span>
						</div>
					</template>

					<Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
					<Column field="code" header="Code" :sortable="true" headerStyle="width:5%; min-width:10rem;">
						<template #body="slotProps">
							<span class="p-column-title">Id</span>
							{{slotProps.data.id}}
						</template>
					</Column>
					<Column field="name" header="Name" :sortable="true" headerStyle="width:40%; min-width:10rem;">
						<template #body="slotProps">
							<span class="p-column-title">Name</span>
							{{slotProps.data.name}}
						</template>
					</Column>
                    <Column field="email" header="Email" :sortable="true" headerStyle="width:40%; min-width:10rem;">
						<template #body="slotProps">
							<span class="p-column-title">Email</span>
							{{slotProps.data.email}}
						</template>
					</Column>
					<Column headerStyle="width:15%;min-width:10rem;">
						<template #body="slotProps">
							<Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="editModel(slotProps.data)" />
							<Button icon="pi pi-trash" class="p-button-rounded p-button-warning mt-2" @click="confirmDeleteProduct(slotProps.data.id)" />
						</template>
					</Column>
				</DataTable>

				<Dialog v-model:visible="modelDialog" :style="{width: '450px'}" header="User Detail" :modal="true" class="p-fluid">
					<div class="field">
						<label for="name">Name</label>
						<InputText id="name" v-model.trim="model.name" required="true" autofocus :class="{'p-invalid': submitted && !model.name}" />
						<small class="p-invalid" v-if="submitted && !model.name">Name is required.</small>
					</div>
					<div class="field">
						<label for="description">Email</label>
                        <InputText id="name" v-model.trim="model.email" required="true" autofocus :class="{'p-invalid': submitted && !model.email}" />
                        <small class="p-invalid" v-if="submitted && !model.email">Email is required.</small>
					</div>
					<template #footer>
						<Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
						<Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveModel" />
					</template>
				</Dialog>

				<Dialog v-model:visible="deleteProductDialog" :style="{width: '450px'}" header="Confirm" :modal="true">
					<div class="flex align-items-center justify-content-center">
						<i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
						<span v-if="product">Are you sure you want to delete <b>{{product.name}}</b>?</span>
					</div>
					<template #footer>
						<Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteProductDialog = false"/>
						<Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteProduct" />
					</template>
				</Dialog>

				<Dialog v-model:visible="deleteProductsDialog" :style="{width: '450px'}" header="Confirm" :modal="true">
					<div class="flex align-items-center justify-content-center">
						<i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
						<span v-if="product">Are you sure you want to delete the selected products?</span>
					</div>
					<template #footer>
						<Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteProductsDialog = false"/>
						<Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedProducts" />
					</template>
				</Dialog>
			</div>
		</div>
	</div>

</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import {FilterMatchMode} from 'primevue/api';
import { Head } from '@inertiajs/inertia-vue3';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Message from 'primevue/message';
import Toolbar from 'primevue/toolbar';
import Toast from 'primevue/toast';
import FileUpload from 'primevue/fileupload';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputNumber from 'primevue/inputnumber';
import Dropdown from 'primevue/dropdown';
import RadioButton from 'primevue/radiobutton';
import Dialog from 'primevue/dialog';
import DatatableService from '../../Services/DatatableService';
import { Inertia } from '@inertiajs/inertia';

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
        Dialog
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
            model: {},
            modelDialog: false,
            models: null
        }
	},
	datatableService: null,
	created() {
        this.datatableService = new DatatableService();
		this.initFilters();
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
            this.datatableService.getData(this.route('users.datatable'), this.datatable.lazyParams).then(data => {
                this.datatable.data = data.data;
                this.datatable.totalRecords = data.total;
                this.datatable.loading = false;
                this.models = data.data;
            });
        },
		formatCurrency(value) {
			if(value)
				return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
			return;
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
			if (this.model.name.trim()) {
			    if (this.model.id) {
				    this.models[this.findIndexById(this.model.id)] = this.model;

                    this.$inertia.put('user.update', this.model), {
                        onSuccess: () => {
                            this.$toast.add({severity:'success', summary: 'Successful', detail: 'User Updated', life: 3000});
                        },
                    }
				}
				else {
                    this.$inertia.post('user.create', this.model), {
                        onSuccess: () => {
                            this.$toast.add({severity:'success', summary: 'Successful', detail: 'User Created', life: 3000});
                        },
                    }

					this.model.push(this.model);
				}
				this.modelDialog = false;
				this.model = {};
			}
		},
		editModel(model) {
			this.model = {...model};
			this.modelDialog = true;
		},
		confirmDeleteProduct(product) {
			this.product = product;
			this.deleteProductDialog = true;
		},
		deleteProduct() {
			this.products = this.products.filter(val => val.id !== this.product.id);
			this.deleteProductDialog = false;
			this.product = {};
			this.$toast.add({severity:'success', summary: 'Successful', detail: 'Product Deleted', life: 3000});
		},
		findIndexById(id) {
			let index = -1;
			for (let i = 0; i < this.products.length; i++) {
				if (this.products[i].id === id) {
					index = i;
					break;
				}
			}
			return index;
		},
		createId() {
			let id = '';
			var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			for ( var i = 0; i < 5; i++ ) {
				id += chars.charAt(Math.floor(Math.random() * chars.length));
			}
			return id;
		},
		exportCSV() {
			this.$refs.dt.exportCSV();
		},
		confirmDeleteSelected() {
			this.deleteProductsDialog = true;
		},
		deleteSelectedProducts() {
			this.products = this.products.filter(val => !this.selectedProducts.includes(val));
			this.deleteProductsDialog = false;
			this.selectedProducts = null;
			this.$toast.add({severity:'success', summary: 'Successful', detail: 'Products Deleted', life: 3000});
		},
		initFilters() {
            this.filters = {
                'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
            }
        }
	}
}
</script>

<style scoped lang="scss">
@import '../../../assets/demo/badges.scss';
</style>
