<template>
    <div class="container">
        <!-- Info Modal -->
        <div class="modal fade" id="modalInfoDlg">
            <ModalInfoDlg v-bind:data="this.item"></ModalInfoDlg>
        </div>
        <!-- Update Modal -->
        <div class="modal fade" id="modalUpdateDlg" data-backdrop="static">
            <ModalUpdateDlg v-bind:sr_list="this.staff_rank_list" :item="this.item" 
                v-bind:menus="this.menus" v-bind:counselors="this.counselors" @orderCreated="onOrderCreated"></ModalUpdateDlg>
        </div>

        <h4 style="text-align: center">予約管理システム（予約管理)</h4>

        <div id="calendar" class="col-md-4">
            <Datepicker v-model="current_date"  @selected="dateSelected()" format="YYYY-MM-DD" width="80px"/>
        </div>
        <!--<datetime format="MM/DD/YYYY" width="300px" name='dob'></datetime>-->
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
                            <grid-item @click.native="onClick($event, item, index)" v-for="(item, index) in conlayout"
                                    :x="item.x"
                                    :y="item.y"
                                    :w="item.w"
                                    :h="item.h"
                                    :i="item.i"
                                    :key="index  + '-separator'"
                                    :static="item.static"                                   
                                    :class="{before: index === activeColor}"
                                    >
                                <span class="text">{{item.i}}</span>
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
        background:rgb(27, 185, 175);
    }

    .vue-grid-layout {
        background: #eee;
    }

    .contentdiv{
        .vue-grid-item.static {
            background: #ccc;           
        }
        .vue-grid-item.before{
            background:#649ABA;
        }
        .vue-grid-item.activecol{
            background:#E891DC;
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
        //background: #ccc;
            background: rgb(0, 176, 240);
        }
                .vue-grid-item.before {
        //background: #ccc;
            background: red;
        }
    }

    .vue-grid-item .text {
        font-size: 12px;
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

