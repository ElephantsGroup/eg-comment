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
				<a style="float: left; cursor: pointer; background-color: #666; color: white; margin: 2px; padding: 5px; border-radius: 2px;" aria-hidden="true" onclick="toggle_form(<?= $com['id']?>)">پاسخ دهید</a>
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
		'<?php if($enabled_name) echo '<p><input class="form-control" type="text" name="reply-name[[com_id]]" id="reply-name[[com_id]]" placeholder="' . $module::t('Name') . '" /></p>'; ?>'+
		'<?php if($enabled_subject) echo '<p><input class="form-control" type="text" name="reply-subject[[com_id]]" id="reply-subject[[com_id]]" placeholder="' .  $module::t('Subject') . '" /></p>'; ?>'+
		'<p><input class="form-control" type="email" name="reply-email[[com_id]]" id="reply-email[[com_id]]" placeholder="<?= $module::t('Email') ?>" /></p>'+
		'<?php if($enabled_description) echo '<p><textarea class="form-control" name="reply-message[[com_id]]" id="reply-message[[com_id]]" placeholder="' . $module::t('Message') .'"></textarea></textarea></p>'; ?>'+
		'<input class="form-control" type="button" value="<?= $module::t('Send') ?>" onclick="post_reply([[com_id]])" />'+
		'</div>';

	function escapeRegExp(str) {
	    return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
	}
	function replaceAll(str, find, replace) {
	    return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}

	function toggle_form(com_id) {
		//console.log($("#reply-form" + com_id));
		if($("#reply-form" + com_id).html() == "")
			$("#reply-form" + com_id).html(replaceAll($reply_form_string, '[[com_id]]', com_id));
		else
			$("#reply-form" + com_id).html("");
		//global_comment_id = com_id;
	}
	var reply_validation = function (com_id) {
		var message_validate = [];
		if(<?= ($module_comment->required_name) ? 'true' : 'false'?> && !($('#reply-name' + com_id).val()))
		{
			//console.log('please entar your name');
			message_validate.push("<?= $module_comment::t('comment', 'please enter your name') ?>");
		}

		if(<?= ($module_comment->required_subject) ? 'true' : 'false' ?> && !($('#reply-subject' + com_id).val()))
		{
			console.log($('#reply-subject' + com_id).val());
			message_validate.push("<?= $module_comment::t('comment', 'please enter your subject') ?>");
		}

		if(<?= ($module_comment->required_email) ? 'true' : 'false'?> && !($('#reply-email' + com_id).val()))
		{
			//console.log('please entar your email');
			message_validate.push("<?= $module_comment::t('comment', 'please enter your Email')?>");
		}

		if(<?= ($module_comment->required_message) ? 'true' : 'false'?> && !($('#reply-message' + com_id).val()))
		{
			//console.log('please entar your message');
			message_validate.push("<?= $module_comment::t('comment', 'please enter your message')?>");
		}

		if (message_validate.length > 0)
			$.notify({message: message_validate.join("<br />")}, {type: 'warning'});

		return message_validate;

	};
	var post_reply = function (com_id) {
		var messages = reply_validation(com_id);
		console.log(messages);
		if(messages.length > 0)
			return;
		$.ajax({
			async: false,
			url: "<?= Yii::getAlias('@web') ?>/comment/ajax/reply",
			method: "POST",
			data: {
				<?php if ($enabled_name) echo 'name: $(\'#reply-name\'+com_id).val(),'?>
				<?php if ($enabled_subject) echo 'subject: $(\'#reply-subject\'+com_id).val(),'?>
				email: $('#reply-email'+com_id).val(),
				<?php if ($enabled_description) echo 'description: $(\'#reply-message\'+com_id).val(),'?>
				<?php if ($item) echo 'item_id:' . $item .','?>
				<?php if ($item_version) echo 'item_version:' . $item_version .','?>
				<?php if ($service) echo 'service_id:' . $service .','?>
				comment_id: com_id,
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
