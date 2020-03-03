<template>
    <div class="">
        <div class="help-text">
            <p>20歳未満の未成年者で施術を希望される方は、保護者の方と一緒にご来院いただくか、「親権者同意書」が必要となります。「親権者同意書」はこちらからダウンロードしていただき、ご署名頂いたものをご持参ください。また、事前に「問診票」を記入、ご持参いただくと診療がスムーズです。</p>
        </div>
        <div class="card">
            <div class="card-body row">
                <div class="col-3 my-auto pdf-btn">
                    <a href="pdf/スタッフ区分.pdf"  target="_blank"><i class="fas fa-file-pdf" style="font-size:3em; color:rgb(232, 86, 94);"></i></a>
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
                    <a href="pdf/表参道メディカルクリニック問診票.pdf" target="_blank"><i class="fas fa-file-pdf" style="font-size:3em; color:rgb(232, 86, 94);"></i></a>
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
            
        },
        methods:{
            onClickNextBtn:function(){
                this.sendmail();
                this.$emit('changeStage', 5);
            },
            onClickPrevBtn:function(){
                this.$emit('changeStage', 3);
            },
            sendmail(){
                var mail_info = {};
                mail_info['customer_email'] = gUserInfo.data.userinfo.email;
                mail_info['customer_first_name'] = gUserInfo.data.userinfo.first_name;
                mail_info['customer_last_name'] = gUserInfo.data.userinfo.last_name;
                mail_info['content'] = this.contents[this.contentIdex];
                mail_info['clinic_info'] = gOrderInfo.data.clinic_info;                

                axios.post('/v1/client/send_mail', { 'mail_info': mail_info })
                    .then((result) => {
                        toast.fire({
                            icon: "success",
                            title: "メール送信成功"
                        });
                        console.log(result.data);
                    })
                    .catch(() => {
                        console.log('send mail error');
                        // toast.fire({
                        //     icon: "error",
                        //     title: "メールの送信に失敗しました"
                        // });
                    });
            },
            download(pdfname) {              
                axios({
                url: '/v1/client/download?filename='+pdfname,
                method: 'GET',
                responseType: 'blob'}) // important
                        .then((response) => {
                            console.log(response);
                            const url = window.URL.createObjectURL(new Blob([response.data]));
                            const link = document.createElement('a');
                            link.href = url;
                            link.setAttribute('download', 'fileaab.pdf'); //or any other extension
                            document.body.appendChild(link);
                            link.click();
                        })
                        .catch(() => {
                            //console.log('send mail error');
                            // toast.fire({
                            //     icon: "error",
                            //     title: "メールの送信に失敗しました"
                            // });
                        });
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
