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



<!-- Viene llamado por AJAX via cargar_servicio -->
<style>
	
	.smart-forms .radio  {padding-left:0px !important;}
	.smart-forms .frm-row {
    	margin: 0px; 
	    padding: 0px 20px;
	}
	.smart-steps .wizard > .steps {z-index:1;}
	
	#map-canvas img {max-width: none;}
	
	#description_ifr {border-left:1px solid #EEE; border-right:1px solid #EEE;height:300px !important;}
	
	#contenedor_disponibilidad .frm-row {
		margin: 10px;
		padding: 10px 0px 10px 20px;
		background: #F4F4F4;
	}
	
	#contenedor_disponibilidad select {background: #FFF;}
	
	#contenedor_disponibilidad a.clone {background-color:#6925a5; color:#FFF;}
	#contenedor_disponibilidad a.delete {background-color:#aa1d1d; color:#FFF;}
	
</style>
            
<div class="smart-wrap">

    <div class="smart-forms">
        
		<div class="form-body theme-purple smart-steps steps-theme-purple">
			
            <form method="post" action="<?php the_permalink(); ?>" id="formulario_servicio" enctype="multipart/form-data">
				<!-- DATOS DEL SERVICIO -->
                <h2>Servicio</h2>
                <fieldset>
                	
                    <div class="frm-row">

                        <div class="section colm colm8">
                            <label for="nombre_servicio" class="field-label">Nombre del servicio <em> * </em> </label>
                            <label class="field prepend-icon">
                                <input type="text" name="nombre_servicio" id="nombre_servicio" class="gui-input required" placeholder="Nombre del servicio" aria-required="true" maxlength="65" value="<?php echo isset($this->servicio->post_title) ? $this->servicio->post_title : '' ?>">
                                <span class="field-icon"><i class="fa fa-pencil-square-o"></i></span>  
                                <span class="small-text block spacer-t10 fine-grey"> El nombre no debe tener más de 50 caracteres </span>
                            </label>
                        </div><!-- end section -->
                        
                        
                        
                        <div class="section colm colm4">
                            <label for="tipo" class="field-label">Tipo de servicio <em> * </em> </label>
                            <label class="field prepend-icon">
                                <input type="text" name="et_meta_tipo" id="tipo" class="gui-input" placeholder="<?php echo $this->tipo_de_servicio ?>" value="<?php echo $this->tipo_de_servicio ?>" readonly>
                                <span class="field-icon"><i class="fa fa-check-square-o"></i></span>  
                            </label>
                        </div><!-- end section -->
                    </div><!-- end .frm-row section -->
                    
					<div class="spacer-t20 spacer-b30">
						<div class="tagline"><span> Costos del servicio </span></div><!-- .tagline -->
					</div> 
                    
                    <div class="frm-row">
                        <div class="section colm colm4 ">
                        	<label for="et_meta_precio_moneda" class="field-label">Tipo de moneda <em> * </em> </label>
                            
                            <label class="field select">
                                
                                <?php $moneda = isset($this->servicio_meta['et_meta_precio_moneda'][0]) ? $this->servicio_meta['et_meta_precio_moneda'][0] : '' ?>

								<select name="et_meta_precio_moneda" id="et_meta_precio_moneda">
						            <option value="VEF" <?php selected( $moneda, "VEF" ); ?>>Bolivares</option>
						            <option value="USD" <?php selected( $moneda, "USD" ); ?>>Dolares Americanos</option>
						            <option value="EU" <?php selected( $moneda, "EU" ); ?>>Euros</option>
						        </select>
                                <i class="arrow double"></i>                    
                            </label>  
                        </div>
                        
                        <!-- ****************** -->
                    	<!-- PRECIO SERVICIO -->
                    	<!-- ****************** -->  
                    	<div class="section colm colm4">
                        	<label for="et_meta_precio" class="field-label">Precio <em> * </em> </label>
                            <label class="field">
                                <label class="field">
		                        	<input type="text" class="gui-input required auto" name="et_meta_precio" id="et_meta_precio" aria-required="true" placeholder="Precio del evento..." data-v-max="9999999" data-v-min="0" value="<?php echo isset($this->servicio_meta['et_meta_precio'][0]) ? $this->servicio_meta['et_meta_precio'][0] : '' ?>">
		                        </label>
                                <b class="tooltip tip-left-bottom"><em> Indique el precio de su servicio.</em></b>
                            </label>
                        </div>
                        <!-- ****************** -->	
                        
                        <!-- ****************** -->
                    	<!-- PRECIO VISIBILIDAD -->
                    	<!-- ****************** -->  
                    	<div class="section colm colm4 ">
                        	<span class="kleo-pin-circle hover-pop animated animate-when-almost-visible el-appear start-animation" data-toggle="popover" data-container="body" data-title="Tipo de precio" data-content="Determina cómo los usuarios verán los precios de su servicios.  <ul><li><strong>Público (recomendado):</strong> Permite a todos los usuarios ver su precio</li><li><strong>Privado:</strong> Los usuarios no podrán ver el precio de su servicio.</li><li><strong>Oculto:</strong> Muestra el precio unicamente si un usuario solicita verlo.</li></ul>" data-placement="left" data-top="-5px" data-left="-15px" style="top: -50px; left: -15px;" data-original-title="" title=""><span></span></span>
                        	
                        	<label for="et_meta_precio_visibilidad" class="field-label">El precio será <em> * </em> </label>
                            
                            <label class="field select">
                                <?php $moneda_visibilidad = isset($this->servicio_meta['et_meta_precio_visibilidad'][0]) ? $this->servicio_meta['et_meta_precio_visibilidad'][0] : '' ?>
							
								<select name="et_meta_precio_visibilidad" id="et_meta_precio_visibilidad">
						            <option value="public" <?php selected( $moneda_visibilidad, "public" ); ?>>Público</option>
						            <option value="private" <?php selected( $moneda_visibilidad, "private" ); ?>>Privado</option>
						            <option value="hidden" <?php selected( $moneda_visibilidad, "hidden" ); ?>>Oculto</option>
						        </select>
                                <i class="arrow double"></i>                    
                            </label>  
                        </div><!-- end section -->
                        <!-- ****************** -->
                    </div>
                                                                                  
                </fieldset>
				<!-- DATOS DEL SERVICIO -->
						
						
				<!-- DATOS DEL SERVICIO -->
                <h2>Detalles</h2>
                <fieldset>
                
                    <div class="frm-row">
						<!-- ****************** -->
	                   	<!-- FILA 3 - CATEGORIA -->
	                   	<!-- ****************** -->	
	                    <div class="frm-row">
	  
			                <div class="spacer-t20 spacer-b30">
		                    	<div class="tagline"><span> Categoría del servicio </span></div><!-- .tagline -->
							</div>
	                    
			                <div class="section">
		                    	<p class="small-text fine-grey">A que categoria pertecene su servicio</p>
		                    </div><!-- end section -->
		                    
		                    <label class="field select">
                                <?php 
	                                $args = array(
										'show_option_all'    => '',
										'show_option_none'   => '',
										'option_none_value'  => '-1',
										'orderby'            => 'name', 
										'order'              => 'ASC',
										'show_count'         => 0,
										'hide_empty'         => 0, 
										'child_of'           => 0,
										'exclude'            => '',
										'echo'               => 1,
										'selected'           => isset($this->servicios_categoria[0]->slug) ? $this->servicios_categoria[0]->slug : '0' ,
										'hierarchical'       => 1, 
										'name'               => 'cat_servicio',
										'id'                 => '',
										'class'              => 'categorias',
										'depth'              => 0,
										'tab_index'          => 1,
										'taxonomy'           => 'servicios-categoria',
										'hide_if_empty'      => false,
										'value_field'	     => 'slug'
										); 
	                                wp_dropdown_categories( $args );
	                                
                                ?>
                                <i class="arrow double"></i>                    
                            </label> 
		                    
	                    </div>	
	                    <!-- ****************** -->	
	                    
	                    <div class="spacer-t20 spacer-b30">
	                    	<div class="tagline"><span> Imagen destacada del servicio </span></div><!-- .tagline -->
	                    </div> 
	                    
	                    <!-- ****************** -->
	                   	<!-- FILA 4 - IMAGEN -->
	                   	<!-- ****************** -->	
	                    <div class="frm-row">
		                   <div class="section">
	                            
	                            <?php 
								if ( has_post_thumbnail ($this->servicio->ID) ): 
									echo ( get_the_post_thumbnail( $this->servicio->ID, 'thumbnail') );
		                             
								else: ?>
		                            <label class="field prepend-icon file">
		                                <span class="button btn-primary"> Elegir portada </span>
		                                <input type="file" class="gui-file" name="imagen_destacada" id="imagen_destacada" onChange="document.getElementById('orderupload').value = this.value;">
		                                <input type="text" class="gui-input" id="orderupload" placeholder="Portada del Servicio" readonly>
		                                <span class="field-icon"><i class="fa fa-upload"></i></span>
		                            </label>
		                            <span class="small-text block spacer-t10 fine-grey"> Solo se permiten imágenes tipo JPG y PNG - Máximo de 1MB </span> 

								<?php
								endif;
								?>
								
	                        </div><!-- end  section -->	

	                    </div>
	                    
	                    <div class="spacer-t20 spacer-b30">
	                    	<div class="tagline"><span> Descripci&oacute;n del servicio </span></div><!-- .tagline -->
	                    </div> 
			            
			            
						<!-- ****************** -->
						<!-- FILA 5 - DESCRIPCION -->
						<!-- ****************** -->	        
		            	<div class="section">
		                	<em for="description" id="error_descripcion" class="" style="display:none;">Por favor selecciona la categoría a la que pertenece tu servicio</em>
		                	<label for="description" class="field-label">Descripci&oacute;n <em> * </em> </label>
		                	<?php $content = isset($this->servicio->post_content) ? $this->servicio->post_content : '' ;
		                	
							$editor_id = 'description';
							$editro_settings = array (
								'media_buttons' => false,
								'quicktags'		=> false,
								'wpautop'		=> false
							);
							
							wp_editor( $content, $editor_id, $editro_settings );
	                    	?>
		                </div><!-- end section -->  
	                    <!-- ****************** -->	
	                    
	                    
                    </div>

				</fieldset>
                        
                        
	            <h2>Publicar</h2>
	            <fieldset>
	                <div class="section">
	                	<div class="notification alert-info">
	                    	<p>Por favor revise toda su información antes de publicar</p>
	                    </div>
	                    
	                    
	                </div><!-- end section -->
	                
	                <div class="section">
	                
	                    <div class="section">
	                        <div class="option-group field">
	                            <label class="option option-black">
	                                <input type="checkbox" name="generalTerms" value="General Terms" aria-required="true" required>
	                                <span class="checkbox"></span> 
	                                Por favor <a href="#" class="smart-link"> lea y acepte </a> nuestros términos y condiciones                
	                            </label>
	                        </div>
	                    </div><!-- end section -->                                 
	                	
	                </div><!-- end section -->
	                
	                
	                <?php echo $this->en_edicion ? "<input type='hidden' name='servicio_status' value='et_updating'>" : "" ?>
	                <?php echo $this->en_edicion ? "<input type='hidden' name='post_id' value='$this->post_id'>" : "" ?>
					<?php wp_nonce_field( 'publicar_servicio', 'publicar_servicio_nonce' ); ?>
					
					<div class="result"></div>
					
	            </fieldset>
            
			</form>                                                                                   
    
        </div><!-- end .form-body section -->
            
    </div><!-- end .smart-forms section -->
    
</div><!-- end .smart-wrap section -->