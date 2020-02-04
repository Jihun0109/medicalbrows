<template>

    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button @click="updateBtn" type="button" class="btn btn-default">asda</button>
                <button @click="updateBtn" type="button" class="btn btn-primary">s更新</button>
                <button @click="updateBtn" type="button" class="btn btn-primary">s更新</button>
                <button @click="updateBtn" type="button" class="btn btn-primary">s更新</button>
            </div>
            <div class="modal-body" v-for="user in users" :key="user._id">
                <div class="info">
                    <div>予約ID : {{user.name}}</div>
                    <div>施術日 : {{user.lv}}</div>
                    <div>院名 : {{user.age}}</div>
                    <div>時間 : {{user.inCnt}}</div>
                    <div>区分 : {{user._id}}</div>
                    <div>施術者 : {{user.pwd}}</div>
                    <div>指名 : {{user.inCnt}}</div>
                    <div>メニュー : アイブ口ウ2回 </div>
                    <div>カウンセラー : {{user.pwd}}</div>
                    <div>お客様各 : {{user.inCnt}}</div>
                    <div>生年月日 : {{user._id}}</div>
                    <div>電話番号 : {{user.pwd}}</div>
                    <div>予約ルー卜 : {{user.inCnt}}</div>
                    <div>備考 : {{user.pwd}}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">メール送信</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">予約取消</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">変更</button>
            </div>
        </div>

    </div>

</template>

<script>
    var userInfo=[
        {   "lv":123123,
            "age": 213,
            "isCnt":123,
            "id":'AAAAAAAAA',
            "pwd":"BBBBBBBB"},
    ];

    export default {
        data () {
            return {
            users:userInfo,
            dialog: false,
            userAges: [],
            userLvs: [],
            userAge: 0,
            userLv: 0,
            userName: '',
            snackbar: false,
            sbMsg: '',
            putId: ''
            }
        },
        mounted () {
            for (let i = 10; i < 40; i++) this.userAges.push(i)
            for (let i = 0; i < 4; i++) this.userLvs.push(i)
            //this.getUsers()
        },
        methods: {
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