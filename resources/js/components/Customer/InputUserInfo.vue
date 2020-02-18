<template>
    <div class="">
        <label class="col-8">＜予約者情報＞</label>
        <form class="user-info">
            <div class="form-group row">
                <label class="col-4 col-form-label">氏名：</label>
                <div class="col">
                    <input type="text" class="form-control" placeholder="麻布　花子">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">フリガナ：</label>
                <div class="col">
                    <input type="text" class="form-control" placeholder="アザブ ハナコ">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">性別：</label>
                <div class="custom-control custom-switch" style="padding-top: 5px;">
                    <span class="switch switch-sm">
                        <label for="switch-sm">女性</label>
                        <input type="checkbox" class="switch" id="switch-sm" >
                        <label for="switch-sm">男性</label>
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">生年月日：</label>
                <div class="col">
                    <input type="date" class="form-control" placeholder="1989/12/01">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">電話番号：</label>
                <div class="col">
                    <input type="text" class="form-control" placeholder="080-XXXX-XXXX">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">email：</label>
                <div class="col">
                    <input type="email" class="form-control" placeholder="hanako@aa.com">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">郵便番号：</label>
                <div class="col">
                    <input type="text" class="form-control" placeholder="106-0031">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">都道府県：</label>
                <div class="col">
                    <select class="custom-select" required>
                        <option value="">東京都</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    <div class="invalid-feedback">Example invalid custom select feedback</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">住所1：</label>
                <div class="col">
                    <input type="text" class="form-control" placeholder="港区西麻布">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">住所2：</label>
                <div class="col">
                    <input type="text" class="form-control" placeholder="1-14-17">
                </div>
            </div>
        </form>
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
        mounted() {
            console.log('Component mounted.')
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
