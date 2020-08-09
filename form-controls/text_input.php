<?php

class TextInput {
    private string $label;
    private string $name;
    private bool $required;
    private bool $autofocus;

    public function __construct(string $label, string $name, bool $required, bool $autofocus) {
        $this->label = $label;
        $this->name = $name;
        $this->required = $required;
        $this->autofocus = $autofocus;
    }

    public function render() {
        ?>

        <label class="form-label"><?php echo $this->label . ($this->required ? "*" : "") ?>:</label>
        <input class="form-control" <?php echo ($this->required ? "required" : "") ?> <?php echo ($this->autofocus ? "autofocus" : "") ?> type="text" name="<?php echo $this->name ?>" />

        <?php if ($required) { ?>
            <div class="invalid-feedback">
                <?php echo $this->label ?> wird benötigt.
            </div>
            <?php
        } 
        ?>


<?
    }
}

?>