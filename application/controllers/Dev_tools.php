<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dev_tools extends CI_Controller {
    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->load->model('stock_model');
        $stock = $this->stock_model->find([
            'select' => 'id, product_id, warehouse_id',
            'limit' => 1,
            'where' => [
                'product_id' => 1,
                'warehouse_id' => 1
            ]
        ])->with([
            'product' => [
                'components'
            ]
        ]);

        dump($stock);
    }
    /**
     *
     */
    public function phpinfo()
    {
        echo phpinfo();
    }

    public function print()
    {
        try {
            $this->load->library('ReceiptPrint');
            $this->receiptprint->add_item(['name' => 'Empanada de Pollo', 'quantity' => 2, 'price' => 70]);
            $this->receiptprint->add_item(['name' => 'Con Queso', 'quantity' => 2, 'price' => 10]);
            $this->receiptprint->add_item(['name' => 'Kipe Frito', 'quantity' => 1, 'price' => 70]);
            $this->receiptprint->connectFile('\\\\localhost\Ticket');
            $this->receiptprint->set_printer_width(48);
            $this->receiptprint->title("Brassiere Lounge");
            $this->receiptprint->add_line('C/ CARLOS MANUEL PUMAROL #20');
            $this->receiptprint->add_line('TEL: (809) 901-4044');
            $this->receiptprint->add_line('Brassiere Lounge By La Gerencia');
            $this->receiptprint->add_line('RNC  1-3157126-3');
            $this->receiptprint->add_line('FEC. ' . date('d/m/Y h:i:s A'));
            $this->receiptprint->add_line('NIF: ' . str_pad(1, 16, '0', STR_PAD_LEFT));
            $this->receiptprint->add_line('NCF: ' . str_pad(1, 11, '0', STR_PAD_LEFT));
            $this->receiptprint->tipo("Factura");
            $this->receiptprint->details();
            $this->receiptprint->subtotal(230);
            $this->receiptprint->discount(30);
            $this->receiptprint->total(200);
            $this->receiptprint->footer("*** GRACIAS POR SU VISITA ***");
        } catch (Exception $e) {
            echo ("Error: Could not print. Message ".$e->getMessage());
            $this->receiptprint->close_after_exception();
        } finally {
            $this->receiptprint->close_after_exception();
        }
    }
}