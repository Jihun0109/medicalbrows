<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                    <h5 class="title">予約が完了しました</h5>
                    <div class="content-data">
                        予約番号： {{resultdata.order_serial_id}}
                        <br>
                        です。
                        <br>
                        <br>
                        同時に予約時に入力いただいた {{resultdata.mail}} 宛にも メール送信しております。
                        <br>
                        <br>
                        本予約番号はクリニック来訪時や再診時 予約キャンセル等でも使用しますので 大切に保管下さい
                        <br>
                        <br>
                        宜しくお願い致します。
                    </div>
                </div>
        </div>
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-auto mr-auto">
                    <button v-show="true" @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" >
                    <button  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9;">完了</button>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
    export default {
        data(){
            return{
                resultdata: gResultID.data,
            }
        },
        mounted() {
            
        },
        methods:{
            onClickPrevBtn(){
                this.$emit('changeStage', 4);
            },
            onClickNextBtn(){
                console.log(this.resultdata);
                this.$emit('changeStage', 0);
            }
        }
    }
</script>
<style lang="scss">
    .content-data{
        margin-top: 30px;
    }
</style>
