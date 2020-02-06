import VueGridLayout from 'vue-grid-layout';
import Datepicker from 'vuejs-datetimepicker';
import Datetime from 'vue-datetime';
import ModalInfoDlg from './ModalInfoDlg.vue';
import ModalUpdateDlg from './ModalUpdateDlg.vue';
var hdLayout = [
    { "x": 0, "y": 0, "w": 2, "h": 2, "i": "", "static": true },

    { "x": 2, "y": 0, "w": 2, "h": 1, "i": "", "static": true },
    { "x": 4, "y": 0, "w": 2, "h": 1, "i": "", "static": true },
    { "x": 20, "y": 1, "w": 2, "h": 1, "i": "", static: true },
];

var timeLayout = [

    { "x": 0, "y": 0, "w": 2, "h": 3, "i": "9:00", static: false },
    { "x": 0, "y": 3, "w": 2, "h": 3, "i": "10:00", static: false },
    { "x": 0, "y": 6, "w": 2, "h": 3, "i": "11:00", static: false },
    { "x": 0, "y": 9, "w": 2, "h": 3, "i": "12:00", static: false },
    { "x": 0, "y": 12, "w": 2, "h": 3, "i": "13:00", static: false },
    { "x": 0, "y": 15, "w": 2, "h": 3, "i": "14:00", static: false },
    { "x": 0, "y": 18, "w": 2, "h": 3, "i": "15:00", static: false },
    { "x": 0, "y": 21, "w": 2, "h": 3, "i": "16:00", static: false },
    { "x": 0, "y": 24, "w": 2, "h": 3, "i": "17:00", static: false },
    { "x": 0, "y": 27, "w": 2, "h": 3, "i": "18:00", static: false },
    { "x": 0, "y": 30, "w": 2, "h": 3, "i": "19:00", static: false },

    //{ "x": 6, "y": 0, "w": 2, "h": 3, "i": "【新規】\t010-XXXXXXXXプレミアムハナコ指名ありアイブロウ2回 1/2カウセ9:20～10:00加野", static: false }, 
];

export default {
    components: {
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem,
        Datepicker,
        Datetime,
        ModalInfoDlg,
        ModalUpdateDlg,
    },
    data() {
        return {
            hdlayout: [], //JSON.parse(JSON.stringify(hdLayout)),
            conlayout: [], //JSON.parse(JSON.stringify([])),
            timelayout: [],
            draggable: false,
            resizable: false,
            mirrored: false,
            responsive: true,
            preventCollision: false,
            rowHeight: 30,
            colNum: 33,
            index: 0,
            editMode: false,
            form: new Form({
                id: '',
                name: ''
            }),
            clinics: {},
            staffs: {},
            current_date: '',
            selected_clinic_id: 0,
            selected_clinic_name: '',
            message: "Welcome, Please Wait....",
            senddata: {
                date: '',
                clinic: '',
                time: '',
                is_new: '',
                staff_rank: '',
                techname: '',
            }
        }
    },
    mounted() {
        console.log('Component mounted.');
        this.index = this.conlayout.length;
    },
    created() {
        console.log('Component created.');
        this.loadClinicList();
        //this.loadStaffRanksList();
        this.current_date = this.formatDate(new Date());
    },

    methods: {
        callFunction: function() {
            var currentDate = new Date();
            console.log(currentDate);
            var currentDateWithFormat = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
            console.log(currentDateWithFormat);

        },
        loadClinicList() {
            axios.get('api/clinic').
            then(({ data }) => {
                this.clinics = data;
                this.selected_clinic_id = this.clinics[0].id;
                this.selected_clinic_name = this.clinics[0].name;
                this.loadStaffRanksList();
            });
        },
        loadStaffRanksList() {
            axios.get('v1/reservation/staffs_ranks?clinic_id=' + this.selected_clinic_id).
            then(({ data }) => {
                this.staffs = data.staff_layout;
                //this.timelayout = JSON.parse(JSON.stringify(timeLayout));                
                if (data.count > 10) {
                    this.colNum = (data.count + 1) * 3;
                }
                this.hdlayout = JSON.parse(JSON.stringify(data.staff_layout));
                this.conlayout = JSON.parse(JSON.stringify(data.content_layout));

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

        dateSelected(e) {
            console.log(this.current_date, 'want to check...');
        },

        clinicSelected(clinic) {
            this.selected_clinic_id = clinic.id;
            this.selected_clinic_name = clinic.name;
            this.loadStaffRanksList();
        },

        onClick: function(event, item) {
            console.log("click i=" + "(" + item.x + "," + item.y + ")");
            if (item.selectable) {
                this.senddata.date = this.current_date;
                this.senddata.clinic = this.selected_clinic_name;
                this.senddata.time = item.time;
                this.senddata.is_new = item.is_new;
                this.senddata.staff_rank = item.staff_rank;
                console.log(this.senddata);
                this.editMode = false;
                this.form.reset();
                $('#modalInfoDlg').modal('show');
            }
        },
        updateBtn: function() {
            this.editMode = false;
            $('#modalShowUpdate').modal('show');
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