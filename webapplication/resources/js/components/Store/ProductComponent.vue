<template>
  <div class="contentWrapper">
    <div class="card-container">
      <!-- <button @click="clickca()">Update</button> -->
      <div class="card-container-head">
        <h3 class="card-container-head-text">Products</h3>

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
            <th>Product</th>
            <th>Cost</th>
            <th>Price</th>
            <th>Supplier</th>
            <!-- <th>Maximum Qty</th>
            <th>Warning Qty</th>
            <th>Current Qty</th> -->
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product,index) in products" :key="products.id">
            <td>{{index + 1}}</td>
            <td>{{product.product_name | textTransform}}</td>
            <td>{{product.product_cost}}</td>
            <td>{{product.product_price}}</td>
            <td>{{product.product_supplier}}</td>
            <!-- <td>{{product.product_max}}</td>
            <td>{{product.product_warn}}</td>
            <td>{{product.product_count}}</td> -->
            <td>{{product.created_at | dateChange}}</td>
            <td>
              <div class="action-wrapper">
                <a class="action-edit" href="javascript:;" @click="edit(product.id)">
                  <i class="fas fa-edit"></i>
                </a>
                <a class="action-delete" href="javascript:;" @click="deleteProduct(product.id)">
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
    <div class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{editMode ? 'Edit Product' : 'Create Product'}}</h5>
            <button type="button" class="close" @click="clearfield" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? update() : create()" @keydown="form.onKeydown($event)">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <input
                      v-model="form.product_name"
                      type="text"
                      name="product_name"
                      placeholder="Product Name"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('product_name') }"
                    />
                    <has-error :form="form" field="product_name"></has-error>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <input
                      v-model="form.product_cost"
                      type="number"
                      name="product_cost"
                      placeholder="Product Cost"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('product_cost') }"
                    />
                    <has-error :form="form" field="product_cost"></has-error>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <input
                      v-model="form.product_price"
                      type="number"
                      name="product_price"
                      placeholder="Product Price"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('product_price') }"
                    />
                    <has-error :form="form" field="product_price"></has-error>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <input
                      v-model="form.product_supplier"
                      type="text"
                      name="product_supplier"
                      placeholder="Supplier"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('product_supplier') }"
                    />
                    <has-error :form="form" field="product_supplier"></has-error>
                  </div>
                </div>
                <!-- <div class="col-md-12">
                  <div class="form-group">
                    <input
                      v-model="form.product_warn"
                      type="number"
                      name="product_warn"
                      placeholder="Product Warning Quantity"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('product_warn') }"
                    />
                    <has-error :form="form" field="product_warn"></has-error>
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
                      :class="{ 'is-invalid': form.errors.has('product_max') }"
                    />
                    <has-error :form="form" field="product_max"></has-error>
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
                      :class="{ 'is-invalid': form.errors.has('product_count') }"
                    />
                    <has-error :form="form" field="product_count"></has-error>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn-secondary" @click="clearfield">Close</button>
              <button
                :disabled="form.busy"
                v-show="!editMode"
                type="submit"
                class="btn-primary"
              >Create</button>
              <button
                :disabled="form.busy"
                v-show="editMode"
                type="submit"
                class="btn-primary"
              >Update</button>
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
        product_name: "",
        product_cost: "",
        product_price: "",
        // product_count: "",
        product_supplier: "",
        // product_max: "",
        // product_warn: ""
      }),
      token:'',
      products: {},
      editMode: false,
      recordExist:false,
      recordContent:"...Loading Data"
    };
  },
  methods: {
    clickca(){
      this.$store.state.counter++
    },
    getproduct() {
       this.$Progress.start();
      this.form.submit("get","/api/product", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
         }).then(({ data }) => {
            if (data.length > 0) {
                            this.recordContent = "";
                            this.recordExist = true;
                           this.products = data;
                            this.$Progress.finish();
                        } else {
                            this.recordContent = "No Record Found";
                            this.recordExist = false;
                             this.$Progress.fail();
                        }
        
        // console.log(data);
      }).catch(()=>{
         this.$Progress.fail();
                    this.recordContent =
                        ". . .Error Getting Record, Login again and try...";
                    this.recordExist = false;
      });
    },
    create() {
      this.$Progress.start();
      this.form
        .submit('post',"/api/product" , {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
         })
        .then(({ data }) => {
          this.$Progress.finish();
          Toast.fire({
            icon: "success",
            title: "Product Created"
          });
          this.getproduct();
          $(".modal").modal("hide");
          this.form.reset();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    edit(id) {
      this.editMode = true;
      this.form
        .get("/api/product/" + id + "/edit")
        .then(({ data }) => {
          console.log(data);
          this.form.fill(data);
          $(".modal").modal("show");
          // this.editMode = false;
        })
        .catch(() => {});
    },
    update() {
      var id = this.form.id;
      this.$Progress.start();
      this.form
        .put("/api/product/" + id)
        .then(({ data }) => {
          console.log(data);
          Toast.fire({
            icon: "success",
            title: "Product Updated"
          });
          $(".modal").modal("hide");
          this.editMode = false;
          this.getproduct();
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
          axios.delete("/api/product/" + id).then(({ data }) => {
            console.log(data);
          });
          Swal.fire("Deleted!", "Your file has been deleted.", "success");
          this.getproduct();
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
    this.getproduct();
    this.getToken();
  }
};
</script>
