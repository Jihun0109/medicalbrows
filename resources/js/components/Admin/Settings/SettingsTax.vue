<template>
    <div class="row">
        <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (税率管理)</h3></div></div>
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
                        <button class="btn btn-success" @click="newModal">
                            追加
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>税率ID</th>
                                <th>税率名</th>
                                <th>税率</th>
                                <th>運用開始日</th>
                                <th>運用終了日</th>
                                <th>編集する</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.amount | percentageSign}}</td>
                                <td>{{ item.start_time }}</td>
                                <td>{{ item.end_time }}</td>
                                <td>
                                    <a href="#" @click="editModal(item)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="#" @click="deleteItem(item.id)">
                                        <i class="fa fa-trash"></i>
                                    </a>
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
        <div
            class="modal fade"
            id="modalAddItem"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            v-show="editMode"
                            class="modal-title"
                            id="exampleModalLabel"
                        >
                            税率更新
                        </h5>
                        <h5
                            v-show="!editMode"
                            class="modal-title"
                            id="exampleModalLabel"
                        >
                            税率追加
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form
                        @submit.prevent="editMode ? updateItem() : createItem()"
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <label>
                                    税率名
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('name')
                                    }"
                                    placeholder="税率名"
                                />
                                <div v-if="form.errors.has('name')" class="invalid-feedback">{{errormsg(form.errors.get('name'),"name","税率名")}}</div>
                            </div>
                            <label>税率</label>
                            <div class="input-group">                            
                                <input
                                    v-model="form.amount"
                                    type="text"
                                    name="amount"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('amount')
                                    }"
                                    placeholder="税率"
                                />
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div v-if="form.errors.has('amount')" class="invalid-feedback">{{errormsg(form.errors.get('amount'),"amount","税率")}}</div>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label>
                                    運用開始日
                                </label>
                                <v-date-picker
                                    locale="ja"
                                    mode='single'
                                    v-model='form.start_time'                            
                                    :attributes='attrs'
                                    ref="calendar1"
                                    :popover="{ placement: 'bottom', visibility: 'click' }"
                                    :class="{'is-invalid':form.errors.has('start_time')}"
                                >
                                    <input type="text" slot-scope='props' :value='props.inputValue' class="form-control" :class="{'is-invalid':form.errors.has('start_time')}">
                                </v-date-picker>
                                <div v-if="form.errors.has('start_time')" class="invalid-feedback">{{errormsg(form.errors.get('start_time'),"start time","運用開始日")}}</div>
                            </div>
                            <div class="form-group">
                                <label>
                                    運用終了日
                                </label>
                                <v-date-picker
                                    locale="ja"
                                    mode='single'
                                    v-model='form.end_time'
                                    is-double-paned
                                    :attributes='attrs'
                                    ref="calendar2"
                                    :popover="{ placement: 'bottom', visibility: 'click' }"
                                    :class="{'is-invalid':form.errors.has('end_time')}"
                                >
                                    <input type="text" slot-scope='props' :value='props.inputValue' class="form-control" :class="{'is-invalid':form.errors.has('end_time')}">
                                </v-date-picker>
                                <div v-if="form.errors.has('end_time')" class="invalid-feedback">{{errormsg(form.errors.get('end_time'),"end time","運用終了日")}}</div>            
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                キャンセル
                            </button>
                            <button
                                v-show="editMode"
                                type="submit"
                                class="btn btn-success"
                            >
                                更新
                            </button>
                            <button
                                v-show="!editMode"
                                type="submit"
                                class="btn btn-primary"
                            >
                                追加
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VCalendar from 'v-calendar';
var moment = require('moment');
export default {
    components: { VCalendar, },
    data() {
        return {
            items: {},
            form: new Form({
                id: "",
                name: "",
                amount: 0,
                start_time: "",
                end_time: "",
                is_deleted: false
            }),
            editMode: false,
            keyword: '',
            attrs: [
                {
                    dot: {
                        class: 'high-light'
                    },
                    dates: new Date()
                }
            ],   
        };
    },
    methods: {
        errormsg(msg,attribute,jpstr){
                return msg.replace(attribute,jpstr);
        },
        searchit(){
            axios.get("/api/tax?keyword=" + this.keyword).then(({ data }) => (this.items = data));
        },
        loadItems() {
            axios.get("/api/tax").then(({ data }) => (this.items = data));
        },
        createItem() {
            this.form.start_time = moment(this.form.start_time).format("YYYY-MM-DD");
            this.form.end_time = this.form.end_time?moment(this.form.end_time).format("YYYY-MM-DD"):null;
            this.form
                .post("/api/tax")
                .then(result => {
                    toast.fire({
                        icon: "success",
                        title: "データ追加しました"
                    });
                    $("#modalAddItem").modal("hide");
                    this.loadItems();
                })
                .catch(() => {})
                .finally(()=> {
                    this.form.start_time = new Date(this.form.start_time);
                    this.form.end_time = this.form.end_time?new Date(this.form.end_time):null;
                });
        },
        updateItem() {
            this.form.start_time = moment(this.form.start_time).format("YYYY-MM-DD");
            this.form.end_time = this.form.end_time?moment(this.form.end_time).format("YYYY-MM-DD"):null;

            this.form
                .put("/api/tax/" + this.form.id)
                .then(() => {
                    toast.fire({
                        icon: "success",
                        title: "更新成功!"
                    });
                    $("#modalAddItem").modal("hide");
                    this.loadItems();
                })
                .catch(() => {})
                .finally(()=> {
                    this.form.start_time = new Date(this.form.start_time);
                    this.form.end_time = this.form.end_time?new Date(this.form.end_time):null;
                });
        },
        deleteItem(id) {
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
                    _this.form.delete('/api/tax/' + id)
                        .then((result)=>{
                            //if (result.message){
                                toast.fire({
                                    icon: "success",
                                    title: "削除しました。"
                                });
                                _this.loadItems();
                            //}
                        })
                        .catch(()=>{

                        });
                }
            })                      
        },
        newModal() {
            this.editMode = false;
            this.form.reset();
            this.form.errors.clear();
            $("#modalAddItem").modal("show");
        },
        editModal(item) {
            console.log(item);
            this.editMode = true;
            this.form.errors.clear();
            this.form.fill(item);
            this.form.start_time = new Date(this.form.start_time);
            this.form.end_time = this.form.end_time?new Date(this.form.end_time):null;
            
            console.log(this.form.end_time);
            $("#modalAddItem").modal("show");
        }        
    },
    created() {
        this.loadItems();
    }
};
</script>
