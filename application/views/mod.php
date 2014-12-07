<?php
	$description_ = "";
	$title = "";
	$id = "";

	if($topic != null){
		$description_ = $topic->description;
		$title = $topic->title;
		$id = $topic->id;
	}
?>
<form action="/index.php/topic/mod" method="POST" class="span10">
	<?php echo validation_errors(); ?>
	<input type='text' name='title' placeholder="제목" class="span12" value="<?=$title?>"/>
	<textarea name='description' placeholder="본문" class="span12" rows="15"><?=$description_?></textarea>
	<input type='hidden' name='id' value='<?=$id?>'/>

	<input type='submit' class="btn" value="저장"/>
</form>