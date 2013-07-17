<div class="container">
	<div class="row">
		<div class="span4 offset4 well">
			<legend><?php echo __LBL_LOGIN_HEADER__; ?></legend>
			<form  method="post" class="">
          	<?php if (isset($_POST['crudUser'])) { ?>
            <div class="alert alert-error">
                <?php echo E_VAL_LOGIN; ?>
            </div>   
            <?php } ?>
			
			<div class="control-group">
              <label for="inputEmail" class="control-label"><?php echo __LBL_LOGIN_NAME__; ?></label>
              <div class="controls">
                <input type="text" placeholder="<?php echo __LBL_LOGIN_NAME__; ?>" name="crudUser[name]" class="span4"  value="<?php
		            if (isset($_POST['crudUser']['name'])) {
		                echo htmlspecialchars($_POST['crudUser']['name']);
		            }
		            ?>" />
              </div>
            </div>
            <div class="control-group">
              <label for="inputEmail" class="control-label"><?php echo __LBL_LOGIN_PASSWORD__; ?></label>
              <div class="controls">
                <input type="password" placeholder="<?php echo __LBL_LOGIN_PASSWORD__; ?>"  name="crudUser[password]" class="span4"  value="<?php
                           if (isset($_POST['crudUser']['name'])) {
                               echo htmlspecialchars($_POST['crudUser']['password']);
                           }
            ?>" />
              </div>
            </div>
			<br />
			<button class="btn btn-info btn-block " name="submit" type="submit"><?php echo __BTN_SIGN_IN__;?></button>
			</form> 
		</div>
	</div>
</div>