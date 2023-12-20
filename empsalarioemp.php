<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Salario</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <!--
        Actualizar Salario
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
        <legend>Actualizar Salario</legend>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <?php
            include('funciones.php');
            $conn=connection();
            $stmt01 = $conn->prepare("SELECT dni from empleado;");
            $stmt01->execute();
            $arrayDNI=$stmt01->FetchAll(PDO::FETCH_ASSOC);
            desplegableDNI($arrayDNI);
        ?>
        <label for="signo">Incremento / Decremento Salario</label>
        <select name="signo" id="signo">
            <option value="+1">+</option>
            <option value="-1">-</option>
        </select>
        <label for="mod_salario">
        <input type="text" name="mod_salario" id="mod_salario" required size="5"> %<br><br> 
        <input type="submit">
        <input type="reset">
    </form>
    </fieldset>
    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            try {
                $dni=test_input($_POST['dni']);
                $mod_salario=test_input($_POST['mod_salario']);

                if (!preg_match('/^(\d+|\d*\.\d+)$/', $mod_salario)) {
                    throw new Exception("El valor introducido no está en el formato corrrecto.
                    <br>Tiene que ser Número decimal positivo.");
                } 

                $signo=test_input($_POST['signo']);
                
                $salarioActual=salario($conn,$dni);

                $salarioNuevo=$salarioActual+($salarioActual*$mod_salario*$signo/100);

                $salarioNuevo=bcdiv($salarioNuevo, '1', 2);

                salarioNuevoStm($conn,$dni,$salarioNuevo);

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