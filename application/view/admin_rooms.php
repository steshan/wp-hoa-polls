<form action="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin/delete') ?>" method="POST">
    <input type="submit" name="submit" value="<?php _e('Delete data', 'hoa_polls'); ?>"><br>
    <br>
    <table>
       <?php
        echo'<tr><th>' . __('Room number', 'hoa_polls') . '</th><th>' . __('Room area', 'hoa_polls') . '</th></tr>';
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $row['roomNumber'] . '</td>';
            echo '<td>' . $row['totalArea'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</form>