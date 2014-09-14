<?php

if (!defined('_PS_VERSION_'))
    exit;

class Booking extends Module
{

    public function __construct()
    {
        $this->name = 'booking';
        $this->tab = 'other';
        $this->version = '0.6';
        $this->author = 'Patrickswebsite.nl';
        $this->is_needed = 0;

        parent::__construct();

        $this->displayName = $this->l('Booking');
        $this->description = $this->l('Booking checkin/checkout and special wishes.');
        $this->confirmUninstall = $this->l('Are you sure about removing these details?');
    }

    /**
     * 
     * @return boolean
     */
    public function install()
    {
        if (!parent::install() ||
                !$this->registerHook('header') ||
                !$this->registerHook('displayBookingDatePicker') ||
                !$this->createTables()
        ) {
            return false;
        }
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function uninstall()
    {
        if (!parent::uninstall() ||
                !$this->dropTables()) {

            return false;
        }

        return true;
    }

    /* Frontend stuff */

    /**
     * 
     * @param type $params
     */
    public function hookHeader($params)
    {
        $this->context->controller->addJqueryPlugin('datepicker');
    }

    /**
     * For use in template {hook h='displayDatePicker' product=$product}
     * 
     * @param type $params
     * @return type
     */
    public function hookDisplayBookingDatePicker($params)
    {
        $product = $params['product'];
        $data = array('id_product' => $product->id, 'token' => Tools::getToken());

        $hasCheckOutDate = false;
        if (strtolower($product->category) == 'luchthaven-vervoer-boeken') {
            $hasCheckOutDate = true;
        }
        $json = Tools::jsonEncode($data);

        $this->context->smarty->assign('hasCheckOutDate', $hasCheckOutDate);
        $this->context->smarty->assign('jsonBooking', $json);
        return $this->display(__FILE__, 'datepicker.tpl');
    }

    /**
     * 
     * @return boolean
     */
    private function createTables()
    {
        /* Set database */
        if (!Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'booking_product` (
			`id_booking` INT AUTO_INCREMENT,
                        `id_cart` int(10) unsigned NOT NULL,
                        `id_product` int(10) unsigned NOT NULL,
                        `checkin_date` date NOT NULL,
                        `checkout_date` date NOT NULL,
                        `token` varchar(255) not null,
			`date_add` datetime NOT NULL,
                        `date_upd` datetime NOT NULL,
			PRIMARY KEY (`id_booking`),
			INDEX(id_cart, id_product)
		) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
            return false;
        }
        return true;
    }

    private function dropTables()
    {
        if (!Db::getInstance()->Execute('DROP TABLE `' . _DB_PREFIX_ . 'booking_product`')) {
            return false;
        }
        return true;
    }

}
