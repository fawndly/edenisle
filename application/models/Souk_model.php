<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Market extends CRYS_Data_Model {
    public $trade_title;
    public $trade_sender;

    // ignore
    public $trade_id;
    public $trade_cap;
    public $trade_notification;

    protected static $REQUIRED = array('item_id', 'user_id');
    protected static $DEFAULTS = array(
        'price' => 0,
        'purchased' => 0,
        'cancled' => 0
    );

    public function __construct($attributes=array()) {
        parent::__construct($attributes);
    }
}

class Market_Model extends CRYS_Composite_Model {
    protected static $TABLE = 'marketplace_items';
    protected static $PRIMARY_KEY = 'id';

    protected static $INNER_MODEL = 'Market';

    protected static $FK_FROM = 'user_id';
    protected static $FK_TO = 'purchased_by';

    public function __construct() {
        parent::__construct();
    }
}
