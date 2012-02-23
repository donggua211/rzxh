<div id="nav">
	<span class="action-span"><a href="<?php echo site_url('admin') ?>"  target="_top">管理系统</a></span>
	<span class="action-span"> » <a href="<?php echo site_url('admin/user') ?>" target="main-frame">用户管理</a></span>
	 » 查看分组
</div>
<div id="main">
	<div id="main_body">
		<?php if(isset($notification) && !empty($notification)): ?>
		<div style="backgroud:#fff;padding:5px;border:1px solid #FF8080;text-align:center">
			<img style="vertical-align: middle;" src="<?php echo img_base_url() ?>icon/warning.gif"> <span style="color:red;font-size:20px;line-height:22px"><?php echo $notification;?></span>
		</div>
		<?php endif;?>
		
		<div id="listDiv" class="list-div">
			<table cellspacing='1' id="list-table">
				<tr>
					<th>分组名称</th>
					<th>操作</th>
				</tr>
				<?php foreach($groups as $group): ?>
				<tr>
					<td align="center"><?php echo $group['group_name'] ?></td>
					<td align="center">
						<a href="<?php echo site_url('admin/group/edit/'.$group['group_id']) ?>">编辑</a>
						<a onclick="return confirm('确定要删除?');" href="<?php echo site_url('admin/group/delete/'.$group['group_id'])?>">删除</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		  </form>
		</div>
	</div>