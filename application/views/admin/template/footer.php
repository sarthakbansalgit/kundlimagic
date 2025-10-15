<style>
    .footer {
        position: inherit !important;
        width: 100% !important;
    }
</style>

<footer class="footer text-center " style="padding-bottom:20px;">

    <hr style="border:1px solid black;">
    <p style="text-align:center;">© 2021. All rights reserved.</p>

    <p style="color:rgba(197, 190, 190, 0.61); text-align:center;">
        POWERED BY <a target="_blank" href="https://www.eniacoder.com/" style="text-decoration:none; color:rgba(197, 190, 190, 0.61);">Eniacoder</a></p>
</footer>

</div>
<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<!--<script src="<?php echo base_url(); ?>admin/assets/js/jquery.min.js"></script>-->
<script src="<?php echo base_url(); ?>admin/assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/detect.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/fastclick.js"></script>

<script src="<?php echo base_url(); ?>admin/assets/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/waves.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/wow.min.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/jquery.nicescroll.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>

<script src="<?php echo base_url(); ?>admin/assets/plugins/peity/jquery.peity.min.js"></script>

<!-- jQuery  -->
<script src="<?php echo base_url(); ?>admin/assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/plugins/counterup/jquery.counterup.min.js"></script>



<!--<script src="<?php echo base_url(); ?>admin/assets/js/jquery.dataTables.min.js"></script>-->



<script src="<?php echo base_url(); ?>admin/assets/plugins/raphael/raphael-min.js"></script>

<script src="<?php echo base_url(); ?>admin/assets/plugins/jquery-knob/jquery.knob.js"></script>


<script src="<?php echo base_url(); ?>admin/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/jquery.core.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/jquery.app.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>admin/assets/js/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>admin/assets/js/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/validation.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/js/intlTelInput.min.js"></script>
<script src="<?php echo base_url(); ?>admin/assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=7thk6ie2owxo0x8ts56eokzxtxphpsgtr0de1mkyxqgqzq7r"></script>
<script src="<?php echo base_url(); ?>admin/assets/plugins/summernote/summernote.min.js"></script>


<script>
    jQuery(document).ready(function() {

        $('.summernote').summernote({
            height: 350, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false // set focus to editable area after initializing summernote
        });

        $('.inline-editor').summernote({
            airMode: true
        });

    });
</script>
<script>
    tinymce.init({
        selector: '.setEditor'
    });
</script>
<script>
    $(document).ready(function() {
        $(".Internationphonecode").intlTelInput({
            utilsScript: "<?php echo base_url(); ?>admin/assets/js/utils.js"
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.counter').counterUp({
            delay: 100,
            time: 1200
        });

        $(".knob").knob();

    });
</script>
<script>
    $('#sidebarToggleTop').click(function() {
        $('#accordionSidebar').toggle();
    });
</script>



</body>

</html>