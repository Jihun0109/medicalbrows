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
            <div class="row">
                <label class="col-3 col-form-label">時間：</label>
                <div class="col" >
                    <p>{{passdata.time}}</p>
                </div>
            </div>
            <div class="row">
                <label class="col-3 col-form-label">施術者：</label>
                <div class="col" >
                    <p style="letter-spacing: -2px;" v-if="passdata.staff_info">{{passdata.staff_info.name}}</p>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-4 col-form-label">施術可能部位：</label>
                <div class="col-sm-6" >
                    <p style="letter-spacing: -2.5px;">眉・アイライン上・アイライン下・リップ</p>
                </div>
            </div>
            <div class="row">
                <label class="col-3 col-form-label">場所：</label>
                <div class="col" >
                    <p v-if="passdata.clinic_info">{{passdata.clinic_info.name}}</p>
                </div>
            </div>
        </div> 
        <div class="text-center" style="margin-top: 20px; margin-bottom: 10px;">       
            <label style="letter-spacing: -1.2px;">予約メニュー、希望枠の日付を選択して下さい</label>
        </div>
        <div class="selmenu-card">            
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
                        <option value="希望メニューを選択または入力" disabled>希望メニューを選択または入力</option>
                        <option v-for="m in menus" :key="m.id" v-bind:value="m">{{m.name}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="seldate-card">
            <label class="mt-3" style="margin-bottom:0px;">希望枠</label>
            <div class="calendar">
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
            <b-form-radio v-model="sel_time_clinic" v-for="(tc, index) in time_clinics" :key="index" value="tc" name="index" size="lg">{{tc}}</b-form-radio>            
        </div>  
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button v-show="sel_time_clinic !== null && selectedmenu !== null"  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    window.toConfirmOrderInfo = {
        data: {            
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
    var calendarLayout = [
        {"x":0,"y":0,"w":2,"h":4,"i":"", "static": false, "selectable":false},

        {"x":2,"y":0,"w":14,"h":1,"i":"2020", "static": false, "selectable":false},

        {"x":2,"y":1,"w":2,"h":1,"i":"1月", "static": false, "selectable":false},
        {"x":4,"y":1,"w":12,"h":1,"i":"2月", "static": false, "selectable":false},
        
        {"x":2,"y":2,"w":2,"h":2,"i":"31<br>(金)", "static": false, "selectable":false},
        {"x":4,"y":2,"w":2,"h":2,"i":"<div style='color:green; '>1<br>(土)</div>", "static": false, "selectable":false},
        {"x":6,"y":2,"w":2,"h":2,"i":"<div style='color:red; '>2<br>(日)</div>", "static": false, "selectable":false},
        {"x":8,"y":2,"w":2,"h":2,"i":"3<br>(月)", "static": false, "selectable":false},
        {"x":10,"y":2,"w":2,"h":2,"i":"4<br>(火)", "static": false, "selectable":false},
        {"x":12,"y":2,"w":2,"h":2,"i":"5<br>(水)", "static": false, "selectable":false},
        {"x":14,"y":2,"w":2,"h":2,"i":"6<br>(木)", "static": false, "selectable":false},

        {"x":0,"y":4,"w":2,"h":1,"i":"午前", "static": false, "selectable":false},
        {"x":0,"y":5,"w":2,"h":1,"i":"日中", "static": false, "selectable":false},
        {"x":0,"y":6,"w":2,"h":1,"i":"夕方", "static": false, "selectable":false},
        
        {"x":2,"y":4,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":2,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":2,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},

        {"x":4,"y":4,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":4,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":4,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        
        {"x":6,"y":4,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":6,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":6,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},

        {"x":8,"y":4,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":8,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":8,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        
        {"x":10,"y":4,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":10,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":10,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        
        {"x":12,"y":4,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":12,"y":5,"w":2,"h":1,"i":"◯", "static": true, "selectable":true, "data":{"date":"2020-02-05", "week":"水", "time":["13:20~16:00"],"clinic":"表参道院"}},
        {"x":12,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        
        {"x":14,"y":4,"w":2,"h":1,"i":"◯", "static": true, "selectable":true, "data":{"date":"2020-02-05", "week":"木", "time":["09:20~12:00","11:20~14:00"],"clinic":"表参道院"}},
        {"x":14,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
        {"x":14,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},

    ];
    export default {
        data() {
            return {
                colNum: 16,
                activeColor: '',
                callayout: JSON.parse(JSON.stringify(calendarLayout)),

                passdata: toChooseMenu.data,
                price_content: '',
                selectedmenu: null,
                menus: [
                    { id: 1, name: 'アイブロウ２回コース　1/2'},
                    { id: 2, name: 'アイライン上2回コース　1/2'},
                    { id: 3, name: 'アイライン下２回コース　1/2'},                    
                ],
                sel_time_clinic: null,
                time_clinics:null,
            }
        },
        components: {        
            
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            onClickCalItem($event, item, index){
                if(item.selectable){
                    $(".vue-grid-item").removeClass("selectedcolor");
                    $(event.currentTarget).addClass("selectedcolor"); //defalt color when click..                     
                    toConfirmOrderInfo.data.calendar_info = item;
                    this.time_clinics = [];
                    item.data.time.forEach(element => {                                    
                        this.time_clinics.push(element + ' ' + item.data.clinic);
                    });
                    this.sel_time_clinic = this.time_clinics[0];
                    console.log(this.time_clinics);  
                }
            },
            onClickPriceBtn(){
                $('#modalPrice').modal('show');
            },
            onClickNextBtn:function(){
                toConfirmOrderInfo.data.menu_info = this.selectedmenu;
                console.log(toConfirmOrderInfo.data);
                this.$emit('changeStage', 2);
            },
            onClickPrevBtn:function(){
                toChooseMenu.data = null;
                this.$emit('changeStage', 2);
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
