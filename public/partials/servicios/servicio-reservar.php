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

if ( is_user_logged_in() ): ?>		

	<input id="calendario_servicio" type="text">

	<div class='Contenedor_Cupos'>
		<h2 id="titulo_selecciona_cupo">Selecciona un día para ver los cupos disponibles</h2>

		<div class="lista_cupos_disponibles"></div>
	
	</div>
	
	<!--
	<?php if ($vacaciones): ?>
	vacaciones = <?php echo json_encode( $dias_vacaciones ); ?>;
	vacaciones = JSON.stringify(vacaciones);
	<?php endif; ?>
	-->

<?php
else: ?>

	<h3>Debes iniciar tu sesión o registrarte para poder reservar el servicio</h3>

<?php 
endif;
?>