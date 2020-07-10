<?php
/**
 * @package   Fapi Elementor
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http://musilda.com
 * @copyright 2020 Musilda.com
 */
use Fapi\FapiClient\FapiClientFactory;
use Fapi\FapiClient\Tools\SecurityChecker;

if( !class_exists( 'Fapi_Forms' ) ){
	
	class Fapi_Forms{

		/**
		 *
		 * @since    1.0.0
		 *
		 * @var      string
		 */
		protected $username = null;

		/**
	 	 * 
		 * @since    1.0.0
		 *
		 * @var      string
		 */
		protected $password = null;

		/**
		 * Construct
		 *
		 * @since     1.0.0
		 */
		public function __construct() {	
		}

	    /**
		 * Get forms
		 *
		 * @since    1.0
		 *
		 * @return    string
		 */
		public function get_forms( $credentials ) {
		
				$fapiClient = ( new FapiClientFactory() )->createFapiClient( $credentials->get_username(), $credentials->get_password() );
                $forms = $fapiClient->getForms()->findAll();
                
                $options = array();

                $options['none'] = esc_attr__( 'None', 'elementor-fapi' );

				if( !empty( $forms ) ){

                    foreach( $forms as $form ){
                        $options[$form['path']] = $form['name'];
                    }                    

				}

			return $options;

		}

	}//End class

}