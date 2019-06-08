<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'third_party/Mike42/autoload.php';
require_once APPPATH . 'libraries/Item.php';

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
/**
 * Class ReceiptPrint
 */
class ReceiptPrint {
    private $CI;
    private $connector;
    private $printer;
    private $printer_width = 32;
    private $items = [];

    public function __construct()
    {
        $this->CI =& get_instance(); // This allows you to call models or other CI objects with $this->CI->... 
    }

    public function connect($ip_address, $port)
    {
        $this->connector = new NetworkPrintConnector($ip_address, $port);
        $this->printer = new Printer($this->connector);
    }

    public function connectFile($path)
    {
        $this->connector = new FilePrintConnector($path);
        $this->printer = new Printer($this->connector);
    }

    public function set_printer_width($width)
    {
        $this->printer_width = $width;
    }

    public function close_after_exception()
    {
        if (isset($this->printer) && is_a($this->printer, 'Mike42\Escpos\Printer')) {
            $this->printer->close();
        }
        $this->connector = null;
        $this->printer = null;
        $this->emc_printer = null;
    }

    public function title($text)
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer->text(strtoupper($text));
        $this->printer->feed(2);
        $this->printer->selectPrintMode(); // Reset
        $this->printer->setJustification(); // Reset
    }

    public function footer($text)
    {
        $this->printer->feed(2);
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->text($text);
        $this->printer->feed(2);
        $this->printer->text(ucwords(strftime("%A %d %B %Y, %I:%M %p")) . "\n");
        $this->printer->feed(8);
    }

    public function details()
    {
        $this->printer->setEmphasis(true);
        $this->printer->text(str_pad("    " . lang('description'), 34, " ", STR_PAD_RIGHT));
        $this->printer->text(str_pad(lang('value') . "    \n", 14, " ", STR_PAD_LEFT));
        $this->add_line("----------------------------------------");
        $this->printer->setEmphasis(false);
        
        if ( count($this->items) > 0) {
            foreach ($this->items as $item) {
                $this->printer->text($item);
            } 
        }

        $this->printer->setEmphasis(true);
        $this->add_line("----------------------------------------");
        $this->printer->setEmphasis(false);
    }

    public function tipo($text)
    {
        $this->printer->feed(2);
        $this->printer->setEmphasis(true);
        $this->add_line("----------------------------------------");
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->add_line(strtoupper($text));
        $this->printer->setJustification(); // Reset
        $this->add_line("----------------------------------------");
        $this->printer->setEmphasis(false);
        
    }

    public function subtotal($amount)
    {
        $this->printer->setJustification(Printer::JUSTIFY_RIGHT);
        $this->printer->text(lang('subtotal') . str_pad(number_format($amount, 2), 20, " ", STR_PAD_LEFT) . "    ");
        $this->printer->feed();
    }

    public function discount($amount)
    {
        $this->printer->setJustification(Printer::JUSTIFY_RIGHT);
        $this->printer->text(lang('discount') . str_pad(number_format($amount, 2), 20, " ", STR_PAD_LEFT) . "    ");
        $this->printer->setJustification();
        $this->printer->feed();
    }

    public function total($amount)
    {
        $this->printer->setJustification(Printer::JUSTIFY_RIGHT);
        $this->printer->setEmphasis(true);
        $this->printer->text(lang('total') . str_pad(number_format($amount, 2), 20, " ", STR_PAD_LEFT) . "    ");
        $this->printer->setEmphasis(false);
        $this->printer->setJustification();
        $this->printer->feed();
        
    }

    // Calls printer->text and adds new line
    public function add_line($text = "", $should_wordwrap = true)
    {
        $text = $should_wordwrap ? wordwrap($text, $this->printer_width - 8) : $text;
        $this->printer->text("    ". $text . "\n");
    }

    public function add_line_two_column($textLeft, $textRight, $left = 30, $right = 14)
    {
        $this->printer->text(str_pad("    " . wordwrap($textLeft, $left), $left) . str_pad($textRight, $right, ' ', STR_PAD_LEFT));
        $this->printer->feed();
    }

    public function add_item($data)
    {
        $this->items[] = new Item($data['name'], $data['quantity'], $data['price']);
    }

    public function set_center()
    {
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
    }

    public function set_left()
    {
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
    }

    public function set_right()
    {
        $this->printer->setJustification(Printer::JUSTIFY_RIGHT);
    }

    public function close()
    {
        $this->printer->close();
    }

    public function print_test_receipt($text = "")
    {
        $this->check_connection();
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->add_line("TESTING");
        $this->add_line("Receipt Print");
        $this->printer->selectPrintMode();
        $this->add_line(); // blank line
        $this->add_line($text);
        $this->add_line(); // blank line
        $this->add_line(date('Y-m-d H:i:s'));
        $this->printer->feed(8);
        $this->printer->cut(Printer::CUT_PARTIAL);
        $this->printer->close();
    }

    public function print_receipt()
    {
        $this->check_connection();
        /* Information for the receipt */
        $items = array(
            new Item("Example item #1", "4.00"),
            new Item("Another thing", "3.50"),
            new Item("Something else", "1.00"),
            new Item("A final item", "4.45"),
        );
        $subtotal = new Item('Subtotal', '12.95');
        $tax = new Item('A local tax', '1.30');
        $total = new Item('Total', '14.25', true);
        /* Date is kept the same for testing */
        $date = date('l jS \of F Y h:i:s A');
        //$date = "Monday 6th of April 2015 02:56:25 PM";
        /* Start the printer */
        //$logo = EscposImage::load("resources/escpos-php.png", false);
        //$printer = new Printer($connector);
        /* Print top logo */
        //$printer -> setJustification(Printer::JUSTIFY_CENTER);
        //$printer -> graphics($logo);
        /* Name of shop */
        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer->text("ExampleMart Ltd.\n");
        $this->printer->selectPrintMode();
        $this->printer->text("Shop No. 42.\n");
        $this->printer->feed();
        /* Title of receipt */
        $this->printer->setEmphasis(true);
        $this->printer->text("SALES INVOICE\n");
        $this->printer->setEmphasis(false);
        /* Items */
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $this->printer->setEmphasis(true);
        $this->printer->text(new item('', '$'));
        $this->printer->setEmphasis(false);
        foreach ($items as $item) {
            $this->printer->text($item);
        }
        $this->printer->setEmphasis(true);
        $this->printer->text($subtotal);
        $this->printer->setEmphasis(false);
        $this->printer->feed();
        /* Tax and total */
        $this->printer->text($tax);
        $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $this->printer->text($total);
        $this->printer->selectPrintMode();
        /* Footer */
        $this->printer->feed(2);
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->text("Thank you for shopping at ExampleMart\n");
        $this->printer->text("For trading hours, please visit example.com\n");
        $this->printer->feed(2);
        $this->printer->text($date . "\n");
        $this->printer->feed(8);
        /* Cut the receipt and open the cash drawer */
        $this->printer->cut();
        $this->printer->pulse();
        $this->printer->close();
    }

    public function check_connection()
    {
        if (!$this->connector OR !$this->printer OR !is_a($this->printer, 'Mike42\Escpos\Printer')) {
            throw new Exception("Tried to create receipt without being connected to a printer.");
        }
    }
}