<?php
$page_title = 'Lista de productos';
require_once('includes/load.php');
page_require_level(2);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <div class="col-md-12">
            <div class="col-md-2">
              <a href="buscar_carton.php" class="btn btn-primary">Busacar Carton</a>
            </div>
            <div class="pull-right">
              <div class="col-md-2">
                <a href="buscar_carton_imprimir.php" class="btn btn-primary">Imprimir Cartones</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>