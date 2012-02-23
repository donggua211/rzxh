<div id="nav">
	<span class="action-span"><a href="<?php echo site_url() ?>"  target="_top">管理系统</a></span>
	 » 历史记录
</div>

<div id="main">
	<div class="room_main">
		<div class="form-div">
		  <form action="<?php echo site_url('history')?>" method="POST" name="searchForm">
			<img src="<?php echo img_base_url() ?>icon/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
			<select id="room_id" name="room_id">
				<option value='0'>所有机房</option>
				<?php
					foreach($rooms as $room)
						echo '<option value="'.$room['room_id'].'" ' . ( ($room['room_id'] == $filter['room_id']) ? 'SELECTED' : '' ) . '>'.$room['room_name'].'</option>';
				?>
			</select>
			
			<select id="device_id" name="device_id">
				<option value='0'>全部设备</option>
			</select>
			
			开始时间: <input name="add_time_a" id="add_time_a" type="text" value="<?php echo $filter['add_time_b'] ?>" size='10'/>
			截止时间: <input name="add_time_b" id="add_time_b" type="text" value="<?php echo $filter['add_time_b'] ?>" size='10'/>
			
			<input type="submit" name="submit" value="查询历史数据" class="button" />
			<input type="submit" name="submit" value="查询报警数据" class="button" />
		  </form>
		</div>
		
		<div id="listDiv" class="list-div">
		  <form action="<?php echo site_url('admin/student/sms_batch')?>" method="POST" id="sms_batch" target="_blank">
			<table cellspacing='1' id="list-table">
				<tr>
					<th>机房名称</th>
					<th>设备名称</th>
					<th>数据</th>
					<th>开始时间</th>
					<th>记录时间</th>
				</tr>
				<?php foreach($history as $val): ?>
				<tr>
					<td align="center"><?php echo $val['room_name'] ?></td>
					<td align="center"><?php echo $val['device_name'] ?></td>
					<td align="center"><?php echo $val['value'] ?></td>
					<td align="center"><?php echo $val['start_time'] ?></td>
					<td align="center"><?php echo $val['add_time'] ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
		  </form>
		</div>
		<!-- 分页 -->
		<?php echo $page_nav; ?>
	<div>
</div>


<script type="text/javascript">
	$( "#add_time_a" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
	});
	
	$( "#add_time_b" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
	});
	
	
	update_device_option($("#room_id option:selected").attr("value"));
	
	$( '#room_id' ).change(function(){
		var room_id = $("#room_id option:selected").attr("value");
		update_device_option(room_id);
		
	});
	
	function update_device_option(room_id) {
		$("#device_id > option").remove();
		$("#device_id").append('<option value="0">全部设备</option>');
		
		if(room_id == 0) {
			return true;
		}
		
		$.post(site_url+"/api/get_device", { room_id: room_id},
			function (result, textStatus){
				
				if(result != '-1')
				{
					$.each(result, function(i, filed){
						$("#device_id").append('<option value="'+i+'">'+filed+'</option>');
					});
				}
		}, "json");
	}
</script>