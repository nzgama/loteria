<?php
$page_title = 'Reporte de ventas';
require_once('includes/load.php');
page_require_level(2);
$vendedores = vendedores();
$imagenes = imagenes();
$modulos = modulos();

$logs = logs();


echo date('Y-m-') . '01';
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="panel panel-default">

  <div class="panel-heading clearfix">
    <span class="glyphicon glyphicon-file"></span>

    <label class="form-label">Generar PDF de cartones</label>

    <div class="pull-right">
    </div>
  </div>
  <div class="panel-body">

    <table class="table table-striped table-bordered table-condensed">
      <form class="clearfix" method="post" target="_blank" action="../loteria/libs/pdf/index.php">

        <thead>
          <tr>
            <th class="text-center" style="width: 10vw;">Modulo</th>
            <th class="text-center" style="width: 10vw;">Serie</th>
            <th class="text-center" style="width: 10vw;">Fecha</th>
            <th class="text-center" style="width: 10vw;">Vendedor</th>
            <th class="text-center" style="width: 10vw;">Imagen</th>
          </tr>
        </thead>
        <tbody>
          <table class="table table-bordered" style="width:10vw">
            <tr>
              <td>
                <div class="form-group">
                  <select class="form-control" name="modulo" style="width:15.5vw">
                    <?php foreach ($modulos as $modulo) : ?>
                      <option value="<?php echo $modulo['modulo'] ?>">
                        <?php echo $modulo['modulo'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </td>
              <td> <input type="number" style="width:7.2vw" class="form-control" id="desde" name="desde" placeholder="Desde" required></td>
              <td> <input type="number" style="width:7.3vw" class="form-control" id="hasta" name="hasta" placeholder="Hasta" required></td>
              <td> <input type="text" style="width:15.5vw" class="form-control" name="date" id="date" data-date data-date-format="yyyy-mm-dd" required> </td>
              <td>
                <div class="form-group">
                  <select class="form-control" name="vendedor" style="width:15.5vw">
                    <?php foreach ($vendedores as $vendedor) : ?>
                      <option value="<?php echo $vendedor['name'] ?>">
                        <?php echo $vendedor['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </td>
              <td>
                <div class="form-group">
                  <select class="form-control" name="img" style="width:15.5vw">
                    <?php foreach ($imagenes as $imagen) : ?>
                      <option value="<?php echo $imagen['file_name'] ?>">
                        <?php echo $imagen['file_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </td>
            </tr>
          </table>
        </tbody>
    </table>
    <div class="form-group">
      <button type="submit" name="submit" class="btn btn-primary">Generar</button>
    </div>
  </div>
  <div class="panel-heading clearfix">
  </div>
</div>
<div class="panel panel-default">

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
                <a href="delete_log.php?id=<?php echo (int)$log['id']; ?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <div class="panel-body">
          <table class="table table-bordered">
            <form class="clearfix" method="post" target="_blank" action="../loteria/historial.php">
              <div class="form-group">
              </div>
        </div>
      </tbody>
    </table>
  </div>
</div>

</div>
<?php include_once('layouts/footer.php'); ?>
<script type="text/javascript" src="libs/js/add_sale2.js"></script>