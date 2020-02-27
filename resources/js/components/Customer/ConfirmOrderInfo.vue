<template>
    <div class="">
        <div class="orderid-info" v-show="existId" v-if="order_id_info.old_order_info != null">
            <label class="col-8">＜予約情報＞</label>
            <div class="info">
                <div class="row">
                    <label class="col-4 col-form-label">日付：</label>
                    <div class="col" >
                        <p>{{formatDate(order_id_info.old_order_info.order_date)}}({{order_id_info.old_order_info.week}})</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">区分：</label>
                    <div class="col" >
                        <p>{{order_id_info.old_order_info.order_type}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">時間：</label>
                    <div class="col" >
                        <p>{{order_id_info.old_order_info.time_schedule}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">場所：</label>
                    <div class="col" >
                        <p>{{order_id_info.old_order_info.clinic_info.name}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術者：</label>
                    <div class="col" >
                        <p>{{order_id_info.old_order_info.staff_info.alias}}【{{order_id_info.old_order_info.rank_info.name}}】</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術メニュー：</label>
                    <div class="col" >
                        <p>{{order_id_info.old_order_info.menu_info.name}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="order-info" v-show="!existId">
            <label class="col-8">＜予約情報＞</label>
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
                        <p :id="`type-${index}`">{{ user_infos.array[index] }}</p>
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
    window.gResultID = {
            data:{
                mail:'',
                order_serial_id:'',
            }     
        };
    export default {
        props:['existId'],
        data() {
            return {     
                order_id_info:gIDInfo.data,//defined ExistReservation.vue
                order_info:gOrderInfo.data,  
                user_infos: gUserInfo.data,
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
                if(this.existId){
                    axios.post('/v1/client/order_update',{ 'order_info': this.order_id_info, 'user_info': gUserInfo.data.userinfo})
                    .then((result)=>{
                        if(result.data == 0){
                            toast.fire({
                                icon: "error",
                                title: "予約IDが存在しません。"
                            });
                        }else{
                            toast.fire({
                                icon: "success",
                                title: "データが更新されました。"
                            });
                            console.log(result.data);
                            gResultID.data.mail = result.data.mail;                        
                            gResultID.data.order_serial_id = result.data.order_serial_id;                        
                            this.$emit('changeStage', 4);
                        }

                    })
                    .catch(()=>{
                        console.log('order update error');
                    });   
                }
                else{
                    axios.post('/v1/client/order_create',{ 'order_info': this.order_info, 'user_info': gUserInfo.data.userinfo})
                    .then((result)=>{
                        if(result.data == 0){
                            toast.fire({
                                icon: "error",
                                title: "予約IDが存在しません."
                            });
                        }else{
                            toast.fire({
                                icon: "success",
                                title: "データ追加しました"
                            });
                            console.log(result.data);
                            gResultID.data.mail = result.data.mail;                        
                            gResultID.data.order_serial_id = result.data.order_serial_id;                        
                            this.$emit('changeStage', 4);
                        }

                    })
                    .catch(()=>{
                        console.log('order create error');
                    });   
                }
             
            },
            onClickPrevBtn:function(){
                gUserInfo.data.array = [];
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
