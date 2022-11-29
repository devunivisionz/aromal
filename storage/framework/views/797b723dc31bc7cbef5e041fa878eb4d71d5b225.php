<footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="http://carmatec.io">Carmatec</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">

    </div>
  </footer>
</div>
<!-- jQuery -->
<script src="<?php echo e(asset('adminpanel/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo e(asset('adminpanel/plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('adminpanel/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- ChartJS -->
<script src="<?php echo e(asset('adminpanel/plugins/chart.js/Chart.min.js')); ?>"></script>

<!-- InputMask -->
<script src="<?php echo e(asset('adminpanel/plugins/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('adminpanel/plugins/inputmask/min/jquery.inputmask.bundle.min.js')); ?>"></script>
<!-- JQVMap
<script src="<?php echo e(asset('adminpanel/plugins/jqvmap/jquery.vmap.min.js')); ?>"></script>
<script src="<?php echo e(asset('adminpanel/plugins/jqvmap/maps/jquery.vmap.usa.js')); ?>"></script>
<script src="<?php echo e(asset('adminpanel/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')); ?>"></script>
<!-- Summernote -->
<script src="<?php echo e(asset('adminpanel/plugins/summernote/summernote-bs4.min.js')); ?>"></script>
<script src="<?php echo e(asset('adminpanel/plugins/toastr/toastr.min.js')); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo e(asset('adminpanel/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('adminpanel/assets/js/adminlte.js')); ?>"></script>
<script src="<?php echo e(asset('adminpanel/plugins/bs-custom-file-input/bs-custom-file-input.min.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
  	<?php if(Session::has('message')): ?>
		toastr.info('<?php echo e(Session::get("message")); ?>')
	<?php endif; ?>
	<?php if(Session::has('error')): ?>
		toastr.error('<?php echo e(Session::get("error")); ?>')
	<?php endif; ?>

});
</script>
<script src="<?php echo e(asset('adminpanel/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('adminpanel/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script>
  document.addEventListener("DOMContentLoaded", function(){
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });

</script>

<!-- AdminLTE dashboard demo (This is only for demo purposes)
<script src="<?php echo e(asset('vendor/vendor/vendor/vendor/vendor/adminpanel/assets/js/pages/dashboard.js')); ?>"></script>

</body>
</html>-->

<?php /**PATH /var/www/vhosts/db-preprod.com360degree.com/httpdocs/resources/views/common/footer.blade.php ENDPATH**/ ?>