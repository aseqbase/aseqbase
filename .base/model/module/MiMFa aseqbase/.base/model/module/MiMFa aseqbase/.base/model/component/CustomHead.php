<?php namespace MiMFa\Component;
class CustomHead extends Component{
	public function EchoStyle(){
		?>
		<style>
		</style>
		<?php
	}

	public function EchoScript(){
		?>
		<script>
		</script>
		<?php
	}

	public function Draw(){
		$this->EchoStyle();
		$this->EchoScript();
	}
}
?>