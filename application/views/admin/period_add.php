<div id="nav">
	<span class="action-span"><a href="<?php echo site_url('admin') ?>"  target="_top">管理系统</a></span>
	<span class="action-span"> » <a href="<?php echo site_url('admin/period') ?>" target="main-frame">时间段管理</a></span>
	 » 添加时间段
</div>
<div id="main">
	<div id="main_body">
		<?php if(isset($notification) && !empty($notification)): ?>
		<div style="backgroud:#fff;padding:5px;border:1px solid #FF8080;text-align:center">
			<img style="vertical-align: middle;" src="<?php echo img_base_url() ?>icon/warning.gif"> <span style="color:red;font-size:20px;line-height:22px"><?php echo $notification;?></span>
		</div>
		<?php endif;?>
		
		<form action="<?php echo site_url('admin/period/add') ?>" method="post" name="addstaff">
		<table width="90%" id="shop_info-table">
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>时间段名称: </td>
				<td>
					<input id="period_name" name="period_name" type="text" value="<?php echo (isset($period['period_name'])) ? $period['period_name'] :''; ?>" size="30" />
				</td>
			</tr>
			<tr>
				<td class="label" valign="top"><span class="notice-star"> * </span>时间段: </td>
				<td>
					<?php
					for($day = 1; $day <= 7; $day++)
					{
						echo '<input name="period[]" type="checkbox" value="'.$day.'"/>周'.$day.' '.show_hour_options('s_hour['.$day.']').show_mins_options('s_mins['.$day.']').'至'.show_hour_options('e_hour['.$day.']').show_mins_options('e_mins['.$day.']').'<br/>';
					}
					?>
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