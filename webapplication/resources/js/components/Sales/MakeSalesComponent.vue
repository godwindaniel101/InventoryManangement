<template>
    <div class="contentWrapper">
        <div class="card-container">
            <div class="card-container-head">
                <i
                    class="fas fa-plus-circle addIcon"
                    style="font-size:40px;"
                    @click="addRow()"
                ></i>
                <input
                    type="text"
                    class="form-control"
                    style="float:right;width:20%"
                    v-model="salesDetail.transactionId"
                    disabled
                />
            </div>
            <div class="card-body" style="background: #f4f6f9">
                <table style="width:100%">
                    <thead>
                        <tr class="sales_head">
                            <th style="width:30%;"><label>Product </label></th>
                            <th style="width:13%;"><label>Qty.a </label></th>
                            <th style="width:14.2%;"><label>Cost</label></th>
                            <th style="width:14.2%;">
                                <label>Quantity</label>
                            </th>
                            <th style="width:14.2%;">
                                <label>Discount(%)</label>
                            </th>
                            <th style="width:18.7%;"><label>Total</label></th>
                        </tr>
                    </thead>
                </table>

                <table
                    class="salescontainer"
                    :style="{ height: salescontainer + 'px' }"
                >
                    <transition-group name="slide" mode="out-in">
                        <app-sales-form
                            :key="data.id"
                            v-for="(data, index) in salesDetail.salesArray"
                            @productChanged="getProductDetail($event, index)"
                            @deleteRow="deleteRow(index)"
                            :salesData="data"
                        >
                        </app-sales-form>
                    </transition-group>
                </table>
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
                netQuantity: ""
            }),
            counter: 0,
            salesError: false,
            validator: false
        };
    },
    methods: {
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
            if (this.validator == true) {
                this.salesError = false;
                this.$Progress.start();
                this.salescontainer = 0;
                this.salesDetail
                    .post("/api/createSales")
                    .then(({ data }) => {
                        this.salesDetail.reset(),
                            (this.salesDetail.netTotal = "");
                        this.getTransactionId();
                        Toast.fire({
                            icon: "success",
                            title: "Sales Made"
                        });
                        this.$Progress.finish();
                        setTimeout(
                            this.addRow(),

                            100
                        );
                        this.salescontainer = 0;
                    })
                    .catch(({}) => {
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
            salesArray[index]["product_id"] = data.id;
            salesArray[index]["product_price"] = data.price;
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
        },
        addRow() {
            this.salescontainer = this.salescontainer + 41;
            this.counter++;
            var salesArray = this.salesDetail.salesArray;
            salesArray.push({
                id: this.counter,
                product_id: "",
                product_name: "",
                product_quantity: "",
                product_count: "",
                product_price: "",
                product_id: "",
                validate: false,
                unitTotal: ""
            });
        },
        //edit section
        updateSales() {
            dataCheck.$emit("checkunitData");
            if (this.validator == true) {
                this.salesError = false;
                this.$Progress.start();
                this.salesDetail
                    .put("/api/updateSales")
                    .then(({ data }) => {
                        //  console.log(data);

                        this.$Progress.start();
                        this.salesDetail.reset(),
                            (this.salesDetail.netTotal = "");
                        this.editMode = false;
                        //   dataCheck.$emit('resetEdit');
                        this.getTransactionId();
                        Toast.fire({
                            icon: "success",
                            title: "Sales Updated"
                        });
                        this.$Progress.finish();
                    })
                    .catch(({}) => {
                        this.$Progress.fail();
                        this.salesError = true;
                    });
            } else {
                this.salesError = true;
            }
        },
        unitEditMode(id) {
            this.editMode = true;
            this.salesDetail.transactionId = id;
            this.salesDetail.salesArray = [];
            (this.salescontainer = 0),
                axios
                    .get("/api/getSalesRecords/" + id)
                    .then(data => {
                        this.editSalesRecord = data.data;
                        var a = data.data.length;
                        for (let i = 0; a > i; i++) {
                            this.salesDetail.salesArray.push(data.data[i]);
                        }
                        setTimeout(function() {
                            dataCheck.$emit("setEditedmode");
                        }, 500);
                    })
                    .catch(() => {});
        }
    },
    mounted() {
        this.addRow();
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
