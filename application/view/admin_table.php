<form action="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=admin/import'); ?>" method="POST">
    <input type="submit" name="submit" value=<?php _e('Import', 'hoa_polls'); ?>><br>
    <br>
    <table>
    <?php
        $x = count($data[0]);
        echo '<tr>';
        for ($i = 1; $i <= $x; $i++) {
            echo '<th><select name="column_type[]"><option>' . __('Select a column type', 'hoa_polls') . '</option><option>' . __('Room number', 'hoa_polls') . '</option><option>' . __('Room area', 'hoa_polls') . '</option></select> </th>';
        }
        echo '</tr>';
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . $cell . '</td>';
            }
            echo '</tr>';
        }
    ?>
    </table>
</form>