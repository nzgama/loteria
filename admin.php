<?php
$page_title = 'Admin página de inicio';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php
$c_categorie     = count_by_id('cartones');
$c_product       = count_by_id('logs');
$c_sale          = count_by_id('categories');
$c_user          = count_by_id('users');
$logs = logs();

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <div class="panel panel-box clearfix">
      <div class="panel-icon pull-left bg-green">
        <i class="glyphicon glyphicon-user"></i>
      </div>
      <div class="panel-value pull-right">
        <h2 class="margin-top"> <?php echo $c_user['total']; ?> </h2>
        <p class="text-muted">Usuarios</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="panel panel-box clearfix">
      <div class="panel-icon pull-left bg-red">
        <i class="glyphicon glyphicon-list"></i>
      </div>
      <div class="panel-value pull-right">
        <h2 class="margin-top"> <?php echo $c_categorie['total'] / 3; ?> </h2>
        <p class="text-muted">Cartones</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="panel panel-box clearfix">
      <div class="panel-icon pull-left bg-blue">
        <i class="glyphicon glyphicon-file"></i>
      </div>
      <div class="panel-value pull-right">
        <h2 class="margin-top"> <?php echo $c_product['total']; ?> </h2>
        <p class="text-muted">PDF Generados</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="panel panel-box clearfix">
      <div class="panel-icon pull-left bg-yellow">
        <i class="glyphicon glyphicon-briefcase"></i>
      </div>
      <div class="panel-value pull-right">
        <h2 class="margin-top"> <?php echo $c_sale['total']; ?></h2>
        <p class="text-muted">Vendedores</p>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-file"></span>
          <span>ÚLTIMOS PDF GENERADOS</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th>Generado</th>
              <th>Modulo</th>
              <th>Serie</th>
              <th>Cartones</th>
              <th>Fecha</th>
              <th>Vendedor</th>
              <th>Imagen</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($logs as  $log) : ?>
              <tr>
                <td class="text-center"><?php echo remove_junk($log['date']); ?></td>
                <td class="text-center"><?php echo remove_junk($log['modulo']); ?></td>
                <td class="text-center"><?php echo remove_junk($log['n_desde']); ?> a <?php echo remove_junk($log['n_hasta']); ?></td>
                <td class="text-center"><?php echo remove_junk($log['n_desde'] * 2); ?> a <?php echo remove_junk($log['n_hasta'] * 2 + 1); ?></td>
                <td class="text-center"><?php echo remove_junk($log['date2']); ?></td>
                <td class="text-center"><?php echo remove_junk($log['vendedor']); ?></td>
                <td class="text-center"><?php echo remove_junk($log['img']); ?></td>
              </tr>
            <?php endforeach; ?>
            <div class="panel-body">
              <table class="table table-bordered">
                <form class="clearfix" method="post" target="_blank" action="../loteria/historial.php">
                  <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary">Hisorial Completo</button>
                  </div>
            </div>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>




<?php include_once('layouts/footer.php'); ?>