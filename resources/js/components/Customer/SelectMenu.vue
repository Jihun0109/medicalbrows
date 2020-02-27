<template>
    <div class="">
        <div id="modalPrice" class="modal fade" style="padding-right: 0px;">
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
                            <label class="col-sm-5 col-form-label">料金表:</label>
                        </div>
                        <div class="form-group">                            
                            <div class='textarea-placeholder'>
                                <b-form-textarea id="textarea" v-model="price_content" :rows="23" :max-rows="23" class="form-control col-sm-10" :placeholder="'xxxxxxxxxxxxxxxxxxxxxx\nxxxxxxxxxxxxxxxxxxxxxx\nxxxxxxxxxxxxxxxxxxxxxx\n'">
                                </b-form-textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <h2 class="title is-2">
            Width: {{ screenwidth }},
        </h2> -->
        <div class="info">
            <div class="row">
                <label class="col-3 col-form-label">診療区分：</label>
                <div class="col" >
                    <p>{{passdata.order_type}}</p>
                </div>
            </div>
            <div class="row" v-show="passdata.date !== null">
                <label class="col-3 col-form-label">希望日：</label>
                <div class="col" >
                    <p>{{formatDate(passdata.date)}}({{passdata.week}})</p>
                </div>
            </div>

            <div class="row" v-show="passdata.staff_info.id !== '' && passdata.mode === 0">
                <label class="col-3 col-form-label">施術者：</label>
                <div class="col" >
                    <p style="letter-spacing: -2px;" v-if="passdata.staff_info">{{passdata.staff_info.name}}【{{passdata.rank_info.name}}】</p>
                </div>
            </div>
            <div class="row" v-show="passdata.staff_info.id !== '' && passdata.mode === 0">
                <label class="col-sm-4 col-form-label">施術可能部位：</label>
                <div class="col-sm-6" >
                    <p style="letter-spacing: -2.5px;">{{passdata.staff_info.area}}</p>
                </div>
            </div>

            <div class="row" v-show="passdata.clinic_info.id !== ''">
                <label class="col-3 col-form-label">場所：</label>
                <div class="col" >
                    <p v-if="passdata.clinic_info">{{passdata.clinic_info.name}}</p>
                </div>
            </div>
        </div> 
        <div class="selstaff-card" v-show="passdata.mode !== 0">            
            <div class="row justify-content-between">
                <div class="col-5" style="margin-bottom:0px;">
                    <label >施術者</label>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <select v-model="selectedstaff" class="form-control" >
                        <option value="null" disabled>施術者を選択または入力</option>
                        <option v-for="s in passdata.staffs" :key="s.id" v-bind:value="s">{{s.name}}</option>
                    </select>
                    <div v-show="selectedstaff !== null">
                        <label class="col-6 col-form-label">施術可能部位：</label>
                        <div class="col-auto" style="letter-spacing: -2.5px;" v-if="selectedstaff">{{selectedstaff.area}}</div>   
                        <label style="margin-left: 10px; width:75%">※ランクアップ変更の場合は 別途 追加費用が発生します</label>                      
                    </div>
                </div>
            </div>
        </div>
        <div v-show="passdata.staff_info.id !== ''" class="text-center" style="margin-top: 20px; margin-bottom: 10px;">       
            <label style="letter-spacing: -1.2px;">予約メニュー、希望枠の日付を選択して下さい</label>
        </div>
        <div class="selmenu-card" v-show="passdata.staff_info.id !=='' || selectedstaff != null"> 
            <div class="row justify-content-between">
                <div class="col-5" style="margin-bottom:0px;">
                    <label >希望メニュー</label>
                </div>
                <div class="col-auto">
                    <button @click="onClickPriceBtn" type="button" class="btn btn-primary" style="background:#DED0B6; color:black; margin-bottom:0px;">料金表</button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <select v-model="selectedmenu" class="form-control" >
                        <option value="null" disabled>希望メニューを選択または入力</option>
                        <option v-for="m in passdata.menu_array" :key="m.id" v-bind:value="m">{{m.name}}</option>                        
                    </select>
                </div>
            </div>
        </div>
        <div class="seldate-card" v-show="passdata.staff_info.id !=='' || selectedstaff != null"> 
        <!-- <div class="seldate-card" >  -->
            <label class="mt-3" style="margin-bottom:0px;">希望枠</label>
            <div class="smnext row justify-content-between" v-show="passdata.mode !== 1">
                <span @click="prevWeekBtn" class="col-4"><i v-show="nextweek_count" class='fas fa-caret-left ' style='font-size:24px;'></i></span>
                <span @click="nextWeekBtn" class="col-auto"><i class='fas fa-caret-right' style='font-size:24px;'></i></span>
            </div>

            <div class="calendar" style="margin: auto;" :class="[{oneday:passdata.mode === 1}]">
                <grid-layout
                    :layout.sync="passdata.calendar_layout"
                    :col-num="passdata.colNum"
                    :row-height="30"
                    :is-draggable="false"
                    :is-resizable="false"
                    :vertical-compact="true"
                    :margin="[ -2 , -1]"
                    :use-css-transforms="true"
                >
                    <grid-item @click.native="onClickCalItem($event, item, index)" v-for="(item, index) in passdata.calendar_layout"
                            :x="item.x"
                            :y="item.y"
                            :w="item.w"
                            :h="item.h"
                            :i="item.i"
                            :key="index  + '-separator'"
                            :static="item.static"                                   
                            :class="[item.order_status, {selectedcolor: index === activeColor, selectable: item.selectable}]"
                            >
                            <div class="text" v-html="item.i"></div>
                            <!-- <span class="text">{{item.i}}</span>-->
                    </grid-item>
                </grid-layout>
            </div>
        </div>      
        <div class="seldtime-card" v-show="sel_time_schedule !== null">
            <label class="mt-3" style="margin-bottom:0px;">予約可能時間帯・場所</label>            
            <b-form-radio v-model="sel_time_schedule" v-for="(tc, index) in time_schedules" :key="index" :value="tc" name="radio-seltime" size="lg">{{tc.start_time}}~{{tc.end_time}}<span style="padding-left: 15px;">{{passdata.clinic_info.name}}</span></b-form-radio> 
         
        </div>  
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button v-show="sel_time_schedule !== null && selectedmenu !== null"  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    window.gOrderInfo = {
        data: {
            staff_choosed:'',
            order_type:'',
            staff_info: {
                id:'',
                name:'',
            },
            rank_info: {
                id:'',
                name:'',
            },
            clinic_info: {
                id:'',
                name:'',
            },
            menu_info: {
                id:'',
                name:'',
            },                     
            calendar_info: {
                date:'',
                week:'',
            },
            time_schedule_info:{
                start_time:'',
                end_time:'',
            },   
        }
    };
    export default {
        data() {
            return {
                selectedstaff: null,
                staffs: [
                    // { id: "1", name:"池田　グランドマスタートレイナー", area:"眉・アイライン上・アイライン下・リップ"}, 
                    // { id: "2", name:"吉田(も)　マスター" , area:"眉・アイライン上・アイ"}, 
                    // { id: "3", name:"松原 ロイヤルアーティスト" , area:"アイライン下・リップ"}
                ],
                colNum: 4,//16
                activeColor: '',
                callayout:[], 
                //callayout: JSON.parse(JSON.stringify(onedayLayout)),

                passdata: gOrderTypeInfo.data,
                price_content: '',
                selectedmenu: null,
                menus: [],
                sel_time_schedule: null,
                time_schedules:null,

                nextweek_count: 0,
                screenwidth: 0,
                screenmode: 7,
            }
        },
        watch: {
            selectedstaff(val) {                 
                if(this.selectedstaff){
                    //시술자가 바뀔때 마다 선택된 모든 항목을 초기화
                    $(".vue-grid-item").removeClass("selectedcolor");
                    this.sel_time_schedule = null;
                    this.time_schedules = null;
                    this.selectedmenu = null;
                    
                    this.passdata.staff_info = this.selectedstaff;
                    this.passdata.rank_info.id = this.selectedstaff.rank_id;
                    this.passdata.rank_info.name = this.selectedstaff.rank_name;
                    //console.log(this.selectedstaff);
                    if(this.passdata.mode === 2){
                        this.getCalendarLayout(this.selectedstaff, this.screenmode);
                    }else if(this.passdata.mode === 1){                        
                        this.getCalendarLayout(this.selectedstaff, 1, this.passdata.date);
                        this.clinic_list(); //날자우선방식인 경우에만 필요, 나머지는 사전에 다 구함.
                        this.menu_list(this.selectedstaff, this.passdata.date);   
                    }                    
                    //this.menu_list();                    
                }                    
            },
            screenmode(val){
                console.log(this.selectedstaff, this.screenmode, 'menu');                        
                if(this.passdata.staff_info.id !== ''){
                    this.getCalendarLayout(this.passdata.staff_info, this.screenmode);
                }
            },
        },
        created() {
            window.addEventListener('resize', this.handleResize)
            this.handleResize(); 
        },
        destroyed() {
            window.removeEventListener('resize', this.handleResize)
        },
        methods:{
            handleResize() {
                if(this.passdata.mode !== 1){
                    this.screenwidth = window.innerWidth;                    
                    if(this.screenwidth > 1024){
                        this.screenmode = 14;                        
                    }                        
                    else{
                        this.screenmode = 7;
                    }
                }
            },
            clinic_list(){
                axios.post('/v1/client/clinic_list', { 'staff_info': this.selectedstaff}).
                then(({ data }) => {
                    var clinic_info = data;
                    this.passdata.clinic_info = clinic_info[0];                    
                });   
            },
            menu_list:function(staff_info, date){
                axios.post('/v1/client/menu_list', { 'staff_info': staff_info ,'date': moment(date).format("YYYY-MM-DD")}).
                then(({ data }) => {
                    gOrderTypeInfo.data.menu_array = data;
                });   
            },
            onClickCalItem($event, item, index){                
                if(item.selectable){
                    console.log(item);
                    $(".vue-grid-item").removeClass("selectedcolor");
                    $(event.currentTarget).addClass("selectedcolor"); //defalt color when click..                     
                    gOrderInfo.data.calendar_info.date = item.date_info.date;                    
                    gOrderInfo.data.calendar_info.week = item.date_info.week;         
                    this.time_schedules = item.order_info;
                    this.sel_time_schedule = item.order_info[0]; //as default
                    if(this.passdata.mode !== 1){
                        this.menu_list( this.passdata.staff_info, item.date_info.date);   
                    }
                    //console.log(this.time_schedules);
                }
            },
            onClickPriceBtn(){
                $('#modalPrice').modal('show');
            },
            onClickNextBtn:function(){
                gOrderInfo.data.staff_choosed = this.passdata.mode === 0?'あり':'なし';
                gOrderInfo.data.order_type = this.passdata.order_type;                
                gOrderInfo.data.staff_info = this.passdata.staff_info;
                gOrderInfo.data.rank_info = this.passdata.rank_info;      
                gOrderInfo.data.clinic_info = this.passdata.clinic_info;
                gOrderInfo.data.menu_info = this.selectedmenu;
                //상담원과  시술자의 rank_schedule_id, 날자, 요일, 시간정보,
                gOrderInfo.data.time_schedule_info = this.sel_time_schedule;
                this.$emit('changeStage', 2);
            },
            onClickPrevBtn:function(){
                this.reset();
                this.$emit('changeStage', 0);
            },
            reset(){
                $(".vue-grid-item").removeClass("selectedcolor");
                this.selectedstaff = null;
                this.staffs = [];
                this.selectedmenu = null;
                this.menus = [];
                this.sel_time_schedule = null;
                this.time_schedules = null;
            },
            nextWeekBtn:function(){
                this.nextweek_count++;
                console.log(this.passdata);
                this.getCalendarLayout(this.passdata.staff_info, this.screenmode);
            },
            prevWeekBtn:function(){
                if(this.nextweek_count >= 1){
                    this.nextweek_count--;
                    this.getCalendarLayout(this.passdata.staff_info, this.screenmode);
                }                    
            },
            getCalendarLayout:function(staff_info, showdays, selecteddate = null){
                axios.post('/v1/client/canledar_info', { 'order_type':this.passdata.order_type, 'staff_info': staff_info,'weekmethod':showdays, 'selecteddate':selecteddate,'count': this.nextweek_count}).
                then(({ data }) => {
                    this.passdata.colNum = data.layout_width;
                    this.passdata.calendar_layout = JSON.parse(JSON.stringify(data.layout));
                    console.log(data);
                });   
            },
            formatDate(dt) {
                dt = new Date(dt);
                var month = ('0' + (dt.getMonth() + 1)).slice(-2);
                var date = ('0' + dt.getDate()).slice(-2);
                var year = dt.getFullYear();
                var formattedDate = year + '年' + month + '月' + date + '日';
                //var formattedDate = year + '-' + month + '-' + date;
                return formattedDate;
            },
        }
    }
</script>
<style lang="scss">
    .seldate-card{
        .vue-grid-item.static {
            cursor: default;
            background: #ccc;           
        }
        .vue-grid-item.selectedcolor{
            cursor: pointer;
            background:#D87319 !important;
        }
        .vue-grid-item.selectable{
            cursor: pointer;
            background:rgb(204, 255, 204);
            color:black;
        } 
        
        .calendar.oneday{
            width: 30%;
        }
     }
     .confirm-btn{
         margin-top: 20px;
         .btn{
            width: 120px;
            height: 48px;
         }
     }
</style>
