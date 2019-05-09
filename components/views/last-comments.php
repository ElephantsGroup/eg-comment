<?php
use elephantsGroup\comment\assets\CommentAsset;

CommentAsset::register($this);
$module = \Yii::$app->getModule('base');
$module_comment = \Yii::$app->getModule('comment');
?>
<div>
	<div>
		<ul>
		<?php foreach ($last_comment['items'] as $id => $com):?>
			<li style="list-style-type: none; background-color: #f7f7f7; margin-bottom: 16px; padding: 16px; border-right: 3px solid rgb(138, 184, 194);">
				<a style="float: left; cursor: pointer" aria-hidden="true" onclick="show_form(<?= $com['id']?>)">پاسخ دهید</a>
				<div style="margin-bottom: 16px;"><strong><?= $com['name'] ?></strong>: <?= $com['description'] ?></div>
				<ul style="list-style-type: none; margin-bottom: 16px;">
					<?php foreach ($com['children'] as $child_id => $child_com):?>
						<li style="padding: 16px; border-right: 3px solid rgb(138, 184, 194);"> <strong><?= $child_com['name'] ?></strong>: <?= $child_com['description'] ?></li>
					<?php endforeach;?>
				</ul>
				<div id="reply-form<?= $com['id']?>"></div>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</div>




<script>
	var gloabl_comment_id = 0;
	$reply_form_string =
		'<div class="submit-review">'+
		'<?php if($enabled_name) echo '<p><input class="form-control" type="text" name="reply-name" id="reply-name" placeholder="' . $module::t('Name') . '" /></p>'; ?>'+
		'<?php if($enabled_subject) echo '<p><input class="form-control" type="text" name="reply-subject" id="reply-subject" placeholder="' .  $module::t('Subject') . '" /></p>'; ?>'+
		'<p><input class="form-control" type="email" name="reply-email" id="reply-email" placeholder="<?= $module::t('Email') ?>" /></p>'+
		'<?php if($enabled_description) echo '<p><textarea class="form-control" name="reply-message" id="reply-message" placeholder="' . $module::t('Message') .'"></textarea></textarea></p>'; ?>'+
		'<input class="form-control" type="button" value="<?= $module::t('Send') ?>" onclick="post_reply()" />'+
		'</div>';

	function show_form(com_id) {
		//console.log($("#reply-form" + com_id));
		$("#reply-form" + com_id).html($reply_form_string);
		global_comment_id = com_id;
	}
	var reply_validation = function () {
		var message_validate = [];
		if(<?= ($module_comment->_required_name) ? 'true' : 'false'?> && !($('#reply-name').val()))
		{
			//console.log('please entar your name');
			message_validate.push("<?= $module_comment::t('please enter your name') ?>");
		}

		if(<?= ($module_comment->_required_subject) ? 'true' : 'false' ?> && !($('#reply-subject').val()))
		{
			//console.log('please entar your subject');
			message_validate.push("<?= $module_comment::t('please enter your subject') ?>");
		}

		if(<?= ($module_comment->_required_email) ? 'true' : 'false'?> && !($('#reply-email').val()))
		{
			//console.log('please entar your email');
			message_validate.push("<?= $module_comment::t('please enter your Email')?>");
		}

		if(<?= ($module_comment->_required_message) ? 'true' : 'false'?> && !($('#reply-message').val()))
		{
			//console.log('please entar your message');
			message_validate.push("<?= $module_comment::t('please enter your message')?>");
		}

		if (message_validate.length > 0)
			$.notify({message: message_validate.join("<br />")}, {type: 'warning'});

		return message_validate;

	};
	var post_reply = function () {
		var messages = reply_validation();
		console.log(messages);
		if(messages.length > 0)
			return;
		$.ajax({
			async: false,
			url: "<?= Yii::getAlias('@web') ?>/comment/ajax/reply",
			method: "POST",
			data: {
				<?php if ($enabled_name) echo 'name: $(\'#reply-name\').val(),'?>
				<?php if ($enabled_subject) echo 'subject: $(\'#reply-subject\').val(),'?>
				email: $('#reply-email').val(),
				<?php if ($enabled_description) echo 'description: $(\'#reply-message\').val(),'?>
				<?php if ($item) echo 'item_id:' . $item .','?>
				<?php if ($item_version) echo 'item_version:' . $item_version .','?>
				<?php if ($service) echo 'service_id:' . $service .','?>
				comment_id: global_comment_id ,
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
				$("#reply-form"+ response_array['comment_id']).empty();
			}
			else
				$.notify({ message: response_array['message']},{type: 'warning'});
		})
			.fail(function( req, status, err )
			{
				console.log('something went wrong', req, status, err);
			})
	}
</script>
