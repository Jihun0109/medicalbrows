<template>
    <div class="modal-dialog modal-dialog-centered" style="width:440px;">
        <div class="modal-content">
            <div class="container">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">                
                <form class="update">
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="row">
                        <label class="col-sm-3 col-form-label">予約ID:</label>
                        <div v-show="isShow(form.order_type)" class="col-sm-8" >
                            <p>{{form.order_serial_id==0?'':item.order_serial_id}}</p>
                        </div>
                        <div v-show="!isShow(form.order_type)" class="col-sm-8">
                            <input v-model="form.order_serial_id" type="text" class="form-control form-control-sm" placeholder="xxxxxxxx-xxxx" 
                            :class="{'is-invalid': form.errors.has('order_serial_id')}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">施術日:</label>
                        <div class="col-sm-8">
                            <p>{{item.date}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label for="hours" class="col-sm-3 col-form-label">時間:</label>
                        <div class="col-sm-8">
                            <p>{{item.time}}</p>
                        </div>

                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">院名:</label>
                        <div class="col-sm-8">
                            <p>{{item.clinic_name}}</p>
                        </div>
                    </div>
                    
                    <fieldset class="form-group" style="margin-bottom:0px;">
                        <div class="row">
                            <label class="col-sm-3 col-form-label">区分:</label>
                            <div class="col-sm-8" style="padding-top: 7px;">
                                <div class="form-check form-check-inline" >
                                    <input class="form-check-input" type="radio" name="order_type" value="新規" v-model="form.order_type" :disabled="item.rank_name === 'カウゼ'">
                                    <label class="form-check-label">新規</label>
                                </div>
                                <div class="form-check form-check-inline" >
                                    <input class="form-check-input" type="radio" name="order_type" value="再診"  v-model="form.order_type" :disabled="item.rank_name === 'カウゼ'">
                                    <label class="form-check-label">再診</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="order_type" value="カウンセ" v-model="form.order_type">
                                    <label class="form-check-label">カウンセリング</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row" style="letter-spacing:-1.8px;">
                        <label class="col-sm-3 col-form-label" v-if="item.rank_name === 'カウゼ'">カウンセラー:</label>
                        <label class="col-sm-3 col-form-label" v-else>施術者:</label>
                        <div class="col-sm-8" style="padding-top: 7px;">
                            <!-- <select v-model="staff_rank" class="form-control form-control-sm" data-width="fit" id="exampleFormControlSelect1">
                                <option disabled value="">項目を選択してください</option>
                                <option v-for="n in sr_list" :key="n.id" v-bind:value="n.name">{{n.name}}</option>
                            </select> -->
                            <div style="letter-spacing: -1.2px">{{item.staff_name + '【' + item.rank_full_name + '】'}}</div>
                        </div>
                    </div>
                    <fieldset class="form-group" style="margin-bottom:0px;">
                        <div class="row">
                            <label class="col-sm-3 col-form-label">指名:</label>
                            <div class="col-sm-8" style="padding-top: 7px;">
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stuff_choosed" value="あり" v-model="form.stuff_choosed">
                                <label class="form-check-label">あり</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="stuff_choosed"  value="なし" v-model="form.stuff_choosed">
                                <label class="form-check-label">なし</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">メニュー:</label>
                        <div class="col-sm-8"  style="padding-top: 7px;" v-show="item.rank_name !== 'カウゼ'">
                            <select v-model="form.menu_id" class="custom-select custom-select-sm form-control-sm" >
                                <option disabled value="">項目を選択してください</option>
                                <option v-for="m in menus" :key="m.id" v-bind:value="m.menu_id">{{m.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" v-show="item.rank_name !== 'カウゼ'">
                        <label class="col-sm-3 col-form-label" style="letter-spacing: -1.8px;">カウンセラー:</label>
                        <div class="col-sm-8" style="padding-top: 7px;"  v-show="isShow(form.order_type)">
                            <select v-model="form.counselor" class="custom-select custom-select-sm form-control-sm">
                                <option disabled value="">項目を選択してください</option>
                                <option v-for="c in counselors" :key="c.id" v-bind:value="c">{{c.timename}}</option>            
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">お客様名:</label>
                        <div class="col-sm-8">
                            <input v-model="form.first_name" id="customerName" type="text" class="form-control form-control-sm" placeholder="お客様名を入力" :class="{'is-invalid':form.errors.has('first_name')}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">フリガナ:</label>
                        <div class="col-sm-8">
                            <input v-model="form.last_name" id="customerFurigana" type="text" class="form-control form-control-sm" :class="{'is-invalid':form.errors.has('last_name')}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">生年月日:</label>
                        <div class="col-sm-8">
                            <input v-model="form.birthday" type="date" class="form-control form-control-sm" placeholder="生年月日" 
                            :class="{'is-invalid': form.errors.has('birthday')}">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">電話番号:</label>
                        <div class="col-sm-8">
                            <input v-model="form.phonenumber" type="tel" class="form-control form-control-sm" v-mask="{mask:'999-999-9999', placeholder:'#'}" :class="{'is-invalid':form.errors.has('phonenumber')}">
                            <!-- <input v-model="form.phonenumber" type="text" class="form-control form-control-sm" placeholder="080-xxx-xxxx" :class="{'is-invalid':form.errors.has('phonenumber')}"> -->
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label">予約ルー卜:</label>
                        <div class="col-sm-8">
                            <select v-model="form.order_route" class="custom-select custom-select-sm form-control-sm">
                                <option disabled value="">項目を選択してください</option>
                                <option v-for="(r,index) in routes" :key="index" v-bind:value="r">{{r}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>備考:</label>
                        <div class='row textarea-placeholder'>
                            <custom-note v-model="form.note"></custom-note>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn">取消</a>
                <a href="#" v-on:click="createCustomerInfo" class="btn btn-primary" style="background:rgb(197, 224, 180); color:black">登録</a>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomNote from './CustomNote.vue';
    import * as AutoKana from 'vanilla-autokana';
    export default {
        props:['item','sr_list','menus','counselors','childbus'],
        components: {
            CustomNote
        },
        data () {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                form: new Form({
                    order_type : '新規',
                    stuff_choosed : 'あり',
                    menu_id : '',
                    counselor : '',
                    first_name : '',
                    last_name : '',
                    birthday : '',
                    phonenumber : '',
                    order_route : 'システム',
                    order_serial_id : '',
                    note:'',
                    old_itvr_x: '',
                    old_itvr_y: '',
                }),
                routes:['システム','電話','Web','チャットボット'],
            }
        },
        mounted () {            
            this.childbus.$on('clearFormErrors', this.clearErrors);            
            AutoKana.bind('#customerName', '#customerFurigana');
        },
        created() {
            this.loadInfo();            
        },
        methods: {
            isShow: function(order_type){                
                if(order_type =="再診")
                    return false;
                if (this.item.rank_name == 'T' || this.item.rank_name == 'NA')
                    return false;
                return true;
            },
            createCustomerInfo(){
                this.form['item'] = this.item;
                //in the case of neworder                
                if(this.item.order_serial_id == ''){
                    this.form.post('/v1/order-create')
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
                                //console.log(result.data);
                                result.data.forEach(element => {                                    
                                    this.$emit('orderCreated', element);
                                });
                                $('#modalUpdateDlg').modal('hide');
                            }
                        })
                        .catch(()=>{
                            console.log('create error');
                        });  
                }
                else{
                    this.form.post('/v1/order-update')
                        .then((result)=>{
                            toast.fire({
                                icon: "success",
                                title: "更新成功!"
                            });
                            $('#modalUpdateDlg').modal('hide');
                            result.data.forEach(element => {                                    
                                this.$emit('orderCreated', element);
                            });
                            //this.$emit('orderCreated', result.data);
                        })
                        .catch(()=>{
                            console.log('update error');
                        });  
                }

            },
            clearErrors(){
                this.form.first_name = '';
                this.form.last_name = '';
                this.form.birthday = '';
                this.form.phonenumber = '';
                this.form.errors.clear();
            },
            loadInfo(){
                this.form.order_type = this.item.order_type;
                this.form.stuff_choosed = this.item.staff_choosed;
                this.form.menu_id = this.item.menu_id;                
                this.form.first_name = this.item.customer_first_name;
                this.form.last_name = this.item.customer_last_name;
                this.form.birthday = this.item.customer_birthday;
                this.form.phonenumber = this.item.customer_phonenumber;
                this.form.order_route = this.item.order_route;
                this.form.order_serial_id = this.item.order_serial_id;
                this.form.note = this.item.note != "" ? this.item.note : '経験 : \n妊娠・授乳・不妊治療 : \n通院歴・薬 : \n金アレ・アトピー・ケロイド確認 : \n眉ブリーチ・炎症・傷跡確認 : \n美容サービス・美容整形確認 : \n料金・所要時間 : \nHP : \nキャンセル規約 : ';                
                
                for (var i=0; i<this.counselors.length; i++){
                    
                    if (this.counselors[i]['interviewer_id'] === this.item.interviewer_id){
                        this.form.counselor = this.counselors[i];                        
                        this.form['old_itvr_x'] = this.counselors[i].x;
                        this.form['old_itvr_y'] = this.counselors[i].y;
                    }
                }
                console.log(this.form,'log from loadInfo');
            },
        },
        watch:{
            item: function(val){
                this.loadInfo();
            }
        }
    }
</script>
<style lang="scss">
    textarea{
        margin:auto;
        min-height: 180px;
    }
    .container{
        padding-top:10px;
    }
    .update{
        p{
            margin:7px;
        }
        div{
            margin-bottom: -5px;
        }
        .experience{
            border:1px solid black;
            margin: 0 12px 0 12px;
            padding-bottom: 17px;
            div{
                margin-bottom: -17px;
            }
        }
    }
    .info {
        div{
            padding-left: 10px;
        }
    }

</style>