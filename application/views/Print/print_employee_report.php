
<html>

<body>
	<table width="100%" border=0 cellspacing="0" colspacing="0">
		<tr>
			<td align="center">
				<p style="font-size:16px; font-weight:bold;"> Employee report</p>
			</td>
			
		</tr>
	</table>

	
	<br>

	<table width='100%' border=1 cellspacing="0" colspacing="0">
		<thead>
			<tr>
            <th>Sr.no</th>
				<th>Employee Name</th>
				<th>Branch</th>
				<th>Designation</th>
			</tr>
		</thead>
		<tbody>
        <?php if (!empty($records)) : ?>
				<?php $i = 1;
				foreach ($records as $row) : ?>
					<tr>
						<td><?php echo $i;
							$i++; ?></td>
						
						<td>
							<?php echo $row->user_name; ?>
						</td>
						<td><?php if($row->branch==1) echo 'Sharjah'; else if($row->branch==2) echo 'Dubai' ; else if($row->branch==3) echo 'Ajman'?></td>
						<td><?php echo $row->designation_name; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</body>

</html>
