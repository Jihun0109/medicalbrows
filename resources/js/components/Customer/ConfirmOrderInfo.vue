<template>
    <div class="">
        <div class="order-info">
            <label class="col-8">＜予約者情報＞</label>
            <div class="info">
                <div class="row">
                    <label class="col-4 col-form-label">日付：</label>
                    <div class="col" >
                        <p>{{formatDate(order_info.calendar_info.date)}}({{order_info.calendar_info.week}})</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">区分：</label>
                    <div class="col" >
                        <p>{{order_info.order_type}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">時間：</label>
                    <div class="col" >
                        <p>{{order_info.time_schedule_info.start_time}}~{{order_info.time_schedule_info.end_time}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">場所：</label>
                    <div class="col" >
                        <p>{{order_info.clinic_info.name}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術者：</label>
                    <div class="col" >
                        <p>{{order_info.staff_info.name}}【{{order_info.rank_info.name}}】</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術メニュー：</label>
                    <div class="col" >
                        <p>{{order_info.menu_info.name}}</p>
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
                    <button @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    export default {
        data() {
            return {     
                order_info:gOrderInfo.data,  
                values: gUserInfo.array,
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
            }
        },
        mounted() {
            
        },
        methods:{
            onClickNextBtn:function(){

                this.$emit('changeStage', 4);
            },
            onClickPrevBtn:function(){
                this.$emit('changeStage', 2);
            },
            formatDate(dt) {
                dt = new Date(dt);
                var month = ('0' + (dt.getMonth() + 1)).slice(-2);
                var date = ('0' + dt.getDate()).slice(-2);
                var year = dt.getFullYear();
                var formattedDate = year + '年' + month + '月' + date + '日';
                //var formattedDate = year + '-' + month + '-' + date;
                return formattedDate;
            },
        }
    }
</script>

<style lang="scss">
    .customer-info{
        margin-top: 20px;
    }
</style>
