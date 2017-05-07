<?php
/**
 * Calculates attendance totals for a specified event (ie, how many tickets
 * have been sold and how many are pending further action, etc).
 *
 * Also has the capability to print this information as HTML, intended for
 * use in the attendee summary screen.
 *
 * Note that the totals are calculated upon instantiation, effectively making
 * the object a snapshot in time. Therefore if the status of one or more tickets
 * is modified or if tickets are added/deleted later in the request, it would be
 * necessary to obtain a new object of this type to get accurate results.
 */
class Tribe__Tickets_Plus__Commerce__Attendance_Totals extends Tribe__Tickets__Abstract_Attendance_Totals {
	protected $total_sold = 0;
	protected $total_complete = 0;
	protected $total_pending = 0;

	/**
	 * Calculate total RSVP attendance for the current event.
	 */
	protected function calculate_totals() {
		foreach ( Tribe__Tickets__Tickets::get_event_tickets( $this->event_id ) as $ticket ) {
			$this->total_sold += $ticket->qty_sold();
			$this->total_pending += $ticket->qty_pending();
		}

		$this->total_complete = $this->total_sold - $this->total_pending;
	}

	/**
	 * Prints an HTML (unordered) list of attendance totals.
	 */
	public function print_totals() {
		$total_sold_label = esc_html_x( 'Total Tickets Sold:', 'attendee summary', 'event-tickets-plus' );
		$total_paid_label = esc_html_x( 'Paid/Complete:', 'attendee summary', 'event-tickets-plus' );
		$total_pending_label = esc_html_x( 'Pending Action:', 'attendee summary', 'event-tickets-plus' );

		$total_sold = $this->get_total_sold();
		$total_paid = $this->get_total_complete();
		$total_pending = $this->get_total_pending();

		echo "
			<ul>
				<li> <strong>$total_sold_label</strong> $total_sold </li>
				<li> <strong>$total_paid_label</strong> $total_paid </li>
				<li> <strong>$total_pending_label</strong> $total_pending </li>
			</ul>
		";
	}

	/**
	 * The total number of tickets sold for this event.
	 *
	 * @return int
	 */
	public function get_total_sold() {
		/**
		 * Returns the total tickets sold for an event.
		 *
		 * @param int $total_sold
		 * @param int $original_total_sold
		 * @param int $event_id
		 */
		return (int) apply_filters( 'tribe_tickets_plus_get_total_sold', $this->total_sold, $this->total_sold, $this->event_id );
	}

	/**
	 * The total number of tickets pending further action for this event.
	 *
	 * @return int
	 */
	public function get_total_pending() {
		/**
		 * Returns the total tickets pending further action for an event.
		 *
		 * @param int $total_pending
		 * @param int $original_total_pending
		 * @param int $event_id
		 */
		return (int) apply_filters( 'tribe_tickets_plus_get_total_pending', $this->total_pending, $this->total_pending, $this->event_id );
	}

	/**
	 * The total number of tickets sold and paid for, for this event.
	 *
	 * @return int
	 */
	public function get_total_complete() {
		/**
		 * Returns the total tickets sold and paid for, for an event.
		 *
		 * @param int $total_complete
		 * @param int $original_total_complete
		 * @param int $event_id
		 */
		return (int) apply_filters( 'tribe_tickets_plus_get_total_paid', $this->total_complete, $this->total_complete, $this->event_id );
	}
}