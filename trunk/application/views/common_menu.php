<div id="menu_main"><div style="float:right;padding-right:5px;padding-top:3px"><a href="<?php echo site_url("room"); ?>" target="main-frame">全部</a></div>
	<h3><a href="#">机房列表</a></h3>
	<ul>
		<?php
		foreach($rooms as $room)
		{
			if(check_user_role($room['room_id']) >= GROUP_ROLE_READABLE)
				echo '<li><a href="'.site_url('room/one/'.$room['room_id']).'" target="main-frame">'.$room['room_name'].'</a></li>';
		}

		?>
	</ul>
	<?php if(is_admin()): ?>
	<h3><a href="#">用户管理</a></h3>
	<ul>
		<li><a href="<?php echo site_url("admin/user/add"); ?>" target="main-frame">添加用户</a></li>
		<li><a href="<?php echo site_url("admin/user"); ?>" target="main-frame">用户列表</a></li>
		<li><a href="<?php echo site_url("admin/group/add"); ?>" target="main-frame">添加分组</a></li>
		<li><a href="<?php echo site_url("admin/group"); ?>" target="main-frame">分组列表</a></li>
	</ul>
	<h3><a href="#">时间段管理</a></h3>
	<ul>
		<li><a href="<?php echo site_url("admin/period/add"); ?>" target="main-frame">添加时间段</a></li>
		<li><a href="<?php echo site_url("admin/period"); ?>" target="main-frame">时间段列表</a></li>
	</ul>
	<?php endif; ?>
</div>
<script language="JavaScript">

jQuery(document).ready(function(){
	$('#menu_main h3').each(function(){
		$(this).addClass("explode");
		$(this).click(function() {
			$(this).next().toggle();
			$(this).toggleClass("collapse");
			return false;
		})
	})
});
</script>