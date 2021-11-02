<?php

/**
 * Class Es_Repository
 */
class Es_Repository
{
    /**
     * @var string
     */
    protected $_table_name;
    /**
     * @var wpdb
     */
    protected $_wpdb;

    /**
     * Es_Repository constructor.
     * @param $table_name
     */
    public function __construct( $table_name )
    {
        global $wpdb;

        $this->_table_name = $table_name;
        $this->_wpdb = $wpdb;
    }

    /**
     * Insert an entity to the database.
     *
     * @param $data
     * @return false|int
     */
    public function create( $data )
    {
        foreach ( $data as $key => $value )
            $data[ $key ] = is_array( $value ) ? serialize( $value ) : $value;

        return $this->_wpdb->insert( $this->_table_name, $data );
    }

    /**
     * Update an entity.
     *
     * @param $data
     * @param $where
     * @return false|int
     */
    public function update( $data, $where )
    {
        foreach ( $data as $key => $value )
            $data[ $key ] = is_array( $value ) ? serialize( $value ) : $value;

        return $this->_wpdb->update( $this->_table_name, $data, $where );
    }

    /**
     * Return one row using where conditions.
     *
     * @param $where
     * @param string $select
     * @return array|null|object
     */
    public function get_one( $where, $select = '*' )
    {
        $item = $this->_wpdb->get_row( "SELECT {$select} FROM {$this->_table_name} " .
            self::build_where_string( $where ) . " LIMIT 1", ARRAY_A );

        foreach ( $item as $field => $value ) {
            $item[ $field ] = maybe_unserialize( $value ) ? unserialize( $value ) : $value;
        }

        return $item;
    }

    /**
     * Get items list by conditions.
     *
     * @param $where
     * @param $select
     * @return array|null|object
     */
    public function get_items($where, $select)
    {
        $items = $this->_wpdb->get_results( "SELECT {$select} FROM {$this->_table_name} " .
            self::build_where_string( $where ), ARRAY_A );

        foreach ( $items as $id => $item ) {
            foreach ( $item as $field => $value ) {
                $items[ $id ][ $field ] = maybe_unserialize( $value ) ? unserialize( $value ) : $value;
            }
        }

        return $items;
    }

    /**
     * Return where string.
     *
     * @param $where
     * @return null
     */
    protected static function build_where_string( $where )
    {
        $result = 'WHERE 1 = 1';

        foreach ( $where as $key => $value ) {
            $result .= " AND {$key} = '{$value}'";
        }

        return $result;
    }
}
