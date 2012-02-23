<div id="nav">
	<span class="action-span"><a href="<?php echo site_url('admin') ?>"  target="_top">管理系统</a></span>
	<span class="action-span"> » <a href="<?php echo site_url('admin/user') ?>" target="main-frame">用户管理</a></span>
	 » 添加用户
</div>
<div id="main">
	<div id="main_body">
		<?php if(isset($notification) && !empty($notification)): ?>
		<div style="backgroud:#fff;padding:5px;border:1px solid #FF8080;text-align:center">
			<img style="vertical-align: middle;" src="<?php echo img_base_url() ?>icon/warning.gif"> <span style="color:red;font-size:20px;line-height:22px"><?php echo $notification;?></span>
		</div>
		<?php endif;?>
		
		<form action="<?php echo site_url('admin/user/add') ?>" method="post" name="addstaff">
		<table width="90%" id="shop_info-table">
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>用户名: </td>
				<td>
					<input id="username" name="username" type="text" value="<?php echo (isset($user['username'])) ? $user['username'] :''; ?>" size="30" />
				</td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>密码: </td>
				<td><input name="password" type="text" value="111111" size="30" /></td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>确认密码: </td>
				<td><input name="password_c" type="text" value="111111" size="30" /></td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>电子邮箱: </td>
				<td><input name="email" type="text" value="<?php echo (isset($user['email'])) ? $user['email'] :''; ?>" size="40" /></td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>电话: </td>
				<td>
					<input name="mobile" type="text" value="<?php echo (isset($user['mobile'])) ? $user['mobile'] :''; ?>" size="40" />
				</td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>所在分组: </td>
				<td>
					<select name="group_id">
						<option value='0'>请选择...</option>
						<?php
							foreach($groups as $val)
							{
								echo '<option value="'.$val['group_id'].'" '.((isset($user['group_id'])) ? ( ($user['group_id'] == $val['group_id']) ? 'SELECTED' : '' ) : '').'>'.$val['group_name'].'</option>';
							}
						?>
					</select>
				</td>
			</tr>		
		</table>
		<div class="button-div">
			<input type="submit" class="button" value=" 确定 " name="submit">
			<input type="reset" class="button" value=" 重置 " name="reset">
		</div>
		</form>
	</div>
</div>