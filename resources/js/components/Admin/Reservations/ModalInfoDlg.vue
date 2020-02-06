<template>
    <div class="modal-dialog modal-dialog-centered" style="width:400px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="el-row"> 
                    <button v-for="tab in tabbtns" 
                            :key="tab"                     
                            type="button" 
                            @click="selected = tab;" 
                            class="el-button  el-button--primary el-button--medium" 
                            :class="['tab-btn', { active: selected === tab }]"                    
                            >
                        <span>{{tab}}</span>
                    </button>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="info">
                    <div>予約ID : </div>
                    <div>施術日 : {{data.date}}</div>
                    <div>院名 : {{data.clinic}}</div>
                    <div>時間 : {{data.time}}</div>
                    <div>区分 : {{data.is_new}}</div>
                    <div>施術者 : {{data.staff_rank}}</div>
                    <div>指名 : {{data.techname}}</div>
                    <div>メニュー : </div>
                    <div>カウンセラー : </div>
                    <br>
                    <div>お客様各 : </div>
                    <div>生年月日 : </div>
                    <div>電話番号 : </div>
                    <div>予約ルー卜 : </div>
                    <div>備考 : </div>
                    <div class="experience">
                        <div>経験 : </div>
                        <div>妊娠・授乳・不妊治療 : </div>
                        <div>通院歴・薬 : </div>
                        <div>金アレ・アトピー・ケロイド確認 : </div>
                        <div>眉ブリーチ・炎症・傷跡確認 : </div>
                        <div>美容サービス・美容整形確認 : </div> 
                        <div>料金・所要時間 : </div>
                        <div>HP : </div>
                        <div>キャンセル規約 : </div>    
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button v-show="changeMode" type="button" class="btn btn-secondary" style="background:rgb(197, 224, 180); color:black" data-dismiss="modal">メール送信</button>
                <button v-show="changeMode" type="button" class="btn btn-secondary" style="background:rgb(197, 224, 180); color:black" data-dismiss="modal">予約取消</button>
                <a data-toggle="modal" href="#modalUpdateDlg" class="btn btn-primary" style="background:rgb(197, 224, 180); color:black">変更</a>
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
    .info {
        div{
            padding-left: 10px;
        }
    }

</style>