<?php

class Pagination extends dataBasegather {

  public $current_page;
  public $per_page;
  public $total_count;

  public function __construct($page=1, $per_page=10, $total_count=0) {
    $this->current_page = (int) $page;
    $this->per_page = (int) $per_page;
    $this->total_count = (int) $total_count;
  }

  public function offset() { //calculates how many records to skip over once you go to another page
    return $this->per_page * ($this->current_page - 1);
  }

  public function total_pages() {
    return ceil($this->total_count / $this->per_page);
  }

  private function previous_page() {
    $prev = $this->current_page - 1;
    return ($prev > 0) ? $prev : false;
  }

  private function next_page() {
    $next = $this->current_page + 1;
    return ($next <= $this->total_pages()) ? $next : false;
  }

  private function previous_link($url="") {
    $link = "";
    if($this->previous_page() != false) {
      $link .= "<a href=\"{$url}&page={$this->previous_page()}\">";
      $link .= "&laquo; Previous</a>";
    }
    return $link;
  }

  private function next_link($url="") {
    $link = "";
    if($this->next_page() != false) {
      $link .= "<a href=\"{$url}&page={$this->next_page()}\">";
      $link .= "Next &raquo;</a>";
    }
    return $link;
  }

  private function number_links($url="") { //for adding the numbers next to the buttons. You can click on the numebrs to get to different pages
    $output = "";
    for($i=1; $i <= $this->total_pages(); $i++) {
      if($i == $this->current_page) {
        $output .= "<span class=\"selected\">{$i}</span>";
      } else {
        $output .= "<a href=\"{$url}&page={$i}\">{$i}</a>";
      }
    }
    return $output;
  }

  public function page_links($url) { //so I can do the other three methods in once method
    $output = "";
    if($this->total_pages() > 1) {
      $output .= "<div class=\"pagination\">";
      $output .= $this->previous_link($url);
      $output .= $this->number_links($url);
      $output .= $this->next_link($url);
      $output .= "</div>";
    }
    return $output;
  }

}

?>
