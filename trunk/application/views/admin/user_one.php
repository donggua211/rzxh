<div id="nav">
	<div id="nav_left">
		<span class="action-span"><a href="<?php echo site_url('admin') ?>"  target="_top">管理系统</a></span>
		<span class="action-span"> » <a href="<?php echo site_url('admin/staff') ?>" target="main-frame">员工管理</a></span>
		 » <?php echo $staff['name'] ?>
	</div>
	<div id="nav_right">
		所在校区: <span><?php echo $staff['branch_name'] ?></span>
	</div>
</div>
<div style="clear:both"></div>
<div id="main">
	<div id="main_navbar">
		<p>
			<span class="navbar-front"><a href="<?php echo site_url('admin/staff/one/'.$staff['staff_id']) ?>">基本信息</a></span>
			
			<span class="navbar-back"><a href="<?php echo site_url('admin/staff/one/'.$staff['staff_id'].'/schedule') ?>">时间表</a></span>
			
			<span class="navbar-back"><a href="<?php echo site_url('admin/staff/one/'.$staff['staff_id'].'/timetable') ?>">课程表</a></span>
			
			<?php
			//access control
			$CI = & get_instance();
			if($CI->admin_ac_staff->view_staff_one_sms()):
			?>
			<span class="navbar-back"><a href="<?php echo site_url('admin/staff/one/'.$staff['staff_id'].'/sms') ?>">短信记录</a></span>
			<?php endif; ?>
		</p>
	</div>
	
	<div id="main_body">
		<?php if(isset($notification) && !empty($notification)): ?>
		<div style="backgroud:#fff;padding:5px;border:1px solid #FF8080;text-align:center">
			<img style="vertical-align: middle;" src="<?php echo img_base_url() ?>icon/warning.gif"> <span style="color:red;font-size:20px;line-height:22px"><?php echo $notification;?></span>
		</div>
		<?php endif;?>
		<table width="90%">
			<tr>
				<td class="label" valign="top">姓名: </td>
				<td><?php echo $staff['name'] ?></td>
			</tr>
			<tr>
				<td class="label" valign="top">性别: </td>
				<td>
					<?php
						echo !(empty($staff['gender'])) ? ( $staff['gender'] == 'm' ? '男' : '女' ) : '无';
					?>
				</td>
			</tr>
			<tr>
				<td class="label" valign="top">生日: </td>
				<td><?php echo $staff['dob']?></td>
			</tr>
			<tr>
				<td class="label" valign="top">星级: </td>
				<td><?php echo $staff['level']?>星级</td>
			</tr>
			<?php if(!empty($staff['subject_name'])): ?>
			<tr>
				<td class="label" valign="top">学科: </td>
				<td><?php echo $staff['subject_name']?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="label" valign="top">年级: </td>
				<td><?php echo $staff['grade_name']?></td>
			</tr>
			<tr>
				<td class="label" valign="top">电话: </td>
				<td><?php echo (isset($staff['phone'])) ? $staff['phone'] :''; ?></td>
			</tr>
			<tr>
				<td class="label" valign="top">咨询QQ: </td>
				<td><?php echo (isset($staff['qq']) && !empty($staff['qq'])) ? $staff['qq'] : ''; ?></td>
			</tr>
			<tr>
				<td class="label" valign="top">电子邮箱: </td>
				<td><?php echo (isset($staff['email'])) ? $staff['email'] :''; ?></td>
			</tr>
			<tr>
				<td class="label" valign="top">地址: </td>
				<td>
					<?php echo (isset($staff['address'])) ? $staff['address'] :''; ?>
				</td>
			</tr>
			<tr>
				<td class="label" valign="top">添加时间: </td>
				<td><?php echo (isset($staff['add_time'])) ? $staff['add_time'] :''; ?></td>
			</tr>
			<tr>
				<td class="label" valign="top">备注: </td>
				<td><?php echo (isset($staff['remark'])) ? $staff['remark'] :''; ?></td>
			</tr>
		
		</table>
		<div class="button-link-div">
			<?php
			//access control
			$CI = & get_instance();
			if($CI->admin_ac_staff->staff_management_ac($staff)):
			?>
			<a href="<?php echo site_url('admin/staff/edit/'.$staff['staff_id']) ?>">编辑</a>
			<?php endif; ?>
			<a href="javascript:void(0);" onclick="history.back(-1)">返回</a>
		</div>
	</div>
</div>