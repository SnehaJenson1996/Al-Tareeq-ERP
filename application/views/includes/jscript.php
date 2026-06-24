    <!-- jQuery -->
    <script src="<?php echo base_url()."vendors/jquery/dist/jquery.min.js";?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url()."vendors/bootstrap/dist/js/bootstrap.bundle.min.js";?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url()."vendors/fastclick/lib/fastclick.js";?>"></script>
    <!-- NProgress -->
    <script src="<?php echo base_url()."vendors/nprogress/nprogress.js";?>"></script>
    <!-- Chart.js -->
    <script src="<?php echo base_url()."vendors/Chart.js/dist/Chart.min.js";?>"></script>
    <!-- gauge.js -->
    <script src="<?php echo base_url()."vendors/gauge.js/dist/gauge.min.js";?>"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo base_url()."vendors/bootstrap-progressbar/bootstrap-progressbar.min.js";?>"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url()."vendors/iCheck/icheck.min.js";?>"></script>
    <!-- Skycons -->
    <script src="<?php echo base_url()."vendors/skycons/skycons.js";?>"></script>
    <!-- Flot -->
    <script src="<?php echo base_url()."vendors/Flot/jquery.flot.js";?>"></script>
    <script src="<?php echo base_url()."vendors/Flot/jquery.flot.pie.js";?>"></script>
    <script src="<?php echo base_url()."vendors/Flot/jquery.flot.time.js";?>"></script>
    <script src="<?php echo base_url()."vendors/Flot/jquery.flot.stack.js";?>"></script>
    <script src="<?php echo base_url()."vendors/Flot/jquery.flot.resize.js";?>"></script>
    <!-- Flot plugins -->
    <script src="<?php echo base_url()."vendors/flot.orderbars/js/jquery.flot.orderBars.js";?>"></script>
    <script src="<?php echo base_url()."vendors/flot-spline/js/jquery.flot.spline.min.js";?>"></script>
    <script src="<?php echo base_url()."vendors/flot.curvedlines/curvedLines.js";?>"></script>
    <!-- DateJS -->
    <script src="<?php echo base_url()."vendors/DateJS/build/date.js";?>"></script>
    <!-- JQVMap -->
    <script src="<?php echo base_url()."vendors/jqvmap/dist/jquery.vmap.js";?>"></script>
    <script src="<?php echo base_url()."vendors/jqvmap/dist/maps/jquery.vmap.world.js";?>"></script>
    <script src="<?php echo base_url()."vendors/jqvmap/examples/js/jquery.vmap.sampledata.js";?>"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo base_url()."vendors/moment/min/moment.min.js";?>"></script>
    <script src="<?php echo base_url()."vendors/bootstrap-daterangepicker/daterangepicker.js";?>"></script>

      <!-- Datatables -->
    <script src=<?php echo base_url()."/vendors/datatables.net/js/jquery.dataTables.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-buttons/js/buttons.flash.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-buttons/js/buttons.html5.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-buttons/js/buttons.print.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/jszip/dist/jszip.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/pdfmake/build/pdfmake.min.js"; ?>></script>
    <script src=<?php echo base_url()."/vendors/pdfmake/build/vfs_fonts.js"; ?>></script>

    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url()."public/build/js/custom.min.js";?>" ></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

function initializeSelect2(selectElement) {
  selectElement.select2({
    placeholder: "Select an item...",
    allowClear:true,
    ajax: {
      url: '<?= base_url("index.php/Item/search_item") ?>',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return { q: params.term };
      },
      processResults: function (data, params) {
        const items = $.map(data, function (item) {
          return {
            id: item.item_id,
            text: item.item_model
          };
        });

        // Always prepend "None" to the list
        items.unshift({ id: '', text: 'None' });

        return { results: items };
      },
      cache: true
    }
  });
}

  $(document).ready(function () {
    initializeSelect2($('.item-select2'));
  });
</script>
    
	
  </body>
</html>