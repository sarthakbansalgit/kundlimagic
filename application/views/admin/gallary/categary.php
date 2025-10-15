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
<style>
    .new-post {
        width: 100%;
        height: auto;
        padding-top: 2rem;
        padding-bottom: 2rem;

    }

    .new-post .box {
        width: 100%;
        height: auto;
        background-color: white;
        box-shadow: 0 3px 3px -2px rgb(0 0 0 / 40%);
        border: 1px solid #cdcdcd;
        padding-top: 2rem;
        padding-bottom: 2rem;
        padding-left: 1rem;
        padding-right: 1rem;
        margin-bottom: 2rem;
    }

    .new-post input[type="text"],
    input[type="file"],
    select,
    textarea {
        width: 100%;
        height: auto;
        padding-top: .5rem;
        padding-bottom: .5rem;
        padding-left: 1rem;
        border: 1px solid #cdcdcd;
        margin-bottom: 1rem;
    }

    .new-post .sub {
        width: 10rem;
        height: auto;
        padding-top: .6rem;
        padding-bottom: .6rem;
        color: white;
        background-color: rgb(239, 69, 84);
        outline: none;
        border: none;
        transition: .5s;
    }

    .new-post .sub:hover {
        opacity: .7;

    }

    .new-post p {
        margin-top: -.5rem;
        color: #666;
        font-size: 12px;
        font-weight: 300;
        font-style: italic;
    }
</style>

<div class="new-post">
    <div class="container">
        <?php
        if ($this->session->flashdata('success')) {
            echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
        } else if ($this->session->flashdata('error')) {
            echo '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>';
        }
        ?>
        <h3>Add categary</h3>
        <form method="post" action="categary/create" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <label>Categary Name</label>
                        <input type="text" name="name" placeholder="Enter Categary Name" required>

                      
                        <label>Categary Description</label>
                        <textarea name="about" id="textareaContent" placeholder="Enter Description" cols="30" rows="10" required></textarea>
            

                        <button class="sub" name="formSubmit">Create</button>
                    </div>
                </div>
        </form>
    </div>
</div>

<br>
<br>
<br>
<br>
<div class="all_post">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3>All Category</h3>
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
                                <th>Category Name</th>
                                <th>Description</th>
                                
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
        <?php echo form_open(base_url('admin/gallary/categary/deletepost'), array('method'=>'post'));?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
                <h4 class="modal-title">Delete category</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" class="deletesliderId" name="deletesliderId"/>
                        <h4><b>Do you really want to Delete this category ?</b></h4>
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
        "ajax": "<?php echo base_url(); ?>admin/gallary/categary/addinventory_api"
    } );
   

    $(document).on('click','.delete_sliders',function(){

     $('.deletesliderId').val($(this).attr('data-id'));
        $('#deletePurchaseModal').modal('show');

    });

});

  </script>




