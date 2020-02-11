<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center" style="width:100%"><div><h3 class="">予約管理システム (スタッフ管理)</h3></div></div>
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"></h3>

                <div class="card-tools">
                  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <!-- <div class="input-group mb-3">
                    <vue-monthly-picker v-model="selectedMonth" :dateFormat="monthlyPickerSetting.dateFormat"
                         :monthLabels="monthlyPickerSetting.monthLabels"
                         :clearOption="false"
                         :alignment="monthlyPickerSetting.alignment"
                    >
                    </vue-monthly-picker>                    
                </div> -->
                <div class="input-group mb-3">
                  
                </div>

                <!-- /input-group -->
                <strong>クリニツク</strong>
                <div class="input-group mb-3">
                  <div class="el-row"> 
                        <button v-for="c in clinics" 
                                :key="c.id"                     
                                type="button" 
                                @click="clinicSelected(c)" 
                                class="el-button  el-button--primary el-button--medium" 
                                :class="['tab-btn', { active: selected_clinic_id === c.id }]"                    
                                >
                            <span>{{c.name}}</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                      <div class="col-sm-6">
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="staff_type" value="1" v-model="staff_type">
                              <label class="form-check-label">施術者</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="staff_type" value="0" v-model="staff_type">
                              <label class="form-check-label">カウンセラー</label>
                          </div>                          
                      </div>
                  </div>
                <div class="row">
                  <div class="col-lg-7 col-md-12">
                    <vuetable ref="vuetable" 
                            :fields="fields" 
                            :api-mode="false"
                            :data="staffs" 
                            :css="css.table"
                            :row-class="onRowClass"                            
                            @vuetable:row-clicked="onRowCLicked"
                    >
                  </vuetable>
                  </div>
                  <div class="col-lg-5 col-md-12">
                    <div class="row mb-3 d-flex justify-content-center">
                      残りの日を選択してください
                    </div>
                    <div class="row d-flex justify-content-center">
                      <v-date-picker
                            locale="ja"
                            mode='multiple'
                            tint-color='#f142f4'
                            v-model='selectedDate'
                            :theme-styles='themeStyles'
                            is-double-paned
                            is-inline                  
                            ref="calendar"
                            @update:from-page="pageChange"
                      >
                      </v-date-picker>
                    </div>
                    
                    <div class="row mt-3 d-flex justify-content-center">                      
                      <button class="btn btn-success" @click="saveShift">保 管</button>
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
import VueMonthlyPicker from 'vue-monthly-picker'
import VCalendar from 'v-calendar';
import Vuetable from 'vuetable-2'

import moment from 'moment'

export default {
    methods: {
        saveShift(){
            if (this.selected_id == -1){
              toast.fire({
                        icon: "warning",
                        title: "Select more than one staff!"
                    });
                    return;
            }
            let staffs = this.$refs.vuetable.selectedTo;
            console.log(staffs);
            let payload = {
                  'month':this.selectedMonth,
                  'staffs':staffs.length>0?staffs:[this.selected_id],
                  'dates':this.selectedDate
                };
            console.log(payload);

            axios.post('v1/shift/update', payload).
            then(({ data }) => {
                
            });
        },
        pageChange(page){
          console.log(page);
          this.selectedMonth = page;
        },
        onRowClass (dataItem, index) {
          //return (dataItem.isOverdue) ? 'active' : 'color-white'
          
          return dataItem.id == this.selected_id ? 'active':'';
        },
        onRowCLicked(row){
            this.selected_id = row.id;
            console.log(row);
        },
        loadClinicList() {
            axios.get('api/clinic').
            then(({ data }) => {
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
            axios.get('v1/shift/clinic/' + this.selected_clinic_id + '/' + this.staff_type).
            then(({ data }) => {
                this.staffs = data;
            });
        }
    },
    components: {
        VueMonthlyPicker,
        VCalendar,
        Vuetable
    },
    data() {
        return {
            first_day: moment().startOf('month').toDate(),
            last_day: moment().endOf('month').toDate(),
            selectedDate: [
              
            ],
            selected_id: -1,
            staff_type: 1,
            clinics: {},
            staffs: {},
            selectedMonth:  moment(),
            selected_clinic_id: 0,
            
            monthlyPickerSetting :{
                dateFormat: "YYYY年MM月",
                alignment: "center",
                monthLabels: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
            },
            fields: [
              {
                name: '__checkbox',
                title: '',                
              },
              {
                name: 'full_name',
                title: 'Name',                
              },
              {
                name: 'name',
                title: 'Rank',                
              },
            ],
            css: {
              table: {
                tableClass: 'table table-bordered ',
                loadingClass: 'loading',
                handleIcon: 'glyphicon glyphicon-menu-hamburger',
              }
            },
            themeStyles: {
              wrapper: {
                border: '1',
              },
              header: {
                color: '#fafafa',
                backgroundColor: '#f142f4',
                borderColor: '#404c59',
                borderWidth: '1px 1px 0 1px',
              },
              headerVerticalDivider: {
                borderLeft: '1px solid #404c59',
              },
              weekdays: {
                color: '#ffffff',
                backgroundColor: '#f142f4',
                borderColor: '#ff0098',
                borderWidth: '0 1px',
                padding: '5px 0 10px 0',
              },
              weekdaysVerticalDivider: {
                borderLeft: '1px solid #404c59',
              },
              weeks: {
                border: '1px solid #dadada',
              },
            },
        };
    },
    watch:{
        staff_type: function(val){
            console.log('watch...');
            this.loadStaffRanksList();
        },
        selectedMonth: function(val){
            console.log(val);
            const calendar = this.$refs.calendar;
            this.first_day = moment(val).startOf('month').toDate();
            this.last_day = moment(val).endOf('month').toDate();            
        }
    },
    created() {
        this.loadClinicList();        
    },
    mounted () {
        this.$watch(
          () => {
            return this.$refs.vuetable.selectedTo
          },
          (val) => {
              console.log(val);
          }
        )
    },
};
</script>
<style lang="scss">
    .tab-btn {
        padding: 6px 10px;
        background: #ffffff;
        cursor: pointer;
        margin-bottom: 3px;//1rem;
        margin-left: 3px;
        border: 1px solid #cccccc;
        outline: none;
    }
    .active {
        //border-bottom: 3px solid green;
        //background: #fcfcfc;
        background:#38c172;
        color:white;
    }

</style>

