<template>
    <div class="">
        <div class="order-info">
            <label class="col-8">＜予約者情報＞</label>
            <div class="info">
                <div class="row">
                    <label class="col-4 col-form-label">日付：</label>
                    <div class="col" >
                        <p>{{calendar_info.date}}({{calendar_info.week}})</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">区分：</label>
                    <div class="col" >
                        <p>{{order_type}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">時間：</label>
                    <div class="col" >
                        <p>{{calendar_info.time}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">場所：</label>
                    <div class="col" >
                        <p>{{clinic_info.name}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術者：</label>
                    <div class="col" >
                        <p>{{staff_info.name}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術メニュー：</label>
                    <div class="col" >
                        <p>{{menu_info.name}}</p>
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
                calendar_info: toConfirmOrderInfo.data.calendar_info,
                order_type: toChooseMenu.data.order_type,
                clinic_info: toChooseMenu.data.clinic_info,
                staff_info: toChooseMenu.data.staff_info,
                menu_info: toConfirmOrderInfo.data.menu_info,
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
                values: toConfirm_UserInfo.array,
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
