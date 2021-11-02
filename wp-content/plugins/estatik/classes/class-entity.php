<?php

/**
 * Base class for Estatik plugin entities Es_Entity.
 */
abstract class Es_Entity
{
    /**
     * @var int Post ID.
     */
    protected $_id;

    /**
     * Property construct.
     *
     * @param $id
     */
    public function __construct( $id = null )
    {
        $this->_id = $id;
    }

    /**
     * Get field value using ID and field name.
     *
     * @param $name
     *
     * @return mixed|null
     */
    public function __get( $name )
    {
        $fields = static::get_fields();

        if ( isset( $fields[ $name ] ) ) {
            $value = $this->get_field_value( $name );
            return apply_filters( 'es_get_entity_field_value', $value, $name, static::get_entity_prefix(), $this );
        }

        return null;
    }

    /**
     * Magic method for empty and isset methods.
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $value = $this->__get( $name );
        return ! empty( $value );
    }

    /**
     * Return entity ID.
     *
     * @return int|null
     */
    public function getID()
    {
        return $this->_id;
    }

    /**
     * Return entity custom fields array.
     *
     * @return mixed
     */
    public static function get_fields()
    {
        return array();
    }

    /**
     * Return field info data.
     *
     * @param $field
     *
     * @return mixed|null
     */
    public static function get_field_info( $field )
    {
        $fields = static::get_fields();
        return ! empty( $fields[ $field ] ) ? $fields[ $field ]: null;
    }

    /**
     * Return entity prefix string.
     *
     * @return string
     */
    abstract public function get_entity_prefix();

    /**
     * Return entity object like WP_Post or WP_User.
     *
     * @return array|null|WP_Post
     */
    abstract public function get_entity();

    /**
     * Return property field value.
     *
     * @param $field
     * @param bool $single
     *
     * @return mixed
     */
    abstract public function get_field_value( $field, $single = true );

    /**
     * Save post field.

     * @param $field
     * @param $value
     *
     * @return void
     */
    abstract public function save_field_value( $field, $value );

	/**
	 * @return string
	 */
	abstract public function get_base_field_name();

	/**
	 * Return entity name.
	 *
	 * @return string
	 */
	abstract public function get_entity_name();
}
