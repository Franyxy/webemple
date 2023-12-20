<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambio Departamento </title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <!--
        Cambio Departamento 
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
        <legend>Cambio Departamento </legend>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <?php
            include('funciones.php');
            $conn=connection();
            $stmt01 = $conn->prepare("SELECT dni from empleado;");
            $stmt01->execute();
            $arrayDNI=$stmt01->FetchAll(PDO::FETCH_ASSOC);
            desplegableDNI($arrayDNI);

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
                $dni=test_input($_POST['dni']);
                $cod_dpto=test_input($_POST['id_dpto']);


                $stmt1 = $conn->prepare("SELECT dni from emple_depart Where dni=:dni AND cod_dpto=:cod_dpto AND fecha_fin IS NULL;");
                $stmt1->bindParam(':dni', $dni);
                $stmt1->bindParam(':cod_dpto', $cod_dpto);
                $stmt1->execute();
                $BoolDept=$stmt1->fetchColumn();
                if(empty($BoolDept)){
                    cambioDptoStm($conn,$dni,$cod_dpto);
                }else{
                    throw new Exception ('No puede cambiar al empleado al departamento en el que ya se encuentra. <br> Eliga otro departamento');
                }
                echo "Se han introducido los datos correctamente";
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