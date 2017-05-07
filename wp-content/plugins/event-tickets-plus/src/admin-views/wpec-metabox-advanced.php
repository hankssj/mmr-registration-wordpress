<?php include dirname( __FILE__ ) . '/price-fields.php'; ?>
<tr class="<?php $this->tr_class(); ?>">
	<td><label for="ticket_wpec_stock"><?php esc_html_e( 'Stock:', 'event-tickets-plus' ); ?></label></td>
	<td>
		<input type='text' id='ticket_wpec_stock' name='ticket_wpec_stock' class="ticket_field" size='7'
		       value='<?php echo esc_attr( $stock ); ?>'/>
		<p class="description"><?php esc_html_e( "(Total available # of this ticket type. Once they're gone, ticket type is sold out.)", 'event-tickets-plus' ); ?></p>
	</td>
</tr>

<tr class="<?php $this->tr_class(); ?>">
	<td><label for="ticket_wpec_sku"><?php esc_html_e( 'SKU:', 'event-tickets-plus' ); ?></label></td>
	<td>
		<input type='text' id='ticket_wpec_sku' name='ticket_wpec_sku' class="ticket_field" size='7'
		       value='<?php echo esc_attr( $sku ); ?>'/>
		<p class="description"><?php esc_html_e( "(A unique identifying code for each ticket type you're selling)", 'event-tickets-plus' ); ?></p>
	</td>

</tr>
<?php

if ( class_exists( 'Tribe__Events__Pro__Main' ) ) {
	if ( is_admin() ) {
		$bumpdown = __( 'Currently, WPECTickets will only show up on the frontend once per full event. For PRO users this means the same ticket will appear across all events in the series. Please configure your events accordingly.', 'event-tickets-plus' );
	} else {
		$bumpdown = __( 'If you are creating a recurring event, Tickets will only show up once per Event Series, meaning that the same ticket will apper across all events. Please configure your events accordingly.', 'event-tickets-plus' );
	}
	?>
	<tr class="<?php $this->tr_class(); ?>">
		<td colspan="2" class="tribe_sectionheader updated">
			<p>
				<?php esc_html_e( 'Selling tickets for recurring events', 'event-tickets-plus' ); ?>
				<span class="tribe-bumpdown-trigger dashicons dashicons-editor-help"
				      data-bumpdown="<?php echo esc_attr( $bumpdown ); ?>"
				      data-bumpdown-class="<?php echo esc_attr( $this->tr_class() ); ?>"></span>
			</p>
		</td>
	</tr>
	<?php
}
