<div id="nav">
	<span class="action-span"><a href="<?php echo site_url() ?>"  target="_top">管理系统</a></span>
	<span class="action-span"> » <a href="<?php echo site_url('room/one/'.$room_info['room_id']) ?>" target="main-frame"><?php echo $room_info['room_name'] ?></a></span>
	 » 视频
</div>
<div id="main">
	<div id="ei_nav">
		<p>
			<span class="navbar-back"><a href="<?php echo site_url('room/detail/'.$room_info['room_id']) ?>">主界面</a></span>
			<span class="navbar-front"><a href="<?php echo site_url('room/video/'.$room_info['room_id']) ?>">视频</a></span>
		<?php
			foreach($extend_interfaces as $val)
				echo '<span class="navbar-back"><a href="'.site_url('room/detail/'.$room_info['room_id'].'/'.$val['extendinterface_id']).'">'.$val['extendinterface_name'].'</a></span>';
		?>
		</p>
	</div>
	<iframe src="<?php echo base_url(); ?>video/cn/?ip=<?php echo $video_info['ip'] ?>&user=<?php echo $video_info['username'] ?>&pwd=<?php echo $video_info['password'] ?>&port=<?php echo $video_info['port'] ?>" width="100%" height="420px" style="maegin-top:10px"></iframe>
</div>
