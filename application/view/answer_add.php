<h1><a href="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=poll/get/') . $data['pollId']; ?>"><?php echo htmlentities($data['pollName']); ?></a></h1>

<form onsubmit="return validateAnswerAdd(<?php echo $data['rooms']; ?>);" action="<?php echo admin_url('admin.php?page=homeowners-association-polls&hoa_path=answer/fill/') . $data['pollId']; ?>" method="POST">
    <label for="roomNumber"><?php _e('Enter room number', 'hoa_polls'); ?></label>
    <input onchange="resetErrors('hoa_room_number');" type="text" name="roomNumber" id="hoa_room_number">
    <span id='hoa_room_number_msg' class="hoa_error_msg"><?php _e("room number is numeric", 'hoa_polls'); ?></span>
    <br>
    <br>
    <div onchange="resetErrors('hoaAnswerAdd');" id="hoaAnswerAdd">
    <?php
    foreach ($data['pollQuestions'] as $question) {
        echo $question['questionText'] . '<input type="radio" name="answers[' . $question['id'] . ']" value = "YES"><label>' . __('Yes', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['id'] . ']" value = "NO"><label>' . __('No', 'hoa_polls') . '</label><input type="radio" name="answers[' . $question['id'] . ']" value = "SKIP"><label>' . __('Skip', 'hoa_polls') . '</label> <br>';
    }
    ?>
    </div>
    <span id='hoaAnswerAdd_msg' class="hoa_error_msg"><?php _e("not all questions answered", 'hoa_polls'); ?></span>
    <br>
    <input type="submit" name="submit" value="<?php _e('Save', 'hoa_polls'); ?>">
</form>