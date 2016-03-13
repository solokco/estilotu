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
	

	
	
}