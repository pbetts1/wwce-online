<?=form_open('advanced/search');?>
			<section class="row-fluid">
				<section class="span3">
	
						<fieldset>
							<legend>
								Search Option
							</legend>
							<label class="checkbox inline">
							  <input type="checkbox" id="inlineCheckbox1" value="option1"> and
							</label>
							<label class="checkbox inline">
							  <input type="checkbox" id="inlineCheckbox2" value="option2"> or
							</label>
						</fieldset>
	
				</section>
				
				<section class="span9">
					
						<fieldset>
							<legend>
								Basic Search
							</legend>
							<label>Find an Intervention</label>
							<input type="text" name="name" class="input-block-level">
						</fieldset>
					
				</section>
				
			</section>
			
			<section class="row-fluid">
				<section class="span6">
					
						<fieldset>
							<legend>
								Overall Desgination
							</legend>
							<label>Intervention Effectiveness:</label>
							
							 <?php 
										 
										 $options = array(
						                  'selected'  => 'Select one...',
						                  'Effective'    => 'Effective',
						                  'Unproven'   => 'Unproven',
						                  'Iatrogenic' => 'Iatrogenic'); 
						                
						
					
						
					                   echo form_dropdown('effective', $options, 'selected');
					
					                  ?>
						</fieldset>
					
				</section>

				<section class="span6">

							<?php
							echo form_fieldset('Target Population');
							echo form_label('Grade Level Intervention');
                             $options = array(
						                  'selected'  => 'Select one...',
						                  'Elementary'    => 'Elementary',
						                  'Middle School'   => 'Middle School',
						                  'High School' => 'High School'); 
						   
					        echo form_dropdown('target', $options, 'selected');
                            echo form_fieldset_close(); 
                            ?>			  
									
								
				
				 
				</section>
				
			</section>
			
			<section class="row-fluid">

				<section class="span6">
			
						<fieldset>
							<legend>
								Outcome Category
							</legend>
							<label>Outcomel of Intervention</label>
							<select name="outcome" size="1">
								<option selected>Select one...</option>
								<option value="afr">Risk Behavior</option>
								<option value="ant">Pro-Social Competencies</option>
								<option value="asia">School-Based outcome</option>
								<option value="asia">General Social-Emotional</option>
							</select>
							<button class="btn">Outcome Taxonomy</button>
						</fieldset>
						
						<?php
							echo form_fieldset('Outcome Category');
							echo form_label('Outcome of Intervention');
                             $options = array(
						                  'selected'  => 'Select one...',
						                  'Risk Behavior'    => 'Risk Behavior',
						                  'Competencies'   => 'Pro-Social Competencies',
						                  'School Based outcome' => 'School-Based outcome',
						                  'Emotional' => 'General Social-Emotional'); 
						   
					        echo form_dropdown('outcomes', $options, 'selected');
                            echo form_fieldset_close(); 
                            ?>			  
						
						
						
						
						
						
						
				
				</section>
				
				<section class="span6">
				
						<fieldset>
							<legend>
								Implementation Strategy
							</legend>
							<label>Content and Pedagogical Elements</label>
							<select name="outcome" size="1">
								<option selected>Select one...</option>
								<option value="afr">Explicit Chatacter Education Program</option>
								<option value="ant">Social and Emotional Curriculum</option>
								<option value="asia">Academic Curriculum Integration</option>
								<option value="asia">Direct Teaching Strategies</option>
								<option value="asia">Interactive Treaching/Learning Strategies</option>
								<option value="asia">Classroom/Behavior Management Strategies</option>
								<option value="asia">School-Wide or Institutional Organization</option>
								<option value="asia">Modeling/Mentoring</option>
								<option value="asia">FamilyCommunity Participation</option>
								<option value="asia">Community Service/Service Learning</option>
								<option value="asia">Profesional Development</option>
							</select>
						</fieldset>
						<input type=submit value='Search' class='btn'/>
						
				
				</section>
  <?=form_close();?>
			</section>