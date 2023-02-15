<style>
	.appointment-part .profile{
		display: inline-flex;
		padding: 10px 20px 10px 0px;
		margin:10px;
		margin-left: 60px;
		border: 1px solid #fafcfd;
		background-color: #ecf0f1;
		box-shadow: 4px 7px 15px #00000015;
		border-radius: 3px 100px 100px 3px;
		-webkit-transition: all 0.5s;
		-moz-transition: all 0.5s;
			-ms-transition: all 0.5s;
			-o-transition: all 0.5s;
				transition: all 0.5s;
	}
	.appointment-part .profile:hover{
		border: 1px solid #ecf0f1;
		background-color: #fafcfd;
		box-shadow: 4px 7px 15px #00000025;
		-webkit-transition: all 0.5s;
		-moz-transition: all 0.5s;
			-ms-transition: all 0.5s;
			-o-transition: all 0.5s;
				transition: all 0.5s;
	}
	.appointment-part .profile-picture{
		background-color: #ecf0f1;
		display: inline-block;
		width: 100px;
		height: 100px;
		margin-left: -50px;
		border-left: 5px solid #fafcfd;
		border-radius: 50%;
	}
	.appointment-part .profile-content {
		width: 100%;
		margin-left: 20px;
	}
	.appointment-part .profile-buttons {
		width: auto;
		text-align: right;
	}
	.appointment-part .profile-appointment{
		background-color: #e67d21;
		color: #fafcfd;
	}
	.appointment-part .profile-buttons button{
		box-shadow: 4px 7px 15px #00000015;
		border: none;
		padding: 5px 10px;
		border-radius: 3px;
	}
	.appointment-part .profile-buttons button:hover{
		box-shadow: 4px 7px 15px #00000015;
	}
</style>
<div class="container appointment-part">
	<?php
		MODULE("PrePage");
		$module = new MiMFa\Module\PrePage();
		$module->Title = "Set an Appointment";
		$module->Draw();
		
		$i = true;
		foreach($list as $person) { ?>
			<?php if($i) { ?> <div class="row"><?php } ?>
				<div class="col-md profile">
					<img src="/file/image/personal/<?php echo $person; ?>.jpg" class="profile-picture">
					<div class="profile-content">
						<b class="profile-title"><?php echo $person; ?></b>
						<div class="row">
							<div class="profile-details col-sm">
									Languages: 
									<span>German</span>
									<span>English</span>
							</div>
							<div class="profile-buttons col-sm">
								<button class="profile-more">More...</button>
								<button class="profile-appointment">Appointment</button>
							</div>
						</div>
					</div>
				</div>
			<?php if(!$i) { ?> </div><?php } ?>
	<?php 
		$i = !$i;
		} ?>
	<?php if(!$i) { ?> </div><?php } ?>
</div>
<?php PART("small-navigation") ?>