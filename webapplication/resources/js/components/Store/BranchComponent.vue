<template>
    <div class="contentWrapper">
        <div class="card-container">
            <!-- <button @click="clickca()">Update</button> -->
            <div class="card-container-head">
                <h3 class="card-container-head-text">Branch</h3>

                <div class="card-toolsx">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="icon icon-tabler icon-tabler-apps"
                        data-toggle="modal"
                        data-target=".modal"
                        width="35"
                        height="35"
                        viewBox="0 0 24 24"
                        stroke-width="1"
                        stroke="#fff"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <rect x="4" y="4" width="6" height="6" rx="1" />
                        <rect x="4" y="14" width="6" height="6" rx="1" />
                        <rect x="14" y="14" width="6" height="6" rx="1" />
                        <line x1="14" y1="7" x2="20" y2="7" />
                        <line x1="17" y1="4" x2="17" y2="10" />
                    </svg>
                </div>
            </div>
            <div class="card-body">
                <div class="fixedCardHeight-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Branch</th>
                                <th style="width:auto;">Address</th>
                                <th>Manager</th>
                                <th>Number</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-show="recordExist">
                            <tr
                                v-for="(branch, index) in branches.data"
                                :key="branches.data.id"
                            >
                                <td>{{ index + 1 }}</td>
                                <td>{{ branch.name | textTransform }}</td>
                                <td>{{ branch.address }}</td>
                                <td>{{ branch.manager }}</td>
                                <td>{{ branch.number }}</td>
                                <td>{{ branch.created_at | dateChange }}</td>
                                <td>
                                    <div class="action-wrapper">
                                        <a
                                            class="action-edit"
                                            href="javascript:;"
                                            @click="editBranch(branch.id)"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a
                                            class="action-delete"
                                            href="javascript:;"
                                            @click="deleteBranch(branch.id)"
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
                </div>
                <div class="paginated">
                    <pagination
                        :data="branches"
                        @pagination-change-page="getResults"
                    ></pagination>
                </div>
            </div>

            <!-- <button @click="getProductStock()">My Click</button> -->
        </div>
        <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ editMode ? "Edit Branch" : "Create Branch" }}
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
                    <div
                        :class="{
                            'is-invalid': form.errors.has('invalid_token')
                        }"
                    ></div>
                    <form
                        @submit.prevent="editMode ? update() : create()"
                        @keydown="form.onKeydown($event)"
                    >
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            name="name"
                                            placeholder="Branch Name"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'name'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="name"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input
                                            v-model="form.address"
                                            type="text"
                                            name="address"
                                            placeholder="Address"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'address'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="address"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <!-- {{errors.get('name')}} -->
                                    <div class="form-group">
                                        <input
                                            v-model="form.manager"
                                            type="text"
                                            name="manager"
                                            placeholder="Manager Name"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'manager'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="manager"
                                        ></has-error>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input
                                            v-model="form.number"
                                            type="text"
                                            name="number"
                                            placeholder="Contact Number"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': form.errors.has(
                                                    'number'
                                                )
                                            }"
                                        />
                                        <has-error
                                            :form="form"
                                            field="number"
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
class Error {
    constructor() {
        this.errors = {};
    }
    get(field) {
        if (this.errors[field]) {
            return this.error[field];
        }
    }
    record(errors) {
        this.errors = errors;
        console.log(errors);
    }
}
export default {
    data() {
        return {
            errors: new Error(),
            form: new Form({
                id: "",
                name: "",
                address: "",
                manager: "",
                number: ""
            }),
            branches: {},
            editMode: false,
            token: null,
            recordExist: false,
            recordContent: ". . .Loading Data"
        };
    },
    methods: {
        clickca() {
            this.$store.state.counter++;
        },
        editBranch(id) {
            // alert(id);
            this.form
                .submit("get", "/api/branch/getBranch/" + id)
                .then(response => {
                    console.log(response.status);
                    if (response) {
                        this.editMode = true;
                        console.log(response.data.data);
                        this.form.fill(response.data.data);
                        $(".modal").modal("show");
                    }
                })
                .catch(() => {});
        },
        addProduct() {},
        getResults(page = 1) {
            this.recordExist = false;
            // this.$Progress.start();
            axios
                .get("/api/branch/getBranch?page=" + page, {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    this.branches = response.data;
                    this.recordExist = true;
                    // this.$Progress.finish();
                })
                .catch(() => {
                    this.recordExist = false;
                    this.recordContent =
                        ". . .Error Getting Record, Login again and try...";
                });
        },
        getBranch() {
            this.$Progress.start();
            this.form
                .submit("get", "/api/branch/getBranch", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(({ data }) => {
                    this.branches = data;
                    this.recordExist = true;
                    // if(response.data.length > 0)
                    //     {this.branches =  response.data,
                    //     this.recordExist=true}
                    // else{
                    //     this.recordContent = ". . .No Record Found"
                    //     this.recordExist=false
                    // }
                })
                .catch(() => {
                    this.recordContent =
                        ". . .Error Getting Record, Login again and try...";
                    this.recordExist = false;
                });
        },
        create() {
            this.$Progress.start();
            this.form
                .submit("post", "/api/branch/createBranch", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    if (response.status) {
                        Toast.fire({
                            icon: "success",
                            title: "Branch created"
                        });
                        this.getBranch();
                        $(".modal").modal("hide");
                        this.clearfield();
                        this.$Progress.finish();
                    } else {
                        this.$Progress.fail();
                    }
                })
                .catch(data => {
                    console.log(data);
                    this.$Progress.fail();
                });
        },
        edit(id) {},
        update() {
            this.$Progress.start();
            this.form
                .submit("put", "/api/branch/updateBranch/" + this.form.id, {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    if (response.status) {
                        Toast.fire({
                            icon: "success",
                            title: "Branch Updated"
                        });
                        this.getBranch();
                        $(".modal").modal("hide");
                        this.clearfield();
                        this.$Progress.finish();
                    } else {
                        this.$Progress.fail();
                    }
                })
                .catch(data => {
                    console.log(data);
                    this.$Progress.fail();
                });
        },
        deleteBranch(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Deleting this Branch will remove all Pending Stock!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "rgb(139, 195, 74)",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    this.$Progress.start();
                    this.form
                        .submit("delete", "/api/branch/deleteBranch/" + id)
                        .then(({ response }) => {
                            if (response.status) {
                                Swal.fire(
                                    "Deleted!",
                                    response.message,
                                    "success"
                                );
                                this.getBranch();
                                this.$Progress.finish();
                            }
                        })
                        .catch(() => {
                            this.$Progress.fail();
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
        this.getBranch();
    },
    created() {
        this.getToken();
        this.getBranch();
    }
};
</script>
