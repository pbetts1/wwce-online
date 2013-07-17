<?php

if (empty($query)){

echo '<h1>';
echo'Sorry your search returned no results';
echo '</h1>';
	
}

	
?>

<div class="container-fluid">
				<div class="row-fluid">
					<div class="span3" style="background-color: #ccc; padding: 0 20px;">
						<?=form_open('advanced/search');?>
									<legend>
										Refine Search
									</legend>
									<div class="form-inline" style= padding-right: 5px;">
									<?php 
									echo form_fieldset('Search Option');
							        echo form_label('And');	 
									echo form_radio('option', 'and');
									echo form_label('Or');	 
									echo form_radio('option', 'or','checked');
					                echo form_fieldset_close(); 
					                  
					               ?>
					               </div>
									<?php 
									echo form_fieldset('Overall Designation'); 
										 $options = array(
						                  ''  => 'Select one...',
						                  'Effective'    => 'Effective',
						                  'Unproven'   => 'Unproven',
						                  'Iatrogenic' => 'Iatrogenic'); 
						
					                   echo form_dropdown('effective', $options, set_value('effective'));
					                   echo form_fieldset_close(); 
					                  ?>
					
									<?php
							echo form_fieldset('Target Population');							
                             $options = array(
						                  ''  => 'Select one...',
						                  'Elementary'    => 'Elementary',
						                  'Middle School'   => 'Middle School',
						                  'High School' => 'High School'); 
						   
					        echo form_dropdown('target', $options, set_value('target'));
                            echo form_fieldset_close(); 
                            ?>			
									<?php
							echo form_fieldset('Outcome Category');							
                             $options = array(
						                  ''  => 'Select one...',
						                  'Risk Behavior'    => 'Risk Behavior',
						                  'Competencies'   => 'Pro-Social Competencies',
						                  'School Based outcome' => 'School-Based outcome',
						                  'Emotional' => 'General Social-Emotional'); 
						   
					        echo form_dropdown('outcomes', $options, set_value('outcomes'));
                            echo form_fieldset_close(); 
                            ?>			 
											<?php
							echo form_fieldset('Implementation Strategy');
                             $options = array(
						                  ''  => 'Select one...',
						                  'Explicit Chatacter Education Program'    => 'Explicit Chatacter Education Program',
						                  'Social and Emotional Curriculum'   => 'Social and Emotional Curriculum',
						                  'Academic Curriculum Integration' => 'Academic Curriculum Integration',
						                  'Direct Teaching Strategies' => 'Direct Teaching Strategies',
						                  'Interactive Teaching Learning Strategies' => 'Interactive Teaching/Learning Strategies',
						                  'Classroom Behavior Management Strategies'   => 'Classroom/Behavior Management Strategies',
						                  'Modeling Mentoring' => 'Modeling/Mentoring',
						                  'Family Community Participation<s' => 'Family Community Participation',
						                  'Community Service Service Learning' => 'Community Service/Service Learning',
						                  'Profesional Development' => 'Profesional Development'); 
						   
					        echo form_dropdown('strategy', $options, set_value('strategy'));
                            echo form_fieldset_close(); 
                          
                  
						  ?>	
						     <input class='btn' type='submit' value='Search' />
				
				
  <?=form_close();?>
					</div>
					
	

<div class="span9">
	<table class="table table-striped table-bordered">
										<thead style="background-color: #ccc;">
										<th style="width: 25%">Program</th>
										<th style="width: 10%"># of Studies</th>
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
</table>
</tbody>
</div>
</div>
</div>