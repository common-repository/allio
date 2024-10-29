<?php
if ( ! empty( $title ) ) {
	echo "$before_title" . esc_html( $title ) . "$after_title";
}
?>

<div id="allioc-container-<?php echo $sequence; ?>" class="allioc-container">

	<?php do_action( 'allioc_widget_before_conversation_area' ); ?>

	<div class="allioc-conversation-area"></div>
	<div id="allioc-input-area">
		<input class="allioc-text" type="text" placeholder="<?php echo $input_text; ?>"></input>
	</div>

</div>

<?php if ( $debug ) { ?>
	<div class="allioc-debug">
		<textarea id="allioc-debug-data" cols="80" rows="20" disabled></textarea>
	</div>
<?php } ?>