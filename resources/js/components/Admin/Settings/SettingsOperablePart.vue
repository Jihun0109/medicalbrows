<template>
    <div class="row">
        <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (施術可能部位管理)</h3></div></div>
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
                      <th>施術可能部位ID</th>
                      <th>施術可能部位</th>
                      <th>編集する</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(part, index) in parts" :key="part.id">
                      <td>{{index + 1}}</td>
                      <td>{{part.name}}</td>
                      <td>
                          <a href="#" @click="editModal(part)"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                          <a href="#" @click="deletedPart(part.id)"><i class="fa fa-trash"></i></a>
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
        <div class="modal fade" id="modalAddPart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">施術可能部位更新</h5>
                    <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">施術可能部位追加</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updatePart() : createPart()">
                <div class="modal-body">
                    <div class="form-group">
                        <label>	施術可能部位</label>
                        <input v-model="form.name" type="text" name="name" class="form-control" :class="{'is-invalid':form.errors.has('name')}" placeholder="ランク名">
                        <has-error :form="form" field="name"></has-error>
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
                parts: {},
                form: new Form({
                    id : '',
                    name : '',
                }),
                editMode: false,
                keyword: ""
            }
        },
        methods: {
            searchit(){
                this.loadParts();
            },
            loadParts(){
                axios.get('/api/operable-part?keyword=' + this.keyword).
                    then(({data}) => (this.parts = data));
            },
            createPart(){
                this.form.post('/api/operable-part')
                    .then((result)=>{                        
                        toast.fire({
                            icon: "success",
                            title: "正しく保存!"
                        });
                        $('#modalAddPart').modal('hide');
                        this.loadParts();
                    })
                    .catch(()=>{

                    });         
            },
            updatePart(){
                this.form.put('/api/operable-part/' + this.form.id)
                    .then(()=>{
                        toast.fire({
                                icon: "success",
                                title: "更新成功"
                            });
                            $('#modalAddPart').modal('hide');
                            this.loadParts();

                    })
                    .catch(()=>{

                    });
            },
            deletedPart(id){
                let _this = this;
                swal.fire({
                    title: '本気ですか？',
                    text: "本当にアイテムを削除しますか？",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'はい',
                    cancelButtonText: 'いいえ',
                    reverseButtons: true
                    }).then(function(isConfirm) {
                        console.log(isConfirm);
                    if (isConfirm.value == true) {
                        _this.form.delete('/api/operable-part/' + id)
                            .then((result)=>{
                                //if (result.message){
                                    toast.fire({
                                        icon: "success",
                                        title: "削除しました。"
                                    });
                                    _this.loadParts();
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
                $('#modalAddPart').modal('show');
            },
            editModal(part){
                this.editMode = true;
                this.form.fill(part);
                $('#modalAddPart').modal('show');
            }
        },
        created() {
            this.loadParts();
        }
    }
</script>
