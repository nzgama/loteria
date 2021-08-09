<?php
/*
   *
    $("#files").change(function() {
      filename = this.files[0].name
      console.log(filename);
    });
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <div>
      <label for="files" class="btn">Select Image</label>
      <input id="files" style="visibility:hidden;" type="file">
    </div>
    
    * https://stackoverflow.com/questions/5138719/change-default-text-in-input-type-file
    */

$page_title = 'Lista de imagenes';
require_once('includes/load.php');
/* cheching that user has allowed level to perform this action
   * level >= 2: privileged users
   */
page_require_level(3);
?>
<?php $media_files = find_all('media'); ?>
<?php
if (isset($_POST['submit'])) {

  /*
    // --- DEBUG ---
    print_r( $_FILES );
    exit();
    */

  $photo = new Media();

  $photo->upload($_FILES['file_upload']);

  if ($photo->process_media()) {
    $session->msg('s', 'Imagen subida al servidor.');
    redirect('media.php', false);
  } else {
    $session->msg('d', join(': ', $photo->errors));
    redirect('media.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<script type="text/javascript">
  $(document).ready(function(e) {

    $('#search_file').click(function() {
      //alert("click")
      $('#file_upload').click()
    });

  });
</script>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>Lista de imagenes</span>
        <div class="pull-right">
          <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <div class="input-group">

                <!-- Dynamic STYLE:  using jQuery to assign event handler
                       (usando jQuery para asignar el control de eventos ("click")) 
                  -->
                <span class="btn btn-default" id="search_file" style="margin-right:1pt" title="Seleccionar archivo" data-toogle="tooltip">Examinar
                </span>

                <!-- Old STYLE:  "hard-coded", specifying the HTML attribute "onclick"
                       (especificando el atributo HTML "onclick"
                  -->
                <!--<div class="btn btn-success" id="search_file" style="margin-right:1pt" title="Seleccionar archivo" data-toogle="tooltip" onclick="document.getElementById('file_upload').click()">Buscar
                  </div>-->

                <input type='file' name="file_upload" id="file_upload" multiple style="display:none; visibility: hidden">

                <button type="submit" name="submit" id="submit" class="btn btn-success" onclick="file_submit()"> Cargar&nbsp; <span class="glyphicon glyphicon-open"></span></button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th class="text-center">Imagen</th>
              <th class="text-center">Descripción</th>
              <th class="text-center" style="width: 20%;">Tipo</th>
              <th class="text-center" style="width: 10em;">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($media_files as $media_file) : ?>
              <tr class="list-inline">
                <td class="text-center"><?php echo count_id(); ?></td>
                <td class="text-center">
                  <img style="height: 6em; width: 6em;" src="libs/pdf/img/<?php echo $media_file['file_name']; ?>" class="img-thumbnail" />
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_name']; ?>
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_type']; ?>
                </td>
                <td class="text-center">
                  <a href="delete_media.php?id=<?php echo (int) $media_file['id']; ?>" class="btn btn-danger btn-sm" title="Eliminar">
                    <span class="glyphicon glyphicon-remove"></span>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
      </div>
    </div>
  </div>
</div>


<?php include_once('layouts/footer.php'); ?>