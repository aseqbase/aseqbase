<?php if(ACCESS(0)){ ?>
<div class="row sign">
    <div class="col-sm" data-aos="zoom-out" data-aos-duration="600">
        <a class="btn" <?php echo " href='".getUrl('sign-in')."'>".__("Sign In"); ?></a>
        <a class="btn" <?php echo " href='".getUrl('sign-up')."'>".__("Sign Up"); ?></a>
    </div>
</div>
<?php } ?>