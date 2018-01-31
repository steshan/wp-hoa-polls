<h1>Список голосований:</h1>
<ol>
    <?php
        foreach ($data as $row) {
            echo '<li><a href="/poll/get/' . $row['id'] . '">' . $row['name'] . '</a>';
            if ($row['read_only'] == 0) {
                echo '<a href="/poll/edit/' . $row['id'] . '"> (редактировать</a> / <a href="/poll/archive/' . $row['id'] . '" onclick="return confirmReadOnly();">архивировать</a> / <a href="/poll/delete/' . $row['id'] . '"onclick="return confirmDelete();">удалить)</a></li>';
            }
        }
    ?>
</ol>
<br>
<a href="/poll/add">Добавить голосование</a>
<br>