<script language="javascript">
    var start = 5; 
	function GoTo()
	{
		if(start == 1)
		{
			document.getElementById('second').innerHTML = start;
			window.location= '<?php echo $back_url ?>';
		}
		else
		{
			start=start-1;
			document.getElementById('second').innerHTML = start;
			setTimeout( 'GoTo() ',1000);
		}
	}

	window.onload = function()
	{
		setTimeout( "GoTo() ",1000);
	}
</script>
<div id="main" style="height:100%">
	<div class="result">
		<img src="<?php echo img_base_url() ?>icon/ok.gif"><span><?php echo $notification;?></span>
	</div>
	<div style="margin:10px;">
		<span id="second">5</span>秒后会刷新页面，或者请点击链接：<a href="<?php echo $back_url ?>">返回</a>
	</div>
</div>