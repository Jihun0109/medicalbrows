<template>
    <div class="">
        <label class="col-8">＜予約者情報＞</label>
        <form id="form" class="user-info">
            <div class="form-group row">
                <label class="col-4 col-form-label">氏名：</label>
                <div class="col">
                    <input v-model="form.first_name" type="text" class="form-control" placeholder="麻布　花子" 
                    :class="{'is-invalid': form.errors.has('first_name')}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">フリガナ：</label>
                <div class="col">
                    <input v-model="form.last_name" type="text" class="form-control" placeholder="アザブ ハナコ">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">性別：</label>
                <div class="custom-control custom-switch" style="padding-top: 5px;">
                    <span class="switch switch-sm">
                        <label for="switch-sm">女性</label>
                        <input v-model="form.sex" type="checkbox" class="switch" id="switch-sm" >
                        <label for="switch-sm">男性</label>
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">生年月日：</label>
                <div class="col">
                    <input v-model="form.birthday" type="date" class="form-control" placeholder="1989/12/01">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">電話番号：</label>
                <div class="col">
                    <input v-model="form.phonenumber" type="text" class="form-control" placeholder="080-XXXX-XXXX">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">email：</label>
                <div class="col">
                    <input v-model="form.email" type="email" class="form-control" placeholder="hanako@aa.com" name="email" :class="{'is-invalid':form.errors.has('email')}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">郵便番号：</label>
                <div class="col">
                    <input v-model="form.zip_code" type="text" class="form-control" placeholder="106-0031">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">都道府県：</label>
                <div class="col">
                    <select v-model="form.city_name"  class="custom-select" required>
                        <option v-for="(city, index) in cities" :key="index" :value="city" name="selcet-city">{{city}}</option>
                    </select>
                    <div class="invalid-feedback">Example invalid custom select feedback</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">住所1：</label>
                <div class="col">
                    <input v-model="form.address1" type="text" name="address" class="form-control" placeholder="港区西麻布">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">住所2：</label>
                <div class="col">
                    <input v-model="form.address2" type="text" name="address" class="form-control" placeholder="1-14-17">
                </div>
            </div>
        </form>

        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    window.toConfirm_UserInfo = {
            array:[],
            calendar_info:null,
            menu_info:null,
            clinic_info:null,
            staff_info:null,
            user_info:null,
        };
    
    export default {
        data () {
            return {                
                form: new Form({
                    first_name:'',
                    last_name:'',
                    sex: 1,
                    birthday:'',
                    phonenumber:'',
                    email:'',
                    zip_code:'',
                    city_name:'東京都',
                    address1:'',
                    address2:'',
                }),
                cities:['東京都','名古屋市','岡崎市'],
            }

        },
        methods:{
            onClickNextBtn:function(){
                console.log(this.form,'userinfo from inputuserinfo.vue');  
                console.log(toConfirmOrderInfo.data,'menuinfo from inputuserinfo.vue');                
                console.log(toChooseMenu.data,'orderinfo from inputuserinfo.vue');  
                this.arrayUserInfo();               
                this.$emit('changeStage', 3);
                
            },
            arrayUserInfo:function(){
                toConfirm_UserInfo.array.push(this.form.first_name);
                toConfirm_UserInfo.array.push(this.form.last_name);
                if(this.form.sex){
                    toConfirm_UserInfo.array.push('男性');
                }
                else{
                    toConfirm_UserInfo.array.push('女性');
                }
                toConfirm_UserInfo.array.push(this.form.birthday);
                toConfirm_UserInfo.array.push(this.form.phonenumber);
                toConfirm_UserInfo.array.push(this.form.email);
                toConfirm_UserInfo.array.push(this.form.zip_code);
                toConfirm_UserInfo.array.push(this.form.city_name);
                toConfirm_UserInfo.array.push(this.form.address1);
                toConfirm_UserInfo.array.push(this.form.address2);
            },
        },
        mounted() {
            console.log('Component mounted.');
        }
    }
</script>

<style lang="scss">
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
