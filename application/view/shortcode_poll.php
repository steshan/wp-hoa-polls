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
            $answer = '';
            foreach ($data['user_answers'] as $user_answers){
                if ($user_answers['questionText'] == $row['title']) {
                    $answer = $user_answers['answer'];
                }
            }
            echo '<tr>';
            echo '<td>' . $row['title'] . '</td>';
            if ($answer == 'YES') {
                echo '<td><strong>' . $row['yes'] . '%</strong></td>';
            } else {
                echo '<td>' . $row['yes'] . '%</td>';
            }
            if ($answer == 'NO') {
                echo '<td><strong>' . $row['no'] . '%</strong></td>';
            } else {
                echo '<td>' . $row['no'] . '%</td>';
            }
            if ($answer == 'SKIP') {
                echo '<td><strong>' . $row['skip'] . '%</strong></td>';
            } else {
                echo '<td>' . $row['skip'] . '%</td>';
            }
            echo '</tr>';
        }
    ?>
</table>