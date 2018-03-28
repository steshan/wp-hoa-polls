<h1><?php _e('New poll', 'hoa_polls'); ?></h1>
<form onsubmit="return validatePollAdd();" action="#" method="POST">
<label for="poll_name"><?php _e('Poll name', 'hoa_polls'); ?></label>
<input onchange="resetErrors('hoa_poll_name');" name="poll_name" id="hoa_poll_name" type="text">
<div id='hoa_poll_name_msg' style='display:none; color: red'>
<p><?php _e("poll name shouldn't be empty", 'hoa_polls'); ?></p>
</div>
<br>
<br>
<label for="poll_quorum"><?php _e('Minimum number of votes for a quorum', 'hoa_polls'); ?></label>
<input onchange="resetErrors('hoa_poll_quorum');" name="poll_quorum" id="hoa_poll_quorum" type="text">
<div id='hoa_poll_quorum_msg' style='display:none; color: red'>
<p><?php _e('quorum is numeric between 1-100', 'hoa_polls'); ?></p>
</div>
<br>
<br>
<p id="questions_title"><strong><?php _e('Question list', 'hoa_polls'); ?></strong></p>
<div id='questions_title_msg' style='display:none; color: red'>
<p><?php _e('add at least one question', 'hoa_polls'); ?></p>
</div>
<br>
<div id="poll_questions">
</div>
<br>
<button onclick="addPollQuestion(); return false;"><?php _e('New question', 'hoa_polls'); ?></button>
<br><br>
<input type="submit" name="submit" value="<?php _e('Save', 'hoa_polls'); ?>">
</form>
<br>