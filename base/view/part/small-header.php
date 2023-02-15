<style>
	.small-header{
		padding: 10px 10px;
		margin-bottom: 10px;
	}
	.small-header .image{
		background-position: center;
		background-repeat: no-repeat;
		background-size: auto 100%;
		background-color: transparent;
		width: 40px;
		display: table-cell;
		padding: 0px;
		margin: 0px 8px;
	}
	.small-header .title{
		font-size: var(--Size-1);
		padding: 0px 20px 0px 5px;
		display: table-cell;
		vertical-align: middle;
	}
</style>
<div class="small-header">
	<a href="<?php echo \_::$INFO->HomePath; ?>">
		<div class="brand-bar" >
			<div class="image" style="background-image: var(--Url-LogoPath);"></div>
			<div class="title"><?php echo \_::$INFO->FullName; ?></div>
		</div>
	</a>
</div>