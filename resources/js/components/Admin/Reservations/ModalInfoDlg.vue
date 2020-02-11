<template>
    <div class="modal-dialog modal-dialog-centered" style="width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="el-row"> 
                    <button v-for="(tab, index) in tabbtns" 
                            :key="tab"                     
                            type="button" 
                            @click="onClickStateBtn(tab, index)" 
                            class="el-button  el-button--primary el-button--medium" 
                            :class="['tab-btn', { setcolor: selected === tab }]"                    
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
                    <div class="row">
                        <label class="col-sm-3 col-form-label">施術者:</label>
                        <div class="col-sm-8" >
                            <p style="letter-spacing:-1.5px">{{data.staff_name + data.rank_full_name}}</p>
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
                            <p>-</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">お客様各:</label>
                        <div class="col-sm-8" >
                            <p>{{data.customer_first_name}}</p>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-3 col-form-label">生年月日:</label>
                        <div class="col-sm-8" >
                            <p>{{data.customer_birthday}}</p>
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
                        <div class="col-sm-8" >
                            <p></p>
                        </div>
                    </div>
                    <div class="experience">
                        <div class="row">
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
                        </div> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button v-show="changeMode" type="button" class="btn btn-secondary" style="background:rgb(197, 224, 180); color:black" data-dismiss="modal">メール送信</button>
                <button v-show="changeMode" type="button" class="btn btn-secondary" style="background:rgb(197, 224, 180); color:black" data-dismiss="modal">予約取消</button>
                <a data-toggle="modal" href="#modalUpdateDlg" class="btn btn-primary" style="background:rgb(197, 224, 180); color:black">変更</a>
            </div>
        </div>

    </div>

</template>

<script>

    export default {
        props:['data'],
        data () {
            return {
                tabbtns:['来院','会計','終了','キャンセル'],
                selected:'来院',
                customer:{},
                dialog: false,
                changeMode:false,
                updatedOrderInfo:store.orderdata,
            }
        },
        mounted () {
            //this.getUsers()
        },
        created(){
            Bus.$on('sendCustInfo',(customerData) =>{
                this.customer = customerData;
            });
        },
        methods: {
            onClickStateBtn(clickedtab, index){
                this.selected = clickedtab;
                var status = '';
                switch(index)
                {
                    case 0:
                        status = 'neworder';
                        break;
                    case 1:
                        status = 'oldorder';
                        break;
                    case 2:
                        status = 'grayconselor';
                        break;
                    case 3:
                        status = 'cancelorder'
                }
                this.data.order_status = status;
                console.log(this.data);
                axios.post('v1/order-statusupdate',{ item: this.data })
                .then((result)=>{
                    console.log(result.data);
                    toast.fire({
                        icon: "success",
                        title: "A status was update successfully."
                    });
                    this.$emit('statusUpdated', result.data);
                })
                .catch(()=>{
                    console.log('update error');
                });  
            },
            changeBtnClick(){
                this.changeMode = false;
                console.log("Updating ...");
                console.log(app.$refs.modalUpdateDlg);
                app.$refs.modalUpdateDlg.loadInfo();
                $('#modalShowUpdate').modal('show');
            }, 
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