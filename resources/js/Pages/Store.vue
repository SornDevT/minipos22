<template lang="">
    <div class="card">
  <h5 class="card-header">ລາຍການ ສະຕ໋ອກສິນຄ້າ</h5>
  <div class="card-body">

    <div class="row" v-if="ShowForm">
        <div class="col-lg-12 text-end">
            <button type="button" class="btn btn-success me-2" :disabled="CheckForm" @click="SaveProduct()" >ບັນທຶກ</button>
            <button type="button" @click="CloseForm()" class="btn btn-danger">ຍົກເລີກ</button>
        </div>
        <div class="col-lg-4 d-flex justify-content-center align-items-center" style="position: relative;">
            
            <button type="button" class="btn rounded-pill btn-icon btn-danger boximg" v-if="FormProduct.image" @click="RemoveImg()">
                <i class='bx bx-x fs-4'></i>
              </button>

            <img :src="ImagePreview" @click="$refs.img_product.click()" class="img-fluid cursor-pointer" alt="..." style="max-height: 200px;">

            <input type="file" ref="img_product" style="display: none;" @change="onSeclect($event)" >
        </div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6"> {{ FormProduct }}
                    <div class="mb-3">
                        <label for="ProductName" class="form-label">ຊື່ສິນຄ້າ:</label>
                        <input type="text" class="form-control" id="ProductName" v-model="FormProduct.name" placeholder="...">
                    </div>
                </div>
                <div class="col-lg-6">
                    
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="Qty" class="form-label">ຈຳນວນ:</label>
                        <cleave :options="options"  v-model="FormProduct.qty" class="form-control" id="Qty" placeholder="..." />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="Category" class="form-label">ໝວດໝູ່:</label>
                        <select class="form-select" v-model="FormProduct.category_id" id="Category">
                            <option :value="cat.id" v-for="cat in CategoryData" :key="cat.id"> {{ cat.name }} </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="Price" class="form-label">ລາຄາຊື້:</label>
                    <div class="input-group mb-3">
                        <cleave :options="options"  v-model="FormProduct.price_buy" class="form-control text-end" id="Price" placeholder="..." />
                    <span class="input-group-text">ກີບ</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="Price" class="form-label">ລາຄາຂາຍ:</label>
                    <div class="input-group mb-3">
                        <cleave :options="options"  v-model="FormProduct.price_sell" class="form-control text-end" id="Price" placeholder="..." />
                    <span class="input-group-text">ກີບ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

    <div v-else>

   
    <div class="row" >
        <div class="col-lg-6 mb-2 d-flex align-items-center"> 
            <!-- {{Sort}} -->
            <div class="me-2 cursor-pointer" @click="Sort=='asc'?Sort='desc':Sort='asc'"> 
                <i class='bx bx-sort-up fs-4' v-if="Sort=='asc'"></i>
                <i class='bx bx-sort-down fs-4' v-if="Sort=='desc'"></i>
            </div> 
            <!-- {{PerPage}} -->
            <select v-model="PerPage" class="form-select w-auto me-2" >
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
            </select>
            <select v-model="CatShow" class="form-select w-auto me-2" >
                <option value="all">ທັງໝົດ</option>
                <option :value="cat.id" v-for="cat in CategoryData" :key="cat.id"> {{ cat.name }} </option>
                 
            </select>
        </div>
        <div class="col-lg-6 mb-2 ">
            <div class="d-flex justify-content-end">

            <div class=" d-flex">
                <div class="input-group me-2 ">
                    <input v-model="Search" type="text" class="form-control" @keyup.enter="GetProduct()" placeholder="ຄົ້ນຫາ..." >
                    <button class="btn btn-primary px-2 " type="button" @click="GetProduct()"> 
                        <i class='bx bx-search-alt fs-5'></i>
                    </button>
                </div>
            </div>
            <button type="button" @click="AddProduct()" class="btn btn-info">ເພີ່ມຂໍ້ມູນ</button>
        </div>
        </div>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>ຮູບພາບ</th>
            <th>ຊື່ສິນຄ້າ</th>
            <th>ໝວດໝູ່</th>
            <th>ຈຳນວນ</th>
            <th>ລາຄາຊື້</th>
            <th>ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in ProductData.data" :key="item.id">
            <td>{{ item.id }}</td>
            <td>{{ item.image }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.category_name }}</td>
            <td class="text-center">{{ formatPrice(item.qty) }}</td>
            <td class="text-end">{{ formatPrice(item.price_buy) }}</td>
            <td class="text-center">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu" style="">
                  <a class="dropdown-item" href="javascript:void(0);" @click="EditProduct(item.id)"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="DelProduct(item.id)"><i class="bx bx-trash me-1"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
         
        </tbody>
      </table>

      <Pagination :pagination="ProductData" :offset="4" @paginate="GetProduct($event)" />

      <button @click="showAlert">Hello world</button>

    </div>

</div>

  </div>
</div>
</template>
<script>
import axios from 'axios';
import { useStore } from '../Store/Auth';
import Cleave from 'vue-cleave-component';

export default {
    setup() {
        const store = useStore();
        return { store };
    },
    data() {
        return {
            ImagePreview: window.location.origin+'/assets/img/upload-img.png',
            ShowForm: false,
            ProductData: {
                data:[]
            },
            Sort: 'desc',
            PerPage: 5,
            CatShow: 'all',
            Search: '',
            FormProduct: {
                name: '',
                category_id: '',
                image: '',
                qty: '',
                price_buy: '',
                price_sell: ''
            },
            FormType: true,
            EditID: '',
            CategoryData: [],
            options: {
                  numeral: true,
                  numeralPositiveOnly: true,
                  noImmediatePrefix: true,
                  rawValueTrimPrefix: true,
                  numeralIntegerScale: 10,
                  numeralDecimalScale: 2,
                  numeralDecimalMark: '.',
                  delimiter: ','
                }
        }
    },
    components: {
            Cleave
    },
    computed: {
        CheckForm(){
            if(this.FormProduct.name == '' || this.FormProduct.category_id == '' || this.FormProduct.qty == '' || this.FormProduct.price_buy == '' || this.FormProduct.price_sell == ''){
                return true;
            }else{
                return false;
            }
        }
    },
    methods: {
        showAlert() {
      // Use sweetalert2
    //   this.$swal({
    //         title: "Drag me!",
    //         icon: "success",
    //         draggable: true
    //         });

    // this.$swal({
    //                         toast: true,
    //                         position: "top-end",
    //                         icon: "success",
    //                         title: "gfdhgdfgfd",
    //                         showConfirmButton: false,
    //                         timer: 1500
    //                     });

                        this.$swal({
                            icon: "error",
                            title: "ຜິດຜາດ!",
                            text: "sssss",
                            showConfirmButton: false,
                            timer: 3500
                        });

    },
    RemoveImg(){
            this.ImagePreview = window.location.origin+'/assets/img/upload-img.png';
            this.FormProduct.image = '';
        },
        onSeclect(e){
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                this.ImagePreview = e.target.result;
                this.FormProduct.image = file;
            }
        }, 
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        AddProduct(){
            this.ShowForm = true;
            this.FormProduct = {
                name: '',
                category_id: '',
                image: '',
                qty: '',
                price_buy: '',
                price_sell: ''
            };
            this.ImagePreview = window.location.origin+'/assets/img/upload-img.png';
        },
        EditProduct(id){
            this.EditID = id;
            this.FormType = false;
            axios.get(`api/product/edit/${id}`,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                this.FormProduct = res.data;
                this.ShowForm = true;
            }).catch(err => {
                console.log(err);
                if(err.response){
                    if(err.response.status == 401){

                        // clear localstorage
                        localStorage.removeItem('web_token');
                        localStorage.removeItem('web_user');

                        // clear store
                        this.store.logout();

                        // redirect to login
                        this.$router.push('/login');
                    }   
                }
            });
        },
        SaveProduct(){

            if(this.FormType){
                // add new product
                axios.post('api/product/add',this.FormProduct,{ headers:{ "Content-Type":"multipart/form-data", Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                    if(res.data.success){
                        this.ShowForm = false;
                        this.GetProduct();

                        this.$swal({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: res.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        this.$swal({
                            icon: "error",
                            title: "ຜິດຜາດ!",
                            text: res.data.message,
                            showConfirmButton: false,
                            timer: 3500
                        });
                    }
                }).catch(err => {
                    console.log(err);
                    if(err.response){
                    if(err.response.status == 401){

                        // clear localstorage
                        localStorage.removeItem('web_token');
                        localStorage.removeItem('web_user');

                        // clear store
                        this.store.logout();

                        // redirect to login
                        this.$router.push('/login');
                    }   
                }
                });
                

            }else{
                // update product
                axios.post(`api/product/update/${this.EditID}`,this.FormProduct,{ headers:{ "Content-Type":"multipart/form-data", Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                    if(res.data.success){
                        this.ShowForm = false;
                        this.GetProduct();

                        this.$swal({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: res.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        this.$swal({
                            icon: "error",
                            title: "ຜິດຜາດ!",
                            text: res.data.message,
                            showConfirmButton: false,
                            timer: 3500
                        });
                    }
                }).catch(err => {
                    console.log(err);
                    if(err.response){
                    if(err.response.status == 401){

                        // clear localstorage
                        localStorage.removeItem('web_token');
                        localStorage.removeItem('web_user');

                        // clear store
                        this.store.logout();

                        // redirect to login
                        this.$router.push('/login');
                    }   
                }
                });
            }

        },
        DelProduct(id){

            this.$swal({
                title: "ທ່ານແນ່ໃຈບໍ່?",
                text: "ທີ່ຈະລຶບຂໍ້ມູນນີ້!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ຍືນຍັນ!",
                cancelButtonText: "ຍົກເລີກ"
                }).then((result) => {
                if (result.isConfirmed) {


                    axios.delete(`api/product/delete/${id}`,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                        if(res.data.success){
                            this.GetProduct();
                            this.$swal({
                                title: "ການລຶບຂໍ້ມູນ!",
                                text: res.data.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 2500
                            });
                        } else {
                            this.$swal({
                                title: "ຜິດຜາດ!",
                                text: res.data.message,
                                icon: "error"
                            });
                        }
                    }).catch(err => {
                        console.log(err);
                        if(err.response){
                            if(err.response.status == 401){

                                // clear localstorage
                                localStorage.removeItem('web_token');
                                localStorage.removeItem('web_user');

                                // clear store
                                this.store.logout();

                                // redirect to login
                                this.$router.push('/login');
                            }   
                        }
                    });
                   
                   
                }
                });

           
        },
        CloseForm(){
            this.ShowForm = false;
        },
        GetProduct(page){
            axios.get(`api/product?page=${page}&sort=${this.Sort}&perpage=${this.PerPage}&catid=${this.CatShow}&search=${this.Search}`,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                this.ProductData = res.data;
            }).catch(err => {
                console.log(err);
                if(err.response){
                    if(err.response.status == 401){

                        // clear localstorage
                        localStorage.removeItem('web_token');
                        localStorage.removeItem('web_user');

                        // clear store
                        this.store.logout();

                        // redirect to login
                        this.$router.push('/login');
                    }   
                }
            });
        },  
        GetCategory(){
            axios.get('api/category',{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                this.CategoryData = res.data;
            }).catch(err => {
                console.log(err);
                if(err.response){
                    if(err.response.status == 401){

                        // clear localstorage
                        localStorage.removeItem('web_token');
                        localStorage.removeItem('web_user');

                        // clear store
                        this.store.logout();

                        // redirect to login
                        this.$router.push('/login');
                    }   
                }
            });
        }
    },
    created(){
        this.GetProduct();
        this.GetCategory();
    },
    watch: {
        Sort(){
            this.GetProduct();
        },
        PerPage(){
            this.GetProduct();
        },
        CatShow(){
            this.GetProduct();
        },
        
        Search(){
            if(this.Search.length == 0){
                this.GetProduct();
            }
        }
    }
}
</script>
<style scoped >
    .ab{
        background-color: #17a299;
        color: white;
    }
    .boximg{
        position: absolute;
        top: 0px;
        right: 20px;
    }
</style>