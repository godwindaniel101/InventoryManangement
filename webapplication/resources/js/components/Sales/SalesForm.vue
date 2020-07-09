<template>
    <tbody>
        <tr>
            <td>
                <div class="form-group">
                    <v-select
                        class="selectProduct"
                        label="product_name"
                        :value="selectedProduct"
                        :options="products"
                        :reduce="product_name => product_name.id"
                        @input="productSelected()"
                        v-model="selectedProduct"
                    ></v-select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input
                        type="text"
                        v-model="product.count"
                        class="form-control"
                        disabled
                    />
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input
                        type="text"
                        v-model="product.price"
                        class="form-control"
                        @keyup="total"
                    />
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input
                        type="text"
                        v-model="product.quantity"
                        class="form-control"
                        @keyup="total"
                    />
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input
                        type="text"
                        v-model="product.discount"
                        class="form-control"
                        @keyup="total"
                    />
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input
                        type="text"
                        v-model="product.unitTotal"
                        class="form-control"
                        disabled
                    />
                </div>
            </td>
            <td class="sales_trash">
                <i class="fas fa-trash sales_trash-icon text-red" @click="removeRow()"></i>
            </td>
        </tr>
        <tr>
            <td><p v-text="error.name"></p></td>
            <td><p v-text="error.count"></p></td>
            <td><p v-text="error.price"></p></td>
            <td><p v-text="error.quantity"></p></td>
            <td><p v-text="error.discount"></p></td>
            <td><p v-text="error.unitTotal"></p></td>
        </tr>
    </tbody>
</template>

<script>
 import {dataCheck} from '../../app';
export default {
    props: {
        salesData: {
            type: Object,
            require: true
        }
    },

    data() {
        return {
            products: [],
            editData: this.salesData,
            selectedProduct: "Select",
            error: {
                name: "",
                count: "",
                price: "",
                quantity: "",
                discount: "",
                unitTotal: ""
            },
            product: {
                id: "",
                price: "",
                count: "",
                quantity: "",
                discount: "",
                unitTotal: "",
                validate: false
            }
        };
    },
    methods: {
        discountCheck() {
            var product = this.product;
            var error = this.error;
            if (product.discount === "" || product.discount === null) {
                error.discount = "";
                return false;
            } else {
                if (product.discount > 99 || product.discount < 1) {
                    error.discount = "Enter a value between 1 - 99";
                    return false;
                } else {
                    error.discount = "";
                    return true;
                }
            }
        },
        quantityCheck() {
            var product = this.product;
            var error = this.error;
            if (product.quantity > product.count) {
                error.quantity = "Available Qty Exceeded";
            } else {
                error.quantity = "";
            }
        },
        total() {
            this.quantityCheck();
            var product = this.product;
            if (this.discountCheck()) {
                product.unitTotal =
                    (product.quantity *
                        product.price *
                        (100 - product.discount)) /
                    100;
            } else {
                product.unitTotal = product.quantity * product.price;
            }
            this.SendProduct();
        },
        unitCheck() {
            //form validation;
            var errorcheck = 0;
            var product = this.product;
            var error = this.error;
            var selectedProduct = this.selectedProduct;
            if (selectedProduct == null || selectedProduct == "Select") {
                error.name = "Select Product";
            } else {
                error.name = "";
            }
            if (product.price == null || product.price < 1) {
                error.price = "*Field is required";
                errorcheck++;
            } else {
                error.price = "";
            }

            if (product.count == null || product.count < 1) {
                error.count = "*Field is required";
                errorcheck++;
            } else {
                error.count = "";
            }
            if (product.quantity == null || product.quantity < 1) {
                error.quantity = "*Field is required";
                errorcheck++;
            } else {
                error.quantity = "";
            }
            if (product.unitTotal == null || product.unitTotal < 1) {
                error.unitTotal = "*Field is required";
                errorcheck++;
            } else {
                error.unitTotal = "";
            }

            if (errorcheck > 0) {
                product.validate = false;
            } else {
                product.validate = true;
            }
            this.SendProduct();
        },
        removeRow() {
            this.$emit("deleteRow");
        },
        productSelected() {
            var id = this.selectedProduct;
            if (Number.isInteger(id)) {
                axios.get("/api/getCost/" + id).then(({ data }) => {
                    var product = this.product;
                    var error = this.error;
                    product.id = id;
                    product.name = data.product_name;
                    product.price = data.product_price;
                    product.count = data.product_count;
                    product.warning = data.product_warn;
                    product.quantity = 1;
                    // data.product.supplier;
                    // data.product_count;
                    // data.product_max;
                    // data.product_warn;
                    if (data.product_warn < data.product_count) {
                        error.name = "Low Stock Count";
                    } else if (data.product_count < 1) {
                        error.name = "Out of Stock";
                    } else {
                        error.name = "";
                    }
                    this.quantityCheck();
                    this.total();
                    this.unitCheck();
                    this.SendProduct();
                });
            } else {
            }
        },
        SendProduct() {
            this.$emit("productChanged", this.product);
        },
        getProduct() {
            axios
                .get("/api/product")
                .then(({ data }) => {
                    this.products = data;
                })
                .catch(() => {});
        },
        unitEditedmode() {
            var product = this.product;
            var data = this.salesData;
            this.selectedProduct = data.product_name;
            product.id = data.product_id;
            product.name = data.product_name;
            product.price = data.product_price;
            product.unitTotal = data.product_unitTotal;
            product.discount = data.product_discount;
            product.quantity = data.product_quantity;
            product.count = data.product_count;
            this.total;
            this.SendProduct();
        }
    },
    mounted() {
        this.getProduct();
    },
    created() {
           dataCheck.$on('checkunitData', ()=>{
              this.unitCheck();
           });
            dataCheck.$on('setEditedmode', ()=>{
              this.unitEditedmode();
           });
    }
};
</script>
<style scoped>
.vs__search::placeholder,
.vs__dropdown-toggle {
    background: #dfe5fb;
    border: none;
    width: 100%;
    height: 38px;
    color: #394066;
    text-transform: lowercase;
    font-variant: small-caps;
}
.vs__dropdown-menu {
    background: lightgrey;
    z-index: 1000;
}
.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
    fill: #394066;
    width: 243px;
    border: 1px solid green;
}
.selectProduct {
    background: white !important;
    width: 243px !important;
}
tr {
    margin: 0;
}
td {
    margin: 0;
}
p {
    margin-bottom: 2px;
    margin: 0;
    line-height: 1;
    color: red;
    font-size: 0.7rem;
}
.form-group {
    margin-bottom: 0;
}
</style>
