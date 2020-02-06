<template>
    <div class="row">
        <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (税率管理)</h3></div></div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
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
                            <tr v-for="(item, index) in items" :key="item.id">
                                <td>{{ index + 1 }}</td>
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
                                <has-error
                                    :form="form"
                                    field="name"
                                ></has-error>
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
                                <has-error
                                    :form="form"
                                    field="amount"
                                ></has-error>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label>
                                    運用開始日
                                </label>
                                <datetime
                                    format="YYYY-MM-DD"
                                    v-model="form.start_time"
                                    placeholder="適用開始日"
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
                                <label>
                                    運用終了日
                                </label>
                                <datetime
                                    format="YYYY-MM-DD"
                                    v-model="form.end_time"
                                    placeholder="適用終了日"
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
import datetime from "vuejs-datetimepicker";
export default {
    components: { datetime },
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
            editMode: false
        };
    },
    methods: {
        loadItems() {
            axios.get("api/tax").then(({ data }) => (this.items = data));
        },
        createItem() {
            this.form
                .post("api/tax")
                .then(result => {
                    toast.fire({
                        icon: "success",
                        title: "New Tax rate Created!"
                    });
                    $("#modalAddItem").modal("hide");
                    this.loadItems();
                })
                .catch(() => {});
        },
        updateItem() {
            this.form
                .put("api/tax/" + this.form.id)
                .then(() => {
                    toast.fire({
                        icon: "success",
                        title: "Updated successfully!"
                    });
                    $("#modalAddItem").modal("hide");
                    this.loadItems();
                })
                .catch(() => {});
        },
        deleteItem(id) {
            this.form
                .delete("api/tax/" + id)
                .then(result => {
                    //if (result.message){
                    toast.fire({
                        icon: "success",
                        title: "Deleted successfully!"
                    });
                    this.loadItems();
                    //}
                })
                .catch(() => {});
        },
        newModal() {
            this.editMode = false;
            this.form.reset();
            $("#modalAddItem").modal("show");
        },
        editModal(item) {
            this.editMode = true;
            this.form.fill(item);
            $("#modalAddItem").modal("show");
        }
    },
    created() {
        this.loadItems();
    }
};
</script>
