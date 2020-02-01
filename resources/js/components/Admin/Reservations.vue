<template>
    <div class="container">

                            <div id="calendar">
                        <Datepicker format="YYYY-MM-DD" width="80px"/>
                    </div>
                                        <!--<datetime format="MM/DD/YYYY" width="300px" name='dob'></datetime>-->
                    <div class="el-row"> 
                        <button type="button" class="el-button  el-button--primary el-button--medium" style="margin-left: 3px; width:80px; background-color:rgb(27, 185, 175); color: black">
                            <span>表参道</span>
                        </button>
                        <button type="button" class="el-button el-button--medium" style="margin-left: 3px; width:80px; color: black">
                            <span>六本木</span>
                        </button>
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
                            <grid-item v-for="item in hdlayout"
                                :x="item.x"
                                :y="item.y"
                                :w="item.w"
                                :h="item.h"
                                :i="item.i"
                                :key="item.i"
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

                            <grid-item v-for="item in conlayout"
                                    :x="item.x"
                                    :y="item.y"
                                    :w="item.w"
                                    :h="item.h"
                                    :i="item.i"
                                    :key="item.i"
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

<script>
    import VueGridLayout from 'vue-grid-layout';
    import datetime from 'vuejs-datetimepicker';
    import Datepicker from 'vuejs-datetimepicker';
    var hdLayout = [
        {"x":0,"y":0,"w":2,"h":2,"i":"", resizable: true, draggable: false, static: true},
        {"x":2,"y":0,"w":2,"h":1,"i":"1", resizable: true, draggable: false, static: true},
        {"x":4,"y":0,"w":2,"h":1,"i":"2", resizable: true, draggable: false, static: true},
        {"x":6,"y":0,"w":2,"h":1,"i":"3", resizable: true, draggable: false, static: true},
        {"x":8,"y":0,"w":2,"h":1,"i":"4", resizable: true, draggable: false, static: true},
        {"x":10,"y":0,"w":2,"h":1,"i":"5", resizable: true, draggable: false, static: true},
        {"x":12,"y":0,"w":2,"h":1,"i":"6", resizable: true, draggable: false, static: true},
        {"x":14,"y":0,"w":2,"h":1,"i":"7", resizable: true, draggable: false, static: true},
        {"x":16,"y":0,"w":2,"h":1,"i":"8", resizable: true, draggable: false, static: true},
        {"x":18,"y":0,"w":2,"h":1,"i":"9", resizable: true, draggable: false, static: true},
        {"x":20,"y":0,"w":2,"h":1,"i":"10", resizable: true, draggable: false, static: true},

        {"x":2,"y":1,"w":2,"h":1,"i":"1", resizable: true, draggable: false, static: true},
        {"x":4,"y":1,"w":2,"h":1,"i":"2", resizable: true, draggable: false, static: true},
        {"x":6,"y":1,"w":2,"h":1,"i":"3", resizable: true, draggable: false, static: true},
        {"x":8,"y":1,"w":2,"h":1,"i":"4", resizable: true, draggable: false, static: true},
        {"x":10,"y":1,"w":2,"h":1,"i":"5", resizable: true, draggable: false, static: true},
        {"x":12,"y":1,"w":2,"h":1,"i":"6", resizable: true, draggable: false, static: true},
        {"x":14,"y":1,"w":2,"h":1,"i":"7", resizable: true, draggable: false, static: true},
        {"x":16,"y":1,"w":2,"h":1,"i":"8", resizable: true, draggable: false, static: true},
        {"x":18,"y":1,"w":2,"h":1,"i":"9", resizable: true, draggable: false, static: true},
        {"x":20,"y":1,"w":2,"h":1,"i":"10", resizable: true, draggable: false, static: true},
    ];
    var contentLayout = [
        {"x":0,"y":0,"w":2,"h":3,"i":"9:00", resizable: false, draggable: false, static: false},
        {"x":0,"y":3,"w":2,"h":3,"i":"10:00", resizable: false, draggable: false, static: false},
        {"x":0,"y":6,"w":2,"h":3,"i":"11:00", resizable: false, draggable: false, static: false},
        {"x":0,"y":9,"w":2,"h":3,"i":"12:00", resizable: false, draggable: false, static: false},
        {"x":0,"y":12,"w":2,"h":3,"i":"13:00", resizable: false, draggable: false, static: false},
        {"x":0,"y":15,"w":2,"h":3,"i":"14:00", resizable: false, draggable: false, static: false},

        {"x":2,"y":0,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":2,"y":3,"w":2,"h":3,"i":"1", resizable: false, draggable: false, static: false},
        {"x":2,"y":6,"w":2,"h":3,"i":"1", resizable: false, draggable: false, static: false},
        {"x":2,"y":9,"w":2,"h":3,"i":"1", resizable: false, draggable: false, static: false},
        {"x":2,"y":12,"w":2,"h":3,"i":"1", resizable: false, draggable: false, static: false},
        {"x":2,"y":15,"w":2,"h":3,"i":"1", resizable: false, draggable: false, static: false},

        {"x":4,"y":0,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":4,"y":3,"w":2,"h":6,"i":"再", resizable: false, draggable: false, static: true},
        {"x":4,"y":6,"w":2,"h":0,"i":"2", resizable: false, draggable: false, static: false},
        {"x":4,"y":9,"w":2,"h":3,"i":"2", resizable: false, draggable: false, static: false},
        {"x":4,"y":12,"w":2,"h":3,"i":"2", resizable: false, draggable: false, static: false},
        {"x":4,"y":15,"w":2,"h":3,"i":"2", resizable: false, draggable: false, static: false},


        {"x":6,"y":2,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":6,"y":5,"w":2,"h":3,"i":"3", resizable: false, draggable: false, static: false},
        {"x":6,"y":8,"w":2,"h":3,"i":"3", resizable: false, draggable: false, static: false},
        {"x":6,"y":11,"w":2,"h":3,"i":"3", resizable: false, draggable: false, static: false},
        
        {"x":8,"y":2,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":10,"y":2,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":12,"y":2,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":14,"y":2,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":16,"y":2,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":18,"y":2,"w":2,"h":3,"i":"", resizable: false, draggable: false, static: false},
        {"x":20,"y":0,"w":2,"h":2,"i":"", resizable: false, draggable: false, static: false},
        {"x":20,"y":2,"w":2,"h":1,"i":"再", resizable: false, draggable: false, static: true},

        
        
        
        
        {"x":8,"y":5,"w":2,"h":3,"i":"4", resizable: false, draggable: false, static: false},
        {"x":10,"y":5,"w":2,"h":3,"i":"5", resizable: false, draggable: false, static: false},
        {"x":12,"y":5,"w":2,"h":3,"i":"6", resizable: false, draggable: false, static: false},
        {"x":14,"y":5,"w":2,"h":3,"i":"7", resizable: false, draggable: false, static: false},
        {"x":16,"y":5,"w":2,"h":3,"i":"8", resizable: false, draggable: false, static: false},
        {"x":18,"y":5,"w":2,"h":3,"i":"9", resizable: false, draggable: false, static: false},
        {"x":20,"y":5,"w":2,"h":3,"i":"10", resizable: false, draggable: false, static: false},

        
        
        
        
        {"x":8,"y":8,"w":2,"h":3,"i":"4", resizable: false, draggable: false, static: false},
        {"x":10,"y":8,"w":2,"h":3,"i":"5", resizable: false, draggable: false, static: false},
        {"x":12,"y":8,"w":2,"h":3,"i":"6", resizable: false, draggable: false, static: false},
        {"x":14,"y":8,"w":2,"h":3,"i":"7", resizable: false, draggable: false, static: false},
        {"x":16,"y":8,"w":2,"h":3,"i":"8", resizable: false, draggable: false, static: false},
        {"x":18,"y":8,"w":2,"h":3,"i":"9", resizable: false, draggable: false, static: false},
        {"x":20,"y":8,"w":2,"h":3,"i":"10", resizable: false, draggable: false, static: false},

        
        
        
        
        {"x":8,"y":11,"w":2,"h":3,"i":"4", resizable: false, draggable: false, static: false},
        {"x":10,"y":11,"w":2,"h":3,"i":"5", resizable: false, draggable: false, static: false},
        {"x":12,"y":11,"w":2,"h":3,"i":"6", resizable: false, draggable: false, static: false},
        {"x":14,"y":11,"w":2,"h":3,"i":"7", resizable: false, draggable: false, static: false},
        {"x":16,"y":11,"w":2,"h":5,"i":"再", resizable: false, draggable: false, static: true},
        {"x":18,"y":11,"w":2,"h":3,"i":"9", resizable: false, draggable: false, static: false},
        {"x":20,"y":11,"w":2,"h":3,"i":"10", resizable: false, draggable: false, static: false},

        
        
        
        {"x":6,"y":14,"w":2,"h":3,"i":"3", resizable: false, draggable: false, static: false},
        {"x":8,"y":14,"w":2,"h":3,"i":"4", resizable: false, draggable: false, static: false},
        {"x":10,"y":14,"w":2,"h":3,"i":"5", resizable: false, draggable: false, static: false},
        {"x":12,"y":14,"w":2,"h":3,"i":"6", resizable: false, draggable: false, static: false},
        {"x":14,"y":14,"w":2,"h":3,"i":"7", resizable: false, draggable: false, static: false},
        {"x":16,"y":14,"w":2,"h":1,"i":"8", resizable: false, draggable: false, static: false},
        {"x":18,"y":14,"w":2,"h":3,"i":"9", resizable: false, draggable: false, static: false},
        {"x":20,"y":14,"w":2,"h":3,"i":"10", resizable: false, draggable: false, static: false},

        
        
        
        {"x":6,"y":17,"w":2,"h":3,"i":"3", resizable: false, draggable: false, static: false},
        {"x":8,"y":17,"w":2,"h":3,"i":"4", resizable: false, draggable: false, static: false},
        {"x":10,"y":17,"w":2,"h":3,"i":"5", resizable: false, draggable: false, static: false},
        {"x":12,"y":17,"w":2,"h":3,"i":"6", resizable: false, draggable: false, static: false},
        {"x":14,"y":17,"w":2,"h":3,"i":"7", resizable: false, draggable: false, static: false},
        {"x":16,"y":17,"w":2,"h":3,"i":"8", resizable: false, draggable: false, static: false},
        {"x":18,"y":17,"w":2,"h":3,"i":"9", resizable: false, draggable: false, static: false},
        {"x":20,"y":17,"w":2,"h":3,"i":"10", resizable: false, draggable: false, static: false},
    ];

    export default {
        components: {
                GridLayout: VueGridLayout.GridLayout,
                GridItem: VueGridLayout.GridItem,
                datetime,
                Datepicker           
        },
        data () {
            return {
                numbers: [ 1, 2, 3, 4, 5 ],
                hdlayout: JSON.parse(JSON.stringify(hdLayout)),
                conlayout: JSON.parse(JSON.stringify(contentLayout)),
                draggable: false,
                resizable: false,
                mirrored: false,
                responsive: true,
                preventCollision: false,
                rowHeight: 30,
                colNum: 22,
                index: 0
            }
        },
        mounted() {
            console.log('Component mounted.');
            this.index = this.layout.length;
        },
        methods: {
            evenNumbers: function () {
                return this.numbers.filter(function (number) {
                return number % 2 === 0
                })
            },
            alertVal () {
                alert(this.dob)
            },
            itemTitle(item) {
                var result = item.i;
                if (item.static) {
                    result += " - Static";
                }
                return result;
            },
            removeItem: function(item) {
                //console.log("### REMOVE " + item.i);
                this.layout.splice(this.layout.indexOf(item), 2);
            },
            addItem: function() {
                // let self = this;
                //console.log("### LENGTH: " + this.layout.length);
                //newX = 0;
                //newY = 0;
                let item = {"x":3,"y":2,"w":2,"h":2,"i":this.index+"", whatever: "bbb"};
                this.index++;
                this.layout.push(item);
            },
        }
    }
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

    .vue-grid-item.isDraggable {
        //background: #ccc;
        background: rgb(0, 176, 240);
    }

    .vue-grid-item.vue-draggable-dragging {
        background: rgb(0, 176, 240);
    }

    .vue-grid-item.isHeader {
        /*background: #cce;*/
        background: rgb(0, 176, 240);
    }

    .vue-grid-item .text {
        //font-size: 24px;
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

