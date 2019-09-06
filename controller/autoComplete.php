<?php

/**
 * Gallery's child AutoComplete Class
 * 
 * Provides data for autocompletion AJAX function
 * 
 * @version 2.0
 * @author  Yoan Morel
 */
class AutoComplete extends Gallery {

  /**
   * Section attribute
   */
  private $section;

  /**
   * Magic construct method. Set section attribute
   * 
   * @param string $imgSection image section
   */
  public function __construct($imgSection) {
    $this->section = $imgSection;
  }

  /**
   * Method running Gallery's method to provide data section or subsection
   */
  public function sections() {
    $data = [];

    if(empty($this->section)):
      $data = $this->getSections()->fetchAll(PDO::FETCH_NUM);
    else:
      $data = $this->getSubSectionsFrom($this->section)->fetchAll(PDO::FETCH_NUM);
    endif;

    return $data;
  }
}

 ?>
