<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Departamento</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <!--
        Alta Departamento 
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
        <legend>Alta Departamento</legend>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nom_dpto">Nombre Departamento</label>    
        <input type="text" name="nom_dpto" id="nom_dpto" required><br><br> 
        <input type="submit">
        <input type="reset">
    </form>
    </fieldset>
    <?php
    include('funciones.php');
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            try {
                $conn=connection();
                $nom_dpto=strtoupper(test_input($_POST['nom_dpto']));


                $stmt0 = $conn->prepare("SELECT nombre_dpto from departamento;");
                $stmt0->execute();
                $arrNombres=$stmt0->FetchAll(PDO::FETCH_COLUMN);

                if(in_array($nom_dpto,$arrNombres)){
                    throw new Exception("El departamento ya ha sido introducido");
                }

                $stmt1 = $conn->prepare("SELECT MAX(cod_dpto) from departamento;");
                $stmt1->execute();
                $CodMax=$stmt1->fetchColumn();

                $cod_dpto=generarCodDpto($CodMax);

                introducirDptoStm($conn,$cod_dpto,$nom_dpto);

                echo "Se han introducido los datos correctamente";
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