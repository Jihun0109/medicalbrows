<template>
    <div class="row">
          <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (クリニツク管理)</h3></div></div>
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
                      <th>クリニツクID</th>
                      <th>クリニツク名 </th>
                      <th>メール</th>                      
                      <th>住所</th>
                      <th>休閉鎖</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(d, index) in data" :key="d.id">
                      <td>{{ index+1 }}</td>
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
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">クリニツク更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">クリニツク追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateRank() : createData()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>クリニツク名</label>
                        <input v-model="form.name" type="text" name="name" class="form-control" :class="{'is-invalid':form.errors.has('name')}" placeholder="クリニツク名 ">
                        <has-error :form="form" field="name"></has-error>
                    </div>                    
                    <div class="form-group">
                        <label>メール</label>
                        <select v-model="form.email" class="custom-select">
                          <option v-for="u in users" :key="u" v-bind:value="u">{{ u }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>住所</label>
                        <input v-model="form.address" type="text" name="address" class="form-control" :class="{'is-invalid':form.errors.has('address')}" placeholder="住所">
                        <has-error :form="form" field="address"></has-error>
                    </div>

                    <div class="form-group">
                        <label>体閉鎖</label>
                        <select v-model="form.is_vacation" class="custom-select">
                          <option v-bind:value=0>アクティブ</option>
                          <option v-bind:value=1>閉鎖</option>
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
                users: {},
                form: new Form({
                    id : '',
                    name : '',
                    email : '',
                    address : '',
                    is_vacation : 0,
                }),
                editMode: false,
                keyword: ""
            }
        },
        methods: {
            searchit(){
                axios.get('/api/clinic?keyword='+this.keyword).
                    then(({data}) => (this.data = data));
            },
            loadList(){
                axios.get('/api/clinic').
                    then(({data}) => (this.data = data));
                axios.get('/v1/clinic/get-email').then(
                    ({data}) => (this.users = data)
                );
            },
            createData(){                
                this.form.post('/api/clinic')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "A account was created successfully."
                        });
                        $('#modalAddUser').modal('hide');
                        this.loadList();
                    })
                    .catch(()=>{

                    });         
            },
            updateRank(){
                this.form.put('/api/clinic/' + this.form.id)
                    .then(()=>{
                        toast.fire({
                                icon: "success",
                                title: "Updated successfully!"
                            });
                            $('#modalAddUser').modal('hide');
                            this.loadList();

                    })
                    .catch(()=>{

                    });
            },
            deleteData(id){
                this.form.delete('/api/clinic/' + id)
                    .then((result)=>{
                        //if (result.message){
                            toast.fire({
                                icon: "success",
                                title: "Deleted successfully!"
                            });
                            this.loadList();
                        //}
                    })
                    .catch(()=>{

                    });
            },
            newModal(){
                this.editMode = false;
                this.form.reset();                
                $('#modalAddUser').modal('show');
            },
            editModal(data){
                this.editMode = true;
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
