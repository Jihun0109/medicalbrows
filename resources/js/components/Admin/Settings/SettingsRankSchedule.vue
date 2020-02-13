<template>
    <div class="row">
          <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (ランクスケジュール管理)</h3></div></div>
          <div class="col-12">              
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"></h3>

                <div class="card-tools">
                  <button class="btn btn-success" @click="newModal">追加 <i class="fa fa-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ランクスケジュールID</th>
                      <th>ランク名</th>                      
                      <th>部位</th>
                      <th>開始時間</th>
                      <th>終了時間</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(d, index) in data" :key="d.id">
                      <td>{{ index+1 }}</td>
                      <td>
                          <div v-for="r in ranks" :key="r.id">
                              <div v-if="d.rank_id == r.id">{{r.name}}</div>
                          </div>
                      </td>
                      <td>
                          <div v-for="p in parts" :key="p.id">
                              <div v-if="d.part_id == p.id">{{p.name}}</div>
                          </div>
                      </td>
                      <td>{{ d.start_time}}</td>
                      <td>{{ d.end_time}}</td>                      
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
        <div class="modal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">ランクスケジュール更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">ランクスケジュール追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateRank() : createData()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>ランク名</label>
                        <select v-model="form.rank_id" class="custom-select">
                          <option v-for="r in ranks" :key="r.id" v-bind:value="r.id">{{ r.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>部位</label>
                        <select v-model="form.part_id" class="custom-select">                           
                          <option v-for="t in parts" :key="t.id" v-bind:value="t.id">{{ t.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h6>開始時間</h6>
                        <datetime
                            format="H:i"
                            v-model="form.start_time"
                            placeholder="開始時間"
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
                        <h6>終了時間</h6>
                        <datetime
                            format="H:i"
                            v-model="form.end_time"
                            placeholder="終了時間"
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
                ranks:{},
                parts:[{'id':0,'name':'アイブロウ'}],
                form: new Form({
                    id : '',
                    rank_id : '',
                    part_id : '',         
                    start_time: '',
                    end_time: '',
                    is_deleted: false
                }),
                editMode: false
            }
        },
        methods: {
            loadList(){
                axios.get('/api/rank-schedule').
                    then(({data}) => {
                        this.data = data;
                        console.log(this.data)
                        });

                axios.get('/api/rank').
                    then(({data}) => (this.ranks = data));
               
            },
            createData(){                
                this.form.post('/api/rank-schedule')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "A Rank Schedule was created successfully."
                        });
                        $('#modalAddItem').modal('hide');
                        this.loadList();
                    })
                    .catch(()=>{

                    });         
            },
            updateRank(){
                this.form.put('/api/rank-schedule/' + this.form.id)
                    .then(()=>{
                        toast.fire({
                                icon: "success",
                                title: "Updated successfully!"
                            });
                            $('#modalAddItem').modal('hide');
                            this.loadList();

                    })
                    .catch(()=>{

                    });
            },
            deleteData(id){
                this.form.delete('/api/rank-schedule/' + id)
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
                $('#modalAddItem').modal('show');
            },
            editModal(data){
                this.editMode = true;
                this.form.fill(data);
                $('#modalAddItem').modal('show');
            }
        },
        created() {
            this.loadList();
        }
    }
</script>

