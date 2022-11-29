<footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="http://carmatec.io">Carmatec</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">

    </div>
  </footer>
</div>
<!-- jQuery -->
<script src="{{asset('adminpanel/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('adminpanel/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('adminpanel/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('adminpanel/plugins/chart.js/Chart.min.js')}}"></script>

<!-- InputMask -->
<script src="{{asset('adminpanel/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('adminpanel/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<!-- JQVMap
<script src="{{asset('adminpanel/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('adminpanel/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{asset('adminpanel/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('adminpanel/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('adminpanel/plugins/toastr/toastr.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('adminpanel/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('adminpanel/assets/js/adminlte.js')}}"></script>
<script src="{{asset('adminpanel/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
  	@if(Session::has('message'))
		toastr.info('{{ Session::get("message") }}')
	@endif
	@if(Session::has('error'))
		toastr.error('{{ Session::get("error") }}')
	@endif

});
</script>
<script src="{{asset('adminpanel/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('adminpanel/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script>
  document.addEventListener("DOMContentLoaded", function(){
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });

</script>

<!-- AdminLTE dashboard demo (This is only for demo purposes)
<script src="{{asset('vendor/vendor/vendor/vendor/vendor/adminpanel/assets/js/pages/dashboard.js')}}"></script>

</body>
</html>-->

