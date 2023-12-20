<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Empleado</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <!--
        Alta Empleado 
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
        <legend>Alta Empleado</legend>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nombre">Nombre</label>    
        <input type="text" name="nombre" id="nombre" required><br><br> 
        <label for="apellido">Apellido</label>    
        <input type="text" name="apellido" id="apellido" required><br><br> 
        <label for="dni">DNI</label>    
        <input type="text" name="dni" id="dni" maxlength="9" required><br><br>
        <?php
            include('funciones.php');
            $conn=connection();
            $stmt0 = $conn->prepare("SELECT nombre_dpto,cod_dpto from departamento;");
            $stmt0->execute();
            $arrayCat=$stmt0->FetchAll(PDO::FETCH_ASSOC);
            desplegableDpto($arrayCat);

        ?>
        <label for="salario">Salario</label>    
        <input type="text" name="salario" id="salario" required><br><br>
        <label for="f_nac">Fecha Nacimiento</label>    
        <input type="date" name="f_nac" id="f_nac" required><br><br>
        <input type="submit">
        <input type="reset">
    </form>
    </fieldset>
    <?php
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            try {
                $nom_emple=strtoupper(test_input($_POST['nombre']));
                $ap_emple=strtoupper(test_input($_POST['apellido']));
                $dni=test_input($_POST['dni']);
                $cod_dpto=test_input($_POST['id_dpto']);
                $salario=test_input($_POST['salario']);
                $f_nac=test_input($_POST['f_nac']);
                if(!validarNIF($dni)){
                    throw new Exception ('El DNI introducido no es correcto');
                }
                $stmt1 = $conn->prepare("SELECT dni from empleado Where dni=:dni;");
                $stmt1->bindParam(':dni', $dni);
                $stmt1->execute();
                $BoolDNI=$stmt1->fetchColumn();
                if(empty($BoolDNI)){
                    altaEmpleadoStm($conn,$dni,$nom_emple,$ap_emple,$f_nac,$salario,$cod_dpto);
                }else{
                    throw new Exception ('El DNI ya está registrado en la base de datos <br> Si quiere cambiar el departamento pulse <a href="empcambiodpto.php">aqui</a>. ');
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