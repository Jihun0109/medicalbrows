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
                    <p>{{passdata.date}}({{passdata.week}})</p>
                </div>
            </div>

            <div class="row" v-show="passdata.staff_info.id !== ''">
                <label class="col-3 col-form-label">施術者：</label>
                <div class="col" >
                    <p style="letter-spacing: -2px;" v-if="passdata.staff_info">{{passdata.staff_info.name}}【{{passdata.rank_info.name}}】</p>
                </div>
            </div>
            <div class="row" v-show="passdata.staff_info.id !== ''">
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
        <div class="selstaff-card" v-show="passdata.staff_info.id === ''">            
            <div class="row justify-content-between">
                <div class="col-5" style="margin-bottom:0px;">
                    <label >施術者</label>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <select v-model="selectedstaff" class="form-control" >
                        <option value="null" disabled>施術者を選択または入力</option>
                        <option v-for="s in staffs" :key="s.id" v-bind:value="s">{{s.name}}</option>
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
        <!-- <div class="seldate-card" v-show="passdata.staff_info.id !=='' || selectedstaff != null">  -->
        <div class="seldate-card" > 
            <label class="mt-3" style="margin-bottom:0px;">希望枠</label>
            <div class="smnext row justify-content-between">
                <span @click="prevWeekBtn" class="col-4"><i v-show="nextweek_count" class='fas fa-caret-left ' style='font-size:24px;'></i></span>
                <span @click="nextWeekBtn" class="col-auto"><i class='fas fa-caret-right' style='font-size:24px;'></i></span>
            </div>

            <div class="calendar" style="margin: auto;">
                <grid-layout
                    :layout.sync="callayout"
                    :col-num="this.colNum"
                    :row-height="30"
                    :is-draggable="false"
                    :is-resizable="false"
                    :vertical-compact="true"
                    :margin="[ -2 , -1]"
                    :use-css-transforms="true"
                >
                    <grid-item @click.native="onClickCalItem($event, item, index)" v-for="(item, index) in callayout"
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
        <div class="seldtime-card" v-show="sel_time_clinic !== null">
            <label class="mt-3" style="margin-bottom:0px;">予約可能時間帯・場所</label>            
            <b-form-radio v-model="sel_time_clinic" v-for="(tc, index) in time_clinics" :key="index" :value="tc" name="radio-seltime" size="lg">{{tc}}</b-form-radio> 
         
        </div>  
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button v-show="sel_time_clinic !== null && selectedmenu !== null"  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    window.gOrderInfo = {
        data: {
            order_type:'',
            staff_info: {
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
                    time:'',
                    clinic:'',
            },
        }
    };
    var onedayLayout = [
        {"x":0,"y":0,"w":2,"h":4,"i":"", "static": false, "selectable":false},

        {"x":2,"y":0,"w":2,"h":1,"i":"2020", "static": false, "selectable":false},
        
        {"x":2,"y":1,"w":2,"h":1,"i":"2月", "static": false, "selectable":false},
        
        {"x":2,"y":2,"w":2,"h":2,"i":"6<br>(木)", "static": false, "selectable":false},

        {"x":0,"y":4,"w":2,"h":1,"i":"午前", "static": false, "selectable":false},
        {"x":0,"y":5,"w":2,"h":1,"i":"日中", "static": false, "selectable":false},
        {"x":0,"y":6,"w":2,"h":1,"i":"夕方", "static": false, "selectable":false},
        
        {"x":2,"y":4,"w":2,"h":1,"i":"◯", "static": true, "selectable":true, "data":{"date":"2020-02-05", "week":"木", "time":["09:20~12:00","11:20~14:00"],"clinic":"表参道院"}},
        {"x":2,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":2,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},  
    ];

    export default {
        data() {
            return {
                selectedstaff: null,
                staffs: [
                    { id: "1", name:"池田　グランドマスタートレイナー", area:"眉・アイライン上・アイライン下・リップ"}, 
                    { id: "2", name:"吉田(も)　マスター" , area:"眉・アイライン上・アイ"}, 
                    { id: "3", name:"松原 ロイヤルアーティスト" , area:"アイライン下・リップ"}
                ],
                colNum: 4,//16
                activeColor: '',
                callayout:[], 
                //callayout: JSON.parse(JSON.stringify(onedayLayout)),

                passdata: gOrderTypeInfo.data,
                price_content: '',
                selectedmenu: null,
                menus: [],
                sel_time_clinic: null,
                time_clinics:null,

                nextweek_count: 0,
            }
        },
        components: {        
            
        },
        mounted() {

        },
        created(){
            axios.post('/v1/client/canledar_info', { 'staff_info': this.selectedstaff, 'count': this.nextweek_count}).
            then(({ data }) => {
                this.colNum = data.layout_width;
                this.callayout = JSON.parse(JSON.stringify(data.layout));
                console.log(data);
            });   
        },
        methods:{
            onClickCalItem($event, item, index){
                if(item.selectable){
                    $(".vue-grid-item").removeClass("selectedcolor");
                    $(event.currentTarget).addClass("selectedcolor"); //defalt color when click..                     
                    gOrderInfo.data.calendar_info.date = item.data.date;                    
                    gOrderInfo.data.calendar_info.week = item.data.week;
                    gOrderInfo.data.calendar_info.clinic = item.data.clinic;
                    this.time_clinics = [];
                    item.data.time.forEach(element => {                                    
                        this.time_clinics.push(element + ' ' + item.data.clinic);
                    });
                    this.sel_time_clinic = this.time_clinics[0];
                }
            },
            onClickPriceBtn(){
                $('#modalPrice').modal('show');
            },
            onClickNextBtn:function(){
                gOrderInfo.data.order_type = this.passdata.order_type;
                gOrderInfo.data.staff_info = this.selectedstaff?this.selectedstaff:this.passdata.staff_info;      
                gOrderInfo.data.clinic_info = this.passdata.clinic_info;
                gOrderInfo.data.menu_info = this.selectedmenu;
                gOrderInfo.data.calendar_info.time = this.sel_time_clinic;
                this.$emit('changeStage', 2);
            },
            onClickPrevBtn:function(){
                this.reset();
                this.$emit('changeStage', 0);
            },
            reset(){
                this.selectedstaff = null;
                this.selectedmenu = null;
                this.sel_time_clinic = null;
                this.time_clinics = null;

                // gOrderTypeInfo.data.staff_info.id = "";
                // gOrderTypeInfo.data.date = null;
                // gOrderTypeInfo.data.clinic_info.id = "";
            },
            nextWeekBtn:function(){
                this.nextweek_count++;
                axios.post('/v1/client/canledar_info', { 'staff_info': this.selectedstaff, 'count': this.nextweek_count}).
                then(({ data }) => {
                    this.colNum = data.layout_width;
                    this.callayout = JSON.parse(JSON.stringify(data.layout));
                    console.log(data);
                });   
            },
            prevWeekBtn:function(){
                if(this.nextweek_count >= 1){
                    this.nextweek_count--;
                    axios.post('/v1/client/canledar_info', { 'staff_info': this.selectedstaff, 'count': this.nextweek_count}).
                        then(({ data }) => {
                            this.colNum = data.layout_width;
                            this.callayout = JSON.parse(JSON.stringify(data.layout));
                            console.log(data);
                    });   
                }                    
            },
        }
    }
</script>
<style lang="scss">
    .seldate-card{
        .vue-grid-item.static {
            background: #ccc;           
        }
        .vue-grid-item.selectedcolor{
            background:#D87319 !important;
        }
        .vue-grid-item.selectable{
            background:rgb(204, 255, 204);
            color:black;
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
