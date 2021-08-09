<?php
$page_title = 'Historial';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php

$logs = logs_historico();

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-file"></span>
          <span>HISTORIAL DE PDF GENERADOS</span>
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
              <th>Acciones</th>

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
                <td class="text-center">
                  <div class="btn-group">
                    <a href="delete_log_2.php?id=<?php echo (int)$log['id']; ?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>




<?php include_once('layouts/footer.php'); ?>