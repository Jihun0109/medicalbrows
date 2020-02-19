<template>
    <div class="container">
        <!-- Info Modal -->
        <div class="modal fade" id="modalInfoDlg">
            <ModalInfoDlg v-bind:data="this.item" @statusUpdated="onOrderCreated" v-on:changedStatus="onStatusChanged"></ModalInfoDlg>
        </div>
        <!-- Update Modal -->
        <div class="modal fade" id="modalUpdateDlg" data-backdrop="static">
            <ModalUpdateDlg v-bind:sr_list="this.staff_rank_list" :item="this.item" 
                v-bind:menus="this.menus" v-bind:counselors="this.counselors" @orderCreated="onOrderCreated" :childbus="bus" ref="modalUpdateDlg"></ModalUpdateDlg>
        </div>
        <!-- Info Modal -->
        <div id="modalMessageBox" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- dialog body -->
                    <div class="modal-body justify-content-center" style="padding: 70px; font-width:blod;">
                        ステータスを「{{order_status}}」に変更して宜しいですか？
                    </div>
                    <!-- dialog buttons -->
                    <div class="modal-footer justify-content-center"> 
                        <a href="#" data-dismiss="modal" class="btn" style="width:100px; background:#707070; color:black; margin-right: 60px;">キャンセル</a>
                        <a href="#" v-on:click="confirmBtn" class="btn btn-primary" style="width:100px; background:#1BB9AF; color:black">OK</a>                      
                    </div>
                </div>
            </div>
        </div>

        <h4 style="text-align: center">予約管理システム（予約管理)</h4>

        <div class="row d-flex justify-content-center" style="padding-bottom: 15px; padding-top: 20px;">
            <button @click="decrement()" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                <v-date-picker
                    locale="ja"
                    mode='single'
                    v-model='selectedDate'   
                    is-double-paned                         
                    :attributes='attrs'
                    ref="calendar1"
                    :popover="{ placement: 'bottom', visibility: 'click' }"
                >
                    <input type="text" slot-scope='props' :value='props.inputValue' class="form-control">
                </v-date-picker>
            <button @click="increment()" type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
        </div>

        <!--<div id="calendar" class="col-md-4">
            <Datepicker v-model="current_date"  @selected="dateSelected()" format="YYYY-MM-DD" width="80px"/>
        </div>
        -<datetime format="MM/DD/YYYY" width="300px" name='dob'></datetime>-->
        
        <div class="el-row" v-if="$gate.isAdmin()">
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
        
        <div class="row justify-content-center" >
            <div class="col-12">
                <div class="card">
                    <div class="headerdiv">
                        <grid-layout 
                            :layout.sync="hdlayout"
                            :col-num="this.colNum"
                            :row-height="30"
                            :is-draggable="false"
                            :is-resizable="false"
                            :vertical-compact="true"
                            :margin="[-2, -1]"
                            :use-css-transforms="true"
                        >
                            <grid-item v-for="(item, index) in hdlayout"
                                :x="item.x"
                                :y="item.y"
                                :w="item.w"
                                :h="item.h"
                                :i="item.i"
                                :key="index + '-label'"
                                :static="item.static"
                                >
                                <span class="text">{{item.i}}</span>
                            </grid-item>
                        </grid-layout>
                    </div>

                    <div class="contentdiv">
                        <grid-layout
                            :layout.sync="conlayout"
                            :col-num="this.colNum"
                            :row-height="30"
                            :is-draggable="false"
                            :is-resizable="false"
                            :vertical-compact="true"
                            :margin="[ -2 , -1]"
                            :use-css-transforms="true"
                        >
                            <grid-item @click.native="onCellClicked($event, item, index)" v-for="(item, index) in conlayout"
                                    :x="item.x"
                                    :y="item.y"
                                    :w="item.w"
                                    :h="item.h"
                                    :i="item.i"
                                    :key="index  + '-separator'"
                                    :static="item.static"                                   
                                    :class="[item.order_status, {selectedcolor: index === activeColor}]"
                                    >
                                    <div class="text" v-html="item.i"></div>
                                    <!-- <span class="text">{{item.i}}</span>-->
                            </grid-item>
                        </grid-layout>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script src="./Reservations">

</script>

<style lang="scss">
    @media screen and (max-width: 600px) {
        .vue-grid-item .text {
            font-size: 0.5em;
        }
    }


    @media screen and (max-width: 992px) {
        #container .vue-grid-item .text {
            font-size: 0.6em !important;
        }
    }

    #app-container {
        width: 100%;
        margin-top: 10px;
    }
    #calendar{
        margin: 0 0px 20px 20px;
    }
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
        background:#1BB9AF;
    }

    .vue-grid-layout {
        background: #eee;
    }

    .contentdiv{
        .vue-grid-item.static {
            background: #ccc;           
        }
        .vue-grid-item.selectedcolor{
            background:#649ABA !important;
        }
        .vue-grid-item.neworder{
            background:#E891DC;
            color:white;
        }
        .vue-grid-item.oldorder{
            background:#D511BA;
            color:white;
        }
        .vue-grid-item.cancelorder{
            background:#3C3A3C;
            color:white;
        }
        .vue-grid-item.grayconselor{
            background:rgb(160,149,201);
        }        
    }

    .layoutJSON {
        background: #ddd;
        border: 1px solid black;
        margin-top: 10px;
        padding: 10px;
    }

    .eventsJSON {
        background: #ddd;
        border: 1px solid black;
        margin-top: 10px;
        padding: 10px;
        height: 100px;
        overflow-y: scroll;
    }

    .columns {
        -moz-columns: 120px;
        -webkit-columns: 120px;
        columns: 120px;
    }

    .vue-grid-item {
        display: table;
        background: white;
    }

    .vue-grid-item:not(.vue-grid-placeholder) {
        /*background: #ccc;*/
        //background: white;
        border: 1px solid black;
    }

    .vue-grid-item.resizing {
        background: rgb(0, 176, 240);
        opacity: 0.9;
    }
    .headerdiv{
        .vue-grid-item.static {
            background: rgb(0, 176, 240);
        }
    }

    .vue-grid-item .text {
        font-size: 0.8em;
        text-align: center;
        /*position: absolute;*/
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        height: 100%;
        width: 100%;
        display: table-cell;
        vertical-align: middle;
    }

    .vue-grid-item .no-drag {
        height: 100%;
        width: 100%;
    }

    .vue-grid-item .minMax {
        font-size: 12px;
    }

    .vue-grid-item .add {
        cursor: pointer;
    }

</style>

