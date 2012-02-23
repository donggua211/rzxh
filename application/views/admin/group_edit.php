<div id="nav">
	<span class="action-span"><a href="<?php echo site_url('admin') ?>"  target="_top">管理系统</a></span>
	<span class="action-span"> » <a href="<?php echo site_url('admin/user') ?>" target="main-frame">用户管理</a></span>
	 » 编辑分组
</div>
<div id="main">
	<div id="main_body">
		<?php if(isset($notification) && !empty($notification)): ?>
		<div style="backgroud:#fff;padding:5px;border:1px solid #FF8080;text-align:center">
			<img style="vertical-align: middle;" src="<?php echo img_base_url() ?>icon/warning.gif"> <span style="color:red;font-size:20px;line-height:22px"><?php echo $notification;?></span>
		</div>
		<?php endif;?>
		
		<form action="<?php echo site_url('admin/group/edit') ?>" method="post" name="addstaff">
		<table width="90%" id="shop_info-table">
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>用户名: </td>
				<td>
					<input id="group_name" name="group_name" type="text" value="<?php echo (isset($group['group_name'])) ? $group['group_name'] :''; ?>" size="30" />
				</td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>权限: </td>
				<td>
					<table>
					<tr><td><input type="checkbox" value="" name="check_all_room" />全选</td><td><input type="radio" value="1" name="check_all_role" />全可读</td><td><input type="radio" value="5" name="check_all_role" />全可配置</td></tr>
					<?php
					foreach($rooms as $room)
						echo '
						<tr>
							<td style="border-bottom:1px"><input name="room['.$room['room_id'].']" type="checkbox" value="'.$room['room_id'].'" '.(isset($group['role'][$room['room_id']]) ? 'CHECKED' : '').'/>'.$room['room_name'].'</td>
							<td style="border-bottom:1px"><input name="role['.$room['room_id'].']" type="radio" value="'.GROUP_ROLE_READABLE.'" '.((isset($group['role'][$room['room_id']]) && $group['role'][$room['room_id']] == GROUP_ROLE_READABLE) ? 'CHECKED' : '').'/>可读</td>
							<td><input name="role['.$room['room_id'].']" type="radio" value="'.GROUP_ROLE_CONFIGABLE.'" '.((isset($group['role'][$room['room_id']]) && $group['role'][$room['room_id']] == GROUP_ROLE_CONFIGABLE) ? 'CHECKED' : '').'/>可读可配置</td></tr>';
					?>
					</table>
				</td>
			</tr>
		</table>
		
		<div class="button-div">
			<input type="hidden" value="<?php echo $group['group_id'] ?>" name="group_id">
			<input type="submit" class="button" value=" 确定 " name="submit">
			<input type="reset" class="button" value=" 重置 " name="reset">
		</div>
		</form>
		<div class="title" style="margin-top:20px"></div>
	</div>
</div>


<div id="dialog-modal" title="Basic modal dialog" style="display:none"></div>

<script type="text/javascript">
	$(document).ready(function() {
		$("input[name='check_all_room']").click(function(){
			if($(this).attr("checked") == 'checked')
				$("input[name^='room']").attr("checked", "checked");
			else
				$("input[name^='room']").removeAttr("checked");
		});
		
		$("input[name='check_all_role']").click(function(){
			var value = $(this).attr("value");
			
			if($(this).attr("checked") == 'checked')
				$("input[name^='role'][value="+value+"]").attr("checked", "checked");
			else
				$("input[name^='role'][value="+value+"]").removeAttr("checked");
		});
	});
</script>