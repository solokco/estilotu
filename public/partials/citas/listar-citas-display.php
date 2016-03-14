<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       mingoagency.com
 * @since      1.0.0
 *
 * @package    Estilotu
 * @subpackage Estilotu/public/partials
 */
?>

<!-- Viene de class-citas-listar-public -->
<div id="Lista_Citas">
	<div class="col-sm-12 wpb_column column_container">
		<div class="Citas">
			<div id="mensaje" style="display:none"><h2 class="Centrar">No hay citas pautadas para este d&iacute;a: <span></span></h2></div>
			
			<?php
			// SEPARA EN GRUPOS DE DIA
			
			
			$group_date=array();
			foreach($citas_pautadas as $key => $item) {
			   $group_date[$item->appoinment_date][$item->appoinment_time][] = $item;
			}
			// SEPARA EN GRUPOS DE DIA
							
			foreach ($group_date as $key_bloque => $bloque):
				
				$dia_servicio = $this->convertir_fecha($key_bloque); ?>
				
				<div id="Bloque_<?php echo $key_bloque ?>" class="Bloque">
					<header class="header_dia"><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
						<h2><?php echo $dia_servicio ?></h2>
						
						<?php if ($this->es_proveedor): ?>
							<form method="post" name="eliminar_cita_dia" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
								<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
								<input type="hidden" name="status" value="cancel">
								<input type="submit" class="button btn-purple" value="Cancelar las clases del dia <?php echo $dia_servicio ?>">
							</form>
						<?php endif; ?>
						
					</header>
					
					<?php 
					foreach ( $bloque as $key_hora => $hora ): ?>
										
						<div class="Hora <?php echo $key_hora ?>">
							<header class="header_hora">
								<h3><?php echo date('H:i A', strtotime($key_hora)); ?></h3>
								
								<?php if ( $this->es_proveedor ): ?>
									<form method="post" name="eliminar_cita_hora" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
										<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
										<input type="hidden" name="hora" value="<?php echo $key_hora ?>">
										<input type="hidden" name="status" value="cancel">
										<input type="submit" class="button btn-purple" value="Cancelar la clase de las <?php echo date('H:i A', strtotime($key_hora)); ?>">
									</form>
								<?php endif; ?>
							</header>
							
							<div class="citas">
								<?php foreach ( $hora as $key_cita => $cita ):							
									if ($cita->appoinment_status == "confirm" )
										$status_cita = "Confirmada";			
									elseif ($cita->appoinment_status == "cancel" )
										$status_cita = "Cancelada";	
									else
										$status_cita = "En Espera";
																			
									$user = get_userdata($cita->appoinment_user_id); ?>
									<div class="usuario">
										<div class="usuario_avatar">
											<a href="/usuarios/<?php echo $user->user_login ?>"><?php echo get_avatar( $cita->appoinment_user_id, 80 ); ?></a>
										</div>
										
										<div class="usuario_datos">								
											<h5> <?php echo ($user->first_name . " " . $user->last_name); ?> - <a href="/usuarios/<?php echo $user->user_login ?>"><?php echo $user->user_login ?></a></h5>
											<h6> <?php echo $user->user_email; ?></h6>
											<p>Status: <?php echo $status_cita; ?></p>
											<p>Servicio: <a href="<?php echo post_permalink($cita->appoinment_service_id); ?>"><?php echo get_the_title($cita->appoinment_service_id); ?></p></a>
									
										</div>
										
										<div class="usuario_opciones">
											
											<div class="usuario_status">
												
												<?php if ($this->es_historial == false): ?>
													<?php if ($status_cita == "Cancelada" || $status_cita == "En Espera" ): ?>
																								
														<form method="post" name="confirmar_cita_individual" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
															<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
															<input type="hidden" name="hora" value="<?php echo $key_hora ?>">
															<input type="hidden" name="id_cita" value="<?php echo $cita->appoinment_id ?>">
															<input type="hidden" name="status" value="confirm">
															<input type="submit" class="button btn-purple" value="Confirmar">
														</form>
			
													<?php endif; ?>
													
													<?php if ($status_cita == "Confirmada" || $status_cita == "En Espera"): ?>
														
														<form method="post" name="cancelar_cita_individual" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
															<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
															<input type="hidden" name="hora" value="<?php echo $key_hora ?>">
															<input type="hidden" name="id_cita" value="<?php echo $cita->appoinment_id ?>">
															<input type="hidden" name="status" value="cancel">
															<input type="submit" class="button btn-purple" value="Cancelar">
														</form>
													<?php endif; ?>
												<?php endif; ?>
											</div>
																					
											<?php if ( $this->es_proveedor && get_post_field( 'post_author', $cita->appoinment_service_id ) == get_current_user_id() ): ?>
												 <div class="smart-forms">											
													<div class="usuario_opciones">
														<form method="post" name="formulario_usuario_opciones" class="formulario_usuario_opciones" id="formulario_usuario_opciones_<?php echo $cita->appoinment_id ?>" enctype="multipart/form-data">
		
															<div class="section">
															    <div class="option-group field">
															    
															        <label class="switch">
															            <input type="checkbox" name="asistencia" class="appoinment_user_assist boton_<?php echo $cita->appoinment_id ?>" id="<?php echo $cita->appoinment_id ?>" value="asistencia" <?php checked( $cita->appoinment_user_assist , 1 ); ?>>
															            <span class="switch-label" data-on="SI" data-off="NO"></span>
															            <span> &iquest;Asisti&oacute;&#63; </span>
															        </label>                     
															    </div><!-- end .option-group section -->
															</div><!-- end section -->
															
															<div class="section">
															    <div class="option-group field">
															    
															        <label class="switch">
															            <input type="checkbox" name="payment" class="appoinment_user_pay boton_<?php echo $cita->appoinment_id ?>" id="<?php echo $cita->appoinment_id ?>" value="payment" <?php checked( $cita->appoinment_user_paid , 1 ); ?>>
															            <span class="switch-label" data-on="SI" data-off="NO"></span>
															            <span> &iquest;Pag&oacute;&#63; </span>
															        </label>                     
															    </div><!-- end .option-group section -->
															</div><!-- end section -->
																												
														</form>
													</div>
												 </div>
											<?php endif; ?>
										</div>	
											
									</div>
								<?php endforeach; ?>		
							</div>
						</div>
		
					<?php 
					endforeach; 
					?>
				</div>
			<?php 
			endforeach;	 ?>
		</div>
	</div>

</div>