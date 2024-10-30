<?php

class IAPoster_Lib_DescriptionProcessor {

	private $format;
	private $attributes;
	private $parse_exception;
	private $stack = array();

	private static $AltEx = '{';
	private static $AltExEnd = '}';
	private static $AltExSep = '|';
	private static $TagEx = '[';
	private static $TagExEnd = ']';


	private function __construct( $format, $attributes ) {
		$this->format          = self::$AltEx . $format . self::$AltExEnd;
		$this->attributes      = $attributes;
		$this->parse_exception = __( 'Description format parse exception', 'iaposter' );
	}

	/**
	 * @param $description_format string
	 * @param $attributes array
	 *
	 * @return mixed string
	 */
	public static function process( $description_format, $attributes ) {
		$instance = new IAPoster_Lib_DescriptionProcessor( $description_format, $attributes );
		try {
			return $instance->parse();
		} catch(Exception $ex) {
			return '';
		}
	}

	public static function validate( $description_format ) {
		try {
			$instance = new IAPoster_Lib_DescriptionProcessor( $description_format, array() );
			$instance->parse();
			return true;
		} catch (Exception $ex) {
			return false;
		}
	}

	public function parse() {
		$format = $this->format;
		$this->process_char( self::$AltEx);
		for ( $i = 0; $i < strlen( $format ); $i ++ ) {
			$this->process_char( $format[ $i ] );
		}
		$this->process_char( self::$AltExEnd);
		if ( count( $this->stack ) == 1 && $this->stack[0] instanceof IAPoster_Lib_IExpression ) {
			return $this->stack[0]->evaluate( $this->attributes );
		} else {
			throw new Exception( $this->parse_exception );
		}
	}

	/**
	 * @param $char string
	 */
	private function process_char( $char ) {
		switch ( $char ) {
			case self::$AltExEnd:
				$this->process_alt_end();
				break;
			case self::$TagExEnd:
				$this->process_tag_end();
				break;
			case self::$TagEx:
			case self::$AltEx:
			case self::$AltExSep:
			default:
				$this->stack[] = $char;
		}
	}

	private function process_tag_end() {
		$tag_reverse = array();
		do {
			$char = array_pop( $this->stack );
			if ( self::$TagEx == $char ) {
				$this->stack[] = new IAPoster_Lib_TagExpression( join( '', array_reverse( $tag_reverse ) ) );
				break;
			}
			if ( count( $this->stack ) == 0 ) {
				throw new Exception( $this->parse_exception );
			}
			$tag_reverse[] = $char;
		} while ( true );
	}

	private function process_alt_end() {
		$alt_pieces = array();
		$alt_iterator = 0;
		$text_expr_array = array();
		do {
			$alt_pieces[ $alt_iterator ] = ! isset( $alt_pieces[ $alt_iterator ] ) ? array() : $alt_pieces[ $alt_iterator ];
			$el = array_pop( $this->stack );
			if ( self::$AltEx === $el) {
				if ( count($text_expr_array) > 0 ){
					$alt_pieces[ $alt_iterator ][] = new iAPoster_Lib_TextExpression( join( '', array_reverse( $text_expr_array ) ) );
				}
				for($i = 0, $reordered = array(); $i < count( $alt_pieces ); $i++) {
					if ( count( $alt_pieces[ $i ] ) > 0 ) {
						$reordered[] = new IAPoster_Lib_ExpressionsExpression( array_reverse( $alt_pieces[ $i ] ) );
					}
				}
				$this->stack[] = new IAPoster_Lib_AltExpression( array_reverse( $reordered ) );
				break;
			} else if ( self::$AltExSep === $el) {
				if ( count($text_expr_array) > 0 ){
					$alt_pieces[ $alt_iterator ][] = new iAPoster_Lib_TextExpression( join( '', array_reverse( $text_expr_array ) ) );
					$text_expr_array = array();
				}
				$alt_iterator++;
			} else if ( $el instanceof IAPoster_Lib_IExpression ) {
				if ( count($text_expr_array) > 0 ){
					$alt_pieces[ $alt_iterator ][] = new iAPoster_Lib_TextExpression( join( '', array_reverse( $text_expr_array ) ) );
					$text_expr_array = array();
				}
				$alt_pieces[ $alt_iterator ][] = $el;
			} else if ( count( $this->stack ) == 0 ) {
				throw new Exception( $this->parse_exception );
			} else {
				// $el is char
				$text_expr_array[] = $el;
			}
		} while( true );
	}
}

interface IAPoster_Lib_IExpression {
	function evaluate( $context );
}

abstract class IAPoster_Lib_TextExpressionBase implements IAPoster_Lib_IExpression {

	protected $text;

	function __construct( $text ) {
		$this->text = $text;
	}

	abstract function evaluate( $context );
}

abstract class IAPoster_LibExpressionsExpressionBase implements IAPoster_Lib_IExpression {
	protected $expressions;

	/**
	 * IAPoster_LibExpressionsExpressionBase constructor.
	 *
	 * @param $expressions IAPoster_Lib_IExpression[]
	 */
	function __construct( $expressions ) {
		$this->expressions = $expressions;
	}

	abstract function evaluate( $context );
}

class iAPoster_Lib_TextExpression extends IAPoster_Lib_TextExpressionBase {

	function evaluate( $context ) {
		return $this->text;
	}
}

class IAPoster_Lib_TagExpression extends IAPoster_Lib_TextExpressionBase {
	function evaluate( $context ) {
		if ( ! isset( $context[ $this->text ] ) ) {
			return false;
		}
		switch( $this->text ) {
			case 'post_tags':
				$tags = $context[ $this->text ];
				for( $i = 0; $i < count( $tags ); $i++ ) {
					$tags[ $i ] = '#' . $tags[ $i ];
				}
				return join( ' ', $tags );
			default:
				return $context[ $this->text ];
		}
	}
}

class IAPoster_Lib_ExpressionsExpression extends IAPoster_LibExpressionsExpressionBase {
	function evaluate( $context ) {
		for ( $i = 0, $result = ''; $i < count( $this->expressions ); $i ++ ) {
			$result .= $this->expressions[ $i ]->evaluate( $context );
		}
		return $result;
	}
}

class IAPoster_Lib_AltExpression extends IAPoster_LibExpressionsExpressionBase {
	function evaluate( $context ) {
		for ( $i = 0; $i < count( $this->expressions ); $i ++ ) {
			$expression_result = $this->expressions[ $i ]->evaluate( $context );
			if ( '' != $expression_result ) {
				return $expression_result;
			}
		}

		return '';
	}
}