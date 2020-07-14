<template>
    <div class="contentWrapper">
        <div class="card-container" style="height:400px;">
            <div class="card-container-head">
                <h3 class="card-container-head-text">Default Settings</h3>
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
                <div class="defeault-branch">
                    <div>
                        <h5>Default Branch</h5>
                    </div>
                    <div class="defeault-branch-a">
                        <div class="defeault-branch-b">
                            <div
                                class="editDefaultContainer"
                                v-show="!showformm"
                            >
                                <div class="editDefault" @click="getBranch()">
                                    <p v-text="currentBranch"></p>
                                </div>
                            </div>
                            <div v-show="showformm">
                                <v-select
                                    :options="branches"
                                    :reduce="name => name.id"
                                    @input="updateDefaultBranch()"
                                    v-model="branch_id"
                                    :value="branch_id"
                                    class="header-form-control"
                                    label="name"
                                ></v-select>
                            </div>
                        </div>

                        <div class="defaultIcon">
                            <preloader
                                :status="loadingStatus"
                                :showtexted="loadingText"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Preloader from "../Authentication/PreloaderComponent.vue";
export default {
    components: {
        preloader: Preloader
    },
    data() {
        return {
            loadingStatus: "loading",
            loadingText: false,
            currentBranch: "",
            token: "",
            branches: [],
            showformm: false,
            editMode: true,
            branch_id: "Select",
            form: new Form({})
        };
    },
    methods: {
        getBranch() {
            this.loadingStatus = "loading";
            axios
                .get("/api/branch/getBranch", {
                    headers: {
                        authorization: "Bearer " + this.token
                    }
                })
                .then(response => {
                    if (response.data.length > 0) {
                        this.branches = response.data;
                        this.loadingStatus = "";
                        this.showformm = true;
                    } else {
                    }
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
                    console.log(data);
                    this.branch_id = parseInt(data.branch_id);
                    this.currentBranch = data.branch_name;
                    this.loadingStatus = " ";
                })
                .catch(() => {});
            return true;
        },
        getToken() {
            this.token = localStorage.getItem("access_token");
        },
        updateDefaultBranch() {
            this.loadingStatus = "loading";
            this.showformm = false;
            this.form
                .submit(
                    "post",
                    "/api/branch/updateDefaultBranch/" + this.branch_id,
                    {
                        headers: {
                            authorization: "Bearer " + this.token
                        }
                    }
                )
                .then(data => {
                    if (this.getUserCurrentBranch()) {
                        this.loadingStatus = "good";
                    } else {
                        this.loadingStatus = "error";
                    }
                })
                .catch(() => {});
        },
        getToken() {
            this.token = localStorage.getItem("access_token");
        }
    },
    mounted() {
        this.getToken();
        this.getUserCurrentBranch();
    }
};
</script>
