import VueGridLayout from 'vue-grid-layout';
import datetime from 'vuejs-datetimepicker';
import Datepicker from 'vuejs-datetimepicker';
import ModalDialog from './ModalDialog.vue';

var db = {
    "code": 20000,
    "data": [{
            "clinicName": "button1",
            "doctorInfo": [{
                    "id": 1,
                    "rank": "GMT",
                    "name": "name1",
                    "predtime": {
                        "0": [10, 12],
                        "1": [12, 14],
                        "2": [15, 16]
                    }
                },
                {
                    "id": 2,
                    "rank": "M",
                    "name": "name2",
                    "predtime": {
                        "0": [10, 12],
                        "1": [12, 14],
                        "2": [15, 16]
                    }
                },
                {
                    "id": 3,
                    "rank": "NA",
                    "name": "name3",
                    "predtime": {
                        "0": [10, 12],
                        "1": [12, 14],
                        "2": [15, 16]
                    }
                }
            ]
        },
        {
            "clinicName": "button2",
            "doctorInfo": []
        }
    ]
};

var hdLayout = [

    { "x": 0, "y": 0, "w": 2, "h": 2, "i": "", static: true },
    { "x": 2, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 4, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 6, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 8, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 10, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 12, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 14, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 16, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 18, "y": 0, "w": 2, "h": 1, "i": "", static: true },
    { "x": 20, "y": 0, "w": 2, "h": 1, "i": "", static: true },

    { "x": 2, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 4, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 6, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 8, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 10, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 12, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 14, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 16, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 18, "y": 1, "w": 2, "h": 1, "i": "", static: true },
    { "x": 20, "y": 1, "w": 2, "h": 1, "i": "", static: true },
];

var contentLayout = [

    { "x": 0, "y": 0, "w": 2, "h": 3, "i": "9:00", static: false },
    { "x": 0, "y": 3, "w": 2, "h": 3, "i": "10:00", static: false },
    { "x": 0, "y": 6, "w": 2, "h": 3, "i": "11:00", static: false },
    { "x": 0, "y": 9, "w": 2, "h": 3, "i": "12:00", static: false },
    { "x": 0, "y": 12, "w": 2, "h": 3, "i": "13:00", static: false },
    { "x": 0, "y": 15, "w": 2, "h": 3, "i": "14:00", static: false },
    /*{"x":0,"y":18,"w":2,"h":3,"i":"15:00", static: false},
    {"x":0,"y":21,"w":2,"h":3,"i":"16:00", static: false},
    {"x":0,"y":24,"w":2,"h":3,"i":"17:00", static: false},
    {"x":0,"y":27,"w":2,"h":3,"i":"18:00", static: false},
    {"x":0,"y":30,"w":2,"h":3,"i":"19:00", static: false},*/

    { "x": 2, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 2, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 2, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 2, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 2, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 2, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 4, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 4, "y": 3, "w": 2, "h": 6, "i": "再", static: true },
    { "x": 4, "y": 6, "w": 2, "h": 0, "i": "", static: false },
    { "x": 4, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 4, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 4, "y": 15, "w": 2, "h": 3, "i": "", static: false },


    { "x": 6, "y": 0, "w": 2, "h": 3, "i": "【新規】\t010-XXXXXXXXプレミアムハナコ指名ありアイブロウ2回 1/2カウセ9:20～10:00加野", static: false },
    { "x": 6, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 6, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 6, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 6, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 6, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 8, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 8, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 8, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 8, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 8, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 8, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 10, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 10, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 10, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 10, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 10, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 10, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 12, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 12, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 12, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 12, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 12, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 12, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 14, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 14, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 14, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 14, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 14, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 14, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 16, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 16, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 16, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 16, "y": 9, "w": 2, "h": 5, "i": "再", static: true },
    { "x": 16, "y": 12, "w": 2, "h": 1, "i": "", static: false },
    { "x": 16, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 18, "y": 0, "w": 2, "h": 3, "i": "", static: false },
    { "x": 18, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 18, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 18, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 18, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 18, "y": 15, "w": 2, "h": 3, "i": "", static: false },

    { "x": 20, "y": 0, "w": 2, "h": 2, "i": "", static: false },
    { "x": 20, "y": 2, "w": 2, "h": 1, "i": "再", static: true },
    { "x": 20, "y": 3, "w": 2, "h": 3, "i": "", static: false },
    { "x": 20, "y": 6, "w": 2, "h": 3, "i": "", static: false },
    { "x": 20, "y": 9, "w": 2, "h": 3, "i": "", static: false },
    { "x": 20, "y": 12, "w": 2, "h": 3, "i": "", static: false },
    { "x": 20, "y": 15, "w": 2, "h": 3, "i": "", static: false },
];

export default {
    components: {
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem,
        datetime,
        Datepicker,
        ModalDialog
    },
    data() {
        return {
            //btnList: [表参道, 新宿, 六本木],
            hdlayout: JSON.parse(JSON.stringify(hdLayout)),
            conlayout: JSON.parse(JSON.stringify(contentLayout)),
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
            })
        }
    },
    mounted() {
        console.log('Component mounted.');
        this.index = this.conlayout.length;
    },
    methods: {
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