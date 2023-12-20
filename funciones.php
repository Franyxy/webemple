<?php
    function validarNIF($nif){
        $pattern = "/^[XYZ]?\d{5,8}[A-Z]$/";
        $dni = strtoupper($nif);
        if(preg_match($pattern, $dni))
        {
            $number = substr($dni, 0, -1);
            $number = str_replace('X', 0, $number);
            $number = str_replace('Y', 1, $number);
            $number = str_replace('Z', 2, $number);
            $dni = substr($dni, -1, 1);
            $start = $number % 23;
            $letter = 'TRWAGMYFPDXBNJZSQVHLCKET';
            $letter = substr('TRWAGMYFPDXBNJZSQVHLCKET', $start, 1);
            if($letter != $dni)
            {
                throw new Exception ('Caracter de Control INCORRECTO');
                return false;
            } else {
                return true;
            }
        }else{
            throw new Exception ('DNI/NIE INCORRECTO // FORMATO INCORRECTO');
            return false;
        }
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function connection(){
        $servername = "localhost";
        $username = "root";
        $password = "rootroot";
        $dbname = "webemple";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    function generarCodDpto($CodMax){
        if($CodMax==null){
            $cod="1";
        }else{
            $cod=substr($CodMax,-3);
            $cod=$cod+1;
        }
        $cod_dpto='D'.str_pad($cod,3,"0",STR_PAD_LEFT);
        return $cod_dpto;
    }

    function introducirDptoStm($conn,$cod_dpto,$nom_dpto){
        $stmt2 = $conn->prepare("INSERT INTO departamento (cod_dpto,nombre_dpto) VALUES (:cod_dpto,:nom_dpto);");
        $stmt2->bindParam(':cod_dpto', $cod_dpto);
        $stmt2->bindParam(':nom_dpto', $nom_dpto);
        $stmt2->execute();
    }

    function desplegableDpto($arrayAssoc){
        echo '<label for="id_dpto">Elija Departamento </label>';
        echo '<select name="id_dpto" id="id_dpto">';
        foreach ($arrayAssoc as $unidad) {
            $nombre = $unidad['nombre_dpto'];
            $cod = $unidad['cod_dpto'];
            echo '<option value='.$cod.'>'.$nombre.'</option>';
        }
        echo '</select><br><br>';
    }
    

    function desplegableDNI($arrayAssoc){
        echo '<label for="dni">Elija DNI </label>';
        echo '<select name="dni" id="dni">';
        foreach ($arrayAssoc as $unidad) {
            $dni = $unidad['dni'];
            echo '<option value='.$dni.'>'.$dni.'</option>';
        }
        echo '</select><br><br>';
    }

    function empleadoActualStm($conn,$cod_dpto){
        $stmt1 = $conn->prepare("SELECT empleado.dni,nombre,apellidos,fecha_nac,salario FROM webemple.empleado INNER JOIN emple_depart on emple_depart.dni=empleado.dni AND emple_depart.cod_dpto=:cod_dpto AND emple_depart.fecha_fin IS NULL;");
        $stmt1->bindParam(':cod_dpto', $cod_dpto);
        $stmt1->execute();
        $arrayEmpleados=$stmt1->FetchAll(PDO::FETCH_ASSOC);
        return $arrayEmpleados;
    }

    function empleadoHistStm($conn,$cod_dpto){
        $stmt1 = $conn->prepare("SELECT empleado.dni, nombre, apellidos, fecha_nac, salario,
        CASE 
            WHEN emple_depart.fecha_fin IS NULL THEN 'SI'
            ELSE 'NO'
        END AS activo
        FROM webemple.empleado
        INNER JOIN emple_depart ON emple_depart.dni = empleado.dni AND emple_depart.cod_dpto = :cod_dpto;");
        $stmt1->bindParam(':cod_dpto', $cod_dpto);
        $stmt1->execute();
        $arrayEmpleados=$stmt1->FetchAll(PDO::FETCH_ASSOC);
        return $arrayEmpleados;
    }

    function imprimirTablaEmpleado($arrayEmpleados){
        echo "<br><br><table>";
        echo "<tr><th>DNI</th><th>NOMBRE</th><th>APELLIDO</th><th>FECHA NACIMIENTO</th><th>SALARIO</th></tr>";
        foreach($arrayEmpleados as $empleado){
            $dni=$empleado['dni'];
            $nombre=$empleado['nombre'];
            $apellido=$empleado['apellidos'];
            $fecha_nac=$empleado['fecha_nac'];
            $salario=$empleado['salario'];
            echo "<tr><td>$dni</td><td>$nombre</td><td>$apellido</td><td>$fecha_nac</td><td>$salario</td></tr>";
        }
        echo "</table>";
    }
    function imprimirTablaEmpleadoHist($arrayEmpleados){
        echo "<br><br><table>";
        echo "<tr><th>DNI</th><th>NOMBRE</th><th>APELLIDO</th><th>FECHA NACIMIENTO</th><th>SALARIO</th><th>ACTIVO</th></tr>";
        foreach($arrayEmpleados as $empleado){
            $dni=$empleado['dni'];
            $nombre=$empleado['nombre'];
            $apellido=$empleado['apellidos'];
            $fecha_nac=$empleado['fecha_nac'];
            $salario=$empleado['salario'];
            $activo=$empleado['activo'];
            echo "<tr><td>$dni</td><td>$nombre</td><td>$apellido</td><td>$fecha_nac</td><td>$salario</td><td>$activo</td></tr>";
        }
        echo "</table>";
    }

    function empleadoFecha($conn,$fecha){
        $stmt1 = $conn->prepare("SELECT empleado.dni,empleado.nombre,empleado.apellidos,empleado.fecha_nac,
                departamento.nombre_dpto 
                    FROM empleado INNER JOIN emple_depart on empleado.dni=emple_depart.dni 
                    INNER JOIN departamento on emple_depart.cod_dpto=departamento.cod_dpto 
                    WHERE DATE(emple_depart.fecha_ini) <= :fecha AND (DATE(emple_depart.fecha_fin) >= :fecha OR emple_depart.fecha_fin IS NULL);");
        $stmt1->bindParam(':fecha', $fecha);
        $stmt1->execute();
        $arrayFecha=$stmt1->FetchAll(PDO::FETCH_ASSOC);
        return $arrayFecha;
    }

    function imprimirTablaEmpleadoFecha($arrayEmpleados){
        echo "<br><br><table>";
        echo "<tr><th>DNI</th><th>NOMBRE</th><th>APELLIDO</th><th>FECHA NACIMIENTO</th><th>NOMBRE DEPARTAMENTO</th></tr>";
        
        foreach($arrayEmpleados as $empleado){
            $dni = $empleado['dni'];
            $nombre = $empleado['nombre'];
            $apellido = $empleado['apellidos'];
            $fecha_nac = $empleado['fecha_nac'];
            $nombre_dpto = $empleado['nombre_dpto'];
    
            echo "<tr><td>$dni</td><td>$nombre</td><td>$apellido</td><td>$fecha_nac</td><td>$nombre_dpto</td></tr>";
        }
    
        echo "</table>";
    }

    function altaEmpleadoStm($conn,$dni,$nom_emple,$ap_emple,$f_nac,$salario,$cod_dpto){
        $stmt2 = $conn->prepare("INSERT INTO empleado (dni, nombre, apellidos, fecha_nac, salario) VALUES (:dni, :nombre, :apellido, :fecha_nac, :salario)");
        $stmt2->bindParam(':dni', $dni);
        $stmt2->bindParam(':nombre', $nom_emple);
        $stmt2->bindParam(':apellido', $ap_emple);
        $stmt2->bindParam(':fecha_nac', $f_nac);
        $stmt2->bindParam(':salario', $salario);
        $stmt2->execute();

        $stmt3 = $conn->prepare("INSERT INTO emple_depart (dni, cod_dpto, fecha_ini) VALUES (:dni, :cod_dpto, NOW())");
        $stmt3->bindParam(':dni', $dni);
        $stmt3->bindParam(':cod_dpto', $cod_dpto);
        $stmt3->execute();
    }

    function cambioDptoStm($conn,$dni,$cod_dpto){
        $stmt2 = $conn->prepare("UPDATE emple_depart SET fecha_fin = NOW() WHERE (dni = :dni and fecha_fin IS NULL);");
        $stmt2->bindParam(':dni', $dni);
        $stmt2->execute();
        
        $stmt3 = $conn->prepare("INSERT INTO emple_depart(dni,cod_dpto,fecha_ini) VALUES(:dni,:cod_dpto, NOW());");
        $stmt3->bindParam(':dni', $dni);
        $stmt3->bindParam(':cod_dpto', $cod_dpto);
        $stmt3->execute();
    }

    function salario($conn,$dni){
        $stmt1 = $conn->prepare("SELECT salario from empleado Where dni=:dni;");
        $stmt1->bindParam(':dni', $dni);
        $stmt1->execute();
        $salarioActual=$stmt1->fetchColumn();
        return $salarioActual;
    }

    function salarioNuevoStm($conn,$dni,$salarioNuevo){
        $stmt2 = $conn->prepare("UPDATE empleado SET salario = :salarioNuevo WHERE dni = :dni;");
        $stmt2->bindParam(':dni', $dni);
        $stmt2->bindParam(':salarioNuevo', $salarioNuevo);
        $stmt2->execute();
    }
?>