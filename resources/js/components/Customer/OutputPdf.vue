<template>
    <div class="">
        <div class="help-text">
            <p>20歳未満の未成年者で施術を希望される方は、保護者の方と一緒にご来院いただくか、「親権者同意書」が必要となります。「親権者同意書」はこちらからダウンロードしていただき、ご署名頂いたものをご持参ください。また、事前に「問診票」を記入、ご持参いただくと診療がスムーズです。</p>
        </div>
        <div class="card">
            <div class="card-body row">
                <div class="col-3 my-auto pdf-btn">
                    <a data-toggle="modal" href="#"><i class="fas fa-file-pdf" style="font-size:3em; color:rgb(232, 86, 94);"></i></a>
                </div>
                <div class="col">
                    <div class="pdf-btn">
                        <div>親権者同意書</div>
                        <div>(PDFファイル)</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body row">
                <div class="col-3 align-self-center pdf-btn">
                    <a data-toggle="modal" href="#"><i class="fas fa-file-pdf" style="font-size:3em; color:rgb(232, 86, 94);"></i></a>
                </div>
                <div class="col">
                    <div class="pdf-btn">
                        <div>問診票</div>
                        <div>(PDFファイル)</div>
                    </div>
                </div>                    
            </div>
        </div>   
        <div>
        <b-form-checkbox v-model="checked" size="lg" style="margin-left: 15px;"> 全て内容理解しました</b-form-checkbox>
        </div>                  
        <div class="confirm-btn">
            <div class="row justify-content-around">
                <div class="col-auto mr-auto">
                    <button @click="onClickPrevBtn" type="button" class="btn btn-secondary" style="background:#9F9F9F;">戻る</button>
                </div>
                <div class="col-auto" >
                    <button  @click="onClickNextBtn" type="button" class="btn btn-primary" style="backgroud:#307DB9;" v-show="checked">次へ</button>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
    export default {
        data() {
            return {
                checked:false,
            }
        },
        mounted() {
            
        },
        methods:{
            onClickNextBtn:function(){
                this.$emit('changeStage', 5);
            },
            onClickPrevBtn:function(){
                this.$emit('changeStage', 3);
            },
        }

    }
</script>

<style lang="scss">
 .help-text{
     font-size: 15px;
 }
 .pdf-btn{
    text-align: center;
    div:first-child{
        font-size: 20px;
        font-weight: bold;
    }
 }
</style>
