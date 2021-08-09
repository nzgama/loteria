<?php
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>
<?php
$log = find_by_id('logs', (int)$_GET['id']);
if (!$log) {
  $session->msg("d", "ID vacío");
  redirect('buscar_carton_imprimir.php');
}
?>
<?php
$delete_id = delete_by_id('logs', (int)$log['id']);
if ($delete_id) {
  $session->msg("s", "Registro eliminado");
  redirect('buscar_carton_imprimir.php');
} else {
  $session->msg("d", "Eliminación falló");
  redirect('buscar_carton_imprimir.php');
}
?>
