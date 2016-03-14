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
class EstiloTu_Citas extends Estilotu_Servicios {

	private $tablename_citas_citas; 
	private $user_id; 
	private $status;
	private $es_proveedor;
	private $es_historial;
	private $ver_citas;
	
	
	
	public function __construct( $ver_citas ) {
		global $wpdb; 
		
		$this->ver_citas = $ver_citas;
		
		parent::__construct();
		$this->tablename_citas = $wpdb->prefix . "bb_appoinments";
	}
	
	/* *********************************************** */
	/* SECCION CITAS */
	/* *********************************************** */
	public function ver_citas() {
		global $bp;
		global $wp_query;
		global $current_user;
						

		wp_enqueue_style ('et_citas');
		wp_enqueue_style ('smart-forms-min');
		
		/* ************************** */
		/* 	VALIDO SI ES PROFESIONAL */
		/* ************************** */

		$this->es_proveedor = Estilotu_Miembro::validar_miebro();
							
		/* ************************** */

		/* **************************** */
		/* 	SI TIENE STATUS 			*/
		/* **************************** */
		if ( isset($wp_query->query_vars['status']) ):
			
			$status_cita 	= $wp_query->query_vars['status'];
			$id_cita 		= $wp_query->query_vars['id_cita'];
	
			if ( $this->cancelarCita ( $id_cita , $status_cita ) ):
				echo "<h3>Se ha modificado correctamente el status de su cita</h3>";
			endif;
	
		endif;
		/* ************************** */
		
		/* ************************************ */
		/* 	SI LA ACCION ES GUARDAR 			*/
		/* ************************************ */
		if ( isset($wp_query->query_vars['accion']) && !empty($_POST) ):
						
			$accion_cita 	= $wp_query->query_vars['accion'];
			$id_cita 		= $_POST['id_servicio'];
			$fecha 			= $_POST["servicio_dia_seleccionado"];
			$hora 			= $_POST["et_meta_hora_inicio"];

			if ( $this->guardarCita ( $id_cita , $fecha , $hora ) ):
				echo "<div class='Centrar destacado_success'><span><i class='fa fa-check'></i>La consulta fue agregada con éxito!</span></div>";
			else:
				echo "<h3>No se pudo guardar su cita, intente más tarde</h3>";
			endif;
	
		endif;
		
		$this->es_historial = isset($wp_query->query_vars['servicios']) && ($wp_query->query_vars['servicios'] == "historial"); 
		
		/* TRAIGO LAS CITAS DEL USUARIO */
		$citas_pautadas = $this->listarCitas();
		
		if ( $citas_pautadas || $this->es_historial ): 
			
			wp_enqueue_script ('et_front_end_citas');
			add_action( 'wp_ajax_et_asistencia_y_pago_participante', 'et_asistencia_y_pago_participante' );
			
						
			$servicios = Estilotu_Miembro::listarServicios( $current_user->ID );
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/citas/listar-citas-display.php' ;
			
			wp_reset_postdata(); 

		else: ?>
			<h2>No hay citas pautadas</h2>
			
		<?php 
		endif; 
	} 

	/* ********************************************************************************** */
	
	/* *********************************************** */
	/* LISTAR CITAS */
	/* *********************************************** */
	protected function listarCitas( $user_id = null, $status = null , $fecha = null ) {  
		
		global $wpdb;
		global $wp_query;
		global $current_user;
						
		$this->es_historial = isset($wp_query->query_vars['servicios']) && ($wp_query->query_vars['servicios'] == "historial"); 
		$this->es_proveedor = Estilotu_Miembro::validar_miebro();
				
		if ($this->es_historial):
			$time_period = "appoinment_date < CURDATE()";
			
		else: 
			$time_period = "appoinment_date >= CURDATE()";
		
		endif;
					
		if ($user_id == null)
			$user_id = $current_user->ID;
			
		if ( !isset($fecha) )	
			$fecha = date("Y-m-d");	
		
		if ( $this->ver_citas == "recibidas" && $this->es_proveedor ):
			if ($status == null)	
				$sql = $wpdb->prepare( "SELECT * FROM $this->tablename_citas WHERE appoinment_provider_id = %d AND $time_period  ORDER BY appoinment_date ASC, appoinment_time ASC", $user_id );
			else 
				$sql = $wpdb->prepare( "SELECT * FROM $this->tablename_citas WHERE appoinment_provider_id = %d AND appoinment_status = %s AND $time_period ORDER BY appoinment_date ASC, appoinment_time ASC", $user_id , $status );
		else:
			if ($status == null)	
				$sql = $wpdb->prepare( "SELECT * FROM $this->tablename_citas WHERE appoinment_user_id = %d AND $time_period  ORDER BY appoinment_date ASC, appoinment_time ASC", $user_id );
			else 
				$sql = $wpdb->prepare( "SELECT * FROM $this->tablename_citas WHERE appoinment_user_id = %d AND appoinment_status = %s AND $time_period ORDER BY appoinment_date ASC, appoinment_time ASC", $user_id , $status);
		endif;
									
		$result = $wpdb->get_results( $sql, OBJECT );
						
		return $result;
		}
	/* *********************************************** */

	/* *********************************************** */
	/* CANCELAR UNA CITA */
	/* *********************************************** */
	protected function cancelarCita( $id_cita , $status_cita , $fecha , $hora ) { 
	
		global $wpdb;
		
		$data = array(
				'appoinment_status' => "$status_cita"
			);
		
		if ( isset( $fecha ) && !isset( $hora ) && !isset( $id_cita ) ):
		
			$where = array(
				'appoinment_date' => $fecha
			);
		
		elseif ( isset( $fecha ) && isset( $hora ) && !isset( $id_cita ) ):
	
			$where = array(
				'appoinment_date' => $fecha,
				'appoinment_time' => $hora
			);
			
		elseif ( isset( $fecha ) && isset( $hora ) && isset( $id_cita ) ):
			
			$where = array(
				'appoinment_id' => $id_cita
			);
		endif;	
			
		return $wpdb->update( $this->tablename_citas, $data, $where );
	
	}
	/* *********************************************** */
	
	/* *********************************************** */
	/* CANCELAR UNA CITA */
	/* *********************************************** */
	protected function guardarCita( $id_cita , $fecha , $hora ) { 
	
		global $wpdb;
		
		$post = get_post( $id_cita );
		$id_provider = $post->post_author;
		
		$data = array( 
					'appoinment_date' 			=> $fecha, 
					'appoinment_time' 			=> $hora, 
					'appoinment_provider_id' 	=> $id_provider, 
					'appoinment_user_id' 		=> get_current_user_id(), 
					'appoinment_service_id'		=> $id_cita, 
					'appoinment_status'			=> "confirm",
					'update_time'	 			=> current_time("Y-m-d H:i:s")
				);
		
		return $wpdb->insert( $this->tablename_citas , $data);	
	}
	/* *********************************************** */
	
	/* ************************************************* */ 
	/* REGISTRAR ASISTENCIA DE PARTICIPANTE */
	/* ************************************************* */ 
	public function et_asistencia_y_pago_participante() {
		
		/* *********************** */
		/* REVISO SEGURIDAD */
		/* *********************** */
	
		// check_ajax_referer( 'et_ajax_asistencia_y_pago_participante_nonce' , 'security' );
	
		/* *********************** */
		global $wpdb;
		$tablename = $wpdb->prefix . "bb_appoinments";
		
		$id_cita = $_POST['id_cita'];
			
		if ( isset( $_POST['appoinment_user_assist'] ) ):
			$data = array(
					'appoinment_user_assist' => $_POST['appoinment_user_assist']
				);
		
		elseif ( isset( $_POST['appoinment_user_paid'] ) ):
			$data = array(
					'appoinment_user_paid' => $_POST['appoinment_user_paid']
				);
		
		endif;
		
		$where = array(
				'appoinment_id' => $id_cita
			);
		
		$wpdb->update( $tablename, $data, $where ) ;
		die;
		
	}
	/* ************************************************* */ 
	
	

}
