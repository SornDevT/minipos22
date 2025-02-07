<template lang="">
     <div class="card">
  <h5 class="card-header">ການເຄື່ອນໄຫວ ທຸລະກຳ</h5>
  <div class="card-body">


   
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
         
        </div>
        <div class="col-lg-6 mb-2 ">
            <div class="d-flex justify-content-end">

            <div class=" d-flex">
                <div class="btn-group me-2" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary" @click="month_type='m'"> <i class='bx bxs-chevron-right' v-if="month_type=='m'"></i> ເດືອນ</button>
                    <button type="button" class="btn btn-secondary" @click="month_type='y'"> <i class='bx bxs-chevron-right' v-if="month_type=='y'"></i> ປີ</button>
                </div>
                <input type="date" class=" form-control" v-model="dmy">
            </div>
            
        </div>
        </div>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ວັນທີ່</th>
            <th>ເລກທີ່ທຸລະກຳ</th>
            <th>ປະເພດທຸລະກຳ</th>
            <th>ລາຍລະອຽດ</th>
            <th class="text-center">ມູນຄ່າ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in TranData.data" :key="item.id">
            <td>{{ date(item.created_at) }}</td>
            <td>{{ item.tran_id }}</td>
            <td>{{ item.tran_type }}</td>
            <td>{{ item.detail }}</td>
            <td class="text-end">{{ formatPrice(item.price) }}</td>
          </tr>
         
        </tbody>
      </table>

      <Pagination :pagination="TranData" :offset="4" @paginate="GetTran($event)" />

   

</div>

  </div>
</div>
</template>
<script>
import axios from 'axios';
import { useStore } from '../Store/Auth';
import moment from 'moment';
export default {
    setup() {
        const store = useStore();
        return { store };
    },
    data() {
        return {
            Sort:'desc',
            PerPage:10,
            month_type:'m',
            dmy: new Date().toISOString().slice(0,10),
            TranData:[],
        }
    },
    components:{
        moment
    },
    methods:{
        date(value){
            return moment(value).format("DD/MM/YYYY");
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        GetTran(page){
            axios.get(`api/transection?page=${page}&sort=${this.Sort}&perpage=${this.PerPage}&month_type=${this.month_type}&dmy=${this.dmy}`,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then((res)=>{
                this.TranData = res.data;
            }).catch((err)=>{
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
    watch:{
        Sort(){
            this.GetTran();
        },
        PerPage(){
            this.GetTran();
        },
        month_type(){
            this.GetTran();
        },
        dmy(){
            this.GetTran();
        }
    },
    created() {
        this.GetTran();
    },
}
</script>
<style lang="">
    
</style>