<?php

class IAPoster_Lib_HtmlParser {

	private static $a_regex = '/(<a[^>]*>)(.*?)<\/a[^>]*>/i';

	public static function get_images_from_content( $content ) {
		$result    = array();
		$img_regex = self::get_element_regex( 'img' );

		preg_match_all( self::$a_regex, $content, $anchors, PREG_SET_ORDER );
		foreach ( $anchors as $anchor ) {
			$img_from_anchor = self::try_get_image_from_anchor( $anchor[1], $anchor[2] );
			if ( ! $img_from_anchor ) {
				continue;
			}
			for ( $i = 0, $exists = false; $i < count( $result ) && ! $exists; $i ++ ) {
				$exists = strtolower( $result[ $i ]['full'] ) == strtolower( $img_from_anchor['full'] ) ||
				          strtolower( $result[ $i ]['src'] ) == strtolower( $img_from_anchor['src'] );
			}
			$result = ! $exists ? array_merge( $result, array( $img_from_anchor ) ) : $result;
		}

		preg_match_all( $img_regex, $content, $images, PREG_SET_ORDER );
		foreach ( $images as $img ) {
			$src = self::get_element_attribute( 'src', $img[0] );
			if ( ! $src ) {
				continue;
			}
			for ( $i = 0, $exists = false; $i < count( $result ) && ! $exists; $i ++ ) {
				$exists = strtolower( $result[ $i ]['full'] ) == strtolower( $src ) ||
				          strtolower( $result[ $i ]['src'] ) == strtolower( $src );
			}
			$result = ! $exists ? array_merge( $result, array( array( 'src' => $src, 'full' => $src ) ) ) : $result;
		}
		$images = array();
		foreach ( $result as $item ) {
			$images[] = $item['full'];
		}

		return $images;
	}

	public static function get_image_attributes_by_src( $image_src, $content ) {
		$image_src = strtolower( $image_src );
		$img_regex = self::get_element_regex( 'img' );

		preg_match_all( self::$a_regex, $content, $anchors, PREG_SET_ORDER );
		foreach ( $anchors as $anchor ) {
			$href  = self::get_element_attribute( 'href', $anchor[1], '' );
			$img = self::get_image( $anchor[2] );
			$img_src = $img ? self::get_element_attribute( 'src', $img, '') : '';

			if ( in_array( $image_src, array( strtolower( $href ), strtolower( $img_src ) ) ) ) {
				return self::get_image_attributes( $img );
			}
		}

		preg_match_all( $img_regex, $content, $images, PREG_SET_ORDER );
		foreach ( $images as $img ) {
			$src = self::get_element_attribute( 'src', $img[0] );
			if ( $src == $image_src ) {
				return self::get_image_attributes( $img[0] );
			}
		}

		return false;
	}

	private static function get_image_attributes( $img_html ) {
		return array(
			'image_alt'   => self::get_element_attribute( 'alt', $img_html, '' ),
			'image_title' => self::get_element_attribute( 'title', $img_html, '' )
		);
	}

	/**
	 * @param $attr_name
	 * @param $html
	 *
	 * @param bool $default
	 *
	 * @return bool|string
	 */
	private static function get_element_attribute( $attr_name, $html, $default = false ) {
		$regex = self::get_attribute_regex( $attr_name );
		preg_match( $regex, $html, $match );

		return count( $match ) == 3 ? $match[2] : $default;
	}

	private static function get_attribute_regex( $attr_name ) {
		return sprintf( '/ %s[ ]*=[ ]*([\"\'])(.*?)\1/i', $attr_name );
	}

	private static function get_element_regex( $el_name ) {
		return sprintf( '/<%s[^>]*>/i', $el_name );
	}

	private static function try_get_image_from_anchor( $anchor_tag, $anchor_inside ) {
		$href           = self::get_element_attribute( 'href', $anchor_tag );
		$href_extension = $href ? pathinfo( $href, PATHINFO_EXTENSION ) : '!';
		$anchor_image   = self::get_image( $anchor_inside );
		if ( ! $anchor_image ) {
			return false;
		}
		$src     = self::get_element_attribute( 'src', $anchor_image );
		$src_ext = $src ? pathinfo( $src, PATHINFO_EXTENSION ) : '?';

		return strtolower( $src_ext ) == strtolower( $href_extension )
			? array( 'src' => $src, 'full' => $href )
			: false;
	}

	private static function get_image( $html ) {
		$img_regex = self::get_element_regex( 'img' );
		preg_match( $img_regex, $html, $img );

		return count( $img ) > 0 ? $img[0] : false;
	}

}