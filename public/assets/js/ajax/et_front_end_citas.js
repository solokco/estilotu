jQuery(document).ready( function($) {
		
	/* **************************** */
	/* ASISTENCIA DEL USUARIO */	
	/* **************************** */
	$(".appoinment_user_assist").change(function() {
	    	    
	    /* CONSIGO EL ID DEL FORMULARIO - CITA */
	    var id_cita = this.id;
	    
	    
	    //jQuery('body').prepend("<div class='ajax_procesando'></div>");

	    /* DESHABILITO OPCIONES HASTA CARGAR EL AJAX */
	    $( '.formulario_usuario_opciones .boton_'+id_cita ).attr( 'disabled' , true );		
	    
	    /* VALIDO SI ES CHECK O NO */
	    if ( this.checked ) {
		  	
		  	appoinment_user_assist = 1;      	    
    	    
    	} else {
	    	
	    	appoinment_user_assist = 0;  
	    	
    	}
	    
	    jQuery("body").addClass("loading");
	    		
		jQuery.ajax({
	        url: ajax_object.ajax_url,
	        type: "POST",
	        //dataType: "JSON",
	        data: { 
				'action': 'et_asistencia_y_pago_participante',
				'id_cita': id_cita,
				'appoinment_user_assist' : appoinment_user_assist
            },      
	         
	        success: function( data, textStatus, jqXHR ) { // Si todo salio bien se ejecuta esto
				
				console.log(data);
				jQuery( '.formulario_usuario_opciones .boton_'+id_cita ).attr( 'disabled' , false );	
				jQuery("body").removeClass("loading");
			
			}
        })
        
        .fail(function( jqXHR, textStatus, errorThrown, data ) { // Si todo salio MAL se ejecuta esto
			alert('Ocurrio un error y no se pudo procesar su solicitud correctamente.');

        });
		
		
		return false;

	});
	/* **************************** */
	
	/* **************************** */
	/* PAGO DEL USUARIO */	
	/* **************************** */
	$(".appoinment_user_pay").change(function() {
	    
	       
	    /* CONSIGO EL ID DEL FORMULARIO - CITA */
	    var id_cita = this.id;
	    
	    //jQuery('body').prepend("<div class='ajax_procesando'></div>");
	    
	    /* DESHABILITO OPCIONES HASTA CARGAR EL AJAX */
	    $( '.formulario_usuario_opciones .boton_'+id_cita ).attr( 'disabled' , true );		
	    
	    /* VALIDO SI ES CHECK O NO */
	    if ( this.checked ) {
		  	
		  	appoinment_user_paid = 1;      	    
    	    
    	} else {
	    	
	    	appoinment_user_paid = 0;  
	    	
    	}
	    
	    var data = {
			'action': 'et_asistencia_y_pago_participante',
			'id_cita': id_cita,
			'security': ajax_objetct.security,
			'appoinment_user_paid' : appoinment_user_paid
		};
	    
		/* cargo el ajax */
	    $.post( ajax_objetct.ajaxurl , data , function (response) {
			
			//$(".ajax_procesando").remove();						
			$( '.formulario_usuario_opciones .boton_'+id_cita ).attr( 'disabled' , false );		
				
		});
		
		return false;

	});
	/* **************************** */
		
});