<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                    <h5 class="title">予約が完了しました</h5>
                    <div class="content-data">
                        予約番号： 011-XXXXXXXXXXXX
                        <br>
                        です。
                        <br>
                        <br>
                        同時に予約時に入力いただいた hanako@aa.com 宛にも メール送信しております。
                        <br>
                        <br>
                        本予約番号はクリニック来訪時や再診時 予約キャンセル等でも使用しますので 大切に保管下さい
                        <br>
                        <br>
                        宜しくお願い致します。
                    </div>
                </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style lang="scss">
    .content-data{
        margin-top: 30px;
    }
</style>
