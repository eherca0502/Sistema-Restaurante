<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$sql = "SELECT

ordenes.*,
mesas.numero_mesa

FROM ordenes

LEFT JOIN mesas
ON ordenes.mesa_id = mesas.id

WHERE ordenes.estado != 'entregada'

ORDER BY ordenes.id DESC";

$stmt = $conexion->prepare($sql);

$stmt->execute();

$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($ordenes as $o):

?>

<div class="card mb-3 shadow border-0">

<div class="card-body">

<h4>

<?php echo $o['folio']; ?>

</h4>

<?php if($o['mesa_id']): ?>

<p>

Mesa:
<strong>

<?php echo $o['numero_mesa']; ?>

</strong>

</p>

<?php endif; ?>

<p>

Estado:

<?php

if($o['estado'] == 'pendiente'){

    echo '<span class="badge bg-warning text-dark">
    Pendiente
    </span>';

}elseif($o['estado'] == 'preparando'){

    echo '<span class="badge bg-primary">
    Preparando
    </span>';

}elseif($o['estado'] == 'lista'){

    echo '<span class="badge bg-success">
    Lista
    </span>';
}

?>

</p>

</div>

</div>

<?php endforeach; ?>