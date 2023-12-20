<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relación Fecha</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <!--
        Relación Fecha
    -->
    <nav>
        <ul>
            <li><a href="empaltadpto.php">Alta Departamento</a></li>
            <li><a href="empaltaemp.php">Alta Empleado</a></li>
            <li><a href="empcambiodpto.php">Cambio Departamento</a></li>
            <li><a href="emplistadpto.php">Empleados de Departamento Actual</a></li>
            <li><a href="emphistdpto.php">Empleados de Departamento Histórico</a></li>
            <li><a href="empsalarioemp.php">Actualizar Salario</a></li>
            <li><a href="empfecha.php">Relacion Fecha</a></li>
        </ul>
    </nav>
    <fieldset>
        <legend>Relación Fecha</legend>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="fecha">Introduzca la fecha: </label>
        <input type="date" name="fecha" id="fecha" required><br><br> 
        <input type="submit">
        <input type="reset">
    </form>
    </fieldset>
    <?php
    include('funciones.php');
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            try {
                $conn=connection();
                $fecha=test_input($_POST['fecha']);
                $arrayFecha=empleadoFecha($conn,$fecha);
                if(empty($arrayFecha)){
                    throw new Exception("No hay datos relacionados con la fecha introducida");
                }else{
                    imprimirTablaEmpleadoFecha($arrayFecha);
                }
            }catch(PDOException $e){
                echo "Error: " . $e->getMessage();
            }catch(Exception $e){
                echo "Error: " . $e->getMessage();
            }
            $conn = null;
        }
    ?>
</body>
</body>
</html>