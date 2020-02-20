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
                                <b-col >
                                    <b-form-radio v-model="order_type" name="radio-ordertype" value="新規">新規</b-form-radio>
                                </b-col>
                                <b-col >
                                    <a @click="onNewHelp" data-toggle="modal" href="#modalInput"><i class="help-icon fas fa-question-circle"></i></a>
                                </b-col>
                            </b-row>
                            <b-row >
                                <b-col >
                                    <b-form-radio v-model="order_type" name="radio-ordertype" value="再診">再診</b-form-radio>
                                </b-col>
                                <b-col >
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
                                <b-form-select v-show="order_method === 'staff'" v-model="selectedstaff" :options="staffs" style="margin-bottom: 8px;"></b-form-select>
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
                                    :theme-styles='themeStyles'
                                    is-double-paned
                                    is-inline                  
                                    ref="calendar"
                                    @update:from-page="pageChange"
                                    v-show="order_method === 'date'"
                                >
                                </v-date-picker>
                                <b-form-radio v-model="order_method" value="clinic" name="radio-method" size="lg">場所を優先して予約</b-form-radio>
                                <b-form-select v-show="order_method === 'clinic'" v-model="selectedclinic" :options="clinics" style="margin-bottom: 10px;"></b-form-select>
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
                        <button v-show='false' type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
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
    console.log('I am intial function');
    return{
        data: {
            order_type: null,
            staff_info: {
                    id:'',
                    name:'',
                },
            date: null,
            week: null,
            clinic_info: {
                    id:'',
                    name:'',
                },
        }
    } 
}

export default {
    components: {        
        VCalendar,
        Vuetable,
    },
    data() {
        return {
            //Option variables(Radio Button)
            order_type:'新規',
            order_method: 'staff', 
            selectedstaff: null,
            staffs: [
                { value: null, text: '施術者を選択または入力' , disabled: true},
                { value: { id: "1", name:"池田　グランドマスタートレイナー", area:'眉・アイライン上・アイライン下・リップ'}, text: '池田　グランドマスタートレイナー' },
                { value: { id: "2", name:"吉田(も)　マスター" , area:'眉・アイライン上・アイ'}, text: '吉田(も)　マスター' },
                { value: { id: "3", name:"松原 ロイヤルアーティスト" , area:'アイライン下・リップ'}, text: '松原 ロイヤルアーティスト' },
            ],
            selectedclinic: null,
            clinics: [
                { value: null, text: '場所を選択または入力', disabled: true },
                { value: { id: "1", name:"表参道院"}, text: '表参道院' },
                { value: { id: "2", name:"新宿院"}, text: '新宿院' },
                { value: { id: "3", name:"六本木院"}, text: '六本木院' },
            ],
            text1: '',

            //Date variables
            selectedweek: null,
            daysOfWeek:['月', '火', '水', '木', '金', '土', '日'],
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
            if(this.selectedstaff)
                gOrderTypeInfo.data.staff_info = this.selectedstaff;
            else if(this.selecteddate){
                gOrderTypeInfo.data.date = moment(this.selecteddate).format("YYYY-MM-DD");
                gOrderTypeInfo.data.week = this.selectedweek;
                console.log( gOrderTypeInfo.data.date, gOrderTypeInfo.data.week);
            }
            else if(this.selectedclinic)
                gOrderTypeInfo.data.clinic_info = this.selectedclinic;
            console.log(gOrderTypeInfo.data, 'orderinfo from Selectordertype.vue'); 
            this.$emit('changeStage', 1);
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
            let i = new Date(date).getDay(date)
            return this.daysOfWeek[i];
        },
        existIDPage:function(){
            this.$emit('toExistIdPage', 'true');
        },
        reset:function(){

        }
    },
    created() {
        this.selecteddate = new Date();        
    },
    watch: {
        selecteddate(val) {
            this.selectedweek = this.getWeek(val);            
        }
    },
};
</script>

<style lang="scss">
    .custom-radio{
        margin-bottom: 8px;
    }

    .bottombtn-home {
        a {
            display: inline-block;
            color: #9e9e9e;
            width: 33.33%;
            float: left;
            text-align: center;
        }
        .active {
            color: rgb(3, 46, 86);
        }
        .icon {
            display: block;
            margin: 0 auto;
            margin-bottom: 1px;
        }
    }
    .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: rgb(240,240,240);
        color: black;
        text-align: center;
    }
    .help-icon{
        font-size: 20px;
    }    

</style>
