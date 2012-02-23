<div id="header-div">
  <div id="submenu-div">
	<ul>
		<li><a href="<?php echo site_url('user/logout') ?>"  target="_top">退出</a></li>
		<li><a href="<?php echo site_url('user/change_psd') ?>" target="main-frame">修改密码</a></li>
		<li><a href="<?php echo site_url('room') ?>" target="main-frame">全部机房情况</a></li>
		<li style="border-left:none;"><font style="color:#FF7F24">欢迎回来, <?php echo $user_info['username'] ?></font></li>
	</ul>
    <div id="load-div" style="padding: 5px 10px 0 0; text-align: right; color: #FF9900; display: none;width:40%;float:right;"><img src="<?php echo img_base_url() ?>top_loader.gif" width="16" height="16" alt="正在处理您的请求..." style="vertical-align: middle" /> 正在处理您的请求...</div>
  </div>
</div>