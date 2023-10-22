<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

require('Pusher.php');

class Realtime {
    private $Channel = FALSE;
    private $Pusher;
    private $write_port = 4567;
    public $host = "sapherna.com";
    public $app_id = "d2b015da2f4402a48bd6";
    public $public_app_token = "d2b015da2f4402a48bd6";
    private $private_app_token = "287bf586004d4510ea79";
    public $read_port = 8080;

	public function __construct()
	{
		$this->Pusher = new Pusher(
			$this->public_app_token,
			$this->private_app_token,
			$this->app_id,
			[
				'port' => $this->write_port, 
				'host' => $this->host, 
				'debug' => false, 
				'encrypted' => false
			]
		);
	}

	public function setChannel($name='')
	{
		$this->Channel = $name;
		return $this;
	}

	public function publish($event_key='', $value='')
	{
		if (!$this->Channel) show_error('Channel is not set');

		$this->Pusher->trigger($this->Channel, $event_key, $value);
	}
}
