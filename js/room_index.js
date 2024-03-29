$(document).ready(function() {
	refresh_device_status();
});


function sleep(numberMillis) {   
	var now = new Date();   
	var exitTime = now.getTime() + numberMillis;  
	while (true) {
	now = new Date();      
	if (now.getTime() > exitTime)
		return;   
	}
} 

function refresh_device_status() {
	$.ajax({
			async: false,
			type: "POST",
			url: site_url+"api/get_room_status",
			dataType: 'json',
			data: "",
			success: function(data){
				var has_warning = false;
				var sound_alert = false;
				var warning = new Array();
				$.each(data, function(i, field){
					$( "#r_"+i ).find("img").attr("src", base_url+'images/icon/w_'+field.status+'.gif');
					warning[i] = new Array();
					warning[i][0] = $( "#r_"+i ).attr("value");
					warning[i][1] = new Array();
					hovertext = '<b>机房</b>：'+$( "#r_"+i ).attr("value") + '<br/>';
					if(field.hasOwnProperty('device'))
					{
						$.each(field.device, function(j, device){
							warning[i][1][j] = device;
							hovertext += device.name + ': ' + device.val + '<br/>';
							has_warning = true;
						});
					}
					
					$( "#r_"+i ).find("em").html(hovertext);
				});
				
				if(has_warning == true) {
					var html_str = '<a name="dialog_modal_title_top"></a><table cellpadding="1" class="w_table">';
					$.each(warning, function(i, text){
						if(typeof(text) != 'undefined' && typeof(text[1]) != 'undefined' && text[1].length > 0) {
							html_str += '<tr><td colspan="5"><h3>'+text[0]+'</h3></td></tr><tr><th>设备</th><th align="center">值</th><th align="center">状态</th><th align="center">报警策略</th><th align="center">报警策略描述</th></tr>';
							$.each(text[1], function(j,text2){
								if(typeof(text2) != 'undefined')
								{
									var status_text = '';
									if(text2.status == '<?php echo DEVICE_STATE_GET_FAILED ?>')
										status_text = '获取失败';
									else if(text2.status == '<?php echo DEVICE_STATE_GET_EMPTY ?>')
										status_text = '值无效';
									else if(text2.status == '<?php echo DEVICE_STATE_GET_NONE ?>')
										status_text = '设备未连接';
									else
										status_text = text2.status+'级';
									
									var val_text = '';
									if(text2.status == '<?php echo DEVICE_STATE_GET_FAILED ?>')
										val_text = '获取失败';
									else if(text2.status == '<?php echo DEVICE_STATE_GET_EMPTY ?>')
										val_text = '值无效';
									else if(text2.status == '<?php echo DEVICE_STATE_GET_NONE ?>')
										val_text = '设备未连接';
									else
										val_text = text2.val;
									
									html_str += '<tr><td width="200">'+text2.name+'</td><td align="center">'+val_text+'</td><td align="center">'+status_text+'</td><td align="center">'+text2.active_strategy.strategy_name+'</td><td align="center">'+text2.active_strategy.warning_content+'</td></tr>';
									
									
									
									if(text2.active_strategy.sound_alert == '1')
									{
										sound_alert == true;
									}
								}
							})
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
					
					if(sound_alert == false) {
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
		document.getElementById("mp").stop();
	}
}