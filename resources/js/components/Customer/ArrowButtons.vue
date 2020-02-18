<template>
    <div class="container">
        <div class="row justify-content-center">
            <b-button-group style=" width:100%; padding-right: 20px;">
                <b-button @click="onClickArrowBtn($event)" variant="secondary" class="arrow0 btn btn-lg btn-primary btn-arrow-right setbtn" style="z-index:5"><p style="letter-spacing: -2.5px;">区分・方法・決定</p></b-button>                
                <b-button @click="onClickArrowBtn($event)" variant="secondary" class="arrow1 btn btn-lg btn-primary btn-arrow-right" style="z-index:4"><p style="letter-spacing: -2.5px;!important">メニュー・枠決定</p></b-button>
                <b-button @click="onClickArrowBtn($event)" variant="secondary" class="arrow2 btn btn-lg btn-arrow-right" style="z-index:3"><p style="">情報入力</p></b-button>
                <b-button @click="onClickArrowBtn($event)" variant="secondary" class="arrow3 btn btn-lg btn-primary btn-arrow-right" style="z-index:2"><p style="">情報碓認</p></b-button>
                <b-button @click="onClickArrowBtn($event)" variant="secondary" class="arrow4 btn btn-lg btn-primary btn-arrow-right" style="z-index:1"><p style="">PDF出力</p></b-button>
                <b-button @click="onClickArrowBtn($event)" variant="secondary" class="arrow5 btn btn-lg btn-primary btn-arrow-right" style="z-index:0"><p style="">予約完了</p></b-button>
            </b-button-group>
            <!-- <b-button-group style=" width:100%; padding-right: 20px;">
                
                <b-button v-for="(abtn, index) in arrowbtns" 
                        :key="index"                     
                        type="button" 
                        @click="onClickArrowBtn(index)"
                        class="btn-lg btn-primary btn-arrow-right" 
                        :class="['arrow' + index, { selbtn: stage === index }]"
                        >
                    <p>{{abtn}}</p>
                </b-button>
            </b-button-group> -->
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                arrowbtns:['区分・方法・決定','メニュー・枠決定','情報入力','情報碓認','PDF出力','予約完了'],
                stage: 0,
            }
        },
        methods: {
            onClickArrowBtn(event){
                $(".btn-arrow-right").removeClass("setbtn");
                $(event.currentTarget).addClass("setbtn"); 
            },
        }
    }
</script>

<style lang="scss">
    * {
        border-radius: 0 !important;
    }
    .arrow0,
    .arrow1,
    .arrow2,
    .arrow3,
    .arrow4,
    .arrow5 {
    /* just for this demo. */    
        margin-top: 5px;
        height: 45px;
        padding: 0px;
        background:rgb(237,237,237);
        color:black;
        p{
            margin: 0px;
            margin-left: 13px;
            font-size: 12px;
            letter-spacing: -1.5px;
        }
        &.setbtn{
            background:rgb(227, 223, 211);
            color: black;
        }        
    }

    .btn-arrow-right {
        padding-left: 0px;
        padding-right: 0px;
    }

    .btn-arrow-right:after
    {  /* make two squares (before and after), looking similar to the button */    
        content: "";
        position: absolute;
        top: 6px;
        width: 32px;
        height: 32px;
        /* button_outer_height / sqrt(2) */        
        background: inherit;
        /* use parent background */        
        border: inherit;
        /* use parent border */        
        border-left-color: transparent;
        /* hide left border */        
        border-bottom-color: transparent;
        border-radius: 0px 4px 0px 0px;
        /* round arrow corner, the shorthand property doesn't accept "inherit" so it is set to 4px */        
    }

    .btn-arrow-right:before,
    .btn-arrow-right:after {
        transform: rotate(45deg);
        /* rotate right arrow squares 45 deg to point right */    
    }
    .btn-arrow-right:before {
    /* align the "before" square to the left */        
        left: -11px;
    }

    .btn-arrow-right:after {
    /* align the "after" square to the right */    
        right: -16px;
    }
    .arrow0{
        z-index: 5;
        &.btn-arrow-right:after {
        /* bring arrow pointers to front */    
            z-index: -1;
        }
    }
    .arrow1{
        z-index: 4;
        &.btn-arrow-right:after {
        /* bring arrow pointers to front */    
            z-index: -1;
        }
    }
    .arrow2{
        z-index: 3;
        &.btn-arrow-right:after {
        /* bring arrow pointers to front */    
            z-index: -1;
        }
    }   
    .arrow3{
        z-index: 2;
        &.btn-arrow-right:after {
        /* bring arrow pointers to front */    
            z-index: -1;
        }
    }
    .arrow4{
        z-index: 1;
        &.btn-arrow-right:after {
        /* bring arrow pointers to front */    
            z-index: -1;
        }
    }
    .arrow5{
        z-index: 0;
        &.btn-arrow-right:after {
        /* bring arrow pointers to front */    
            z-index: -1;
        }
    }
</style>
