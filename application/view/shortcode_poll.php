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