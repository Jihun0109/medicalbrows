<template>
    <div class="col-md-6">
        <div v-show="complete === 1">
            <div class="complete-cancel">
                予約ID: {{form.order_serial_id}}
                <br>
                <br>
                予約キャンセルを致しました
                <br>
                <br>
                hanako.a@gmail.com宛に
                <br>
                予約キャンセルご案内メールも送信しております
            </div>
        </div>
        <div style="margin-top: 20px; margin-bottom: 10px; text-align: center;" v-show="complete === 0">       
            <label style="letter-spacing: -1.2px; margin-bottom: 35px;">予約キャンセル手続き</label>
            <div class="form-card" v-show="changepage === 0">
                <form id="form" class="cancel-card ">
                    <div class="form-group row">
                        <label class="col-4 col-form-label">予約ID :</label>
                        <div class="col">
                            <input v-model="form.order_serial_id" type="text" class="form-control" placeholder="予約ID">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-4 col-form-label">電話番号 :</label>
                        <div class="col">
                            <input v-model="form.phonenumber" type="text" class="form-control" placeholder="電話番号">
                        </div>
                    </div>            
                </form>
            </div>
            <div class="orderinfo-card" v-show="changepage === 1 ">
                <label class="col-8">＜予約情報＞</label>
                <div class="info">
                    <div class="row">
                        <label class="col-4 col-form-label">予約ID：</label>
                        <div class="col" >
                            <p>{{form.order_serial_id}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-4 col-form-label">指名スタッフ：</label>
                        <div class="col" >
                            <p>{{staff_rank_name}}</p>
                        </div>
                    </div>                    
                    <div class="row">
                        <label class="col-4 col-form-label">日付：</label>
                        <div class="col" >
                            <p>{{date}}</p>
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
                            <p>{{time}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-4 col-form-label">施術部位：</label>
                        <div class="col" >
                            <p>{{part}}</p>
                        </div>
                    </div>
                </div>  
                <label class="col-8">＜お客様情報＞</label>
                <div class="info">
                    <div class="row">
                        <label class="col-4 col-form-label">電話番号：</label>
                        <div class="col" >
                            <p>{{form.phonenumber}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="confirm-btn">
                <div class="row justify-content-around">
                    <div class="col-4">
                        <button  @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                    </div>
                    <div class="col-auto" style="margin-left: 40px;">
                        <button  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                    </div>
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
<script>
    window.gIDInfo = {
            data:{
                order_serial_id:null,
                phonenumber:''
            }
        };
    
    export default {
        data () {
            return {                
                form: new Form({
                    order_serial_id: null,
                    phonenumber:'',
                }),
                staff_rank_name:'',
                date:'',
                order_type:'',
                time:'',
                part:'',
                changepage: 0,
                complete: 0,
            }
        },
        methods:{
            onClickNextBtn:function(){
                if(!this.changepage)
                    this.changepage = 1;
                else
                    this.complete = 1;                
            },
            onClickPrevBtn:function(){
                if(this.changepage)
                    this.changepage = 0;   
                else{
                    this.$emit('toOrderMode');
                }
            },
        },
        mounted() {

        }
    }
</script>

<style lang="scss">
    .cancel-card{
        margin-bottom: 55px;
        .form-group{
            margin-bottom: 25px;
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
    .orderinfo-card .info{
        div{
            margin-bottom: 10px;
        }
    }
</style>
