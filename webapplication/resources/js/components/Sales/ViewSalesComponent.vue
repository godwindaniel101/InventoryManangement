<template>
  <div style="overflow:hidden">
    <div v-show="editMode">
      <!-- <i class="fas fa-backward text-grey">Sales</i> -->
      <!-- <transition-group name="slide" mode="out-in"> -->
      <app-sales-form></app-sales-form>
      <!-- </transition-group> -->
    </div>
    <div class="contentWrapper">
      <div class="card-container" v-show="!editMode">
        <div class="card-container-head">
          <h3 class="card-container-head-text">Sales</h3>

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
        <div class="card-body">
          <div class="fixedCardHeight-table">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Transantion Id</th>
                  <th>Branch</th>
                  <th>Quantity</th>
                  <th>Net</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody v-show="recordExist">
                <tr v-for="sales_record in sales.data" :key="sales_record.id">
                  <td>{{ sales_record.transaction_id }}</td>
                  <td>{{ sales_record.branch ? sales_record.branch.name : 'Old Branch' }}</td>
                  <td>{{ sales_record.total_quantity }}</td>
                  <td>{{ sales_record.net_total }}</td>
                  <td>
                    {{
                    sales_record.status == 1
                    ? "Sold"
                    : "Reversed"
                    }}
                  </td>
                  <td>
                    {{
                    sales_record.created_at | dateChange
                    }}
                  </td>
                  <td>
                    {{
                    sales_record.created_at | timeChange
                    }}
                  </td>
                  <td>
                    <div class="dropdown show">
                      <a
                        class="btn btn-success_p dropdown-toggle"
                        href="#"
                        role="button"
                        id="dropdownMenuLink"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >Action</a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <div
                          class="dropdown-item"
                          aria-labelledby="dropdownMenuLink"
                          @click="getViewed(sales_record.transaction_id)"
                        >
                          <a href="javascript:;">
                            <i class="fas fa-eye text-green"></i>
                            <span>View</span>
                          </a>
                        </div>
                        <div
                          class="dropdown-item"
                          v-show="sales_record.status == 1"
                          @click="getEdited(sales_record.transaction_id)"
                        >
                          <a href="javascript:;">
                            <i class="fas fa-edit text-blue"></i>
                            <span>Edit</span>
                          </a>
                        </div>
                        <div
                          class="dropdown-item"
                          v-show="sales_record.status == 1"
                          @click="reverseSales(sales_record.transaction_id)"
                        >
                          <a href="javascript:;">
                            <i class="fas fa-undo-alt text-yellow"></i>
                            <span>Reverse</span>
                          </a>
                        </div>

                        <div
                          class="dropdown-item"
                          v-show="sales_record.status !==1"
                          @click="recreateSales(sales_record.transaction_id)"
                        >
                          <a href="javascript:;">
                            <i class="fas fa-undo-alt text-yellow"></i>
                            <span>Restore</span>
                          </a>
                        </div>
                        <div
                          class="dropdown-item"
                          @click=" deleteSales( sales_record.transaction_id, sales_record.status)"
                        >
                          <a href="javascript:;">
                            <i class="fas fa-trash text-red"></i>
                            <span>Delete</span>
                          </a>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>

              <!-- <tbody></tbody> -->
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
            <pagination :data="sales" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>

      <div
        class="modal fade"
        id="viewSalesModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header" style="position:relative">
              <table style="width:100%;">
                <tr style="width:100%;">
                  <td style="width:18%;">
                    <tr>Transaction Id</tr>
                    <tr>Branch</tr>
                    <tr>Sold By</tr>
                    <tr>Status</tr>
                    <tr>Sales Date</tr>
                  </td>

                  <td>
                    <tr>
                      {{
                      transactionId
                      }}
                    </tr>
                    <tr>
                      {{
                      branch_name
                      }}
                    </tr>
                    <tr>
                      {{
                      user_name
                      }}
                    </tr>
                    <tr v-if="status == '1'" class="status">Sold</tr>
                    <tr v-else class="not_status">Reverted</tr>
                    <tr>
                      {{
                      dateCreated
                      }}
                    </tr>
                  </td>
                </tr>
              </table>

              <span
                v-if="status == 1"
                class="stamp"
                style="color:green;border:1px solid green;"
              >Sold</span>
              <span v-else class="stamp">Rejected</span>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <table class="table table-hover">
                <tr>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Cost</th>
                  <th>Discount</th>
                  <th>Unit Total</th>
                  <th>Gross</th>
                </tr>
                <tr v-for="data in viewSalesRecord" :key="data.id">
                  <td>{{ data.product.product_name }}</td>
                  <td>{{ data.product_quantity }}</td>
                  <td>{{ data.product_cost }}</td>
                  <td>
                    {{
                    data.product_discount < 1
                    ? "-"
                    : data.product_discount
                    }}
                  </td>
                  <td>{{ data.product_unitTotal }}</td>
                  <td>{{ data.unitGross }}</td>
                </tr>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button
                type="button"
                class="btn btn-primary"
                v-show="status == '1'"
                @click="getEdited(transactionId)"
              >Edit Sales</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import salesForm from "./MakeSalesComponent.vue";
import { dataCheck } from "../../app";
export default {
  components: {
    appSalesForm: salesForm
  },
  data() {
    return {
      transactionId: "",
      branch_name: "",
      dateCreated: "",
      user_name: "",
      status: "",
      viewSalesRecord: [],
      editMode: false,
      sales: {},
      token: "",
      products: {},
      recordExist: false,
      recordContent: " . . . Processing",
      form: new Form({
        id: " ",
        product_quantity: " ",
        product_cost: " ",
        product_id: " ",
        unitTotal: " ",
        created_at: " "
      })
    };
  },
  methods: {
    getViewed(id) {
      axios
        .get("/api/getTotalSalesRecords/" + id)
        .then(data => {
          this.viewSalesRecord = data.data["sales"];
          this.transactionId = data.data["salesDetail"]["transaction_id"];
          this.status = data.data["salesDetail"]["status"];
          this.branch_name = data.data["salesDetail"]["branch"]["name"];
          this.user_name = data.data["salesDetail"]["user"]["name"];
          this.dateCreated = data.data["salesDetail"]["created_at"];
          // console.log(data.data['salesDetail']['status'])
          $("#viewSalesModal").modal("show");
        })
        .catch(() => {});
    },

    reverseSales(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "Reverting this sales Will Return Goods to store!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Reverse it!"
      }).then(result => {
        if (result.value) {
          axios
            .post("/api/reverseSales/" + id)
            .then(({ data }) => {
              // console.log(data);
              if (data.success) {
                Toast.fire({
                  icon: "success",
                  title: data.message
                });
              } else {
                Toast.fire({
                  icon: "error",
                  title: data.message
                });
              }
              // console.log(data);
              this.getSales();
            })
            .catch(() => {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!",
                footer: "<a href>Why do I have this issue?</a>"
              });
              this.getSales();
            });
        }
      });
    },
    recreateSales(id) {
      Swal.fire({
        title: "Changed Mind?",
        text: "This  Will Sales Reverted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Recreate it!"
      }).then(result => {
        if (result.value) {
          axios
            .post("/api/restoreSales/" + id)
            .then(({ data }) => {
              if (data.success == true) {
                Toast.fire({
                  icon: "success",
                  title: data.message
                });
              } else {
                Toast.fire({
                  icon: "error",
                  title: data.message
                });
              }
              console.log(data.message);
              // console.log(data);
              this.getSales();
            })
            .catch(() => {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!",
                footer: "<a href>Why do I have this issue?</a>"
              });
            });
        }
      });
    },
    deleteSales(id, status) {
      Swal.fire({
        title: "Are you sure?",
        text: "Deleting this sales Will Offset Your Record!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          if (status !== 1) {
            axios
              .delete("/api/deleteSales/" + id)
              .then(data => {
                this.getSales();
                Toast.fire({
                  icon: "success",
                  title: "Sales Deleted"
                });
              })
              .catch(() => {});
          } else {
            Swal.fire({
              title: "Note!?",
              text: "Do you wish to Reverse Sales before Delete?",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: " Yes, Revert Sales !",
              cancelButtonText: "No, Just Delete!"
            }).then(result => {
              console.log(result);
              if (result.value) {
                axios
                  .delete("/api/deleteReverseSales/" + id)
                  .then(data => {
                    this.getSales();
                    Toast.fire({
                      icon: "success",
                      title: "Sales Deleted"
                    });
                  })
                  .catch(() => {});
              } else if (result.dismiss == "cancel") {
                axios
                  .delete("/api/deleteSales/" + id)
                  .then(data => {
                    this.getSales();
                    Toast.fire({
                      icon: "success",
                      title: "Sales Deleted"
                    });
                  })
                  .catch(() => {});
              } else {
              }
            });
          }
        }
      });
    },

    getEdited(id) {
      this.editMode = true;
      dataCheck.$emit("setEditMode", id);
      $("#viewSalesModal").modal("hide");
    },
    getResults(page = 1) {
      this.recordExist = false;
      // this.$Progress.start();
      axios
        .get("/api/salesRecord?page=" + page, {
          headers: {
            authorization: "Bearer " + this.token
          }
        })
        .then(response => {
          this.sales = response.data;

          this.recordExist = true;
          // this.$Progress.finish();
        })
        .catch(() => {
          this.recordExist = false;
          this.recordContent =
            ". . .Error Getting Record, Login again and try...";
        });
    },
    getSales() {
      axios
        .get("/api/salesRecord", {
          headers: {
            authorization: "Bearer " + this.token
          }
        })
        .then(({ data }) => {
          this.sales = data;
          this.recordExist = true;
        })
        .catch(({}) => {
          this.recordExist = false;
          this.recordContent =
            ". . .Error Getting Record, Login again and try...";
        });
    },
    getToken() {
      this.token = localStorage.getItem("access_token");
    }
  },
  mounted() {
    this.getToken();
    this.getSales();
    this.editMode = false;
  },
  created() {
    dataCheck.$on("resetEdit", () => {
      this.getSales();
      this.editMode = false;
    });
    this.editMode = false;
  }
};
</script>
<style scoped>
.stamp {
  position: absolute;
  top: 70px;
  right: 68px;
  border-style: double;
  padding: 0px 11px;
  color: red;
  border: 1px double red;
  z-index: 3000;
  font-size: 5px;
  transform-origin: 50% 50%;
  transform: rotate(20deg) scale(4);
}
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
