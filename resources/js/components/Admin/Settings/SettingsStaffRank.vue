<template>
  <div class="row">
    <div class="row d-flex justify-content-center" style="width:100%">
      <div>
        <h3 class>予約管理システム (スタッフスキル管理)</h3>
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
                <th>スタッフ ランクID</th>
                <th>ランク名</th>
                <th>スタッフ名</th>
                <th>表記名</th>
                <th>施術可能部位</th>
                <th>昇格日</th>
                <th>編集する</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="d in data" :key="d.unique_id">
                <td>{{ d.unique_id }}</td>
                <td>
                  <div v-for="r in ranks" :key="r.id">
                    <div v-if="d.rank_id == r.id">{{r.name}}</div>
                  </div>
                </td>
                <td>
                  <div v-for="s in staffs" :key="s.id">
                    <div v-if="d.staff_id == s.id">{{s.full_name}}</div>
                  </div>
                </td>
                <td>
                  <div v-for="s in staffs" :key="s.id">
                    <div v-if="d.staff_id == s.id">{{s.alias}}</div>
                  </div>
                </td>
                <!-- <td>
                  <div v-for="p in parts" :key="p.id">
                    <div v-if="d.part_id == p.id">{{p.name}}</div>
                  </div>
                </td>-->
                <td>
                  <span
                    v-for="p in d.parts"
                    :key="p['key']"
                    class="badge badge-primary p-1 mr-1"
                  >{{p['value']}}</span>
                </td>
                <td>{{ d.promo_date }}</td>
                <td>
                  <a href="#" @click="editModal(d)">
                    <i class="fa fa-edit"></i>
                  </a> &nbsp;&nbsp;
                  <a href="#" @click="deleteData(d.unique_id)">
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
      id="modalAddStaffRank"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">スタッフ ランク更新</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">スタッフ ランク追加</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? updateRank() : createData()">
            <div class="modal-body">
              <div class="form-group">
                <label>ランク名</label>
                <select
                  v-model="form.rank_id"
                  class="custom-select"
                  name="rank_id"
                  :class="{'is-invalid':form.errors.has('rank_id')}"
                >
                  <option
                    v-for="rank in ranks"
                    :key="rank.id"
                    v-bind:value="rank.id"
                  >{{ rank.name }}</option>
                </select>
                <div
                  v-if="form.errors.has('rank_id')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('rank_id'),"rank id","ランク名")}}</div>
              </div>
              <div class="form-group">
                <label>スタッフ表記名</label>
                <select
                  v-model="form.staff_id"
                  class="custom-select"
                  name="staff_id"
                  :class="{'is-invalid':form.errors.has('staff_id')}"
                >
                  <option
                    v-for="staff in staffs"
                    :key="staff.id"
                    v-bind:value="staff.id"
                  >{{ staff.alias }}</option>
                </select>
                <div
                  v-if="form.errors.has('staff_id')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('staff_id'),"staff id","スタッフ表記名")}}</div>
              </div>
              <div class="form-group">
                <label>施術可能部位</label>
                <tags-input
                  element-id="tags"
                  v-model="selectedTags"
                  :existing-tags="existingTags"
                  :typeahead="true"
                  placeholder="施術可能部位"
                  :only-existing-tags="true"
                  :typeahead-hide-discard="true"
                  :typeahead-show-on-focus="true"
                  :typeahead-style="'dropdown'"
                  @tags-updated="updateTags"
                  :typeahead-activation-threshold="0"
                  :class="{'is-invalid':form.errors.has('staff_id')}"
                ></tags-input>
                <div
                  v-if="form.errors.has('parts')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('parts'),"parts","施術可能部位")}}</div>
              </div>
              <div class="form-group">
                <label>昇格日</label>
                <v-date-picker
                  locale="ja"
                  mode="single"
                  v-model="form.promo_date"
                  is-double-paned
                  :attributes="attrs"
                  ref="calendar1"
                  :popover="{ placement: 'bottom', visibility: 'click' }"
                >
                  <input
                    type="text"
                    slot-scope="props"
                    :value="props.inputValue"
                    class="form-control"
                  />
                </v-date-picker>
                <!-- <datetime format="YYYY-MM-DD" v-model="form.promo_date" placeholder="昇格日" name="promo_date" :class="{'is-invalid': form.errors.has('promo_date')}"></datetime> -->
                <div
                  v-if="form.errors.has('promo_date')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('promo_date'),"promo date","昇格日")}}</div>
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
export default {
  components: { VCalendar },
  data() {
    return {
      data: {},
      ranks: {},
      staffs: {},
      parts: {},
      form: new Form({
        id: "",
        rank_id: "",
        staff_id: "",
        parts: "",
        promo_date: "",
        is_deleted: 0,
        unique_id: ""
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
      ],
      selectedTags: [],
      existingTags: []
    };
  },
  methods: {
    updateTags() {
      this.form.parts = this.selectedTags;
    },
    errormsg(msg, attribute, jpstr) {
      return msg.replace(attribute, jpstr);
    },
    searchit() {
      axios
        .get("/api/staff-rank?keyword=" + this.keyword)
        .then(({ data }) => (this.data = data));
    },
    loadList() {
      axios
        .get("/api/staff-rank?keyword=" + this.keyword)
        .then(({ data }) => (this.data = data));
      axios.get("/api/rank").then(({ data }) => (this.ranks = data));
      axios.get("/api/staff").then(({ data }) => (this.staffs = data));
      axios.get("/api/operable-part").then(({ data }) => {
        this.existingTags = [];
        for (let i = 0; i < data.length; i++) {
          this.existingTags.push({ key: data[i].id, value: data[i]["name"] });
        }
      });
    },
    createData() {
      this.form.promo_date = this.utcToLocalTime(this.form.promo_date);
      this.form
        .post("/api/staff-rank")
        .then(result => {
          toast.fire({
            icon: "success",
            title: "データ追加しました"
          });
          $("#modalAddStaffRank").modal("hide");
          this.loadList();
        })
        .catch(error => {
          if (error.response.data.errors.parts) {
            $(".tags-input-wrapper-default").addClass("is-invalid");
          }
        });
    },
    updateRank() {
      this.form.promo_date = this.utcToLocalTime(this.form.promo_date);
      this.form
        .put("/api/staff-rank/" + this.form.unique_id)
        .then(() => {
          toast.fire({
            icon: "success",
            title: "更新成功!"
          });
          $("#modalAddStaffRank").modal("hide");
          this.loadList();
        })
        .catch(error => {
          if (error.response.data.errors.parts) {
            $(".tags-input-wrapper-default").addClass("is-invalid");
          }
        });
    },
    deleteData(unique_id) {
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
          if (isConfirm.value == true) {
            _this.form
              .delete("/api/staff-rank/" + unique_id)
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
      this.form.promo_date = new Date();
      this.selectedTags = [];
      $("#modalAddStaffRank").modal("show");
      $(".tags-input-wrapper-default").removeClass("is-invalid");
    },
    editModal(data) {
      this.editMode = true;
      this.form.errors.clear();
      this.form.fill(data);
      this.form.promo_date = new Date(this.form.promo_date);
      this.form.password = "";
      this.selectedTags = data["parts"];
      $("#modalAddStaffRank").modal("show");
      $(".tags-input-wrapper-default").removeClass("is-invalid");
    },
    utcToLocalTime(date) {
      var userTimezoneOffset = new Date().getTimezoneOffset() * 60000;
      return new Date(date.getTime() - userTimezoneOffset);
    }
  },
  created() {
    this.loadList();
  }
};
</script>

<style lang="scss">
.is-invalid {
  border-color: #e3342f;
}
</style>