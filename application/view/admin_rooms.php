<form action="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin/delete') ?>" method="POST">
    <input type="submit" name="submit" value="Удалить данные"><br>
    <br>
    <table>
       <?php
        echo'<tr><th>Номер квартиры</th><th>Площадь квартиры</th></tr>';
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $row['roomNumber'] . '</td>';
            echo '<td>' . $row['totalArea'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</form>