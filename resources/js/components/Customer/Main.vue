<template>
  <div class="container d-flex flex-column justify-content-between">
    <div class="cancelorder-page" v-show="cancelmode === 1">
      <div class="row justify-content-center">
        <cancel-order @toOrderMode="changeMode"></cancel-order>
      </div>
    </div>
    <div class="reservation-page" v-show="cancelmode === 0">
      <div class="row justify-content-center">
        <b-button-group style=" width:100%; padding-right: 20px;">
          <b-button
            variant="secondary"
            class="arrow0 btn btn-lg btn-primary btn-arrow-right"
            style="z-index:5;"
            @click="onClickArrowBtn($event, 0)"
            :class="{setbtn:stage == 0}"
          >
            <p style="letter-spacing: -2.5px;  margin-left: 0px;">区分・方法・決定</p>
          </b-button>

          <b-button
            variant="secondary"
            class="arrow1 btn btn-lg btn-primary btn-arrow-right"
            style="z-index:4"
            @click="onClickArrowBtn($event, 1)"
            :class="{setbtn:stage == 1}"
          >
            <p style="letter-spacing: -2.5px;!important">メニュー・枠決定</p>
          </b-button>

          <b-button
            variant="secondary"
            class="arrow2 btn btn-lg btn-arrow-right"
            style="z-index:3"
            @click="onClickArrowBtn($event, 2)"
            :class="{setbtn:stage == 2}"
          >
            <p style>情報入力</p>
          </b-button>

          <b-button
            variant="secondary"
            class="arrow3 btn btn-lg btn-primary btn-arrow-right"
            style="z-index:2"
            @click="onClickArrowBtn($event, 3)"
            :class="{setbtn:stage == 3}"
          >
            <p style>情報碓認</p>
          </b-button>

          <b-button
            variant="secondary"
            class="arrow4 btn btn-lg btn-primary btn-arrow-right"
            style="z-index:1"
            @click="onClickArrowBtn($event, 4)"
            :class="{setbtn:stage == 4}"
          >
            <p style>同意確認</p>
          </b-button>

          <b-button
            variant="secondary"
            class="arrow5 btn btn-lg btn-primary btn-arrow-right"
            style="z-index:0"
            @click="onClickArrowBtn($event, 5)"
            :class="{setbtn:stage == 5}"
          >
            <p style>予約完了</p>
          </b-button>
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
        </b-button-group>-->
      </div>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div v-show="stage === 0 && !existId" class="tab-content-div">
            <select-order-type
              v-bind:ranks="this.ranks"
              v-bind:clinics="this.clinics"
              @changeStage="changeStageTab"
              @toExistIdPage="changeExistIdPage"
              @toCancelPage="openCancelPage"
            ></select-order-type>
          </div>
          <div v-show="stage === 0 && existId" class="tab-content-div">
            <exist-reservation-id @changeStage="changeStageTab" @toExistIdPage="changeExistIdPage"></exist-reservation-id>
          </div>
          <div v-show="stage === 1" class="tab-content-div">
            <select-menu @changeStage="changeStageTab"></select-menu>
          </div>
          <div v-show="stage === 2" class="tab-content-div">
            <input-user-info v-bind:existId="this.existId" @changeStage="changeStageTab"></input-user-info>
          </div>
          <div v-show="stage === 3" class="tab-content-div">
            <confirm-order-info v-bind:existId="this.existId" @changeStage="changeStageTab"></confirm-order-info>
          </div>
          <div v-show="stage === 4" class="tab-content-div">
            <output-pdf @changeStage="changeStageTab"></output-pdf>
          </div>
          <div v-show="stage === 5" class="tab-content-div">
            <complete-order @changeStage="changeFirstStageTab"></complete-order>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SelectOrderType from "./SelectOrderType.vue";
import SelectMenu from "./SelectMenu.vue";
import InputUserInfo from "./InputUserInfo.vue";
import ConfirmOrderInfo from "./ConfirmOrderInfo.vue";
import OutputPdf from "./OutputPdf.vue";
import CompleteOrder from "./CompleteOrder.vue";
import ExistReservationId from "./ExistReservationId.vue";
import CancelOrder from "./CancelOrder.vue";

export default {
  components: {
    SelectOrderType,
    SelectMenu,
    InputUserInfo,
    ConfirmOrderInfo,
    OutputPdf,
    CompleteOrder,
    ExistReservationId,
    CancelOrder
  },
  created() {
    this.rankList();
    this.clinicList();
  },
  data() {
    return {
      componentname: "select-order-type",
      arrowbtns: [
        "区分・方法・決定",
        "メニュー・枠決定",
        "情報入力",
        "情報碓認",
        "PDF出力",
        "予約完了"
      ],
      stage: 0,
      existId: false,
      cancelmode: 0,
      ranks: [],
      clinics: []
    };
  },
  methods: {
    onClickArrowBtn(event, index) {
      // $(".btn-arrow-right").removeClass("setbtn");
      // $(event.currentTarget).addClass("setbtn");
      // this.stage = index;
    },
    openCancelPage: function(cmode) {
      this.cancelmode = cmode;
    },
    changeStageTab: function(index) {
      this.stage = index;
    },
    changeFirstStageTab: function(index) {
      this.stage = 0;
      this.cancelmode = 0;
      this.existId = false;
    },
    changeExistIdPage: function(index) {
      this.existId = index;
    },
    changeMode() {
      this.cancelmode = 0;
    },
    rankList: function() {
      axios.post("/v1/client/rank_list").then(({ data }) => {
        this.ranks = data;
      });
    },
    clinicList: function() {
      axios.post("/v1/client/clinic_list").then(({ data }) => {
        this.clinics = data;
      });
    }
  }
};
</script>

<style lang="scss">
// * {
//     border-radius: 0 !important;
// }
.tab-content-div {
  margin-top: 25px;
}
.arrow0,
.arrow1,
.arrow2,
.arrow3,
.arrow4,
.arrow5 {
  /* just for this demo. */
  pointer-events: none;
  margin-top: 5px;
  height: 45px;
  padding: 0px;
  background: rgb(237, 237, 237);
  color: black;
  p {
    margin: 0px;
    margin-left: 13px;
    font-size: 12px;
    //letter-spacing: -1.5px;
  }
  &.setbtn {
    background: rgb(227, 223, 211);
    color: black;
  }
}

.btn-arrow-right {
  padding-left: 0px;
  padding-right: 0px;
}

.btn-arrow-right:after {
  /* make two squares (before and after), looking similar to the button */
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
.arrow0 {
  z-index: 5;
  &.btn-arrow-right:after {
    /* bring arrow pointers to front */
    z-index: -1;
  }
}
.arrow1 {
  z-index: 4;
  &.btn-arrow-right:after {
    /* bring arrow pointers to front */
    z-index: -1;
  }
}
.arrow2 {
  z-index: 3;
  &.btn-arrow-right:after {
    /* bring arrow pointers to front */
    z-index: -1;
  }
}
.arrow3 {
  z-index: 2;
  &.btn-arrow-right:after {
    /* bring arrow pointers to front */
    z-index: -1;
  }
}
.arrow4 {
  z-index: 1;
  &.btn-arrow-right:after {
    /* bring arrow pointers to front */
    z-index: -1;
  }
}
.arrow5 {
  z-index: 0;
  &.btn-arrow-right:after {
    /* bring arrow pointers to front */
    z-index: -1;
  }
}
</style>
