<?php 

declare(strict_types = 1);

use Fapi\FapiClient\FapiClientFactory;
use Fapi\FapiClient\Tools\SecurityChecker;

add_action( 'admin_menu', 'fapi_add_plugin_admin_menu' );
function fapi_add_plugin_admin_menu(){

    
    add_submenu_page(
        'options-general.php',
        __( 'Fapi', 'fapi-membership' ),
        __( 'Fapi', 'fapi-membership' ),
        'manage_options',
        'fapi',
        'fapi_admin_page'
    );
    
}


function fapi_admin_page(){
    
    $credentials = new Fapi_Credentials();
    //Save credentials if form is sent
    $credentials->save_credentials();
    $credentials->set_credentials();
    
    $forms = new Fapi_Forms();
	$option = $forms->get_forms( $credentials );
    ?>

    <div class="wrap">

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

        <div class="t-col-12">
            <div class="toret-box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php esc_attr_e( 'Credentials', 'elementor-fapi' ); ?></h3>
                </div>
                <div class="box-body">
                    <form method="post" action="">
                        <table class="table-bordered">
                            <tr>
                                <th><?php esc_attr_e( 'Username', 'elementor-fapi' ); ?></th>
                                <th><?php esc_attr_e( 'API key', 'elementor-fapi' ); ?></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td><input type="text" name="fapi_username" style="width:100%" <?php if( !empty( $credentials->get_username() ) ){ echo 'value="'.$credentials->get_username().'"'; } ?> /></td>
                                <td><input type="text" name="fapi_password" style="width:100%" <?php if( !empty( $credentials->get_password() ) ){ echo 'value="'.$credentials->get_password().'"'; } ?> /></td>
                                <td class="td_center"><input class="btn btn-success" type="submit" name="credentials" value="<?php esc_attr_e( 'Save', 'elementor-fapi' ); ?>" /></td>
                            </tr>
                        </table>
                    </form>                
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>

        </div>
        <div class="clear"></div>

    </div>
    <?php

}



add_action('admin_init', 'fapi_output_buffer' );
function fapi_output_buffer() {
    ob_start();
}
    