<?php
require_once(LIB_PATH_INC . 'load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table)
{
  global $db;
  if (tableExists($table)) {
    return find_by_sql("SELECT * FROM " . $db->escape($table));
  }
}

/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
  return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
 * Returns the entire row of a table, matching by id.
/*--------------------------------------------------------------*/
function find_by_id($table, $id)
{
  global $db;
  $id = (int)$id;
  if (tableExists($table)) {
    /*
    $sql_result = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
    */
    $sql  = "SELECT * FROM " . $db->escape($table);
    $sql .= " WHERE id=" . $db->escape($id);
    $sql .= " LIMIT 1";
    $sql_result = $db->query($sql);
    if ($result = $db->fetch_assoc($sql_result))
      return $result;
    else
      return NULL;
  } else
    return NULL;
}

/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table, $id)
{
  global $db;
  if (tableExists($table)) {
    $sql  = "DELETE FROM " . $db->escape($table);
    $sql .= " WHERE id=" . $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? TRUE : FALSE;
  }
  return NULL;
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table)
{
  global $db;
  if (tableExists($table)) {
    $sql = "SELECT COUNT(id) AS total FROM " . $db->escape($table);
    $sql_result = $db->query($sql);
    return $db->fetch_assoc($sql_result);
  } else
    return NULL;
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table)
{
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM ' . DB_NAME . ' LIKE "' . $db->escape($table) . '"');
  if ($table_exit) {
    if ($db->num_rows($table_exit) > 0)
      return TRUE;
    else
      return FALSE;
  }
}

/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
function authenticate($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password']) {
      return $user['id'];
    }
  }
  return FALSE;
}
/*--------------------------------------------------------------*/
/* Login with the data provided in $_POST,
/* coming from the login_v2.php form.
/* If you use this method then remove authenticate function.
/*--------------------------------------------------------------*/
function authenticate_v2($username = '', $password = '')
{
  global $db;
  $username = $db->escape($username);
  $password = $db->escape($password);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
  $result = $db->query($sql);
  if ($db->num_rows($result)) {
    $user = $db->fetch_assoc($result);
    $password_request = sha1($password);
    if ($password_request === $user['password']) {
      return $user;
    }
  }
  return FALSE;
}


/*--------------------------------------------------------------*/
/* Find current log in user by session id
/*--------------------------------------------------------------*/
function current_user()
{
  static $current_user;
  global $db;
  if (!$current_user) {
    if (isset($_SESSION['user_id'])) {
      $user_id = intval($_SESSION['user_id']);
      $current_user = find_by_id('users', $user_id);
    }
  }
  return $current_user;
}
/*--------------------------------------------------------------*/
/* Find all user by
/* Joining users table and user gropus table
/*--------------------------------------------------------------*/
function find_all_user()
{
  global $db;
  $results = array();
  $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
  $sql .= "g.group_name ";
  $sql .= "FROM users u ";
  $sql .= "LEFT JOIN user_groups g ";
  $sql .= "ON g.group_level=u.user_level ORDER BY u.name ASC";
  $result = find_by_sql($sql);
  return $result;
}
/*--------------------------------------------------------------*/
/* Function to update the last log in of a user
/*--------------------------------------------------------------*/

function updateLastLogIn($user_id)
{
  global $db;
  $date = make_date();
  $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
  $result = $db->query($sql);
  return ($result && $db->affected_rows() === 1 ? TRUE : FALSE);
}

/*--------------------------------------------------------------*/
/* Find all Group name
/*--------------------------------------------------------------*/
function find_by_groupName($val)
{
  global $db;
  $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
  $result = $db->query($sql);
  return ($db->num_rows($result) === 0 ? TRUE : FALSE);
}
/*--------------------------------------------------------------*/
/* Find group level
/*--------------------------------------------------------------*/
function find_by_groupLevel($level)
{
  global $db;
  //$sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
  $sql = "SELECT * FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
  $result = $db->query($sql);
  //return($db->num_rows($result) === 0 ? TRUE : FALSE);
  return $result->fetch_assoc();
}
/*--------------------------------------------------------------*/
/* Function for checking which user level has access to page
/*--------------------------------------------------------------*/
function page_require_level($required_level)
{
  global $session;
  $current_user = current_user();

  /* caution */
  /* === added by Yoel.- 2019.05.23 === */
  if (!$current_user) {
    redirect('home.php', FALSE);
    return FALSE;
  }

  $login_group = find_by_groupLevel($current_user['user_level']);

  // if user is not logged in
  if (!$session->isUserLoggedIn(TRUE)) {
    $session->msg('d', 'Por favor Iniciar sesión...');
    redirect('index.php', FALSE);
  }
  // if group status is inactive
  elseif ($login_group['group_status'] === '0') {
    $session->msg('d', 'Este nivel de usaurio esta inactivo!');
    redirect('home.php', FALSE);
  }
  // checking if (user level) <= (required level)
  elseif ($current_user['user_level'] <= (int)$required_level) {
    return TRUE;
  } else {
    $session->msg("d", "¡Lo siento! no tienes permiso para ver la página.");
    redirect('home.php', FALSE);
  }
}
/*--------------------------------------------------------------*/
/* Function for Finding all product name
/* JOIN with categorie and media database table
/*--------------------------------------------------------------*/


function join_serie_table()
{
  global $db;
  $sql  = " SELECT *";
  $sql  .= " FROM series p";
  $sql  .= " ORDER BY p.id ASC";
  return find_by_sql($sql);
}

function join_carton_table($carton)
{
  global $db;
  $carton = 3;
  $sql  = " SELECT *";
  $sql  .= " FROM cartones p";
  $sql .= " WHERE p.carton = $carton ";

  $sql  .= " ORDER BY p.id ASC";
  return find_by_sql($sql);
}


function find_series_by_series($desde, $hasta)
{
  global $db;
  $desde  = $desde;
  $hasta  = $hasta;

  $sql  = " SELECT *";
  $sql  .= " FROM series p ";
  $sql .= "JOIN cartones c ON p.carton = c.carton";

  $sql .= " WHERE p.serie BETWEEN $desde AND $hasta";
  $sql  .= " ORDER BY p.id ASC";
  return find_by_sql($sql);
}

function vendedores()
{
  global $db;

  $sql  = " SELECT *";
  $sql  .= " FROM categories c ";
  $sql  .= " ORDER BY c.id ASC";
  return find_by_sql($sql);
}

function imagenes()
{
  global $db;

  $sql  = " SELECT *";
  $sql  .= " FROM media c ";
  $sql  .= " ORDER BY c.id ASC";
  return find_by_sql($sql);
}

function modulos()
{
  global $db;

  $sql  = " SELECT *";
  $sql  .= " FROM modulos c ";
  $sql  .= " ORDER BY c.id ASC";
  return find_by_sql($sql);
}

function buscar_carton($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14, $p15, $p16, $p17, $p18, $p19, $p20, $p21, $p22, $p23, $p24, $p25, $p26, $p27, $p28, $p29, $p30, $p31, $p32, $p33, $p34, $p35, $p36, $p37, $p38, $p39, $p40, $p41, $p42, $p43, $p44, $p45, $p46, $p47, $p48, $p49, $p50, $p51, $p52, $p53, $p54, $p55, $p56, $p57, $p58, $p59, $p60)
{
  global $db;
  if (empty($p1)) {
    $p1  = 0;
  } else {
    $p1  = $p1;
  }
  if (empty($p2)) {
    $p2  = 0;
  } else {
    $p2  = $p2;
  }
  if (empty($p3)) {
    $p3  = 0;
  } else {
    $p3  = $p3;
  }
  if (empty($p4)) {
    $p4  = 0;
  } else {
    $p4  = $p4;
  }
  if (empty($p5)) {
    $p5  = 0;
  } else {
    $p5  = $p5;
  }
  if (empty($p6)) {
    $p6  = 0;
  } else {
    $p6  = $p6;
  }
  if (empty($p7)) {
    $p7  = 0;
  } else {
    $p7  = $p7;
  }
  if (empty($p8)) {
    $p8  = 0;
  } else {
    $p8  = $p8;
  }
  if (empty($p9)) {
    $p9  = 0;
  } else {
    $p9  = $p9;
  }
  if (empty($p10)) {
    $p10  = 0;
  } else {
    $p10  = $p10;
  }
  if (empty($p11)) {
    $p11  = 0;
  } else {
    $p11  = $p11;
  }
  if (empty($p12)) {
    $p12  = 0;
  } else {
    $p12  = $p12;
  }
  if (empty($p13)) {
    $p13  = 0;
  } else {
    $p13  = $p13;
  }
  if (empty($p14)) {
    $p14  = 0;
  } else {
    $p14  = $p14;
  }
  if (empty($p15)) {
    $p15  = 0;
  } else {
    $p15  = $p15;
  }
  if (empty($p16)) {
    $p16  = 0;
  } else {
    $p16  = $p16;
  }
  if (empty($p17)) {
    $p17  = 0;
  } else {
    $p17  = $p17;
  }
  if (empty($p18)) {
    $p18  = 0;
  } else {
    $p18  = $p18;
  }
  if (empty($p19)) {
    $p19  = 0;
  } else {
    $p19  = $p19;
  }
  if (empty($p20)) {
    $p20  = 0;
  } else {
    $p20  = $p20;
  }
  if (empty($p21)) {
    $p21  = 0;
  } else {
    $p21  = $p21;
  }
  if (empty($p22)) {
    $p22  = 0;
  } else {
    $p22  = $p22;
  }
  if (empty($p23)) {
    $p23  = 0;
  } else {
    $p23  = $p23;
  }
  if (empty($p24)) {
    $p24  = 0;
  } else {
    $p24  = $p24;
  }
  if (empty($p25)) {
    $p25  = 0;
  } else {
    $p25  = $p25;
  }
  if (empty($p26)) {
    $p26  = 0;
  } else {
    $p26  = $p26;
  }
  if (empty($p27)) {
    $p27  = 0;
  } else {
    $p27  = $p27;
  }
  if (empty($p28)) {
    $p28  = 0;
  } else {
    $p28  = $p28;
  }
  if (empty($p29)) {
    $p29  = 0;
  } else {
    $p29  = $p29;
  }
  if (empty($p30)) {
    $p30  = 0;
  } else {
    $p30  = $p30;
  }
  if (empty($p31)) {
    $p31  = 0;
  } else {
    $p31  = $p31;
  }
  if (empty($p32)) {
    $p32  = 0;
  } else {
    $p32  = $p32;
  }
  if (empty($p33)) {
    $p33  = 0;
  } else {
    $p33  = $p33;
  }
  if (empty($p34)) {
    $p34  = 0;
  } else {
    $p34  = $p34;
  }
  if (empty($p35)) {
    $p35  = 0;
  } else {
    $p35  = $p35;
  }
  if (empty($p36)) {
    $p36  = 0;
  } else {
    $p36  = $p36;
  }
  if (empty($p37)) {
    $p37  = 0;
  } else {
    $p37  = $p37;
  }
  if (empty($p38)) {
    $p38  = 0;
  } else {
    $p38  = $p38;
  }
  if (empty($p39)) {
    $p39  = 0;
  } else {
    $p39  = $p39;
  }
  if (empty($p40)) {
    $p40  = 0;
  } else {
    $p40  = $p40;
  }
  if (empty($p41)) {
    $p41  = 0;
  } else {
    $p41  = $p41;
  }
  if (empty($p42)) {
    $p42  = 0;
  } else {
    $p42  = $p42;
  }
  if (empty($p43)) {
    $p43  = 0;
  } else {
    $p43  = $p43;
  }
  if (empty($p44)) {
    $p44  = 0;
  } else {
    $p44  = $p44;
  }
  if (empty($p45)) {
    $p45  = 0;
  } else {
    $p45  = $p45;
  }
  if (empty($p46)) {
    $p46  = 0;
  } else {
    $p46  = $p46;
  }
  if (empty($p47)) {
    $p47  = 0;
  } else {
    $p47  = $p47;
  }
  if (empty($p48)) {
    $p48  = 0;
  } else {
    $p48  = $p48;
  }
  if (empty($p49)) {
    $p49  = 0;
  } else {
    $p49  = $p49;
  }
  if (empty($p50)) {
    $p50  = 0;
  } else {
    $p50  = $p50;
  }
  if (empty($p51)) {
    $p51  = 0;
  } else {
    $p51  = $p51;
  }
  if (empty($p52)) {
    $p52  = 0;
  } else {
    $p52  = $p52;
  }
  if (empty($p53)) {
    $p53  = 0;
  } else {
    $p53  = $p53;
  }
  if (empty($p54)) {
    $p54  = 0;
  } else {
    $p54  = $p54;
  }
  if (empty($p55)) {
    $p55  = 0;
  } else {
    $p55  = $p55;
  }
  if (empty($p56)) {
    $p56  = 0;
  } else {
    $p56  = $p56;
  }
  if (empty($p57)) {
    $p57  = 0;
  } else {
    $p57  = $p57;
  }
  if (empty($p58)) {
    $p58  = 0;
  } else {
    $p58  = $p58;
  }
  if (empty($p59)) {
    $p59  = 0;
  } else {
    $p59  = $p59;
  }
  if (empty($p60)) {
    $p60  = 0;
  } else {
    $p60  = $p60;
  }

  $sql  = " SELECT *";
  $sql  .= " FROM cartones p ";
  $sql .= " WHERE (p.p1 = $p1 OR p.p1 = $p2 OR p.p1 = $p3 OR p.p1 = $p4 OR p.p1 = $p5 OR p.p1 = $p6 OR p.p1 = $p7 OR p.p1 = $p8 OR p.p1 = $p9 OR p.p1 = $p10 OR p.p1 = $p11 OR p.p1 = $p12 OR p.p1 = $p13 OR p.p1 = $p14 OR p.p1 = $p15 OR p.p1 = $p16 OR p.p1 = $p17 OR p.p1 = $p18 OR p.p1 = $p19 OR p.p1 = $p20 OR p.p1 = $p21 OR p.p1 = $p22 OR p.p1 = $p23 OR p.p1 = $p24 OR p.p1 = $p25 OR p.p1 = $p26 OR p.p1 = $p27 OR p.p1 = $p28 OR p.p1 = $p29 OR p.p1 = $p30 OR p.p1 = $p31 OR p.p1 = $p32 OR p.p1 = $p33 OR p.p1 = $p34 OR p.p1 = $p35 OR p.p1 = $p36 OR p.p1 = $p37 OR p.p1 = $p38 OR p.p1 = $p39 OR p.p1 = $p40 OR p.p1 = $p41 OR p.p1 = $p42 OR p.p1 = $p43 OR p.p1 = $p44 OR p.p1 = $p45 OR p.p1 = $p46 OR p.p1 = $p47 OR p.p1 = $p48 OR p.p1 = $p49 OR p.p1 = $p50 OR p.p1 = $p51 OR p.p1 = $p52 OR p.p1 = $p53 OR p.p1 = $p54 OR p.p1 = $p55 OR p.p1 = $p56 OR p.p1 = $p57 OR p.p1 = $p58 OR p.p1 = $p59 OR p.p1 = $p60 OR p.p1 IS null)
              AND (p.p2 = $p1 OR p.p2 = $p2 OR p.p2 = $p3 OR p.p2 = $p4 OR p.p2 = $p5 OR p.p2 = $p6 OR p.p2 = $p7 OR p.p2 = $p8 OR p.p2 = $p9 OR p.p2 = $p10 OR p.p2 = $p11 OR p.p2 = $p12 OR p.p2 = $p13 OR p.p2 = $p14 OR p.p2 = $p15 OR p.p2 = $p16 OR p.p2 = $p17 OR p.p2 = $p18 OR p.p2 = $p19 OR p.p2 = $p20 OR p.p2 = $p21 OR p.p2 = $p22 OR p.p2 = $p23 OR p.p2 = $p24 OR p.p2 = $p25 OR p.p2 = $p26 OR p.p2 = $p27 OR p.p2 = $p28 OR p.p2 = $p29 OR p.p2 = $p30 OR p.p2 = $p31 OR p.p2 = $p32 OR p.p2 = $p33 OR p.p2 = $p34 OR p.p2 = $p35 OR p.p2 = $p36 OR p.p2 = $p37 OR p.p2 = $p38 OR p.p2 = $p39 OR p.p2 = $p40 OR p.p2 = $p41 OR p.p2 = $p42 OR p.p2 = $p43 OR p.p2 = $p44 OR p.p2 = $p45 OR p.p2 = $p46 OR p.p2 = $p47 OR p.p2 = $p48 OR p.p2 = $p49 OR p.p2 = $p50 OR p.p2 = $p51 OR p.p2 = $p52 OR p.p2 = $p53 OR p.p2 = $p54 OR p.p2 = $p55 OR p.p2 = $p56 OR p.p2 = $p57 OR p.p2 = $p58 OR p.p2 = $p59 OR p.p2 = $p60 OR p.p2 IS null) 
              AND (p.p3 = $p1 OR p.p3 = $p2 OR p.p3 = $p3 OR p.p3 = $p4 OR p.p3 = $p5 OR p.p3 = $p6 OR p.p3 = $p7 OR p.p3 = $p8 OR p.p3 = $p9 OR p.p3 = $p10 OR p.p3 = $p11 OR p.p3 = $p12 OR p.p3 = $p13 OR p.p3 = $p14 OR p.p3 = $p15 OR p.p3 = $p16 OR p.p3 = $p17 OR p.p3 = $p18 OR p.p3 = $p19 OR p.p3 = $p20 OR p.p3 = $p21 OR p.p3 = $p22 OR p.p3 = $p23 OR p.p3 = $p24 OR p.p3 = $p25 OR p.p3 = $p26 OR p.p3 = $p27 OR p.p3 = $p28 OR p.p3 = $p29 OR p.p3 = $p30 OR p.p3 = $p31 OR p.p3 = $p32 OR p.p3 = $p33 OR p.p3 = $p34 OR p.p3 = $p35 OR p.p3 = $p36 OR p.p3 = $p37 OR p.p3 = $p38 OR p.p3 = $p39 OR p.p3 = $p40 OR p.p3 = $p41 OR p.p3 = $p42 OR p.p3 = $p43 OR p.p3 = $p44 OR p.p3 = $p45 OR p.p3 = $p46 OR p.p3 = $p47 OR p.p3 = $p48 OR p.p3 = $p49 OR p.p3 = $p50 OR p.p3 = $p51 OR p.p3 = $p52 OR p.p3 = $p53 OR p.p3 = $p54 OR p.p3 = $p55 OR p.p3 = $p56 OR p.p3 = $p57 OR p.p3 = $p58 OR p.p3 = $p59 OR p.p3 = $p60 OR p.p3 IS null) 
              AND (p.p4 = $p1 OR p.p4 = $p2 OR p.p4 = $p3 OR p.p4 = $p4 OR p.p4 = $p5 OR p.p4 = $p6 OR p.p4 = $p7 OR p.p4 = $p8 OR p.p4 = $p9 OR p.p4 = $p10 OR p.p4 = $p11 OR p.p4 = $p12 OR p.p4 = $p13 OR p.p4 = $p14 OR p.p4 = $p15 OR p.p4 = $p16 OR p.p4 = $p17 OR p.p4 = $p18 OR p.p4 = $p19 OR p.p4 = $p20 OR p.p4 = $p21 OR p.p4 = $p22 OR p.p4 = $p23 OR p.p4 = $p24 OR p.p4 = $p25 OR p.p4 = $p26 OR p.p4 = $p27 OR p.p4 = $p28 OR p.p4 = $p29 OR p.p4 = $p30 OR p.p4 = $p31 OR p.p4 = $p32 OR p.p4 = $p33 OR p.p4 = $p34 OR p.p4 = $p35 OR p.p4 = $p36 OR p.p4 = $p37 OR p.p4 = $p38 OR p.p4 = $p39 OR p.p4 = $p40 OR p.p4 = $p41 OR p.p4 = $p42 OR p.p4 = $p43 OR p.p4 = $p44 OR p.p4 = $p45 OR p.p4 = $p46 OR p.p4 = $p47 OR p.p4 = $p48 OR p.p4 = $p49 OR p.p4 = $p50 OR p.p4 = $p51 OR p.p4 = $p52 OR p.p4 = $p53 OR p.p4 = $p54 OR p.p4 = $p55 OR p.p4 = $p56 OR p.p4 = $p57 OR p.p4 = $p58 OR p.p4 = $p59 OR p.p4 = $p60 OR p.p4 IS null) 
              AND (p.p5 = $p1 OR p.p5 = $p2 OR p.p5 = $p3 OR p.p5 = $p4 OR p.p5 = $p5 OR p.p5 = $p6 OR p.p5 = $p7 OR p.p5 = $p8 OR p.p5 = $p9 OR p.p5 = $p10 OR p.p5 = $p11 OR p.p5 = $p12 OR p.p5 = $p13 OR p.p5 = $p14 OR p.p5 = $p15 OR p.p5 = $p16 OR p.p5 = $p17 OR p.p5 = $p18 OR p.p5 = $p19 OR p.p5 = $p20 OR p.p5 = $p21 OR p.p5 = $p22 OR p.p5 = $p23 OR p.p5 = $p24 OR p.p5 = $p25 OR p.p5 = $p26 OR p.p5 = $p27 OR p.p5 = $p28 OR p.p5 = $p29 OR p.p5 = $p30 OR p.p5 = $p31 OR p.p5 = $p32 OR p.p5 = $p33 OR p.p5 = $p34 OR p.p5 = $p35 OR p.p5 = $p36 OR p.p5 = $p37 OR p.p5 = $p38 OR p.p5 = $p39 OR p.p5 = $p40 OR p.p5 = $p41 OR p.p5 = $p42 OR p.p5 = $p43 OR p.p5 = $p44 OR p.p5 = $p45 OR p.p5 = $p46 OR p.p5 = $p47 OR p.p5 = $p48 OR p.p5 = $p49 OR p.p5 = $p50 OR p.p5 = $p51 OR p.p5 = $p52 OR p.p5 = $p53 OR p.p5 = $p54 OR p.p5 = $p55 OR p.p5 = $p56 OR p.p5 = $p57 OR p.p5 = $p58 OR p.p5 = $p59 OR p.p5 = $p60 OR p.p5 IS null) 
              AND (p.p6 = $p1 OR p.p6 = $p2 OR p.p6 = $p3 OR p.p6 = $p4 OR p.p6 = $p5 OR p.p6 = $p6 OR p.p6 = $p7 OR p.p6 = $p8 OR p.p6 = $p9 OR p.p6 = $p10 OR p.p6 = $p11 OR p.p6 = $p12 OR p.p6 = $p13 OR p.p6 = $p14 OR p.p6 = $p15 OR p.p6 = $p16 OR p.p6 = $p17 OR p.p6 = $p18 OR p.p6 = $p19 OR p.p6 = $p20 OR p.p6 = $p21 OR p.p6 = $p22 OR p.p6 = $p23 OR p.p6 = $p24 OR p.p6 = $p25 OR p.p6 = $p26 OR p.p6 = $p27 OR p.p6 = $p28 OR p.p6 = $p29 OR p.p6 = $p30 OR p.p6 = $p31 OR p.p6 = $p32 OR p.p6 = $p33 OR p.p6 = $p34 OR p.p6 = $p35 OR p.p6 = $p36 OR p.p6 = $p37 OR p.p6 = $p38 OR p.p6 = $p39 OR p.p6 = $p40 OR p.p6 = $p41 OR p.p6 = $p42 OR p.p6 = $p43 OR p.p6 = $p44 OR p.p6 = $p45 OR p.p6 = $p46 OR p.p6 = $p47 OR p.p6 = $p48 OR p.p6 = $p49 OR p.p6 = $p50 OR p.p6 = $p51 OR p.p6 = $p52 OR p.p6 = $p53 OR p.p6 = $p54 OR p.p6 = $p55 OR p.p6 = $p56 OR p.p6 = $p57 OR p.p6 = $p58 OR p.p6 = $p59 OR p.p6 = $p60 OR p.p6 IS null) 
              AND (p.p7 = $p1 OR p.p7 = $p2 OR p.p7 = $p3 OR p.p7 = $p4 OR p.p7 = $p5 OR p.p7 = $p6 OR p.p7 = $p7 OR p.p7 = $p8 OR p.p7 = $p9 OR p.p7 = $p10 OR p.p7 = $p11 OR p.p7 = $p12 OR p.p7 = $p13 OR p.p7 = $p14 OR p.p7 = $p15 OR p.p7 = $p16 OR p.p7 = $p17 OR p.p7 = $p18 OR p.p7 = $p19 OR p.p7 = $p20 OR p.p7 = $p21 OR p.p7 = $p22 OR p.p7 = $p23 OR p.p7 = $p24 OR p.p7 = $p25 OR p.p7 = $p26 OR p.p7 = $p27 OR p.p7 = $p28 OR p.p7 = $p29 OR p.p7 = $p30 OR p.p7 = $p31 OR p.p7 = $p32 OR p.p7 = $p33 OR p.p7 = $p34 OR p.p7 = $p35 OR p.p7 = $p36 OR p.p7 = $p37 OR p.p7 = $p38 OR p.p7 = $p39 OR p.p7 = $p40 OR p.p7 = $p41 OR p.p7 = $p42 OR p.p7 = $p43 OR p.p7 = $p44 OR p.p7 = $p45 OR p.p7 = $p46 OR p.p7 = $p47 OR p.p7 = $p48 OR p.p7 = $p49 OR p.p7 = $p50 OR p.p7 = $p51 OR p.p7 = $p52 OR p.p7 = $p53 OR p.p7 = $p54 OR p.p7 = $p55 OR p.p7 = $p56 OR p.p7 = $p57 OR p.p7 = $p58 OR p.p7 = $p59 OR p.p7 = $p60 OR p.p7 IS null) 
              AND (p.p8 = $p1 OR p.p8 = $p2 OR p.p8 = $p3 OR p.p8 = $p4 OR p.p8 = $p5 OR p.p8 = $p6 OR p.p8 = $p7 OR p.p8 = $p8 OR p.p8 = $p9 OR p.p8 = $p10 OR p.p8 = $p11 OR p.p8 = $p12 OR p.p8 = $p13 OR p.p8 = $p14 OR p.p8 = $p15 OR p.p8 = $p16 OR p.p8 = $p17 OR p.p8 = $p18 OR p.p8 = $p19 OR p.p8 = $p20 OR p.p8 = $p21 OR p.p8 = $p22 OR p.p8 = $p23 OR p.p8 = $p24 OR p.p8 = $p25 OR p.p8 = $p26 OR p.p8 = $p27 OR p.p8 = $p28 OR p.p8 = $p29 OR p.p8 = $p30 OR p.p8 = $p31 OR p.p8 = $p32 OR p.p8 = $p33 OR p.p8 = $p34 OR p.p8 = $p35 OR p.p8 = $p36 OR p.p8 = $p37 OR p.p8 = $p38 OR p.p8 = $p39 OR p.p8 = $p40 OR p.p8 = $p41 OR p.p8 = $p42 OR p.p8 = $p43 OR p.p8 = $p44 OR p.p8 = $p45 OR p.p8 = $p46 OR p.p8 = $p47 OR p.p8 = $p48 OR p.p8 = $p49 OR p.p8 = $p50 OR p.p8 = $p51 OR p.p8 = $p52 OR p.p8 = $p53 OR p.p8 = $p54 OR p.p8 = $p55 OR p.p8 = $p56 OR p.p8 = $p57 OR p.p8 = $p58 OR p.p8 = $p59 OR p.p8 = $p60 OR p.p8 IS null) 
              AND (p.p9 = $p1 OR p.p9 = $p2 OR p.p9 = $p3 OR p.p9 = $p4 OR p.p9 = $p5 OR p.p9 = $p6 OR p.p9 = $p7 OR p.p9 = $p8 OR p.p9 = $p9 OR p.p9 = $p10 OR p.p9 = $p11 OR p.p9 = $p12 OR p.p9 = $p13 OR p.p9 = $p14 OR p.p9 = $p15 OR p.p9 = $p16 OR p.p9 = $p17 OR p.p9 = $p18 OR p.p9 = $p19 OR p.p9 = $p20 OR p.p9 = $p21 OR p.p9 = $p22 OR p.p9 = $p23 OR p.p9 = $p24 OR p.p9 = $p25 OR p.p9 = $p26 OR p.p9 = $p27 OR p.p9 = $p28 OR p.p9 = $p29 OR p.p9 = $p30 OR p.p9 = $p31 OR p.p9 = $p32 OR p.p9 = $p33 OR p.p9 = $p34 OR p.p9 = $p35 OR p.p9 = $p36 OR p.p9 = $p37 OR p.p9 = $p38 OR p.p9 = $p39 OR p.p9 = $p40 OR p.p9 = $p41 OR p.p9 = $p42 OR p.p9 = $p43 OR p.p9 = $p44 OR p.p9 = $p45 OR p.p9 = $p46 OR p.p9 = $p47 OR p.p9 = $p48 OR p.p9 = $p49 OR p.p9 = $p50 OR p.p9 = $p51 OR p.p9 = $p52 OR p.p9 = $p53 OR p.p9 = $p54 OR p.p9 = $p55 OR p.p9 = $p56 OR p.p9 = $p57 OR p.p9 = $p58 OR p.p9 = $p59 OR p.p9 = $p60 OR p.p9 IS null) 
              AND (p.p10 = $p1 OR p.p10 = $p2 OR p.p10 = $p3 OR p.p10 = $p4 OR p.p10 = $p5 OR p.p10 = $p6 OR p.p10 = $p7 OR p.p10 = $p8 OR p.p10 = $p9 OR p.p10 = $p10 OR p.p10 = $p11 OR p.p10 = $p12 OR p.p10 = $p13 OR p.p10 = $p14 OR p.p10 = $p15 OR p.p10 = $p16 OR p.p10 = $p17 OR p.p10 = $p18 OR p.p10 = $p19 OR p.p10 = $p20 OR p.p10 = $p21 OR p.p10 = $p22 OR p.p10 = $p23 OR p.p10 = $p24 OR p.p10 = $p25 OR p.p10 = $p26 OR p.p10 = $p27 OR p.p10 = $p28 OR p.p10 = $p29 OR p.p10 = $p30 OR p.p10 = $p31 OR p.p10 = $p32 OR p.p10 = $p33 OR p.p10 = $p34 OR p.p10 = $p35 OR p.p10 = $p36 OR p.p10 = $p37 OR p.p10 = $p38 OR p.p10 = $p39 OR p.p10 = $p40 OR p.p10 = $p41 OR p.p10 = $p42 OR p.p10 = $p43 OR p.p10 = $p44 OR p.p10 = $p45 OR p.p10 = $p46 OR p.p10 = $p47 OR p.p10 = $p48 OR p.p10 = $p49 OR p.p10 = $p50 OR p.p10 = $p51 OR p.p10 = $p52 OR p.p10 = $p53 OR p.p10 = $p54 OR p.p10 = $p55 OR p.p10 = $p56 OR p.p10 = $p57 OR p.p10 = $p58 OR p.p10 = $p59 OR p.p10 = $p60 OR p.p10 IS null) 
              AND (p.p11 = $p1 OR p.p11 = $p2 OR p.p11 = $p3 OR p.p11 = $p4 OR p.p11 = $p5 OR p.p11 = $p6 OR p.p11 = $p7 OR p.p11 = $p8 OR p.p11 = $p9 OR p.p11 = $p10 OR p.p11 = $p11 OR p.p11 = $p12 OR p.p11 = $p13 OR p.p11 = $p14 OR p.p11 = $p15 OR p.p11 = $p16 OR p.p11 = $p17 OR p.p11 = $p18 OR p.p11 = $p19 OR p.p11 = $p20 OR p.p11 = $p21 OR p.p11 = $p22 OR p.p11 = $p23 OR p.p11 = $p24 OR p.p11 = $p25 OR p.p11 = $p26 OR p.p11 = $p27 OR p.p11 = $p28 OR p.p11 = $p29 OR p.p11 = $p30 OR p.p11 = $p31 OR p.p11 = $p32 OR p.p11 = $p33 OR p.p11 = $p34 OR p.p11 = $p35 OR p.p11 = $p36 OR p.p11 = $p37 OR p.p11 = $p38 OR p.p11 = $p39 OR p.p11 = $p40 OR p.p11 = $p41 OR p.p11 = $p42 OR p.p11 = $p43 OR p.p11 = $p44 OR p.p11 = $p45 OR p.p11 = $p46 OR p.p11 = $p47 OR p.p11 = $p48 OR p.p11 = $p49 OR p.p11 = $p50 OR p.p11 = $p51 OR p.p11 = $p52 OR p.p11 = $p53 OR p.p11 = $p54 OR p.p11 = $p55 OR p.p11 = $p56 OR p.p11 = $p57 OR p.p11 = $p58 OR p.p11 = $p59 OR p.p11 = $p60 OR p.p11 IS null) 
              AND (p.p12 = $p1 OR p.p12 = $p2 OR p.p12 = $p3 OR p.p12 = $p4 OR p.p12 = $p5 OR p.p12 = $p6 OR p.p12 = $p7 OR p.p12 = $p8 OR p.p12 = $p9 OR p.p12 = $p10 OR p.p12 = $p11 OR p.p12 = $p12 OR p.p12 = $p13 OR p.p12 = $p14 OR p.p12 = $p15 OR p.p12 = $p16 OR p.p12 = $p17 OR p.p12 = $p18 OR p.p12 = $p19 OR p.p12 = $p20 OR p.p12 = $p21 OR p.p12 = $p22 OR p.p12 = $p23 OR p.p12 = $p24 OR p.p12 = $p25 OR p.p12 = $p26 OR p.p12 = $p27 OR p.p12 = $p28 OR p.p12 = $p29 OR p.p12 = $p30 OR p.p12 = $p31 OR p.p12 = $p32 OR p.p12 = $p33 OR p.p12 = $p34 OR p.p12 = $p35 OR p.p12 = $p36 OR p.p12 = $p37 OR p.p12 = $p38 OR p.p12 = $p39 OR p.p12 = $p40 OR p.p12 = $p41 OR p.p12 = $p42 OR p.p12 = $p43 OR p.p12 = $p44 OR p.p12 = $p45 OR p.p12 = $p46 OR p.p12 = $p47 OR p.p12 = $p48 OR p.p12 = $p49 OR p.p12 = $p50 OR p.p12 = $p51 OR p.p12 = $p52 OR p.p12 = $p53 OR p.p12 = $p54 OR p.p12 = $p55 OR p.p12 = $p56 OR p.p12 = $p57 OR p.p12 = $p58 OR p.p12 = $p59 OR p.p12 = $p60 OR p.p12 IS null) 
              AND (p.p13 = $p1 OR p.p13 = $p2 OR p.p13 = $p3 OR p.p13 = $p4 OR p.p13 = $p5 OR p.p13 = $p6 OR p.p13 = $p7 OR p.p13 = $p8 OR p.p13 = $p9 OR p.p13 = $p10 OR p.p13 = $p11 OR p.p13 = $p12 OR p.p13 = $p13 OR p.p13 = $p14 OR p.p13 = $p15 OR p.p13 = $p16 OR p.p13 = $p17 OR p.p13 = $p18 OR p.p13 = $p19 OR p.p13 = $p20 OR p.p13 = $p21 OR p.p13 = $p22 OR p.p13 = $p23 OR p.p13 = $p24 OR p.p13 = $p25 OR p.p13 = $p26 OR p.p13 = $p27 OR p.p13 = $p28 OR p.p13 = $p29 OR p.p13 = $p30 OR p.p13 = $p31 OR p.p13 = $p32 OR p.p13 = $p33 OR p.p13 = $p34 OR p.p13 = $p35 OR p.p13 = $p36 OR p.p13 = $p37 OR p.p13 = $p38 OR p.p13 = $p39 OR p.p13 = $p40 OR p.p13 = $p41 OR p.p13 = $p42 OR p.p13 = $p43 OR p.p13 = $p44 OR p.p13 = $p45 OR p.p13 = $p46 OR p.p13 = $p47 OR p.p13 = $p48 OR p.p13 = $p49 OR p.p13 = $p50 OR p.p13 = $p51 OR p.p13 = $p52 OR p.p13 = $p53 OR p.p13 = $p54 OR p.p13 = $p55 OR p.p13 = $p56 OR p.p13 = $p57 OR p.p13 = $p58 OR p.p13 = $p59 OR p.p13 = $p60 OR p.p13 IS null) 
              AND (p.p14 = $p1 OR p.p14 = $p2 OR p.p14 = $p3 OR p.p14 = $p4 OR p.p14 = $p5 OR p.p14 = $p6 OR p.p14 = $p7 OR p.p14 = $p8 OR p.p14 = $p9 OR p.p14 = $p10 OR p.p14 = $p11 OR p.p14 = $p12 OR p.p14 = $p13 OR p.p14 = $p14 OR p.p14 = $p15 OR p.p14 = $p16 OR p.p14 = $p17 OR p.p14 = $p18 OR p.p14 = $p19 OR p.p14 = $p20 OR p.p14 = $p21 OR p.p14 = $p22 OR p.p14 = $p23 OR p.p14 = $p24 OR p.p14 = $p25 OR p.p14 = $p26 OR p.p14 = $p27 OR p.p14 = $p28 OR p.p14 = $p29 OR p.p14 = $p30 OR p.p14 = $p31 OR p.p14 = $p32 OR p.p14 = $p33 OR p.p14 = $p34 OR p.p14 = $p35 OR p.p14 = $p36 OR p.p14 = $p37 OR p.p14 = $p38 OR p.p14 = $p39 OR p.p14 = $p40 OR p.p14 = $p41 OR p.p14 = $p42 OR p.p14 = $p43 OR p.p14 = $p44 OR p.p14 = $p45 OR p.p14 = $p46 OR p.p14 = $p47 OR p.p14 = $p48 OR p.p14 = $p49 OR p.p14 = $p50 OR p.p14 = $p51 OR p.p14 = $p52 OR p.p14 = $p53 OR p.p14 = $p54 OR p.p14 = $p55 OR p.p14 = $p56 OR p.p14 = $p57 OR p.p14 = $p58 OR p.p14 = $p59 OR p.p14 = $p60 OR p.p14 IS null) 
              AND (p.p15 = $p1 OR p.p15 = $p2 OR p.p15 = $p3 OR p.p15 = $p4 OR p.p15 = $p5 OR p.p15 = $p6 OR p.p15 = $p7 OR p.p15 = $p8 OR p.p15 = $p9 OR p.p15 = $p10 OR p.p15 = $p11 OR p.p15 = $p12 OR p.p15 = $p13 OR p.p15 = $p14 OR p.p15 = $p15 OR p.p15 = $p16 OR p.p15 = $p17 OR p.p15 = $p18 OR p.p15 = $p19 OR p.p15 = $p20 OR p.p15 = $p21 OR p.p15 = $p22 OR p.p15 = $p23 OR p.p15 = $p24 OR p.p15 = $p25 OR p.p15 = $p26 OR p.p15 = $p27 OR p.p15 = $p28 OR p.p15 = $p29 OR p.p15 = $p30 OR p.p15 = $p31 OR p.p15 = $p32 OR p.p15 = $p33 OR p.p15 = $p34 OR p.p15 = $p35 OR p.p15 = $p36 OR p.p15 = $p37 OR p.p15 = $p38 OR p.p15 = $p39 OR p.p15 = $p40 OR p.p15 = $p41 OR p.p15 = $p42 OR p.p15 = $p43 OR p.p15 = $p44 OR p.p15 = $p45 OR p.p15 = $p46 OR p.p15 = $p47 OR p.p15 = $p48 OR p.p15 = $p49 OR p.p15 = $p50 OR p.p15 = $p51 OR p.p15 = $p52 OR p.p15 = $p53 OR p.p15 = $p54 OR p.p15 = $p55 OR p.p15 = $p56 OR p.p15 = $p57 OR p.p15 = $p58 OR p.p15 = $p59 OR p.p15 = $p60 OR p.p15 IS null) 
              AND (p.p16 = $p1 OR p.p16 = $p2 OR p.p16 = $p3 OR p.p16 = $p4 OR p.p16 = $p5 OR p.p16 = $p6 OR p.p16 = $p7 OR p.p16 = $p8 OR p.p16 = $p9 OR p.p16 = $p10 OR p.p16 = $p11 OR p.p16 = $p12 OR p.p16 = $p13 OR p.p16 = $p14 OR p.p16 = $p15 OR p.p16 = $p16 OR p.p16 = $p17 OR p.p16 = $p18 OR p.p16 = $p19 OR p.p16 = $p20 OR p.p16 = $p21 OR p.p16 = $p22 OR p.p16 = $p23 OR p.p16 = $p24 OR p.p16 = $p25 OR p.p16 = $p26 OR p.p16 = $p27 OR p.p16 = $p28 OR p.p16 = $p29 OR p.p16 = $p30 OR p.p16 = $p31 OR p.p16 = $p32 OR p.p16 = $p33 OR p.p16 = $p34 OR p.p16 = $p35 OR p.p16 = $p36 OR p.p16 = $p37 OR p.p16 = $p38 OR p.p16 = $p39 OR p.p16 = $p40 OR p.p16 = $p41 OR p.p16 = $p42 OR p.p16 = $p43 OR p.p16 = $p44 OR p.p16 = $p45 OR p.p16 = $p46 OR p.p16 = $p47 OR p.p16 = $p48 OR p.p16 = $p49 OR p.p16 = $p50 OR p.p16 = $p51 OR p.p16 = $p52 OR p.p16 = $p53 OR p.p16 = $p54 OR p.p16 = $p55 OR p.p16 = $p56 OR p.p16 = $p57 OR p.p16 = $p58 OR p.p16 = $p59 OR p.p16 = $p60 OR p.p16 IS null) 
              AND (p.p17 = $p1 OR p.p17 = $p2 OR p.p17 = $p3 OR p.p17 = $p4 OR p.p17 = $p5 OR p.p17 = $p6 OR p.p17 = $p7 OR p.p17 = $p8 OR p.p17 = $p9 OR p.p17 = $p10 OR p.p17 = $p11 OR p.p17 = $p12 OR p.p17 = $p13 OR p.p17 = $p14 OR p.p17 = $p15 OR p.p17 = $p16 OR p.p17 = $p17 OR p.p17 = $p18 OR p.p17 = $p19 OR p.p17 = $p20 OR p.p17 = $p21 OR p.p17 = $p22 OR p.p17 = $p23 OR p.p17 = $p24 OR p.p17 = $p25 OR p.p17 = $p26 OR p.p17 = $p27 OR p.p17 = $p28 OR p.p17 = $p29 OR p.p17 = $p30 OR p.p17 = $p31 OR p.p17 = $p32 OR p.p17 = $p33 OR p.p17 = $p34 OR p.p17 = $p35 OR p.p17 = $p36 OR p.p17 = $p37 OR p.p17 = $p38 OR p.p17 = $p39 OR p.p17 = $p40 OR p.p17 = $p41 OR p.p17 = $p42 OR p.p17 = $p43 OR p.p17 = $p44 OR p.p17 = $p45 OR p.p17 = $p46 OR p.p17 = $p47 OR p.p17 = $p48 OR p.p17 = $p49 OR p.p17 = $p50 OR p.p17 = $p51 OR p.p17 = $p52 OR p.p17 = $p53 OR p.p17 = $p54 OR p.p17 = $p55 OR p.p17 = $p56 OR p.p17 = $p57 OR p.p17 = $p58 OR p.p17 = $p59 OR p.p17 = $p60 OR p.p17 IS null) 
              AND (p.p18 = $p1 OR p.p18 = $p2 OR p.p18 = $p3 OR p.p18 = $p4 OR p.p18 = $p5 OR p.p18 = $p6 OR p.p18 = $p7 OR p.p18 = $p8 OR p.p18 = $p9 OR p.p18 = $p10 OR p.p18 = $p11 OR p.p18 = $p12 OR p.p18 = $p13 OR p.p18 = $p14 OR p.p18 = $p15 OR p.p18 = $p16 OR p.p18 = $p17 OR p.p18 = $p18 OR p.p18 = $p19 OR p.p18 = $p20 OR p.p18 = $p21 OR p.p18 = $p22 OR p.p18 = $p23 OR p.p18 = $p24 OR p.p18 = $p25 OR p.p18 = $p26 OR p.p18 = $p27 OR p.p18 = $p28 OR p.p18 = $p29 OR p.p18 = $p30 OR p.p18 = $p31 OR p.p18 = $p32 OR p.p18 = $p33 OR p.p18 = $p34 OR p.p18 = $p35 OR p.p18 = $p36 OR p.p18 = $p37 OR p.p18 = $p38 OR p.p18 = $p39 OR p.p18 = $p40 OR p.p18 = $p41 OR p.p18 = $p42 OR p.p18 = $p43 OR p.p18 = $p44 OR p.p18 = $p45 OR p.p18 = $p46 OR p.p18 = $p47 OR p.p18 = $p48 OR p.p18 = $p49 OR p.p18 = $p50 OR p.p18 = $p51 OR p.p18 = $p52 OR p.p18 = $p53 OR p.p18 = $p54 OR p.p18 = $p55 OR p.p18 = $p56 OR p.p18 = $p57 OR p.p18 = $p58 OR p.p18 = $p59 OR p.p18 = $p60 OR p.p18 IS null) 
              AND (p.p19 = $p1 OR p.p19 = $p2 OR p.p19 = $p3 OR p.p19 = $p4 OR p.p19 = $p5 OR p.p19 = $p6 OR p.p19 = $p7 OR p.p19 = $p8 OR p.p19 = $p9 OR p.p19 = $p10 OR p.p19 = $p11 OR p.p19 = $p12 OR p.p19 = $p13 OR p.p19 = $p14 OR p.p19 = $p15 OR p.p19 = $p16 OR p.p19 = $p17 OR p.p19 = $p18 OR p.p19 = $p19 OR p.p19 = $p20 OR p.p19 = $p21 OR p.p19 = $p22 OR p.p19 = $p23 OR p.p19 = $p24 OR p.p19 = $p25 OR p.p19 = $p26 OR p.p19 = $p27 OR p.p19 = $p28 OR p.p19 = $p29 OR p.p19 = $p30 OR p.p19 = $p31 OR p.p19 = $p32 OR p.p19 = $p33 OR p.p19 = $p34 OR p.p19 = $p35 OR p.p19 = $p36 OR p.p19 = $p37 OR p.p19 = $p38 OR p.p19 = $p39 OR p.p19 = $p40 OR p.p19 = $p41 OR p.p19 = $p42 OR p.p19 = $p43 OR p.p19 = $p44 OR p.p19 = $p45 OR p.p19 = $p46 OR p.p19 = $p47 OR p.p19 = $p48 OR p.p19 = $p49 OR p.p19 = $p50 OR p.p19 = $p51 OR p.p19 = $p52 OR p.p19 = $p53 OR p.p19 = $p54 OR p.p19 = $p55 OR p.p19 = $p56 OR p.p19 = $p57 OR p.p19 = $p58 OR p.p19 = $p59 OR p.p19 = $p60 OR p.p19 IS null) 
              AND (p.p20 = $p1 OR p.p20 = $p2 OR p.p20 = $p3 OR p.p20 = $p4 OR p.p20 = $p5 OR p.p20 = $p6 OR p.p20 = $p7 OR p.p20 = $p8 OR p.p20 = $p9 OR p.p20 = $p10 OR p.p20 = $p11 OR p.p20 = $p12 OR p.p20 = $p13 OR p.p20 = $p14 OR p.p20 = $p15 OR p.p20 = $p16 OR p.p20 = $p17 OR p.p20 = $p18 OR p.p20 = $p19 OR p.p20 = $p20 OR p.p20 = $p21 OR p.p20 = $p22 OR p.p20 = $p23 OR p.p20 = $p24 OR p.p20 = $p25 OR p.p20 = $p26 OR p.p20 = $p27 OR p.p20 = $p28 OR p.p20 = $p29 OR p.p20 = $p30 OR p.p20 = $p31 OR p.p20 = $p32 OR p.p20 = $p33 OR p.p20 = $p34 OR p.p20 = $p35 OR p.p20 = $p36 OR p.p20 = $p37 OR p.p20 = $p38 OR p.p20 = $p39 OR p.p20 = $p40 OR p.p20 = $p41 OR p.p20 = $p42 OR p.p20 = $p43 OR p.p20 = $p44 OR p.p20 = $p45 OR p.p20 = $p46 OR p.p20 = $p47 OR p.p20 = $p48 OR p.p20 = $p49 OR p.p20 = $p50 OR p.p20 = $p51 OR p.p20 = $p52 OR p.p20 = $p53 OR p.p20 = $p54 OR p.p20 = $p55 OR p.p20 = $p56 OR p.p20 = $p57 OR p.p20 = $p58 OR p.p20 = $p59 OR p.p20 = $p60 OR p.p20 IS null) 
              AND (p.p21 = $p1 OR p.p21 = $p2 OR p.p21 = $p3 OR p.p21 = $p4 OR p.p21 = $p5 OR p.p21 = $p6 OR p.p21 = $p7 OR p.p21 = $p8 OR p.p21 = $p9 OR p.p21 = $p10 OR p.p21 = $p11 OR p.p21 = $p12 OR p.p21 = $p13 OR p.p21 = $p14 OR p.p21 = $p15 OR p.p21 = $p16 OR p.p21 = $p17 OR p.p21 = $p18 OR p.p21 = $p19 OR p.p21 = $p20 OR p.p21 = $p21 OR p.p21 = $p22 OR p.p21 = $p23 OR p.p21 = $p24 OR p.p21 = $p25 OR p.p21 = $p26 OR p.p21 = $p27 OR p.p21 = $p28 OR p.p21 = $p29 OR p.p21 = $p30 OR p.p21 = $p31 OR p.p21 = $p32 OR p.p21 = $p33 OR p.p21 = $p34 OR p.p21 = $p35 OR p.p21 = $p36 OR p.p21 = $p37 OR p.p21 = $p38 OR p.p21 = $p39 OR p.p21 = $p40 OR p.p21 = $p41 OR p.p21 = $p42 OR p.p21 = $p43 OR p.p21 = $p44 OR p.p21 = $p45 OR p.p21 = $p46 OR p.p21 = $p47 OR p.p21 = $p48 OR p.p21 = $p49 OR p.p21 = $p50 OR p.p21 = $p51 OR p.p21 = $p52 OR p.p21 = $p53 OR p.p21 = $p54 OR p.p21 = $p55 OR p.p21 = $p56 OR p.p21 = $p57 OR p.p21 = $p58 OR p.p21 = $p59 OR p.p21 = $p60 OR p.p21 IS null) 
              AND (p.p22 = $p1 OR p.p22 = $p2 OR p.p22 = $p3 OR p.p22 = $p4 OR p.p22 = $p5 OR p.p22 = $p6 OR p.p22 = $p7 OR p.p22 = $p8 OR p.p22 = $p9 OR p.p22 = $p10 OR p.p22 = $p11 OR p.p22 = $p12 OR p.p22 = $p13 OR p.p22 = $p14 OR p.p22 = $p15 OR p.p22 = $p16 OR p.p22 = $p17 OR p.p22 = $p18 OR p.p22 = $p19 OR p.p22 = $p20 OR p.p22 = $p21 OR p.p22 = $p22 OR p.p22 = $p23 OR p.p22 = $p24 OR p.p22 = $p25 OR p.p22 = $p26 OR p.p22 = $p27 OR p.p22 = $p28 OR p.p22 = $p29 OR p.p22 = $p30 OR p.p22 = $p31 OR p.p22 = $p32 OR p.p22 = $p33 OR p.p22 = $p34 OR p.p22 = $p35 OR p.p22 = $p36 OR p.p22 = $p37 OR p.p22 = $p38 OR p.p22 = $p39 OR p.p22 = $p40 OR p.p22 = $p41 OR p.p22 = $p42 OR p.p22 = $p43 OR p.p22 = $p44 OR p.p22 = $p45 OR p.p22 = $p46 OR p.p22 = $p47 OR p.p22 = $p48 OR p.p22 = $p49 OR p.p22 = $p50 OR p.p22 = $p51 OR p.p22 = $p52 OR p.p22 = $p53 OR p.p22 = $p54 OR p.p22 = $p55 OR p.p22 = $p56 OR p.p22 = $p57 OR p.p22 = $p58 OR p.p22 = $p59 OR p.p22 = $p60 OR p.p22 IS null) 
              AND (p.p23 = $p1 OR p.p23 = $p2 OR p.p23 = $p3 OR p.p23 = $p4 OR p.p23 = $p5 OR p.p23 = $p6 OR p.p23 = $p7 OR p.p23 = $p8 OR p.p23 = $p9 OR p.p23 = $p10 OR p.p23 = $p11 OR p.p23 = $p12 OR p.p23 = $p13 OR p.p23 = $p14 OR p.p23 = $p15 OR p.p23 = $p16 OR p.p23 = $p17 OR p.p23 = $p18 OR p.p23 = $p19 OR p.p23 = $p20 OR p.p23 = $p21 OR p.p23 = $p22 OR p.p23 = $p23 OR p.p23 = $p24 OR p.p23 = $p25 OR p.p23 = $p26 OR p.p23 = $p27 OR p.p23 = $p28 OR p.p23 = $p29 OR p.p23 = $p30 OR p.p23 = $p31 OR p.p23 = $p32 OR p.p23 = $p33 OR p.p23 = $p34 OR p.p23 = $p35 OR p.p23 = $p36 OR p.p23 = $p37 OR p.p23 = $p38 OR p.p23 = $p39 OR p.p23 = $p40 OR p.p23 = $p41 OR p.p23 = $p42 OR p.p23 = $p43 OR p.p23 = $p44 OR p.p23 = $p45 OR p.p23 = $p46 OR p.p23 = $p47 OR p.p23 = $p48 OR p.p23 = $p49 OR p.p23 = $p50 OR p.p23 = $p51 OR p.p23 = $p52 OR p.p23 = $p53 OR p.p23 = $p54 OR p.p23 = $p55 OR p.p23 = $p56 OR p.p23 = $p57 OR p.p23 = $p58 OR p.p23 = $p59 OR p.p23 = $p60 OR p.p23 IS null) 
              AND (p.p24 = $p1 OR p.p24 = $p2 OR p.p24 = $p3 OR p.p24 = $p4 OR p.p24 = $p5 OR p.p24 = $p6 OR p.p24 = $p7 OR p.p24 = $p8 OR p.p24 = $p9 OR p.p24 = $p10 OR p.p24 = $p11 OR p.p24 = $p12 OR p.p24 = $p13 OR p.p24 = $p14 OR p.p24 = $p15 OR p.p24 = $p16 OR p.p24 = $p17 OR p.p24 = $p18 OR p.p24 = $p19 OR p.p24 = $p20 OR p.p24 = $p21 OR p.p24 = $p22 OR p.p24 = $p23 OR p.p24 = $p24 OR p.p24 = $p25 OR p.p24 = $p26 OR p.p24 = $p27 OR p.p24 = $p28 OR p.p24 = $p29 OR p.p24 = $p30 OR p.p24 = $p31 OR p.p24 = $p32 OR p.p24 = $p33 OR p.p24 = $p34 OR p.p24 = $p35 OR p.p24 = $p36 OR p.p24 = $p37 OR p.p24 = $p38 OR p.p24 = $p39 OR p.p24 = $p40 OR p.p24 = $p41 OR p.p24 = $p42 OR p.p24 = $p43 OR p.p24 = $p44 OR p.p24 = $p45 OR p.p24 = $p46 OR p.p24 = $p47 OR p.p24 = $p48 OR p.p24 = $p49 OR p.p24 = $p50 OR p.p24 = $p51 OR p.p24 = $p52 OR p.p24 = $p53 OR p.p24 = $p54 OR p.p24 = $p55 OR p.p24 = $p56 OR p.p24 = $p57 OR p.p24 = $p58 OR p.p24 = $p59 OR p.p24 = $p60 OR p.p24 IS null) 
              AND (p.p25 = $p1 OR p.p25 = $p2 OR p.p25 = $p3 OR p.p25 = $p4 OR p.p25 = $p5 OR p.p25 = $p6 OR p.p25 = $p7 OR p.p25 = $p8 OR p.p25 = $p9 OR p.p25 = $p10 OR p.p25 = $p11 OR p.p25 = $p12 OR p.p25 = $p13 OR p.p25 = $p14 OR p.p25 = $p15 OR p.p25 = $p16 OR p.p25 = $p17 OR p.p25 = $p18 OR p.p25 = $p19 OR p.p25 = $p20 OR p.p25 = $p21 OR p.p25 = $p22 OR p.p25 = $p23 OR p.p25 = $p24 OR p.p25 = $p25 OR p.p25 = $p26 OR p.p25 = $p27 OR p.p25 = $p28 OR p.p25 = $p29 OR p.p25 = $p30 OR p.p25 = $p31 OR p.p25 = $p32 OR p.p25 = $p33 OR p.p25 = $p34 OR p.p25 = $p35 OR p.p25 = $p36 OR p.p25 = $p37 OR p.p25 = $p38 OR p.p25 = $p39 OR p.p25 = $p40 OR p.p25 = $p41 OR p.p25 = $p42 OR p.p25 = $p43 OR p.p25 = $p44 OR p.p25 = $p45 OR p.p25 = $p46 OR p.p25 = $p47 OR p.p25 = $p48 OR p.p25 = $p49 OR p.p25 = $p50 OR p.p25 = $p51 OR p.p25 = $p52 OR p.p25 = $p53 OR p.p25 = $p54 OR p.p25 = $p55 OR p.p25 = $p56 OR p.p25 = $p57 OR p.p25 = $p58 OR p.p25 = $p59 OR p.p25 = $p60 OR p.p25 IS null) 
              AND (p.p26 = $p1 OR p.p26 = $p2 OR p.p26 = $p3 OR p.p26 = $p4 OR p.p26 = $p5 OR p.p26 = $p6 OR p.p26 = $p7 OR p.p26 = $p8 OR p.p26 = $p9 OR p.p26 = $p10 OR p.p26 = $p11 OR p.p26 = $p12 OR p.p26 = $p13 OR p.p26 = $p14 OR p.p26 = $p15 OR p.p26 = $p16 OR p.p26 = $p17 OR p.p26 = $p18 OR p.p26 = $p19 OR p.p26 = $p20 OR p.p26 = $p21 OR p.p26 = $p22 OR p.p26 = $p23 OR p.p26 = $p24 OR p.p26 = $p25 OR p.p26 = $p26 OR p.p26 = $p27 OR p.p26 = $p28 OR p.p26 = $p29 OR p.p26 = $p30 OR p.p26 = $p31 OR p.p26 = $p32 OR p.p26 = $p33 OR p.p26 = $p34 OR p.p26 = $p35 OR p.p26 = $p36 OR p.p26 = $p37 OR p.p26 = $p38 OR p.p26 = $p39 OR p.p26 = $p40 OR p.p26 = $p41 OR p.p26 = $p42 OR p.p26 = $p43 OR p.p26 = $p44 OR p.p26 = $p45 OR p.p26 = $p46 OR p.p26 = $p47 OR p.p26 = $p48 OR p.p26 = $p49 OR p.p26 = $p50 OR p.p26 = $p51 OR p.p26 = $p52 OR p.p26 = $p53 OR p.p26 = $p54 OR p.p26 = $p55 OR p.p26 = $p56 OR p.p26 = $p57 OR p.p26 = $p58 OR p.p26 = $p59 OR p.p26 = $p60 OR p.p26 IS null) 
              AND (p.p27 = $p1 OR p.p27 = $p2 OR p.p27 = $p3 OR p.p27 = $p4 OR p.p27 = $p5 OR p.p27 = $p6 OR p.p27 = $p7 OR p.p27 = $p8 OR p.p27 = $p9 OR p.p27 = $p10 OR p.p27 = $p11 OR p.p27 = $p12 OR p.p27 = $p13 OR p.p27 = $p14 OR p.p27 = $p15 OR p.p27 = $p16 OR p.p27 = $p17 OR p.p27 = $p18 OR p.p27 = $p19 OR p.p27 = $p20 OR p.p27 = $p21 OR p.p27 = $p22 OR p.p27 = $p23 OR p.p27 = $p24 OR p.p27 = $p25 OR p.p27 = $p26 OR p.p27 = $p27 OR p.p27 = $p28 OR p.p27 = $p29 OR p.p27 = $p30 OR p.p27 = $p31 OR p.p27 = $p32 OR p.p27 = $p33 OR p.p27 = $p34 OR p.p27 = $p35 OR p.p27 = $p36 OR p.p27 = $p37 OR p.p27 = $p38 OR p.p27 = $p39 OR p.p27 = $p40 OR p.p27 = $p41 OR p.p27 = $p42 OR p.p27 = $p43 OR p.p27 = $p44 OR p.p27 = $p45 OR p.p27 = $p46 OR p.p27 = $p47 OR p.p27 = $p48 OR p.p27 = $p49 OR p.p27 = $p50 OR p.p27 = $p51 OR p.p27 = $p52 OR p.p27 = $p53 OR p.p27 = $p54 OR p.p27 = $p55 OR p.p27 = $p56 OR p.p27 = $p57 OR p.p27 = $p58 OR p.p27 = $p59 OR p.p27 = $p60 OR p.p27 IS null) 
              ";
  return find_by_sql($sql);
}

function buscar_carton_imprimir($desde, $hasta)
{
  global $db;
  global $db;
  $desde  = $desde;
  $hasta  = $hasta;

  $sql  = " SELECT *";
  $sql  .= " FROM series p ";
  $sql .= "JOIN cartones c ON p.carton = c.carton";
  $sql .= " WHERE p.serie BETWEEN $desde AND $hasta";
  $sql  .= " ORDER BY p.id ASC";
  return find_by_sql($sql);
}

function logs()
{
  global $db;
  $sql_query  = " SELECT *";
  $sql_query  .= " FROM logs p";
  $sql_query  .= " ORDER BY p.id DESC";
  $sql_query .= " LIMIT 20";

  return find_by_sql($sql_query);
}

function logs_historico()
{
  global $db;
  $sql_query  = " SELECT *";
  $sql_query  .= " FROM logs p";
  $sql_query  .= " ORDER BY p.id DESC";

  return find_by_sql($sql_query);
}
