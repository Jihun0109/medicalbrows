<template>
    <div class="row">
        <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (スタッフ管理)</h3></div></div>
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
                      <th>スタッフID</th>
                      <th>スタツフ名</th>
                      <th>表記名</th>
                      <th>スタッフ区分</th>
                      <th>クリニック名</th>
                      <th>休退職</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="d in data" :key="d.id">
                      <td>{{ d.id }}</td>
                      <td>{{ d.full_name }}</td>
                      <td>{{ d.alias }}</td>
                      <td>
                          <div v-for="t in staff_types" :key="t.id">
                              <div v-if="d.staff_type_id == t.id">{{t.name}}</div>
                          </div>
                      </td>
                      <td>
                          <div v-for="c in clinics" :key="c.id">
                              <div v-if="d.clinic_id == c.id">{{c.name}}</div>
                          </div>
                      </td>
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
        <div class="modal fade" id="modalAddStaff" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">スタッフ更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">スタッフ追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateRank() : createData()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>スタツフ名</label>
                        <input v-model="form.full_name" type="text" name="full_name" class="form-control" :class="{'is-invalid':form.errors.has('full_name')}" placeholder="スタツフ名">
                        <has-error :form="form" field="full_name"></has-error>
                    </div>
                    <div class="form-group">
                        <label>表記名</label>
                        <input v-model="form.alias" type="text" name="alias" class="form-control" :class="{'is-invalid':form.errors.has('alias')}" placeholder="表記名">
                        <has-error :form="form" field="alias"></has-error>
                    </div>
                    
                    <div class="form-group">
                        <label>スタツフ区分</label>
                        <select v-model="form.staff_type_id" class="custom-select" name="staff_type_id" :class="{'is-invalid':form.errors.has('staff_type_id')}">
                          <option v-for="type in staff_types" :key="type.id" v-bind:value="type.id">{{ type.name }}</option>
                        </select>
                        <has-error :form="form" field="staff_type_id"></has-error>
                    </div>
                    
                    <div class="form-group">
                        <label>クリニック名</label>
                        <select v-model="form.clinic_id" class="custom-select" name="clinic_id" :class="{'is-invalid':form.errors.has('clinic_id')}">
                          <option v-for="clinic in clinics" :key="clinic.id" v-bind:value="clinic.id">{{ clinic.name }}</option>
                        </select>
                        <has-error :form="form" field="clinic_id"></has-error>
                    </div>
                    <div class="form-group">
                        <label>休退職</label>
                        <select v-model="form.is_vacation" class="custom-select" name="is_vacation" :class="{'is-invalid':form.errors.has('is_vacation')}">
                          <option v-bind:value=0>アクティブ</option>
                          <option v-bind:value=1>退職</option>
                        </select>
                        <has-error :form="form" field="is_vacation"></has-error>
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
                clinics: {},
                staff_types : {},
                form: new Form({
                    id : '',                    
                    full_name : '',
                    alias : '',
                    staff_type_id : '',
                    clinic_id : '',
                    is_vacation : '',
                }),
                editMode: false,
                keyword : ""
            }
        },
        methods: {
            searchit(){
                axios.get('/api/staff?keyword=' + this.keyword).
                    then(({data}) => (this.data = data));
            },
            loadList(){
                axios.get('/api/staff').
                    then(({data}) => (this.data = data));
                axios.get('/api/clinic').
                    then(({data}) => (this.clinics = data));
                axios.get('/api/staff-type').
                    then(({data}) => (this.staff_types = data));
            },
            createData(){                
                this.form.post('/api/staff')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "データ追加しました"
                        });
                        $('#modalAddStaff').modal('hide');
                        this.loadList();
                    })
                    .catch(()=>{

                    });         
            },
            updateRank(){
                this.form.put('/api/staff/' + this.form.id)
                    .then(()=>{
                        toast.fire({
                                icon: "success",
                                title: "更新成功!"
                            });
                            $('#modalAddStaff').modal('hide');
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
                        _this.form.delete('/api/staff/' + id)
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
                $('#modalAddStaff').modal('show');
            },
            editModal(data){
                this.editMode = true;
                this.form.errors.clear();
                this.form.fill(data);
                this.form.password = "";
                $('#modalAddStaff').modal('show');
            }
        },
        created() {
            this.loadList();
        }
    }
</script>
