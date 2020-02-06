<template>
    <div class="modal-dialog modal-dialog-centered" style="width:440px;">
        <div class="modal-content">
            <div class="container">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">                
                <form class="update">
                    <div class="row">
                        <label class="col-sm-3 col-form-label">予約ID:</label>
                        <div class="col-sm-8">
                            <p>010-XXXXXXXXXXXXX</p>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-sm-3 col-form-label">施術日:</label>
                        <div class="col-sm-8">
                            <p>{{data.date}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label for="hours" class="col-sm-3 col-form-label">時間:</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control form-control-sm" placeholder="10:00">
                        </div>
                        <label for="" class="col-sm-1 col-form-label">~</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control form-control-sm" placeholder="12:00">
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-sm-3 col-form-label">院名:</label>
                        <div class="col-sm-8">
                            <p>{{data.clinic}}</p>
                        </div>
                    </div>
                    <fieldset class="form-group" style="margin-bottom:0px;">
                        <div class="row">
                            <label for="" class="col-sm-3 col-form-label">区分:</label>
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                <input v-model="form.is_new" class="form-check-input" type="radio" name="in_new" id="0" value="新規">
                                <label class="form-check-label" for="1" >新規</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input v-model="form.is_new" class="form-check-input" type="radio" name="in_new" id="1" value="再診">
                                <label class="form-check-label" for="inlineRadio2">再診</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input v-model="form.is_new" class="form-check-input" type="radio" name="in_new" id="2" value="カウンセリング" disabled>
                                <label class="form-check-label" for="inlineRadio3">カウンセリング</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row">
                        <label for="" class="col-sm-3 col-form-label">施術者:</label>
                        <div class="col-sm-8">
                            <select class="form-control form-control-sm" id="exampleFormControlSelect1">
                                <option>{{data.staff_rank}}</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                    <fieldset class="form-group" style="margin-bottom:0px;">
                        <div class="row">
                            <label for="" class="col-sm-3 col-form-label">指名:</label>
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="3" value="あり">
                                <label class="form-check-label" for="3">あり</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="4" value="なし">
                                <label class="form-check-label" for="4">なし</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row">
                        <label for="" class="col-sm-3 col-form-label">メニュー:</label>
                        <div class="col-sm-8">
                            <select class="form-control form-control-sm" id="exampleFormControlSelect1">
                                <option>アイブロウ２回 2/2</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-sm-3 col-form-label" style="letter-spacing: -1.8px;">カウンセラー:</label>
                        <div class="col-sm-8">
                            <select class="form-control form-control-sm" id="exampleFormControlSelect1">
                                <option>09:20〜10:00 加野</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label for="hours" class="col-sm-3 col-form-label">お客様各:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="プレミアム 花子">
                        </div>
                    </div>
                    <div class="row">
                        <label for="hours" class="col-sm-3 col-form-label">フリガナ:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="time" placeholder="プレミアム ハナコ">
                        </div>
                    </div>
                    <div class="row">
                        <label for="hours" class="col-sm-3 col-form-label">生年月日:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="2002/6/8">
                        </div>
                    </div>
                    <div class="row">
                        <label for="hours" class="col-sm-3 col-form-label">電話番号:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" placeholder="080-xxx-xxxx">
                        </div>
                    </div>
                    <div class="row">
                        <label for="" class="col-sm-3 col-form-label">予約ルー卜:</label>
                        <div class="col-sm-8">
                            <select class="form-control form-control-sm" id="exampleFormControlSelect1">
                                <option>システム</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="" class="col-sm-3 col-form-label">経験:</label>
                    </div>
                    <div class="experience">
                        <div>
                            <label for="" class="col-sm-8 col-form-label">妊娠・授乳・不妊治療 :</label>
                        </div> 
                        <div>
                            <label for="" class="col-sm-8 col-form-label">通院歴・薬 :</label>
                        </div>
                        <div>
                            <label for="" class="col-sm-8 col-form-label">金アレ・アトピー・ケロイド確認 :</label>
                        </div>
                        <div>
                            <label for="" class="col-sm-8 col-form-label">眉ブリーチ・炎症・傷跡確認 :</label>
                        </div>
                        <div>
                            <label for="" class="col-sm-8 col-form-label">美容サービス・美容整形確認 :</label>
                        </div>
                        <div>
                            <label for="" class="col-sm-8 col-form-label">料金・所要時間 :</label>
                        </div>
                        <div>
                            <label for="" class="col-sm-8 col-form-label">HP :</label>
                        </div>                                                                                                         <div>
                            <label for="" class="col-sm-8 col-form-label">キャンセル規約 :</label>
                        </div>           
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn">取消</a>
                <a href="#" class="btn btn-primary" style="background:rgb(197, 224, 180); color:black">登録</a>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props:['data'],
        data () {
            return {
                tabbtns:['来院','会計','終了','キャンセル'],
                selected:'来院',
                users:{},
                dialog: false,
                userAges: [],
                userLvs: [],
                userAge: 0,
                userLv: 0,
                userName: '',
                snackbar: false,
                sbMsg: '',
                putId: '',
                changeMode:false,
                form: new Form({
                    id: '',
                    name: ''
                }),
            }
        },
        mounted () {
            for (let i = 10; i < 40; i++) this.userAges.push(i)
            for (let i = 0; i < 4; i++) this.userLvs.push(i)
            //this.getUsers()
        },
        methods: {
            changeBtnClick(){
                this.changeMode = false;
                this.form.reset();
                $('#modalShowUpdate').modal('show');
            }, 
            getUsers () {
                this.$axios.get('manage/user')
                    .then((r) => {
                        this.users = r.data.users
                    })
                    .catch((e) => {
                    if (!e.response) this.$store.commit('pop', { msg: e.message, color: 'warning' })
                    })
            },
            putDialog (user) {
                this.putId = user._id
                this.dialog = true
                this.userName = user.name
                this.userLv = user.lv
                this.userAge = user.age
            },
            putUser () {
                this.dialog = false
                this.$axios.put(`manage/user/${this.putId}`, {
                    name: this.userName, lv: this.userLv, age: this.userAge
                })
                .then((r) => {
                this.$store.commit('pop', { msg: '사용자 수정 완료', color: 'success' })
                this.getUsers()
                })
                .catch((e) => {
                if (!e.response) this.$store.commit('pop', { msg: e.message, color: 'warning' })
                })
            },
            delUser (id) {
                this.$axios.delete(`manage/user/${id}`)
                    .then((r) => {
                    this.$store.commit('pop', { msg: '사용자 삭제 완료', color: 'success' })
                    this.getUsers()
                    })
                    .catch((e) => {
                    if (!e.response) this.$store.commit('pop', { msg: e.message, color: 'warning' })
                    })
            },
            pop (msg) {
                this.snackbar = true
                this.sbMsg = msg
            }
        }
    }
</script>
<style lang="scss">

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