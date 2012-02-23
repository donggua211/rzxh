<div id="nav">
	<span class="action-span"><a href="<?php echo site_url() ?>"  target="_top">管理系统</a></span>
	配置系统 -> 第一步
</div>

<div id="main">
	<div class="room_main">
		确定要做系统机房和设备同步？</br>
		
		第一步：清理现有机房，设备的数据：</br>
		请按“确定”继续。</br>
		<form action="<?php echo site_url('configer/synch') ?>" method="post" name="addstaff">
		<div class="button-div">
			<input type="hidden" value="1" name="step">
			<input type="submit" class="button" value=" 确定 " name="submit">
			<input type="reset" class="button" value=" 重置 " name="reset">
		</div>
		</form>
	<div>
</div>