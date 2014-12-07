<script type="text/javascript">
	$(function(){
		//삭제
		$("#del_btn").on("click", function(){
			$("#frm1").attr("action", "/index.php/topic/del");
			if($("#frm1 #id").val() != "<?php echo $topic->id?>"){ <?php //id를 바꿨을 경우 return;?>
				return;
			} else {
				$("#frm1").submit();
			}
		});
	});
</script>
<div class="span10">
<article>
	<h1><?php echo $topic->title?></h1>
	<div>
		<div>
			<?php echo kdate($topic->created)?>
		</div>
		<?php echo auto_link($topic->description) ?>
		<?//php echo auto_link('www.google.com') ?>
		<?//php echo 'www.google.com'?>
	</div>
</article>
	<div>
		<a href="/index.php/topic/add" class="btn">추가</a>
		<a href="/index.php/topic/mod?id=<?php echo $topic->id?>" class="btn">수정</a>
		<a id="del_btn" href="#none" class="btn">삭제</a>
	</div>
	<form id="frm1" name="frm1" style="display:none;" action="" method="POST">
		<input type="hidden" id="id" name="id" value="<?php echo $topic->id?>" readonly/>
	</form>
</div>