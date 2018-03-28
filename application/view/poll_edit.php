<h1><?php _e('Edit poll', 'hoa_polls'); ?></h1>
<form onsubmit="return validatePollEdit();" action="#" method="POST">
<label for="poll_name"><?php _e('Poll name', 'hoa_polls'); ?></label>
<input onchange="resetErrors('hoa_poll_name');" name="poll_name" id="hoa_poll_name" type="text" value = "<?php echo $data['pollName'] ?>">
<div id='hoa_poll_name_msg' style='display:none; color:red'>
<p><?php _e("poll name shouldn't be empty", 'hoa_polls'); ?></p>
</div>
<br>
<br>
<label for="poll_quorum"><?php _e('Minimum number of votes for a quorum', 'hoa_polls'); ?></label>
<input onchange="resetErrors('hoa_poll_quorum');" name="poll_quorum" id="hoa_poll_quorum" type="text" value = "<?php echo $data['quorum'] ?>">
<div id='hoa_poll_quorum_msg' style='display:none; color: red'>
<p><?php _e('quorum is numeric between 1-100', 'hoa_polls'); ?></p>
</div>
<br>
<br>
     <input type="submit" name="submit" value="<?php _e('Save', 'hoa_polls'); ?>">
</form>
<br>