<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
  <title><?php if (isset($page_title) && !empty($page_title))
         echo remove_junk($page_title);
          elseif(isset($user) && !empty($user))
         echo ucfirst($user['name']);
          else echo "Sistema simple de inventario";?>
  </title>

  <!-- loading external CSS -->
  <!--
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />-->
  <link rel="stylesheet" href="libs/css/main.css" />

  <!-- cached versions -->
  <link rel="stylesheet" href="libs/css/bootstrap.min.css" />
  <link rel="stylesheet" href="libs/css/datepicker3.min.css" />

  <!-- jquery/ajax -->
  <!-- cached version -->
  <script type="text/javascript" src="libs/js/jquery-3.5.1.js"></script>
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>-->
  
  <script type="text/javascript" src="cache/js/jquery.min.js"></script>
  <script type="text/javascript" src="cache/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="cache/js/bootstrap-datepicker.min.js"></script>

  <!-- loading external JS -->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>-->
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>-->
  <!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>-->
  <!-- jquery/ajax -->
  <script type="text/javascript" src="libs/js/functions.js"></script>
  <script type="text/javascript" src="libs/js/product_item.js"></script>

<!--     NOT SURE HERE !!!
===================================
<style type="text/css">
#result {cursor:pointer;}
</style>
-->
  </head>
  <body>

    <div class="row">
      <div class="col-md-6">
        <?php require_once( 'includes/functions.php' ) ?>
        <?php /*echo display_msg($msg);*/ ?>
        <form method="post" action="ajax/product_item.php" autocomplete="off" id="sug_form">

            <div class="form-group">
              <div class="input-group">
                <span class="input-group-btn">
                  <button id="sug_form_submit" type="submit" class="btn btn-primary">Búsqueda</button>
                </span>
                <!--<input type="text" id="sug_input" class="form-control" name="product_name" placeholder="Buscar por el nombre del producto">-->
                <input type="text" id="sug_input" class="form-control" name="hint" placeholder="Buscar por el nombre del producto">
             </div>

             <!-- NOTE: added style="cursor:pointer: -->
             <div id="result" style="cursor:pointer" class="list-group"></div>
            </div>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
            <strong>
              <span class="glyphicon glyphicon-th"></span>
              <span>Registrar Salida</span>
           </strong>
          </div>
          <div class="panel-body">
            <form method="post" action="add_sale.php">
             <table class="table table-bordered">
               <thead>
                <th> Producto </th>
                <th> Precio </th>
                <th> Cantidad </th>
                <th> Total </th>
                <th> Destino </th>
                <th> Fecha </th>
                <th> Acciones </th>
               </thead>
                 <tbody id="product_info"> </tbody>
             </table>
           </form>
          </div>
        </div>
      </div>

  </body>
</html>
