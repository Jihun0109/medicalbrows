import VueGridLayout from 'vue-grid-layout';
import Datepicker from 'vuejs-datetimepicker';
import ModalInfoDlg from './ModalInfoDlg.vue';
import ModalUpdateDlg from './ModalUpdateDlg.vue';

window.Bus = new Vue({});
window.store = {
    orderdata: {
        reserve_id: '',
        order_type: '',
        staff_rank: '',
        appoint: '',
        menu: '',
        counselor_info: '',
    }
}
var moment = require('moment');
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
            selected_clinic_id: 0,
            selected_clinic_name: '',
            message: "Welcome, Please Wait....",
            staffInfo: {
                date: '',
                clinic: '',
                time: '',
                order_type: '',
                staff_rank: '',
                staff_name: '',
            },
            item: {},
        }
    },
    mounted() {
        console.log('Component mounted.');
        this.index = this.conlayout.length;
        $('#modalInfoDlg').on('hidden.bs.modal', function() {
            $(".vue-grid-item").removeClass("selectedcolor");
        });
        $('#modalUpdateDlg').on('hidden.bs.modal', function() {
            $(".vue-grid-item").removeClass("selectedcolor");
        });
    },
    created() {
        console.log('Component created.');
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
        increment: function() {
            this.selectedDate = new Date(moment(this.selectedDate).add(1, "days"));
            this.loadStaffRanksList();
        },
        decrement: function() {
            this.selectedDate = new Date(moment(this.selectedDate).subtract(1, "days"));
            this.loadStaffRanksList();
        },
        onOrderCreated: function(item) {
            console.log("onOrderCreated");
            console.log(item);
            this.conlayout.forEach(function(cell) {
                if (cell.x == item.x && cell.y == item.y) {
                    cell.i = item.i;
                    cell.order_serial_id = item.order_serial_id;
                    cell.order_history_id = item.order_history_id;
                    cell.order_status = item.order_status;
                    cell.order_type = item.order_type;
                    cell.menu_id = item.menu_id;
                    cell.menu_name = item.menu_name;
                    cell.staff_choosed = item.staff_choosed;
                    cell.customer_first_name = item.customer_first_name;
                    cell.customer_last_name = item.customer_last_name;
                    cell.customer_phonenumber = item.customer_phonenumber;
                    cell.customer_birthday = item.customer_birthday;
                    return false;
                }
            });

        },
        callFunction: function() {
            var currentDate = new Date();
            console.log(currentDate);
            var currentDateWithFormat = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
        },
        loadClinicList() {
            axios.get('/api/clinic').
            then(({ data }) => {
                this.clinics = data;
                this.selected_clinic_id = this.clinics[0].id;
                this.selected_clinic_name = this.clinics[0].name;
                this.loadStaffRanksList();
            });
            axios.get('/api/menu').
            then(({ data }) => {
                this.menus = data.data;
            });
        },
        loadStaffRanksList() {
            //axios.get('v1/reservation/staff_list?clinic_id=' + this.selected_clinic_id ).
            axios.post('/v1/reservation/staff_list', { 'clinic_id': this.selected_clinic_id, 'date': this.selectedDate }).
            then(({ data }) => {
                this.staffs = data.staff_layout;
                //this.timelayout = JSON.parse(JSON.stringify(timeLayout));                
                if (data.count > 10) {
                    this.colNum = (data.count + 1) * 3;
                }
                this.hdlayout = JSON.parse(JSON.stringify(data.staff_layout));
                this.conlayout = JSON.parse(JSON.stringify(data.content_layout));
            });
            axios.post('/v1/reservation/staff_rank_list', { 'clinic_id': this.selected_clinic_id, 'date': this.selectedDate }).
            then(({ data }) => {
                this.staff_rank_list = data;
                //console.log(this.staffInfo);
            });
            axios.post('/v1/reservation/counselor_list', { 'clinic_id': this.selected_clinic_id, 'date': this.selectedDate }).
            then(({ data }) => {
                //console.log(data, 'counselor list');
                this.counselors = data;
            });
        },

        formatDate(dt) {
            var month = ('0' + (dt.getMonth() + 1)).slice(-2);
            var date = ('0' + dt.getDate()).slice(-2);
            var year = dt.getFullYear();
            var currentDateWithFormat = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
            console.log(currentDateWithFormat);
            //var formattedDate = year + '年' + month + '月' + date + '日';
            var formattedDate = year + '-' + month + '-' + date;
            return formattedDate;
        },

        changedDate() {
            console.log(this.selectedDate, 'want to check...');
        },

        functionEvents(date) {
            console.log(date, 'want to check...');
        },

        clinicSelected(clinic) {
            this.selected_clinic_id = clinic.id;
            this.selected_clinic_name = clinic.name;
            this.loadStaffRanksList();
        },

        onClick: function(event, item, index) {
            console.log("Item Info");
            if (item.selectable) {
                console.log(item);
                $(event.currentTarget).addClass("selectedcolor"); //defalt color when click..                
                //this.activeColor = index;
                // Here this variable is Reservation Vue component (Parent of modals)
                this.item = item;
                this.item['date'] = this.formatDate(this.selectedDate);
                console.log(this.item, 'after clicked item');
                if (item.order_history_id == 0) {
                    // New order creating
                    this.changeMode = false;
                    $('#modalUpdateDlg').modal('show');
                } else {
                    // order info and editing
                    $('#modalInfoDlg').modal('show');
                }
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
        },
        removeItem: function(item) {
            //console.log("### REMOVE " + item.i);
            this.conlayout.splice(this.conlayout.indexOf(item), 2);
        },
        addItem: function() {
            // let self = this;
            //console.log("### LENGTH: " + this.layout.length);
            //newX = 0;
            //newY = 0;
            let item = { "x": 3, "y": 2, "w": 2, "h": 2, "i": this.index + "", whatever: "bbb" };
            this.index++;
            this.conlayout.push(item);
        },
    }
}