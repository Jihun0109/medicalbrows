<template>
    <div class="modal-dialog modal-dialog-centered" style="width:400px;">

        <div class="modal-content">
            <div class="modal-header">
                <div class="el-row">
                    <button v-for="(tab, index) in tabbtns" 
                            :key="index"
                            type="button"
                            @click="onClickStateBtn(index)"
                            class="el-button  el-button--primary el-button--medium" 
                            :class="['tab-btn', { setcolor: data.order_status === select_color[index] }]"                    
                    >
                        <span>{{tab}}</span>
                    </button>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="info">
                    <div class="row">
                        <label class="col-sm-3 col-form-label">予約ID:</label>
                        <div class="col-sm-8" >
                            <p>{{data.order_serial_id}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">施術日:</label>
                        <div class="col-sm-8" >
                            <p>{{data.date}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">院名:</label>
                        <div class="col-sm-8" >
                            <p>{{data.clinic_name}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">時間:</label>
                        <div class="col-sm-8" >
                            <p>{{data.time}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">区分:</label>
                        <div class="col-sm-8" >
                            <p>{{data.order_type}}</p>
                        </div>
                    </div>
                    <div class="row" style="letter-spacing:-1.8px;">
                        <label class="col-sm-3 col-form-label" v-if="data.rank_name === 'カウセ'">カウンセラー:</label>
                        <label class="col-sm-3 col-form-label" v-else>施術者:</label>
                        <div class="col-sm-8" >
                            <p style="letter-spacing:-1.5px">{{data.staff_name + '【' + data.rank_full_name + '】'}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">指名:</label>
                        <div class="col-sm-8" >
                            <p>{{data.staff_choosed}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">メニュー:</label>
                        <div class="col-sm-8" >
                            <p>{{data.menu_name}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label" style="letter-spacing:-1.5px" >カウンセラー:</label>
                        <div class="col-sm-8" >
                            <p>{{data.interviewer_name}}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">お客様名:</label>
                        <div class="col-sm-8" >
                            <p>{{data.customer_first_name}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">フリガナ:</label>
                        <div class="col-sm-8" >
                            <p>{{data.customer_last_name}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">メール:</label>
                        <div class="col-sm-8" >
                            <p>{{data.customer_email}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">生年月日:</label>
                        <div class="col-sm-8" >
                            <p>{{formatBirthday(data.customer_birthday)}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">電話番号:</label>
                        <div class="col-sm-8" >
                            <p>{{data.customer_phonenumber}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label" style="letter-spacing:-1.5px">予約ルー卜:</label>
                        <div class="col-sm-8" >
                            <p>{{data.order_route}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">備考:</label>
                        <div class="col-sm-10" >
                            <pre>{{data.note}}</pre>
                        </div>
                    </div>
                    <div class="experience">
                        <!-- <div class="row">
                            <label class="col-sm-7 col-form-label">経験:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-7 col-form-label">妊娠・授乳・不妊治療:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-7 col-form-label">通院歴・薬:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-8 col-form-label">金アレ・アトピー・ケロイド確認:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-7 col-form-label">眉ブリーチ・炎症・傷跡確認:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-7 col-form-label">美容サービス・美容整形確認:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-7 col-form-label">料金・所要時間:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-7 col-form-label">HP:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div> 
                        <div class="row">
                            <label class="col-sm-7 col-form-label">キャンセル規約:</label>
                            <div class="col-sm-3" >
                                <p></p>
                            </div>
                        </div>  -->
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a v-show="true" data-toggle="modal" href="#modalMailBox" class="btn btn-secondary" style="background:rgb(197, 224, 180); color:black" >メール送信</a>
                <button v-show="changeMode" type="button" class="btn btn-secondary" style="background:rgb(197, 224, 180); color:black" data-dismiss="modal">予約取消</button>
                <a data-toggle="modal" href="#modalUpdateDlg" class="btn btn-primary" style="background:rgb(197, 224, 180); color:black">変更</a>
            </div>
        </div>

    </div>

</template>

<script>

    export default {
        props:['data','clinic'],
        data () {
            return {
                tabbtns:['来院','会計','終了','キャンセル'],
                select_color:['neworder','oldorder','grayconselor','cancelorder'],
                tabindex: '',
                customer:{},
                dialog: false,
                changeMode:false,
            }
        },
        mounted () {

        },
        created(){
            Bus.$on('sendCustInfo',(customerData) =>{
                this.customer = customerData;
            });     
            Bus.$on('confirmClicked', this.statusChange);
        },

        methods: {
            onClickStateBtn(index){
                if(this.data.order_status !== this.select_color[index]){
                    this.tabindex = index;
                    this.$emit('changedStatus', this.tabbtns[index]);
                    $('#modalMessageBox').modal('show');
                }
                else{
                    this.tabindex = -1;
                    this.$emit('changedStatus', 'オリジナル');
                    $('#modalMessageBox').modal('show');
                } 
            },
            statusChange() {                
                axios.post('/v1/order-statusupdate',{ 'item': this.data, 'statusIdx': this.tabindex})
                .then((result)=>{
                     toast.fire({
                        icon: "success",
                        title: "状態の変更に成功"
                    });
                    //this.data.order_status = this.select_color[this.tabindex];//아래서 직접 변경하므로 막는다 (상태변경 중복됨.)
                    //console.log(result.data);
                    this.$emit('updateCellStatus', result.data);
                })
                .catch(()=>{
                    console.log('update error');
                }); 
            },

            changeBtnClick(){
                console.log("Click edit button from info modal");
                this.changeMode = false;
                app.$refs.modalUpdateDlg.loadInfo();
                $('#modalShowUpdate').modal('show');
            },

            formatBirthday(birthday){
                return birthday + " (" + moment().diff(birthday, 'years')+"歳)";
            }

        }
    }
</script>
<style lang="scss">
    .modal-header .setcolor{
        background:#76DBF8;
    }
    .modal-body{
        padding-left: 30px;
        padding-right: 8px;
    }
    .info {
        font-size:14px;
        .experience{
            .row{
                margin-bottom: -5px;
            }
        }
        div{
            padding-left: 10px;
        }
        label{
            padding:2px;
        }
        p{
            margin-bottom:0px;
        }
    }

</style>