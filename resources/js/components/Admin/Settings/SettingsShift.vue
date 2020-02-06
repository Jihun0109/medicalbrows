<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="example">
                        <a data-toggle="modal" href="#myModal" class="btn btn-primary">Launch modal</a>

                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Modal title</h4>    
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="container">

                                </div>
                                <div class="modal-body">
                                    Content for the dialog / modal goes here.
                                    <a data-toggle="modal" class="btn btn-primary">Launch modal</a>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" data-dismiss="modal" class="btn">Close</a>
                                    <a data-toggle="modal" href="#myModal2" class="btn btn-primary">Save changes</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="modal fade" id="myModal2" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">2nd Modal title</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="container">

                                    </div>
                                    <div class="modal-body">
                                    Content for the dialog / modal goes here.
                                    Content for the dialog / modal goes here.
                                    Content for the dialog / modal goes here.
                                    Content for the dialog / modal goes here.
                                    Content for the dialog / modal goes here.
                                    </div>
                                    <div class="modal-footer">
                                        <a href="#" data-dismiss="modal" class="btn">Close</a>
                                        <a href="#" class="btn btn-primary">Save changes</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
</template>

<script>
$('#openBtn').click(function(){
	$('#myModal').modal({show:true});
})
export default {
  
  data() {
    return {
      
    };
  }
  
};
</script>


