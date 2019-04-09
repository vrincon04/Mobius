<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Ticket_model class
 *
 * @author Victor Rincon
 */
class Ticket_model extends CI_Model {

    public function PrintInvoice($id)
    {
        //
        $this->load->model('invoice_model');
        //
        $this->load->library('ReceiptPrint');

        $invoice = $this->invoice_model->get($id)->with(['tenant', 'order' => ['user'], 'user', 'details']);

        foreach ($invoice->details as $detail) {
            $detail->with(['product']);

            $this->receiptprint->add_item([
                'name' => $detail->product->name, 
                'quantity' => $detail->quantity, 
                'price' => $detail->price
            ]);
        }

        try {
            $this->receiptprint->connectFile('\\\\localhost\Ticket');
            $this->receiptprint->set_printer_width(48);
            $this->receiptprint->check_connection();
            $this->receiptprint->title($invoice->tenant->name);
            $this->receiptprint->add_line($invoice->tenant->address);
            $this->receiptprint->add_line("TEL: {$invoice->tenant->phone}");
            if ( trim($invoice->tenant->business_name) != '') {
                $this->receiptprint->add_line($invoice->tenant->business_name);
            }
            
            if ( trim($invoice->tenant->rnc) != '') {
                $this->receiptprint->add_line("RNC  {$invoice->tenant->rnc}");
            } 

            $this->receiptprint->add_line('FEC. ' . date('d/m/Y h:i:s A'));
            $this->receiptprint->add_line('NIF: ' . str_pad($invoice->id, 16, '0', STR_PAD_LEFT));
            //$this->receiptprint->add_line('NCF: ' . str_pad(0, 11, '0', STR_PAD_LEFT));
            $this->receiptprint->tipo(lang('invoice'));
            $this->receiptprint->details();
            $this->receiptprint->subtotal(230);
            $this->receiptprint->discount(30);
            $this->receiptprint->total(200);
            $this->receiptprint->add_line("");
            $this->receiptprint->add_line(str_pad(lang('waiter') . ":", 20) . str_pad($invoice->order->user->username, 20, ' ', STR_PAD_LEFT));
            $this->receiptprint->add_line(str_pad(lang('cashier') . ":", 20) . str_pad($invoice->user->username, 20, ' ', STR_PAD_LEFT));
            $this->receiptprint->footer("*** GRACIAS POR SU VISITA ***");
        } catch (Exception $e) {
            echo ("Error: Could not print. Message ".$e->getMessage());
            $this->receiptprint->close_after_exception();
        } finally {
            $this->receiptprint->close_after_exception();
        }
    }

    public function PrintOrder($id)
    {
        //
        $this->load->model('order_model');
        //
        $this->load->library('ReceiptPrint');

        $order = $this->order_model->get($id)->with(['tenant', 'user', 'details']);
        try {
            $this->receiptprint->connectFile('\\\\localhost\Ticket');
            $this->receiptprint->set_printer_width(48);
            $this->receiptprint->check_connection();
            $this->receiptprint->title($order->tenant->name);
            $this->receiptprint->tipo(lang('order') . " #" . str_pad($order->id, 6, '0', STR_PAD_LEFT));
            foreach ($order->details as $detail) {
                $detail->with(['product']);
                $this->receiptprint->add_line_two_column($detail->product->name, $detail->quantity);
            }
            $this->receiptprint->add_line("");
            $this->receiptprint->add_line(str_pad(lang('waiter') . ":", 20) . str_pad($order->user->username, 20, ' ', STR_PAD_LEFT));
            $this->receiptprint->footer("*** FAVOR DE ESPERAR SU ORDEN ***");
        } catch (Exception $e) {
            echo ("Error: Could not print. Message ".$e->getMessage());
            $this->receiptprint->close_after_exception();
        } finally {
            $this->receiptprint->close_after_exception();
        }
    }
}