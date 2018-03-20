<h1><?php echo htmlentities($data['pollResult']['title']); ?></h1>
<p>
    <?php
        echo sprintf(__("%s rooms have voted. It's %s%% of the vote. ", 'hoa_polls'), $data['pollResult']['numberOfVoters'], $data['pollResult']['percentVoted']);
        if ($data['pollResult']['hasQuorum'] === TRUE) {
            _e('There is a quorum', 'hoa_polls');
        } else {
            _e( 'There is no quorum', 'hoa_polls');
        }
    ?>
</p>
<table>
    <tr>
        <th><?php _e('Question', 'hoa_polls'); ?></th>
        <th><?php _e('Yes', 'hoa_polls'); ?></th>
        <th><?php _e('No', 'hoa_polls'); ?></th>
        <th><?php _e('Skip', 'hoa_polls'); ?></th>
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
            foreach (array('YES', 'NO', 'SKIP') as $option) {
                if ($answer === $option) {
                    echo '<td><strong>' . $row[strtolower($option)] . '%</strong></td>';
                } else {
                    echo '<td>' . $row[strtolower($option)] . '%</td>';
                }
            }
            echo '</tr>';
        }
    ?>
</table>