<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados de Departamento Actual</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <!--
        Empleados de Departamento Actual
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
        <legend>Empleados de Departamento Actual</legend>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <?php
            include('funciones.php');
            $conn=connection();
            $stmt0 = $conn->prepare("SELECT nombre_dpto,cod_dpto from departamento;");
            $stmt0->execute();
            $arrayCat=$stmt0->FetchAll(PDO::FETCH_ASSOC);
            desplegableDpto($arrayCat);
        ?>
        <input type="submit">
        <input type="reset">
    </form>
    </fieldset>
    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            try {
                $cod_dpto=test_input($_POST['id_dpto']);
                $arrayEmpleados=empleadoActualStm($conn,$cod_dpto);

                if(empty($arrayEmpleados)){
                    echo "<br>En el departamento seleccionado no se encuentra ningún empleado";
                }else{
                    imprimirTablaEmpleado($arrayEmpleados);

                }
                }
            catch(PDOException $e)
                {
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