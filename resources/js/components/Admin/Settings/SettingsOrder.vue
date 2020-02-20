<template>
    <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">注文管理</h3>

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
                      <th>is_new</th>
                      <th>phone_number</th>
                      <th>prev_order</th>                      
                      <th>staff</th>
                      <th>counselor</th>
                      <th>menu</th>
                      <th>clinic</th>
                      <th>description</th>
                      <th>始まる時間</th>
                      <th>終了時間</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in data" :key="item.id">
                      <td>{{ index }}</td>
                      <td>{{ item.is_new }}</td>
                      <td>{{ item.phone_number }}</td>
                      <td>{{ item.order_id }}</td>
                      <td>{{item.staff_id}}</td>
                      <td>{{item.counselor_id}}</td>
                      <td>
                          <div v-for="m in menus" :key="m.id">
                              <div v-if="item.menu_id === m.id">{{m.name}}</div>
                          </div>
                      </td>
                      <td>{{item.clinic_id}}</td>
                      <td>{{item.description}}</td>
                      <td>{{item.start_time}}</td>
                      <td>{{item.end_time}}</td>
                      <td>
                          <a href="#" @click="editModal(item)"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                          <a href="#" @click="deleteData(item.id)"><i class="fa fa-trash"></i></a>
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
        <div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <!-- radio -->
                    <div class="form-group" style="display: flex">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="0" value="0" name="is_new" checked v-model="form.is_new">
                            <label for="0" class="custom-control-label">NEW</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="1" value="1" name="is_new" v-model="form.is_new">
                            <label for="1" class="custom-control-label">VIP</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>PhoneNumber</label>
                        <input v-model="form.phone_number" type="text" name="phone_number" class="form-control" :class="{'is-invalid':form.errors.has('phone_number')}" placeholder="PhoneNumber">
                        <has-error :form="form" field="phone_number"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Order_ID</label>
                        <input v-model="form.order_id" type="text" name="order_id" class="form-control" :class="{'is-invalid':form.errors.has('order_id')}" placeholder="Order_ID">
                        <has-error :form="form" field="order_id"></has-error>
                    </div>
                    <div class="form-group">
                        <label>staff</label>
                        <select v-model="form.staff_id" class="custom-select">
                          <option v-for="staff in operators" :key="staff.id" v-bind:value="staff.id">{{ staff.full_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>counselor</label>
                        <select v-model="form.counselor_id" class="custom-select">
                          <option v-for="c in counselors" :key="c.id" v-bind:value="c.id">{{ c.full_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>menu</label>
                        <select v-model="form.menu_id" class="custom-select">
                          <option v-for="m in menus" :key="m.id" v-bind:value="m.id">{{ m.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>clinic</label>
                        <select v-model="form.clinic_id" class="custom-select">
                          <option v-for="c in clinics" :key="c.id" v-bind:value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>description</label>
                        <input v-model="form.description" type="text" name="description" class="form-control" :class="{'is-invalid':form.errors.has('description')}" placeholder="description">
                        <has-error :form="form" field="description"></has-error>
                    </div>
                    <div class="form-group">
                        <h6>
                            StartTime:
                        </h6>
                        <datetime
                            format="YYYY-MM-DD H:i"
                            v-model="form.start_time"
                            placeholder="starttime"
                            name="start_time"
                            :class="{
                                'is-invalid': form.errors.has(
                                    'start_time'
                                )
                            }"
                        ></datetime>
                        <has-error
                            :form="form"
                            field="start_time"
                        ></has-error>
                    </div>
                    <div class="form-group">
                        <h6>
                            EndTime:
                        </h6>
                        <datetime
                            format="YYYY-MM-DD H:i"
                            v-model="form.end_time"
                            placeholder="endtime"
                            name="end_time"
                            :class="{
                                'is-invalid': form.errors.has(
                                    'end_time'
                                )
                            }"
                        ></datetime>
                        <has-error
                            :form="form"
                            field="end_time"
                        ></has-error>
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
    import datetime from "vuejs-datetimepicker";
    export default {
        components: { datetime },
        data() {
            return {
                data: {},
                operators: {},
                counselors: {},
                menus: {},
                clinics: {},
                form: new Form({
                    id : 0,
                    is_new : 0,
                    phone_number : '',
                    order_id : 0,
                    staff_id : 0,
                    counselor_id : 0,
                    menu_id : 0,
                    clinic_id : 0,
                    description : '',
                    start_time: "",
                    end_time: "",
                }),
                editMode: false
            }
        },
        methods: {
            loadList(){
                axios.get('/api/order').
                    then(({data}) => (this.data = data.data));
                axios.get('/v1/staff/operators').
                    then(({data}) => (this.operators = data));
                axios.get('/v1/staff/counselors').
                    then(({data}) => (this.counselors = data));
                axios.get('/api/menu').
                    then(({data}) => (this.menus = data.data));
                axios.get('/api/clinic').
                    then(({data}) => (this.clinics = data));
            },
            createData(){ 
                console.log(this.form.is_new, this.form.phone_number, this.form.clinic_id);
                
                this.form.post('/api/order')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "データ追加しました"
                        });
                        $('#modalAddItem').modal('hide');
                        this.loadList();
                    })
                    .catch(()=>{
                        toast.fire({
                            icon: "failed",
                            title: "保存に失敗しました!"
                        });
                    });         
            },
            updateRank(){
                this.form.put('/api/order/' + this.form.id)
                    .then(()=>{
                        toast.fire({
                                icon: "success",
                                title: "更新成功!"
                            });
                            $('#modalAddItem').modal('hide');
                            this.loadList();

                    })
                    .catch(()=>{

                    });
            },
            deleteData(id){
                this.form.delete('/api/order/' + id)
                    .then((result)=>{
                        //if (result.message){
                            toast.fire({
                                icon: "success",
                                title: "成功を削除!"
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
                this.form.errors.clear();                
                $('#modalAddItem').modal('show');
            },
            editModal(data){
                this.editMode = true;
                this.form.errors.clear();
                this.form.fill(data);
                $('#modalAddItem').modal('show');
            }
        },
        created() {
            this.loadList();
        }
    }
</script>
