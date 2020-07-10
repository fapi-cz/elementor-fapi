<?php
namespace ElementorFapi\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Fapi\FapiClient\FapiClientFactory;
use Fapi\FapiClient\FapiClient;
use Fapi\FapiClient\Tools\SecurityChecker;
use Fapi\FapiClient\EndPoints\ApiTokens;
use Fapi\FapiClient\EndPoints\Clients;
use Fapi\FapiClient\EndPoints\Countries;
use Fapi\FapiClient\EndPoints\DiscountCodes;
use Fapi\FapiClient\EndPoints\ExchangeRates;
use Fapi\FapiClient\EndPoints\Forms;
use Fapi\FapiClient\EndPoints\Invoices;
use Fapi\FapiClient\EndPoints\Items;
use Fapi\FapiClient\EndPoints\ItemTemplates;
use Fapi\FapiClient\EndPoints\MessageTemplates;
use Fapi\FapiClient\EndPoints\Orders;
use Fapi\FapiClient\EndPoints\PeriodicInvoices;
use Fapi\FapiClient\EndPoints\Settings;
use Fapi\FapiClient\EndPoints\Statistics;
use Fapi\FapiClient\EndPoints\User;
use Fapi\FapiClient\EndPoints\UserSettings;
use Fapi\FapiClient\Rest\FapiRestClient;
use Fapi\HttpClient\IHttpClient;

use Fapi_Credentials;
use Fapi_Forms;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Fapi extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0
	 *
	 * @access public
	 *
	 * @return string Widget name
	 */
	public function get_name() {
		return 'fapi';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Fapi', 'fapi-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'fapi-elementor' ),
			]
		);

		$this->add_control(
			'fapiforms',
			[
				'label' => __( 'Fapi forms', 'fapi-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_fapi_forms()
			]
        );
        
        $this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'fapi-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_transform',
			[
				'label' => __( 'Text Transform', 'fapi-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'fapi-elementor' ),
					'uppercase' => __( 'UPPERCASE', 'fapi-elementor' ),
					'lowercase' => __( 'lowercase', 'fapi-elementor' ),
					'capitalize' => __( 'Capitalize', 'fapi-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .title' => 'text-transform: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		
		if ( $settings['fapiforms'] ) {
            
			echo '<script type="text/javascript" src="https://form.fapi.cz/script.php?id='.$settings['fapiforms'].'"></script>';
			
		}
	}

	/**
	 * Get forms from Fapi
	 *
	 * @since 1.0
	 *
	 * @access protected
	 */
	protected function get_fapi_forms() {
	
		$credentials = new Fapi_Credentials();
		$credentials->set_credentials();

		$forms = new Fapi_Forms();
		$option = $forms->get_forms( $credentials );

		return $option;

	}
}
