<?php 
use MiMFa\Library\User;
if(ACCESS(0)){ ?>
<div class="row sign">
    <div class="col-sm">
        <?php if(ACCESS(1)){ ?>
            <a class="btn" <?php echo " href='".User::$OutHandlerPath."'>".__("Sign Out"); ?></a>
        <?php } else { ?>
            <a class="btn" data-aos="zoom-left" data-aos-duration="600" <?php echo " href='".User::$InHandlerPath."'>".__("Sign In"); ?></a>
            <a class="btn" data-aos="zoom-right" data-aos-duration="600" <?php echo " href='".User::$UpHandlerPath."'>".__("Sign Up"); ?></a>
        <?php } ?>
    </div>
</div>
<?php } ?>