<?php
$page_title = 'Carton';
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
    $p1 = remove_junk($db->escape($_POST['p1']));
    $p2 = remove_junk($db->escape($_POST['p2']));
    $p3 = remove_junk($db->escape($_POST['p3']));
    $p4 = remove_junk($db->escape($_POST['p4']));
    $p5 = remove_junk($db->escape($_POST['p5']));
    $p6 = remove_junk($db->escape($_POST['p6']));
    $p7 = remove_junk($db->escape($_POST['p7']));
    $p8 = remove_junk($db->escape($_POST['p8']));
    $p9 = remove_junk($db->escape($_POST['p9']));
    $p10 = remove_junk($db->escape($_POST['p10']));
    $p11 = remove_junk($db->escape($_POST['p11']));
    $p12 = remove_junk($db->escape($_POST['p12']));
    $p13 = remove_junk($db->escape($_POST['p13']));
    $p14 = remove_junk($db->escape($_POST['p14']));
    $p15 = remove_junk($db->escape($_POST['p15']));
    $p16 = remove_junk($db->escape($_POST['p16']));
    $p17 = remove_junk($db->escape($_POST['p17']));
    $p18 = remove_junk($db->escape($_POST['p18']));
    $p19 = remove_junk($db->escape($_POST['p19']));
    $p20 = remove_junk($db->escape($_POST['p20']));
    $p21 = remove_junk($db->escape($_POST['p21']));
    $p22 = remove_junk($db->escape($_POST['p22']));
    $p23 = remove_junk($db->escape($_POST['p23']));
    $p24 = remove_junk($db->escape($_POST['p24']));
    $p25 = remove_junk($db->escape($_POST['p25']));
    $p26 = remove_junk($db->escape($_POST['p26']));
    $p27 = remove_junk($db->escape($_POST['p27']));
    $p28 = remove_junk($db->escape($_POST['p28']));
    $p29 = remove_junk($db->escape($_POST['p29']));
    $p30 = remove_junk($db->escape($_POST['p30']));
    $p31 = remove_junk($db->escape($_POST['p31']));
    $p32 = remove_junk($db->escape($_POST['p32']));
    $p33 = remove_junk($db->escape($_POST['p33']));
    $p34 = remove_junk($db->escape($_POST['p34']));
    $p35 = remove_junk($db->escape($_POST['p35']));
    $p36 = remove_junk($db->escape($_POST['p36']));
    $p37 = remove_junk($db->escape($_POST['p37']));
    $p38 = remove_junk($db->escape($_POST['p38']));
    $p39 = remove_junk($db->escape($_POST['p39']));
    $p40 = remove_junk($db->escape($_POST['p40']));
    $p41 = remove_junk($db->escape($_POST['p41']));
    $p42 = remove_junk($db->escape($_POST['p42']));
    $p43 = remove_junk($db->escape($_POST['p43']));
    $p44 = remove_junk($db->escape($_POST['p44']));
    $p45 = remove_junk($db->escape($_POST['p45']));
    $p46 = remove_junk($db->escape($_POST['p46']));
    $p47 = remove_junk($db->escape($_POST['p47']));
    $p48 = remove_junk($db->escape($_POST['p48']));
    $p49 = remove_junk($db->escape($_POST['p49']));
    $p50 = remove_junk($db->escape($_POST['p50']));
    $p51 = remove_junk($db->escape($_POST['p51']));
    $p52 = remove_junk($db->escape($_POST['p52']));
    $p53 = remove_junk($db->escape($_POST['p53']));
    $p54 = remove_junk($db->escape($_POST['p54']));
    $p55 = remove_junk($db->escape($_POST['p55']));
    $p56 = remove_junk($db->escape($_POST['p56']));
    $p57 = remove_junk($db->escape($_POST['p57']));
    $p58 = remove_junk($db->escape($_POST['p58']));
    $p59 = remove_junk($db->escape($_POST['p59']));
    $p60 = remove_junk($db->escape($_POST['p60']));





    $cartones = buscar_carton($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14, $p15, $p16, $p17, $p18, $p19, $p20, $p20, $p21, $p22, $p23, $p24, $p25, $p26, $p27, $p28, $p29, $p30, $p31, $p32, $p33, $p34, $p35, $p36, $p37, $p38, $p39, $p40, $p41, $p42, $p43, $p44, $p45, $p46, $p47, $p48, $p49, $p50, $p51, $p52, $p53, $p54, $p55, $p56, $p57, $p58, $p59, $p60);
  else :
    $session->msg("d", $errors);
    redirect('sales_report.php', false);
  endif;
} else {
  $session->msg("d", "Select dates");
  redirect('sales_report.php', false);
}
?>
<?php include_once('layouts/header.php'); ?>


<?php foreach ($cartones as $carton) : ?>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="panel-body">
          <table class="table table-bordered">
            <tbody>
              <thead>
                <tr>
                  <th>Modulo</th>
                  <th>carton</th>
                  <th>item</th>
                </tr>
              </thead>
              <td class="text-center"><?php echo remove_junk($carton['modulo']); ?></td>
              <td class="text-center"><?php echo remove_junk(str_pad($carton['carton'],  3, "0", STR_PAD_LEFT)); ?>
              <td class="text-center"><?php echo remove_junk($carton['id']); ?></td>
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
    </div>
  </div>
<?php endforeach; ?>

<?php include_once('layouts/footer.php'); ?>