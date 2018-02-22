<h1><?php echo htmlentities($data['pollResult']['title']); ?></h1>
<p>
Проголосовало <?php echo $data['pollResult']['numberOfVoters']; ?> квартир, обладающих <?php echo $data['pollResult']['percentVoted']; ?>% голосов - 
    <?php
        if ($data['pollResult']['hasQuorum'] === TRUE) {
            echo 'кворум есть';
        } else {
            echo 'кворума нет';
        }
    ?>
</p>
<table>
    <tr>
        <th>Вопрос</th>
        <th>Да</th>
        <th>Нет</th>
        <th>Воздержался</th>
    </tr>
    <?php
        foreach ($data['pollResult']['questions'] as $row) {
            echo '<tr>';
            echo '<td>' . $row['title'] . '</td>';
            echo '<td>' . $row['yes'] . '%</td>';
            echo '<td>' . $row['no'] . '%</td>';
            echo '<td>' . $row['skip'] . '%</td>';
            echo '</tr>';
        }
    ?>
</table>
<br>
<button onclick="toggleVoteResults()">Показать ответы</button>
<br>
<div id="VoteResults" style="display: none">
    <br>
    <table>
        <tr>
            <th>Квартира</th>
            <?php
            foreach ($data['pollQuestions'] as $row){
                echo '<th>' . $row['questionText'] . '</th>';
            }
            ?>
        </tr>
    <?php
        foreach ($data['pollAnswers'] as $row) {
            echo '<tr>';
            foreach ($row as $key => $column) {
                echo '<td>' . $column;
                if ($key == 0 && !$data['pollArchived']){
                    echo '(<a href="' . admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/edit/') . $data['pollId'] . '/' . $column . '">редактировать</a> / <a href="' . admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/delete/') . $data['pollId'] . '/' . $column . '" onclick="return confirmDelete();">удалить</a>)';
                }
                echo '</td>';
            }
            echo '</tr>';
        }
    ?>
    </table>
</div>
<br>
<?php
    if (!$data['pollArchived']) {
        echo '<li><a href="' . admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/fill/') . $data['pollId'] . '">Добавить ответы</a></li>';
    }
?>