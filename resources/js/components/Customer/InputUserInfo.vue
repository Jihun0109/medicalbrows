<template>
    <div class="">
        <div class="exist-order-info" v-show="order_id_data.order_serial_id !== null">
            <label class="col-8">＜前回予約情報＞</label>
            <div class="info">
                <div class="row">
                    <label class="col-4 col-form-label">日付：</label>
                    <div class="col" >
                        <p>{{menu_info.calendar_info.date}}({{menu_info.calendar_info.week}})</p>
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
                        <p>{{menu_info.calendar_info.time}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">場所：</label>
                    <div class="col" >
                        <p>{{menu_info.calendar_info.clinic}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術者：</label>
                    <div class="col" >
                        <p>{{order_info.staff_info.name}}</p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-4 col-form-label">施術メニュー：</label>
                    <div class="col" >
                        <p>{{menu_info.menu_info.name}}</p>
                    </div>
                </div>
            </div>  
            <p style="font-weight: bold; margin-top:15px;">個人情報保護の観点から、過去入力したお客様情報の内容は表示しません。<br><br>お手数ですが、再度入力をお願いします。</p>          
        </div>
        <div class="customer-infor">
            <label class="col-8" v-if="order_id_data.order_serial_id !== null">＜お客様情報＞   </label>
            <label class="col-8" v-else>＜予約者情報＞</label>
            <form id="form" class="user-info">
                <div class="form-group row">
                    <label class="col-4 col-form-label">氏名：</label>
                    <div class="col">
                        <input v-model="formdata.first_name" type="text" class="form-control" placeholder="麻布　花子" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">フリガナ：</label>
                    <div class="col">
                        <input v-model="formdata.last_name" type="text" class="form-control" placeholder="アザブ ハナコ">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">性別：</label>
                    <div class="custom-control custom-switch" style="padding-top: 5px;">
                        <span class="switch switch-sm">
                            <label for="switch-sm">女性</label>
                            <input v-model="formdata.sex" type="checkbox" class="switch" id="switch-sm" >
                            <label for="switch-sm">男性</label>
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">生年月日：</label>
                    <div class="col">
                        <input v-model="formdata.birthday" type="date" class="form-control" placeholder="1989/12/01">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">電話番号：</label>
                    <div class="col">
                        <!-- <input v-model="form.phonenumber" type="text" class="form-control" placeholder="080-XXXX-XXXX"> -->
                        <input v-model="formdata.phonenumber" type="tel" class="form-control" v-mask="{mask:'9999-999-9999', placeholder:'#'}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">email：</label>
                    <div class="col">
                        <input v-model="formdata.email" type="email" class="form-control" placeholder="hanako@aa.com" name="email">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">郵便番号：</label>
                    <div class="col">
                        <!-- <input v-model="form.zip_code" type="text" class="form-control" placeholder="106-0031"> -->
                        <input v-model="formdata.zip_code" type="text" class="form-control" v-mask="{ mask: ['999-9999','9A9 A9A'], placeholder: '#' }">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">都道府県：</label>
                    <div class="col">
                        <select v-model="formdata.city_name"  class="custom-select" required>
                            <option v-for="(city, index) in cities" :key="index" :value="city" name="selcet-city">{{city}}</option>
                        </select>                    
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">住所1：</label>
                    <div class="col">
                        <input v-model="formdata.address1" type="text" name="address" class="form-control" placeholder="港区西麻布">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">住所2：</label>
                    <div class="col">
                        <input v-model="formdata.address2" type="text" name="address" class="form-control" placeholder="1-14-17">
                    </div>
                </div>
            </form>
        </div>
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button  v-show="order_id_data.order_serial_id === null" @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次dへ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    window.gUserInfo = {
            data:{
                userinfo:null,
                array:[],  
            }          
        };
    
    export default {
        data () {
            return {   
                order_id_data:gIDInfo.data,//defined cancel.vue
                order_info:gOrderTypeInfo.data, //defined selectordertype.vue
                menu_info:gOrderInfo.data,  //defined selectmenu.vue            
                //form: new Form({
                formdata: {
                    first_name:'',
                    last_name:'',
                    sex: 0,
                    birthday:'',
                    phonenumber:'',
                    email:'',
                    zip_code:'',
                    city_name:'東京都',
                    address1:'',
                    address2:'',
                },
                cities:['東京都','名古屋市','岡崎市'],
            }

        },
        methods:{
            onClickNextBtn:function(){
                console.log( this.formdata);
                this.arrayUserInfo();
                gUserInfo.data.userinfo = this.formdata;
                console.log( gUserInfo.data);
                this.$emit('changeStage', 3);
                
            },
            arrayUserInfo:function(){
                gUserInfo.data.array.push(this.formdata.first_name);
                gUserInfo.data.array.push(this.formdata.last_name);
                if(this.formdata.sex){
                    gUserInfo.data.array.push('男性');
                }
                else{
                    gUserInfo.data.array.push('女性');
                }
                gUserInfo.data.array.push(this.formdata.birthday);
                gUserInfo.data.array.push(this.formdata.phonenumber);
                gUserInfo.data.array.push(this.formdata.email);
                gUserInfo.data.array.push(this.formdata.zip_code);
                gUserInfo.data.array.push(this.formdata.city_name);
                gUserInfo.data.array.push(this.formdata.address1);
                gUserInfo.data.array.push(this.formdata.address2);
                
            },
            onClickPrevBtn:function(){
                this.$emit('changeStage', 1);
            },
        },
        mounted() {

        }
    }
</script>

<style lang="scss">
    .exist-order-info{
        .info{
            div{
                margin-bottom: 5px;
            }
        }
    }
    .user-info{
        margin-bottom: 55px;
        .form-group{
            margin-bottom: 5px;
            input {
                border:none;
                border-bottom: 2px solid #3E3E3E;
                padding: 5px 10px;
                outline: none;
            }
            label{
                letter-spacing: -1.5px;
                margin-top: 2px;
            }
            [placeholder]:focus::-webkit-input-placeholder {
                transition: text-indent 0.4s 0.4s ease; 
                text-indent: -100%;
                opacity: 1;
            }
        }

    }

    $font-size-base: 1rem;
    $font-size-lg: ($font-size-base * 1.25);
    $font-size-sm: ($font-size-base * .875);
    $input-height: 2.375rem;
    $input-height-sm: 1.9375rem;
    $input-height-lg: 3rem;
    $input-btn-focus-width: .2rem;
    $custom-control-indicator-bg: #dee2e6;
    $custom-control-indicator-disabled-bg: #e9ecef;
    $custom-control-description-disabled-color: #868e96;
    $white: white;
    $selet: #D7D8C4;
    $theme-colors: (
    'primary': #08d
    );

    //
    // These variables can be used to customize the switch component.
    //
    $switch-height: calc(#{$input-height} * .8) !default;
    $switch-height-sm: calc(#{$input-height-sm} * .8) !default;
    $switch-height-lg: calc(#{$input-height-lg} * .8) !default;
    $switch-border-radius: $switch-height !default;
    $switch-bg: $custom-control-indicator-bg !default;
    $switch-checked-bg: map-get($theme-colors, 'primary') !default;
    $switch-disabled-bg: $custom-control-indicator-disabled-bg !default;
    $switch-disabled-color: $custom-control-description-disabled-color !default;
    $switch-thumb-bg: $selet !default;
    $switch-thumb-border-radius: 50% !default;
    $switch-thumb-padding: 2px !default;
    $switch-focus-box-shadow: 0 0 0 $input-btn-focus-width rgba(map-get($theme-colors, 'primary'), .25);
    $switch-transition: .2s all !default;

    .switch {
    font-size: $font-size-base;
    position: relative;

    input {
        position: absolute;
        height: 1px;
        width: 1px;
        background: none;
        border: 0;
        clip: rect(0 0 0 0);
        clip-path: inset(50%);
        overflow: hidden;
        padding: 0;

        + label {
        margin: 9px 5px 4px 7px;
        position: relative;
        min-width: calc(#{$switch-height} * 2);
        border-radius: $switch-border-radius;
        height: $switch-height;
        line-height: $switch-height;
        display: inline-block;
        cursor: pointer;
        outline: none;
        user-select: none;
        vertical-align: middle;
        text-indent: calc(calc(#{$switch-height} * 2) + .5rem);
        }

        + label::before,
        + label::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: calc(#{$switch-height} * 2);
        bottom: 0;
        display: block;
        }

        + label::before {
        right: 0;
        background-color: $switch-bg;
        border-radius: $switch-border-radius;
        transition: $switch-transition;
        }

        + label::after {
        top: $switch-thumb-padding;
        left: $switch-thumb-padding;
        width: calc(#{$switch-height} - calc(#{$switch-thumb-padding} * 2));
        height: calc(#{$switch-height} - calc(#{$switch-thumb-padding} * 2));
        border-radius: $switch-thumb-border-radius;
        background-color: $switch-thumb-bg;
        transition: $switch-transition;
        }

        &:checked + label::before {
        background-color: $switch-checked-bg;
        }

        &:checked + label::after {
        margin-left: $switch-height;
        }

        &:focus + label::before {
        outline: none;
        box-shadow: $switch-focus-box-shadow;
        }

        &:disabled + label {
        color: $switch-disabled-color;
        cursor: not-allowed;
        }

        &:disabled + label::before {
        background-color: $switch-disabled-bg;
        }
    }

    // Small variation
    &.switch-sm {
        font-size: $font-size-sm;

        input {
        + label {
            min-width: calc(#{$switch-height-sm} * 2);
            height: $switch-height-sm;
            line-height: $switch-height-sm;
            text-indent: calc(calc(#{$switch-height-sm} * 2) + .5rem);
        }

        + label::before {
            width: calc(#{$switch-height-sm} * 2);
        }

        + label::after {
            width: calc(#{$switch-height-sm} - calc(#{$switch-thumb-padding} * 2));
            height: calc(#{$switch-height-sm} - calc(#{$switch-thumb-padding} * 2));
            border: 2px solid black;
        }

        &:checked + label::after {
            margin-left: $switch-height-sm;
        }
        }
    }

    // Large variation
    &.switch-lg {
        font-size: $font-size-lg;

        input {
        + label {
            min-width: calc(#{$switch-height-lg} * 2);
            height: $switch-height-lg;
            line-height: $switch-height-lg;
            text-indent: calc(calc(#{$switch-height-lg} * 2) + .5rem);
        }

        + label::before {
            width: calc(#{$switch-height-lg} * 2);
        }

        + label::after {
            width: calc(#{$switch-height-lg} - calc(#{$switch-thumb-padding} * 2));
            height: calc(#{$switch-height-lg} - calc(#{$switch-thumb-padding} * 2));
        }

        &:checked + label::after {
            margin-left: $switch-height-lg;
        }
        }
    }

    + .switch {
        margin-left: 1rem;
    }
    }


    .dropdown-menu {
    margin-top: .75rem;
    }

</style>
