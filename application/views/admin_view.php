<h2>Create a Record</h2>

<?php echo form_open('admin_controller/create'); ?>
<?php echo form_close(); ?>






<table class="table table-striped table-bordered">
										<thead style="background-color: #ccc;">
										<th>Program</th>
										<th>No. of Studies</th>
										<th>Synopsis</th>
									    </thead>
									    <tbody>
	
	<?php foreach($query as $item):?>
	<tr>

	<td><?=  anchor('main_controller/results/'.$item->ProgramID, $item->Program_Synopsis) ?></td>
	<td> <?= nl2br($item->Number_of_Studies); ?></td>
	<td> <?= nl2br($item->Goals_Mission_Target_outcomes); ?></td>
	
	</tr>
	
	<?php endforeach;?>
</tbody>
</table>