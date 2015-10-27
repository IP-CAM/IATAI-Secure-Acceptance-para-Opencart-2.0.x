<?php 
    echo $header;
    echo $column_left;
?>
<table width="400px" align="center" style="border: 1px solid black;  border-spacing: 0; border-spacing: 3px;
    border-collapse: separate;">
    <tr bgcolor="#2f4074">
        <td colspan="2"><h4 style="color: white; text-align: center;">Datos de la transacción</td>
    </tr>
<?php
    $fields = $data['fields'];
    for ($i = 0 ; $i < count($fields) ; $i++){
        echo "<tr>";
        echo "<td bgcolor='#dddddd'>" . $fields[$i]['nombre_campo'];
        echo "</td>";
        echo "<td bgcolor='#f4f4f4'>" . $fields[$i]['valor_campo'];
        echo "</td>";
        echo "</tr>";
    }
?>
</table>
<h3 align="center">Guarde esta información para referencias futuras</h3>
<?php echo $footer; ?>
