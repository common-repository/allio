<?php 
		if ($user_icon == ""):
	?>
	
	<?php
		else:?>
		<div class="allioc-content-logo <?php if (isset($toggle_class) && $toggle_class== 'allioc-toggle-open') echo 'opend';?>" style="right:<?php echo $position_right;?>px;bottom:<?php echo $position_bottom;?>px">
		<?php		
			echo $user_icon;
			?>
		</div> <?php
		endif;
	?>
	
	<style type="text/css">
		#allioc-input-area input[type="text"]:active, 
        #allioc-input-area input[type="text"]:focus {	
	        background-color: #fff!important;
       }
	   </style>
	   <script src="https://js.stripe.com/v3/"></script>
	   <script id="hidden-template" type="text/x-custom-template">
	   <div class="cell example example2" id="example-2">
        <form>
         
          <div class="row">
            <div class="field">
              <div id="example2-card-number" class="input empty"></div>
              <label for="example2-card-number" data-tid="elements_examples.form.card_number_label">Card number</label>
              <div class="baseline"></div>
            </div>
          </div>
          <div class="row">
            <div class="field">
              <div id="example2-card-expiry" class="input empty"></div>
              <label for="example2-card-expiry" data-tid="elements_examples.form.card_expiry_label">Expiration</label>
              <div class="baseline"></div>
            </div>
            <div class="field">
              <div id="example2-card-cvc" class="input empty"></div>
              <label for="example2-card-cvc" data-tid="elements_examples.form.card_cvc_label">CVC</label>
              <div class="baseline"></div>
            </div>
		  </div>
		  
		  <div data-locale-reversible>           
            <div class="row" data-locale-reversible>           
              <div class="field">
                <input id="example2-zip" data-tid="elements_examples.form.postal_code_placeholder" class="input empty" type="text" placeholder="94107" required="" autocomplete="postal-code">
                <label for="example2-zip" data-tid="elements_examples.form.postal_code_label">ZIP</label>
                <div class="baseline"></div>
              </div>
            </div>
		  </div>
		  
		  <div class="error" role="alert"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
              <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
              <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
            </svg>
            <span class="message"></span></div>
        </form>
</div>
		</script>
<div class="allioc-content-overlay <?php if ( isset( $toggle_class ) ) { echo $toggle_class; } ?>">
	<div class="allioc-content-overlay-header ">
		<?php 
			if ($chat_logo == ""):
		?>
		
		<?php
			else:?>
			<div class="allioc-header-logo" >
			<?php		
				echo $chat_logo;
				?>
			</div> <?php
			endif;
		?>
		<div class="allioc-content-top">
			<h2 class="title">Hi there<span class="allioc-greeting"></span></h2>
			<p><?php if (strlen($overlay_header_text) > 0) 
				echo $overlay_header_text; 
				else 
				echo 'Thanks for visiting Allioc. How can we help?'; ?>
				 </p>
		</div>	
		

		
	</div>
	
	<div class="allioc-content-overlay-container" <?php if (isset($toggle_class) && $toggle_class!= 'allioc-toggle-open') echo 'style="display: none";';?>"><?php echo do_shortcode( '[allio_chatbot]' ); ?></div>
	<?php if ( strlen( $overlay_powered_by_text ) > 0 ) {
		?>
		<div class="allioc-content-overlay-powered-by"><?php echo $overlay_powered_by_text; ?></div>
		<?php
	} ?>
</div>
