<?php $this->view('partials/head', array(
	"scripts" => array(
		"clients/client_list.js"
	)
)); ?>

<div class="container">
    
  <div class="row">
    <?php $widget->view($this, 'ms_mau_channel'); ?>
    <?php $widget->view($this, 'ms_version'); ?>
  </div> <!-- /row -->
    
  <div class="row">
      <?php $widget->view($this, 'ms_word_generation'); ?>
      <?php $widget->view($this, 'ms_powerpoint_generation'); ?>
      <?php $widget->view($this, 'ms_excel_generation'); ?>
  </div> <!-- /row -->
    
  <div class="row">
      <?php $widget->view($this, 'ms_outlook_generation'); ?>
      <?php $widget->view($this, 'ms_onenote_generation'); ?>
  </div> <!-- /row -->
    
</div>  <!-- /container -->

<script src="<?php echo conf('subdirectory'); ?>assets/js/munkireport.autoupdate.js"></script>

<?php $this->view('partials/foot'); ?>
