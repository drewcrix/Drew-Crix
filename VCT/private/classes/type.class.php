<?php


class Type extends dataBasegather{
  static protected $table_name = 'type';
  static protected $db_columns = ['ID','Description'];

  public $ID;
  public $Description;

  public function __construct($args = []){
    $this->Description = $args['Description'] ?? '';
  }


}
?>
