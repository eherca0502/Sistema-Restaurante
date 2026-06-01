<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$fecha = isset($_GET['fecha'])
? $_GET['fecha']
: date('Y-m-d');


$sql = "

SELECT

a.*,
u.nombre,
u.rol

FROM asistencia a

INNER JOIN usuarios u
ON a.usuario_id = u.id

WHERE a.fecha = :fecha

ORDER BY a.hora_entrada ASC

";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    ':fecha' => $fecha

]);

$asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_admin.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<div
class="p-4 rounded shadow mb-4 text-white"
style="
background: linear-gradient(
135deg,
#111827,
#1f2937,
#374151
);
">

<h1 class="fw-bold">

 Control de Asistencia

</h1>

<p class="mb-0">

Entradas y salidas de empleados.

</p>

</div>


<form method="GET" class="mb-4">

<div class="row">

<div class="col-md-4">

<input
type="date"
name="fecha"
value="<?= $fecha; ?>"
class="form-control">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

Filtrar

</button>

</div>

</div>

</form>


<div class="card shadow border-0">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Empleado</th>
<th>Rol</th>
<th>Fecha</th>
<th>Entrada</th>
<th>Salida</th>
<th>Horas</th>

</tr>

</thead>

<tbody>

<?php if(count($asistencias) > 0): ?>

<?php foreach($asistencias as $a): ?>

<?php

$horasTrabajadas = "-";

if(
    !empty($a['hora_entrada']) &&
    !empty($a['hora_salida'])
){

    $entrada = strtotime($a['hora_entrada']);
    $salida = strtotime($a['hora_salida']);

    $diferencia = $salida - $entrada;

    $horas = floor($diferencia / 3600);
    $minutos = floor(($diferencia % 3600) / 60);

    $horasTrabajadas =
    $horas . "h "
    . $minutos . "m";
}

?>

<tr>

<td>

<?= $a['nombre']; ?>

</td>

<td>

<span class="badge bg-info">

<?= ucfirst($a['rol']); ?>

</span>

</td>

<td>

<?= $a['fecha']; ?>

</td>

<td class="text-success fw-bold">

<?= $a['hora_entrada']; ?>

</td>

<td class="text-danger fw-bold">

<?= $a['hora_salida']
? $a['hora_salida']
: 'Trabajando'; ?>

</td>

<td>

<?= $horasTrabajadas; ?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center">

No hay asistencias registradas.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>