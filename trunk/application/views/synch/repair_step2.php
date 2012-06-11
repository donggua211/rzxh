<div id="nav">
	<span class="action-span"><a href="<?php echo site_url() ?>"  target="_top">管理系统</a></span>
	修复配置系统 -> 第三步
</div>

<div id="main">
	<div class="room_main">
		设备更新列表如下：</br>
		<?php print_r($result); ?></br>
		
		第五步：执行一次数据同步：</br>
		请按“确定”继续。</br>
		<form action="<?php echo site_url('configer/synch/repair') ?>" method="post" name="addstaff">
		<div class="button-div">
			<input type="hidden" value="3" name="step">
			<input type="submit" class="button" value=" 确定 " name="submit">
			<input type="reset" class="button" value=" 重置 " name="reset">
		</div>
		</form>
		或者，再次执行一次数据修复：</br>
		<form action="<?php echo site_url('configer/synch/repair') ?>" method="post" name="addstaff">
		<div class="button-div">
			<input type="hidden" value="2" name="step">
			<input type="submit" class="button" value=" 修复 " name="submit">
			<input type="reset" class="button" value=" 重置 " name="reset">
		</div>
		</form>
	<div>
</div>