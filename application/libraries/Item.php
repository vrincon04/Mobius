<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* A wrapper to do organise item names & prices into columns */
class Item
{
    private $name;
    private $quantity;
    private $price;
    private $dollarSign;
    private $total;
    public function __construct($name = '', $quantity = 0, $price = 0, $dollarSign = false)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->total = $quantity * $price;
        $this->dollarSign = $dollarSign;
    }
    
    public function __toString()
    {
        $rightCols = 14;
        $leftCols = 34;
        if ($this->dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $top = "    " . number_format($this->quantity,  2) . " x " . number_format($this->price,  2);
        $left = str_pad("    " . $this->name, $leftCols) ;
        
        $sign = ($this->dollarSign ? '$ ' : '');
        $right = str_pad($sign . number_format($this->total, 2) . "    ", $rightCols, ' ', STR_PAD_LEFT);
        return "$top\n$left$right\n";
    }
}