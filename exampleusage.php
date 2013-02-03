<?php
	require("php/PHPNodeNotifier.php");
	
	/*
		PHPNodeNotifier::emit(string $tag, mixed $data, optional array constraints);
	*/
	
	(new PHPNodeNotifier())->emit("DebugUser",array("name"=>"yes-1"))
	->emit("DebugUser",array("name"=>"yes"),array("url"=>"home/index"))
	->emit("DebugUser",array("name"=>"yes2"),array("user"=>1))
	->emit("DebugUser",array("name"=>"yes3"),array("user"=>array(1,2)))
	->emit("DebugUser",array("name"=>"Ryan is awesome AND #".rand(0,10000)))
	->emit("DebugUser",array("name"=>"Ryan is awesome AND #".rand(0,10000)))
	->emit("DebugUser",array("name"=>"Guests don't get this"),array("guest"=>false))
	->emit("DebugUser",array("name"=>"Guests get this"),array("guest"=>true))
	->emit("DebugUser",array("name"=>"yes4"),array("user"=>1));
		
?>

<script>
	var socket = io.connect(':9090'); //Localhost on port 9090
	<?php
		/*
		* This is my personal usage for $path. It can be anything you want
		* but since I am using Yii Framework, I send it as <controller>/<action>
		* Also, enforcing the action to be index if it is null.
		*/
		
		$path = substr(Yii::app()->request->requestUri,strlen(Yii::app()->request->baseUrl));
		if ($path == "/"){
			$path = $this->uniqueid."/index";
		}
		$path = trim($path,"/");
		if (substr_count($path, "/") == 0){
			$path .= "/index";
		}
		
	?>
	
		<?php
		/*
		* The data sent to our node module can be whatever you wish, customized, removed, added, whatever.
		* I am just showing off how I use it in the project that this was designed for.
		* Authentication was done to ensure that there wasa a secure way to braodcast messages
		* to specific users on the site in real time.
		*/
		?>
	  socket.emit('auth', { 
			path: "<?php print $path; ?>",
			guest: <?php print $this->isGuest() ? "1" : "0"; ?>,
			username: "<?php if ($this->user() != null){ print $this->user()->username; }?>",
			authtoken: "<?php if ($this->user() != null){ print $this->user()->password; } /* TODO: Do a real auth token here.*/ ?>"
	  });
	  
	  socket.on('DebugUser', function (data) {
		console.log(data);
	  });
	  
</script>