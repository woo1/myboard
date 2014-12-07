<div class="span2">
      <!--Sidebar content-->  
<ul class="nav nav-tabs nav-stacked">
<?php
foreach($topics as $entry){
?>
<li><a href="/index.php/topic/<?=$entry->id?>"><?=htmlspecialchars($entry->title)?></a></li>
<?php
}
?>
</ul>
</div>