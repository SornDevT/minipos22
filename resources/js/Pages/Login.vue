<template lang="">
  <div class="row d-flex justify-content-center">
    <div class="authentication-wrapper authentication-basic container-p-y col-md-4">
      <div class="authentication-inner">
        <!-- Login -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="index.html" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
              <span class="text-primary">

                
              </span>
              </span>
                <span class="app-brand-text demo text-heading fw-bold">Mini Pos 22</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1">ສະບາຍດີ! 👋</h4>
            <p class="mb-6">ກະລຸນາເຂົ້່ສູ່ລະບົບ</p>

            
              <div class="mb-6 form-control-validation fv-plugins-icon-container">
                <label for="email" class="form-label">ອີເມວລ໌:</label>
                <input type="text" v-model="email" class="form-control" id="email" name="email-username" placeholder="...." >
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="mb-6 form-password-toggle form-control-validation fv-plugins-icon-container">
                <label class="form-label" for="password">ລະຫັດຜ່ານ:</label>
                <div class="input-group input-group-merge has-validation">
                  <input type="password" v-model="password" id="password" class="form-control" @keyup.enter="Login()" name="password" placeholder="············" aria-describedby="password">
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              </div>
              <div class="mb-7">
                <div class="d-flex justify-content-between">
                  <div class="form-check mb-0"> 
                    <input class="form-check-input" type="checkbox" id="remember-me" v-model="remember_me">
                    <label class="form-check-label" for="remember-me"> ຈື່ຂ້ອຍໄວ້ໃນລະບົບ </label>
                  </div>
                  <span></span>
                </div>
              </div>
              <div class="alert alert-warning" role="alert" v-if="message_email_error || message_password_error">
               {{message_email_error}} {{message_password_error}}
              </div>
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" :disabled="CheckForm" @click="Login()" type="submit">ເຂົ້າສູ່ລະບົບ</button>
              </div>
         

            <p class="text-center">
              <span>ບໍ່ມີ User ຜູ້ໃຊ້?</span>
              <router-link to="/register" > 
                <span>ກະລຸນາລົງທະບຽນ</span>
              </router-link>
            </p>

            
          </div>
        </div>
        <!-- /Login -->
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
export default {
    data() {
      return {
        email: '',
        password: '',
        message_email_error: '',
        message_password_error: '',
        remember_me: false  
      }
    },
    computed:{
      CheckForm(){

        // Check Email Format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(this.email){
          if(emailRegex.test(this.email)){
            this.message_email_error = '';
          }else{
            this.message_email_error = 'ອີເມວລ໌ບໍ່ຖືກຕ້ອງ!';
          }
        } else {
          this.message_email_error = '';
        }

        // ຖ້າມີລະຫັດຜ່ານ, ກວດລະຫັດຜ່ານ ຕ້ອງຫຼາຍກ່ວາ 6 ແລະ ບໍ່ມີຊື່ວ່າງ
        if(this.password){
          if(this.password.length < 6){
            this.message_password_error = 'ລະຫັດຜ່ານຕ້ອງຫຼາຍກ່ວາ 6 ຕົວຂຶ້ນ!';
          }else{
            this.message_password_error = '';
          }
        } else {
          this.message_password_error = '';
        }
        

        if(!emailRegex.test(this.email) || this.email == '' || this.password == '' || this.password.length < 6){
          return true;
        } else {
          return false;
        }
      }
    },
    methods:{
      Login(){

        axios.post('api/login',{
          email:this.email,
          password:this.password,
          remember_me:this.remember_me
        }).then((res)=>{

          console.log(res.data);

          if(res.data.success){

            // Save Token to LocalStorage
            localStorage.setItem('web_token',res.data.token);
            localStorage.setItem('web_user',JSON.stringify(res.data.user));

            // clear from
            this.email = '';
            this.password = '';

            // go to root page
            this.$router.push('/');

          } else {
            this.message_email_error = res.data.message;
          }

        }).catch((err)=>{
          console.log(err);
        });

      }
    }
}
</script>
<style lang="">
    
</style>