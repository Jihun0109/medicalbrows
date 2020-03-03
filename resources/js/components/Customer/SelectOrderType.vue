<template>
    <div class="">
        <div id="modalInput" class="modal fade" style="padding-right: 0px;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="width:370px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- dialog body -->
                    <div class="modal-body justify-content-center"  style="padding-left: 8px;" >
                        <div  style="font-weight:blod; padding-left: 10px;">
                            <label class="col-sm-5 col-form-label">{{order_type}}とは:</label>
                        </div>
                        <div class="form-group">                            
                            <div class='textarea-placeholder'>
                                <b-form-textarea id="textarea" v-model="text1" :rows="23" :max-rows="23" class="form-control col-sm-10" :placeholder="'xxxxxxxxxxxxxxxxxxxxxx\nxxxxxxxxxxxxxxxxxxxxxx\nxxxxxxxxxxxxxxxxxxxxxx\n'">
                                </b-form-textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cardcontent">
            <div class="text-center">       
                <label style="letter-spacing: -1.2px;">診療区分、予約方法を選択して下さい</label>
            </div>
            <label class="mt-3" style="margin-bottom:0px;">診療区分:</label>
            <div class="division">
                 <b-card title="">
                    <b-card-text>
                        <div class="radiobtns">
                            <b-row >
                                <b-col style="display: flex">
                                    <b-form-radio v-model="order_type" name="radio-ordertype" value="新規" style="padding-right: 20px;">新規</b-form-radio>
                                    <a @click="onNewHelp" data-toggle="modal" href="#modalInput"><i class="help-icon fas fa-question-circle"></i></a>
                                </b-col>
                            </b-row>
                            <b-row >
                                <b-col style="display: flex">
                                    <b-form-radio v-model="order_type" name="radio-ordertype" value="再診" style="padding-right: 20px;">再診</b-form-radio>
                                    <a @click="onOldHelp" data-toggle="modal" href="#modalInput"><i class="help-icon fas fa-question-circle"></i></a>
                                </b-col>
                            </b-row>
                            <label v-show="order_type === '再診'" style="margin-top: 10px;">前回予約IDをお持ちの方は<a @click="existIDPage" href="#" class="card-link">こちら</a></label>
                        </div> 
                    </b-card-text>
                </b-card>
           </div>
           <label class="mt-3" style="margin-bottom:0px;">予約方法:</label>
           <div class="method">
                <b-card title="">
                    <b-card-text>
                        <div class="radiobtns">
                            <div>
                                <b-form-radio v-model="order_method" value="staff" name="radio-method" size="lg">施術者を優先して予約</b-form-radio>
                                <select v-show="order_method === 'staff'" v-model="selectedrank" class="form-control" style="margin-bottom: 8px;">
                                    <option value="null" disabled>ランクを選択</option>
                                    <option v-for="(r,index) in ranks" :key="index" v-bind:value="r">{{r.name}}</option>
                                </select>
                                <select v-show="order_method === 'staff' && selectedrank !== null" v-model="selectedstaff" class="form-control" style="margin-bottom: 8px;">
                                    <option value="null" disabled>施術者を選択</option>
                                    <option v-for="(s, index) in staffs" :key="index" v-bind:value="s">{{s.name}}</option>
                                </select>
                                <div v-show="selectedstaff !== null && order_method === 'staff'">
                                    <label class="" style="margin-bottom:0px;">施術可能部位:</label>
                                    <div style="letter-spacing:-1.5px;" v-if="selectedstaff">{{selectedstaff.area}}</div>
                                </div>
                                <b-form-radio v-model="order_method" value="date" name="radio-method" size="lg">希望日を優先して予約</b-form-radio>
                                <v-date-picker
                                    locale="ja"                                    
                                    tint-color='#f142f4'
                                    v-model='selecteddate'
                                    :attributes='attrs'
                                    :min-date="minDate"
                                    :max-date="maxDate"                                                                        
                                    :theme-styles='themeStyles'
                                    is-double-paned
                                    is-inline                  
                                    ref="calendar"
                                    @update:from-page="pageChange"
                                    v-show="order_method === 'date'"
                                >
                                </v-date-picker>
                                <b-form-radio v-model="order_method" value="clinic" name="radio-method" size="lg">場所を優先して予約</b-form-radio>
                                <select v-show="order_method === 'clinic'" v-model="selectedclinic" class="form-control" style="margin-bottom: 10px;">
                                    <option value="null" disabled>場所を選択または入力</option>
                                    <option v-for="(c, index) in clinics" :key="index" v-bind:value="c">{{c.name}}</option>
                                </select>
                                <label v-show="order_method === 'clinic' && order_type ==='再診'" style="margin-left: 10px; width:65%">※院変更の場合は 別途 初診料2,000円（税別）が発生します</label>
                            </div>                            
                        </div> 
                    </b-card-text>
                </b-card>
                <label v-show="order_type === '再診'&& order_method === 'staff'" style="margin-left: 10px; width:65%">※ランクアップ変更の場合は 別途 追加費用が発生します</label> 
            </div>
            <div class="confirm-btn">
                <div class="row justify-content-around">
                    <div class="col-4">
                        <button v-show='true' @click="onClickCancelBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">予約取消</button>
                    </div>
                    <div class="col-auto" style="margin-left: 40px;">
                        <button v-show="showNextBtn(order_method)" @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VCalendar from 'v-calendar';
import Vuetable from 'vuetable-2';
import moment from 'moment';

window.gOrderTypeInfo = initialState();
function initialState(){
    return{
        data: {
            mode: 0,
            screenmode: 7,
            order_type: null,
            rank_info:{
                id:'',
                name:'',             
            },
            staff_info: {
                    id:'',
                    name:'',
                    area:'',
                },
            date: null,
            week: null,
            clinic_info: {
                    id:'',
                    name:'',
                },
            menu_array:[],
            calendar_layout:[],
            colNum: 16,
            staffs:[],
        }
    } 
}

export default {
    props:['ranks','clinics'],
    components: {        
        VCalendar,
        Vuetable,
    },
    data() {
        return {
            //Option variables(Radio Button)
            order_type:'新規',
            order_method: 'staff', 

            selectedrank: null,
            // ranks: [],

            selectedstaff: null,
            staffs: [],

            selectedclinic: null,
            // clinics: [],
            text1: '',

            //Date variables
            selectedweek: null,            
            selecteddate: null,
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
            attrs: [
                {
                    dot: {
                        class: 'high-light'
                    },
                    dates: new Date()
                }
            ],
            maxDate: new Date(moment().add(3, 'months').endOf('month')),
            minDate: new Date(),
        };
    },
    methods: {
        showNextBtn: function(method){                
            if(method =="staff"){
                if(this.selectedstaff != null){
                    this.selecteddate = null;
                    this.selectedclinic = null;
                    return true;
                }
            }
            if(method =="date"){
                if(this.selecteddate != null){
                    this.selectedstaff = null;
                    this.selectedclinic = null;
                    return true;
                }
            }
            if(method =="clinic"){
                if(this.selectedclinic != null){
                    this.selecteddate = null;
                    this.selectedstaff = null;
                    return true;
                }
            }
            return false;
        },
        onClickNextBtn:function(){
            gOrderTypeInfo.data.order_type = this.order_type;
            if(this.selectedstaff){
                gOrderTypeInfo.data.mode = 0;
                gOrderTypeInfo.data.date = null;
                gOrderTypeInfo.data.clinic_info.id = "";
                gOrderTypeInfo.data.staff_info = this.selectedstaff;
                gOrderTypeInfo.data.rank_info = this.selectedrank;

                //2020-03-03부터 메뉴를 가지고 날자표를 얻어오므로 이 부분은 막아버리자...대신 메뉴목록을 날자에 관계없이 몽땅 얻어낸다.
                //그리고 메뉴선택한 후에 날자표에 반영해주면 되니까.
                // var screenwidth = window.innerWidth;
                // if(screenwidth > 1024)
                //     gOrderTypeInfo.screenmode = 14;
                // else
                //     gOrderTypeInfo.screenmode = 7;
                // axios.post('/v1/client/canledar_info', { 'order_type': this.order_type, 'staff_info': this.selectedstaff, 'weekmethod': gOrderTypeInfo.screenmode}).
                // then(({ data }) => {
                //     gOrderTypeInfo.data.colNum = data.layout_width;
                //     gOrderTypeInfo.data.calendar_layout = JSON.parse(JSON.stringify(data.layout));
                //     console.log(data);
                // });   
                var curDate = new Date();
                this.menu_List(curDate);

                axios.post('/v1/client/clinic_list', { 'staff_info': this.selectedstaff}).
                then(({ data }) => {
                    var clinic_info = data;
                    gOrderTypeInfo.data.clinic_info = clinic_info[0];
                    this.$emit('changeStage', 1);                   
                });   
            }                
            else if(this.selecteddate){
                gOrderTypeInfo.data.mode = 1;
                gOrderTypeInfo.data.date = moment(this.selecteddate).format("YYYY-MM-DD");
                gOrderTypeInfo.data.week = this.selectedweek;
                gOrderTypeInfo.data.staff_info.id = "";
                gOrderTypeInfo.data.clinic_info.id = "";     
                gOrderTypeInfo.data.calendar_layout = []; //이전에 현시되여있는 표를 삭제
                axios.post('/v1/client/staff_list_withdate', { 'date': gOrderTypeInfo.data.date}).
                then(({ data }) => {
                    gOrderTypeInfo.data.staffs = data;
                    this.$emit('changeStage', 1);
                    console.log(gOrderTypeInfo.data.staffs,'=====date--->staffs========');
                });              
            }
            else if(this.selectedclinic){
                gOrderTypeInfo.data.mode = 2;
                gOrderTypeInfo.data.staff_info.id = "";
                gOrderTypeInfo.data.date = null;
                gOrderTypeInfo.data.clinic_info = this.selectedclinic;   
                
                axios.post('/v1/client/staff_list', { 'clinic_info': this.selectedclinic}).
                then(({ data }) => {                    
                    gOrderTypeInfo.data.staffs = data;
                    this.$emit('changeStage', 1);   
                    console.log(gOrderTypeInfo.data.staffs,'=====clinic--->staffs========');
                });                         
            }
            this.reset();
            console.log(gOrderTypeInfo.data, 'orderinfo from Selectordertype.vue'); 
        },
        onClickCancelBtn(){
            this.$emit('toCancelPage', 1);
        },
        onNewHelp: function(){
            this.order_type = '新規';
        },
        onOldHelp: function(){
            this.order_type = '再診';
        },
        pageChange(page){
          console.log(page);
        },
        getWeek(date){
            var daysOfWeek = ['日','月', '火', '水', '木', '金', '土'];
            let i = new Date(date).getDay(date)
            return daysOfWeek[i];
        },
        existIDPage:function(){
            this.$emit('toExistIdPage', true);
        },
        staff_rank_List:function(){
            axios.post('/v1/client/staff_list', { 'rank_info': this.selectedrank}).
            then(({ data }) => {
                this.staffs = data;
                //console.log(this.staffs);
            });   
        },
        menu_List:function(curdate){
            axios.post('/v1/client/menu_list', { 'staff_info': this.selectedstaff ,'date': moment(curdate).format("YYYY-MM-DD")}).
            then(({ data }) => {
                gOrderTypeInfo.data.menu_array = data;
            });   
        },
        reset:function(){
            //this.order_type = '新規';
            //this.order_method = 'staff'; 
            this.selectedrank = null;
            this.selectedstaff = null;
            this.staffs = [];
            this.selectedclinic = null;
            this.text1 = '';
            this.selectedweek = null;            
            this.selecteddate = null;
        },
    },
    created() {
        this.selecteddate = new Date();    
    },
    watch: {
        selecteddate(val) {
            this.selectedweek = this.getWeek(val);            
        },
        selectedrank(val) {
            this.selectedstaff = null;
            if(this.selectedrank)
                this.staff_rank_List();  
        },
        // selectedstaff(val) {
        //     if(this.selectedstaff)
        //         this.menu_List();  
        // },
    },
};
</script>

<style lang="scss">
    .custom-radio{
        margin-bottom: 8px;
    }

    .help-icon{
        font-size: 20px;
    }    

</style>
