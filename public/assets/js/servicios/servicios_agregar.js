jQuery(function( $ ) {	
    /* ************************************ */
    /* ACTIVOS LOS STEPS DEL FORMULARIO 	*/
    /* ************************************ */	    		    
    jQuery("#formulario_servicio").steps({
		bodyTag: "fieldset",
		headerTag: "h2",
		bodyTag: "fieldset",
		transitionEffect: "slideLeft",
		stepsOrientation: "vertical",
		titleTemplate: "<span class='number'>#index#</span> #title#",
		labels: {
			finish: "Enviar",
			next: "Siguiente",
			previous: "Regresar",
			loading: "Cargado..." 
		},
		onStepChanging: function (event, currentIndex, newIndex){
			if (currentIndex > newIndex){return true; }
			var form = jQuery(this);
			if (currentIndex < newIndex){}
			return form.valid();
		},
		onStepChanged: function (event, currentIndex, priorIndex){
		},
		onFinishing: function (event, currentIndex){
			var form = $(this);
			form.validate().settings.ignore = ":disabled";
			return form.valid();
		},
		onFinished: function (event, currentIndex){
			var form = jQuery(this);
			form.submit();
		}

	})
	/* ************************************ */
	
	/* ************************************ */
    /* HORA INICIO Y FIN PARA EVENTOS	 	*/
    /* ************************************ */	
	jQuery( "#fecha_inicio" ).datepicker({
		defaultDate: "+1w",
		changeMonth: false,
		numberOfMonths: 1,
		minDate: 0,
		dateFormat: 'yy-mm-dd',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',
		
		onClose: function( selectedDate ) {
			jQuery( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	
	jQuery( "#fecha_fin" ).datepicker({
		defaultDate: "+1w",
		changeMonth: false,
		numberOfMonths: 1,
		minDate: 0,
		dateFormat: 'yy-mm-dd',
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',			
		
		onClose: function( selectedDate ) {
			jQuery( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
	
	jQuery('#hora_inicio').timepicker({
		timeFormat: 'HH:mm:ss',
		stepMinute: 15,
		timeOnlyTitle: 'Selecciona la hora',
		timeText: 'Hora Final',
		hourText: 'Hora',
		minuteText: 'Minutos',
		closeText: 'Seleccionar',
		showButtonPanel: 0,
		showSecond: false,
		
		beforeShow: function(input, inst) {
				var newclass = 'smart-forms'; 
				var smartpikr = inst.dpDiv.parent();
				if (!smartpikr.hasClass('smart-forms')){
					inst.dpDiv.wrap('<div class="'+newclass+'"></div>');
				}
		}					
	
	});
	
	jQuery('#hora_fin').timepicker({
		timeFormat: 'HH:mm:ss',
		stepMinute: 15,
		timeOnlyTitle: 'Selecciona la hora',
		timeText: 'Hora Final',
		hourText: 'Hora',
		minuteText: 'Minutos',
		closeText: 'Seleccionar',
		showButtonPanel: 0,
		showSecond: false,
		
		beforeShow: function(input, inst) {
				var newclass = 'smart-forms'; 
				var smartpikr = inst.dpDiv.parent();
				if (!smartpikr.hasClass('smart-forms')){
					inst.dpDiv.wrap('<div class="'+newclass+'"></div>');
				}
		}					
	
	});
/*
	jQuery("#fecha_inicio").datepicker({
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',
		dateFormat: 'yy-mm-dd',
		timeFormat: 'HH:mm:ss',
		stepMinute: 15,
		timeOnlyTitle: 'Selecciona la hora',
		timeText: 'Hora Final',
		hourText: 'Hora',
		minuteText: 'Minutos',
		closeText: 'Seleccionar',
		showButtonPanel: 0,
		alwaysSetTime: 0,
		
		showSecond: false,
		minDate: 0,				
		
		beforeShow: function(input, inst) {
				inst.dpDiv.unwrap().unwrap(smartpickerWrapper);
				var smartpikr = inst.dpDiv.parent();
				if (!smartpikr.hasClass('smart-forms')){
					inst.dpDiv.wrap(smartpickerWrapper);
				}
		},
		
		onClose: function( input, inst ) {
			
			jQuery( "#fecha_fin" ).datetimepicker( "option", "minDate", smartpickerWrapper );
		}
		
	});
	
	jQuery("#fecha_fin").datepicker({
		prevText: '<i class="fa fa-chevron-left"></i>',
		nextText: '<i class="fa fa-chevron-right"></i>',
		dateFormat: 'yy-mm-dd',
		timeFormat: 'HH:mm:ss',
		stepMinute: 15,
		timeOnlyTitle: 'Selecciona la hora',
		timeText: 'Hora Final',
		hourText: 'Hora',
		minuteText: 'Minutos',
		closeText: 'Seleccionar',
		showSecond: false,
		minDate: 0,
		showButtonPanel: 0,
						
		beforeShow: function(input, inst) {
				inst.dpDiv.unwrap().unwrap(smartpickerWrapper);
				var smartpikr = inst.dpDiv.parent();
				if (!smartpikr.hasClass('smart-forms')){
					inst.dpDiv.wrap(smartpickerWrapper);
				}
		},
		
		onClose: function(input, inst) {
			console.log ("El input es " + input + " ; el Inst es " + inst);
			jQuery( "#fecha_inicio" ).datetimepicker( "option", "maxDate", smartpickerWrapper );
		}
		
	});
*/

	/* ************************************ */
	
	/* *************************************** */
	/* ACOMODA EL MAPA AL CAMBIAR LA DIRECCION */
	/* *************************************** */
	jQuery('#et_meta_direccion , #et_meta_ciudad, #et_meta_zipcode , #et_meta_pais , #et_meta_estado').change(function() {
		
		codeAddress();
	    	
	});
	/* ************************************ */
	
	/* ************************************ */
	/* AGREGO LA LISTA DE PAISES Y ESTADOS 	*/
	/* ************************************ */
    
    
    
    /* ************************************ */
	
	/* ************************************ */
	/* REGULO EL TIPO DE NUMERO			 	*/
	/* ************************************ */
	// jQuery('.auto').autoNumeric('init');	
	/* ************************************ */

	jQuery('.ShowHide').formShowHide({
		
	}); 
	
	jQuery('.ShowHideReset').formShowHide({
		// resetClass: 'smartform-reset'
	}); 
	
	
	/* ************************************ */
	/* MUESTRO EL SELECTOR DE HORA		 	*/
	/* ************************************ */
	jQuery('body').on('focus' , "input.timepicker" , function(){
    	jQuery(this).timepicker({
	    	timeFormat: 'HH:mm:ss',
			stepMinute: 15,
			timeOnlyTitle: 'Selecciona la hora',
			timeText: 'Hora Final',
			hourText: 'Hora',
			minuteText: 'Minutos',
			closeText: 'Seleccionar',
			
			showSecond: false,
			minDate: new Date(2050, 1, 1),
	
			beforeShow: function(input, inst) {
				var newclass = 'smart-forms'; 
				var smartpikr = inst.dpDiv.parent();
				if (!smartpikr.hasClass('smart-forms')){
					inst.dpDiv.wrap('<div class="'+newclass+'"></div>');
				}
			}
	    });
	})

	/* ************************************ */
		
	/* ************************************ */
	/* CLONACION DE HORARIOS			 	*/
	/* ************************************ */
	jQuery("body").on( "click", "a.delete"  , function( event ) {
		event.preventDefault();
		
		jQuery(this).parents(".frm-row").remove();
	});
	
	
	jQuery("body").on( "click", "a.clone"  , function( event ) {
		event.preventDefault();
		 
		var id_parent;
		var dia;
		var dia_id
		var cantidad_bloques;
		var clone;
		var regex;
		
		id_parent 	= jQuery(this).closest(".contenedor_dia").attr("id") ;
		dia 		= id_parent.split("_");
		dia			= dia[1];
		
		switch(dia) {
		    case "lunes":
		        dia_id = 0;
		        break;
		    
		    case "martes":
		        dia_id = 1;
		        break;
			
			case "miercoles":
		        dia_id = 2;
		        break;
			
			case "jueves":
		        dia_id = 3;
		        break;
			
			case "viernes":
				dia_id = 4;
		        break;
		    
		    case "sabado":
				dia_id = 5;
		        break;    
		        
		    case "domingo":
				dia_id = 6;
		        break;
		}
		
		
		cantidad_bloques = jQuery("#" + id_parent + " .clone_" + dia).length;
		
		ultimo_bloque = cantidad_bloques - 1;
		id_ultimo_bloque = "#" + dia + "_" + dia_id + "_" + ultimo_bloque;
		
		regex = /(_)(\d+)(_)(\d+)/gi;
				
		jQuery( id_ultimo_bloque ).clone()
        .appendTo( "#" + id_parent )
        .attr("id", dia + "_" + dia_id + "_" +  cantidad_bloques)
		.find("input , select")
		.each(function() {
			
			var id = this.id ;
			
			if ( id.match(/^duracion/) ) {
				
				jQuery(this).attr({
					"id" 	: "duracion_" + dia + "_" + dia_id + "_" +  cantidad_bloques,
					"name"	: "disponible["+ dia +"][bloque]["+ cantidad_bloques +"][et_meta_duracion]"
				});
			
			} else if ( id.match(/^resultadoHora/) ) {
				
				jQuery(this)
				.removeClass("hasDatepicker")
				.attr({
					"id" 	: "resultadoHora_" + dia + "_" + dia_id + "_" +  cantidad_bloques,
					"name"	: "disponible["+ dia +"][bloque]["+ cantidad_bloques +"][et_meta_hora_inicio]"
				});
				
				
			
			} else if ( id.match(/^cupos/) ) {
			
				jQuery(this).attr({
					"id" 	: "cupos_" + dia + "_" + dia_id + "_" +  cantidad_bloques,
					"name"	: "disponible["+ dia +"][bloque]["+ cantidad_bloques +"][et_meta_cupos]"
				});
			}
		});
	})
		
});

/* Initialize other plugins after steps here */	