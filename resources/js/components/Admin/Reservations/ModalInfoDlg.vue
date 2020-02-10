<template>
    <div class="modal-dialog modal-dialog-centered" style="width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="el-row"> 
                    <button v-for="tab in tabbtns" 
                            :key="tab"                     
                            type="button" 
                            @click="selected = tab;" 
                            class="el-button  el-button--primary el-button--medium" 
                            :class="['tab-btn', { active: selected === tab }]"                    
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
                    <div>予約ID : {{data.order_serial_id}}</div>
                    <div>施術日 : {{data.date}}</div>
                    <div>院名 : {{data.clinic_name}}</div>
                    <div>時間 : {{data.time}}</div>
                    <div>区分 : </div>
                    <div>施術者 : {{data.staff_name}}</div>
                    <div>指名 : {{data.staff_choosed}}</div>
                    <div>メニュー : {{data.menu_name}}</div>
                    <div>カウンセラー : </div>
                    <br>
                    <div>お客様各 : {{data.customer_first_name}}</div>
                    <div>生年月日 : {{data.customer_birthday}}</div>
                    <div>電話番号 : {{data.customer_phonenumber}}</div>
                    <div>予約ルー卜 : {{data.order_route}}</div>
                    <div>備考 : </div>
                    <div class="experience">
                        <div>経験 : </div>
                        <div>妊娠・授乳・不妊治療 : </div>
                        <div>通院歴・薬 : </div>
                        <div>金アレ・アトピー・ケロイド確認 : </div>
                        <div>眉ブリーチ・炎症・傷跡確認 : </div>
                        <div>美容サービス・美容整形確認 : </div> 
                        <div>料金・所要時間 : </div>
                        <div>HP : </div>
                        <div>キャンセル規約 : </div>    
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
    .info {
        div{
            padding-left: 10px;
        }
    }

</style>