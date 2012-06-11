<div id="nav">
	<span class="action-span"><a href="<?php echo site_url() ?>"  target="_top">管理系统</a></span>
	修复配置系统 -> 第二步
</div>

<div id="main">
	<div class="room_main">
		机房数据已修复</br>
		新加了如下机房信息：
		<pre><?php print_r($add_room_list);?></pre>
		
		第三步：获取房间数据：</br>
		请按“确定”继续。</br>
		<form action="<?php echo site_url('configer/synch/repair') ?>" method="post" name="addstaff">
		<div class="button-div">
			<input type="hidden" value="2" name="step">
			<input type="submit" class="button" value=" 确定 " name="submit">
			<input type="reset" class="button" value=" 重置 " name="reset">
		</div>
		</form>
	<div>
</div>