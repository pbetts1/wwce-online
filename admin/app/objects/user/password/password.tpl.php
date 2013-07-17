<div class="container">
         <?php echo $this->user_menu; ?>
         <br />
        <form class="bs-docs-example form-horizontal" method="post" action="<?php echo strUrl('user/changePassword.php'); ?>">
            <?php if (count($this->errors) > 0) { ?>
                <div class="alert alert-error">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <?php foreach ($this->errors as $v) { ?>
                        <strong>Error!</strong> <?php echo $v; ?> <br />
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if ($this->update_flag == 1 && count($this->errors) <= 0) { ?>
                <div class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo __LBL_CHANGE_PASSWORD_SUCCESS__;?>
                </div>
            <?php } ?>
            <div class="control-group  <?php if (array_key_exists('current_password', $this->errors)) { ?> error <?php } ?>">
                <label for="inputPassword" class="control-label" style=" text-align: right !important;"> <?php echo __LBL_CURRENT_PASSWORD__;?></label>
                <div class="controls">
                    <input type="password" placeholder="Current Password" id="current_password"  name="current_password"  value="<?php
            if (isset($_POST['current_password'])) {
                echo htmlspecialchars($_POST['current_password']);
            }
            ?>" >
                </div>
            </div>
            <div class="control-group  <?php if (array_key_exists('new_password', $this->errors)) { ?> error <?php } ?>">
                <label for="inputPassword" class="control-label" style=" text-align: right !important;"><?php echo __LBL_NEW_PASSWORD__;?></label>
                <div class="controls">
                    <input type="password" placeholder="New Password" id="new_password"   name="new_password"  value="<?php
                           if (isset($_POST['new_password'])) {
                               echo htmlspecialchars($_POST['new_password']);
                           }
            ?>" >
                </div>
            </div>
            <div class="control-group <?php if (array_key_exists('confirm_new_password', $this->errors)) { ?> error <?php } ?>">
                <label for="inputPassword" class="control-label" style=" text-align: right !important;"><?php echo __LBL_RE_NEW_PASSWORD__;?></label>
                <div class="controls">
                    <input type="password" placeholder="Re-type New Password" id="confirm_new_password"   name="confirm_new_password"  value="<?php
                           if (isset($_POST['confirm_new_password'])) {
                               echo htmlspecialchars($_POST['confirm_new_password']);
                           }
            ?>" >
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-primary" type="submit"><?php echo __BTN_CHANGE_PASSWORD__;?></button>
                </div>
            </div>
        </form>

        <hr />
        <footer>
            <p><?php echo __LBL_COPYRIGHT__; ?></p>
        </footer>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('title').html($('h3').html());
    });
</script>