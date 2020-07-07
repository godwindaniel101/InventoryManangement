<template>
  <div class="mainContainer">
    <div class="containerWrapper">
       
      <div
        class="ImageContainer"
        style="background-image:url('image/inventory4.png'); background-size:cover;background-position:top center;"
      ></div>
    
      <div class="formContainer">
          <div class="non-Scroll">
        <div class="formHeader">
          <h3>Vance Inventory</h3>
          <p>Your Business is our priority</p>
        </div>
        <div class="formContent">
          <form class="formInput" v-show="!loginMode">
            <div class="form-group-head">
              <p>{{loginMode ? 'Login' : 'Register'}}</p>
            </div>
            <div class="form-group">
              <label>User Name</label>
              <input
                v-model="form.username"
                type="text"
                name="username"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('username') }"
              />
              <has-error :form="form" field="username"></has-error>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input
                v-model="form.email"
                type="text"
                name="email"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('email') }"
              />
              <has-error :form="form" field="email"></has-error>
            </div>
            <div class="form-group">
              <label>Password</label>
              <input
                v-model="form.password"
                type="password"
                name="password"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('password') }"
              />
              <has-error :form="form" field="password"></has-error>
            </div>
            <div class="form-group">
              <label>Confirm Password</label>
              <input
                v-model="form.confirm_password"
                type="password"
                name="confirm_password"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('confirm_password') }"
              />
              <has-error :form="form" field="confirm_password"></has-error>
            </div>
            <div class="form-group-link">
              <div class="btn" @click="registerUser()">Register</div>
            </div>
            <div class="notice">
                <p>Already have an account ? <span @click="loginMode = true">Login</span></p>
            </div>
          </form>

          <form class="formInput" v-show="loginMode">
            <div class="form-group-head">
              <p>{{loginMode ? 'Login' : 'Register'}}</p>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input
                v-model="form.email"
                type="text"
                name="email"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('email') }"
              />
              <has-error :form="form" field="email"></has-error>
            </div>
            <div class="form-group">
              <label>Password</label>
              <input
                v-model="form.password"
                type="password"
                name="password"
                class="form-control"
                :class="{ 'is-invalid': form.errors.has('password') }"
              />
              <has-error :form="form" field="password"></has-error>
            </div>
            <div class="form-group-checkbox">
                     <input type="checkbox" v-model="checkbox">
                     <p>Remember me </p>
            </div>
            <div class="form-group-link">
              <div class="btn" @click="loginUser()">Login</div>
            </div>
              
            <div class="notice">
                <p>Don't have an account ? <span @click="loginMode = false">Register</span></p>
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
// Vue.component(HasError.name, HasError)
// Vue.component(AlertError.name, AlertError)
export default {
  data() {
    return {
        checkbox:true,
        loginMode:true,
      form: new Form({
        username: "",
        email: "",
        password: "",
        confirm_password: "",
        token: ""
      })
      // tokenForm: new Form({
      //         token:'',
      // }),
    };
  },
  methods: {
    loginUser(){
        this.$Progress.start();
      this.form
        .post("api/login", this.form)
        .then(({ data }) => {
          console.log(data);
          if (data) {
            if(this.checkbox){
                localStorage.setItem("access_token", data.token);
            }else{
                sessionStorage.setItem("access_token", data.token);
            }
            
            return this.$router.push("/dashboard");
            this.form.clear();
            this.$Progress.finish();
          }
        })
        .catch(data => {
          console.log(data);
          this.$Progress.fail();
        });
    },
    registerUser() {
    this.$Progress.start();
      this.form
        .post("api/register", this.form)
        .then(({ data }) => {
          console.log(data);
          if (data.success) {
            localStorage.setItem("access_token", data.token);
            return this.$router.push("/dashboard");
            this.form.clear();
            this.$Progress.finish();
          }
        })
        .catch(data => {
          console.log(data);
          this.$Progress.fail();
        });
    },
    checkUser() {
      this.$Progress.start();
      var token = localStorage.getItem("access_token");
      if (token != null) {
        axios
          .get("api/checkuser", {
            headers: {
              authorization: "Bearer " + token
            }
          })
          .then(response => {
            if(response){
                return this.$router.push("/dashboard");
            }
          }).catch((response)=>{
              console.log(response);
          });
      }else{
          return this.$router.push("/");
          this.$Progress.fail();
      }
      this.$Progress.finish();
    },
    tryLogin() {
      return this.$router.push("dashboard");
    }
  },
  mounted() {
    this.checkUser();
  }
};
</script>
