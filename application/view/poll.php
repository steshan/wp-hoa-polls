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
<button onclick="toggleVoteResults('VoteResults')"><?php _e('Show answers', 'hoa_polls'); ?></button>
<br>
<div id="VoteResults" style="display: none">
    <br>
    <table>
        <tr>
            <th><?php _e('Room', 'hoa_polls'); ?></th>
            <?php
            foreach ($data['pollQuestions'] as $row){
                echo '<th>' . $row['questionText'] . '</th>';
            }
            ?>
        </tr>
    <?php
        foreach ($data['pollAnswers'] as $row) {
            echo '<tr>';
            ksort($row);
            foreach ($row as $key => $column) {
                echo '<td>' . __($column, 'hoa_polls');
                if ($key == 0 && !$data['pollArchived']){
                    echo '(<a href="' . admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/edit/') . $data['pollId'] . '/' . $column . '">' . __('edit', 'hoa_polls') . '</a> / <a href="' . admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/delete/') . $data['pollId'] . '/' . $column . '" onclick="return confirmDelete();">' . __('delete', 'hoa_polls') . '</a>)';
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
        echo '<li><a href="' . admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/fill/') . $data['pollId'] . '">' . __('Add answers', 'hoa_polls') . '</a></li>';
    }
?>