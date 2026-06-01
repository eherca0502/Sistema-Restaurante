<?php

session_start();

if(isset($_SESSION['usuario_id'])){

    if($_SESSION['rol'] == 'admin'){

        header("Location: admin/dashboard.php");

    }elseif($_SESSION['rol'] == 'cajero'){

        header("Location: cajero/dashboard.php");

    }elseif($_SESSION['rol'] == 'cocina'){

        header("Location: cocina/dashboard.php");

    }elseif($_SESSION['rol'] == 'mesero'){

        header("Location: mesero/dashboard.php");
    }

    exit();
}

require_once "config/database.php";

$sql = "SELECT * FROM configuracion LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->execute();

$config = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Pizzeria La Ermita</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

    background: linear-gradient(135deg, #111827, #1f2937);
    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    font-family:'Segoe UI', sans-serif;
}

.login-card{

    background:white;
    border-radius:25px;

    padding:40px;

    width:100%;
    max-width:420px;

    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.logo{

    width:280px;
    max-width:100%;
    height:auto;

    margin-bottom:20px;

    object-fit:contain;
}

.title{

    font-size:32px;
    font-weight:bold;
    color:#111827;
}

.subtitle{

    color:#6b7280;
    margin-bottom:30px;
}

.form-control{

    border-radius:12px;
    padding:12px;
}

.btn-login{

    background:#ea580c;
    border:none;

    border-radius:12px;

    padding:12px;

    color:white;

    font-weight:bold;

    transition:0.3s;
}

.btn-login:hover{

    background:#c2410c;
}

.footer{

    margin-top:25px;
    font-size:14px;
    color:#9ca3af;
}

</style>

</head>

<body>

<div class="login-card text-center">

<?php if(!empty($config['logo'])): ?>

<img src="assets/img/<?php echo $config['logo']; ?>" class="logo" alt="Logo">

<?php else: ?>

<div style="font-size:80px; color:#ea580c;">
🍕
</div>

<?php endif; ?>

<h1 class="title">

<?php

if(!empty($config['nombre_restaurante'])){
    echo $config['nombre_restaurante'];
}else{
    echo "Pizzeria La Ermita";
}

?>

</h1>

<p class="subtitle">
Sistema Punto de Venta
</p>


<?php if(isset($_SESSION['error_login'])): ?>
<div id="errorBox" class="alert alert-danger">
    <?php 
        echo $_SESSION['error_login']; 
        unset($_SESSION['error_login']);
    ?>
</div>

<script>
    setTimeout(() => {
        const box = document.getElementById("errorBox");
        if(box){
            box.style.display = "none";
        }
    }, 3000);
</script>
<?php endif; ?>

<form action="login.php" method="POST">

<div class="mb-3 text-start">

<label>Usuario</label>

<input type="text" name="usuario" class="form-control" required>

</div>

<div class="mb-4 text-start">

<label>Contraseña</label>

<input type="password" name="password" class="form-control" required>

</div>

<button class="btn btn-login w-100">

<i class="fa fa-right-to-bracket"></i>
Ingresar

</button>

</form>

<div class="footer">

© 2026 Derechos Reservados | Realizado por InfoFix

</div>

</div>

</body>

</html>