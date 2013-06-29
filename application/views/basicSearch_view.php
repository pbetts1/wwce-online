

	<table class="table table-striped table-bordered">
	
	<?php foreach($query as $item):?>
	<tr>

	<td><?=  anchor('main_controller/results/'.$item->ProgramID, $item->Program_Synopsis) ?></td>
	<td> <?= nl2br($item->Goals_Mission_Target_outcomes); ?></td>
	
	</tr>
	
	<?php endforeach;?>
</table>
