<?php

/**
* Remove certain tabs from user profile based on member role.
 */
function buddydev_remove_tabs_based_on_member_roles() {
	/*if ( ! bp_is_user() ) {
		return;
	}*/

	$member_actor = bp_current_user_can( 'customer' );
	$member_casting_director = bp_current_user_can( 'customer' );
	$administrator = bp_current_user_can('administrator');

	$member_user = ($member_actor ? : $member_casting_director ? : $administrator);

	if ( ! $member_user ) {
		//bp_core_remove_nav_item( 'friends' ); //removes the tab friends
		bp_core_remove_nav_item( 'messages' ); //removes the tab messages
		//bp_core_remove_nav_item( 'reviews' ); //removes the tab reviews
	}

}

add_action( 'bp_setup_nav', 'buddydev_remove_tabs_based_on_member_roles', 1001 );
