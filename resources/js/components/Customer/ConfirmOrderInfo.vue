<template>
    <div class="">
        <div class="order-info">
            <label class="col-8">＜予約者情報＞</label>
            <div class="info">
                <div class="row">
                    <label class="col-4 col-form-label">日付：</label>
                    <div class="col" >
                        <p>2020年2月6日(木)</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">区分：</label>
                    <div class="col" >
                        <p>再診</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">時間：</label>
                    <div class="col" >
                        <p>09:20 ～ 13:00</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">場所：</label>
                    <div class="col" >
                        <p>表参道院</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術者：</label>
                    <div class="col" >
                        <p>小澤 アーティスト</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術メニュー：</label>
                    <div class="col" >
                        <p>アイブロウ2回コース 1/2</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="customer-info">
            <label class="col-8">＜お客様情報＞</label>
            <div class="info">
                <div class="row" v-for="(type, index) in types" :key="index" style="padding-bottom:5px;">
                    <label class="col-4 col-form-label" :for="`type-${index}`">{{ type }}</label>
                    <div class="col" >
                        <p :id="`type-${index}`">{{ values[index] }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    export default {
        data() {
            return {
                types: [
                    '氏名：',
                    'フリガナ：',
                    '性別：',
                    '生年月日：',
                    '電話番号：',
                    'email：',
                    '郵便番号：',
                    '都道府県：',
                    '住所1：',
                    '住所2：'
                ],
                values: [
                    '麻布 花子',
                    'アザブ ハナコ',
                    '女性',
                    '1989/12/01',
                    '080-XXXX-XXXX',
                    'hanako@aa.com',
                    '106-0031',
                    '東京都',
                    '港区西麻布',
                    '1-14-17'
                ],
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style lang="scss">
    .customer-info{
        margin-top: 20px;
    }
</style>
