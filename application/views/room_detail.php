<div id="nav">
	<span class="action-span"><a href="<?php echo site_url() ?>"  target="_top">管理系统</a></span>
	<span class="action-span"> » <a href="<?php echo site_url('room/one/'.$room_info['room_id']) ?>" target="main-frame"><?php echo $room_info['room_name'] ?></a></span>
	 » <?php echo ($extendinterface_id == 0) ? '主界面' : $extend_interfaces[$extendinterface_id]['extendinterface_name'] ?>
</div>

<div id="main">
	<div id="ei_nav">
		<p>
			<span class="navbar-<?php echo ($extendinterface_id == 0) ? 'front' : 'back'?>"><a href="<?php echo site_url('room/detail/'.$room_info['room_id']) ?>">主界面</a></span>
			<?php if(!empty($video_info)): ?>
			<span class="navbar-back"><a href="<?php echo site_url('room/video/'.$room_info['room_id']) ?>">视频</a></span>
			<?php endif; ?>
		<?php
			foreach($extend_interfaces as $val)
				echo '<span class="navbar-'.(($extendinterface_id == $val['extendinterface_id']) ? 'front' : 'back').'"><a href="'.site_url('room/detail/'.$room_info['room_id'].'/'.$val['extendinterface_id']).'">'.$val['extendinterface_name'].'</a></span>';
		?>
		</p>
	</div>


	<div class="room_main">
		<div class="room_main_title">
			<table>
				<tr>
					<td width="150px" align="center">数据名称</td>
					<td width="50px" align="center">数据</td>
					<td width="30px" align="center">状态</td>
					<td width="200px" align="center" style="padding-left:10px;padding-right:10px">策略</td>
					<?php if(is_admin()): ?><td width="70px" align="center">策略操作</td><?php endif; ?>
				</tr>
			</table>
		</div>
		<ul id="sortable">
		<?php
			foreach($device as $val)
			{
				$device_keys[] = $val['device_id'];
				echo '
				<li value="'.$val['device_id'].'">
					<table><tr>
						<td width="150px">'.$val['device_name'].'</td>
						<td width="50px" align="center" class="d_value" id="value_'.$val['device_id'].'"><img src="'.img_base_url().'icon/wait.gif"></td>
						<td width="30px" align="center" class="d_status" id="status_'.$val['device_id'].'"><img src="'.img_base_url().'icon/wait.gif"></td>';
				
				echo '<td width="200px" style="padding-left:10px;padding-right:10px">';
				if(isset($strategy[$val['device_id']]))
				{
					$strategy_arr = array();
					foreach($strategy[$val['device_id']] as $one)
						$strategy_arr[] = '<span title="'.$one['warning_content'].'">'.$one['strategy_name'].'</span>';
					
					echo implode(', ', $strategy_arr);
				}
				echo '</td>';
				
				if(is_admin())
					echo '<td width="70px" align="center"><span class="'.(is_kaiguan($val['device_cat'], $val['device_num']) ? 'edit_num_strategy' : 'edit_strategy').'" value="'.$val['device_id'].'"><a href="#">编辑/新加</a></span></td></tr>';
				echo '</table>
				</li>';
			}
		?>
		</ul>
	<div>
</div>

<div style="margin:10px;text-align:right">
	<a href="<?php echo site_url('history/room_id='.$room_info['room_id']) ?>">历史及报警记录</a>
</div>

<?php if(is_admin()): ?>
<div id="dialog-modal" title="Basic modal dialog" style="display:none">
	<h3>现有的策略</h3>
	<div id="strategy_now"><img src="<?php echo img_base_url() ?>icon/wait.gif"></div>
	<h3>新加策略</h3>
	<form action="<?php echo site_url('strategy/add') ?>" method="post">
	<table>
		<tr><td>策略名: </td><td><input name="strategy_name" type="text" value="" size="40" /></td></tr>
		<tr><td>策略值: </td><td><input name="value" type="text" value="" size="40" /></td></tr>
		<tr><td>策略条件: </td><td><input type="radio" name="condition" value="gt" />大于 <input type="radio" name="condition" value="eq" />等于 <input type="radio" name="condition" value="lt" />小于 </td></tr>
		<tr><td>报警级别: </td><td><input type="radio" name="level" value="0" />正常 <input type="radio" name="level" value="1" />1级 <input type="radio" name="level" value="2" />2级 <input type="radio" name="level" value="3" />3级 <input type="radio" name="level" value="4" />4级 <input type="radio" name="level" value="5" />5级</td></tr>
		<tr><td>报警内容: </td><td><textarea name="content" cols="40" rows="4"></textarea></td></tr>
		<tr><td>分组: </td><td>
		<?php
		foreach($groups as $group)
		{
			echo '<input type="checkbox" name="group_id[]" value="'.$group['group_id'].'">'.$group['group_name']." ";
		}
		?>
		</td></tr>	
		<tr><td>时间段: </td><td>
		<?php
		foreach($periods as $period)
		{
			echo '<input type="radio" name="period_id" value="'.$period['period_id'].'">'.$period['period_name']." ";
		}
		?>
		</td></tr>
		<tr><td>是否启用声音报警: </td><td><input id="sound_alert_1" type="radio" name="sound_alert" value="1" />是 <input id="sound_alert_0" type="radio" name="sound_alert" value="0" />否</td></tr>
		<tr><td colspan="2">
			<input type="submit" name="submit" value="添加新策略" />
			<input type="hidden" id="input_device_id" name="device_id" value="" />
			<input type="hidden" name="room_id" value="<?php echo $room_info['room_id'] ?>" />
		</td></tr>
	</table>
	</form>
</div>

<div id="dialog-modal-num" title="Basic modal dialog" style="display:none">
	<h3>现有的策略</h3>
	<div id="strategy_now_num"><img src="<?php echo img_base_url() ?>icon/wait.gif"></div>
	<h3>新加策略</h3>
	<form action="<?php echo site_url('strategy/add') ?>" method="post">
	<table>
		<tr><td>策略名: </td><td><input name="strategy_name" type="text" value="" size="40" /></td></tr>
		<tr><td>策略条件: </td><td><input type="radio" name="value" value="0" />等于0 <input type="radio" name="value" value="1" />等于1</td></tr>
		<tr><td>报警级别: </td><td><input type="radio" name="level" value="0" />正常 <input type="radio" name="level" value="1" />1级 <input type="radio" name="level" value="2" />2级 <input type="radio" name="level" value="3" />3级 <input type="radio" name="level" value="4" />4级 <input type="radio" name="level" value="5" />5级</td></tr>
		<tr><td>报警内容: </td><td><textarea name="content" cols="40" rows="4"></textarea></td></tr>
		<tr><td>分组: </td><td>
		<?php
		foreach($groups as $group)
		{
			echo '<input type="checkbox" name="group_id[]" value="'.$group['group_id'].'">'.$group['group_name']." ";
		}
		?>
		</td></tr>	
		<tr><td>时间段: </td><td>
		<?php
		foreach($periods as $period)
		{
			echo '<input type="radio" name="period_id" value="'.$period['period_id'].'">'.$period['period_name']." ";
		}
		?>
		</td></tr>
		<tr><td>是否启用声音报警: </td><td><input id="sound_alert_1_num" type="radio" name="sound_alert" value="1" />是 <input id="sound_alert_0_num" type="radio" name="sound_alert" value="0" />否</td></tr>
		<tr><td colspan="2">
			<input type="submit" name="submit" value="添加新策略" />
			<input type="hidden"  name="condition" value="eq" />
			<input type="hidden" id="input_device_id_num" name="device_id" value="" />
			<input type="hidden" name="room_id" value="<?php echo $room_info['room_id'] ?>" />
		</td></tr>
	</table>
	</form>
</div>

<div id="dialog-modal2" title="Basic modal dialog" style="display:none">
	<h3>编辑策略</h3>
	<form action="<?php echo site_url('strategy/edit') ?>" method="post">
	<table>
		<tr><td>策略名: </td><td><input id="edit_strategy_name" name="strategy_name" type="text" value="" size="40" /></td></tr>
		<tr><td>策略值: </td><td><input id="edit_value" name="value" type="text" value="" size="40" /></td></tr>
		<tr><td>策略条件: </td><td><input id="condition_gt" type="radio" name="condition" value="gt" />大于 <input id="condition_eq" type="radio" name="condition" value="eq" />等于 <input id="condition_lt" type="radio" name="condition" value="lt" />小于 </td></tr>
		<tr><td>报警级别: </td><td><input id="level_0" type="radio" name="level" value="0" />正常 <input id="level_1" type="radio" name="level" value="1" />1级 <input id="level_2" type="radio" name="level" value="2" />2级 <input id="level_3" type="radio" name="level" value="3" />3级 <input id="level_4" type="radio" name="level" value="4" />4级 <input id="level_5" type="radio" name="level" value="5" />5级</td></tr>
		<tr><td>报警内容: </td><td><textarea id="content" name="content" cols="40" rows="4"></textarea></td></tr>
		<tr><td>分组: </td><td>
		<?php
		foreach($groups as $group)
		{
			echo '<input type="checkbox" id="group_id_'.$group['group_id'].'" name="group_id[]" value="'.$group['group_id'].'">'.$group['group_name']." ";
		}
		?>
		</td></tr>	
		<tr><td>时间段: </td><td>
		<?php
		foreach($periods as $period)
		{
			echo '<input type="radio" id="period_id_'.$period['period_id'].'" name="period_id" value="'.$period['period_id'].'">'.$period['period_name']." ";
		}
		?>
		</td></tr>
		<tr><td>是否启用声音报警: </td><td><input id="edit_sound_alert_1" type="radio" name="sound_alert" value="1" />是 <input id="edit_sound_alert_0" type="radio" name="sound_alert" value="0" />否</td></tr>
		<tr><td colspan="2">
			<input type="submit" name="submit" value="编辑策略" />
			<input type="hidden" id="strategy_id" name="strategy_id" value="" />
			<input type="hidden" name="room_id" value="<?php echo $room_info['room_id'] ?>" />
		</td></tr>
	</table>
	</form>
</div>


<div id="dialog-modal2-num" title="Basic modal dialog" style="display:none">
	<h3>编辑策略</h3>
	<form action="<?php echo site_url('strategy/edit') ?>" method="post">
	<table>
		<tr><td>策略名: </td><td><input id="edit_strategy_name_num" name="strategy_name" type="text" value="" size="40" /></td></tr>
		<tr><td>策略条件: </td><td><input id="eq0" type="radio" name="value" value="0" />等于0 <input id="eq0" type="radio" name="value" value="1" />等于1</td></tr>
		<tr><td>报警级别: </td><td><input id="level_0_num" type="radio" name="level" value="0" />正常 <input id="level_1_num" type="radio" name="level" value="1" />1级 <input id="level_2_num" type="radio" name="level" value="2" />2级 <input id="level_3_num" type="radio" name="level" value="3" />3级 <input id="level_4_num" type="radio" name="level" value="4" />4级 <input id="level_5_num" type="radio" name="level" value="5" />5级</td></tr>
		<tr><td>报警内容: </td><td><textarea id="content_num" name="content" cols="40" rows="4"></textarea></td></tr>
		<tr><td>分组: </td><td>
		<?php
		foreach($groups as $group)
		{
			echo '<input type="checkbox" id="group_id_'.$group['group_id'].'_num" name="group_id[]" value="'.$group['group_id'].'">'.$group['group_name']." ";
		}
		?>
		</td></tr>	
		<tr><td>时间段: </td><td>
		<?php
		foreach($periods as $period)
		{
			echo '<input type="radio" id="period_id_'.$period['period_id'].'_num" name="period_id" value="'.$period['period_id'].'">'.$period['period_name']." ";
		}
		?>
		</td></tr>
		<tr><td>是否启用声音报警: </td><td><input id="edit_sound_alert_1_num" type="radio" name="sound_alert" value="1" />是 <input id="edit_sound_alert_0_num" type="radio" name="sound_alert" value="0" />否</td></tr>
		<tr><td colspan="2">
			<input type="submit" name="submit" value="编辑策略" />
			<input type="hidden"  name="condition" value="eq" />
			<input type="hidden" id="strategy_id_num" name="strategy_id" value="" />
			<input type="hidden" name="room_id" value="<?php echo $room_info['room_id'] ?>" />
		</td></tr>
	</table>
	</form>
</div>

<div id="dialog-modal3" title="Basic modal dialog" style="display:none"></div>
<?php endif; ?>

<script>
	$(document).ready(function() {
	<?php if(is_admin()): ?>
		$( "#sortable" ).sortable({
			revert: true ,
			placeholder: "ui-state-highlight",
			cursor: 'move',
		});
		
		$( "#sortable" ).bind( "sortstop", function(event, ui) {
			$.post(site_url+"/api/update_rank", { room_id: <?php echo $room_info['room_id'] ?>, extendinterface_id: <?php echo $extendinterface_id?>, device_id: $(ui.item[0]).attr("value"), prev_device_id: $(ui.item[0]).prev().attr("value"), next_device_id: $(ui.item[0]).next().attr("value")},
				function (data, textStatus){
					
			}, "text");
		});
		
		$( ".edit_strategy" ).click(function(){
			$( "#dialog-modal" ).dialog({
				title: '编辑/新加策略',
				width: 600,
				modal: true,
				show: 'slide',
				hide: 'fade',
			});
			
			var device_id =  $(this).attr("value");
			$("#input_device_id").attr("value", device_id);
			$.post(site_url+"/api/get_strategy", { device_id: device_id},
				function (data, textStatus){
					var html_content = '';
					if(data == '-1')
						html_content += '无记录';
					else
					{
						html_content += '<table width="100%" cellspacing="1"><tr><th align="center">策略名</th><th align="center">策略值</th><th align="center">策略条件</th><th align="center">报警级别</th><th align="center">用户组</th><th align="center">时间段</th><th align="center">操作</th>';

						$.each(data, function(i, field){
							html_content += '<tr>';
							html_content += '<td align="center" style="padding:4px 0 4px 0">' + UrlDecode(field.strategy_name) + '</td>';
							html_content += '<td align="center">' + UrlDecode(field.value) + '</td>';
							html_content += '<td align="center">' + UrlDecode(field.condition) + '</td>';
							html_content += '<td align="center">' + UrlDecode(field.warning_level) + '</td>';
							html_content += '<td align="center">';
							if(field.groups != undefined)
							{
								$.each(field.groups, function(j, group_obj){
									html_content += UrlDecode(group_obj.group_name) + ', ';
								});
							}
							html_content += '</td>';
							html_content += '<td align="center">' + UrlDecode(field.period_name) + '</td>';
							html_content += '<td align="center"><a href="#" class="edit_one_strategy" value="' + UrlDecode(field.strategy_id) + '">编辑</a> <a href="#" class="del_strategy" value="' + UrlDecode(field.strategy_id) + '">删除</a></td>';
							html_content += '</tr>';
						});
						
						html_content += '</table>';
					}
					$( "#strategy_now" ).html(html_content);
					
					$( ".edit_one_strategy" ).click(function(){
						$( "#dialog-modal" ).dialog('closeOnEscape', false);
						$( "#dialog-modal2" ).dialog({
							title: '编辑/新加策略',
							width: 600,
							modal: true,
							show: 'slide',
							hide: 'fade',
							close: function(event, ui) {
								$( "#dialog-modal" ).dialog('closeOnEscape', true);
							}
						});
						
						$("#strategy_id").attr('value', $(this).attr('value'));
						$.post(site_url+"/api/get_one_strategy", { strategy_id: $(this).attr('value')},
							function (result, textStatus){
								var html_content = '';
								if(result == '-1')
									html_content += '无法找到所选的策略，请返回重试！';
								else
								{
									$("#edit_strategy_name").attr('value', UrlDecode(result.strategy_name));
									$("#edit_value").attr('value', UrlDecode(result.value));
									$("#condition_" + UrlDecode(result.condition)).attr('checked', 'checked');
									$("#level_" + UrlDecode(result.warning_level)).attr('checked', 'checked');
									$("#period_id_" + UrlDecode(result.period_id)).attr('checked', 'checked');
									$("#content" ).html(UrlDecode(result.warning_content));
									$.each(result.groups, function(i, field){
										$("#group_id_" + UrlDecode(field)).attr('checked', 'checked');
									});
									
									$("#edit_sound_alert_" + UrlDecode(result.sound_alert)).attr('checked', 'checked');
									
								}
						}, "json");
					});
					
					$( ".del_strategy" ).click(function(){
						$( "#dialog-modal" ).dialog('closeOnEscape', false);
						$( "#dialog-modal3" ).dialog({
							title: '删除策略',
							modal: true,
							show: 'slide',
							hide: 'fade',
							close: function(event, ui) {
								$( "#dialog-modal" ).dialog('closeOnEscape', true);
							}
						});
						$(this).parent().parent().remove();
						$( "#dialog-modal3" ).html('<img src="'+base_url+'images/icon/wait.gif">');
						$.post(site_url+"/api/delete_one_strategy", { strategy_id: $(this).attr('value')},
							function (result, textStatus){
								var html_content = '';
								if(result == 'OK')
								{
									html_content += '删除成功！';
								}
								else
								{
									html_content += '删除失败。。';
								}
								$( "#dialog-modal3" ).html(html_content);
						});
					});
			}, "json");
		});
		
		$( ".edit_num_strategy" ).click(function(){
			$( "#dialog-modal-num" ).dialog({
				title: '编辑/新加策略',
				width: 600,
				modal: true,
				show: 'slide',
				hide: 'fade',
			});
			
			var device_id =  $(this).attr("value");
			$("#input_device_id_num").attr("value", device_id);
			$.post(site_url+"/api/get_strategy", { device_id: device_id},
				function (data, textStatus){
					var html_content = '';
					if(data == '-1')
						html_content += '无记录';
					else
					{
						html_content += '<table width="100%" cellspacing="1"><tr><th align="center">策略名</th><th align="center">策略条件</th><th align="center">报警级别</th><th align="center">用户组</th><th align="center">时间段</th><th align="center">操作</th>';

						$.each(data, function(i, field){
							html_content += '<tr>';
							html_content += '<td align="center" style="padding:4px 0 4px 0">' + UrlDecode(field.strategy_name) + '</td>';
							html_content += '<td align="center">';
							if(UrlDecode(field.value) == 0) {
								html_content += '等于0';
							} else {
								html_content += '等于1';
							}
							html_content += '</td>';
							html_content += '<td align="center">' + UrlDecode(field.warning_level) + '</td>';
							html_content += '<td align="center">';
							if(field.groups != undefined)
							{
								$.each(field.groups, function(j, group_obj){
									html_content += UrlDecode(group_obj.group_name) + ', ';
								});
							}
							html_content += '</td>';
							html_content += '<td align="center">' + UrlDecode(field.period_name) + '</td>';
							html_content += '<td align="center"><a href="#" class="edit_one_strategy_num" value="' + UrlDecode(field.strategy_id) + '">编辑</a> <a href="#" class="del_strategy_num" value="' + UrlDecode(field.strategy_id) + '">删除</a></td>';
							html_content += '</tr>';
						});
						
						html_content += '</table>';
					}
					$( "#strategy_now_num" ).html(html_content);
					
					$( ".edit_one_strategy_num" ).click(function(){
						$( "#dialog-modal-num" ).dialog('closeOnEscape', false);
						$( "#dialog-modal2-num" ).dialog({
							title: '编辑/新加策略',
							width: 600,
							modal: true,
							show: 'slide',
							hide: 'fade',
							close: function(event, ui) {
								$( "#dialog-modal-num" ).dialog('closeOnEscape', true);
							}
						});
						
						$("#strategy_id_num").attr('value', $(this).attr('value'));
						$.post(site_url+"/api/get_one_strategy", { strategy_id: $(this).attr('value')},
							function (result, textStatus){
								
								var html_content = '';
								if(result == '-1')
									html_content += '无法找到所选的策略，请返回重试！';
								else
								{
									$("#edit_strategy_name_num").attr('value', UrlDecode(result.strategy_name));
									if(UrlDecode(result.value) == 0) {
										$("#eq0").attr('checked', 'checked');
									} else {
										$("#eq0").attr('checked', 'checked');
									}
									
									$("#level_" + UrlDecode(result.warning_level) + "_num").attr('checked', 'checked');
									$("#period_id_" + UrlDecode(result.period_id) + "_num").attr('checked', 'checked');
									$("#content" + "_num").html(UrlDecode(result.warning_content));
									$.each(result.groups, function(i, field){
										$("#group_id_" + UrlDecode(field) + "_num").attr('checked', 'checked');
									});
									
									$("#edit_sound_alert_" + UrlDecode(result.sound_alert) + "_num").attr('checked', 'checked');
								}
						}, "json");
					});
					
					$( ".del_strategy_num" ).click(function(){
						$( "#dialog-modal-num" ).dialog('closeOnEscape', false);
						$( "#dialog-modal3" ).dialog({
							title: '删除策略',
							modal: true,
							show: 'slide',
							hide: 'fade',
							close: function(event, ui) {
								$( "#dialog-modal-num" ).dialog('closeOnEscape', true);
							}
						});
						
						$(this).parent().parent().remove();
						$( "#dialog-modal3" ).html('<img src="'+base_url+'images/icon/wait.gif">');
						$.post(site_url+"/api/delete_one_strategy", { strategy_id: $(this).attr('value')},
							function (result, textStatus){
								var html_content = '';
								if(result == 'OK')
								{
									html_content += '删除成功！';
									
								}
								else
								{
									html_content += '删除失败。。';
								}
								$( "#dialog-modal3" ).html(html_content);
						});
					});
			}, "json");
		});
		
		<?php endif;?>
		refresh_device_status();
		
		setInterval("refresh_device_status()", <?php $CI = & get_instance();echo $CI->config->config['site_setting']['basic']['refresh_interval']; ?>);
	});
	
	function refresh_device_status() {
	
		$.each($(".d_value"), function(i, field){
			$(field).html('<img src="'+base_url+'images/icon/wait.gif">');
		});
		$.each($(".d_status"), function(i, field){
			$(field).html('<img src="'+base_url+'images/icon/wait.gif">');
		});
			
		$.ajax({
				async: false,
				type: "POST",
				url: site_url+"api/get_device_status",
				dataType: 'json',
				data: "room_id=<?php echo $room_info['room_id'] ?>&extendinterface_id=<?php echo $extendinterface_id?>&devices=<?php echo (isset($device_keys)) ? implode(',', $device_keys) : '' ?>",
				success: function(data){
					$.each(data, function(i, field){
						$( "#value_"+field.device_id ).html(field.val);
						$( "#status_"+field.device_id ).html('<img src="'+base_url+'images/icon/w_'+field.status+'.gif" alt="'+field.status+'">');
					});
				}
			});
	}
</script>