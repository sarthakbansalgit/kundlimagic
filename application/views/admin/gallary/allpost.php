<link href="<?php echo base_url(); ?>admin/assets/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>admin/assets/css/core.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>admin/assets/js/jquery.dataTables.min.js"></script>
<style>
    td img{
        width:100px;
    }
    .ad{
        width:3rem;
        height:auto;
        padding: .3rem .3rem;
        float:right;
        border:none;
        color:white;
        outline:none;
        background-color:rgb( 239, 69, 84 );
        font-size:20px;
        font-weight:600;
    }
    .ad:hover{
        opacity:.7;
    }
</style>
<div class="all_post">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>All Gallary Images</h3>
            </div>    
            <div class="col-md-6">
                <a href="<?php echo base_url();?>admin/gallary/newpost"><button class="ad">+</button></a>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="card-box table-responsive">
                    <table id="lowinventory" data-order='[[ 0, "desc" ]]'  style="width:100%" class="table table-striped table-bordered table_shop_custom display">
                        <thead>
                            <tr> 
                                <th >ID</th>
                                <th> Image</th>
                                <th>Category</th>
                                
                                
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>    


<div id="deletePurchaseModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <?php echo form_open(base_url('admin/gallary/allpost/deletepost'), array('method'=>'post'));?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
                <h4 class="modal-title">Delete gallary</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" class="deletesliderId" name="deletesliderId"/>
                        <h4><b>Do you really want to Delete this gallary ?</b></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-info" name="deleteslider" value="Delete">
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>


<script>
  $(document).ready(function() {
    $('#lowinventory').DataTable( {
        "ajax": "<?php echo base_url(); ?>admin/gallary/allpost/addinventory_api"
    } );
   

    $(document).on('click','.delete_sliders',function(){

     $('.deletesliderId').val($(this).attr('data-id'));
        $('#deletePurchaseModal').modal('show');

    });

});

  </script>