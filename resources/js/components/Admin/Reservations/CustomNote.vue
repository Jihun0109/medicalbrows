<template>
    <b-form-textarea
        id="textarea"
        ref="textarea"
        v-model="data"
        :rows="8"
        :max-rows="8"
        class="form-control col-sm-10"
        @input="updateDate()"
        @click="clickArea()"
        @keyup="keyPress()"
        @keydown.enter.exact.prevent
        @keyup.enter="enterPress()"
    ></b-form-textarea>
</template>

<script>
export default {
    props: ["value"],
    data () {
        return {
            data : this.value?value:'経験 : \n妊娠・授乳・不妊治療 : \n通院歴・薬 : \n金アレ・アトピー・ケロイド確認 : \n眉ブリーチ・炎症・傷跡確認 : \n美容サービス・美容整形確認 : \n料金・所要時間 : \nHP : \nキャンセル規約 : ',
            cursor: 0,
        }
    },
    methods: {
        updateDate() {
            //this.cursor = $('#textarea').prop("selectionStart");
            this.$emit("input", this.data);
        },
        clickArea(){            
            this.cursor = $('#textarea').prop("selectionStart");
            console.log("click " + this.cursor);
            this.checkFormat();
        },
        keyPress(){
            this.cursor = $('#textarea').prop("selectionStart");
            console.log("keypress "  + this.cursor);
            this.checkFormat();
        },
        enterPress(){
            console.log("enter pressed");
            this.cursor = $('#textarea').prop("selectionStart");
            let lineNo = (this.data.substring(0, this.cursor).match(/\n/g) || []).length;
            let linesArray = this.data.split('\n');
            console.log("lines total len " + linesArray.length);
            console.log("cur line no " + lineNo);
            if (linesArray.length > lineNo+1)
                this.setLineCursor(lineNo+1);
        },
        checkFormat(){
            let lineNo = (this.data.substring(0, this.cursor).match(/\n/g) || []).length;
            
            console.log("line No: " + lineNo);
            let linesArray = this.data.split('\n');
            console.log("linesArray: ");
            console.log(linesArray);
            //let subString = linesArray.slice(0, lineNo+1).join('\n');
            let soFar = this.data.substring(0, this.cursor);
            console.log(soFar);
            if ((soFar.match(/:/g) || []).length < lineNo+1) {
                console.log("smaller than ...");
                this.setLineCursor(lineNo);
            }
            //this.setCursor(0);
        },
        setLineCursor(line){
            //let lineNo = (this.data.substring(0, this.cursor).match(/\n/g) || []).length;
            let linesArray = this.data.split('\n');
            let subString = linesArray.slice(0, line+1).join('\n');
            this.setCursor(subString.length);
        },
        setCursor(pos){
            $('#textarea').prop("selectionStart",pos);
            $('#textarea').prop("selectionEnd",pos);
        }
    },
    mounted() {
        this.$emit("input", this.data);
        $('#textarea').on("click", function(e){
            this.cursor = $('#textarea').prop("selectionStart");
        });
    }
};
</script>
