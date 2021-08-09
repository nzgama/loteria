<?php
$page_title = 'Reporte de ventas';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(3);

echo date('Y-m-') . '01';
//exit;
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="panel panel-default">

  <div class="panel-heading clearfix">
    <span class="glyphicon glyphicon-search"></span>

    <label class="form-label">Buscar carton</label>

    <div class="pull-right">
    </div>
  </div>
  <div class="panel-body">

    <table class="table table-bordered">
      <form class="clearfix" method="post" action="carton.php">
        <thead>
          <tr>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
            <th class="text-center" style="width: 10%;"></th>
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
              <td> <input type="number" class="form-control" id="p1" name="p1"></td>
              <td> <input type="number" class="form-control" id="p2" name="p2"></td>
              <td> <input type="number" class="form-control" id="p3" name="p3"></td>
              <td> <input type="number" class="form-control" id="p4" name="p4"></td>
              <td> <input type="number" class="form-control" id="p5" name="p5"></td>
              <td> <input type="number" class="form-control" id="p6" name="p6"></td>
              <td> <input type="number" class="form-control" id="p7" name="p7"></td>
              <td> <input type="number" class="form-control" id="p8" name="p8"></td>
              <td> <input type="number" class="form-control" id="p9" name="p9"></td>
              <td> <input type="number" class="form-control" id="p10" name="p10"></td>
            <tr>
              <td> <input type="number" class="form-control" id="p11" name="p11"></td>
              <td> <input type="number" class="form-control" id="p12" name="p12"></td>
              <td> <input type="number" class="form-control" id="p13" name="p13"></td>
              <td> <input type="number" class="form-control" id="p14" name="p14"></td>
              <td> <input type="number" class="form-control" id="p15" name="p15"></td>
              <td> <input type="number" class="form-control" id="p16" name="p16"></td>
              <td> <input type="number" class="form-control" id="p17" name="p17"></td>
              <td> <input type="number" class="form-control" id="p18" name="p18"></td>
              <td> <input type="number" class="form-control" id="p19" name="p19"></td>
              <td> <input type="number" class="form-control" id="p20" name="p20"></td>
            <tr>
              <td> <input type="number" class="form-control" id="p21" name="p21"></td>
              <td> <input type="number" class="form-control" id="p22" name="p22"></td>
              <td> <input type="number" class="form-control" id="p23" name="p23"></td>
              <td> <input type="number" class="form-control" id="p24" name="p24"></td>
              <td> <input type="number" class="form-control" id="p25" name="p25"></td>
              <td> <input type="number" class="form-control" id="p26" name="p26"></td>
              <td> <input type="number" class="form-control" id="p27" name="p27"></td>
              <td> <input type="number" class="form-control" id="p28" name="p28"></td>
              <td> <input type="number" class="form-control" id="p29" name="p29"></td>
              <td> <input type="number" class="form-control" id="p30" name="p30"></td>
            <tr>
              <td> <input type="number" class="form-control" id="p31" name="p31"></td>
              <td> <input type="number" class="form-control" id="p32" name="p32"></td>
              <td> <input type="number" class="form-control" id="p33" name="p33"></td>
              <td> <input type="number" class="form-control" id="p34" name="p34"></td>
              <td> <input type="number" class="form-control" id="p35" name="p35"></td>
              <td> <input type="number" class="form-control" id="p36" name="p36"></td>
              <td> <input type="number" class="form-control" id="p37" name="p37"></td>
              <td> <input type="number" class="form-control" id="p38" name="p38"></td>
              <td> <input type="number" class="form-control" id="p39" name="p39"></td>
              <td> <input type="number" class="form-control" id="p40" name="p40"></td>
            <tr>
              <td> <input type="number" class="form-control" id="p41" name="p41"></td>
              <td> <input type="number" class="form-control" id="p42" name="p42"></td>
              <td> <input type="number" class="form-control" id="p43" name="p43"></td>
              <td> <input type="number" class="form-control" id="p44" name="p44"></td>
              <td> <input type="number" class="form-control" id="p45" name="p45"></td>
              <td> <input type="number" class="form-control" id="p46" name="p46"></td>
              <td> <input type="number" class="form-control" id="p47" name="p47"></td>
              <td> <input type="number" class="form-control" id="p48" name="p48"></td>
              <td> <input type="number" class="form-control" id="p49" name="p49"></td>
              <td> <input type="number" class="form-control" id="p50" name="p50"></td>
            <tr>
              <td> <input type="number" class="form-control" id="p51" name="p51"></td>
              <td> <input type="number" class="form-control" id="p52" name="p52"></td>
              <td> <input type="number" class="form-control" id="p53" name="p53"></td>
              <td> <input type="number" class="form-control" id="p54" name="p54"></td>
              <td> <input type="number" class="form-control" id="p55" name="p55"></td>
              <td> <input type="number" class="form-control" id="p56" name="p56"></td>
              <td> <input type="number" class="form-control" id="p57" name="p57"></td>
              <td> <input type="number" class="form-control" id="p58" name="p58"></td>
              <td> <input type="number" class="form-control" id="p59" name="p59"></td>
              <td> <input type="number" class="form-control" id="p60" name="p60"></td>
            </tr>
          </table>
        </tbody>
    </table>
    <div class="form-group">
      <button type="submit" name="submit" class="btn btn-primary">Buscar</button>
    </div>
  </div>
</div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>