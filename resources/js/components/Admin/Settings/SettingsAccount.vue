<template>
    <div class="row">
        <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (アカウント管理)</h3></div></div>
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
                      <th>ユーザーID</th>
                      <th>ユーザー名</th>
                      <th>メール</th>                      
                      <th>アカウントタイブ</th>
                      <th>状態</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="d in data" :key="d.id">                      
                      <td>{{ d.user_id }}</td>
                      <td>{{ d.name }}</td>
                      <td>{{ d.email }}</td>                      
                      <td>
                          <div v-for="r in roles" :key="r.id">
                              <div v-if="d.role_id == r.id">{{r.display_name}}</div>
                          </div>
                      </td>
                      <td>
                          {{ d.is_active | isActive}}
                      </td>
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
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">アカウント更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">アカウント追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateRank() : createData()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>ユーザーID</label>
                        <input v-model="form.user_id" type="text" name="user_id" class="form-control" :class="{'is-invalid':form.errors.has('user_id')}" placeholder="ユーザーID">
                        <div v-if="form.errors.has('user_id')" class="invalid-feedback">{{errormsg(form.errors.get('user_id'),"user id","ユーザーID")}}</div>
                    </div>
                    <div class="form-group">
                        <label>ユーザー名</label>
                        <input v-model="form.name" type="text" name="name" class="form-control" :class="{'is-invalid':form.errors.has('name')}" placeholder="ユーザー名">
                        <div v-if="form.errors.has('name')" class="invalid-feedback">{{errormsg(form.errors.get('name'),"name","ユーザー名")}}</div>
                    </div>
                    <div class="form-group">
                        <label>メール</label>
                        <input v-model="form.email" type="text" name="email" class="form-control" :class="{'is-invalid':form.errors.has('email')}" placeholder="メール">
                        <div v-if="form.errors.has('email')" class="invalid-feedback">{{errormsg(form.errors.get('email'),"email","メール")}}</div>
                    </div>
                    <div class="form-group">
                        <label>バスワード</label>
                        <input v-model="form.password" type="password" class="form-control" :class="{'is-invalid':form.errors.has('password')}" placeholder="バスワード">
                        <div v-if="form.errors.has('password')" class="invalid-feedback">{{errormsg(form.errors.get('password'),"password","バスワード")}}</div>
                    </div>
                    <div class="form-group">
                        <label>アカウントタイブ</label>
                        <select v-model="form.role_id" class="custom-select">
                          <option v-for="role in roles" :key="role.id" v-bind:value="role.id">{{ role.display_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>状態</label>
                        <select v-model="form.is_active" class="custom-select">
                          <option v-bind:value=0>非アクティブ</option>
                          <option v-bind:value=1>アクティブ</option>
                        </select>
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
                roles: {},
                form: new Form({
                    id : '',
                    user_id : '',
                    name : '',
                    email : '',
                    password : '',
                    role_id : 2,
                    is_active: 1
                }),
                editMode: false,
                keyword: ''
            }
        },
        methods: {
            errormsg(msg,attribute,jpstr){
                return msg.replace(attribute,jpstr);
            },
            searchit(){
                axios.get('/api/user?keyword=' + this.keyword).
                    then(({data}) => (this.data = data));
            },
            loadList(){
                axios.get('/api/user').
                    then(({data}) => (this.data = data));
                axios.get('/api/role').
                    then(({data}) => (this.roles = data));
            },
            createData(){                
                this.form.post('/api/user')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "データ追加しました."
                        });
                        $('#modalAddUser').modal('hide');
                        this.loadList();
                    })
                    .catch(()=>{

                    });         
            },
            updateRank(){
                this.form.put('/api/user/' + this.form.id)
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
                        _this.form.delete('/api/user/' + id)
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
                this.form.reset();       
                this.form.errors.clear();         
                $('#modalAddUser').modal('show');
            },
            editModal(data){
                this.editMode = true;
                this.form.errors.clear();
                this.form.fill(data);
                this.form.password = "";
                $('#modalAddUser').modal('show');
            }
        },
        created() {
            this.loadList();
        }
    }
</script>
