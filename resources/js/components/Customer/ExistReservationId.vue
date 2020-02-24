<template>
    <div class="">
        <form id="form" class="id-info">
            <div class="row">
                <label class="col-4 col-form-label">診療区分</label>
                <div class="col my-auto" >
                    <p style="margin-bottom: 0px;">再診</p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">前回予約ID</label>
                <div class="col">
                    <input v-model="form.order_serial_id" type="text" class="form-control" placeholder="前回予約ID">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">電話番号</label>
                <div class="col">
                    <input v-model="form.phonenumber" type="text" class="form-control" placeholder="電話番号">
                </div>
            </div>            
        </form>
        <label style="margin-left: 10px;">※電話番号の場合は、数字のみご記入下さい 例: 0903XXXXXXX</label>
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-4">
                    <button  v-show="false" @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" style="margin-left: 40px;">
                    <button  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9; ">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

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
            }
        },
        methods:{
            onClickNextBtn:function(){
                this.form.post('/v1/client/get_orderinfo')
                    .then((result)=>{
                        console.log(result.data);
                        //gIDInfo.data.order_serial_id = this.form.order_serial_id;
                        //this.$emit('changeStage', 2);                        
                    })
                    .catch(()=>{
                        console.log('update error');
                    });                  
            },
            onClickPrevBtn:function(){
                this.form.reset();
                //this.$emit('changeStage', 1);
            },
        },
        mounted() {

        }
    }
</script>

<style lang="scss">
    .id-info{
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
</style>
