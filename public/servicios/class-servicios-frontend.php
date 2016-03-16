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
class Estilotu_Servicios_FrontEnd {
	
	private $en_edicion;
	private $servicio;
	private $servicio_meta;
	private $servicios_categoria;
	private $post_id;
	private $fecha_seleccionada;
	private $dia_seleccionado;
	private $table_name;
	private $provider_id;
	private $old_version = false;
	
	
	
	public function __construct() {
		add_shortcode("et_listar_seccion_profesionales_activos", array ($this , "et_listar_seccion_profesionales_activos") );
		add_shortcode("et_listar_profesionales_activos", array ($this , "et_listar_profesionales_activos") );
		add_shortcode("et_ver_lista_servicios", array ($this , "listar_servicios" ) );
		
	}
	
	/* *********************************************** */
	/* MOSTRAR EL CALENDARIO EN EL FRONT */
	/* *********************************************** */
	public function ver_calendario_servicios () {	
		
	    $this->post_id = get_the_ID(); 
	    $this->provider_id = get_the_author_meta('ID');
		
		$this->servicio_meta = get_post_custom($this->post_id) ;
		
		$url_guardar = add_query_arg( 'accion', 'guardar', bp_core_get_user_domain( get_current_user_id() ) . "citas" );	
		
		$disponibilidad = unserialize($this->servicio_meta['disponibilidad_servicio'][0]);

		/* ******************************************** */
		/* EN CASO QUE SE TENGA LA ESTRUCTURA VIEJA 	*/
		/* ******************************************** */
		if ( isset($this->servicio_meta['bloque_lunes'][0]) )	
			$cuposLunes 	= unserialize( unserialize( $this->servicio_meta['bloque_lunes'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_martes'][0]) )	
			$cuposMartes 	= unserialize( unserialize( $this->servicio_meta['bloque_martes'][0] ) ) ;
	
		if ( isset($this->servicio_meta['bloque_miercoles'][0]) )		
			$cuposMiercoles = unserialize( unserialize( $this->servicio_meta['bloque_miercoles'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_jueves'][0]) )	
			$cuposJueves 	= unserialize( unserialize( $this->servicio_meta['bloque_jueves'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_viernes'][0]) )	
			$cuposViernes 	= unserialize( unserialize( $this->servicio_meta['bloque_viernes'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_sabado'][0]) )	
			$cuposSabado 	= unserialize( unserialize( $this->servicio_meta['bloque_sabado'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_domingo'][0]) )	
			$cuposDomingo 	= unserialize( unserialize( $this->servicio_meta['bloque_domingo'][0] ) ) ;	
		/* ******************************************** */
		
		
		//  ************************
		// 	REVISO POR VACACIONES
		//  ************************
		global $wpdb;
	    	    
		//$tablename = $wpdb->prefix . "bb_vacations";
		//$sql = $wpdb->prepare( "SELECT * FROM $tablename WHERE vacation_provider_id = %d", $provider_id );
		
		//$vacaciones = $wpdb->get_results($sql, OBJECT );
		
		/*
		if ($vacaciones):
			
			$dias_vacaciones = array();
			
			foreach ($vacaciones as $vacaion) :
				
				$start = strtotime($vacaion->vacation_start);
				$end = strtotime($vacaion->vacation_end);
			
			
				for ($i = $start; $i <= $end; $i += 24 * 3600):
	
			        $dias_vacaciones []= date("Y-m-d", $i);
			        
				endfor;
							
			endforeach; 
			
		endif;
		*/
		
		
		/* ****************************************** 		*/
		/* BUSCO LOS DIAS LIBRES Y OCUPADOS DEL SERVICIO	*/
		/* ******************************************		*/
		$dias = array("domingo","lunes","martes","miercoles","jueves","viernes","sabado" );
		$dias_activados = array();
		$dias_desactivados = array();
				
		/* ****************************************** 	*/
		/* SI SE ESTA USANDO LA VERSION 1.0 			*/
		/* ******************************************	*/
		if ( !isset($this->servicio_meta['disponibilidad_servicio']) && ( isset($cuposDomingo) || isset($cuposLunes) || isset($cuposMartes) || isset($cuposMiercoles) || isset($cuposJueves) || isset($cuposViernes) || isset($cuposSabado) ) ):
		
			$this->old_version = true;

			foreach ( $dias as $key_dia => $dia_disponible ):
				
				if ( $this->servicio_meta['et_meta_dias_activo_'.$dia_disponible][0] == "on" ):
				
					$dias_activados[] = $key_dia;
				
				else:
					
					$dias_desactivados[] = $key_dia;
					
				endif;	

			endforeach;
		/* ******************************************	*/
		
		/* ****************************************** */
		/* SI SE ESTA USANDO LA VERSION 2.0 */
		/* ****************************************** */
		elseif ( isset( $this->servicio_meta["disponibilidad_servicio"][0]  ) ):
			
			$disponibilidad = unserialize($this->servicio_meta['disponibilidad_servicio'][0]);
				
			foreach ( $dias as $key_dia => $dia_disponible ):

				if ( array_key_exists ("activo" , $disponibilidad[$dia_disponible]) ):
				
					$dias_activados[] = $key_dia;
				
				else:
					
					$dias_desactivados[] = $key_dia;
					
				endif;	

			endforeach;
		
		endif;
		/* ****************************************** */
		
		
		$max_date = date_i18n('Y-m-d');
		$max_date = strtotime($max_date);
		$max_date = strtotime( $this->servicio_meta['et_meta_max_time'][0] . " day", $max_date);		
		$max_date = date('Y-m-d' , $max_date);
		
		$datatoBePassed = array(
			'id_servicio' 	=> $this->post_id,
			'url_user'		=> $url_guardar,	
			'old_version'	=> $this->old_version,
			'dias_activados' => $dias_activados,
			'dias_desactivados' => $dias_desactivados,
			'max_date'			=> $max_date,
			
			'close_time'		=> $this->servicio_meta["et_meta_close_time"][0]
		);
		
		wp_enqueue_script( 'et_mostrar_calendario_fe');
		wp_localize_script( 'et_mostrar_calendario_fe', 'php_vars', $datatoBePassed );
		wp_enqueue_script( 'et_datetimepicker');

		wp_enqueue_style('et_datetimepicker');
		wp_enqueue_style('et_servicios');
		
		
		// ARCHIVO FRONT
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/servicios/servicio-reservar.php' ;
					
	}
	
	
	/* *********************************************** */
	/* AJAX CARGA LOS CUPOS DISPONIBLES DE UN SERVICIO */
	/* *********************************************** */
	public function et_cargar_cupos_func( ) {
		
		$this->post_id 				= $_POST['id_servicio'];
		$this->fecha_seleccionada 	= $_POST['fecha_seleccionada'];
		$this->dia_seleccionado		= $_POST['dia_seleccionado'];
		$this->old_version			= $_POST['is_old'];
		
		$this->servicio_meta = get_post_custom($this->post_id) ;
		
		$activo = false;
		
		$dias = array("domingo","lunes","martes","miercoles","jueves","viernes","sabado" );
		$dia = $dias[$this->dia_seleccionado];
		
		if ( $this->old_version) :
			
			// SI ESTOY USANDO EL ARRAY VIEJO
			if ($this->servicio_meta['et_meta_dias_activo_' . $dia][0] == "on"):
				
				$activo = true;
				
				$bloque = unserialize(unserialize($this->servicio_meta['bloque_' . $dia][0]));
				
				$disponibilidad[$dia]["bloque"] = $bloque;
				
				
				foreach ( $disponibilidad[$dia]["bloque"] as $key => $value ):
			
					foreach ( $value as $key2 => $value2 ):
						
						if ($key2 == "et_meta_hora_inicio"):
							$time_in_24_hour_format  = date("H:i:s", strtotime($value2));
		
							$disponibilidad[$dia]["bloque"][$key][$key2] = $time_in_24_hour_format;
							
						endif;
						
					endforeach;
										
				endforeach;

				
			
			else:
				$activo = false;
			
			endif;
						
		else:
			
			if ( isset( $this->servicio_meta["disponibilidad_servicio"][0]  ) ):
				$disponibilidad = unserialize($this->servicio_meta['disponibilidad_servicio'][0]);
				
				if ( $disponibilidad[$dia]["activo"] )
					$activo = true;
				else
					$activo = false;
					
			endif;	
			
		endif;
		
		
		if ( $activo ):
						
			global $wpdb;
			global $current_user;
			
			$this->table_name = $wpdb->prefix . "bb_appoinments";
			$user_ID = get_current_user_id();
			
			$citas = $wpdb->prepare("SELECT appoinment_time , appoinment_user_id FROM $this->table_name WHERE appoinment_date = %s AND appoinment_service_id = %d AND (appoinment_status = 'confirm' OR appoinment_status = 'hold' )" , $this->fecha_seleccionada , $this->post_id ); 						
			$citas = $wpdb->get_results($citas , ARRAY_A);
			
			if ( isset($citas) && is_array($citas) ):
				
				// Creo un array para las horas duplicadas de este dia
				$ocupado = array();
				foreach ($citas as $key => $value){
				    foreach ($value as $key2 => $value2){
				        
				        if ( $key2 == "appoinment_time") {
					        $index = $value2;
					        if (array_key_exists($index, $ocupado)){
					            $ocupado[$index]++;
					        } else {
					            $ocupado[$index] = 1;
					        }	
						}
				    }   
				}
				
				// Creo un array para las horas que el usuario ya tiene reserva
				$reservado = array();
				foreach ($citas as $key => $value){
				    
				    foreach ($value as $key2 => $value2){
				        
				        if ( $key2 == "appoinment_user_id" && $value2 == $user_ID ) {
					        $index = $value["appoinment_time"];
					        if (!array_key_exists($index, $reservado)){
					            $reservado[$index] = true;
					        }	
						}
					}	
				}
			 
				
				$disponibilidad[$dia]["ocupado"] 	= $ocupado;
				$disponibilidad[$dia]["reservado"] 	= $reservado;
								
				ob_start("ob_gzhandler");
					$return = $disponibilidad[$dia];					
				ob_end_clean();
				
			endif;	
		
		else:
			ob_start("ob_gzhandler");
			$return = 0;
			ob_end_flush();
		endif;

		wp_send_json($return) ;
		
		wp_die();
		
		
	}
	/* *********************************************** */
	
	/* *********************************************** 	*/
	/* CUENTO LAS RESERVAS DE UN SERVICIOS				*/
	/* *********************************************** 	*/
	public function et_contar_reservas ( $fecha_seleccionada , $post_id  ) {
		
		global $wpdb;
		global $current_user;
			
		$table_name = $wpdb->prefix . "bb_appoinments";
		$user_ID = get_current_user_id();
		
		$citas = $wpdb->prepare("SELECT appoinment_time , appoinment_user_id FROM $table_name WHERE appoinment_date = %s AND appoinment_service_id = %d AND (appoinment_status = 'confirm' OR appoinment_status = 'hold' )" , $fecha_seleccionada , $post_id ); 						
		$citas = $wpdb->get_results($citas , ARRAY_A);
		
		$servicio = array();
		
		if ( isset($citas) && is_array($citas) ):

			foreach ($citas as $key => $value){
			    foreach ($value as $key2 => $value2){
			        
			        if ( $key2 == "appoinment_time") {
				        $index = $value2;
				        if (array_key_exists($index, $servicio["ocupado"] ) ) {
				            $servicio["ocupado"][$index]++;
				        } else {
				            $servicio["ocupado"][$index] = 1;
				        }	
					}
			    }   
			}
			
			
			foreach ($citas as $key => $value){
			    
			    foreach ($value as $key2 => $value2){
			        
			        if ( $key2 == "appoinment_user_id" && $value2 == $user_ID ) {
				        $index = $value["appoinment_time"];
				        if (!array_key_exists($index, $servicio["reservado"])){
				            $servicio["reservado"][$index] = true;
				        }	
					}
				}	
			}
		
		else:
		
			$servicio["ocupado"] 	= 0;
			$servicio["reservado"] 	= 0;
			
		endif;
		
		return $servicio;	
	
	}
	/* *********************************************** */
		
	public function ordenar_por_hora ($a, $b) {
		$t1 = strtotime($a);
	    $t2 = strtotime($b);
	    
	    return $t1 - $t2;
	}
	
	// ************************************************
	//	SHORTCODE PARA AGRUPAR USUARIOS PROFESIONALES
	// *************************************************
	public function et_listar_seccion_profesionales_activos() { 
		
		$member_args = array('member_type' => array( 'profesional' ) ) ;
		
		do_action( 'bp_before_members_loop' );
		
		if ( bp_has_members( $member_args ) ) : ?>
	
		<div id="buddypress">
		<style>
			#buddypress #members-list li div.item-avatar {width:65%;height:auto;float:none; margin: 0px auto;border:none;}
			#buddypress #members-list li div.item-avatar img {box-shadow:0px 0px 8px #333;}
					
			#buddypress #members-list div.item {display:inline-block;width:100%;}
			
			.user_services {text-align:center;padding:20px 0px;}
			.user_services h3 a {color: #7d588e;}
			
			#buddypress #members-list .user_services li {list-style-type: circle;display:list-item;}
			
			#buddypress #members-list .item-meta {margin:10px 0px;text-align:center;}
			
		</style>
			<div id="pag-top" class="pagination">
		
				<div class="pag-count" id="member-dir-count-top">
		
					<?php bp_members_pagination_count(); ?>
		
				</div>
		
				<div class="pagination-links" id="member-dir-pag-top">
		
					<?php bp_members_pagination_links(); ?>
		
				</div>
		
			</div>
	
			<?php do_action( 'bp_before_directory_members_list' ); ?>
		
			<ul id="members-list" class="item-list row kleo-isotope masonry">
		
				<?php while ( bp_members() ) : bp_the_member(); ?>
			
					<li class="kleo-masonry-item">
						<div class="member-inner-list animated animate-when-almost-visible bottom-to-top">
							<div class="item-avatar rounded">
								<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('type=full&width=180&height=180'); ?></a>
							</div>
					
							<div class="item">
								<div class="item-title">
									<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
								</div>
		  
								<?php do_action( 'bp_directory_members_item' ); ?>
		  
								<div class="user_social">
									<!--
									<?php
	
									if ( bp_get_member_profile_data( 'field=13' ) ) { ?>
									
										<a target='_blank' href="<?php echo bp_get_member_profile_data( 'field=13' ) ;?>">Web</a>
										
									<?php }
										
									if ( bp_get_member_profile_data( 'field=Facebook' ) ) { ?>
									
										<a target='_blank' href="<?php echo bp_get_member_profile_data( 'field=Facebook' ) ;?>">Facebook</a>
										
									<?php }
										
									if ( bp_get_member_profile_data( 'field=Twitter' ) ) { ?>
									
										<a target='_blank' href="<?php echo bp_get_member_profile_data( 'field=Twitter' ) ;?>">Twitter</a>
										
									<?php }
										
									if ( bp_get_member_profile_data( 'field=Instagram' ) ) { ?>
									
										<a target='_blank' href="<?php echo bp_get_member_profile_data( 'field=Instagram' ) ;?>">Instagram</a>
										
									<?php }
										
									if ( bp_get_member_profile_data( 'field=Google+' ) ) { ?>
									
										<a target='_blank' href="<?php echo bp_get_member_profile_data( 'field=Google+' ) ;?>">Google+</a>
										
									<?php }			
									
									
										
									?>
									-->
								</div>
								
								<div class="user_services">
																									
									<?php
									$args = array(
									  'post_type'   => 'servicios', 
									  'post_status' => 'publish',
									  'author'		=>	bp_get_member_user_id()
									);
									
									$the_query = new WP_Query( $args );
	
									// The Loop
									if ( $the_query->have_posts() ) { ?>
									  
									  <a class="vc_general vc_btn3 vc_btn3-size-sm vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-left vc_btn3-color-violet" href="<?php bp_member_permalink(); ?>servicios/#item-nav" title="" target="_self"><i class="vc_btn3-icon entypo-icon entypo-icon-ticket"></i> Ver Servicios</a>
									  
									  <?php 
									  echo '<ul>';
									  while ( $the_query->have_posts() ) {
									    $the_query->the_post();
									    echo '<li><a href="'.get_permalink().'">' . get_the_title() . '</a></li>';
									  }
									  echo '</ul>';
									  
									  
									} else {
									  echo "<p>Sin servicios</p>";
									}
									/* Restore original Post Data */
									wp_reset_postdata();							    
								    
								    
									?>
									
								</div>
								
								
								<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span></div>
								
			        		</div>
		  
							<div class="action">
					
								<?php do_action( 'bp_directory_members_actions' ); ?>
					
							</div>
					
						</div><!--end member-inner-list-->
					</li>
		
				<?php endwhile; ?>
	
			</ul>
	
			<?php do_action( 'bp_after_directory_members_list' ); ?>
	
			<?php bp_member_hidden_fields(); ?>
	
			<div id="pag-bottom" class="pagination">
		
				<div class="pag-count" id="member-dir-count-bottom">
		
					<?php bp_members_pagination_count(); ?>
		
				</div>
		
				<div class="pagination-links" id="member-dir-pag-bottom">
		
					<?php bp_members_pagination_links(); ?>
		
				</div>
		
			</div>
	
		</div>
		
		<?php else: ?>
	
			<div id="message" class="info">
				<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
			</div>
	
		<?php endif; ?>
	
		<?php do_action( 'bp_after_members_loop' );
	
	}
	
	// ************************************************
	//	SHORTCODE PARA AGRUPAR USUARIOS PROFESIONALES
	// *************************************************
	function et_listar_profesionales_activos($atts, $content=null, $code="") {
		// examples: [et_listar_profesionales_activos avatar="false" email="false" levels="1,2"]
		
		extract(shortcode_atts(array(
			'avatar' => NULL,
			'email' => NULL,
			'levels' => NULL,
			'show_level' => NULL,
			'show_search' => NULL,
			'start_date' => NULL,
			'perline' => 4,
			'cantidad' => 12,
			'animation' => '',
			'rounded' => "",
			'class' => ''
		), $atts));
		
		global $wpdb;
		
		//turn 0's into falses
		if($avatar === "0" || $avatar === "false" || $avatar === "no")
			$avatar = false;
		else
			$avatar = true;
		
		if($email === "0" || $email === "false" || $email === "no")
			$email = false;
		else
			$email = true;
	
		if($show_level === "0" || $show_level === "false" || $show_level === "no")
			$show_level = false;
		else
			$show_level = true;
	
		if($show_search === "0" || $show_search === "false" || $show_search === "no")
			$show_search = false;
		else
			$show_search = true;
	
		if($start_date === "0" || $start_date === "false" || $start_date === "no")
			$start_date = false;
		else
			$start_date = true;
	
		ob_start();
		if(isset($_REQUEST['ps']))
			$s = $_REQUEST['ps'];
		else
			$s = "";
		
		if(isset($_REQUEST['pk']))
			$key = $_REQUEST['pk'];
		else
			$key = "";
			
		if(isset($_REQUEST['pn']))
			$pn = $_REQUEST['pn'];
		else
			$pn = 1;
			
		if($s)
		{
			$sqlQuery = "SELECT SQL_CALC_FOUND_ROWS u.ID, u.user_login, u.user_email, u.user_nicename, u.display_name, UNIX_TIMESTAMP(u.user_registered) as joindate, mu.membership_id, mu.initial_payment, mu.billing_amount, mu.cycle_period, mu.cycle_number, mu.billing_limit, mu.trial_amount, mu.trial_limit, UNIX_TIMESTAMP(mu.startdate) as startdate, UNIX_TIMESTAMP(mu.enddate) as enddate, m.name as membership FROM $wpdb->users u LEFT JOIN $wpdb->usermeta um ON u.ID = um.user_id LEFT JOIN $wpdb->pmpro_memberships_users mu ON u.ID = mu.user_id LEFT JOIN $wpdb->pmpro_membership_levels m ON mu.membership_id = m.id WHERE mu.status = 'active' AND mu.membership_id > 0 AND ";
			
			if(empty($key))
				$sqlQuery .= "(u.user_login LIKE '%$s%' OR u.user_email LIKE '%$s%' OR u.display_name LIKE '%$s%' OR um.meta_value LIKE '%$s%') ";
			else
				$sqlQuery .= "(um.meta_key = '" . $wpdb->escape($key) . "' AND um.meta_value LIKE '%$s%') ";
		
			if($levels)
				$sqlQuery .= " AND mu.membership_id IN(" . $levels . ") ";					
				
			$sqlQuery .= "GROUP BY u.ID ORDER BY user_registered DESC";
		}
		else
		{
			$sqlQuery = "SELECT SQL_CALC_FOUND_ROWS u.ID, u.user_login, u.user_email, u.user_nicename, u.display_name, UNIX_TIMESTAMP(u.user_registered) as joindate, mu.membership_id, mu.initial_payment, mu.billing_amount, mu.cycle_period, mu.cycle_number, mu.billing_limit, mu.trial_amount, mu.trial_limit, UNIX_TIMESTAMP(mu.startdate) as startdate, UNIX_TIMESTAMP(mu.enddate) as enddate, m.name as membership FROM $wpdb->users u LEFT JOIN $wpdb->pmpro_memberships_users mu ON u.ID = mu.user_id LEFT JOIN $wpdb->pmpro_membership_levels m ON mu.membership_id = m.id";
			$sqlQuery .= " WHERE mu.membership_id > 0  AND mu.status = 'active' ";
			if($levels)
				$sqlQuery .= " AND mu.membership_id IN(" . $levels . ") ";
			$sqlQuery .= "ORDER BY user_registered DESC";
		}
				
		$theusers = $wpdb->get_results($sqlQuery);
		$totalrows = $wpdb->get_var("SELECT FOUND_ROWS() as found_rows");
		
		ob_start();
		
		?>
		
		
		<?php
			if(!empty($theusers))
			{
				?>
				<div class="pmpro_member_directory">
					<?php
					$count = 0;
					$lista_usuarios = array();
					foreach($theusers as $auser):
						
						$auser = get_userdata($auser->ID);
	
						$user_post_count = count_user_posts( $auser->ID , "servicios" );
																		
						if ( $user_post_count > 0 ):
							
							$lista_usuarios[] = $auser->ID;
	
						endif;																		
	
					endforeach; ?>
					
					<style>
						.kleo-thumbs-images a img {max-width:120px; height:auto;}	
					</style>
					
					<?php
										
					$output = $anim1 = '';
					
					$lista_usuarios = implode(",",$lista_usuarios);
					
					$params = array(
						//'type' => "popular",
						'per_page' => $cantidad,
						'include' => $lista_usuarios,
						
					);
					
					if($perline != '') {
						$class .= ' ' . $perline . '-thumbs';
					}
					
					if ($animation != '') {
						$anim1 = ' animate-when-almost-visible';
						$class .= ' kleo-thumbs-animated th-' . $animation;
					}
					
					if ($rounded == 'rounded') {
						$class .= ' rounded';
					}
					
					if ( bp_has_members( $params ) ){
						$output .= '<div class="wpb_wrapper">';
						$output .= '<div class="kleo-gallery'.$anim1.'">';
						$output .= '<div class="kleo-thumbs-images '.$class.'">';
							while( bp_members() ){
				
									bp_the_member();
									$output .= '<a href="'. bp_get_member_permalink() .'" title="'. bp_get_member_name() .'">';
											$output .= bp_get_member_avatar( array(	'type' => 'full', 'width' => '120', 'height' => '120' ));
											$output .= kleo_get_img_overlay();
									$output .= '</a>';	
				
							}
						$output .= '</div>';	
						$output .= '</div>';
						$output .= '</div>';
					}
					?>
				</div>
				<?php
					
				echo $output;	
			}	
			else
			{	
				?>
				<div class="pmpro_member_directory_message pmpro_message pmpro_error">No matching profiles found<?php if($s) { ?> within <em><?php echo ucwords(esc_html($s)); ?></em>. <a href="<?php echo get_permalink(); ?>">View All Members</a><?php } else { ?>.<?php } ?></div>
				<?php					
			}
	
		$temp_content = ob_get_contents();
		ob_end_clean();
		return $temp_content;
	}
	
	// *************************************************

	
	// *************************************************
	
}