<?php
/**
 * Event trait.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Event trait.
 */
trait Event {

	/**
	 * Set meta for the event.
	 */
	public function set_event_meta() : self {
		$this->set_config( 'url', (string) get_post_meta( $this->get_post_id(), '_EventURL', true ) );
		$this->set_config(
			'address',
			trim(
				implode(
					', ',
					array_filter(
						[
							tribe_get_venue( $this->get_post_id() ),
							tribe_get_address( $this->get_post_id() ),
							tribe_get_city( $this->get_post_id() ),
							tribe_get_stateprovince( $this->get_post_id() ) . ' ' . tribe_get_zip( $this->get_post_id() ),
						]
					)
				)
			)
		);

		$start_date = tribe_get_start_date( $this->get_post_id(), false, 'l, F j, Y' );
		$end_date   = tribe_get_end_date( $this->get_post_id(), false, 'l, F j, Y' );

		if ( $start_date === $end_date ) {
			$this->set_config( 'date', $start_date );

			$start_time = tribe_get_start_date( $this->get_post_id(), true, 'g:i A' );
			$end_time   = tribe_get_end_date( $this->get_post_id(), true, 'g:i A' );

			if ( $start_time === $end_time ) {
				return $this->set_config( 'time', $start_time );
			}

			$time = sprintf(
				/* translators: 1. start time of event 2. end time of event */
				esc_html__( '%1$s to %2$s', 'cpr' ),
				tribe_get_start_date( $this->get_post_id(), true, 'g:i A' ),
				tribe_get_end_date( $this->get_post_id(), true, 'g:i A' )
			);
			return $this->set_config( 'time', $time );
		}

		$date_time = sprintf(
			/* translators: 1. start date/time of event 2. end date/time of event */
			esc_html__( '%1$s to %2$s', 'cpr' ),
			tribe_get_start_date( $this->get_post_id(), true, 'l, F j, Y g:i A' ),
			tribe_get_end_date( $this->get_post_id(), true, 'l, F j, Y g:i A' )
		);
		return $this->set_config( 'date_time', $date_time );
	}
}
