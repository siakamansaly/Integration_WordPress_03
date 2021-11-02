<?php

/**
 * Class Es_Session_Storage
 */
class Es_Session_Storage
{
    /**
     * @var string
     */
    protected $_key = 'storage';

    /**
     * Es_Session_Storage constructor.
     * @param $key
     */
    public function __construct( $key ) {
        $this->_key = $key;
    }

    /**
     * Set data to the session container.
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public function set( $key, $value ) {
	    $messages = $this->get_all();
	    $messages[ $key ] = $value;
	    set_transient( $this->_key, $messages );
    }

    /**
     * Set array to the storage
     *
     * @param array $data
     *
     * @return void
     */
    public function set_all( array $data ) {
        if ( $data ) {
            foreach ( $data as $key => $value ) {
                $this->set( $key, $value );
            }
        }
    }

    /**
     * Get value from the storage.
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public function get( $key, $default = null ) {
    	$messages = $this->get_all();
        return ! empty( $messages[ $key ] ) ? $messages[ $key ] : $default;
    }

    /**
     * Check for key.
     *
     * @param $key
     *
     * @return bool
     */
    public function exists( $key ) {
	    $messages = $this->get_all();
        return isset( $messages[ $key ] );
    }

    /**
     * Remove data using key.
     *
     * @param $key
     *
     * @return void
     */
    public function remove( $key ) {
	    $messages = $this->get_all();
        unset( $messages[ $key ] );
        $this->set_all( $messages );
    }

    /**
     * Clear all data using main key.
     *
     * @return void
     */
    public function clear_all()
    {
        delete_transient( $this->_key );
    }

    /**
     * Return all storage data using main key.
     *
     * @param null $default
     * @return null
     */
    public function get_all( $default = null )
    {
    	$messages = get_transient( $this->_key );
        return ! empty( $messages ) ? $messages : $default;
    }
}
