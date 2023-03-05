<?php
use MiMFa\Library\Local;
MODULE("PrePage");
$module = new MiMFa\Module\PrePage();
$module->Title = "Update";
$module->Draw();


if(count($_POST)>0) try {
    $src = Local::CreateNewAddress(\_::$TMP_DIR);
    $tmpfile = $src.".zip";
    $src .= "/";

    Local::CopyFile(\_::$CONFIG->GetLatestVersionPath(),$tmpfile);

    $zip = new ZipArchive();
    if ($zip->open($tmpfile) !== true) echo "<div class='alert alert-danger'>".__('Unzipped Process failed')."</div>";
    elseif($zip->extractTo($src)) {
        $zip->close();
        echo "<div class='alert alert-success'>".__('Unzipped Process Successful!')."</div>";

        Local::DeleteFile($tmpfile);
        Local::DeleteFile($src."global/Information.php");
        Local::DeleteFile($src."global/Configuration.php");
        Local::DeleteFile($src."global/Template.php");

        if(!$_POST["administration"]) Local::DeleteDirectory($src."aseqbase/administration/");

        if($_POST["aseqbase"]){
            if($_POST["base"])
                if(Local::CopyDirectory($src."base/", \_::$BASE_DIR, true))
                    echo "<div class='alert alert-success'>".__('The base directory updated successfully!')."</div>";
                else echo "<div class='alert alert-warning'>".__('Could not update the base directory!')."</div>";
            if($_POST["client"])Local::CopyDirectory($src."aseqbase/", \_::$DIR, true);
            if($_POST["sequences"])
                foreach (\_::$SEQUENCES as $seq=>$root)
                    if(Local::CopyDirectory($src."aseqbase/", $seq, true))
                        echo "<div class='alert alert-success'>".__('$seq updated successfully!')."</div>";
                    else echo "<div class='alert alert-warning'>".__('Could not update $seq!')."</div>";
            if($_POST["aseq"])
                if(Local::CopyDirectory($src."aseq/", \_::$BASE_DIR."../aseq/", true))
                    echo "<div class='alert alert-success'>".__('The aseq directory updated successfully!')."</div>";
                else echo "<div class='alert alert-warning'>".__('Could not update the aseq directory!')."</div>";
        }
        elseif($_POST["all"]){
            if($_POST["base"])
                if(Local::CopyDirectory($src."base/", \_::$BASE_DIR, true))
                    echo "<div class='alert alert-success'>".__('The base directory updated successfully!')."</div>";
                else echo "<div class='alert alert-warning'>".__('Could not update the base directory!')."</div>";
            if($_POST["sequences"])
                foreach (scandir(\_::$BASE_DIR."../") as $seq)
                    if(isASEQ($seq))
                        if(Local::CopyDirectory($src."aseqbase/", $seq, true))
                            echo "<div class='alert alert-success'>".__('$seq updated successfully!')."</div>";
                        else echo "<div class='alert alert-warning'>".__('Could not update $seq!')."</div>";
            if($_POST["aseq"])
                if(Local::CopyDirectory($src."aseq/", \_::$BASE_DIR."../aseq/", true))
                    echo "<div class='alert alert-success'>".__('The aseq directory updated successfully!')."</div>";
                else echo "<div class='alert alert-warning'>".__('Could not update the aseq directory!')."</div>";
            echo "<div class='alert alert-info'>".__('Server updated successfully!')."</div>";
        } else echo "<div class='alert alert-danger'>".__('Update stoped!')."</div>";
    }
    Local::DeleteDirectory($src);
}catch(Exception $ex){
    echo "<div class='alert alert-danger'>".__($ex->getMessage())."</div>";
}
?>

<center>
    <?php
    echo __("Your Version: ").\_::$VERSION."<br />";
    echo __("Latest Version: ").\_::$CONFIG->GetLatestVersion()."<br />";
    ?>
    <form method="post">
        <table>
            <tr>
                <td>
                    <?php echo __("Infrastructure:",true,false)?>
                </td>
                <td>
                    <label for="aseq">
                        <input type="checkbox" checked="checked" id="aseq" name="aseq" />
                        <?php echo __("Update aseq",true,false)?>
                    </label>
                    <label for="base">
                        <input type="checkbox" checked="checked" id="base" name="base" />
                        <?php echo __("Update base",true,false)?>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo __("Website:",true,false)?>
                </td>
                <td>
                    <label for="client">
                        <input type="checkbox" checked="checked" id="client" name="client" />
                        <?php echo __("Update this domain",true,false)?>
                    </label>
                    <label for="sequences">
                        <input type="checkbox" checked="checked" id="sequences" name="sequences" />
                        <?php echo __("Update this domain sequences",true,false)?>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo __("Administration:",true,false)?>
                </td>
                <td>
                    <label for="administration">
                        <input type="checkbox" id="administration" name="administration"/>
                        <?php echo __("Update administration",true,false)?>
                    </label>
                </td>
            </tr>
        </table>
        <?php if(\_::$CONFIG->IsLatestVersion()):
                  echo "<div class='alert alert-success'>".__("You have the latest version!")."</div>";
              else: ?>
        <input type="submit" name="aseqbase" value="<?php echo __("Update Website",true,false)?>" class="btn btn-outline-primary" />
        <?php endif; ?>
        <input type="submit" name="all" value="<?php echo __("Update all aseqbase frameworks",true,false)?>" class="btn btn-secondary" />
    </form>
</center>
