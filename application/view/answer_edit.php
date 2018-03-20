<h1><a href="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=poll/get/') . $data['pollId']; ?>"><?php echo htmlentities($data['pollName']); ?></a></h1>
<h2><?php _e('Room', 'hoa_polls'); echo ' ' . htmlentities($data['roomNumber']); ?></h2>


<br>
<form action="#" method="POST">
    <?php
    foreach ($data['answers'] as $question) {
        $input = $question['questionText'];
       switch ($question['answer']){
            case "YES":
                $input .= '<input type="radio" name="answers[' . $question['questionId'] . ']" value = "YES" checked="checked"><label>' . __('Yes', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "NO"><label>' . __('No', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "SKIP"><label>' . __('Skip', 'hoa_polls') . '</label>';
                break;
            case "NO":
                $input .= '<input type="radio" name="answers[' . $question['questionId'] . ']" value = "YES"><label>' . __('Yes', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "NO" checked="checked"><label>' . __('No', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "SKIP"><label>' . __('Skip', 'hoa_polls') . '</label>';
                break;
            case "SKIP":
                $input .= '<input type="radio" name="answers[' . $question['questionId'] . ']" value = "YES"><label>' . __('Yes', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "NO"><label>' . __('No', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['questionId'] . ']" value = "SKIP" checked="checked"><label>' . __('Skip', 'hoa_polls') . '</label>';
                break;
        }
        echo '<br>';
        echo $input;
    }
    ?>
    <br>
    <br>
    <input type="submit" name="submit" value="<?php _e('Save', 'hoa_polls'); ?>">
</form>

