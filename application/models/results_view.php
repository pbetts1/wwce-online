<?php foreach($query as $item):?>
			<section class="span10 offset1">
				<section>
				<h1><?= $item->	Program_Synopsis; ?></h1>
				<h3>STUDIES: <?= $item->	Number_of_Studies; ?></h3>
				<table class="table table-striped table-bordered">
					<caption>
						Program Description
					</caption>
					<tbody>
						<tr>
							<th>Target Population</th>
							<td><?= $item->	Target_population; ?></td>
						</tr>
						<tr>
							<th>Distributor</th>
							<td><?= $item->	Distributor; ?></td>
						</tr>
						<tr>
							<th>Goals/Mission/Target outcomes</th>
							<td>
							
									<?= nl2br($item->Goals_Mission_Target_outcomes); ?>	
							</td>
						</tr>
						<tr>
							<th>Delivery agents</th>
							<td><?= $item->	Delivery_Agents; ?></td>
						</tr>
						<tr>
							<th>Intervention Strategies</th>
							<td>
							<?= nl2br($item->Intervention_Strategies); ?>	
							
							</td>
						</tr>
						<tr>
							<th>Duration</th>
							<td><?= $item->	Duration; ?></td>
						</tr>
					</tbody>
				</table>

				<table class="table table-striped table-bordered">
					<caption>
						<?= $item->	History; ?>
					</caption>
					<tbody>
						<tr>
							<th>Date of origin</th>
							<td><?= $item->	Date_of_Origin; ?></td>
						</tr>
						<tr>
							<th>Developer</th>
							<td><?= $item->	Developer; ?></td>
						</tr>
						<tr>
							<th>Initial Mission</th>
							<td><?= $item->	Initial_Mission; ?></td>
						</tr>
						<tr>
							<th>Changes in mission</th>
							<td><?= $item->	Changes_in_Mission; ?></td>
						</tr>
						<tr>
							<th>Stages in expansion/revision</th>
							<td><?= nl2br($item->Stages_in_expansion_revision); ?></td>
						</tr>
					</tbody>
				</table>

				<table id="program" class="table table-striped table-bordered">
					<caption>
						Requirements
					</caption>
					<tbody>
						<tr>
							<th>Material purchases</th>
							<td><?= $item->	Material_Purchases; ?></td>
						</tr>
						<tr>
							<th>Staff development</th>
							<td><?= $item->	Staff_Development; ?></td>
						</tr>
					</tbody>
				</table>
				<table id="outcomes" class="table table-striped table-bordered">
					<caption>
						Outcomes
					</caption>
					<tbody>
						<tr>
							<th>Outcomes</th>
							<td><?= nl2br($item->Outcomes_Taxonomy); ?></td>
							
						</tr>
						
					</tbody>
				</table>
	
						
			</section>

			<section>
				<h3>Research</h3>
				<div class="accordion" id="accordion2">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"> <h4>Study #1</h4> </a>
						</div>
						<div id="collapseOne" class="accordion-body collapse">
							<div class="accordion-inner">
								<p>
									Hansen, W.B. (1996) Pilot Test Results Comparing the All Stars Program with Seventh Grade D.A.R.E.: Program Integrity and Mediating Variable Analysis. Clemmons, NC Tanglewood Research, Inc.
								</p>
								<table class="table table-bordered">
									<thead>
										<th>Sample size</th>
										<th>Comparison group</th>
										<th>Statistical test of Significance</th>
										<th>Publication</th>
										<th>Implementation</th>
										<th>Scientific Quality</th>
									</thead>
									<tbody>
										<tr>
											<td>Adequate</td>
											<td>Partial or unclear basis for assignment</td>
											<td>Statistical tests and significance reported</td>
											<td>Unpublished but with complete methodological report</td>
											<td>Implementation assessed</td>
											<td>Acceptable</td>
										</tr>
									</tbody>
								</table>
								<h5>Research Questions</h5>
								<p>
									What are the effects of the All Stars program on behavior as well as mediating variables when measured in a large scale randomized longitudinal study?
								</p>
								<h5>Methods</h5>
								<ul>
									<li>
										1,910 students (11-13 years old) in 6th and 7th grade from 14 middle schools in two cities in Kentucky were studied.
									</li>
									<li>
										Pre-test administered in September or October, followed by one academic year of the program, followed by post-test administered in late May.
									</li>
									<li>
										Conditions:
									</li>
									<ul>
										<li>
											Seven program specialists who received extensive one-week training and 23 regular teachers who received one-half day training administered the program.
										</li>
										<li>
											Schools were divided as follows:
										</li>

										<ul>
											<li>
												Eight received the program (program schools)
											</li>
											<ul>
												<li>
													Program specialists taught in five of the schools (specialist condition).
												</li>
												<li>
													Regular teachers taught in three of the schools (teacher condition).
												</li>
											</ul>
											<li>
												Six schools did not receive the program (control condition).
											</li>
										</ul>
									</ul>
									<li>
										Process Evaluation: Random samples of each classroom lesson were selected for observation. A total of 799 classroom sessions were observed. Ratings (coded 1=all to 4=none) were assigned based on the following:
									</li>
									<ul>
										<li>
											The extent to which the teaching methods were effective.
										</li>
										<li>
											The extent to which the objectives were achieved.
										</li>
										<li>
											The extent to which students were involved.
										</li>
										<li>
											Each observed lesson was also given an overall rating (coded 1=excellent to 4=poor)
										</li>
									</ul>
									<li>
										Mediator variables targeted by the program were as follows:
									</li>
									<ul>
										<li>
											Degree of bonding with school.
										</li>
										<li>
											Strength of personal commitments.
										</li>
										<li>
											Positivity of ideals.
										</li>
										<li>
											Beliefs in conventional norms.
										</li>
									</ul>
									<li>
										Outcome Evaluation: variables analyzed were as follows:
									</li>
									<ul>
										<li>
											Substance abuse
										</li>
										<li>
											Sexual activity
										</li>
										<li>
											Violence
										</li>
									</ul>
									<li>
										Outcome evaluation questionnaire for students included the following:
									</li>
									<ul>
										<li>
											Demographic information � Ethnicity, age and sex.
										</li>
										<li>
											Substance abuse � use and frequency.
										</li>
										<li>
											Sexual behavior � 10-item Adolescent Sexual Activity Index (ASAI) and frequency of sexual intercourse in past 30 days.
										</li>
										<li>
											Violence � 10 items selected from extant delinquency scales to focus on violence towards other people.
										</li>
										<li>
											Mediating variables of bonding, commitments, ideals and norms.
										</li>
									</ul>
								</ul>
								<h5>Results</h5>
								<ul>
									<li>
										Fidelity (Process evaluation)
									</li>
									<ul>
										<li>
											In 90% of the 799 classroom sessions observed, most or all of the teaching methods were rated as �effective,� most or all of the objectives were achieved and most or all of the students were involved.
										</li>
										<li>
											85% of the sessions were rated as �good� or �excellent� overall.
										</li>
										<li>
											While ratings for teachers ranged from �very good� to �excellent,� those for trained specialists were higher.
										</li>
									</ul>
									<li>
										Outcome variables:
									</li>
									<ul>
										<li>
											Substance use and frequency
										</li>
										<ul>
											<li>
												Frequency of substance use increased between pre- and post-test for students in the control and specialist conditions compared to students in the teacher condition who remained at pre-test levels. The differences, however, were not significant.
											</li>
										</ul>
										<li>
											Sexual activity
										</li>
										<ul>
											<li>
												ASAI scores showed no significant program effects between pre- and post-test.
											</li>
											<li>
												For frequency of sex in the past 30 days, students in the control condition reported significantly higher levels of sexual intercourse and greater numbers of partners at post-test than at pre-test compared to students in teacher and specialist conditions.
											</li>
										</ul>
										<li>
											Violence
										</li>
										<ul>
											<li>
												No significant program effects.
											</li>
										</ul>
									</ul>
									<li>
										Mediator variables:
									</li>
									<ul>
										<li>
											Bonding to school
										</li>
										<ul>
											<li>
												Bonding decreased between pre- and post-test among students in control condition and remained unchanged among students in specialist condition, it increased significantly among students in teacher condition.
											</li>
										</ul>
										<li>
											Commitment
										</li>
										<ul>
											<li>
												Strength of commitment was significantly greater for students in teacher condition than for those in the control or specialist conditions.
											</li>
										</ul>
										<li>
											Ideals
										</li>
										<ul>
											<li>
												Students taught in teacher condition reported significantly more positive ideals than students in the control or specialist conditions.
											</li>
										</ul>
										<li>
											Normative beliefs
										</li>
										<ul>
											<li>
												No significant program effects.
											</li>
										</ul>
									</ul>

								</ul>
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> Study #2 </a>
						</div>
						<div id="collapseTwo" class="accordion-body collapse">
							<div class="accordion-inner">
								<b>Study #2 Info Goes In Here</b>
							</div>
						</div>
					</div>
				</div>
			</section>
			
			
			<section>
				<table id="outcomes" class="table table-striped table-bordered">
					<caption>
						Additional Information
					</caption>
					<tbody>
						<tr>
							<th>Awards and Recognition</th>
							<td><?= $item->Intervention_Strategies; ?></td>
						</tr>
						<tr>
							<th>Variations</th>
							<td><?= nl2br($item->Variations); ?></td>
						</tr>
						<tr>
							<th>Contact Information</th>
							<td><?= nl2br($item->Contact_Information); ?></td>
						</tr>
						<tr>
							<th>References</th>
							<td><?=   nl2br($item->References); ?></td>
						</tr>
						
					</tbody>
				</table>
			</section>
			</section>
		<?php endforeach;?>

			