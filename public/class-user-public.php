<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       mingoagency.com
 * @since      1.0.0
 *
 * @package    Estilotu
 * @subpackage Estilotu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Estilotu
 * @subpackage Estilotu/public
 * @author     Carlos Carmona <ccarmona@mingoagency.com>
 */
class Estilotu_User extends Estilotu_Public {

	private $user_id;
	
	public function __construct() {
	
		add_shortcode("et_ver_porcentaje_registro", array( $this , "et_ver_porcentaje_registro") );
		
	}
	
	// *************************************************
	// MUESTRA PORCENTAJE DE PERFIL COMPLETADO
	// *************************************************
	public function et_ver_porcentaje_registro() { 
		
		// REVISO SI EL USUARIO ESTA REGISTRADO
		if ( is_user_logged_in() ):
			
			global $current_user;
			$user_id = $current_user->ID;
			
			if ( bp_has_profile("user_id=".$user_id."&hide_empty_groups=0&hide_empty_fields=0") ) :
				$cantidad_campos = 0;
				$cantidad_campos_llenos = 0;
				$porcentaje_lleno = 0;
				$color = "#964ae2";
							
				$username = $current_user->user_login;
	
				$tiene_avatar = bp_get_user_has_avatar( $user_id );
									
				while ( bp_profile_groups() ) : bp_the_profile_group();
				
					while ( bp_profile_fields() ) : bp_the_profile_field(); 
					    
					    if ( bp_field_has_data() ) : 
							
							$cantidad_campos_llenos++;
					
						endif; 
						
						$cantidad_campos++; 
					  
					endwhile; 
				
				endwhile; 
			   
				// OBTIENE EL %
				$porcentaje_lleno = round( ($cantidad_campos_llenos * 100 ) / $cantidad_campos );
							
				if ( $porcentaje_lleno <= 50 ): ?>
					
					<style>small.vc_label{color:#FFF;}</style>
					
					<?php
					$color = "#d71919";
				
				elseif ( $porcentaje_lleno >= 51 && $porcentaje_lleno < 75 ): ?>
				
					<style>small.vc_label{color:#000;}</style>
					
					<?php
					$color = "#ffea00";
				
				else: ?>
					
					<style>small.vc_label{color:#FFF;}</style>
					
					<?php
					$color = "#964ae2";
				
				endif;
			   
				// IMPRIME LA BARRA DE PROGRESO
				echo do_shortcode( '[vc_progress_bar values="'.$porcentaje_lleno.'|Perfil Completado" bgcolor="custom" options="striped,animated" units="%" custombgcolor="'.$color.'"]' ); 
			   
				// MUESTRA BOTON SI NO TIENE AVATAR
				if (!$tiene_avatar)
					echo do_shortcode( '[vc_btn title="Agrega tu foto de perfil" style="modern" shape="round" color="violet" size="md" align="center" i_align="left" i_type="fontawesome" i_icon_fontawesome="fa fa-user" i_icon_openiconic="vc-oi vc-oi-dial" i_icon_typicons="typcn typcn-adjust-brightness" i_icon_entypo="entypo-icon entypo-icon-note" i_icon_linecons="vc_li vc_li-heart" link="url:%2Fusuarios%2F'. $username .'%2Fprofile%2Fchange-avatar%2F%23item-nav||" button_block="true" add_icon="true" i_icon_pixelicons="vc_pixel_icon vc_pixel_icon-alert"]' );
				
				// MUESTRA BOTON SI NO TIENE AVATAR
				echo do_shortcode( '[vc_btn title="Completa el resto de tu perfil" style="modern" shape="round" color="violet" size="md" align="center" i_align="left" i_type="fontawesome" i_icon_fontawesome="fa fa-pencil" i_icon_openiconic="vc-oi vc-oi-dial" i_icon_typicons="typcn typcn-adjust-brightness" i_icon_entypo="entypo-icon entypo-icon-note" i_icon_linecons="vc_li vc_li-heart" link="url:%2Fusuarios%2F'. $username .'%2Fprofile%2Fedit%2Fgroup%2F1%2F#item-nav||" button_block="true" add_icon="true" i_icon_pixelicons="vc_pixel_icon vc_pixel_icon-alert"]' ); ?>
	 
			<?php else: ?>
			 
			  <div id="message" class="info">
			    <p>This user does not have a profile.</p>
			  </div>
			 
			<?php 
			endif;
	
		endif;
	}
	// *************************************************
	


}
