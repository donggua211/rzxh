<table width="100%" cellspacing="0">
  <tr>
	<td align="right" nowrap="true">
		总计  <span id="totalRecords"><?php echo $total ?></span>个记录, 
		分为 <span id="totalPages"><?php echo $total_page ?></span>页, 
		当前第 <span id="pageCurrent"><?php echo $current_page ?></span>页.
		<span id="page-link">
			<a href="<?php echo pack_fileter_url(1, $base_url, $filter); ?>">第一页</a>
			<?php if(isset($previous)): ?>
				<a href="<?php echo pack_fileter_url($previous, $base_url, $filter) ?>">上一页</a>
			<?php endif; ?>
			<?php if(isset($next)): ?>
			<a href="<?php echo pack_fileter_url($next, $base_url, $filter); ?>">下一页</a>
			<?php endif; ?>
			<a href="<?php echo pack_fileter_url($last_page, $base_url, $filter); ?>">最末页</a>
			跳转到:
			<select onchange="window.location.href=this.options[this.selectedIndex].value">
				<?php for( $i = 1; $i <= $total_page; $i++):?>
				<option value="<?php echo pack_fileter_url($i, $base_url, $filter);?>" <?php if($i == $current_page) echo 'SELECTED';?>>
					<?php echo $i;?>
				</option>
				<?php endfor;?>
			</select>
		</span>
	</td>
  </tr>
</table>