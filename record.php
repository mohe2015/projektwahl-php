<?php
abstract class Record {

  abstract function getValidationErrors();

  public function validate() {
    $validation_errors = getValidationErrors();
    if (!empty($validation_errors)) {
      throw new ValidationError(implode("<br>", $validation_errors));
    }
  }
}
?>
