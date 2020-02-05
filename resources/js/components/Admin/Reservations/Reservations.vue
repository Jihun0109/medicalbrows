<template>
    <div class="container">
                <!-- Modal -->
        <div class="modal fade" id="modalShowInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <ModalDialog></ModalDialog>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalShowUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">ランク更新</h5>
                        <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">ランク追加</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateBtn() : updateBtn()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" class="form-control" :class="{'is-invalid':form.errors.has('name')}" placeholder="ランク名前">
                                <has-error :form="form" field="name"></has-error>
                            </div>                    
                        </div>
                        <div class="modal-footer">
                            <button v-show="false" type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                            <button v-show="editMode" type="submit" class="btn btn-success">s更新</button>
                            <button v-show="!editMode" type="submit" class="btn btn-primary">s更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h4 style="text-align: center">予約管理システム（予約管理)</h4>
            <div style="display:flex">
                <div id="calendar" class="col-md-4">
                    <Datepicker v-model="current_date"  @selected="dateSelected()" format="YYYY-MM-DD" width="80px"/>
                </div>
                <!-- 
                <button type="button" class="el-button  el-button--primary el-button--medium" style="margin-left: 3px; width:80px; background-color:rgb(27, 185, 175); color: black" @click="dateSelected()">
                        <span>confirm</span>
                </button>
                -->

            </div>

        <!--<datetime format="MM/DD/YYYY" width="300px" name='dob'></datetime>-->
        <div class="el-row"> 
            <button v-for="c in clinics" :key="c.id" type="button" @click="clinicSelected(c.id)" class="el-button  el-button--primary el-button--medium" style="margin-left: 3px; background-color:rgb(27, 185, 175); color: black">
                <span>{{c.name}}</span>
            </button>
            <!-- 
            <button type="button" class="el-button el-button--medium" style="margin-left: 3px; width:80px; color: black">
                <span>六本木</span>
            </button>-->
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="headerdiv">
                        <grid-layout 
                            :layout.sync="hdlayout"
                            :col-num="22"
                            :row-height="30"
                            :is-draggable="false"
                            :is-resizable="false"
                            :is-mirrored="false"
                            :vertical-compact="true"
                            :margin="[-1, -1]"
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
                            :col-num="22"
                            :row-height="30"
                            :is-draggable="false"
                            :is-resizable="false"
                            :is-mirrored="false"
                            :vertical-compact="true"
                            :margin="[-1, -1]"
                            :use-css-transforms="true"
                        >
                            <grid-item @click.native="onClick($event, item.x, item.y)" v-for="(item, index) in conlayout"
                                    :x="item.x"
                                    :y="item.y"
                                    :w="item.w"
                                    :h="item.h"
                                    :i="item.i"
                                    :key="index  + '-separator'"
                                    :static="item.static"
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
    .vue-grid-layout {
        background: #eee;
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
    }

    .contentdiv{
        .vue-grid-item.static {
            background: #ccc;           
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

