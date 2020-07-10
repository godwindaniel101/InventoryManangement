<template>
    <div class="contentWrapper">
        <div class="card-container">
            <!-- <button @click="clickca()">Update</button> -->
            <div class="card-container-head">
                <h3 class="card-container-head-text">Product Stock</h3>

                <div class="card-toolsx">
                    <svg
                        data-toggle="modal"
                        data-target=".modal"
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-arrows-maximize"
                        width="25"
                        height="25"
                        viewBox="0 0 24 24"
                        stroke-width="1"
                        stroke="black"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <polyline points="16 4 20 4 20 8" />
                        <line x1="14" y1="10" x2="20" y2="4" />
                        <polyline points="8 20 4 20 4 16" />
                        <line x1="4" y1="20" x2="10" y2="14" />
                        <polyline points="16 20 20 20 20 16" />
                        <line x1="14" y1="14" x2="20" y2="20" />
                        <polyline points="8 4 4 4 4 8" />
                        <line x1="4" y1="4" x2="10" y2="10" />
                    </svg>
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Branch</th>
                        <th>Product</th>
                        <th>Maximum Qty</th>
                        <th>Warning Qty</th>
                        <th>Current Qty</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(product, index) in productStock"
                        :key="productStock.id"
                    >
                        <td>{{ index + 1 }}</td>
                        <td>{{ product.branch_name | textTransform }}</td>
                        <td>{{ product.product_name | textTransform }}</td>
                        <td>{{ product.product_max }}</td>
                        <td>{{ product.product_warn }}</td>
                        <td>
                            {{
                                product.product_count > 0
                                    ? product.product_count
                                    : 0
                            }}
                        </td>
                        <td>{{ product.created_at | dateChange }}</td>
                        <td>
                            <div class="action-wrapper">
                                <a
                                    class="action-edit"
                                    href="javascript:;"
                                    @click="edit(product.id)"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a
                                    class="action-delete"
                                    href="javascript:;"
                                    @click="deleteProduct(product.id)"
                                >
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-show="!recordExist" class="noRecordcontianer">
                <div class="noRecordImage">
                    <img src="/image/preloader.gif" />
                </div>
                <div class="noRecordText">
                    <p v-text="recordContent"></p>
                </div>
            </div>
            <!-- <button @click="getProductStock()">My Click</button> -->
        </div>
        <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{
                                editMode
                                    ? "Edit Product Stock"
                                    : "Create Product Stock"
                            }}
                        </h5>
                        <button
                            type="button"
                            class="close"
                            @click="clearfield"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form
                        @submit.prevent="editMode ? update() : create()"
                        @keydown="form.onKeydown($event)"
                    >
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <v-select
                                            class="form-control-drop"
                                            label="name"
                                            :options="branches"
                                            :value="form.name"
                                            :reduce="name => name.id"
                                            @input="branchSelected()"
                                            v-model="form.branch_id"
                                            :disabled="editMode ? true : false"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'branch_id'
                                                )
                                            }"
                                        ></v-select>
                                        <has-error
                                            :form="form"
                                            field="branch_id"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <v-select
                                            class="form-control-drop"
                                            label="product_name"
                                            v-model="form.product_id"
                                            :value="form.product_id"
                                            :options="products"
                                            :disabled="editMode ? true : false"
                                            :reduce="
                                                product_name => product_name.id
                                            "
                                            @input="productSelected()"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'product_id'
                                                )
                                            }"
                                        ></v-select>
                                        <has-error
                                            :form="form"
                                            field="product_id"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input
                                            v-model="form.product_warn"
                                            type="number"
                                            name="product_warn"
                                            placeholder="Product Warning Quantity"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'product_warn'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="product_warn"
                                        ></has-error>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input
                                            v-model="form.product_max"
                                            type="number"
                                            name="product_max"
                                            placeholder="Product Maximum Quantity"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'product_max'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="product_max"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input
                                            v-model="form.product_count"
                                            type="number"
                                            name="product_count"
                                            placeholder="Product Count"
                                            class="form-control"
                                            :disabled="editMode ? true : false"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'product_count'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="product_count"
                                        ></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn-secondary"
                                @click="clearfield"
                            >
                                Close
                            </button>
                            <button
                                :disabled="form.busy"
                                v-show="!editMode"
                                type="submit"
                                class="btn-primary"
                            >
                                Create
                            </button>
                            <button
                                :disabled="form.busy"
                                v-show="editMode"
                                type="submit"
                                class="btn-primary"
                            >
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
export default {
    data() {
        return {
            form: new Form({
                id: "",
                product_id: "",
                branch_id: "",
                product_count: "",
                product_max: "",
                product_warn: ""
            }),
            products: [],
            editMode: false,
            productStock: {},
            branches: [],
            recordExist: false,
            recordContent: ". . .Loading Data"
        };
    },
    methods: {
        branchSelected() {
            this.form.clear("branch_id");
            this.productSelected();
        },
        productSelected() {
            this.form.clear("product_id");
            this.form
                .submit("post", "/api/product_stock/checkProductStock", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    console.log(response);
                })
                .catch(() => {});
        },
        clickca() {
            this.$store.state.counter++;
        },
        getProduct() {
            axios
                .get("/api/product")
                .then(({ data }) => {
                    console.log(data);
                    this.products = data;
                    // this.productStock = data;
                })
                .catch(() => {});
        },
        getBranch() {
            axios
                .get("/api/branch/getBranch", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    console.log(response);
                    if (response.data.length > 0) {
                        this.branches = response.data;
                    } else {
                        console.log(this.branches);
                    }
                })
                .catch(() => {
                    console.log(this.branches);
                });
        },

        create() {
            this.$Progress.start();
            this.form.clear("product_id");
            this.form
                .submit("post", "/api/product_stock/createProductStock", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    console.log(response);
                    if (response.status) {
                        Toast.fire({
                            icon: "success",
                            title: "ProductStock created"
                        });
                        this.getProductStock();
                        $(".modal").modal("hide");
                        this.clearfield();
                        this.$Progress.finish();
                    }
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        getProductStock() {
            this.form
                .submit("get", "/api/product_stock/getProductStock", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    console.log(response);
                    if (response.status) {
                        if (response.data.data.length > 0) {
                            this.recordContent = "";
                            this.recordExist = true;
                            this.productStock = response.data.data;
                        } else {
                            this.recordContent = "No Record Found";
                            this.recordExist = false;
                        }
                    }
                })
                .catch(() => {
                    this.$Progress.fail();
                    this.recordContent =
                        ". . .Error Getting Record, Login again and try...";
                    this.recordExist = false;
                });
        },
        edit(id) {
            this.editMode = true;
            this.form
                .submit("get", "/api/product_stock/getEditProductStock/" + id)
                .then(response => {
                    console.log(response);
                    var data = response.data.data;
                    if (response.data.status) {
                        this.form.id = data.id;
                        this.form.branch_id = data.name;
                        this.form.product_id = data.product_name;
                        this.form.product_count = data.product_count;
                        this.form.product_max = data.product_max;
                        this.form.product_warn = data.product_warn;
                        $(".modal").modal("show");
                    }
                })
                .catch(() => {});
        },
        update() {
            var id = this.form.id;
            this.$Progress.start();
            this.form
                .put("/api/product_stock/updateProductStock/" + id)
                .then(({ data }) => {
                    console.log(data);
                    Toast.fire({
                        icon: "success",
                        title: "ProductStock Updated"
                    });
                    $(".modal").modal("hide");
                    this.editMode = false;
                    this.getProductStock();
                    this.$Progress.finish();
                    this.form.reset();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        deleteProduct(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "rgb(139, 195, 74)",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    this.form.submit(
                        "delete",
                        "/api/product_stock/deleteStock/" + id
                    ).then(response => {
                        if (response.status) {
                            Swal.fire(
                                "Deleted!",
                                // "Your file has been deleted.",
                                response.message,
                                "success"
                            );
                            this.getProductStock();
                        }
                    });
                }
            });
        },
        clearfield() {
            this.form.reset();
            this.editMode = false;
            $(".modal").modal("hide");
        },
        getToken() {
            this.token = localStorage.getItem("access_token");
        }
    },
    mounted() {
        console.log(this.editMode);
        this.getToken();
        this.getBranch();
        this.getProduct();
        this.getProductStock();
    }
};
</script>
