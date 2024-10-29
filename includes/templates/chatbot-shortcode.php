<div id="allioc-container-<?php echo $sequence; ?>" class="allioc-container">

	<?php do_action( 'allioc_shortcode_before_conversation_area' ); ?>

	<div class="allioc-conversation-area"></div>


	<script type="text/javascript">
		jQuery(document).ready(function() {
			/*setTimeout(function() {
				jQuery('html').scrollTop(jQuery('.allioc-container').offset().top - 100);
			}, 500);			*/
		});		
	</script>
</div>
<div id="allioc-input-area">
	<?php

$user_agent = $_SERVER['HTTP_USER_AGENT']; 
if (stripos( $user_agent, 'Chrome') !== false)
{
?>

<?php
}

?><a class="allioc-micro-button"></a>
		<input class="allioc-text" type="text" placeholder="<?php echo $input_text; ?>"></input>
		
		
		<button class="allioc-send-button" ><svg viewBox="0 0 16 16"><path d="M1.388 15.77c-.977.518-1.572.061-1.329-1.019l1.033-4.585c.123-.543.659-1.034 1.216-1.1l6.195-.72c1.648-.19 1.654-.498 0-.687l-6.195-.708c-.55-.063-1.09-.54-1.212-1.085L.056 1.234C-.187.161.408-.289 1.387.231l12.85 6.829c.978.519.98 1.36 0 1.88l-12.85 6.83z" fill-rule="evenodd"></path></svg></button>
		
	</div>
<?php if ( $debug ) { ?>
	<div class="allioc-debug">
		<textarea id="allioc-debug-data" cols="80" rows="20" disabled></textarea>
	</div>
<?php } ?>
