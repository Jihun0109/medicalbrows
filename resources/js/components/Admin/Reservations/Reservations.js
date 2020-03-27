import VueGridLayout from 'vue-grid-layout';
import Datepicker from 'vuejs-datetimepicker';
import ModalInfoDlg from './ModalInfoDlg.vue';
import ModalUpdateDlg from './ModalUpdateDlg.vue';
// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.use(Loading);

window.Bus = new Vue();
export default {
    components: {
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem,
        Datepicker,
        ModalInfoDlg,
        ModalUpdateDlg,
    },
    data() {
        return {
            bus: new Vue(),
            order_status: '',
            hdlayout: [], //JSON.parse(JSON.stringify(hdLayout)),
            conlayout: [], //JSON.parse(JSON.stringify([])),
            timelayout: [],
            activeColor: '',
            rowHeight: 30,
            colNum: 33,
            index: 0,
            form: new Form({
                id: '',
                name: ''
            }),
            clinics: {},
            staffs: {},
            staff_rank_list: {},
            menus: {},
            counselors: {},
            selectedDate: '',
            attrs: [{
                dot: {
                    class: 'high-light'
                },
                dates: new Date()
            }],
            selected_clinic: '',
            item: {},
            formats: {
                title: 'YYYY年 MMMM',
                weekdays: 'W',
                navMonths: 'MMM',
                input: ['YYYY年MM月DD日 (W)'],
                dayPopover: 'L',
                data: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD']
            },
            fullPage: true,
            mailtitle: '',
            contents: [
                'メディカルブローです。\r\n9 / 12(木)～ 名古屋駅前院でのご予約を承っております。\nご予約の変更・ キャンセルがございましたら、 2 日前17時までに【 0570 - 078 - 889】 までご連絡をお願い致します。 以降のご変更は、 キャンセル料金【 5, 000 円(税別)】 のご負担をいただく可能性がございますのでご了承下さい。 当日は、 ご予約時間の5分前到着でご来院をお願い致します。\n *このSMSは送信専用です。 ',
                'メディカルブローです。予約変更を承りました。\n\n予約ID: \n■ 変更前\n日時：\n■ 変更後\n日時：\n\n本件についてのお問い合わせは【 0570 - 078 - 889】 までご連絡をお願い致します。\n *このSMSは送信専用です。 ',
                'メディカルブローです。予約キャンセルを承りました。\n予約ID: \n予約日時：\nキャンセル料発生： 5, 000 円(税別)\n\n本件についてのお問い合わせは【 0570 - 078 - 889】 までご連絡をお願い致します。 * \nこのSMSは送信専用です。 ',
                'メディカルブローです。\nこの度は弊社クリニックをご利用いただき誠にありがとうございます。\nまたの機会をお待ちしております。\n今後とも宜しくお願い申し上げます。 * \nこのSMSは送信専用です。 ',
            ],
            contentIdex: 0,
        }
    },
    mounted() {
        this.index = this.conlayout.length;
        $('#modalInfoDlg').on('hidden.bs.modal', function() {
            $(".vue-grid-item").removeClass("selectedcolor");
        });
        $('#modalUpdateDlg').on('hidden.bs.modal', function() {
            $(".vue-grid-item").removeClass("selectedcolor");
        });
    },
    created() {
        this.loadClinicList();
        //Wed Feb 19 2020 00:00:00 GMT+0800 (China Standard Time)
        this.selectedDate = new Date();
    },
    watch: {
        selectedDate(val) {
            this.loadStaffRanksList();
        }
    },

    methods: {
        onClickSendMail: function() {
            //v-bind:href="'mailto:'+clinic.email"
            console.log(this.item);
            var mail_info = {};
            mail_info['customer_email'] = this.item.customer_email;
            mail_info['customer_first_name'] = this.item.customer_first_name;
            mail_info['customer_last_name'] = this.item.customer_last_name;
            mail_info['mailtitle'] = this.mailtitle;
            mail_info['content'] = this.contents[this.contentIdex];
            mail_info['clinic_email'] = this.selected_clinic.email;
            mail_info['clinic_name'] = this.item.clinic_name;
            mail_info['order_history_id'] = this.item.order_history_id;
            let loader = this.$loading.show({
                // Optional parameters
                container: this.fullPage ? null : this.$refs.formContainer,
                canCancel: false,
                onCancel: this.onCancel,
            });

            axios.post('/v1/send_mail', {
                    'mail_info': mail_info
                })
                .then((result) => {
                    toast.fire({
                        icon: "success",
                        title: "メール送信成功"
                    });
                    loader.hide();
                    console.log(result.data);
                })
                .catch(() => {
                    console.log('send mail error');
                    toast.fire({
                        icon: "error",
                        title: "メールの送信に失敗しました"
                    });
                    loader.hide();
                });
        },
        onStatusChanged: function(status) {
            this.order_status = status;
        },
        cancelUpdateBtn: function() {
            $('#modalUpdateMessageBox').modal('hide');
            $('#modalInfoDlg').modal('show');
        },
        confirmUpdateBtn: function() {
            $('#modalUpdateMessageBox').modal('hide');
            console.log("init order form");
            this.changeMode = false;
            this.item.order_history_id = ""
            this.item.order_serial_id = ""
            this.item.order_status = 'static'
            this.item.customer_birthday = ""
            this.item.customer_email = ""
            this.item.customer_first_name = ""
            this.item.customer_last_name = ""
            this.item.customer_phonenumber = ""
            this.item.interviewer_id = ""
            this.item.menu_id = ""
            this.item.note = "経験 : \n妊娠・授乳・不妊治療 : \n通院歴・薬 : \n金アレ・アトピー・ケロイド確認 : \n眉ブリーチ・炎症・傷跡確認 : \n美容サービス・美容整形確認 : \n料金・所要時間 : \nHP : \nキャンセル規約 : "
            this.$refs.modalUpdateDlg.loadInfo();
            $('#modalUpdateDlg').modal('show');

        },
        confirmBtn: function() {
            Bus.$emit('confirmClicked');
            $('#modalMessageBox').modal('hide');
        },

        increment: function() {
            this.selectedDate = new Date(moment(this.selectedDate).add(1, "days"));
        },

        decrement: function() {
            this.selectedDate = new Date(moment(this.selectedDate).subtract(1, "days"));
        },
        onUpdateCellStatus: function(items) {
            for (var i = 0; i < items.length; i++) {
                var rs_id = items[i].rs_id;
                var staff_id = items[i].staff_id;
                var status = items[i].status;
                var filteredObj = this.conlayout.find(function(cell, i) {
                    if (cell.rank_schedule_id == rs_id && cell.staff_id == staff_id) {
                        cell.order_status = status;
                        return i;
                    }
                });
            }
        },
        onOrderCreated: function(item) {
            console.log(item, 'log info from cell')
            this.conlayout.forEach(function(cell) {
                if (cell.x == item.x && cell.y == item.y) {
                    cell.i = item.i;
                    cell.order_serial_id = item.order_serial_id;
                    cell.order_history_id = item.order_history_id;
                    cell.order_status = item.order_status;
                    cell.order_type = item.order_type;
                    cell.order_route = item.order_route;
                    cell.menu_id = item.menu_id;
                    cell.menu_name = item.menu_name;
                    cell.staff_choosed = item.staff_choosed;
                    cell.customer_first_name = item.customer_first_name;
                    cell.customer_last_name = item.customer_last_name;
                    cell.customer_email = item.customer_email;
                    cell.customer_phonenumber = item.customer_phonenumber;
                    cell.customer_birthday = item.customer_birthday;
                    cell.note = item.note;
                    cell.old_itvr_x = item.old_itvr_x;
                    cell.old_itvr_y = item.old_itvr_y;
                    if (item.interviewer_id) {
                        cell.interviewer_id = item.interviewer_id;
                        cell.interviewer_name = item.interviewer_name;
                    }
                    return false;
                }
            });
            this.$refs.modalUpdateDlg.loadInfo();

        },
        callFunction: function() {
            var currentDate = new Date();
            var currentDateWithFormat = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
        },
        loadClinicList() {
            let url = '/api/clinic?isActive=1';
            if (this.$gate.isClinic())
                url = '/api/clinic?user_id=' + this.$gate.getUserId();
            axios.get(url).
            then(({
                data
            }) => {
                this.clinics = data;
                this.selected_clinic = this.clinics[0];
                this.loadStaffRanksList();
            });
        },
        loadStaffRanksList() {
            //axios.get('v1/reservation/staff_list?clinic_id=' + this.selected_clinic_id ).
            axios.post('/v1/reservation/staff_list', {
                'clinic_id': this.selected_clinic.id,
                'date': moment(this.selectedDate).format("YYYY-MM-DD")
            }).
            then(({
                data
            }) => {
                this.staffs = data.staff_layout;
                //this.timelayout = JSON.parse(JSON.stringify(timeLayout));                
                if (data.count > 10) {
                    this.colNum = (data.count + 1) * 3;
                }
                this.hdlayout = JSON.parse(JSON.stringify(data.staff_layout));
                this.conlayout = JSON.parse(JSON.stringify(data.content_layout));
            });
            axios.post('/v1/reservation/staff_rank_list', {
                'clinic_id': this.selected_clinic.id,
                'date': this.selectedDate
            }).
            then(({
                data
            }) => {
                this.staff_rank_list = data;
                //console.log(this.staffInfo);
            });
        },

        formatDate(dt) {
            var month = ('0' + (dt.getMonth() + 1)).slice(-2);
            var date = ('0' + dt.getDate()).slice(-2);
            var year = dt.getFullYear();
            var currentDateWithFormat = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
            //var formattedDate = year + '年' + month + '月' + date + '日';
            var formattedDate = year + '-' + month + '-' + date;
            return formattedDate;
        },

        changedDate() {
            console.log(this.selectedDate, 'want to check...');
        },

        clinicSelected(clinic) {
            this.selected_clinic = clinic;
            this.loadStaffRanksList();
        },

        onCellClicked: function(event, item, index) {
            if (item.selectable) {
                console.log(item, '==============');
                if (item.order_status == 'neworder') {
                    this.contentIdex = 1;
                } else if (this.item.order_status == 'cancelorder') {
                    this.contentIdex = 2;
                } else if (this.item.order_status == 'grayconselor') {
                    this.contentIdex = 3;
                }
                this.bus.$emit('clearFormErrors');
                $(".vue-grid-item").removeClass("selectedcolor");
                $(event.currentTarget).addClass("selectedcolor"); //defalt color when click..                
                // Here this variable is Reservation Vue component (Parent of modals)
                axios.post('/v1/reservation/menu_list', {
                    'rank_id': item.rank_id,
                    'date': moment(this.selectedDate).format("YYYY-MM-DD")
                }).
                then(({
                    data
                }) => {
                    this.menus = data;
                });
                //이때는 상담원 목록을 얻을 필요 없어 따로 처리한다.
                if (item.rank_id == 9 || item.rank_id == 8 || item.rank_id == 7) { //NA, T, counseler
                    this.item = item;
                    this.item['date'] = this.formatDate(this.selectedDate);
                    //신규예약 상담원인경우 시술자 구하기
                    if (item.rank_id == 9 && item.order_type == '新規') {
                        var filteredObj = this.conlayout.find(function(cell, i) {
                            if (cell.order_serial_id == item.order_serial_id && cell.interviewer_id == item.staff_id) {
                                return cell;
                            }
                        });
                        this.item['old_staff_info'] = filteredObj;
                    }

                    this.$refs.modalUpdateDlg.loadInfo();
                    if (item.order_history_id == 0) {
                        // New order creating
                        this.changeMode = false;
                        $('#modalUpdateDlg').modal('show');
                    } else {
                        // order info and editing
                        if (item.order_status == "cancelorder") {
                            //open messagebox
                            $('#modalUpdateMessageBox').modal('show');
                        } else {
                            $('#modalInfoDlg').modal('show');
                        }
                    }
                    return;
                }
                axios.post('/v1/reservation/counselor_list', {
                    'clinic_id': this.selected_clinic.id,
                    'date': moment(this.selectedDate).format("YYYY-MM-DD"),
                    'rank_schedule_id': item.rank_schedule_id,
                    'order_history_id': item.order_history_id
                }).
                then(({
                    data
                }) => {
                    this.counselors = data;
                    for (var i = 0; i < this.counselors.length; i++) {
                        var rs_id = this.counselors[i].interviewer_rank_schedule_id;
                        var staff_id = this.counselors[i].interviewer_id;
                        var filteredObj = this.conlayout.find(function(item, i) {
                            if (item.rank_schedule_id == rs_id && item.staff_id == staff_id) {
                                return i;
                            }
                        });
                        this.counselors[i].x = filteredObj.x;
                        this.counselors[i].y = filteredObj.y;
                    }
                    this.item = item;
                    this.item['date'] = this.formatDate(this.selectedDate);
                    this.$refs.modalUpdateDlg.loadInfo();
                    if (item.order_history_id == 0) {
                        // New order creating
                        this.changeMode = false;
                        $('#modalUpdateDlg').modal('show');
                    } else {
                        // order info and editing
                        if (item.order_status == "cancelorder") {
                            //open messagebox
                            $('#modalUpdateMessageBox').modal('show');
                        } else {
                            $('#modalInfoDlg').modal('show');
                        }
                    }
                });

            }
        },

        alertVal() {
            alert(this.dob)
        },
        itemTitle(item) {
            var result = item.i;
            if (item.static) {
                result += " - Static";
            }
            return result;
        }
    }
}