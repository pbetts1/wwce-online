<?=form_open('advanced/search');?>
			<section class="row-fluid">
				<section class="span3">
					<form>
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
					</form>
				</section>
				
				<section class="span9">
					<form>
						<fieldset>
							<legend>
								Basic Search
							</legend>
							<label>Find an Intervention</label>
							<input type="text" name="name" class="input-block-level">
						</fieldset>
					</form>
				</section>
				
			</section>
			
			<section class="row-fluid">
				<section class="span6">
					<form>
						<fieldset>
							<legend>
								Overall Desgination
							</legend>
							<label>Intervention Effectiveness:</label>
							<select name="population" size="1">
								<option selected>Select one...</option>
								<option value="afr">Effective</option>
								<option value="ant">Unproven</option>
								<option value="asia">Iatrogenic</option>
							</select>
						</fieldset>
					</form>
				</section>

				<section class="span6">
					<form>
						<fieldset>
							<legend>
								Target Population
							</legend>
							<label>Grade level of Intervention</label>
										  
										 <?= $options = array(
						                  'selected'  => 'Select one...',
						                  'elem'    => 'Elementary',
						                  'mid'   => 'Middle School',
						                  'high' => 'High School',
						                );
						
						$target = array('elem', 'mid', 'high'); ?>
						
					echo form_dropdown('target', $options, 'selected');
						
						</fieldset>
					</form>
				</section>
				
			</section>
			
			<section class="row-fluid">

				<section class="span6">
					<form>
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
					</form>
				</section>
				
				<section class="span6">
					<form>
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
						<?=form_input($search); <input type=submit value='Search' class='btn'/> ?>
						
					</form>
				</section>
  <?=form_close();?>
			</section>