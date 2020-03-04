<template>
  <div class="container-fluid">
    <div class="row d-flex justify-content-center" style="width:100%">
      <div>
        <h3 class>予約管理システム (シフト管理)</h3>
      </div>
    </div>
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>

          <div class="card-tools"></div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="input-group mb-3"></div>

          <!-- /input-group -->
          <strong>クリニック</strong>
          <div class="input-group mb-3">
            <div class="el-row">
              <button
                v-for="c in clinics"
                :key="c.id"
                type="button"
                @click="clinicSelected(c)"
                class="el-button el-button--primary el-button--medium"
                :class="['tab-btn', { active: selected_clinic_id === c.id }]"
              >
                <span>{{c.name}}</span>
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  name="staff_type"
                  value="1"
                  v-model="staff_type"
                />
                <label class="form-check-label">施術者</label>
              </div>
              <div class="form-check form-check-inline">
                <input
                  class="form-check-input"
                  type="radio"
                  name="staff_type"
                  value="0"
                  v-model="staff_type"
                />
                <label class="form-check-label">カウンセラー</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-7 col-md-12">
              <vuetable
                ref="vuetable"
                :fields="fields"
                :api-mode="false"
                :data="staffs"
                :css="css.table"
                :row-class="onRowClass"
                @vuetable:cell-clicked="onRowCellCicked"
                @vuetable:checkbox-toggled="onCheckBoxClicked"
                :per-page="lengthOfStaffs"
              ></vuetable>
            </div>
            <div class="col-lg-5 col-md-12">
              <div class="row mb-3 d-flex justify-content-center">出勤日を選択してください</div>
              <div class="row d-flex justify-content-center">
                <v-date-picker
                  locale="ja"
                  mode="multiple"
                  tint-color="#f142f4"
                  v-model="selectedDate"
                  :theme-styles="themeStyles"
                  is-double-paned
                  is-inline
                  ref="calendar"
                  @update:from-page="pageChange"
                  :min-date="minDate"
                  :max-date="maxDate"
                  :attributes="attrs"
                  :masks="formats"
                  :popover="{ placement: 'bottom', visibility: 'click' }"
                ></v-date-picker>
              </div>

              <div class="row mt-3 d-flex justify-content-center">
                <button
                  class="btn btn-secondary mr-5"
                  @click="selectAllDate"
                >{{is_selected_all?'クリア':'全選択'}}</button>
                <button class="btn btn-success" @click="saveShift">保 存</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
</template>

<script>
import VCalendar from "v-calendar";
import Vuetable from "vuetable-2";

export default {
  methods: {
    selectAllDate() {
      function isInArrayForAdd(array, value) {
        return !!array.find(item => {
          return (
            moment(item).month() == moment(value).month() &&
            moment(item).year() == moment(value).year() &&
            moment(item).date() == moment(value).date()
          );
        });
      }
      function isInArrayForRemove(array, value) {
        return !!array.find(item => {
          return (
            moment(item).month() == moment(value).month() &&
            moment(item).year() == moment(value).year()
          );
        });
      }

      if (this.is_selected_all) {
        this.is_selected_all = false; // remove all
        //this.selectedDate = [];

        let tempArray = this.selectedDate;
        var i = tempArray.length;

        while (i--) {
          var it = moment(this.selectedDate[i]);
          if (
            this.selectedMonth["year"] === it.year() &&
            this.selectedMonth["month"] === it.month() + 1
          ) {
            tempArray.splice(i, 1);
          }
        }

        this.selectedData = tempArray;
      } else {
        // add all
        this.getAllDateInAMonth(
          this.selectedMonth["year"],
          this.selectedMonth["month"]
        ).forEach(day => {
          if (!isInArrayForAdd(this.selectedDate, day)) {
            this.selectedDate.push(day);
          }
        });
        this.is_selected_all = true;
        // this.selectedDate = this.reverseDateArray(
        //   [],
        //   this.selectedMonth["year"],
        //   this.selectedMonth["month"]
        // );
      }
    },
    saveShift() {
      let staffs = this.$refs.vuetable.selectedTo;
      if (this.selected_id == -1 && staffs.length == 0) {
        toast.fire({
          icon: "warning",
          title: "複数のスタッフを選択!"
        });
        return;
      }

      let payload = {
        month: this.selectedMonth,
        staffs: staffs.length > 0 ? staffs : [this.selected_id],
        mindate: this.minDate,
        maxdate: this.maxDate,
        dates: this.selectedDate
      };

      payload.dates = payload.dates.map(d => moment(d).format("YYYY-MM-DD"));
      axios.post("/v1/shift/update", payload).then(({ data }) => {
        if (data["result"] == "success") {
          toast.fire({
            icon: "success",
            title: "データ追加しました"
          });
          this.$refs.vuetable.selectedTo = [];
        } else {
          if ("TakenOrder" == data["message"]) {
            let title = "シフトを保存できませんでした";

            let text = "<p>";
            for (let i = 0; i < data["data"].length; i++) {
              text +=
                data["data"][i]["staff_name"] +
                " : " +
                data["data"][i]["date"] +
                "<br>";
            }
            text += "</p>";

            swal.fire({
              title: title,
              html: text,
              icon: "warning",
              confirmButtonColor: "#3085d6",
              confirmButtonText: "オーケー"
            });
          }
        }
      });
    },
    pageChange(page) {
      console.log(page, "pageChanged");
      this.selectedMonth = page;

      this.initClearButton();

      //this.selectedDate = [];
      // if (this.selected_id > -1)
      //   this.getShift(this.selected_id, page.year, page.month);
    },
    initClearButton() {
      for (let i = 0; i < this.selectedDate.length; i++) {
        if (this.selectedDate[i].getMonth() + 1 === this.selectedMonth.month) {
          this.is_selected_all = true;
          return;
        }
      }
      this.is_selected_all = false;
    },
    getShift(staff_id, year, month) {
      axios
        .post("/v1/shift/get", {
          staff_id: staff_id,
          year: year,
          month: month,
          mindate: moment(this.minDate).format("YYYY-MM-DD"),
          maxdate: moment(this.maxDate).format("YYYY-MM-DD")
        })
        .then(({ data }) => {
          let dates = data.map(d => moment(d).toDate());
          this.selectedDate = dates;
          this.initClearButton();
        });
    },
    onRowClass(dataItem, index) {
      return dataItem.id == this.selected_id ? "active" : "";
    },

    onRowCellCicked(data, cellInfo, mouseEventObj) {
      this.selected_id = data.id;

      if (this.selectedMonth && this.$refs.vuetable.selectedTo.length == 0)
        this.getShift(
          this.selected_id,
          this.selectedMonth.year,
          this.selectedMonth.month
        );
    },
    onCheckBoxClicked(flag, row) {
      console.log("check item: ");
      console.log(row);
    },
    loadClinicList() {
      axios.get("/api/clinic").then(({ data }) => {
        this.clinics = data;
        this.selected_clinic_id = this.clinics[0].id;
        // this.selected_clinic_name = this.clinics[0].name;
        this.loadStaffRanksList();
      });
    },
    clinicSelected(clinic) {
      this.selected_clinic_id = clinic.id;

      this.loadStaffRanksList();
    },
    loadStaffRanksList() {
      axios
        .get(
          "/v1/shift/clinic/" + this.selected_clinic_id + "/" + this.staff_type
        )
        .then(({ data }) => {
          this.staffs = data;
          this.lengthOfStaffs = data.length;
        });
    },
    getAllDateInAMonth(year, month) {
      let startDay = new Date(year, month - 1, 1); // Get first day of specified month
      // min date check because we could not select before day from today.
      if (year === moment().year() && month === moment().month() + 1)
        startDay = new Date();

      startDay.setHours(0, 0, 0, 0);

      let endDay = new Date(year, month, 0); // Get last day of specified month
      let allDays = [new Date(startDay)];

      // Fill all days of specified month to the "allDays" vairalbe.
      while (startDay < endDay) {
        allDays.push(startDay);
        var newDate = startDay.setDate(startDay.getDate() + 1);
        startDay = new Date(newDate);
      }

      return allDays;
    },
    reverseDateArray(arrDates, year, month, load = false) {
      console.log(year + "/" + month);
      console.log(arrDates);
      let startDay = new Date(year, month - 1, 1); // Get first day of specified month
      console.log(moment().year());
      console.log(moment().month());
      // min date check
      if (year === moment().year() && month === moment().month() + 1)
        startDay = new Date(moment().format("YYYY/MM/DD"));

      //
      if (load && arrDates.length == 0) return [];

      let endDay = new Date(year, month, 0); // Get last day of specified month
      let allDays = [new Date(startDay)];
      let restDays = [];

      // Fill all days of specified month to the "allDays" vairalbe.
      while (startDay < endDay) {
        allDays.push(startDay);
        var newDate = startDay.setDate(startDay.getDate() + 1);
        startDay = new Date(newDate);
      }

      if (arrDates.length == 0) {
        console.log("all days because length is zero..");
        return allDays;
      }
      function isInArray(array, value) {
        return !!array.find(item => {
          return item.getTime() == value.getTime();
        });
      }
      allDays.forEach(day => {
        if (!isInArray(arrDates, day)) {
          restDays.push(day);
        }
      });
      console.log(restDays);
      return restDays;
    }
  },
  components: {
    VCalendar,
    Vuetable
  },
  data() {
    return {
      formats: {
        title: "YYYY年 MMMM",
        weekdays: "W",
        navMonths: "MMM",
        input: ["YYYY年MM月DD日 (W)"],
        dayPopover: "YYYY-MM-DD",
        data: ["YYYY-MM-DD", "YYYY/MM/DD"]
      },
      is_selected_all: false,
      first_day: moment()
        .startOf("month")
        .toDate(),
      last_day: moment()
        .endOf("month")
        .toDate(),
      selectedDate: [],
      lengthOfStaffs: 10,
      selected_id: -1,
      staff_type: 1,
      clinics: {},
      staffs: {},
      selectedMonth: moment(),
      selected_clinic_id: 0,

      fields: [
        {
          name: "__checkbox",
          titleClass: "center aligned",
          dataClass: "center aligned"
        },
        {
          name: "full_name",
          title: "スタツフ名"
        },
        {
          name: "short_name",
          title: "略称"
        },
        {
          name: "name",
          title: "ランク"
        }
      ],
      css: {
        table: {
          tableClass: "table table-bordered ",
          loadingClass: "loading",
          handleIcon: "glyphicon glyphicon-menu-hamburger"
        }
      },
      themeStyles: {
        wrapper: {
          border: "1"
        },
        header: {
          color: "#fafafa",
          backgroundColor: "#f142f4",
          borderColor: "#404c59",
          borderWidth: "1px 1px 0 1px"
        },
        headerVerticalDivider: {
          borderLeft: "1px solid #404c59"
        },
        weekdays: {
          color: "#ffffff",
          backgroundColor: "#f142f4",
          borderColor: "#ff0098",
          borderWidth: "0 1px",
          padding: "5px 0 10px 0"
        },
        weekdaysVerticalDivider: {
          borderLeft: "1px solid #404c59"
        },
        weeks: {
          border: "1px solid #dadada"
        }
      },
      attrs: [
        {
          dot: {
            class: "high-light"
          },
          dates: new Date()
        }
      ],
      maxDate: new Date(
        moment()
          .add(3, "months")
          .endOf("month")
      ),
      minDate: new Date()
    };
  },
  watch: {
    staff_type: function(val) {
      console.log("watch...");
      this.loadStaffRanksList();
    },
    selectedMonth: function(val) {
      console.log("selectedMonth", val);
      const calendar = this.$refs.calendar;
      this.first_day = moment(val)
        .startOf("month")
        .toDate();
      this.last_day = moment(val)
        .endOf("month")
        .toDate();
    }
  },
  created() {
    this.loadClinicList();
  },
  mounted() {
    this.$watch(
      () => {
        return this.$refs.vuetable.selectedTo;
      },
      val => {
        console.log(val);
      }
    );
  }
};
</script>
<style lang="scss">
.tab-btn {
  padding: 6px 10px;
  background: #ffffff;
  cursor: pointer;
  margin-bottom: 3px; //1rem;
  margin-left: 3px;
  border: 1px solid #cccccc;
  outline: none;
}
.active {
  //border-bottom: 3px solid green;
  //background: #fcfcfc;
  background: #38c172;
  color: white;
}

.high-light {
  background-color: #ff6666;
  border-radius: 50% !important;
  // border-width: 1px;
  // border-style: solid;
  // opacity: 0.8;
}
</style>
