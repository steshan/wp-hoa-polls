<h1>Список голосований:</h1>
<ol>
    <?php
        foreach ($data as $row) {
            echo '<li><a href="/wp-admin/admin.php?page=homeowners-association-polls&hoa_path=poll/get/' . $row['id'] . '">' . $row['name'] . '</a>';
            if ($row['read_only'] == 0) {
                echo ' (<a href="/wp-admin/admin.php?page=homeowners-association-polls&hoa_path=poll/edit/' . $row['id'] . '">редактировать</a> / <a href="/wp-admin/admin.php?page=homeowners-association-polls&hoa_path=poll/archive/' . $row['id'] . '" onclick="return confirmReadOnly();">архивировать</a> / <a href="/wp-admin/admin.php?page=homeowners-association-polls&hoa_path=poll/delete/' . $row['id'] . '"onclick="return confirmDelete();">удалить</a>)';
            }
            echo '</li>';
        }
    ?>
</ol>
<br>
<a href="/wp-admin/admin.php?page=homeowners-association-polls&hoa_path=poll/add">Добавить голосование</a>
<br>