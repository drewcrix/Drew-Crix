<?php
class Program extends dataBasegather{
  static protected $table_name = 'Program';
  static protected $db_columns = ['ID','Name'];

  public $ID;
  public $Name;

  public function __construct($args = []){
    $this->Name = $args['Name'] ?? '';
  }



//one post and then can assign these values.
}
?>
