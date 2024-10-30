<?php

class IAPoster_Lib_PostHelper {

	public static function get_post_attributes( $post_id ) {
		$post = get_post( $post_id );
		$author_id = $post->post_author;
		return array(
			'post_author'  => get_the_author_meta('user_nicename', $author_id),
			'post_excerpt' => get_the_excerpt( $post ),
			'post_tags'    => wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'slugs' ) ),
			'post_title'   => get_the_title( $post )
		);
	}
}