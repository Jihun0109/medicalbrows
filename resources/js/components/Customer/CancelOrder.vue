<template>
  <div class="col-md-6">
    <div v-show="complete === 1">
      <div class="complete-cancel">
        予約ID: {{form.order_serial_id}}
        <br />
        <br />予約キャンセルを致しました
        <br />
        <br />
        {{email}}宛に
        <br />予約キャンセルご案内メールも送信しております
      </div>
      <div class="row justify-content-around">
        <div class="col-auto mr-auto">
          <button
            v-show="false"
            @click="onClickPrevBtn"
            type="button"
            class="btn btn-secondary"
            style="background:#9F9F9F;"
          >戻る</button>
        </div>
        <div class="col-auto">
          <button
            @click="onClickCompleteBtn"
            type="button"
            class="btn btn-primary"
            style="backgroud:#307DB9;"
          >完了</button>
        </div>
      </div>
    </div>
    <div style="margin-top: 20px; margin-bottom: 10px; text-align: center;" v-show="complete === 0">
      <label style="letter-spacing: -1.2px; margin-bottom: 35px;">予約キャンセル手続き</label>
      <div class="form-card" v-show="changepage === 0">
        <form id="form-cancel" class="cancel-card">
          <div class="form-group row">
            <label class="col-4 col-form-label">予約ID :</label>
            <div class="col">
              <input
                v-model="form.order_serial_id"
                type="text"
                class="form-control"
                placeholder="予約ID"
              />
            </div>
          </div>
          <div class="form-group row">
            <label class="col-4 col-form-label">電話番号 :</label>
            <div class="col">
              <!-- <input v-model="form.phonenumber" type="text" class="form-control" placeholder="電話番号"> -->
              <input
                :value="form.phonenumber"
                type="text"
                onkeypress='return event.charCode >= 48 && event.charCode <= 57' 
                class="form-control"
                placeholder="xxxxxxxxxxx"
                @input="updatePhoneValue"
              />
            </div>
          </div>
        </form>
      </div>
      <div class="orderinfo-card" v-show="changepage === 1 " v-if="order_info != null">
        <label class="col-8">＜予約情報＞</label>
        <div class="info">
          <div class="row">
            <label class="col-4 col-form-label">予約ID：</label>
            <div class="col">
              <p>{{form.order_serial_id}}</p>
            </div>
          </div>
          <div class="row">
            <label class="col-4 col-form-label">指名スタッフ：</label>
            <div class="col">
              <p>{{order_info.staff_info.alias}}【{{order_info.rank_info.name}}】</p>
            </div>
          </div>
          <div class="row">
            <label class="col-4 col-form-label">日付：</label>
            <div class="col">
              <p>{{formatDate(order_info.order_date)}}({{order_info.week}})</p>
            </div>
          </div>
          <div class="row">
            <label class="col-4 col-form-label">区分：</label>
            <div class="col">
              <p>{{order_info.order_type}}</p>
            </div>
          </div>
          <div class="row">
            <label class="col-4 col-form-label">時間：</label>
            <div class="col">
              <p>{{order_info.time_schedule}}</p>
            </div>
          </div>
          <div class="row">
            <label class="col-4 col-form-label">施術部位：</label>
            <div class="col">
              <p>{{order_info.part_info.name}}</p>
            </div>
          </div>
        </div>
        <label class="col-8">＜お客様情報＞</label>
        <div class="info">
          <div class="row">
            <label class="col-4 col-form-label">電話番号：</label>
            <div class="col">
              <p>{{form.phonenumber}}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="confirm-btn">
        <div class="row justify-content-around">
          <div class="col-4">
            <button
              @click="onClickPrevBtn"
              type="button"
              class="btn btn-secondary"
              style="background:#9F9F9F;"
            >戻る</button>
          </div>
          <div class="col-auto" style="margin-left: 40px;">
            <button
              @click="onClickNextBtn"
              type="button"
              class="btn btn-primary"
              style="backgroud:#307DB9; "
            >次へ</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
<script>
export default {
  data() {
    return {
      form: new Form({
        order_serial_id: null,
        phonenumber: ""
      }),
      staff_rank_name: "",
      date: "",
      order_type: "",
      time: "",
      part: "",
      changepage: 0,
      complete: 0,

      email: "",
      order_info: null,
      customer_info: null,
      content:
        "メディカルブローです。予約キャンセルを承りました。\n予約ID: \n予約日時：\nキャンセル料発生： 5, 000 円(税別)\n\n本件についてのお問い合わせは【 0570 - 078 - 889】 までご連絡をお願い致します。 * \nこのSMSは送信専用です。 "
    };
  },
  methods: {
    updatePhoneValue(event) {
      const value = event.target.value;
      if (String(value).length <= 11) {
        this.form.phonenumber = value;
      }
      this.$forceUpdate();
    },
    onClickNextBtn: function() {
      if (!this.changepage) {
        this.form
          .post("/v1/client/get_orderinfo")
          .then(result => {
            console.log(result.data);
            if (result.data === "wrongID") {
              toast.fire({
                icon: "error",
                title: "予約IDが存在しません。"
              });
            } else if (result.data === "wrongPhone") {
              toast.fire({
                icon: "error",
                title: "電話番号が存在しません。"
              });
            } else {
              if (result.data.order_info.order_status == 4) {
                toast.fire({
                  icon: "error",
                  title: "該当の予約IDは既にキャンセルされています。"
                });
                return;
              }
              this.order_info = result.data.order_info;
              this.customer_info = result.data.customer_info;
              this.changepage = 1;
            }
          })
          .catch(() => {
            console.log("get_orderinfo error");
          });
      } else {
        axios
          .post("/v1/client/order_cancel", {
            order_serial_id: this.form.order_serial_id
          })
          .then(result => {
            console.log(result.data);
            this.email = result.data.email;
            this.complete = 1;
            this.sendmail();
          })
          .catch(() => {
            console.log("order_cancel error");
          });
      }
    },
    onClickPrevBtn: function() {
      if (this.changepage) this.changepage = 0;
      else {
        this.$emit("toOrderMode");
      }
    },
    onClickCompleteBtn() {
      this.changepage = 0;
      this.complete = 0;
      this.$emit("toOrderMode");
    },
    formatDate(dt) {
      dt = new Date(dt);
      var month = ("0" + (dt.getMonth() + 1)).slice(-2);
      var date = ("0" + dt.getDate()).slice(-2);
      var year = dt.getFullYear();
      var formattedDate = year + "年" + month + "月" + date + "日";
      //var formattedDate = year + '-' + month + '-' + date;
      return formattedDate;
    },
    sendmail() {
      var mail_info = {};
      mail_info["customer_email"] = this.customer_info.email;
      mail_info["customer_first_name"] = this.customer_info.first_name;
      mail_info["customer_last_name"] = this.customer_info.last_name;
      mail_info["content"] =
        "メディカルブローです。予約キャンセルを承りました。\n予約ID:" +
        this.form.order_serial_id +
        "\n予約日時：" +
        this.formatDate(this.order_info.order_date) +
        this.order_info.week +
        "\nキャンセル料発生： 5, 000 円(税別)\n\n本件についてのお問い合わせは【 0570 - 078 - 889】 までご連絡をお願い致します。 * \nこのSMSは送信専用です。 "; //this.content;
      mail_info["clinic_info"] = this.order_info.clinic_info;
      //console.log(mail_info);
      axios
        .post("/v1/client/send_mail", { mail_info: mail_info })
        .then(result => {
          toast.fire({
            icon: "success",
            title: "メール送信成功"
          });
          console.log(result.data);
        })
        .catch(() => {
          console.log("send mail error");
          // toast.fire({
          //     icon: "error",
          //     title: "メールの送信に失敗しました"
          // });
        });
    }
  },
  mounted() {}
};
</script>

<style lang="scss">
.cancel-card {
  margin-bottom: 55px;
  .form-group {
    margin-bottom: 25px;
    input {
      border: none;
      border-bottom: 2px solid #3e3e3e;
      padding: 5px 10px;
      outline: none;
    }
    label {
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
.orderinfo-card .info {
  div {
    margin-bottom: 10px;
  }
}
</style>
