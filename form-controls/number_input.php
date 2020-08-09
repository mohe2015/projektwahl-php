<?php

class NumberInput {
    private string $label;
    private bool $required;

    public function __construct(string $label, bool $required) {
        $this->label = $label;
        $this->required = $required;
    }

    public function render() {
        
    }
}

?>