<template lang="">
    <div class="card">
  <h5 class="card-header">ລາຍການ ສະຕ໋ອກສິນຄ້າ</h5>
  <div class="card-body">

    <div class="row" v-if="ShowForm">
        <div class="col-lg-12 text-end">
            <button type="button" class="btn btn-success me-2" :disabled="CheckForm" @click="SaveProduct()" >ບັນທຶກ</button>
            <button type="button" @click="CloseForm()" class="btn btn-danger">ຍົກເລີກ</button>
        </div>
        <div class="col-lg-4">Image</div>
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
                        <input type="number" v-model="FormProduct.qty" class="form-control" id="Qty" placeholder="...">
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
                        <input type="text" v-model="FormProduct.price_buy" class="form-control text-end" id="Price" placeholder="...">
                    <span class="input-group-text">ກີບ</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="Price" class="form-label">ລາຄາຂາຍ:</label>
                    <div class="input-group mb-3">
                        <input type="text" v-model="FormProduct.price_sell" class="form-control text-end" id="Price" placeholder="...">
                    <span class="input-group-text">ກີບ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

    <div v-else>

   
    <div class="row" >
        <div class="col-lg-6 mb-2 d-flex align-items-center">
            <div class="me-2">
                <i class='bx bx-sort-up fs-4'></i>
            </div>
            <select v-model="PerPage" class="form-select w-auto me-2" >
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
                    <input v-model="Search" type="text" class="form-control" placeholder="ຄົ້ນຫາ..." >
                    <button class="btn btn-primary px-2 " type="button" >
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
          <tr>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu" style="">
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                </div>
              </div>
            </td>
          </tr>
         
        </tbody>
      </table>
    </div>

</div>

  </div>
</div>
</template>
<script>
import axios from 'axios';
import { useStore } from '../Store/Auth';

export default {
    setup() {
        const store = useStore();
        return { store };
    },
    data() {
        return {
            ShowForm: false,
            ProductData: {
                data:[]
            },
            Sort: 'desc',
            PerPage: 10,
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
        }
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
        AddProduct(){
            this.ShowForm = true;
        },
        SaveProduct(){

            if(this.FormType){
                // add new product
                axios.post('api/product/add',this.FormProduct,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                    if(res.data.success){
                        this.ShowForm = false;
                        this.GetProduct();
                    }
                });
                

            }else{
                // update product
            }

        },
        CloseForm(){
            this.ShowForm = false;
        },
        GetProduct(page){
            axios.get(`api/product?page=${page}&sort=${this.Sort}&perpage=${this.PerPage}&catid=${this.CatShow}&search=${this.Search}`,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                this.ProductData = res.data;
            });
        },  
        GetCategory(){
            axios.get('api/category',{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                this.CategoryData = res.data;
            });
        }
    },
    created(){
        this.GetProduct();
        this.GetCategory();
    }
}
</script>
<style scoped >
    .ab{
        background-color: #17a299;
        color: white;
    }
</style>