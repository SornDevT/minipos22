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
            <p class="mb-6">ກະລຸນາປ້ອນຂໍ້ມູນລົງມະຽນ</p>

            
              <div class="mb-2 form-control-validation fv-plugins-icon-container">
                <label  class="form-label">ຊື່ຜູ້ໃຊ້:</label>
                <input type="text" v-model="user_name" class="form-control"  placeholder="...." >
                <!-- UserName: {{ user_name }} <br> -->
                <label  class="form-label">ອີເມວລ໌:</label>
                <input type="email" v-model="email" class="form-control" placeholder="...." >
                
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
              <div class="m2-6 mb-1 form-password-toggle form-control-validation fv-plugins-icon-container">
                <label class="form-label" >ລະຫັດຜ່ານ:</label>
                <div class="input-group input-group-merge has-validation">
                  <input type="password"  class="form-control" v-model="password" placeholder="············" aria-describedby="password">
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                
              </div>
              <div class="m2-6 mb-6 form-password-toggle form-control-validation fv-plugins-icon-container">
                <label class="form-label" >ຍືນຍັນລະຫັດຜ່ານ:</label>
                <div class="input-group input-group-merge has-validation">
                  <input type="password" class="form-control" v-model="password_confirmation"  placeholder="············" aria-describedby="password">
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                
              </div>
              <div class="alert alert-warning" role="alert" v-if="message_error">
               {{message_error}}
              </div>
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" @click="Register" type="submit">ລົງທະບຽນ</button>
              </div>
              <p class="text-center">
              <span>ມີ User ຜູ້ໃຊ້ແລ້ວ!</span>
              <router-link to="/login" > 
                <span>ເຂົ້າສູ່ລະບົບ</span>
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
    data(){
        return{
            user_name:'',
            email:'',
            password:'',
            password_confirmation:'',
            message_error:'',
        }
    },
    methods:{
        Register(){
            // console.log('press register button! 4564564564')
            if(this.user_name == '' || this.email == '' || this.password == '' || this.password_confirmation == ''){
                this.message_error = 'ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ!';
            } else {
                if(this.password != this.password_confirmation){
                    this.message_error = 'ລະຫັດຜ່ານບໍ່ກົງກັນ!';
                } else {
                    this.message_error = '';
                    // console.log('ສະບາຍດີຜູ້ໃຊ້ທີ່ລົງທະບຽນ');
                    axios.post('api/register',{
                        user_name:this.user_name,
                        email:this.email,
                        password:this.password
                    }).then((res)=>{

                        console.log(res.data);

                        if(res.data.success){
                            this.$router.push('/login');
                        } else {
                            this.message_error = res.data.message;
                        }

                    }).catch((err)=>{
                        console.log(err);
                    });

                }
            }
        }
    }
}
</script>
<style lang="">
    
</style>