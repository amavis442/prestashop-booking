<?php

//namespace booking\models;

/**
 * Description of ProductBooking
 *
 * @author patrick
 */
class BookingProduct extends ObjectModel
{

    //put your code here
    public $id_booking;
    public $id_cart;
    public $id_product;
    public $checkin_date;
    public $checkout_date;
    public $token;
    public $date_add;
    public $date_upd;
    
    /**
     *
     * @var type 
     */
    public static $definition = array(
        'table' => 'booking_product',
        'primary' => 'id_booking',
        'multilang' => false,
        'fields' => array(
            'id_booking' => array(
                'type' => self::TYPE_INT,
            ),
            'id_cart' => array(
                'type' => self::TYPE_INT,
            ),
            'id_product' => array(
                'type' => self::TYPE_INT,
            ),
            'checkin_date' => array(
                'type' => self::TYPE_DATE
            ),
            'checkout_date' => array(
                'type' => self::TYPE_DATE
            ),
            'token' => array(
                'type' => self::TYPE_STRING
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE
            ),
            'date_upd' => array(
                'type' => self::TYPE_DATE
            ),
        ),
    );

}
