<br /><br /><br /><br /><br /><br />
<FORM action="<?php echo site_url("user/login"); ?>" method="post" name="pass" id="pass">
	<table width="400" border="0" align="center" cellpadding="0" cellspacing="0" class="tableborder">
		<tr class="header">
			<td colspan="3" align="right"><div align="right">管理登入</div></td>
		</tr>
		<tr>
			<td colspan="3"><span>
			<?php 
				if(!empty($notification)):
			?>
					<span style="color:#FF0000;font-size:18px"><?php echo $notification ?></span>
			<?php
				else:
			?>
					&nbsp;
			<?php
				endif;
			?>
			</td>
		</tr>
		<tr>
			<td width="149" height="40" align="right"><div align="right">用户名：            </div></td>
			<td width="251" colspan="2"><input type="text" name="username"></td>
		</tr>
		<tr>
			<td height="40" align="right"><div align="right">密码：</div></td>
			<td colspan="2"><input type="password" name="password"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2"><input class="submit" type="submit" value="Login" name="submit"></td>
		</tr>
	</table>