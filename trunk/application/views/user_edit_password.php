<div id="nav">
	<span class="action-span"><a href="<?php echo site_url('admin') ?>"  target="_top">管理系统</a></span>
	 » 修改密码
	<div style="clear:both"></div>
</div>
<div id="main">
	<div id="main_body">
		<?php if(isset($notification) && !empty($notification)): ?>
		<div style="backgroud:#fff;padding:5px;border:1px solid #FF8080;text-align:center">
			<img style="vertical-align: middle;" src="<?php echo img_base_url() ?>icon/warning.gif"> <span style="color:red;font-size:20px;line-height:22px"><?php echo $notification;?></span>
		</div>
		<?php endif;?>
		<form action="<?php echo site_url('user/change_psd') ?>" method="post" name="addstaff">
		<table width="90%" id="shop_info-table">
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>原始密码: </td>
				<td><input name="old_password" type="text" size="30" /></td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>新密码: </td>
				<td><input name="new_password" type="text" size="30" /></td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>确认新密码: </td>
				<td><input name="new_password_c" type="text" size="30" /></td>
			</tr>
		</table>
		<div class="button-div">
			<input type="submit" class="button" value=" 确定 " name="submit">
			<input type="reset" class="button" value=" 重置 " name="reset">
		</div>
		</form>
	</div>
</div>
