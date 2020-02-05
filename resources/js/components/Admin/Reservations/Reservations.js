import VueGridLayout from 'vue-grid-layout';
import Datepicker from 'vuejs-datetimepicker';
import Datetime from 'vue-datetime';
import ModalDialog from './ModalDialog.vue';

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
        ModalDialog,
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
            colNum: 22,
            index: 0,
            editMode: false,
            form: new Form({
                id: '',
                name: ''
            }),
            appointment: {
                appointmet_date: '', //new Date()
            },
            clinics: {},
            orders: {},
            current_date: '',
            current_clinic_id: 0,
        }
    },
    mounted() {
        console.log('Component mounted.');
        this.index = this.conlayout.length;
    },
    created() {
        console.log('Component created.');
        this.loadClinicList();
        console.log(this.current_clinic_id);
        this.loadStaffRanksList();
    },

    methods: {

        loadClinicList() {
            axios.get('api/clinic').
            then(({ data }) => {
                this.clinics = data;
                this.current_clinic_id = this.clinics[0].id;
                console.log(this.current_clinic_id);
            });
        },
        loadStaffRanksList() {
            axios.get('v1/reservation/staffs_ranks?clinic_id=' + this.current_clinic_id).
            then(({ data }) => {
                this.orders = data.staff_layout;
                //console.log(this.orders);
                //this.timelayout = JSON.parse(JSON.stringify(timeLayout));
                this.hdlayout = JSON.parse(JSON.stringify(data.staff_layout));
                this.conlayout = JSON.parse(JSON.stringify(data.content_layout));

            });
        },

        dateSelected(e) {
            // console.log(this.appointment.appointmet_date);
            // this.$nextTick(() => console.log(this.appointment.appointmet_date));
            console.log(this.current_date, 'want to check...');
        },

        clinicSelected(id) {
            this.current_clinic_id = id;
            this.loadStaffRanksList();
        },

        onClick: function(event, x, y) {
            console.log("click i=" + event);
            //var targetId = event.currentTarget.id;
            //window.alert("CLICK!" + "(" + x + "," + y + ")");
            this.editMode = false;
            this.form.reset();
            $('#modalShowInfo').modal('show');
        },
        updateBtn: function() {
            this.editMode = false;
            //$('#modalShowInfo').modal('hide');
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