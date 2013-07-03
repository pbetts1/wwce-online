<?=form_open('advanced/search');?>
		<section  class="span10 offset1">
			<section class="row-fluid">
				<section class="span3">
	
				
						<?php 
									echo form_fieldset('Search Option');
							        echo form_label('And');	 
									echo form_radio('option', 'and');
									echo form_label('Or');	 
									echo form_radio('option', 'or','checked');
					                echo form_fieldset_close(); 
					                  
					        ?>
					
						
	
				</section>
				
				<section class="span9">
					
					<?php 
									echo form_fieldset('Basic Search');
							        echo form_label('Find an Intervention');	
									echo form_input('basic', set_value('basic'));
					                echo form_fieldset_close(); 
					                  ?>
		
				</section>
				
			</section>
			
			<section class="row-fluid">
				<section class="span6">
					
							 <?php 
									echo form_fieldset('Overall Designation');
							        echo form_label('Intervention Effectiveness');	 
										 $options = array(
						                  ''  => 'Select one...',
						                  'Effective'    => 'Effective',
						                  'Unproven'   => 'Unproven',
						                  'Iatrogenic' => 'Iatrogenic'); 
						
					                   echo form_dropdown('effective', $options, set_value('effective'));
					                   echo form_fieldset_close(); 
					                  ?>
					
				</section>

				<section class="span6">

							<?php
							echo form_fieldset('Target Population');
							echo form_label('Grade Level Intervention');
                             $options = array(
						                  ''  => 'Select one...',
						                  'Elementary'    => 'Elementary',
						                  'Middle School'   => 'Middle School',
						                  'High School' => 'High School'); 
						   
					        echo form_dropdown('target', $options, set_value('target'));
                            echo form_fieldset_close(); 
                            ?>			  
									
								
				
				 
				</section>
				
			</section>
			
			<section class="row-fluid">

				<section class="span6">
			
						<?php
							echo form_fieldset('Outcome Category');
							echo form_label('Outcome of Intervention');
                             $options = array(
						                  ''  => 'Select one...',
						                  'Risk Behavior'    => 'Risk Behavior',
						                  'Competencies'   => 'Pro-Social Competencies',
						                  'School Based outcome' => 'School-Based outcome',
						                  'Emotional' => 'General Social-Emotional'); 
						   
					        echo form_dropdown('outcomes', $options, set_value('outcomes'));
                            echo form_fieldset_close(); 
                            ?>			  
		        </section>
				
				<section class="span6">	
					
						<?php
							echo form_fieldset('Implementation Strategy');
							echo form_label('Content and Pedagogical Elements');
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
				
				</section>
  <?=form_close();?>
			</section>
		</section>