<template>
    <div class="row">
          <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (メニュー管理)</h3></div></div>
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
                      <th>メニューID</th>
                      <th>コード</th>
                      <th>メニュー名 </th>
                      <th>ランク名</th>                      
                      <th>料金</th>
                      <th>税率</th>
                      <th>運用開始日</th>
                      <th>運用終了日</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(d, index) in data" :key="d.id">
                      <td>{{ index+1 }}</td>
                      <td>{{ d.code }}</td>
                      <td>{{ d.name }}</td>
                      <td>
                          <div v-for="r in ranks" :key="r.id">
                              <div v-if="d.rank_id == r.id">{{r.name}}</div>
                          </div>
                      </td>
                      <td>¥{{ d.amount}}</td>
                      <td>
                          <div v-for="t in taxs" :key="t.id">
                              <div v-if="d.tax_id == t.id">{{t.name}}</div>
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
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">メニュー更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">メニュー追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateRank() : createData()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>メニュー名</label>
                        <input v-model="form.name" type="text" name="name" class="form-control" :class="{'is-invalid':form.errors.has('name')}" placeholder="メニュー名">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label>コード</label>
                        <input v-model="form.code" type="text" name="code" class="form-control" :class="{'is-invalid':form.errors.has('code')}" placeholder="コード">
                        <has-error :form="form" field="code"></has-error>
                    </div>
                    <div class="form-group">
                        <label>ランク名</label>
                        <select v-model="form.rank_id" class="custom-select">
                          <option v-for="r in ranks" :key="r.id" v-bind:value="r.id">{{ r.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>料金</label>
                        <input v-model="form.amount" type="number" name="amount" class="form-control" :class="{'is-invalid':form.errors.has('amount')}" placeholder="料金">
                        <has-error :form="form" field="amount"></has-error>
                    </div>
                    <div class="form-group">
                        <label>税率</label>
                        <select v-model="form.tax_id" class="custom-select">                           
                          <option v-for="t in taxs" :key="t.id" v-bind:value="t.id">{{ t.name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h6>運用開始日</h6>                        
                        <v-date-picker
                            locale="ja"
                            mode='single'
                            tint-color='#f142f4'
                            v-model='form.start_time'                            
                            is-double-paned                                 
                            ref="calendar1"                            
                        >
                        </v-date-picker>
                        <has-error
                            :form="form"
                            field="start_time"
                        ></has-error>
                    </div>
                    <div class="form-group">
                        <h6>運用終了日</h6>
                        <v-date-picker
                            locale="ja"
                            mode='single'
                            tint-color='#f142f4'
                            v-model='form.end_time'
                            is-double-paned
                            ref="calendar2"
                        >
                        </v-date-picker>
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
    import VCalendar from 'v-calendar';
    export default {
        components: { VCalendar, },
        data() {
            return {
                data: {},
                ranks:{},
                taxs: {},
                form: new Form({
                    id : '',
                    name : '',
                    rank_id : '',
                    amount: 0,
                    tax_id : '',                    
                    start_time: '',
                    end_time: '',
                    code: '',
                    is_deleted: false
                }),
                editMode: false,
                keyword: '',
            }
        },
        methods: {
            searchit(){
                axios.get('/api/menu?keyword=' + this.keyword).
                    then(({data}) => {
                        this.data = data.data
                        console.log(this.data)
                        });
            },
            loadList(){
                axios.get('/api/menu').
                    then(({data}) => {
                        this.data = data.data
                        console.log(this.data)
                        });

                axios.get('/api/rank').
                    then(({data}) => (this.ranks = data));

                axios.get('/api/tax').
                    then(({data}) => (this.taxs = data));                    
            },
            createData(){                
                this.form.post('/api/menu')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "A menu was created successfully."
                        });
                        $('#modalAddItem').modal('hide');
                        this.loadList();
                    })
                    .catch(()=>{

                    });         
            },
            updateRank(){
                this.form.put('/api/menu/' + this.form.id)
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
                this.form.delete('/api/menu/' + id)
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
