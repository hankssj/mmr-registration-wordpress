<?php

class Tribe__Tickets_Plus__Meta__Render {
	public function __construct() {
		add_action( 'tribe_tickets_ticket_email_ticket_bottom', array( $this, 'ticket_email_meta' ) );
		add_action( 'event_tickets_attendees_table_after_row', array( $this, 'table_meta_data' ) );
		add_filter( 'event_tickets_attendees_table_row_actions', array( $this, 'add_meta_toggle_to_ticket_column' ), 10, 2 );
	}

	public function add_meta_toggle_to_ticket_column( array $row_actions, array $item ) {
		$meta_data = get_post_meta( $item['attendee_id'], Tribe__Tickets_Plus__Meta::META_KEY, true );

		if ( ! $meta_data ) {
			return $row_actions;
		}

		$row_actions[] = '
			<a href="" class="event-tickets-meta-toggle">
				<span class="event-tickets-meta-toggle-view">' . esc_html( 'View details', 'event-tickets-plus' ) . '</span>
				<span class="event-tickets-meta-toggle-hide">' . esc_html( 'Hide details', 'event-tickets-plus' ) . '</span>
			</a>
		';

		 return $row_actions;
	}

	public function table_meta_data( $item ) {
		if ( ! isset( $item['product_id'] ) || ! isset( $item['attendee_id'] ) ) {
			return;
		}

		wp_enqueue_style( 'event-tickets-meta' );
		wp_enqueue_script( 'event-tickets-meta-report' );

		$meta_fields = Tribe__Tickets_Plus__Main::instance()->meta()->get_meta_fields_by_ticket( $item['product_id'] );
		$meta_data = get_post_meta( $item['attendee_id'], Tribe__Tickets_Plus__Meta::META_KEY, true );
		$orphaned_data = (array) $meta_data;

		$valid_meta_html = '';
		$orphaned_meta_html = '';

		foreach ( $meta_fields as $field ) {
			if ( 'checkbox' === $field->type && isset( $field->extra['options'] ) ) {
				$values = array();
				foreach ( $field->extra['options'] as $option ) {
					$key = $field->slug . '_' . sanitize_title( $option );

					if ( isset( $meta_data[ $key ] ) ) {
						$values[] = $meta_data[ $key ];
						unset( $orphaned_data[ $key ] );
					}
				}

				$value = implode( ', ', $values );
			} elseif ( isset( $meta_data[ $field->slug ] ) ) {
				$value = $meta_data[ $field->slug ];
				unset( $orphaned_data[ $field->slug ] );
			} else {
				continue;
			}

			if ( '' === trim( $value ) ) {
				$value = '&nbsp;';
			}

			$value = $value ? wp_kses_post( $value ) : '&nbsp;';

			$valid_meta_html .= '
				<dt class="event-tickets-meta-label_' . esc_attr( $field->slug ) . '">' . wp_kses_post( $field->label ) . '</dt>
				<dd class="event-tickets-meta-data_' . esc_attr( $field->slug ) . '">' . $value . '</dd>
			';
		}

		if ( ! empty( $valid_meta_html ) ) {
			$valid_meta_html = '<dl>' . $valid_meta_html . '</dl>';
		}

		foreach ( $orphaned_data as $key => $value ) {
			$key = esc_html( $key );
			$value = esc_html( $value );

			$orphaned_meta_html .= "
				<dt class='event-tickets-orphaned-meta-label'> $key </dt>
				<dd class='event-tickets-orphaned-meta-data'> $value </dd>
			";
		}

		if ( ! empty( $orphaned_meta_html ) ) {
			$orphaned_meta_html = '
				<h4>' . esc_html_x( 'Other attendee data:', 'orphaned attendee meta data', 'event-tickets-plus' ) . '</h4>
				<dl>' . $orphaned_meta_html . '</dl>
			';
		}

		echo "
			<tr class='event-tickets-meta-row'>
				<th></th>
				<td colspan='6'>
					$valid_meta_html
					$orphaned_meta_html
				</td>
			</tr>
		";
	}

	/**
	 * Inject custom meta in to tickets
	 *
	 * @param array $item Attendee data
	 */
	public function ticket_email_meta( $item ) {
		if ( ! isset( $item['product_id'] ) || ! isset( $item['attendee_id'] ) ) {
			return;
		}

		$meta_fields = Tribe__Tickets_Plus__Main::instance()->meta()->get_meta_fields_by_ticket( $item['product_id'] );
		$meta_data = get_post_meta( $item['qr_ticket_id'], Tribe__Tickets_Plus__Meta::META_KEY, true );

		if ( ! $meta_fields || ! $meta_data ) {
			return;
		}

		?>
		<table class="inner-wrapper" border="0" cellpadding="0" cellspacing="0" width="620" bgcolor="#f7f7f7" style="margin:0 auto !important; width:620px; padding:0;">
			<tr>
				<td valign="top" class="ticket-content" align="left" width="580" border="0" cellpadding="20" cellspacing="0" style="padding:20px; background:#f7f7f7;" colspan="2">
					<h6 style="color:#909090 !important; margin:0 0 4px 0; font-family: 'Helvetica Neue', Helvetica, sans-serif; text-transform:uppercase; font-size:13px; font-weight:700 !important;"><?php esc_html_e( 'Attendee Information', 'event-tickets-plus' ); ?></h6>
				</td>
			</tr>
			<?php
			foreach ( $meta_fields as $field ) {
				if ( 'checkbox' === $field->type && isset( $field->extra['options'] ) ) {
					$values = array();
					foreach ( $field->extra['options'] as $option ) {
						$key = $field->slug . '_' . sanitize_title( $option );

						if ( isset( $meta_data[ $key ] ) ) {
							$values[] = $meta_data[ $key ];
						}
					}

					$value = implode( ', ', $values );
				} elseif ( isset( $meta_data[ $field->slug ] ) ) {
					$value = $meta_data[ $field->slug ];
				} else {
					continue;
				}

				?>
				<tr>
					<th valign="top" class="event-tickets-meta-label_<?php echo esc_attr( $field->slug ); ?>" align="left" border="0" cellpadding="20" cellspacing="0" style="padding:0 20px; background:#f7f7f7;min-width:100px;">
						<?php echo wp_kses_post( $field->label ); ?>
					</th>
					<td valign="top" class="event-tickets-meta-data_<?php echo esc_attr( $field->slug ); ?>" align="left" border="0" cellpadding="20" cellspacing="0" style="padding:0 20px; background:#f7f7f7;">
						<?php echo wp_kses_post( $value ); ?>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}
}
