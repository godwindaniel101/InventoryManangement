<template>
  <tbody>
    <tr>
      <td>
        <div class="form-group">
          <v-select
            class="selectProduct"
            label="product_name"
            :value="selectedProduct"
            :options="productsa"
            :reduce="product_name => product_name.product_id"
            @input="productSelected()"
            v-model="selectedProduct"
          ></v-select>
        </div>
      </td>
      <td>
        <div class="form-group">
          <input type="text" v-model="product.count" class="form-control" disabled />
        </div>
      </td>
      <td>
        <div class="form-group">
          <input type="text" v-model="product.cost" class="form-control" @keyup="total" />
        </div>
      </td>
      <td>
        <div class="form-group">
          <input type="text" v-model="product.quantity" class="form-control" @keyup="total" />
        </div>
      </td>
      <td>
        <div class="form-group">
          <input type="text" v-model="product.discount" class="form-control" @keyup="total" />
        </div>
      </td>
      <td>
        <div class="form-group">
          <input type="text" v-model="product.unitTotal" class="form-control" disabled />
        </div>
      </td>
      <td class="sales_trash">
        <i class="fas fa-trash sales_trash-icon text-red" @click="removeRow()"></i>
      </td>
    </tr>
    <tr>
      <td>
        <p v-text="error.name"></p>
      </td>
      <td>
        <p v-text="error.count"></p>
      </td>
      <td>
        <p v-text="error.cost"></p>
      </td>
      <td>
        <p v-text="error.quantity"></p>
      </td>
      <td>
        <p v-text="error.discount"></p>
      </td>
      <td>
        <p v-text="error.unitTotal"></p>
      </td>
    </tr>
  </tbody>
</template>

<script>
import { dataCheck } from "../../app";
export default {
  props: {
    salesData: {
      type: Object,
      require: true
    },
    myproducts: {
      type: Array,
      require: true
    },
    mybranch: {
      type: [Number, String],
      require: true
    }
  },

  data() {
    return {
      // products: [],
      productsa: this.myproducts,
      branch_id: this.mybranch,
      editData: this.salesData,
      selectedProduct: "Select",
      branchId: 1,
      error: {
        name: "",
        count: "",
        cost: "",
        quantity: "",
        discount: "",
        unitTotal: ""
      },
      product: {
        id: "",
        cost: "",
        count: "",
        quantity: "",
        discount: "",
        unitTotal: "",
        stock_id: "",
        stock_id:"",
        validate: false
      },
      token: ""
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
          (product.quantity * product.cost * (100 - product.discount)) / 100;
      } else {
        product.unitTotal = product.quantity * product.cost;
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
      if (product.cost == null) {
        error.cost = "*Field is required";
        errorcheck++;
      } else if (product.cost < 1) {
        error.cost = "*Sorry No Free Sales";
        errorcheck++;
      } else {
        error.cost = "";
      }

      if (product.count == null) {
        error.count = "*Field is required";
        errorcheck++;
      } else if (product.count < 1) {
        error.count = "*Count cant be zero";
        errorcheck++;
      } else {
        error.count = "";
      }
      if (product.quantity == null) {
        error.quantity = "*Field is required";
        errorcheck++;
      } else if (product.quantity < 1) {
        error.quantity = "*Qty cant be zero";
        errorcheck++;
      } else {
        error.quantity = "";
      }
      if (product.unitTotal == null || product.unitTotal < 1) {
        error.unitTotal = "*Field is required";
        errorcheck++;
      } else if (product.cost < 1) {
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
      console.log(this.productsa);
      var id = this.selectedProduct;
      var branch_id = this.branch_id;

      if (id) {
        axios
          .get("/api/getCost/" + id + "/branch/" + branch_id)
          .then(({ data }) => {
            var product = this.product;
            var error = this.error;
            product.id = id;
            product.stock_id = data.id;
            product.name = data.product_name;
            product.cost = data.product_cost;
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
        .get("/api/product/", {
          headers: {
            authorization: "Bearer " + this.token
          }
        })
        .then(({ data }) => {
          // console.log(data);
          this.products = data;
        })
        .catch(() => {});
    },
    getBranchProduct() {
      axios
        .get("/api/getBranchProduct/" + this.branchId, {
          headers: {
            authorization: "Bearer " + this.token
          }
        })
        .then(({ data }) => {
          // console.log(data);
          this.products = data;
        })
        .catch(() => {});
    },
    unitEditedmode() {
      var product = this.product;
      var data = this.salesData;
      console.log(data);
      this.selectedProduct = data.product_id;
      product.id = data.product_id;
      product.name = data.product_name;
      product.cost = data.product_cost;
      product.unitTotal = data.product_unitTotal;
      product.discount = data.product_discount;
      product.quantity = data.product_quantity;
      product.stock_id = data.product_stock_id;
      product.count = data.product_count;
      this.total;
      this.SendProduct();
    },
    getToken() {
      this.token = localStorage.getItem("access_token");
    }
  },
  mounted() {
    this.getToken();
    this.getBranchProduct();
  },
  created() {
    dataCheck.$on("checkunitData", () => {
      this.unitCheck();
    });
    dataCheck.$on("setEditedmode", () => {
      this.unitEditedmode();
    });
    dataCheck.$on("branchData", data => {
      this.products = data;
      // console.log(this.products);
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
