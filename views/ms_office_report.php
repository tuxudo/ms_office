<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">
    
  <div class="row">
    <?php $widget->view($this, 'ms_mau_channel'); ?>
    <?php $widget->view($this, 'ms_license_type'); ?>
    <?php $widget->view($this, 'ms_how_to_check'); ?>
  </div> <!-- /row -->
    
  <div class="row">
      <?php $widget->view($this, 'ms_word_generation'); ?>
      <?php $widget->view($this, 'ms_powerpoint_generation'); ?>
      <?php $widget->view($this, 'ms_excel_generation'); ?>
  </div> <!-- /row -->
    
  <div class="row">
      <?php $widget->view($this, 'ms_outlook_generation'); ?>
      <?php $widget->view($this, 'ms_onenote_generation'); ?>
      <?php $widget->view($this, 'ms_remote_desktop_mas'); ?>
  </div> <!-- /row -->
    
  <div class="row">
      <?php $widget->view($this, 'ms_word_mas'); ?>
      <?php $widget->view($this, 'ms_powerpoint_mas'); ?>
      <?php $widget->view($this, 'ms_excel_mas'); ?>
  </div> <!-- /row -->
    
  <div class="row">
      <?php $widget->view($this, 'ms_outlook_mas'); ?>
      <?php $widget->view($this, 'ms_onenote_mas'); ?>
      <?php $widget->view($this, 'ms_onedrive_mas'); ?>
  </div> <!-- /row -->
    
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
