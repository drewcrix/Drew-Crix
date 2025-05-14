<?php
class Department extends dataBasegather{
  static protected $table_name = 'Dept';
  static protected $db_columns = ['ID','Name','Program'];

  public $ID;
  public $Name;
  public $Program;

  public function __construct($args = []){
    $this->Name = $args['Name'] ?? '';
    $this->Program = $args['Program'] ?? '';
  }



//one post and then can assign these values.
}
?>
