jQuery(document).ready(function( $ ) {
	// http://xdsoft.net/jqplugins/datetimepicker/
	
	// php_vars trae por wp_localize_script las variable declaradas en class-servicios-frontend.php
		
	jQuery.datetimepicker.setLocale('es');
	
	var logic = function( date , input ){
		var cupos_disponibles;		

		jQuery("body").addClass("loading");
		
		$.ajax({
	        url	: ajax_object.ajax_url,
	        type: "POST",
	        datatype:"json",
	        data: { 
				"action"				: 'et_cargar_cupos_func',
				"id_servicio"			: php_vars.id_servicio,
				"fecha_seleccionada"	: input.val(),
				"dia_seleccionado"		: date.getDay(),
				"is_old"				: php_vars.old_version
            },      
	         
	        success: function( data, textStatus, jqXHR ) { // Si todo salio bien se ejecuta esto
				
				cupos_disponibles = mostrar_cupos( data , input.val() );
				
				jQuery(".lista_cupos_disponibles").html( cupos_disponibles );
				jQuery("body").removeClass("loading");
				jQuery("#titulo_selecciona_cupo").hide();
				
			}
        })
        
        .fail(function( jqXHR, textStatus, errorThrown, data ) { // Si todo salio MAL se ejecuta esto
			
			alert('Ocurrio un error y no se pudo procesar su solicitud correctamente.');
			jQuery("body").removeClass("loading");

        });
	};
	
	jQuery("#calendario_servicio").datetimepicker({
		
		onGenerate:function( ct ){

			var desactivar = "";
			var max_array;
			
			max_array = php_vars.dias_desactivados.length;
						
			$.each(php_vars.dias_desactivados , function ( key , dia ) {
				
				if ( key >= max_array - 1  ) {
					desactivar += '.xdsoft_date.xdsoft_day_of_week'+dia ;
				}
				else {
					desactivar += '.xdsoft_date.xdsoft_day_of_week'+dia+', ' ;
				}
				
				
			});
			
			jQuery(this).find(desactivar).addClass('xdsoft_disabled');
			
		},
		//weekends:['01.01.2014','02.01.2014','03.01.2014','04.01.2014','05.01.2014','06.01.2014'],
		
		onChangeDateTime: logic,
		dayOfWeekStart	: 1,
		timepicker	: false,
		inline		: true,
		format		: 'Y-m-d',
		minDate		: '0',//yesterday is minimum date(for today use 0 or -1970/01/01)
		maxDate		: php_vars.max_date,//tomorrow is maximum date calendar
		scrollMonth : false,
		scrollInput : false,
		yearStart 	: 2016
		
		//disabledDates: [],  		
		
	})
	
	
	function mostrar_cupos( cupos , dia_seleccionado ) {
		var et_html;
		var cupos;
		var ocupados;
		var disponible;
		var tiene_reserva;
		var close_time 		= php_vars.close_time * 1000;
		var today 			= new Date();
		var hora_actual 	= today.getTime();
		var hora_servicio 	= new Date(dia_seleccionado);
		
		hora_servicio = hora_servicio.getTime() - close_time ;
					
		// si viene vacio o nulo regreso que NO HAY CUPOS
		if ( cupos == 0 || cupos == null )
			return et_html = "<h2 class='sin_cupos'>No hay cupos disponibles para el "+ dia_seleccionado +"</h2>";
			
		et_html = 	"<h2>Cupos disponibles para " + dia_seleccionado + "</h2>";
			
		$.each(cupos.bloque , function( key, obj ) {
        	tiene_reserva = false;
        	disponible = obj.et_meta_cupos;
        	
        	$.each(cupos.ocupado , function ( hora , veces_repetido ) {
	        	
	        	if ( hora == obj.et_meta_hora_inicio ) {
		        	disponible = disponible - veces_repetido;
	        	}
	        	
        	});
        	
        	$.each(cupos.reservado , function ( hora , reservado ) {
	        	
	        	if ( hora == obj.et_meta_hora_inicio && reservado == true ) {
		        	tiene_reserva = true;
	        	}
	        	
        	});
        	
        	et_html += 	"<div class='cupoDisponible' id='cupoDisponible_"+ key +"'>";
        	
        	et_html += 		"<form class='formulario_reserva_cupo' id='hacer_reserva_"+ key +"' action='"+ php_vars.url_user +"citas/' method='post' >";
        	
        	et_html +=			"<header>"+ obj.et_meta_hora_inicio +"</header>";
			et_html +=	       	"<p>Duracion: " + obj.et_meta_duracion +" minutos</p>";
			et_html +=	   		"<p>Cupos Maximo: " + obj.et_meta_cupos +"</p>";
			et_html +=	   		"<p>Cupos Disponibles: "+ disponible +"</p> ";
        	
        	
			et_html +=		    "<input type='hidden' value='"+ php_vars.id_servicio +"' id='id_servicio_"+ php_vars.id_servicio +"' name='id_servicio'>";
			et_html +=		   	"<input type='hidden' value='"+ dia_seleccionado +"' id='servicio_dia_seleccionado' class='servicio_dia_seleccionado' name='servicio_dia_seleccionado'>";
			et_html +=		   	"<input type='hidden' value='"+ obj.et_meta_hora_inicio +"' id='et_meta_hora_inicio' name='et_meta_hora_inicio'>";
			et_html +=		   	"<input type='hidden' value='' id='et_meta_close_time' name='et_meta_close_time'> ";
        	
        	if (tiene_reserva) {
	        	et_html +=		"<input disabled type='submit' value='Ya reservaste' class='button btn-morado servicio_reservado' id='boton_reservar' name='agotado'>"	;
        	}
        	
        	else if (disponible < 1 ) {
				et_html +=		"<input disabled type='submit' value='Cupos agotados' class='button btn-morado servicio_agotado' id='boton_reservar' name='agotado'>";
			}
			
			else if ( hora_actual > hora_servicio ) {
				et_html +=		"<input disabled type='submit' value='Clase cerrada' class='button btn-morado servicio_cerrado' id='boton_reservar' name='agotado'>";
			}
			
			else {
	        	et_html +=		"<input type='submit' value='reservar' class='button btn-morado' id='boton_reservar' name='is_reserve'>";
        	}
        	
        	et_html += 		"</form>";
        	
        	et_html += 	"</div>";
        	
		});
		
		return et_html;
		
	}
	
});