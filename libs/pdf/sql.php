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
function join_product_table()
{
  global $db;
  $sql  = " SELECT p.id,p.name,p.partNo,p.quantity,p.buy_price,p.sale_price,p.location,p.media_id,p.date,c.name";
  $sql  .= " AS categorie,m.file_name AS image";
  $sql  .= " FROM products p";
  $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql  .= " ORDER BY p.id ASC";
  return find_by_sql($sql);
}


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
/*--------------------------------------------------------------*/
/* Function for Finding all product name
/* Request coming from ajax.php for auto suggest
/*--------------------------------------------------------------*/
function find_product_by_title($product_name)
{
  global $db;
  $p_name = remove_junk($db->escape($product_name));
  $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
  $result = find_by_sql($sql);
  return $result;
}

/** Search a product by its name
 * 
 *  By Yoel Monsalve -- June, 2016.
 */
function find_product_by_name($p_name)
{
  global $db;        // <-- ???

  $p_name = remove_junk($db->escape($p_name));
  $sql_query  = "SELECT * FROM `products`";
  $sql_query .= " WHERE `name`='${p_name}'";
  $sql_query .= " LIMIT 1";
  $sql_result = $db->query($sql_query);
  if ($result = $db->fetch_assoc($sql_result)) {
    return $result;
  } else
    return NULL;        /* yoel: this 'NULL' is on pretty old C-style, so nice! */
}

/** Search a product by its partNo/COD
 * 
 *  By Yoel Monsalve -- June, 2016.
 */
function find_product_by_partNo($partNo, $mode = 0)
{
  global $db;        // <-- ???

  $partNo = remove_junk($db->escape($partNo));
  /* exact match */
  if ($mode == 0) {
    $sql_query  = "SELECT * FROM `products`";
    $sql_query .= " WHERE `partNo`='${partNo}'";
    $sql_query .= " LIMIT 1";

    $sql_result = $db->query($sql_query);
    $result = $db->fetch_assoc($sql_result);
  }
  /* partial match */ else {
    $sql_query  = "SELECT `partNo` FROM `products`";
    $sql_query .= " WHERE `partNo` LIKE '%${partNo}%'";
    $sql_query .= " LIMIT 5";

    $result = find_by_sql($sql_query);
  }
  if ($result) {
    return $result;
  } else
    return NULL;        /* yoel: this 'NULL' is on pretty old C-style, so nice! */
}

/*--------------------------------------------------------------*/
/* Function for Finding all product info by product title
/* Request coming from ajax.php
/*--------------------------------------------------------------*/
function find_all_product_info_by_title($title)
{
  global $db;
  $sql  = "SELECT * FROM products ";
  $sql .= " WHERE name ='{$title}'";
  $sql .= " LIMIT 1";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Function for Update product quantity
/*--------------------------------------------------------------*/
function update_product_qty($qty, $p_id)
{
  global $db;
  $qty = (int)$qty;
  $id  = (int)$p_id;
  $sql  =  "UPDATE products SET quantity = quantity -'{$qty}'";
  $sql .= " WHERE id = '{$id}'";
  $result = $db->query($sql);
  return ($db->affected_rows() === 1 ? TRUE : FALSE);
}
/*--------------------------------------------------------------*/
/* Function for Display Recent product Added
/*--------------------------------------------------------------*/
function find_recent_product_added($limit)
{
  global $db;
  $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
  $sql  .= "m.file_name AS image FROM products p";
  $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql  .= " ORDER BY p.id DESC LIMIT " . $db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Find Highest saleing Product
/*--------------------------------------------------------------*/
function find_higest_saleing_product($limit)
{
  global $db;
  $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
  $sql .= " GROUP BY s.product_id";
  $sql .= " ORDER BY SUM(s.qty) DESC LIMIT " . $db->escape((int)$limit);
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for find all sales
/*--------------------------------------------------------------*/
function find_all_sales()
{
  global $db;
  $sql  = "SELECT s.id,s.product_id,s.qty,s.sale_price,s.total_sale,s.destination,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Display Recent sale
/*--------------------------------------------------------------*/
function find_recent_sale_added($limit)
{
  global $db;
  $sql  = "SELECT s.id,s.qty,s.sale_price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT " . $db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date, $end_date)
{
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date,p.name,s.destination,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  //$sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(s.qty) AS total_qty,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
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


function buscar_carton($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14, $p15, $p16, $p17, $p18, $p19, $p20)
{
  global $db;
  $p1  = $p1;
  $p2  = $p2;
  $p3  = $p3;
  $p4  = $p4;
  $p5  = $p5;
  $p6  = $p6;
  $p7  = $p7;
  $p8  = $p8;
  $p9  = $p9;
  $p10 = $p10;
  $p11 = $p11;
  $p12 = $p12;
  $p13 = $p13;
  $p14 = $p14;
  $p15 = $p15;
  $p16 = $p16;
  $p17 = $p17;
  $p18 = $p18;
  $p19 = $p19;
  $p20 = $p20;




  $sql  = " SELECT *";
  $sql  .= " FROM cartones p ";
  $sql .= " WHERE (p.p1 = $p1 OR p.p1 = $p2 OR p.p1 = $p3 OR p.p1 = $p4 OR p.p1 = $p5 OR p.p1 = $p6 OR p.p1 = $p7 OR p.p1 = $p8 OR p.p1 = $p9 OR p.p1 = $p10 OR p.p1 = $p11 OR p.p1 = $p12 OR p.p1 = $p13 OR p.p1 = $p14 OR p.p1 = $p15 OR p.p1 = $p16 OR p.p1 = $p17 OR p.p1 = $p18 OR p.p1 = $p19 OR p.p1 = $p20 OR p.p1 = 0)
              AND (p.p2 = $p1 OR p.p2 = $p2 OR p.p2 = $p3 OR p.p2 = $p4 OR p.p2 = $p5 OR p.p2 = $p6 OR p.p2 = $p7 OR p.p2 = $p8 OR p.p2 = $p9 OR p.p2 = $p10 OR p.p2 = $p11 OR p.p2 = $p12 OR p.p2 = $p13 OR p.p2 = $p14 OR p.p2 = $p15 OR p.p2 = $p16 OR p.p2 = $p17 OR p.p2 = $p18 OR p.p2 = $p19 OR p.p2 = $p20 OR p.p2 = 0) 
              AND (p.p3 = $p1 OR p.p3 = $p2 OR p.p3 = $p3 OR p.p3 = $p4 OR p.p3 = $p5 OR p.p3 = $p6 OR p.p3 = $p7 OR p.p3 = $p8 OR p.p3 = $p9 OR p.p3 = $p10 OR p.p3 = $p11 OR p.p3 = $p12 OR p.p3 = $p13 OR p.p3 = $p14 OR p.p3 = $p15 OR p.p3 = $p16 OR p.p3 = $p17 OR p.p3 = $p18 OR p.p3 = $p19 OR p.p3 = $p20 OR p.p3 = 0) 
              AND (p.p4 = $p1 OR p.p4 = $p2 OR p.p4 = $p3 OR p.p4 = $p4 OR p.p4 = $p5 OR p.p4 = $p6 OR p.p4 = $p7 OR p.p4 = $p8 OR p.p4 = $p9 OR p.p4 = $p10 OR p.p4 = $p11 OR p.p4 = $p12 OR p.p4 = $p13 OR p.p4 = $p14 OR p.p4 = $p15 OR p.p4 = $p16 OR p.p4 = $p17 OR p.p4 = $p18 OR p.p4 = $p19 OR p.p4 = $p20 OR p.p4 = 0) 
              AND (p.p5 = $p1 OR p.p5 = $p2 OR p.p5 = $p3 OR p.p5 = $p4 OR p.p5 = $p5 OR p.p5 = $p6 OR p.p5 = $p7 OR p.p5 = $p8 OR p.p5 = $p9 OR p.p5 = $p10 OR p.p5 = $p11 OR p.p5 = $p12 OR p.p5 = $p13 OR p.p5 = $p14 OR p.p5 = $p15 OR p.p5 = $p16 OR p.p5 = $p17 OR p.p5 = $p18 OR p.p5 = $p19 OR p.p5 = $p20 OR p.p5 = 0) 
              AND (p.p6 = $p1 OR p.p6 = $p2 OR p.p6 = $p3 OR p.p6 = $p4 OR p.p6 = $p5 OR p.p6 = $p6 OR p.p6 = $p7 OR p.p6 = $p8 OR p.p6 = $p9 OR p.p6 = $p10 OR p.p6 = $p11 OR p.p6 = $p12 OR p.p6 = $p13 OR p.p6 = $p14 OR p.p6 = $p15 OR p.p6 = $p16 OR p.p6 = $p17 OR p.p6 = $p18 OR p.p6 = $p19 OR p.p6 = $p20 OR p.p6 = 0) 
              AND (p.p7 = $p1 OR p.p7 = $p2 OR p.p7 = $p3 OR p.p7 = $p4 OR p.p7 = $p5 OR p.p7 = $p6 OR p.p7 = $p7 OR p.p7 = $p8 OR p.p7 = $p9 OR p.p7 = $p10 OR p.p7 = $p11 OR p.p7 = $p12 OR p.p7 = $p13 OR p.p7 = $p14 OR p.p7 = $p15 OR p.p7 = $p16 OR p.p7 = $p17 OR p.p7 = $p18 OR p.p7 = $p19 OR p.p7 = $p20 OR p.p7 = 0) 
              AND (p.p8 = $p1 OR p.p8 = $p2 OR p.p8 = $p3 OR p.p8 = $p4 OR p.p8 = $p5 OR p.p8 = $p6 OR p.p8 = $p7 OR p.p8 = $p8 OR p.p8 = $p9 OR p.p8 = $p10 OR p.p8 = $p11 OR p.p8 = $p12 OR p.p8 = $p13 OR p.p8 = $p14 OR p.p8 = $p15 OR p.p8 = $p16 OR p.p8 = $p17 OR p.p8 = $p18 OR p.p8 = $p19 OR p.p8 = $p20 OR p.p8 = 0) 
              AND (p.p9 = $p1 OR p.p9 = $p2 OR p.p9 = $p3 OR p.p9 = $p4 OR p.p9 = $p5 OR p.p9 = $p6 OR p.p9 = $p7 OR p.p9 = $p8 OR p.p9 = $p9 OR p.p9 = $p10 OR p.p9 = $p11 OR p.p9 = $p12 OR p.p9 = $p13 OR p.p9 = $p14 OR p.p9 = $p15 OR p.p9 = $p16 OR p.p9 = $p17 OR p.p9 = $p18 OR p.p9 = $p19 OR p.p9 = $p20 OR p.p9 = 0) 
              AND (p.p10 = $p1 OR p.p10 = $p2 OR p.p10 = $p3 OR p.p10 = $p4 OR p.p10 = $p5 OR p.p10 = $p6 OR p.p10 = $p7 OR p.p10 = $p8 OR p.p10 = $p9 OR p.p10 = $p10 OR p.p10 = $p11 OR p.p10 = $p12 OR p.p10 = $p13 OR p.p10 = $p14 OR p.p10 = $p15 OR p.p10 = $p16 OR p.p10 = $p17 OR p.p10 = $p18 OR p.p10 = $p19 OR p.p10 = $p20 OR p.p10 = 0) 
              AND (p.p11 = $p1 OR p.p11 = $p2 OR p.p11 = $p3 OR p.p11 = $p4 OR p.p11 = $p5 OR p.p11 = $p6 OR p.p11 = $p7 OR p.p11 = $p8 OR p.p11 = $p9 OR p.p11 = $p10 OR p.p11 = $p11 OR p.p11 = $p12 OR p.p11 = $p13 OR p.p11 = $p14 OR p.p11 = $p15 OR p.p11 = $p16 OR p.p11 = $p17 OR p.p11 = $p18 OR p.p11 = $p19 OR p.p11 = $p20 OR p.p11 = 0) 
              AND (p.p12 = $p1 OR p.p12 = $p2 OR p.p12 = $p3 OR p.p12 = $p4 OR p.p12 = $p5 OR p.p12 = $p6 OR p.p12 = $p7 OR p.p12 = $p8 OR p.p12 = $p9 OR p.p12 = $p10 OR p.p12 = $p11 OR p.p12 = $p12 OR p.p12 = $p13 OR p.p12 = $p14 OR p.p12 = $p15 OR p.p12 = $p16 OR p.p12 = $p17 OR p.p12 = $p18 OR p.p12 = $p19 OR p.p12 = $p20 OR p.p12 = 0) 
              AND (p.p13 = $p1 OR p.p13 = $p2 OR p.p13 = $p3 OR p.p13 = $p4 OR p.p13 = $p5 OR p.p13 = $p6 OR p.p13 = $p7 OR p.p13 = $p8 OR p.p13 = $p9 OR p.p13 = $p10 OR p.p13 = $p11 OR p.p13 = $p12 OR p.p13 = $p13 OR p.p13 = $p14 OR p.p13 = $p15 OR p.p13 = $p16 OR p.p13 = $p17 OR p.p13 = $p18 OR p.p13 = $p19 OR p.p13 = $p20 OR p.p13 = 0) 
              AND (p.p14 = $p1 OR p.p14 = $p2 OR p.p14 = $p3 OR p.p14 = $p4 OR p.p14 = $p5 OR p.p14 = $p6 OR p.p14 = $p7 OR p.p14 = $p8 OR p.p14 = $p9 OR p.p14 = $p10 OR p.p14 = $p11 OR p.p14 = $p12 OR p.p14 = $p13 OR p.p14 = $p14 OR p.p14 = $p15 OR p.p14 = $p16 OR p.p14 = $p17 OR p.p14 = $p18 OR p.p14 = $p19 OR p.p14 = $p20 OR p.p14 = 0) 
              AND (p.p15 = $p1 OR p.p15 = $p2 OR p.p15 = $p3 OR p.p15 = $p4 OR p.p15 = $p5 OR p.p15 = $p6 OR p.p15 = $p7 OR p.p15 = $p8 OR p.p15 = $p9 OR p.p15 = $p10 OR p.p15 = $p11 OR p.p15 = $p12 OR p.p15 = $p13 OR p.p15 = $p14 OR p.p15 = $p15 OR p.p15 = $p16 OR p.p15 = $p17 OR p.p15 = $p18 OR p.p15 = $p19 OR p.p15 = $p20 OR p.p15 = 0) 
              AND (p.p16 = $p1 OR p.p16 = $p2 OR p.p16 = $p3 OR p.p16 = $p4 OR p.p16 = $p5 OR p.p16 = $p6 OR p.p16 = $p7 OR p.p16 = $p8 OR p.p16 = $p9 OR p.p16 = $p10 OR p.p16 = $p11 OR p.p16 = $p12 OR p.p16 = $p13 OR p.p16 = $p14 OR p.p16 = $p15 OR p.p16 = $p16 OR p.p16 = $p17 OR p.p16 = $p18 OR p.p16 = $p19 OR p.p16 = $p20 OR p.p16 = 0) 
              AND (p.p17 = $p1 OR p.p17 = $p2 OR p.p17 = $p3 OR p.p17 = $p4 OR p.p17 = $p5 OR p.p17 = $p6 OR p.p17 = $p7 OR p.p17 = $p8 OR p.p17 = $p9 OR p.p17 = $p10 OR p.p17 = $p11 OR p.p17 = $p12 OR p.p17 = $p13 OR p.p17 = $p14 OR p.p17 = $p15 OR p.p17 = $p16 OR p.p17 = $p17 OR p.p17 = $p18 OR p.p17 = $p19 OR p.p17 = $p20 OR p.p17 = 0) 
              AND (p.p18 = $p1 OR p.p18 = $p2 OR p.p18 = $p3 OR p.p18 = $p4 OR p.p18 = $p5 OR p.p18 = $p6 OR p.p18 = $p7 OR p.p18 = $p8 OR p.p18 = $p9 OR p.p18 = $p10 OR p.p18 = $p11 OR p.p18 = $p12 OR p.p18 = $p13 OR p.p18 = $p14 OR p.p18 = $p15 OR p.p18 = $p16 OR p.p18 = $p17 OR p.p18 = $p18 OR p.p18 = $p19 OR p.p18 = $p20 OR p.p18 = 0) 
              AND (p.p19 = $p1 OR p.p19 = $p2 OR p.p19 = $p3 OR p.p19 = $p4 OR p.p19 = $p5 OR p.p19 = $p6 OR p.p19 = $p7 OR p.p19 = $p8 OR p.p19 = $p9 OR p.p19 = $p10 OR p.p19 = $p11 OR p.p19 = $p12 OR p.p19 = $p13 OR p.p19 = $p14 OR p.p19 = $p15 OR p.p19 = $p16 OR p.p19 = $p17 OR p.p19 = $p18 OR p.p19 = $p19 OR p.p19 = $p20 OR p.p19 = 0) 
              AND (p.p20 = $p1 OR p.p20 = $p2 OR p.p20 = $p3 OR p.p20 = $p4 OR p.p20 = $p5 OR p.p20 = $p6 OR p.p20 = $p7 OR p.p20 = $p8 OR p.p20 = $p9 OR p.p20 = $p10 OR p.p20 = $p11 OR p.p20 = $p12 OR p.p20 = $p13 OR p.p20 = $p14 OR p.p20 = $p15 OR p.p20 = $p16 OR p.p20 = $p17 OR p.p20 = $p18 OR p.p20 = $p19 OR p.p20 = $p20 OR p.p20 = 0) 
              AND (p.p21 = $p1 OR p.p21 = $p2 OR p.p21 = $p3 OR p.p21 = $p4 OR p.p21 = $p5 OR p.p21 = $p6 OR p.p21 = $p7 OR p.p21 = $p8 OR p.p21 = $p9 OR p.p21 = $p10 OR p.p21 = $p11 OR p.p21 = $p12 OR p.p21 = $p13 OR p.p21 = $p14 OR p.p21 = $p15 OR p.p21 = $p16 OR p.p21 = $p17 OR p.p21 = $p18 OR p.p21 = $p19 OR p.p21 = $p20 OR p.p21 = 0) 
              AND (p.p22 = $p1 OR p.p22 = $p2 OR p.p22 = $p3 OR p.p22 = $p4 OR p.p22 = $p5 OR p.p22 = $p6 OR p.p22 = $p7 OR p.p22 = $p8 OR p.p22 = $p9 OR p.p22 = $p10 OR p.p22 = $p11 OR p.p22 = $p12 OR p.p22 = $p13 OR p.p22 = $p14 OR p.p22 = $p15 OR p.p22 = $p16 OR p.p22 = $p17 OR p.p22 = $p18 OR p.p22 = $p19 OR p.p22 = $p20 OR p.p22 = 0) 
              AND (p.p23 = $p1 OR p.p23 = $p2 OR p.p23 = $p3 OR p.p23 = $p4 OR p.p23 = $p5 OR p.p23 = $p6 OR p.p23 = $p7 OR p.p23 = $p8 OR p.p23 = $p9 OR p.p23 = $p10 OR p.p23 = $p11 OR p.p23 = $p12 OR p.p23 = $p13 OR p.p23 = $p14 OR p.p23 = $p15 OR p.p23 = $p16 OR p.p23 = $p17 OR p.p23 = $p18 OR p.p23 = $p19 OR p.p23 = $p20 OR p.p23 = 0) 
              AND (p.p24 = $p1 OR p.p24 = $p2 OR p.p24 = $p3 OR p.p24 = $p4 OR p.p24 = $p5 OR p.p24 = $p6 OR p.p24 = $p7 OR p.p24 = $p8 OR p.p24 = $p9 OR p.p24 = $p10 OR p.p24 = $p11 OR p.p24 = $p12 OR p.p24 = $p13 OR p.p24 = $p14 OR p.p24 = $p15 OR p.p24 = $p16 OR p.p24 = $p17 OR p.p24 = $p18 OR p.p24 = $p19 OR p.p24 = $p20 OR p.p24 = 0) 
              AND (p.p25 = $p1 OR p.p25 = $p2 OR p.p25 = $p3 OR p.p25 = $p4 OR p.p25 = $p5 OR p.p25 = $p6 OR p.p25 = $p7 OR p.p25 = $p8 OR p.p25 = $p9 OR p.p25 = $p10 OR p.p25 = $p11 OR p.p25 = $p12 OR p.p25 = $p13 OR p.p25 = $p14 OR p.p25 = $p15 OR p.p25 = $p16 OR p.p25 = $p17 OR p.p25 = $p18 OR p.p25 = $p19 OR p.p25 = $p20 OR p.p25 = 0) 
              AND (p.p26 = $p1 OR p.p26 = $p2 OR p.p26 = $p3 OR p.p26 = $p4 OR p.p26 = $p5 OR p.p26 = $p6 OR p.p26 = $p7 OR p.p26 = $p8 OR p.p26 = $p9 OR p.p26 = $p10 OR p.p26 = $p11 OR p.p26 = $p12 OR p.p26 = $p13 OR p.p26 = $p14 OR p.p26 = $p15 OR p.p26 = $p16 OR p.p26 = $p17 OR p.p26 = $p18 OR p.p26 = $p19 OR p.p26 = $p20 OR p.p26 = 0) 
              AND (p.p27 = $p1 OR p.p27 = $p2 OR p.p27 = $p3 OR p.p27 = $p4 OR p.p27 = $p5 OR p.p27 = $p6 OR p.p27 = $p7 OR p.p27 = $p8 OR p.p27 = $p9 OR p.p27 = $p10 OR p.p27 = $p11 OR p.p27 = $p12 OR p.p27 = $p13 OR p.p27 = $p14 OR p.p27 = $p15 OR p.p27 = $p16 OR p.p27 = $p17 OR p.p27 = $p18 OR p.p27 = $p19 OR p.p27 = $p20 OR p.p27 = 0) 
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
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year, $month)
{
  global $db;
  $sql  = "SELECT s.qty,s.destination,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,p.partNo,";
  $sql .= "SUM(s.total_sale) AS total_saleing_price,";
  $sql .= "SUM(s.qty) AS total_qty,";
  $sql .= "SUM(s.profit) AS total_profit";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  $sql .= " ORDER BY DATE_FORMAT(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year)
{
  global $db;
  $sql  = "SELECT s.qty,s.destination,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,p.partNo,";
  $sql .= "SUM(s.total_sale) AS total_saleing_price,";
  $sql .= "SUM(s.qty) AS total_qty,";
  $sql .= "SUM(s.profit) AS total_profit";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
  $sql .= " ORDER BY DATE_FORMAT(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}
