<?php
  $page_title = 'Editar Grupo';
  require_once('includes/load.php');
  // Checking What level user has permission to view this page
  
  // Dissabled in 
  //page_require_level(1);
?>

<?php 
  if( verb_mode() ) {
    print( "GET params:\n" );
    print_r( $_GET );
    print( "POST params:\n" );
    print_r( $_POST );
  }
?>

<?php
  /* This is the first consult (using GET)
   * Allows to initialize the Userform with the current
   * data of the given group ID
   *
   * NOTE: I added the condition isset() to check if the ID
   * is present in the request.
   */
  if ( isset( $_GET['id'] ) ) {
    $e_group = find_by_id('user_groups',(int)$_GET['id']);
    if(!$e_group){
      $session->msg("d","Missing Group id.");
      redirect('group.php');
    }
  }
?>

<?php
  if(isset($_POST['update'])) 
  {
    if( verb_mode() ) print( "Processing POST\n" );

    $req_fields = array('group-name','group-level','group-status');
    //validate_fields($req_fields);
    
    if( validate_fields( $req_fields ) ) {

      if( verb_mode() ) print( "Validating fields GOOD!\n" );

      $name   = remove_junk($db->escape($_POST['group-name']));
      $level  = remove_junk($db->escape($_POST['group-level']));
      $status = remove_junk($db->escape($_POST['group-status']));

      $query  = "UPDATE user_groups SET ";
      $query .= "group_name='{$name}',group_level='{$level}',group_status='{$status}'";
      $query .= " WHERE ID='{$db->escape($e_group['id'])}'";
      $result = $db->query($query);

      if( verb_mode() ) print( "QUERY: ".$query );

      if($result) {
        if ( $db->affected_rows() === 1 ) {
          //sucess
          $session->msg('s',"Grupo se ha actualizado! ");
        }
        else {
          $session->msg('i',"No se cambio informacion");
        }
        if( verb_mode() ) print( "\nSucccess" );
        
        redirect('edit_group.php?id='.(int)$e_group['id'], false);
      }
      else {
        //failed
        $session->msg('d','Lamentablemente no se ha actualizado el grupo!');
        
        if( verb_mode() ) print( "\nFailed. La Base de Datos no proceso la operacion." );
        
        $sql_err_msg = Array( 'warning' => "".$db->get_last_error() );
        print_r( $sql_err_msg );

        redirect('edit_group.php?id='.(int)$e_group['id'], false);
      }
    } 
    else {
      // error in form fields
      $session->msg("d", $errors);
      if( verb_mode() ) print( "\nFailed. Problema en los campos del formulario." );
      redirect('edit_group.php?id='.(int)$e_group['id'], false);
    }
  }
  exit();
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
       <h3>Editar Grupo</h3>
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id'];?>" class="clearfix">
        <div class="form-group">
              <label for="name" class="control-label">Nombre del grupo</label>
              <input type="name" class="form-control" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>">
        </div>
        <div class="form-group">
              <label for="level" class="control-label">Nivel del grupo</label>
              <input type="number" class="form-control" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
        </div>
        <div class="form-group">
          <label for="status">Estado</label>
              <select class="form-control" name="group-status">
                <option <?php if($e_group['group_status'] === '1') echo 'selected="selected"';?> value="1"> Activo </option>
                <option <?php if($e_group['group_status'] === '0') echo 'selected="selected"';?> value="0">Inactivo</option>
              </select>
        </div>
        <div class="form-group clearfix">
                <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
