<template lang="">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body d-flex">
                    <select v-model="CatShow" class="form-select w-auto me-2" >
                        <option value="all">ທັງໝົດ</option>
                        <option :value="cat.id" v-for="cat in CategoryData" :key="cat.id"> {{ cat.name }} </option>
                        
                    </select>
                    <div class="input-group">
                        <input type="text" class="form-control" v-model="Search" placeholder="ຄົ້ນຫາ..." @keyup.enter="GetProduct()">
                        <button class="btn btn-primary px-3" @click="GetProduct()">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </div>
            </div>

            <PerfectScrollbar>
            <div class="row mt-3" style="height: 65vh;">
                <div class="col-md-3" v-for="item in ProductData.data" :key="item.id">
                    <div class="card mb-3 cursor-pointer" @click="AddOrder(item.id)" style="position:relative">
                        <span v-for="i in ListOrder" :key="i.id">
                            <span class="num-order"  v-if="item.id == i.id" > {{ i.qty }} </span>
                        </span>
                        <span class="no-product" v-if="item.qty<=0" >ບໍ່ສິນຄ້າ</span>
                        <img class="card-img-top imgpos" v-if="item.image" :src="url + '/assets/img/'+item.image" >
                        <img class="card-img-top imgpos" v-else :src="url + '/assets/img/no_img.jpg'" >
                        <div class="card-body p-1 text-center">
                            <h5 class="card-title">{{ item.name }}</h5>
                            <p class="card-text">
                            {{ formatPrice(item.price_sell) }} ກີບ
                            </p>
                            
                        </div>
                        </div>
                </div>
            </div>
        </PerfectScrollbar>

        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-3">
                        <label>ຊື່ລູກຄ້າ:</label>
                        <input type="text" v-model="customer_name" class="form-control mb-2" placeholder="...">
                        <label>ເບີໂທ:</label>
                        <input type="text" v-model="customer_tel" class="form-control mb-2" placeholder="...">
                    </div>

                    <div class="p-2 bg-info text-white ">
                        ລາຍການສັ່ງຊື້
                    </div>
                <PerfectScrollbar>
                    <div style="height:40vh">
                    <div  class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <tbody>
                            <tr v-for="item in ListOrder" :key="item.id">
                                <td class="p-1">
                                    <div class=" d-flex">
                                        <img v-if="item.image" :src="url+'/assets/img/'+item.image" class="list-img-order me-1">
                                        <img v-else :src="url+'/assets/img/no_img.jpg'" class="list-img-order me-1">
                                        <div class="w-100 pe-2">
                                            <div >{{ item.name }}</div>
                                            <div class="text-end">{{ item.qty }} x {{ formatPrice(item.price) }}</div>
                                            <div class="d-flex justify-content-between">
                                                <span>
                                                    <i class='bx bxs-plus-circle text-success cursor-pointer' @click="AddOrder(item.id)"></i> |
                                                    <i class='bx bxs-minus-circle text-warning cursor-pointer' @click="RemoveOrder(item.id)"></i> |
                                                    <i class='bx bxs-trash text-danger cursor-pointer' @click="DelOrder(item.id)"></i>
                                                </span>
                                                <span>{{ formatPrice(item.qty*item.price) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
                </PerfectScrollbar>
                    <div class="p-2 bg-info text-white d-flex justify-content-between">
                        <span>ລວມຍອດເງິນ:</span>
                        <span>{{ formatPrice(TotalOrder) }} ກີບ</span>
                    </div>
                    <div class="p-2">
                        <button :disabled="!TotalOrder" @click="Pay()" class="btn btn-success w-100">ຊຳລະເງິນ</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dialog_pay" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="backDropModalTitle">ການຊຳລະເງິນ</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                        <div class="col-md-6">
                            <div class=" d-flex justify-content-between">
                                <span>ລວມຍອດເງິນ:</span>
                                <span>{{ formatPrice(TotalOrder) }} ກີບ</span>
                            </div>
                            <div class=" d-flex justify-content-between text-danger" v-if="(CashAmount-TotalOrder)>0">
                                <span>ເງິນທອນ:</span>
                                <span>{{ formatPrice(CashAmount-TotalOrder) }} ກີບ</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <label>ຮັບເງິນນຳລູກຄ້າ</label>
                            <cleave :options="options" v-model="CashAmount" class=" form-control text-end" />
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100" @click="AddNum(500)" >500</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(1000)">1,000</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(2000)" >2,000</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(5000)">5,000</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(10000)">10,000</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(20000)" >20,000</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(50000)">50,000</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(100000)" >100,000</button>
                                </div>
                            </div>
                        </div>
                  </div>
                </div>
                <div class="modal-footer">
                  
                  <button type="button" class="btn btn-primary me-2" :disabled="CheckBTPay" @click="Confirm_Pay()" >ບັນທຶກ</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ປິດ</button>
                </div>
              </form>
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
            url: window.location.origin,
            Sort: 'desc',
            PerPage: 30,
            CatShow: 'all',
            CategoryData: [],
            ProductData: {
                data:[]
            },
            ListOrder:[],
            options: {
                  numeral: true,
                  numeralPositiveOnly: true,
                  noImmediatePrefix: true,
                  rawValueTrimPrefix: true,
                  numeralIntegerScale: 10,
                  numeralDecimalScale: 2,
                  numeralDecimalMark: '.',
                  delimiter: ','
                },
            Search: '',
            CashAmount:0,
            customer_name:'',
            customer_tel:''
        }
    },
    components: {
            Cleave
    },
    computed:{
        CheckBTPay(){
                if(this.CashAmount>=this.TotalOrder){
                    return false 
                } else {
                    return true
                }
            }, 
        CheckBTPay(){
                if(this.CashAmount>=this.TotalOrder){
                    return false 
                } else {
                    return true
                }
            },
        TotalOrder(){
            let total = 0;
            this.ListOrder.forEach((item)=>{
                total += item.qty*item.price;
            });
            return total;
        }
    },
    methods: {

        async openLink(link){
            const response = await fetch(`${link}`,{ headers:{ Authorization: 'Bearer '+ this.store.getToken}});
            const html = await response.text();
            const blob = new Blob([html],{ type: "text/html"});
            const blobUrl = URL.createObjectURL(blob);
            window.open(blobUrl, "_blank");
        },
        AddNum(num){
            this.CashAmount = parseInt(this.CashAmount?this.CashAmount:0) + parseInt(num);
        },
        AddOrder(id){

            let item = this.ProductData.data.find((i)=>i.id == id);
            let old_item = this.ListOrder.find((i)=>i.id == id);

            if(item.qty>0){
                    
                    if(old_item){
                        if(item.qty - old_item.qty>0){
                            old_item.qty++;
                        } else {
                            this.$swal({
                                        title:"ບໍ່ສາມາດຂາຍສິນຄ້າໄດ້!",
                                        text:"ສິນຄ້າດັ່ງກ່າວໄດ້ໝົດແລ້ວ!",
                                        showConfirmButton: false,
                                        icon:"error",
                                        timer:3500
                                    });
                        }
                        
                    } else {
                        this.ListOrder.push({
                            id: item.id,
                            image: item.image,
                            name: item.name,
                            price: item.price_sell,
                            qty: 1
                        }); 
                    }
            } else {
                this.$swal({
                                        title:"ບໍ່ສາມາດຂາຍສິນຄ້າໄດ້!",
                                        text:"ສິນຄ້າດັ່ງກ່າວໄດ້ໝົດແລ້ວ!",
                                        showConfirmButton: false,
                                        icon:"error",
                                        timer:3500
                                    });
            }

            

            
        },
        RemoveOrder(id){

            let index = this.ListOrder.findIndex((i)=>i.id==id);
            if(this.ListOrder[index].qty>1){
                this.ListOrder[index].qty -= 1;
            } else {
                this.ListOrder.splice(index,1);
            }
            
            
        },
        DelOrder(id){

            let index = this.ListOrder.findIndex((i)=>i.id==id);
            this.ListOrder.splice(index,1);
        },
        Pay(){
            $('#dialog_pay').modal('show');
        },
        Confirm_Pay(){
            axios.post(`api/transection/add`,{
                customer_name: this.customer_name,
                customer_tel: this.customer_tel,
                listorder: this.ListOrder
            },{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then((res)=>{

                if(res.data.success){

                    this.ListOrder = [];
                    this.customer_name = '';
                    this.customer_tel = '';
                    this.CashAmount = '';
                    $('#dialog_pay').modal('hide');

                    this.$swal({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: res.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                    // ກວດວ່າມີ bill_id ສົ່ງກັບມາຫຼືບໍ່
                    if(res.data.bill_id){
                        // window.open(window.location.origin+"/api/bills/print/"+res.data.bill_id);
                        this.openLink(window.location.origin+"/api/bills/print/"+res.data.bill_id);
                    } 

                    // ອັບເດດລາຍການສິນຄ້າ

                    this.GetProduct();

                } else {
                    this.$swal({
                            icon: "error",
                            title: "ຜິດຜາດ!",
                            text: res.data.message,
                            showConfirmButton: false,
                            timer: 3500
                        });
                }

            }).catch((err)=>{
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
            })
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
<style scoped>
    .imgpos{
        object-fit: cover;
        object-position: center;
        height: 95px;
    }
    .num-order{
        position: absolute;
    top: 0px;
    right: 0px;
    background-color: orangered;
    color: white;
    padding: 5px;
    border-radius: 0px 5px;
    }
    .list-img-order{
        width: 70px;
    height: 70px;
    object-fit: cover;
    object-position: center;
    border-radius: 5px;
    padding: 2px;
    border: 1px solid #d8d8d9;
    }

    .no-product{
        position: absolute;
    background-color: #f5222296;
    color: white;
    width: 100%;
    padding: 5px;
    text-align: center;
    top: 40px;
    }
</style>