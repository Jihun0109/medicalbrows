<template>
    <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">アカウント管理</h3>

                <div class="card-tools">
                  <button class="btn btn-success" @click="newModal">追加 <i class="fa fa-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>番号</th>
                      <th>クリニツク名 </th>
                      <th>メール</th>                      
                      <th>住所</th>
                      <th>体閉鎖</th>
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
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">アカウント更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">アカウント追加</h5>
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
                        <input v-model="form.email" type="text" name="email" class="form-control" :class="{'is-invalid':form.errors.has('email')}" placeholder="メール">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                        <label>住所</label>
                        <input v-model="form.address" type="text" name="address" class="form-control" :class="{'is-invalid':form.errors.has('address')}" placeholder="住所">
                        <has-error :form="form" field="address"></has-error>
                    </div>

                    <div class="form-group">
                        <label>体閉鎖</label>
                        <select v-model="form.is_vacation" class="custom-select">
                          <option v-bind:value=0>開いた</option>
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
                form: new Form({
                    id : '',
                    name : '',
                    email : '',
                    address : '',
                    is_vacation : 0,
                }),
                editMode: false
            }
        },
        methods: {
            loadList(){
                axios.get('api/clinic').
                    then(({data}) => (this.data = data.data));
            },
            createData(){                
                this.form.post('api/clinic')
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
                this.form.put('api/clinic/' + this.form.id)
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
                this.form.delete('api/clinic/' + id)
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
