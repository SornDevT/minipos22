<template lang="">
    
  <!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">
  <div class="layout-container">

    
    




<!-- Menu -->

    <SideMenu  v-if="store.getToken"/>

<!-- / Menu -->

    

    <!-- Layout container -->
    <div class="layout-page">
      
      



<!-- Navbar -->




<nav v-if="store.getToken" class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
  

  

  

      
      

      
      
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0   d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
          <i class="bx bx-menu bx-md"></i>
        </a>
      </div>
      

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        

        


        
        <!-- Search -->
        <div class="navbar-nav align-items-center">
          <div class="nav-item d-flex align-items-center">
            <i class="bx bx-search bx-md"></i>
            <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..." aria-label="Search...">
          </div>
        </div>
        <!-- /Search -->
        

        <ul class="navbar-nav flex-row align-items-center ms-auto">
          

          
          <!-- Place this tag where you want the button to render. -->
          <li class="nav-item lh-1 me-4">
            <a class="github-button" href="https://github.com/themeselection/sneat-html-admin-template-free" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themeselection/sneat-html-admin-template-free on GitHub">Star</a>
          </li>
          


          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img :src="url + 'assets/img/avatars/1.png'" alt="" class="w-px-40 h-auto rounded-circle">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="#">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar avatar-online">
                        <img :src="url+'assets/img/avatars/1.png'" alt="" class="w-px-40 h-auto rounded-circle">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-0">{{user.name}}</h6>
                      <small class="text-muted">{{user.permission}}</small>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider my-1"></div>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <i class="bx bx-cog bx-md me-3"></i><span>Settings</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#">
                  <span class="d-flex align-items-center align-middle">
                    <i class="flex-shrink-0 bx bx-credit-card bx-md me-3"></i><span class="flex-grow-1 align-middle">Billing Plan</span>
                    <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                  </span>
                </a>
              </li>
              <li>
                <div class="dropdown-divider my-1"></div>
              </li>
              <li>
                <a class="dropdown-item" @click="Logout()" href="javascript:void(0);">
                  <i class="bx bx-power-off bx-md me-3"></i><span>ອອກຈາກລະບົບ</span>
                </a>
              </li>
            </ul>
          </li>
          <!--/ User -->
          

        </ul>
      </div>

      
      
  </nav>
  

  
<!-- / Navbar -->

      

      <!-- Content wrapper -->
      <div class="content-wrapper">

        <!-- Content -->
        
          <div class="container-xxl flex-grow-1 container-p-y">
          
            <router-view></router-view>

          </div>
          <!-- / Content -->

          
          

<!-- Footer -->
<footer class="content-footer footer bg-footer-theme" v-if="store.getToken">
  
  <!-- {{ $router.currentRoute }} -->
    <!-- {{ store.getToken }} -->
  <div class="container-xxl">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body">
        © , made with ❤️ by <a href="https://themeselection.com" target="_blank" class="footer-link">ThemeSelection</a>
      </div>
      <div class="d-none d-lg-inline-block">
        
        <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
        <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>
        
        <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>
        
        <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank" class="footer-link">Support</a>
        
        
      </div>
    </div>
  </div>
</footer>
<!-- / Footer -->

          
          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    
    
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    
    
  </div>
  <!-- / Layout wrapper -->

  
</template>
<script>
import { useStore } from './Store/Auth';
import axios from 'axios';
export default {
  setup(){
    const store = useStore();
    return {
      store
    }
  },
    data() {
        return {
            url: window.location.href,
            user:{
              name:'',
              permission:''
            }
        }
    },
    methods: {
      // test(){
      //     const user = JSON.parse(this.store.getUser);
      //     // console.log( typeof this.store.getUser)
      //     console.log(user)
      //     console.log(user.name)
      // },  
      Logout(){
          axios.get('api/logout',{ headers: { Authorization: 'Bearer ' + this.store.getToken } }).then((res)=>{
           
              if(res.data.success){
                // clear local storage
                localStorage.removeItem('web_token');
                localStorage.removeItem('web_user');

                // clear store
                this.store.logout();

                // got to login page
                this.$router.push('/login');
              }
          }).catch((err)=>{
              console.log(err);
          });
      }
    },
    created(){
      this.user = JSON.parse(localStorage.getItem("web_user"));
      // console.log(this.user.name)
    },
    watch:{
      '$route'(){
        if(localStorage.getItem("web_user")){
           this.user = JSON.parse(localStorage.getItem("web_user"));
        }
       
      }
    }

}
</script>
<style lang="">
    
</style>