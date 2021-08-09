<?php
include 'plantilla.php';
require 'conexion.php';
require 'load.php';



if (isset($_POST['submit'])) {
    $req_fields = array('n_desde', 'n_hasta', 'accion', 'vendedor', 'modulo', 'date');
    validate_fields($req_fields);
    if (empty($errors)) {
        $n_desde  = remove_junk($db->escape($_POST['desde']));
        $n_hasta  = remove_junk($db->escape($_POST['hasta']));
        $vendedor  = remove_junk($db->escape($_POST['vendedor']));
        $img  = remove_junk($db->escape($_POST['img']));
        $accion   = "generar pdf";
        $modulo = remove_junk($db->escape($_POST['modulo']));

        $date = date("d/m/Y  g:i a");

        $date2 = remove_junk($db->escape($_POST['date']));

        $date    = make_date();
        $query  = "INSERT INTO logs (";
        $query .= " n_desde,n_hasta,date2,accion,vendedor,date,modulo,img";
        $query .= ") VALUES (";
        $query .= " '{$n_desde}', '${n_hasta}','{$date2}', '{$accion}', '{$vendedor}','{$date}','{$modulo}','{$img}'";
        $query .= ")";
        if ($db->query($query)) {
            $session->msg('s', "Generado exitosamente. ");
        }
    }
}



if (isset($_POST['submit'])) {
    $req_dates = array('start-date', 'end-date', 'vendedor');
    validate_fields($req_dates);

    if (empty($errors)) :
        $desde = remove_junk($db->escape($_POST['desde']));
        $hasta = remove_junk($db->escape($_POST['hasta']));
        $vendedor = remove_junk($db->escape($_POST['vendedor']));
        $vendedores = false;
        $modulo = remove_junk($db->escape($_POST['modulo']));
        $img_ruta = remove_junk($db->escape($_POST['img']));
    else :
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    endif;
} else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
}


$sql  = " SELECT *";
$sql .= " FROM series p ";
$sql .= " JOIN cartones c ON (p.carton = c.carton) AND (p.modulo = c.modulo)";
$sql .= " WHERE (p.modulo = $modulo) AND (p.serie BETWEEN $desde AND $hasta) ";
$sql .= " ORDER BY c.id ASC";

$resultado = $mysqli->query($sql);

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFillColor(232, 232, 232);
$pdf->SetFont('Arial', 'B', 10.5);

foreach ($resultado as $row) {

    if ($row['modulo']==0) {
        $cuarto = 0;
    }
    if ($row['modulo']==1) {
        $cuarto = 1;
    }
    if ($row['modulo']==2) {
        $cuarto = 2;
    }
    if ($row['modulo']==3) {
        $cuarto = 3;
    }
    if ($row['modulo']==4) {
        $cuarto = 4;
    }
    if ($row['modulo']==5) {
        $cuarto = 5;
    }
    if ($row['modulo']==6) {
        $cuarto = 6;
    }
    if ($row['modulo']==7) {
        $cuarto = 7;
    }
    if ($row['modulo']==8) {
        $cuarto = 8;
    }
    if ($row['modulo']==9) {
        $cuarto = 9;
    }

    if ($row['p1'] == 0) {
        $gris1 = 1;
    } else {
        $gris1 = 0;
    }
    if ($row['p2'] == 0) {
        $gris2 = 1;
    } else {
        $gris2 = 0;
    }
    if ($row['p3'] == 0) {
        $gris3 = 1;
    } else {
        $gris3 = 0;
    }
    if ($row['p4'] == 0) {
        $gris4 = 1;
    } else {
        $gris4 = 0;
    }
    if ($row['p5'] == 0) {
        $gris5 = 1;
    } else {
        $gris5 = 0;
    }
    if ($row['p6'] == 0) {
        $gris6 = 1;
    } else {
        $gris6 = 0;
    }
    if ($row['p7'] == 0) {
        $gris7 = 1;
    } else {
        $gris7 = 0;
    }
    if ($row['p8'] == 0) {
        $gris8 = 1;
    } else {
        $gris8 = 0;
    }
    if ($row['p9'] == 0) {
        $gris9 = 1;
    } else {
        $gris9 = 0;
    }
    if ($row['p10'] == 0) {
        $gris10 = 1;
    } else {
        $gris10 = 0;
    }
    if ($row['p11'] == 0) {
        $gris11 = 1;
    } else {
        $gris11 = 0;
    }
    if ($row['p12'] == 0) {
        $gris12 = 1;
    } else {
        $gris12 = 0;
    }
    if ($row['p13'] == 0) {
        $gris13 = 1;
    } else {
        $gris13 = 0;
    }
    if ($row['p14'] == 0) {
        $gris14 = 1;
    } else {
        $gris14 = 0;
    }
    if ($row['p15'] == 0) {
        $gris15 = 1;
    } else {
        $gris15 = 0;
    }
    if ($row['p16'] == 0) {
        $gris16 = 1;
    } else {
        $gris16 = 0;
    }
    if ($row['p17'] == 0) {
        $gris17 = 1;
    } else {
        $gris17 = 0;
    }
    if ($row['p18'] == 0) {
        $gris18 = 1;
    } else {
        $gris18 = 0;
    }
    if ($row['p19'] == 0) {
        $gris19 = 1;
    } else {
        $gris19 = 0;
    }
    if ($row['p20'] == 0) {
        $gris20 = 1;
    } else {
        $gris20 = 0;
    }
    if ($row['p21'] == 0) {
        $gris21 = 1;
    } else {
        $gris21 = 0;
    }
    if ($row['p22'] == 0) {
        $gris22 = 1;
    } else {
        $gris22 = 0;
    }
    if ($row['p23'] == 0) {
        $gris23 = 1;
    } else {
        $gris23 = 0;
    }
    if ($row['p24'] == 0) {
        $gris24 = 1;
    } else {
        $gris24 = 0;
    }
    if ($row['p25'] == 0) {
        $gris25 = 1;
    } else {
        $gris25 = 0;
    }
    if ($row['p26'] == 0) {
        $gris26 = 1;
    } else {
        $gris26 = 0;
    }
    if ($row['p27'] == 0) {
        $gris27 = 1;
    } else {
        $gris27 = 0;
    }

    if ($row['carton'] % 2 == 0 || $row['carton'] == 0) {

        $pdf->ln(7);



        if ($vendedores == false) {

            $pdf->SetFont('Arial', 'B', 12);


            $pdf->Cell(10.5, 5, 'Serie', 1, 0, 'C');
            $pdf->Cell(10.5, 5, $row['serie'], 1, 0, 'C');

            $pdf->Cell(21, 5, 'Carton', 1, 0, 'C', 1);
            $pdf->Cell(21, 5, $cuarto.str_pad($row['carton'], 3, "0", STR_PAD_LEFT), 1, 0, 'C', 1);

            //$pdf->Cell(10.5, 5, 'Item', 1, 0, 'C');
            //$pdf->Cell(10.5, 5, $row['id'], 1, 0, 'C');


            $pdf->Cell(42, 5, "", 1, 0, 'C');
            $pdf->Cell(21, 5, "Fecha", 1, 0, 'C');
            $pdf->Cell(21, 5, $date2, 1, 0, 'C');


            $pdf->Cell(42, 5, $vendedor, 1, 1, 'C');

            $vendedores = true;
        } else {
            $pdf->Cell(21, 5, "", 0, 1, 'C', 0);
        }


        $pdf->image("img/$img_ruta", 190, 0, 15, 0, 'png');
        $pdf->SetFont('Arial', 'B', 20);


        $pdf->Cell(21, 10.5, $row['p1'], 1, 0, 'C', $gris1);
        $pdf->Cell(21, 10.5, $row['p2'], 1, 0, 'C', $gris2);
        $pdf->Cell(21, 10.5, $row['p3'], 1, 0, 'C', $gris3);
        $pdf->Cell(21, 10.5, $row['p4'], 1, 0, 'C', $gris4);
        $pdf->Cell(21, 10.5, $row['p5'], 1, 0, 'C', $gris5);
        $pdf->Cell(21, 10.5, $row['p6'], 1, 0, 'C', $gris6);
        $pdf->Cell(21, 10.5, $row['p7'], 1, 0, 'C', $gris7);
        $pdf->Cell(21, 10.5, $row['p8'], 1, 0, 'C', $gris8);
        $pdf->Cell(21, 10.5, $row['p9'], 1, 1, 'C', $gris9);

        $pdf->Cell(21, 10.5, $row['p10'], 1, 0, 'C', $gris10);
        $pdf->Cell(21, 10.5, $row['p11'], 1, 0, 'C', $gris11);
        $pdf->Cell(21, 10.5, $row['p12'], 1, 0, 'C', $gris12);
        $pdf->Cell(21, 10.5, $row['p13'], 1, 0, 'C', $gris13);
        $pdf->Cell(21, 10.5, $row['p14'], 1, 0, 'C', $gris14);
        $pdf->Cell(21, 10.5, $row['p15'], 1, 0, 'C', $gris15);
        $pdf->Cell(21, 10.5, $row['p16'], 1, 0, 'C', $gris16);
        $pdf->Cell(21, 10.5, $row['p17'], 1, 0, 'C', $gris17);
        $pdf->Cell(21, 10.5, $row['p18'], 1, 1, 'C', $gris18);

        $pdf->Cell(21, 10.5, $row['p19'], 1, 0, 'C', $gris19);
        $pdf->Cell(21, 10.5, $row['p20'], 1, 0, 'C', $gris20);
        $pdf->Cell(21, 10.5, $row['p21'], 1, 0, 'C', $gris21);
        $pdf->Cell(21, 10.5, $row['p22'], 1, 0, 'C', $gris22);
        $pdf->Cell(21, 10.5, $row['p23'], 1, 0, 'C', $gris23);
        $pdf->Cell(21, 10.5, $row['p24'], 1, 0, 'C', $gris24);
        $pdf->Cell(21, 10.5, $row['p25'], 1, 0, 'C', $gris25);
        $pdf->Cell(21, 10.5, $row['p26'], 1, 0, 'C', $gris26);
        $pdf->Cell(21, 10.5, $row['p27'], 1, 1, 'C', $gris27);
    } else {

        $pdf->ln(7.5);

        if ($vendedores == true) {
            $pdf->SetFont('Arial', 'B', 12);

            $pdf->Cell(10.5, 5, 'Serie', 1, 0, 'C');
            $pdf->Cell(10.5, 5, $row['serie'], 1, 0, 'C');

            $pdf->Cell(21, 5, 'Carton', 1, 0, 'C', 1);
            $pdf->Cell(21, 5,  $cuarto.str_pad($row['carton'], 3, "0", STR_PAD_LEFT), 1, 0, 'C', 1);

            //$pdf->Cell(10.5, 5, 'Item', 1, 0, 'C');
            //$pdf->Cell(10.5, 5, $row['id'], 1, 0, 'C');


            $pdf->Cell(42, 5, "", 1, 0, 'C');
            $pdf->Cell(21, 5, "Fecha", 1, 0, 'C');
            $pdf->Cell(21, 5, $date2, 1, 0, 'C');

            $pdf->Cell(42, 5, $vendedor, 1, 1, 'C');

            $vendedores = false;
        } else {
            $pdf->Cell(21, 5, "", 0, 1, 'C', 0);
        }

        $pdf->image("img/$img_ruta", 0, 140, 15, 0, 'png');

        $pdf->SetFont('Arial', 'B', 20);

        $pdf->Cell(21, 10.5, $row['p1'], 1, 0, 'C', $gris1);
        $pdf->Cell(21, 10.5, $row['p2'], 1, 0, 'C', $gris2);
        $pdf->Cell(21, 10.5, $row['p3'], 1, 0, 'C', $gris3);
        $pdf->Cell(21, 10.5, $row['p4'], 1, 0, 'C', $gris4);
        $pdf->Cell(21, 10.5, $row['p5'], 1, 0, 'C', $gris5);
        $pdf->Cell(21, 10.5, $row['p6'], 1, 0, 'C', $gris6);
        $pdf->Cell(21, 10.5, $row['p7'], 1, 0, 'C', $gris7);
        $pdf->Cell(21, 10.5, $row['p8'], 1, 0, 'C', $gris8);
        $pdf->Cell(21, 10.5, $row['p9'], 1, 1, 'C', $gris9);

        $pdf->Cell(21, 10.5, $row['p10'], 1, 0, 'C', $gris10);
        $pdf->Cell(21, 10.5, $row['p11'], 1, 0, 'C', $gris11);
        $pdf->Cell(21, 10.5, $row['p12'], 1, 0, 'C', $gris12);
        $pdf->Cell(21, 10.5, $row['p13'], 1, 0, 'C', $gris13);
        $pdf->Cell(21, 10.5, $row['p14'], 1, 0, 'C', $gris14);
        $pdf->Cell(21, 10.5, $row['p15'], 1, 0, 'C', $gris15);
        $pdf->Cell(21, 10.5, $row['p16'], 1, 0, 'C', $gris16);
        $pdf->Cell(21, 10.5, $row['p17'], 1, 0, 'C', $gris17);
        $pdf->Cell(21, 10.5, $row['p18'], 1, 1, 'C', $gris18);

        $pdf->Cell(21, 10.5, $row['p19'], 1, 0, 'C', $gris19);
        $pdf->Cell(21, 10.5, $row['p20'], 1, 0, 'C', $gris20);
        $pdf->Cell(21, 10.5, $row['p21'], 1, 0, 'C', $gris21);
        $pdf->Cell(21, 10.5, $row['p22'], 1, 0, 'C', $gris22);
        $pdf->Cell(21, 10.5, $row['p23'], 1, 0, 'C', $gris23);
        $pdf->Cell(21, 10.5, $row['p24'], 1, 0, 'C', $gris24);
        $pdf->Cell(21, 10.5, $row['p25'], 1, 0, 'C', $gris25);
        $pdf->Cell(21, 10.5, $row['p26'], 1, 0, 'C', $gris26);
        $pdf->Cell(21, 10.5, $row['p27'], 1, 1, 'C', $gris27);
    }
}


$pdf->Output();
