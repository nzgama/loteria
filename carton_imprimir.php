<?php
$page_title = 'carton para imprimir';
$results = '';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);
?>
<?php
if (isset($_POST['submit'])) {
  $req_dates = array('start-date', 'end-date');
  validate_fields($req_dates);

  if (empty($errors)) :
    $desde = remove_junk($db->escape($_POST['desde']));
    $hasta = remove_junk($db->escape($_POST['hasta']));


    $cartones = buscar_carton_imprimir($desde, $hasta);

  else :
    $session->msg("d", $errors);
    redirect('sales_report.php', false);
  endif;
} else {
  $session->msg("d", "Select dates");
  redirect('sales_report.php', false);
}
?>

<?php include_once('layouts/header2.php'); ?>
<?php foreach ($cartones as $carton) : ?>
  <div class="conteiner-fluid">

    <div class="col-sm-6">

      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center" style="width: 10%;">Serie : <?php echo remove_junk($carton['serie']); ?></th>
            <th class="text-center" style="width: 10%;">Carton : <?php echo remove_junk($carton['carton']); ?></th>
            <th class="text-center" style="width: 10%;">Item : <?php echo remove_junk($carton['id']); ?></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
          </tr>
        </thead>
        <tbody>


          <table class="table table-bordered">
            <tr>
              <td class="text-center"> <?php echo remove_junk($carton['p1']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p2']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p3']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p4']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p5']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p6']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p7']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p8']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p9']); ?></td>
            <tr>
              <td class="text-center"> <?php echo remove_junk($carton['p10']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p11']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p12']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p13']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p14']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p15']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p16']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p17']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p18']); ?></td>
            <tr>
              <td class="text-center"> <?php echo remove_junk($carton['p19']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p20']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p21']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p22']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p23']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p24']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p25']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p26']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p27']); ?></td>
            </tr>
          </table>
        </tbody>
      </table>
    </div>
    <div class="col-sm-6">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center" style="width: 10%;">Serie : <?php echo remove_junk($carton['serie']); ?></th>
            <th class="text-center" style="width: 10%;">Carton : <?php echo remove_junk($carton['carton']); ?></th>
            <th class="text-center" style="width: 10%;">Item : <?php echo remove_junk($carton['id']); ?></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
          </tr>
        </thead>
        <tbody>


          <table class="table table-bordered">
            <tr>
              <td class="text-center"> <?php echo remove_junk($carton['p1']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p2']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p3']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p4']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p5']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p6']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p7']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p8']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p9']); ?></td>
            <tr>
              <td class="text-center"> <?php echo remove_junk($carton['p10']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p11']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p12']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p13']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p14']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p15']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p16']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p17']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p18']); ?></td>
            <tr>
              <td class="text-center"> <?php echo remove_junk($carton['p19']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p20']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p21']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p22']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p23']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p24']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p25']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p26']); ?></td>
              <td class="text-center"> <?php echo remove_junk($carton['p27']); ?></td>
            </tr>
          </table>
        </tbody>
      </table>
    </div>
  </div>

<?php endforeach; ?>