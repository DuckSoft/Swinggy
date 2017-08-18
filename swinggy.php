<?php
const status_init = -1;
const status_fallback = -2;
class Swinggy{
    public $stat = status_init;
    public $stor;

    private $status_determinators;
    private $performers;

    public function __construct($status_determinators) {
        $this->status_determinators = $status_determinators;
    }
    public function ready() {
        foreach ($this->status_determinators as $key => &$value) {
            if ($value()) {
                $this->stat = $key;
                return;
            }
        }
        $this->stat = status_fallback;
    }
    public function set($performers) {
        $this->performers = $performers;
    }
    public function go($performer) {
        $this->performers[$performer]($this);
    }
}
