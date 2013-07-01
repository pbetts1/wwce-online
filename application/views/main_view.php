<?= $this->load->view('header_view'); ?>
<section class="row-fluid">
				<article class="span5 offset1">
					<h2>WWCE Database</h2>
					<p>
						What Works in Character Education represents an effort to uncover and synthesize existing scientific research on the effects of K-12 character education. It is made up of a brief overview of the project, a description of the main findings, a set of guidelines on effective character education practice,and some brief cautionary remarks regarding how to interpret these findings. It is intended to provide practical advice for educators derived from a review of the research.What Works in Character Education was made possible by the Character Education Partnership (CEP).
					</p>
				</article>

				<section class="span5 offset1">
					<p>
				<div class="input-append">
					<?=form_open('main_controller/search');?>
                    <?php $search = array('name'=>'search','id'=>'appendedInputButton','value'=>"");?>
                <?=form_input($search);?><input class='btn' type='submit' value='Search' /></p>
                <?=form_close();?>
                </div>
               </p>
               <p>
               	<?= anchor('advanced/index/','Advanced Search'); ?>
               	
               </p>
               <p>
               	<?= anchor('main_controller/search/','View All Studies'); ?>
               	
               </P>
               
				</section>

			</section>
<?= $this->load->view('footer_view'); ?>

