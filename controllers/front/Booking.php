<?php
/**
 * @since 1.5.0
 */
if (!defined('_PS_VERSION_'))
    exit;
include('/../../../config/config.inc.php');

include(dirname(__FILE__) . '/../../classes/Booking.php');
//use booking\models\ProductBooking;

class bookingBookingModuleFrontController extends ModuleFrontController
{

    public $ajax_search;
    public $instant_search;
    public $id_product;
    
    public function __construct()
    {
        parent::__construct();
        $this->className = 'Booking';

        $this->context = Context::getContext();
    }

    public function init()
    {
        parent::init();
        if (Tools::getValue('token') != Tools::getToken(false)) {
            //Tools::redirect('index.php');
            header('404 Page not found');
            die();
        }
    }

    public function postProcess()
    {
        if ($this->ajax) {
            if (!$this->context->cart->id && isset($_COOKIE[$this->context->cookie->getName()]))
            {
                $this->context->cart->add();
                $this->context->cookie->id_cart = (int)$this->context->cart->id;
            }
                                
            $id_cart = $this->context->cart->id;
            $id_product = Tools::getValue('id_product');
            if (Tools::getValue('checkin_date')) {
                $checkin_date = preg_replace('/(\d{2})-(\d{2})-(\d{4})/',"$3-$2-$1",Tools::getValue('checkin_date'));
            }
            if (Tools::getValue('checkout_date')) {
                $checkout_date = preg_replace('/(\d{2})-(\d{2})-(\d{4})/',"$3-$2-$1",Tools::getValue('checkout_date'));
            }
            
            /* Check if there is already a record */
            $sql = sprintf('SELECT id_booking FROM '._DB_PREFIX_.'booking_product WHERE id_cart = %d AND id_product = %d',$id_cart,$id_product);
            $result = Db::getInstance()->executeS($sql);
            $id_booking = ($result[0]['id_booking'] ? $result[0]['id_booking'] : null); 
                
            if ($id_productbooking) {
                $booking = new Booking($id_booking); 
            } else {
                $booking = new ProductBooking();
                $booking->id_product = $id_product;
                $booking->id_cart = $id_cart;
                $booking->token = Tools::getToken(false);
            } 
            if (Tools::getValue('checkin_date')) {
                $booking->checkin_date = $checkin_date;
            }
            if (Tools::getValue('checkout_date')) {
                $booking->checkout_date = $checkout_date;
            }
            $booking->save();
            
            
            //$json = Tools::jsonEncode(array('result'=>$checkin_date,'dbres'=>$result,'id_productbooking'=>$id_productbooking,'id_product'=>$id_product,'token'=>Tools::getValue('token'),'id_productbooking'=>$id_productbooking,'id_cart'=>$id_cart,'sql'=>$sql));
            $json = Tools::jsonEncode(array('result'=>$booking->id));
            
            
            die($json);
        }
     
    }
}
