<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>
    <?php if (!empty($page_title))
      echo remove_junk($page_title);
    elseif (!empty($user))
      echo ucfirst($user['name']);
    else echo ""; ?>
  </title>

  <!-- Bootstrap -->
  <!--
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css"/>-->

  <!-- cached version -->
  <link rel="stylesheet" href="libs/css/bootstrap.min.css" />
  <link rel="stylesheet" href="libs/css/datepicker3.min.css" />

  <script type="text/javascript" src="libs/js/jquery-3.5.1.js"></script>


</head>