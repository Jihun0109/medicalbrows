<template>
    <div class="row">
          <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (クリニック管理)</h3></div></div>
          <div class="col-12">              
            <div class="card">
              <div class="card-header">
                <div class="card-title">
                    <div class="input-group input-group-md">
                        <input type="text" class="form-control" placeholder="検索したい文字列を入力" v-model="keyword" @keyup.enter="searchit">
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info" @click="searchit">検索</button>
                        </span>
                    </div>
                </div>

                <div class="card-tools">
                  <button class="btn btn-success" @click="newModal">追加 <i class="fa fa-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>クリニックID</th>
                      <th>クリニック名 </th>
                      <th>メール</th>                      
                      <th>住所</th>
                      <th>休閉鎖</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="d in data" :key="d.id">
                      <td>{{ d.id }}</td>
                      <td>{{ d.name }}</td>
                      <td>{{ d.email }}</td>
                      <td>{{ d.address }}</td>
                      <td>{{ d.is_vacation | isVacation}}</td>
                      <td>
                          <a href="#" @click="editModal(d)"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                          <a href="#" @click="deleteData(d.id)"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>                    
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

        <!-- Modal -->
        <div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">クリニック更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">クリニック追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateData() : createData()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>クリニック名</label>
                        <input v-model="form.name" type="text" name="name" class="form-control" :class="{'is-invalid':form.errors.has('name')}" placeholder="クリニック名 ">
                        <div v-if="form.errors.has('name')" class="invalid-feedback">{{errormsg(form.errors.get('name'),"name","クリニック名")}}</div>
                    </div>                    
                    <div class="form-group">
                        <label>ユーザーID</label>
                        <select v-model="form.user_id" class="custom-select" name="user_id" :class="{'is-invalid':form.errors.has('user_id')}">
                          <option v-for="u in users" :key="u.user_id" v-bind:value="u.user_id">{{ u.user_id }}</option>
                        </select>
                        <div v-if="form.errors.has('user_id')" class="invalid-feedback">{{errormsg(form.errors.get('user_id'),"user id","ユーザーID")}}</div>
                    </div>
                    <div class="form-group">
                        <label>住所</label>
                        <input v-model="form.address" type="text" name="address" class="form-control" :class="{'is-invalid':form.errors.has('address')}" placeholder="住所">
                        <div v-if="form.errors.has('address')" class="invalid-feedback">{{errormsg(form.errors.get('address'),"address","住所")}}</div>
                    </div>

                    <div class="form-group">
                        <label>休閉鎖</label>
                        <select v-model="form.is_vacation" class="custom-select" name="is_vacation" :class="{'is-invalid':form.errors.has('is_vacation')}">
                          <option v-bind:value=0>アクティブ</option>
                          <option v-bind:value=1>閉鎖</option>
                        </select>
                        <div v-if="form.errors.has('is_vacation')" class="invalid-feedback">{{errormsg(form.errors.get('is_vacation'),"is vacation","休閉鎖")}}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                    <button v-show="editMode" type="submit" class="btn btn-success">更新</button>
                    <button v-show="!editMode" type="submit" class="btn btn-primary">追加</button>
                </div>
                </form>
                </div>
            </div>
            </div>
        </div>
</template>

<script>
    export default {
        data() {
            return {
                data: {},
                users: {},
                form: new Form({
                    id : '',
                    name : '',
                    email : 0,
                    address : '',
                    is_vacation : 0,
                }),
                editMode: false,
                keyword: ""
            }
        },
        methods: {
            errormsg(msg,attribute,jpstr){
                return msg.replace(attribute,jpstr);
            },            
            searchit(){
                axios.get('/api/clinic?keyword='+this.keyword).
                    then(({data}) => (this.data = data));
            },
            loadList(){
                axios.get('/api/clinic').
                    then(({data}) => (this.data = data));                
            },
            createData(){  
                console.log(this.data);
                console.log(this.form.user_id);
                this.users.forEach(element => {                                    
                    if (this.form.user_id === element.user_id){
                        console.log(">>>"+element.user_id);
                        this.form.email = element.email;
                    }
                });              
                this.form.post('/api/clinic')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "データ追加しました"
                        });
                        $('#modalAddUser').modal('hide');
                        this.loadList();
                    })
                    .catch(()=>{

                    });         
            },
            updateData(){
                console.log(this.users);
                this.users.forEach(element => {                                    
                    if (this.form.user_id == element.user_id){
                        this.form.email = element.email;
                    }
                });
                this.form.put('/api/clinic/' + this.form.id)
                    .then(()=>{
                        toast.fire({
                                icon: "success",
                                title: "更新成功!"
                            });
                            $('#modalAddUser').modal('hide');
                            this.loadList();

                    })
                    .catch(()=>{

                    });
            },
            deleteData(id){
             let _this = this;
                swal.fire({
                    //title: '本気ですか？',
                    text: "データ削除しても宜しいですか？",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '実行',
                    cancelButtonText: 'キャンセル',
                    reverseButtons: true
                    }).then(function(isConfirm) {
                        console.log(isConfirm);
                    if (isConfirm.value == true) {
                        _this.form.delete('/api/clinic/' + id)
                            .then((result)=>{
                                //if (result.message){
                                    toast.fire({
                                        icon: "success",
                                        title: "削除しました。"
                                    });
                                    _this.loadList();
                                //}
                            })
                            .catch(()=>{

                            });
                    }
                })                
            },
            newModal(){
                this.editMode = false;
                this.form.errors.clear();     
                this.form.reset();
                this.getEmailList('');
                $('#modalAddUser').modal('show');
            },
            editModal(data){
                this.editMode = true;
                this.form.errors.clear();     
                this.form.fill(data);                
                this.form.password = "";
                this.getEmailList(data);
                $('#modalAddUser').modal('show');
            },
            getEmailList(targetEmail){
                axios.get('/v1/clinic/get-email').then(
                    ({data}) => {
                        this.users = data;
                        if (targetEmail)
                            this.users.push({'user_id':targetEmail['user_id'],'email':targetEmail['email']});
                    }
                );
            }
        },
        created() {
            this.loadList();
        }
    }
</script>
