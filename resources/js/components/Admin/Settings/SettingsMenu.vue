<template>
  <div class="row">
    <div class="row d-flex justify-content-center" style="width:100%">
      <div>
        <h3 class>予約管理システム (メニュー管理)</h3>
      </div>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <div class="input-group input-group-md">
              <input
                type="text"
                class="form-control"
                placeholder="検索したい文字列を入力"
                v-model="keyword"
                @keyup.enter="searchit"
              />
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
                <th>メニューID</th>
                <th>コード</th>
                <th>メニュー名</th>
                <th>ランク名</th>
                <th>料金</th>
                <th>税率</th>
                <th>運用開始日</th>
                <th>運用終了日</th>
                <th>編集する</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="d in data" :key="d.id">
                <td>{{ d.id }}</td>
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
                  <a href="#" @click="editModal(d)">
                    <i class="fa fa-edit"></i>
                  </a> &nbsp;&nbsp;
                  <a href="#" @click="deleteData(d.id)">
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
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('name')}"
                  placeholder="メニュー名"
                />
                <div
                  v-if="form.errors.has('name')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('name'),"name","メニュー名")}}</div>
              </div>
              <div class="form-group">
                <label>コード</label>
                <input
                  v-model="form.code"
                  type="text"
                  name="code"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('code')}"
                  placeholder="コード"
                />
                <div
                  v-if="form.errors.has('code')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('code'),"code","コード")}}</div>
              </div>
              <div class="form-group">
                <label>ランク名</label>
                <select
                  v-model="form.rank_id"
                  class="custom-select"
                  name="rank_id"
                  :class="{'is-invalid':form.errors.has('rank_id')}"
                >
                  <option v-for="r in ranks" :key="r.id" v-bind:value="r.id">{{ r.name }}</option>
                </select>
                <div
                  v-if="form.errors.has('rank_id')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('rank_id'),"rank id","ランク名")}}</div>
              </div>
              <div class="form-group">
                <label>料金</label>
                <input
                  v-model="form.amount"
                  type="number"
                  name="amount"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('amount')}"
                  placeholder="料金"
                />
                <div
                  v-if="form.errors.has('amount')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('amount'),"amount","料金")}}</div>
              </div>
              <div class="form-group">
                <label>税率</label>
                <select
                  v-model="form.tax_id"
                  class="custom-select"
                  :class="{'is-invalid':form.errors.has('tax_id')}"
                >
                  <option v-for="t in taxs" :key="t.id" v-bind:value="t.id">{{ t.name }}</option>
                </select>
                <div
                  v-if="form.errors.has('tax_id')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('tax_id'),"tax id","税率")}}</div>
              </div>
              <div class="form-group">
                <h6>運用開始日</h6>
                <v-date-picker
                  locale="ja"
                  mode="single"
                  v-model="form.start_time"
                  :attributes="attrs"
                  ref="calendar1"
                  :popover="{ placement: 'bottom', visibility: 'click' }"
                  :class="{'is-invalid':form.errors.has('start_time')}"
                >
                  <input
                    type="text"
                    slot-scope="props"
                    :value="props.inputValue"
                    class="form-control"
                    :class="{'is-invalid':form.errors.has('start_time')}"
                  />
                </v-date-picker>
                <div
                  v-if="form.errors.has('start_time')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('start_time'),"start time","運用開始日")}}</div>
              </div>
              <div class="form-group">
                <h6>運用終了日</h6>
                <v-date-picker
                  locale="ja"
                  mode="single"
                  v-model="form.end_time"
                  is-double-paned
                  :attributes="attrs"
                  ref="calendar2"
                  :popover="{ placement: 'bottom', visibility: 'click' }"
                  :class="{'is-invalid':form.errors.has('end_time')}"
                >
                  <input
                    type="text"
                    slot-scope="props"
                    :value="props.inputValue"
                    class="form-control"
                    :class="{'is-invalid':form.errors.has('end_time')}"
                    @input="handleInput"
                  />
                </v-date-picker>
                <div
                  v-if="form.errors.has('end_time')"
                  class="invalid-feedback"
                >運用終了日には、運用開始日より後の日付を指定してください。</div>
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
import VCalendar from "v-calendar";
var moment = require("moment");
export default {
  components: { VCalendar },
  data() {
    return {
      data: {},
      ranks: {},
      taxs: {},
      form: new Form({
        id: "",
        name: "",
        rank_id: "",
        amount: 0,
        tax_id: "",
        start_time: "",
        end_time: "",
        code: "",
        is_deleted: false
      }),
      editMode: false,
      keyword: "",
      attrs: [
        {
          dot: {
            class: "high-light"
          },
          dates: new Date()
        }
      ]
    };
  },
  methods: {
    handleInput(e) {
      if (e.target.value == undefined || e.target.value === "")
        this.form.end_time = "";
      // this.$emit('input', this.selectedDates);
      // this.$emit('changed');
    },
    errormsg(msg, attribute, jpstr) {
      return msg.replace(attribute, jpstr);
    },
    searchit() {
      axios.get("/api/menu?keyword=" + this.keyword).then(({ data }) => {
        this.data = data;
      });
    },
    loadList() {
      axios.get("/api/menu").then(({ data }) => {
        this.data = data;
      });

      axios.get("/api/rank").then(({ data }) => (this.ranks = data));

      axios.get("/api/tax").then(({ data }) => (this.taxs = data));
    },
    createData() {
      this.form.start_time = moment(this.form.start_time).format("YYYY-MM-DD");
      this.form.end_time = this.form.end_time
        ? moment(this.form.end_time).format("YYYY-MM-DD")
        : null;
      this.form
        .post("/api/menu")
        .then(result => {
          toast.fire({
            icon: "success",
            title: "データ追加しました"
          });
          $("#modalAddItem").modal("hide");
          this.loadList();
        })
        .catch(() => {})
        .finally(() => {
          this.form.start_time = new Date(this.form.start_time);
          this.form.end_time = this.form.end_time
            ? new Date(this.form.end_time)
            : null;
        });
    },
    updateRank() {
      this.form.start_time = moment(this.form.start_time).format("YYYY-MM-DD");
      this.form.end_time = this.form.end_time
        ? moment(this.form.end_time).format("YYYY-MM-DD")
        : null;
      this.form
        .put("/api/menu/" + this.form.id)
        .then(() => {
          toast.fire({
            icon: "success",
            title: "更新成功!"
          });
          $("#modalAddItem").modal("hide");
          this.loadList();
        })
        .catch(() => {})
        .finally(() => {
          this.form.start_time = new Date(this.form.start_time);
          this.form.end_time = this.form.end_time
            ? new Date(this.form.end_time)
            : null;
        });
    },
    deleteData(id) {
      let _this = this;
      swal
        .fire({
          //title: '本気ですか？',
          text: "データ削除しても宜しいですか？",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "実行",
          cancelButtonText: "キャンセル",
          reverseButtons: true
        })
        .then(function(isConfirm) {
          console.log(isConfirm);
          if (isConfirm.value == true) {
            _this.form
              .delete("/api/menu/" + id)
              .then(result => {
                //if (result.message){
                toast.fire({
                  icon: "success",
                  title: "削除しました。"
                });
                _this.loadList();
                //}
              })
              .catch(() => {});
          }
        });
    },
    newModal() {
      this.editMode = false;
      this.form.reset();
      this.form.errors.clear();
      $("#modalAddItem").modal("show");
    },
    editModal(data) {
      this.editMode = true;
      this.form.errors.clear();
      this.form.fill(data);
      this.form.start_time = new Date(this.form.start_time);
      this.form.end_time = this.form.end_time
        ? new Date(this.form.end_time)
        : null;
      $("#modalAddItem").modal("show");
    }
  },
  created() {
    this.loadList();
  }
};
</script>

<style lang="scss">
.high-light {
  background-color: #ff6666;
  border-radius: 50% !important;
  // border-width: 1px;
  // border-style: solid;
  // opacity: 0.8;
}
</style>
