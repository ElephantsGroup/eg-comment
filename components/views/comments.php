<?php
use elephantsGroup\comment\assets\CommentAsset;

CommentAsset::register($this);
$module = \Yii::$app->getModule('base');
$module_comment = \Yii::$app->getModule('comment');
?>
<div>
	<div class="submit-review">
		<?php if($enabled_name) echo '<p><input class="form-control" type="text" name="name" id="name" placeholder="' . $module::t('Name') . '" /></p>'; ?>
		<?php if($enabled_subject) echo '<p><input class="form-control" type="text" name="subject" id="subject" placeholder="' .  $module::t('Subject') . '" /></p>'; ?>
		<p><input class="form-control" type="email" name="email" id="email" placeholder="<?= $module::t('Email') ?>" /></p>
		<?php if($enabled_description) echo '<p><textarea class="form-control" name="message" id="message" placeholder="' . $module::t('Message') .'"></textarea></textarea></p>'; ?>
		<input class="form-control" type="button" value="<?= $module::t('Send') ?>" onclick="post_comment()" />
	</div>
</div>

<script>
var comment_validation = function () {
	var message_validate = [];
	if(<?= ($module_comment->_required_name) ? 'true' : 'false'?> && !($('#name').val()))
	{
		//console.log('please entar your name');
		message_validate.push("<?= $module_comment::t('please enter your name') ?>");
	}

	if(<?= ($module_comment->_required_subject) ? 'true' : 'false' ?> && !($('#subject').val()))
	{
		//console.log('please entar your subject');
		message_validate.push("<?= $module_comment::t('please enter your subject') ?>");
	}

	if(<?= ($module_comment->_required_email) ? 'true' : 'false'?> && !($('#email').val()))
	{
		//console.log('please entar your email');
		message_validate.push("<?= $module_comment::t('please enter your Email') ?>");
	}

	if(<?= ($module_comment->_required_message) ? 'true' : 'false'?> && !($('#message').val()))
	{
		//console.log('please entar your message');
		message_validate.push("<?= $module_comment::t('please enter your message') ?>");
	}

	if (message_validate.length > 0)
		$.notify({message: message_validate.join("<br />")}, {type: 'warning'});

	return message_validate;

};

var post_comment = function () {
	var messages = comment_validation();
	if(messages.length > 0)
		return;
	$.ajax({
		url: "<?= Yii::getAlias('@web') ?>/comment/ajax/create",
		method: "POST",
		data: {
			<?php if ($enabled_name) echo 'name: $(\'#name\').val(),'?>
			<?php if ($enabled_subject) echo 'subject: $(\'#subject\').val(),'?>
			email: $('#email').val(),
			<?php if ($enabled_description) echo 'description: $(\'#message\').val(),'?>
			<?php if ($item) echo 'item_id:' . $item .','?>
			<?php if ($item_version) echo 'item_version:' . $item_version .','?>
			<?php if ($service) echo 'service_id:' . $service .','?>
			<?= Yii::$app->request->csrfParam; ?>:"<?= Yii::$app->request->csrfToken; ?>"
		}
	})
	.done(function (response)
	{
		response_array = JSON.parse(response);
		if(response_array['status'] == 200) {
			$.notify(
				{
					message: response_array['message'],
					icon: 'glyphicon glyphicon glyphicon-ok-circle'
				},
				{
					type: 'success',
					allow_dismiss: false,
					placement: {
						from: "bottom",
						align: "right"
					}
				}
			);
//			$('#heart-like' + in_item_id).css('display', 'none');
//			$('#heart-unlike' + in_item_id).css('display', 'block');
		}
		else
			$.notify({ message: response_array['message']},{type: 'warning'});
	})
	.fail(function( req, status, err )
	{
		alert(1);
		console.log('something went wrong', req, status, err);
	})
}
</script>
