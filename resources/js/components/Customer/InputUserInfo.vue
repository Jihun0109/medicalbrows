<template>
    <div class="">
        <div class="exist-order-info" v-show="existId" v-if="order_id_info.old_order_info != null">
            <label class="col-8">＜前回予約情報＞</label>
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
                        <p v-if="order_id_info.old_order_info.clinic_info != null">{{order_id_info.old_order_info.clinic_info.name}}</p>
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
            <p style="font-weight: bold; margin-top:15px;">個人情報保護の観点から、過去入力したお客様情報の内容は表示しません。<br><br>お手数ですが、再度入力をお願いします。</p>          
        </div>
        <div class="customer-infor">
            <label class="col-8" v-if="existId">＜お客様情報＞   </label>
            <label class="col-8" v-else>＜予約者情報＞</label>
            <form id="form" class="user-info">
                <div class="form-group row">
                    <label class="col-4 col-form-label">氏名：</label>
                    <div class="col">
                        <input v-model="formdata.first_name" id="customerName" v-on:input="update_furigana" type="text" class="form-control" placeholder="氏名を入力して下さい" :class="{ 'is-invalid': submitted && $v.formdata.first_name.$error }" v-on:blur="handleBlur" />
                        <!-- <input v-model="formdata.first_name" type="text" class="form-control" placeholder="氏名を入力して下さい" :class="{ 'is-invalid': submitted && $v.formdata.first_name.$error }" /> -->
                        <div v-if="submitted && !$v.formdata.first_name.required" class="invalid-feedback">氏名を入力して下さい。</div>
                    </div>                    
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">フリガナ：</label>
                    <div class="col">
                        <input v-model="formdata.last_name" id="customerFurigana" type="text" class="form-control" :class="{ 'is-invalid': submitted && $v.formdata.last_name.$error }" v-on:input="handleFurigana"/>
                        <!-- <input v-model="formdata.last_name" type="text" class="form-control" placeholder="フリガナを入力して下さい" :class="{ 'is-invalid': submitted && $v.formdata.last_name.$error }" /> -->
                        <div v-if="submitted && $v.formdata.last_name.$error" class="invalid-feedback">
                            <span v-if="!$v.formdata.last_name.required">フリガナを入力して下さい。</span>
                            <!-- <span v-if="!$v.formdata.last_name.max">フリガナは最大6文字でなければします。</span> -->
                        </div>                        
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
                        <input v-model="formdata.birthday" type="date" class="form-control" placeholder="生年月日を入力して下さい" :class="{ 'is-invalid': submitted && $v.formdata.birthday.$error }" />
                        <div v-if="submitted && $v.formdata.birthday.$error" class="invalid-feedback">
                            <span v-if="!$v.formdata.birthday.required">生年月日を入力して下さい。</span>
                            <!-- <span v-else-if="!$v.formdata.birthday.isDate">Enter a valid birthdate</span>
                            <span v-else-if="!$v.formdata.birthday.isLegalAge">Must be 18 Years old</span> -->
                        </div>                         
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">電話番号：</label>
                    <div class="col">
                        <!-- <input v-model="form.phonenumber" type="text" class="form-control" placeholder="電話番号を入力して下さい"> -->
                        <!-- <input v-model="formdata.phonenumber" type="tel" class="form-control" placeholder="電話番号を入力して下さい" v-mask="{mask:'999-9999-9999', placeholder:'#'}" :class="{ 'is-invalid': submitted && $v.formdata.phonenumber.$error }" /> -->
                        <input  :value="formdata.phonenumber" type="number" class="form-control" placeholder="xxxxxxxxxxx" @input="updatePhoneValue" :class="{ 'is-invalid': submitted && $v.formdata.phonenumber.$error }" />
                        <div v-if="submitted && !$v.formdata.phonenumber.required" class="invalid-feedback">電話番号を入力して下さい。</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">email：</label>
                    <div class="col">
                        <input v-model="formdata.email" type="email" class="form-control" placeholder="メールを入力して下さい" name="email" :class="{ 'is-invalid': submitted && $v.formdata.email.$error }" />
                        <div v-if="submitted && $v.formdata.email.$error" class="invalid-feedback">
                            <span v-if="!$v.formdata.email.required">メールを入力して下さい。</span>
                            <span v-if="!$v.formdata.email.email">メールの形式が無効です。</span>
                        </div> 
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">郵便番号：</label>
                    <div class="col">
                        <input v-model="formdata.zip_code" type="text" class="form-control" placeholder="郵便番号を入力して下さい" :class="{ 'is-invalid': submitted && $v.formdata.zip_code.$error }"/>
                        <!-- <input v-model="formdata.zip_code" type="text" class="form-control" v-mask="{ mask: ['999-9999','9A9 A9A'], placeholder: '#' }" :class="{ 'is-invalid': submitted && $v.formdata.zip_code.$error }" /> -->
                        <div v-if="submitted && !$v.formdata.zip_code.required" class="invalid-feedback">郵便番号を入力して下さい。</div>
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
                        <input v-model="formdata.address1" type="text" name="address" class="form-control" placeholder="住所1を入力して下さい" :class="{ 'is-invalid': submitted && $v.formdata.address1.$error }" />
                        <div v-if="submitted && !$v.formdata.address1.required" class="invalid-feedback">住所1を入力して下さい。</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 col-form-label">住所2：</label>
                    <div class="col">
                        <input v-model="formdata.address2" type="text" name="address" class="form-control" placeholder="住所2を入力して下さい"/>
                        <!-- <input v-model="formdata.address2" type="text" name="address" class="form-control" placeholder="住所2を入力して下さい" :class="{ 'is-invalid': submitted && $v.formdata.address2.$error }" />
                        <div v-if="submitted && !$v.formdata.address2.required" class="invalid-feedback">住所2を入力して下さい。</div> -->
                    </div>
                </div>
            </form>
        </div>
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button  v-show="true" @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
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
    import { required, email, maxLength, and } from "vuelidate/lib/validators";

    const isDate = (value) => moment(value, 'YYYY-MM-DD', true).isValid()

    const calcAge = (date) => {
        const today = moment()
        return today.diff(moment(date), 'years')
    }

    const isLegalAge = (date, minAge) => {
        if (calcAge(date) >= minAge) {
            return true;
        } else {
            return false;
        }
    }

    export default {
        props:['existId'],
        validations: {
            formdata: {
                first_name: { required },
                last_name: { required},//, max: maxLength(6) },
                birthday: { required, 
                            isDate(value){
                                return isDate(value);
                            },
                            isLegalAge(value){
                                // use 'and' to verify the date is valid before checking age
                                return and('isDate', value => isLegalAge(value, 18));                                
                            }
                         },
                phonenumber: { required },
                email: { required, email },
                zip_code: { required },
                address1: { required },
                //address2: { required },
            }
        },
        data () {
            return {   
                order_id_info:gIDInfo.data,//defined ExistReservation.vue
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
                    city_name:'北海道',
                    address1:'',
                    address2:'',
                    note:'経験 : \n妊娠・授乳・不妊治療 : \n通院歴・薬 : \n金アレ・アトピー・ケロイド確認 : \n眉ブリーチ・炎症・傷跡確認 : \n美容サービス・美容整形確認 : \n料金・所要時間 : \nHP : \nキャンセル規約 : ',
                },
                submitted:false,
                cities:['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'],
            }

        },
        mounted() {
            $.fn.autoKana("#customerName", "#customerFurigana", { katakana: true });
        },
        methods:{
            update_furigana(input) {
                this.formdata.last_name = $("#customerFurigana").val();
                // this.history.push(input.target.value);
                // this.form.last_name = historykana(this.history);
            },
            handleBlur() {
                this.formdata.last_name = $("#customerFurigana").val();
            },
            handleFurigana() {
                this.formdata.last_name = $("#customerFurigana")
                    .val()
                    .replace(/[^ア-ン]+/i, "");
            },
            updatePhoneValue(event) {
                const value = event.target.value;
                if (String(value).length <= 11) {
                    this.formdata.phonenumber = value;
                }
                this.$forceUpdate();
            },
            onClickNextBtn:function(){
                this.submitted = true;
                // stop here if form is invalid
                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

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
                this.submitted = false;
                if(this.existId)
                    this.$emit('changeStage', 0);
                else{
                    this.$emit('changeStage', 1);
                }
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
        },

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
                &::placeholder{
                    font-size: 13px;
                    letter-spacing: -0.21px;
                }
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

        //&:checked + label::before {
            //background-color: $switch-checked-bg;
        //}

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
