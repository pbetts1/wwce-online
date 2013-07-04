
<?php foreach($query as $item):?>
		
			<section class="span10 offset1">
				<section>
				<h1><?= $item->	Program_Synopsis; ?></h1>
				<h3>STUDIES: <?= $item->	Number_of_Studies; ?></h3>
				<table class="table table-striped table-bordered">
					<caption class="program">
						Program Description 
					</caption> 
					<tbody>                  
						<tr>
							<th style="width: 25%">Target Population</th>
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
					<caption class="program">
						History
					</caption>
					<tbody>
						<tr>
							<th style="width: 25%">Date of origin</th>
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
					<caption class="program">
						Requirements
					</caption>
					<tbody>
						<tr>
							<th style="width: 25%">Material purchases</th>
							<td><?= $item->	Material_Purchases; ?></td>
						</tr>
						<tr>
							<th>Staff development</th>
							<td><?= $item->	Staff_Development; ?></td>
						</tr>
					</tbody>
				</table>
				<table id="outcomes" class="table table-striped table-bordered">
					<caption class="program">
						Outcomes
					</caption>
					<tbody>
						<tr>
							<th style="width: 25%">Outcomes</th>
							<td><?= nl2br($item->Outcomes_Taxonomy); ?></td>
							
						</tr>
						
					</tbody>
				</table>
	
						
			</section>
            
    <div id="accordion">		
            <?php include 'singledb.php'; ?>
            
            <?php $program = $item -> ProgramID; ?>
            
            
            <?php
					
					// Retrieve all the data from the "example" table
					$result = mysql_query("SELECT * FROM research_study where ProgramID = $program")
				
					or die(mysql_error());  
					
					// store the record of the "example" table into $row
					//$row = mysql_fetch_array( $result );
            
	        $num_row = 1;
			 
					 while ($row = mysql_fetch_assoc($result)) {
           
echo'<h3>';
echo "(".$num_row.")&nbsp;".$row['Study_slug']."...";
echo'</h3>';   
echo "<div>";
				
						 echo "<p>";
				   echo $row['Study']."\n\n";
				   echo "</p>";
				
				echo "<table class=\"table table-bordered\">";
									echo "<thead>";
										echo "<th>Sample size</th>";
										echo "<th>Comparison group</th>";
										echo "<th>Statistical test of Significance</th>";
										echo "<th>Publication</th>";
										echo "<th>Implementation</th>";
										echo "<th>Scientific Quality</th>";
									echo "</thead>";
									echo "<tbody>";
										echo "<tr>";
											echo "<td>".$row['Sample_Size']."</td>";
											echo "<td>".$row['Comparison_group']."</td>";
											echo "<td>".$row['Statistical_test_of_Significance']."</td>";
											echo "<td>".$row['Publication']."</td>";
											echo "<td>".$row['Implementation']."</td>";
											echo "<td>".$row['Scientific_Quality']."</td>";
										echo "</tr>";
									echo "</tbody>";
								echo "</table>";
				
				
	                          echo"<h5>Research Questions</h5>";
								 echo "<p>";
				                 echo nl2br($row['Research_Questions']);
				                 echo "</p>";
								 
								 
								 echo"<h5>Methods</h5>";
								 echo "<p>";
								 echo nl2br($row['Methods']);
								 echo "</p>";
								 
								 echo"<h5>Results</h5>";
								 echo "<p>";
								 echo nl2br($row['Results']);
								 echo "</p>";
	 
				
	echo "</div>";		
	
	$num_row ++;	   
	 }


		?>			
								
		
			
		
				<h3>Additional Information</h3>	
			<div>
				
				<table id="outcomes" class="table table-striped table-bordered">
					
					
					
					<tbody>
						<tr>
<<<<<<< HEAD
							<th style="width: 25%">Awards and Recognition</th>
							<td><?= $item->Intervention_Strategies; ?></td>
=======
							<th>Awards and Recognition</th>
							<td><?= nl2br($item->Intervention_Strategies); ?></td>
>>>>>>> more changes
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
			</div>
       </div>
			
			</section>
		
			
		<?php endforeach;?>
      