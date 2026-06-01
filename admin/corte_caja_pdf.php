<?php

require_once "../includes/auth.php";
require_once "../config/database.php";
require_once "../lib/fpdf/fpdf.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}

date_default_timezone_set('America/Mexico_City');

$fecha = isset($_GET['fecha'])
? $_GET['fecha']
: date('Y-m-d');




$sqlConfig = "SELECT * FROM configuracion LIMIT 1";

$stmtConfig = $conexion->prepare($sqlConfig);

$stmtConfig->execute();

$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);




$sqlOrdenes = "

SELECT *

FROM ordenes

WHERE DATE(fecha)=:fecha

ORDER BY id DESC

";

$stmtOrdenes = $conexion->prepare($sqlOrdenes);

$stmtOrdenes->execute([

    ':fecha'=>$fecha

]);

$ordenes = $stmtOrdenes->fetchAll(PDO::FETCH_ASSOC);




$sqlPagos = "

SELECT

pagos.*,
mesas.numero_mesa

FROM pagos

LEFT JOIN comandas
ON pagos.comanda_id = comandas.id

LEFT JOIN mesas
ON comandas.mesa_id = mesas.id

WHERE DATE(pagos.fecha)=:fecha

ORDER BY pagos.id DESC

";

$stmtPagos = $conexion->prepare($sqlPagos);

$stmtPagos->execute([

    ':fecha'=>$fecha

]);

$pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);




$totalVentas = 0;
$totalEfectivo = 0;
$totalTarjeta = 0;
$totalOrdenes = 0;
$gananciaEstimada = 0;

foreach($ordenes as $o){

    $totalVentas += $o['total'];
    $totalOrdenes++;

    if($o['metodo_pago'] == 'efectivo'){

        $totalEfectivo += $o['total'];
    }

    if($o['metodo_pago'] == 'tarjeta'){

        $totalTarjeta += $o['total'];
    }
}

foreach($pagos as $p){

    $totalVentas += $p['total'];
    $totalOrdenes++;

    if($p['metodo_pago'] == 'efectivo'){

        $totalEfectivo += $p['total'];
    }

    if($p['metodo_pago'] == 'tarjeta'){

        $totalTarjeta += $p['total'];
    }
}




$sqlProductos = "

SELECT
SUM(cd.cantidad * p.precio) as total

FROM comanda_detalle cd

INNER JOIN productos p
ON cd.producto_id = p.id

INNER JOIN comandas c
ON cd.comanda_id = c.id

WHERE DATE(c.fecha)=:fecha

";

$stmtProductos = $conexion->prepare($sqlProductos);

$stmtProductos->execute([

    ':fecha'=>$fecha

]);

$gananciaComandas = $stmtProductos->fetch(PDO::FETCH_ASSOC);

$gananciaEstimada += $gananciaComandas['total'] ?? 0;

$gananciaEstimada += $totalVentas * 0.35;



class PDF extends FPDF{

    function Header(){

        global $config;
        global $fecha;

        /* FONDO */
        $this->SetFillColor(33,37,41);

        $this->Rect(0,0,220,32,'F');


        /* LOGO */
        if(!empty($config['logo'])){

            $rutaLogo =
            __DIR__ .
            "/../assets/img/" .
            $config['logo'];

            if(file_exists($rutaLogo)){

                $this->Image(
                    $rutaLogo,
                    80,
                    5,
                    45
                );
            }
        }

        $this->Ln(30);
    }

    function Footer(){

        $this->SetY(-15);

        $this->SetFont(
            'Arial',
            'I',
            9
        );

        $this->SetTextColor(
            120,
            120,
            120
        );

        $this->Cell(
            0,
            10,
            utf8_decode(
                "Sistema La Ermita POS | Página "
            ).
            $this->PageNo(),
            0,
            0,
            'C'
        );
    }
}

$pdf = new PDF();

$pdf->AddPage();

$pdf->SetAutoPageBreak(true,20);




$pdf->SetFont(
    'Arial',
    'B',
    22
);

$pdf->SetTextColor(33,37,41);

$pdf->Cell(
    0,
    10,
    utf8_decode("CORTE DE CAJA"),
    0,
    1,
    'C'
);

$pdf->SetFont(
    'Arial',
    '',
    12
);

$pdf->SetTextColor(100,100,100);

$pdf->Cell(
    0,
    8,
    utf8_decode("Fecha: ".$fecha),
    0,
    1,
    'C'
);

$pdf->Ln(8);




$pdf->SetFillColor(255,255,255);


$pdf->SetFont('Arial','B',13);
$pdf->SetTextColor(0,0,0);

$ancho = 47;

$yInicial = $pdf->GetY();


$pdf->Cell($ancho,10,"Ventas",0,0,'C');
$pdf->Cell($ancho,10,"Efectivo",0,0,'C');
$pdf->Cell($ancho,10,"Tarjeta",0,0,'C');
$pdf->Cell($ancho,10,utf8_decode("Órdenes"),0,1,'C');


$pdf->SetFont('Arial','B',18);

$pdf->SetTextColor(39,174,96);
$pdf->Cell($ancho,14,"$".number_format($totalVentas,2),0,0,'C');

$pdf->SetTextColor(41,128,185);
$pdf->Cell($ancho,14,"$".number_format($totalEfectivo,2),0,0,'C');

$pdf->SetTextColor(231,76,60);
$pdf->Cell($ancho,14,"$".number_format($totalTarjeta,2),0,0,'C');

$pdf->SetTextColor(243,156,18);
$pdf->Cell($ancho,14,$totalOrdenes,0,1,'C');

$pdf->Ln(10);



$pdf->SetFont('Arial','B',13);

$pdf->SetTextColor(25,135,84);

$pdf->Cell(
0,
10,
utf8_decode(
"Ganancia estimada: $".
number_format($gananciaEstimada,2)
),
0,
1
);

$pdf->Ln(5);



$pdf->SetFont('Arial','B',13);

$pdf->SetTextColor(33,37,41);

$pdf->Cell(
0,
10,
utf8_decode("ÓRDENES LLEVAR / DOMICILIO"),
0,
1
);

$pdf->SetFillColor(220,53,69);

$pdf->SetTextColor(255,255,255);

$pdf->SetFont('Arial','B',11);

$pdf->Cell(35,10,"Folio",1,0,'C',true);

$pdf->Cell(35,10,"Tipo",1,0,'C',true);

$pdf->Cell(35,10,"Pago",1,0,'C',true);

$pdf->Cell(35,10,"Total",1,0,'C',true);

$pdf->Cell(50,10,"Fecha",1,1,'C',true);

$pdf->SetFont('Arial','',10);

$pdf->SetTextColor(0,0,0);

foreach($ordenes as $o){

    $pdf->Cell(35,8,$o['folio'],1);

    $pdf->Cell(35,8,ucfirst($o['tipo_orden']),1);

    $pdf->Cell(35,8,ucfirst($o['metodo_pago']),1);

    $pdf->Cell(
        35,
        8,
        "$".number_format($o['total'],2),
        1
    );

    $pdf->Cell(50,8,$o['fecha'],1);

    $pdf->Ln();
}

$pdf->Ln(8);




$pdf->SetFont('Arial','B',13);

$pdf->SetTextColor(33,37,41);

$pdf->Cell(
0,
10,
utf8_decode("COMANDAS COBRADAS"),
0,
1
);

$pdf->SetFillColor(25,135,84);

$pdf->SetTextColor(255,255,255);

$pdf->SetFont('Arial','B',11);

$pdf->Cell(40,10,"Mesa",1,0,'C',true);

$pdf->Cell(40,10,"Pago",1,0,'C',true);

$pdf->Cell(40,10,"Total",1,0,'C',true);

$pdf->Cell(60,10,"Fecha",1,1,'C',true);

$pdf->SetFont('Arial','',10);

$pdf->SetTextColor(0,0,0);

foreach($pagos as $p){

    $pdf->Cell(
        40,
        8,
        "Mesa ".$p['numero_mesa'],
        1
    );

    $pdf->Cell(
        40,
        8,
        ucfirst($p['metodo_pago']),
        1
    );

    $pdf->Cell(
        40,
        8,
        "$".number_format($p['total'],2),
        1
    );

    $pdf->Cell(
        60,
        8,
        $p['fecha'],
        1
    );

    $pdf->Ln();
}




$pdf->Ln(10);

$pdf->SetFont(
    'Arial',
    'I',
    10
);

$pdf->SetTextColor(
    100,
    100,
    100
);

$pdf->MultiCell(
    0,
    6,
    utf8_decode(
        $config['mensaje_ticket']
    ),
    0,
    'C'
);

$pdf->Output();

?>