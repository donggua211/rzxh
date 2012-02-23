<div id="nav">
	<span class="action-span"><a href="<?php echo site_url() ?>"  target="_top">管理系统</a></span>
	» <?php echo $room_info['room_name'] ?>
</div>

<div id="main">
	<div class="room_main">
		<ul class=menu2>
		  <?php
			foreach($device as $val)
			{
				$device_keys[] = $val['device_id'];
				echo '<li><a id="d_'.$val['device_id'].'" href="#" value="'.$val['device_name'].'"><img src="'.img_base_url().'icon/wait.gif"></span><em>'.$val['device_name'].'</em></a></li>';
			}
			?>
		</ul>
	<div>
</div>

<div style="clear:both"></div>

<div style="margin:10px;text-align:right">
	<a href="<?php echo site_url('room/detail/'.$room_info['room_id']) ?>">查看详情</a>
</div>

<div style="margin:10px;text-align:right;clear:both">
	<a href="<?php echo site_url('history/room_id='.$room_info['room_id']) ?>">历史及报警记录</a>
</div>

<div id="dialog-modal" title="Basic modal dialog" style="display:none"></div>

<script>
	$(document).ready(function() {
		refresh_device_status();
		setInterval("refresh_device_status()", <?php $CI = & get_instance();echo $CI->config->config['site_setting']['basic']['refresh_interval']; ?>);
		
		$(".menu2 a").hover(function() {
			$(this).find("em").show();
		}, function() {
			$(this).find("em").hide();
		});
	});
	
	function refresh_device_status() {
	
		$.each($(".d_status"), function(i, field){
			$(field).html('<img src="'+base_url+'images/icon/wait.gif">');
		});
		
		$.ajax({
			async: false,
			type: "POST",
			url: site_url+"api/get_device_status",
			dataType: 'json',
			data: "room_id=<?php echo $room_info['room_id'] ?>&extendinterface_id=0",
			success: function(data){
				var sound_alert = false;
				var has_warning = false;
				var warning = new Array();
				$.each(data, function(i, field){
					$( "#d_"+field.device_id ).find("img").attr("src", base_url+'images/icon/w_'+field.status+'.gif');
					
					hovertext = $( "#d_"+field.device_id ).attr("value") + '<br/>value: ' + field.val;
					$( "#d_"+field.device_id ).find("em").html(hovertext);
					
					if(field.status != '0')
					{
						has_warning = true;
						warning.push(field);
					}
					
					if(field.sound_alert == '1')
						sound_alert == true;
				});
				
				if(has_warning == true) {
					var html_str = '<a name="dialog_modal_title_top"></a><table cellpadding="1" class="w_table"><tr><th>设备</th><th align="center">值</th><th align="center">状态</th><th align="center">报警策略</th><th align="center">报警策略描述</th></tr>';
					$.each(warning, function(i, text){
						if(typeof(text) != 'undefined') 
						{
							var status_text = '';
							if(text.status == <?php echo DEVICE_STATE_GET_FAILED ?>) {
								status_text = '获取失败';
							}
							else if(text.status == <?php echo DEVICE_STATE_GET_EMPTY ?>) {
								status_text = '值无效';
							}
							else if(text.status == <?php echo DEVICE_STATE_GET_NONE ?>) {
								status_text = '设备未连接';
							}
							else {
								status_text = text.status+'级';
							}
							
							var val_text = '';
							if(text.status == '<?php echo DEVICE_STATE_GET_FAILED ?>')
								val_text = '获取失败';
							else if(text.status == '<?php echo DEVICE_STATE_GET_EMPTY ?>')
								val_text = '值无效';
							else if(text.status == '<?php echo DEVICE_STATE_GET_NONE ?>')
								val_text = '设备未连接';
							else
								val_text = text.val;
							
							
							html_str += '<tr><td>'+$( "#d_"+i ).attr("value")+'</td><td align="center">'+val_text+'</td><td align="center">'+status_text+'</td><td align="center">'+text.active_strategy.strategy_name+'</td><td align="center">'+text.active_strategy.warning_content+'</td></tr>';
						}
					})
					html_str += '</table>';
					
					$( "#dialog-modal" ).html(html_str);
					
					flash_title();
					
					$( "#dialog-modal" ).dialog({
						title: '报警',
						position: 'bottom',
						zIndex: -1,
						width: '90%',
						height: 350,
						show: 'slide',
						hide: 'fade',
						close: function(event, ui) { clear_flash(); sound_alerm('stop');}
					});
					
					if(sound_alert) {
						sound_alerm('start');
					}
					
					location.hash = 'dialog_modal_title_top';
				} else {
					$( "#dialog-modal" ).dialog( "close" );
					clear_flash();
					sound_alerm('stop');
				}
			}
		});
	}
	
	var step=0, _title = document.title, time_out_id;  //计数器变量,初始值为0
	function flash_title(){
		step++;  //变量递增
		if (step==3) {step=1};  //两种变化,所以大于3时回到1
		if (step==1) {document.title='【　　】'+_title};
		if (step==2) {document.title='【报警】'+_title};
		time_out_id = setTimeout("flash_title()", 1 * 1000);  //每一秒钟变换一次
	}
	function clear_flash() {
		clearInterval(time_out_id); 
		document.title = _title;
	}
	
	function sound_alerm(action){
		if(action == 'start'){
			document.getElementById("mp").play();
		}
		else{
			document.getElementById("mp").pause();
		}
	}
</script>
<embed src="<?php echo img_base_url() ?>warn.mp3" id="mp" align="center" border="0" autostart="true" loop="true" style="display:none">