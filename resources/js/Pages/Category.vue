<template lang="">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-2 border-bottom d-flex align-items-center justify-content-between">
                        <h5 >ໝວດໝູ່ ສິນຄ້າ</h5>
                        <button type="button" @click="AddCat()" class="btn rounded-pill btn-icon btn-info">
                            <i class='bx bx-plus fs-4' ></i>
                        </button>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item list-group-item-action d-flex justify-content-between" v-for="item in CategoryData" :key="item.id">
                        <span> {{ item.name }}</span>
                        <div>
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
                            <div class="dropdown-menu" style="">
                                <a class="dropdown-item" href="javascript:void(0);" @click="EditCat(item.id)"><i class="bx bx-edit-alt me-2"></i> ແກ້ໄຂ</a>
                                <a class="dropdown-item" href="javascript:void(0);" @click="DelCat(item.id)" ><i class="bx bx-trash me-2"></i> ລຶບ</a>
                            </div>
                        </div>
                    </div>
          </div>
                </div>
        </div>
        
    </div>

    <div class="modal fade" id="FormCat" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="backDropModalTitle">ຟອມໝວດໝູ່ ສິນຄ້າ</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col">
                      <label  class="form-label">ຊື່ໝວດໝູ່:</label>
                      <input type="text" v-model="CatName"  class="form-control" placeholder="...">
                    </div>
                  </div>
                  
                </div>
                <div class="modal-footer">
                  
                  <button type="button" class="btn btn-primary me-2" :disabled="CheckForm" @click="SaveCat()" >ບັນທຶກ</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ປິດ</button>
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
            CatName: '',
            FormType: true,
            EditID: '',
            CategoryData: [],
        }
    },
    computed: {
        CheckForm(){
            if(this.CatName == ''){
                return true;
            } else {
                return false;
            }
        }
    },
    methods: {
        AddCat(){

            $('#FormCat').modal('show');
            this.CatName = '';
            this.FormType = true;
        },
        EditCat(id){
            this.FormType = false;
            this.EditID = id;
            // console.log(id);
            axios.get(`api/category/edit/${id}`,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                this.CatName = res.data.name;
                $('#FormCat').modal('show');
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
        DelCat(id){



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


                    axios.delete(`api/category/delete/${id}`,{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                        if(res.data.success){
                            this.GetCat();
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
        SaveCat(){
            
            if(this.FormType){
                // add new category
                axios.post('api/category/add', {
                    name: this.CatName
                },{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                    if(res.data.success){
                        // close modal
                        $('#FormCat').modal('hide');
                        this.GetCat();

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
            } else {
                // edit category
                axios.post(`api/category/update/${this.EditID}`, {
                    name: this.CatName
                },{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                    if(res.data.success){
                        // close modal
                        $('#FormCat').modal('hide');
                        this.GetCat();

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
        GetCat(){
            axios.get('api/category',{ headers:{ Authorization: 'Bearer '+this.store.getToken} }).then(res => {
                this.CategoryData = res.data;
            }).catch(err => {
                // console.log(err.response);

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
        this.GetCat();
    }
}
</script>
<style lang="">
    
</style>