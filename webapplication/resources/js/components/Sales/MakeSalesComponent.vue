<template>
    <div class="contentWrapper">
        <div class="card-container">
            <div class="card-container-head">
                <!-- <i
                    class="fas fa-plus-circle addIcon"
                    style="font-size:40px;"
                   
        ></i>-->
                <svg
                    @click="addRow()"
                    xmlns="http://www.w3.org/2000/svg"
                    class="icon icon-tabler icon-tabler-circle-plus"
                    width="44"
                    height="44"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="white"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path stroke="none" d="M0 0h24v24H0z" />
                    <circle cx="12" cy="12" r="9" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                    <line x1="12" y1="9" x2="12" y2="15" />
                </svg>
                <div class="header-tag">
                    <div class="header_branch">
                        <p>Branch</p>
                    </div>
                    <div class="header-form">
                        <div>
                            <v-select
                                :options="branches"
                                :reduce="name => name.id"
                                @input="getDefault()"
                                v-model="selectedBranch"
                                :value="selectedBranch"
                                class="header-form-control"
                                label="name"
                            ></v-select>
                        </div>
                        <div class="form-control-trans">
                            <p v-text="salesDetail.transactionId"></p>
                        </div>
                    </div>
                </div>
                <!-- <input
                    type="text"
                    class="form-control"
                    style="float:right;width:100px;border:none;background:rgb(139, 195, 74);"
                    v-model="salesDetail.transactionId"
                    disabled
        />-->
            </div>
            <div class="card-body" style="background: #f4f6f9;padding-top:0;">
                <div class="table-no-scroll fixedCardHeight">
                    <table
                        style="width:100%;margin:0"
                        class="table table-hover"
                    >
                        <thead>
                            <tr class="sales_head">
                                <th style="width:30%;">
                                    <label>Product</label>
                                </th>
                                <th style="width:13%;">
                                    <label>Qty.a</label>
                                </th>
                                <th style="width:14.2%;">
                                    <label>Cost</label>
                                </th>
                                <th style="width:14.2%;">
                                    <label>Quantity</label>
                                </th>
                                <th style="width:14.2%;">
                                    <label>Discount(%)</label>
                                </th>
                                <th style="width:18.7%;">
                                    <label>Total</label>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <table
                        v-show="recordExist"
                        class="salescontainer"
                        :style="{ height: salescontainer + 'px' }"
                    >
                        <transition-group name="slide" mode="out-in">
                            <app-sales-form
                                :key="data.id"
                                v-for="(data, index) in salesDetail.salesArray"
                                @productChanged="
                                    getProductDetail($event, index)
                                "
                                @deleteRow="deleteRow(index)"
                                :salesData="data"
                                :myproducts="products"
                                :mybranch="selectedBranch"
                            ></app-sales-form>
                        </transition-group>
                    </table>
                    <div v-show="!recordExist" class="noRecordcontianer">
                        <div class="noRecordImage" v-show="!imageNotNeeded">
                            <img src="/image/preloader.gif" />
                        </div>
                        <div class="noRecordText">
                            <p v-text="recordContent"></p>
                        </div>
                    </div>
                </div>
                <hr />
                <table style="width:100%;z-index:20;position:relative">
                    <tr>
                        <td>
                            <button
                                v-show="!editMode"
                                :class="
                                    salesError
                                        ? 'salesError'
                                        : 'btn-dash-primary'
                                "
                                class="btn-dash"
                                @click="createSales()"
                                :disabled="salesButton"
                            >
                                Make Sales
                            </button>
                            <button
                                v-show="editMode"
                                :class="
                                    salesError
                                        ? 'salesError'
                                        : 'btn-dash-success'
                                "
                                class="btn-dash"
                                @click="updateSales()"
                            >
                                Update Sales
                            </button>
                        </td>
                        <td style="float:right">
                            <input
                                type="text"
                                class="form-control"
                                v-model="salesDetail.netTotal"
                                disabled
                            />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import salesForm from "./SalesForm.vue";
import { dataCheck } from "../../app";
export default {
    components: {
        appSalesForm: salesForm
    },
    data() {
        return {
            salescontainer: 0,
            editMode: false,
            salesDetail: new Form({
                salesArray: [],
                netTotal: "",
                transactionId: "",
                netQuantity: "",
                selectedBranch: ""
            }),
            counter: 0,
            salesError: false,
            validator: false,
            token: "",
            branches: [],
            products: [],
            recordExist: false,
            salesButton: true,
            imageNotNeeded: true,
            selectedBranch: "",
            recordContent: ". . .Select A Sales Branch"
        };
    },
    methods: {
        getDefault() {
            // alert('this.selectedBranch' )
            if (this.selectedBranch > 0) {
                this.imageNotNeeded = false;
                this.salesDetail.selectedBranch = this.selectedBranch;
                (this.recordExist = false),
                    (this.recordContent = ". . .Processing");

                // this.salescontainer = 0;
                axios
                    .get("/api/getBranchProduct/" + this.selectedBranch, {
                        headers: {
                            authorization: "Bearer " + this.token
                        }
                    })
                    .then(({ data }) => {
                        // console.log(data);
                        if (data.length > 0) {
                            this.recordExist = true;
                            this.products = data;

                            if (!this.editMode) {
                                this.addRow();
                            }

                            this.salesButton = false;
                        } else {
                            this.recordExist = false;
                            this.imageNotNeeded = true;
                            this.recordContent =
                                ". . .Selected Branch have No Product";
                        }
                    })
                    .catch(() => {
                        this.recordExist = false;
                        this.imageNotNeeded = false;
                        this.recordContent =
                            ". . .There was a Problem Loading  Data";
                    });
            }
        },
        getToken() {
            this.token = localStorage.getItem("access_token");
        },
        getBranch() {
            axios
                .get("/api/branch/getBranch", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    if (response.data.length > 0) {
                        this.branches = response.data;
                    } else {
                    }
                })
                .catch(() => {});
        },
        getTransactionId() {
            axios
                .get("/api/getTransactionId/sales")
                .then(({ data }) => {
                    this.salesDetail.transactionId = data;
                })
                .catch(() => {});
        },
        createSales() {
            dataCheck.$emit("checkunitData");
            this.salesDetail.selectedBranch = this.selectedBranch;
            if (this.validator == true) {
                this.salesError = false;
                this.$Progress.start();
                this.salescontainer = 0;
                this.salesDetail
                    .submit("post", "/api/createSales", {
                        headers: {
                            authorization: "Bearer " + this.token
                        }
                    })

                    .then(({ data }) => {
                        // console.log(data);

                        if (data.success) {
                            this.salesDetail.reset(),
                                (this.salesDetail.netTotal = "");
                            this.getTransactionId();
                            Toast.fire({
                                icon: "success",
                                title: data.message
                            });
                            this.$Progress.finish();
                            setTimeout(
                                this.addRow(),

                                100
                            );
                            this.salescontainer = 0;
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: data.message
                            });
                        }
                    })
                    .catch(({}) => {
                        Toast.fire({
                            icon: "error",
                            title: "Sales Failed"
                        });
                        this.$Progress.fail();
                        this.salesError = true;
                    });
            } else {
                this.salesError = true;
            }
        },
        deleteRow(index) {
            this.salescontainer = this.salescontainer - 41;
            var salesArray = this.salesDetail.salesArray;
            salesArray.splice(index, 1);
        },
        getProductDetail(data, index) {
            var salesArray = this.salesDetail.salesArray;
            var b = salesArray.length;
            var productcount = 0;
            for (let i = 0; b > i; i++) {
                if (salesArray[i]["product_stock_id"] === data.stock_id) {
                    productcount++;
                }
            }
            if (productcount < 2) {
                salesArray[index]["product_id"] = data.id;
                salesArray[index]["product_cost"] = data.cost;
                salesArray[index]["product_stock_id"] = data.stock_id;
                salesArray[index]["product_count"] = data.count;
                salesArray[index]["product_quantity"] = data.quantity;
                salesArray[index]["product_discount"] = data.discount;
                salesArray[index]["product_unitTotal"] = data.unitTotal;
                salesArray[index]["validate"] = data.validate;
                var b = salesArray.length;
                var c = true;
                var a = 0;
                var d = 0;
                for (let i = 0; b > i; i++) {
                    if (
                        salesArray[i]["product_unitTotal"] == " " ||
                        salesArray[i]["product_unitTotal"] == null
                    ) {
                        a += 0;
                    } else {
                        a += Number(salesArray[i]["product_unitTotal"]);
                    }
                    if (
                        salesArray[i]["product_quantity"] == " " ||
                        salesArray[i]["product_quantity"] == null
                    ) {
                        d += 0;
                    } else {
                        d += Number(salesArray[i]["product_quantity"]);
                    }

                    c = new Boolean(c * salesArray[i]["validate"]);
                }
                this.salesDetail.netTotal = a;
                this.salesDetail.netQuantity = d;
                this.validator = c;
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Product Already Selected"
                });
                this.deleteRow(index);
            }
        },
        addRow() {
            this.salescontainer = this.salescontainer + 50;
            this.counter++;
            var salesArray = this.salesDetail.salesArray;
            salesArray.push({
                id: this.counter,
                product_id: "",
                product_name: "",
                product_quantity: "",
                product_count: "",
                product_cost: "",
                product_stock_id: "",
                product_id: "",
                validate: false,
                unitTotal: ""
            });
            dataCheck.$emit("branchData", this.products);
        },
        //edit section
        updateSales() {
            dataCheck.$emit("checkunitData");
            this.salesDetail.selectedBranch = this.selectedBranch;
            if (this.validator == true) {
                this.salesError = false;
                // this.$Progress.start();
                this.salesDetail
                    .put("/api/updateSales", {
                        headers: {
                            authorization: "Bearer " + this.token
                        }
                    })
                    .then(({ data }) => {
                        if(data.success){
                        this.salesDetail.reset(), (this.editMode = false);
                        this.getTransactionId();
                        dataCheck.$emit("resetEdit");
                        Toast.fire({
                            icon: "success",
                            title: data.message,
                        });
                        }else{
                            Toast.fire({
                            icon: "error",
                            title: data.message,
                        }); 
                        }
                       
                        // this.$Progress.finish();
                    })
                    .catch(({}) => {
                        // this.$Progress.fail();
                        // this.salesError = true;
                        Toast.fire({
                            icon: "error",
                            title: "Sales Could not be Updated"
                        });
                    });
            } else {
                this.salesError = true;
            }
        },
        unitEditMode(id) {
            this.selectedBranch = "";
            this.salesDetail.transactionId = id;
            this.editMode = true;
            (this.recordExist = false), (this.imageNotNeeded = false);
            this.recordContent = ". . .Processing";
            this.salesDetail.salesArray = [];
            (this.salescontainer = 0),
                axios
                    .get("/api/getSalesRecords/" + id)
                    .then(dataRecord => {
                        // console.log(dataRecord);
                        var branch_id =
                            dataRecord.data["salesDetails"]["branch_id"];
                        this.selectedBranch = parseInt(branch_id);

                        axios
                            .get(
                                "/api/getBranchProduct/" + this.selectedBranch,
                                {
                                    headers: {
                                        authorization: "Bearer " + this.token
                                    }
                                }
                            )
                            .then(({ data }) => {
                                this.products = data;

                                var a = dataRecord.data["salesItems"].length;
                                for (let i = 0; a > i; i++) {
                                    this.salesDetail.salesArray.push(
                                        dataRecord.data["salesItems"][i]
                                    );
                                }

                                setTimeout(function() {
                                    dataCheck.$emit("setEditedmode");
                                }, 1000);
                                this.recordExist = true;
                            })
                            .catch(() => {});
                    })
                    .catch(() => {});
        },
        getUserCurrentBranch() {
            this.loadingStatus = "loading";
            axios
                .get("/api/branch/getUserCurrentBranch", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(({ data }) => {
                    // console.log(data);
                    this.selectedBranch = parseInt(data.branch_id);
                    this.getDefault();
                })
                .catch(() => {});
            return true;
        }
    },
    mounted() {
        this.getToken();
        this.getBranch();
        this.getUserCurrentBranch();
        this.getTransactionId();
    },
    created() {
        dataCheck.$on("setEditMode", id => {
            this.unitEditMode(id);
        });
    }
};
</script>
<style scoped>
table th {
    text-align: left;
}
.salesError {
    border: 1px solid red;
    background: red;
    color: white;
}
.slide-enter {
    opacity: 0;
}
.slide-enter-active {
    animation: slide-in 1s ease-out forwards;
    transition: opacity 0.5s;
    position: absolute;
}
.slide-leave {
}
.slide-leave-active {
    animation: slide-in 1s ease-out forwards;
    transition: opacity 1s;
}
.slide-move {
    transition: transform 1s;
}
.salescontainer {
    transition: height 500ms;
    position: relative;
    width: 100%;
}
@keyframes slide-in {
    from {
        transform: translateY(10px);
    }
    to {
        transform: translateY(0px);
    }
}
@keyframes slide-out {
    from {
        transform: translateY(0px);
    }
    to {
        transform: translateY(1px);
    }
}
</style>
