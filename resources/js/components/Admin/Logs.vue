<template>
  <div class="row">
    <div class="row d-flex justify-content-center" style="width:100%">
      <div>
        <h3 class>予約管理システム (ログ管理)</h3>
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

          <!-- <div class="card-tools">
                  <button class="btn btn-success" @click="newModal">追加 <i class="fa fa-plus"></i></button>
          </div>-->
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ログID</th>
                <th>ログ</th>
                <th>日付</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(log, index) in items" :key="log.id">
                <td>{{index + 1}}</td>
                <td>{{log.log}}</td>
                <td>{{log.created_at}}</td>
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
      id="modalAddLog"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">ログ更新</h5>
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">ログ追加</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? updateLog() : createLog()">
            <div class="modal-body">
              <div class="form-group">
                <label>ログ</label>
                <input
                  v-model="form.name"
                  type="text"
                  name="name"
                  class="form-control"
                  :class="{'is-invalid':form.errors.has('name')}"
                  placeholder="ランク名"
                />
                <div
                  v-if="form.errors.has('name')"
                  class="invalid-feedback"
                >{{errormsg(form.errors.get('name'),"name","ランク名")}}</div>
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
      items: {},
      form: new Form({
        id: "",
        name: ""
      }),
      editMode: false,
      keyword: ""
    };
  },
  methods: {
    errormsg(msg, attribute, jpstr) {
      return msg.replace(attribute, jpstr);
    },
    searchit() {
      axios
        .get("/v1/logs?keyword=" + this.keyword)
        .then(({ data }) => (this.items = data));
    }
  },
  created() {
    this.searchit();
  }
};
</script>
