import VueGridLayout from 'vue-grid-layout';
import Datepicker from 'vuejs-datetimepicker';
import ModalInfoDlg from './ModalInfoDlg.vue';
import ModalUpdateDlg from './ModalUpdateDlg.vue';

var moment = require('moment');
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
              }
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
        onStatusChanged: function(status) {
            this.order_status = status;
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
        onOrderCreated: function(item) {
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
                    cell.customer_phonenumber = item.customer_phonenumber;
                    cell.customer_birthday = item.customer_birthday;
                    cell.note = item.note;
                    cell.old_itvr_x = item.old_itvr_x;
                    cell.old_itvr_y = item.old_itvr_y;
                    if (item.interviewer_id){
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
            let url = '/api/clinic';
            if (this.$gate.isClinic())
                url = '/api/clinic?email=' + this.$gate.getEmail();
            axios.get(url).
            then(({ data }) => {
                this.clinics = data;
                this.selected_clinic = this.clinics[0];
                this.loadStaffRanksList();
            });
        },
        loadStaffRanksList() {
            //axios.get('v1/reservation/staff_list?clinic_id=' + this.selected_clinic_id ).
            axios.post('/v1/reservation/staff_list', { 'clinic_id': this.selected_clinic.id, 'date': moment(this.selectedDate).format("YYYY-MM-DD") }).
            then(({ data }) => {
                this.staffs = data.staff_layout;
                //this.timelayout = JSON.parse(JSON.stringify(timeLayout));                
                if (data.count > 10) {
                    this.colNum = (data.count + 1) * 3;
                }
                this.hdlayout = JSON.parse(JSON.stringify(data.staff_layout));
                this.conlayout = JSON.parse(JSON.stringify(data.content_layout));
            });
            axios.post('/v1/reservation/staff_rank_list', { 'clinic_id': this.selected_clinic.id, 'date': this.selectedDate }).
            then(({ data }) => {
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
            console.log(item, "cellClicked.");
            if (item.selectable) {
                this.bus.$emit('clearFormErrors');
                $(".vue-grid-item").removeClass("selectedcolor");
                $(event.currentTarget).addClass("selectedcolor"); //defalt color when click..                
                // Here this variable is Reservation Vue component (Parent of modals)
                axios.post('/v1/reservation/menu_list', { 'rank_id': item.rank_id, 'date': moment(this.selectedDate).format("YYYY-MM-DD") }).
                then(({ data }) => {
                    this.menus = data;
                });
                
                if (item.rank_id == 9 || item.rank_id == 8 || item.rank_id == 7) { //NA, T, counseler
                    this.item = item;
                    this.item['date'] = this.formatDate(this.selectedDate);
                    this.$refs.modalUpdateDlg.loadInfo();
                    if (item.order_history_id == 0) {
                        // New order creating
                        this.changeMode = false;
                        $('#modalUpdateDlg').modal('show');
                    } else {
                        // order info and editing
                        $('#modalInfoDlg').modal('show');
                    }
                    return;
                }
                axios.post('/v1/reservation/counselor_list', 
                        { 
                            'clinic_id': this.selected_clinic.id,
                            'date': moment(this.selectedDate).format("YYYY-MM-DD"), 
                            'rank_schedule_id': item.rank_schedule_id,
                            'order_history_id':item.order_history_id
                        }).
                then(({ data }) => {
                    this.counselors = data;
                    //선택된 시술자에 해당한 상담원목록을 얻고 그들의 현재 x,y좌표를 구한다. 이값은 신규인경우 상담원칸에 정보를 자동으로 채우는데 리용된다.
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
                        $('#modalInfoDlg').modal('show');
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