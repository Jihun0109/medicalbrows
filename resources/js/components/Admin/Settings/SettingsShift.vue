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
                
                <div class="input-group mb-3">
                    <vue-monthly-picker v-model="selectedMonth" :dateFormat="monthlyPickerSetting.dateFormat"
                         :monthLabels="monthlyPickerSetting.monthLabels"
                         :clearOption="false"
                         :alignment="monthlyPickerSetting.alignment"
                    >
                    </vue-monthly-picker>
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
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Task</th>
                      <th>Progress</th>
                      <th style="width: 40px">Label</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1.</td>
                      <td>Update software</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-danger">55%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td>Clean database</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar bg-warning" style="width: 70%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-warning">70%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td>Cron job running</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar bg-primary" style="width: 30%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-primary">30%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td>Fix and squish bugs</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar bg-success" style="width: 90%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-success">90%</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
    </div>
</template>

<script>
import VueMonthlyPicker from 'vue-monthly-picker'
import moment from 'moment'
export default {
    methods: {
        loadClinicList() {
            axios.get('api/clinic').
            then(({ data }) => {
                this.clinics = data;
                this.selected_clinic_id = this.clinics[0].id;
                // this.selected_clinic_name = this.clinics[0].name;
                // this.loadStaffRanksList();
            });
            // axios.get('api/menu').
            // then(({ data }) => {
            //     this.menus = data.data;
            // });
        },
        clinicSelected(clinic) {
            this.selected_clinic_id = clinic.id;
            
            //this.loadStaffRanksList();
        },
    },
    components: {
        VueMonthlyPicker
    },
    data() {
        return {
            clinics: {},
            selectedMonth:  moment(),
            selected_clinic_id: 0,
            
            monthlyPickerSetting :{
                dateFormat: "YYYY年MM月",
                alignment: "center",
                monthLabels: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
            }
        };
    },
    created() {
        this.loadClinicList();
        this.selectedMonth = this.formatDate(new Date());
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
        background:rgb(27, 185, 175);
    }
</style>

