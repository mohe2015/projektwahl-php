<?php
class ValidationError extends Exception { }

abstract class Record {

  abstract function getValidationErrors();

  public function validate() {
    $validation_errors = $this->getValidationErrors();
    if (!empty($validation_errors)) {
      throw new ValidationError(implode("<br>", $validation_errors));
    }
  }
}
?>
