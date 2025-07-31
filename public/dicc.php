<?php
// Conexión a la base de datos
$host = "localhost";
$dbname = "proyectodb";
$user = "usuariodb";
$password = "usuario";
$port = "5433";

$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password port=$port");

if (!$conn) {
    echo "Error al conectar: " . pg_last_error();
    exit;
}

// Consulta para obtener las tablas
$query = "SELECT schemaname, tablename, *
    FROM pg_tables
    WHERE schemaname IN ('public', 'secundaria', 'ingreso', 'registro_acad')
    ORDER BY schemaname, tablename";
$result = pg_query($conn, $query);

$tablas = pg_fetch_all($result);

// Consulta para obtener las Restricciones
$query = "SELECT
    tc.constraint_name,
    tc.table_schema,
    tc.table_name,
    kcu.column_name,
    tc.constraint_type,
    ccu.table_name as foreign_table_name,
    ccu.column_name as foreign_column_name,
    ccu.table_schema as foreign_schema_name,
    pc.confupdtype, pc.confdeltype
FROM
    information_schema.table_constraints AS tc
JOIN
    information_schema.key_column_usage AS kcu
ON
    tc.constraint_name = kcu.constraint_name
    AND tc.table_schema = kcu.table_schema
JOIN
    information_schema.constraint_column_usage AS ccu
ON
    tc.constraint_name = ccu.constraint_name
    --AND tc.table_schema = ccu.table_schema
JOIN
    pg_constraint AS pc ON pc.conname = tc.constraint_name
JOIN
    pg_class AS rel ON rel.oid = pc.conrelid
    ";
$result = pg_query($conn, $query);
$restricciones = [];
$res = pg_fetch_all($result);

foreach ($res as $r) {
    $rel = $r['constraint_type'] === 'FOREIGN KEY' ? ' ON ' . $r['foreign_schema_name'] . '.' . $r['foreign_table_name'] . '(' . $r['foreign_column_name'] . ')' : '';

    $rel .= $r['confdeltype'] === 'r' ? ' ON DELETE RESTRICT' : '';
    $rel .= $r['confdeltype'] === 'c' ? ' ON DELETE CASCADE' : '';
    $rel .= $r['confdeltype'] === 'a' ? ' ON DELETE NO ACTION' : '';
    $rel .= $r['confupdtype'] === 'r' ? ' ON UPDATE RESTRICT' : '';
    $rel .= $r['confupdtype'] === 'c' ? ' ON UPDATE CASCADE' : '';
    $rel .= $r['confupdtype'] === 'a' ? ' ON UPDATE NO ACTION' : '';

    $restricciones[$r['table_schema'] . '_' . $r['table_name'] . '_' . $r['column_name']][] = $r['constraint_type'] . $rel;
}

// Procesar los resultados
foreach ($tablas as $row) {

    $nombre = $row['schemaname'] . '.' . $row['tablename'];
    $query = "SELECT objsubid, description
        FROM pg_description
        WHERE objoid = '$nombre'::regclass";

    $result = pg_query($conn, $query);
    $comentarios_ = pg_fetch_all($result);
    $comentarios = [];
    foreach ($comentarios_ as $c) {
        $comentarios[$c['objsubid']] = $c['description'];
    }


    // Consulta para obtener las columnas de la tabla
    $query_columnas = "
        SELECT column_name, data_type, dtd_identifier,
                character_maximum_length, column_default,
                udt_name, is_nullable, numeric_precision, numeric_scale
        FROM information_schema.columns
        WHERE table_schema = '$row[schemaname]'
            AND table_name = '$row[tablename]'";
    $result_columnas = pg_query($conn, $query_columnas);

    $idCol = 0;
    $comentarioT = array_key_exists($idCol, $comentarios) ? $comentarios[$idCol] : '';

    if ($result_columnas) {
        echo '<BR>
        <TABLE border = 1>
            <CAPTION>
                Tabla: ' . $nombre . '<BR>
                Descripci&oacute;n: ' . $comentarioT . '
            <THEAD>
                <TR>
                    <TH></TH>
                    <TH>Nombre</TH>
                    <TH>Tipo</TH>
                    <TH>Nulo</TH>
                    <TH>Restricciones</TH>
                    <TH>Valor por defecto</TH>
                    <TH>Descripci&oacute;n</TH>
                </TR>
            </THEAD>
            <TBODY>';
        while ($row_columnas = pg_fetch_assoc($result_columnas)) {
            $tipo = $row_columnas['data_type'];
            if ($row_columnas['udt_name'] === 'varchar') {
                $tipo .= " ($row_columnas[character_maximum_length]) ";
            } elseif ($row_columnas['udt_name'] === 'numeric') {
                $tipo .= " ($row_columnas[numeric_precision], $row_columnas[numeric_scale]) ";
            }
            $nullable = $row_columnas['is_nullable'] === 'YES' ? 'S&iacute;' : 'No';
            $idCol = $row_columnas['dtd_identifier'];
            $comentario = array_key_exists($idCol, $comentarios) ? $comentarios[$idCol] : '';

            $fn = $row['schemaname'] . '_' . $row['tablename'] . '_' . $row_columnas['column_name'];
            $restriccionesCampo = '';
            if (array_key_exists($fn, $restricciones)) {
                $restriccionesCampo = implode(', ', $restricciones[$fn]);
            }
            echo "<TR>
                    <TD>$row_columnas[dtd_identifier]</TD>
                    <TD>$row_columnas[column_name]</TD>
                    <TD>$tipo</TD>
                    <TD>$nullable</TD>
                    <TD>$restriccionesCampo</TD>
                    <TD>$row_columnas[column_default]</TD>
                    <TD>$comentario</TD>
                </TR>";
        }
        echo '
            </TBODY>
            </TABLE>';
    }
}

// Cerrar la conexión
pg_close($conn);
