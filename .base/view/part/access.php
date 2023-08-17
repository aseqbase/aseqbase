<?php 
use MiMFa\Library\User;
if(getAccess(\_::$CONFIG->GuestAccess)){ ?>
<div class="row sign">
    <div class="col-sm">
        <?php if(getAccess(\_::$CONFIG->UserAccess)){ ?>
            <a class="btn" <?php echo " href='".User::$ViewHandlerPath."'>".__("Profile"); ?></a>
            <a class="btn" <?php echo " href='".User::$OutHandlerPath."'>".__("Sign Out"); ?></a>
        <?php } else { ?>
            <a class="btn" data-aos="zoom-left" data-aos-duration="600" <?php echo " href='".User::$InHandlerPath."'>".__("Sign In"); ?></a>
            <a class="btn" data-aos="zoom-right" data-aos-duration="600" <?php echo " href='".User::$UpHandlerPath."'>".__("Sign Up"); ?></a>
        <?php } ?>
    </div>
</div>
<?php } ?>