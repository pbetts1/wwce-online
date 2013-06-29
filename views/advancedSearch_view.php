<html>
<body>
	<table>
	
	<?php foreach($query as $item):?>
	<tr>

	<td><?=  anchor('main_controller/results/'.$item->ProgramID, $item->Program_Synopsis) ?></td>
	</tr>
	<?php endforeach;?>
</table>
</body>
</html>