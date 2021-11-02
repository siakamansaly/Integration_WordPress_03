<?php

/**
 * Class Es_Messenger
 */
class Es_Messenger implements Es_Messenger_Interface
{
    /**
     * Container messages key.
     *
     * @var string
     */
    protected $_key;

	/**
	 * Es_Messenger constructor.
	 *
	 * @param $key
	 *    Message container key.
	 */
	public function __construct( $key )
	{
		$this->_key = $key;
	}

    /**
     * Set new message
     *
     * @param $message
     * @param $type
     */
    public function set_message( $message, $type )
    {
	    $messages = $this->get_messages();
	    $messages[ $type ][] = $message;
    	set_transient( $this->_key, $messages );
    }

    /**
     * Render all messages and clear container.
     *
     * @return void
     */
    public function render_messages()
    {
        if ( $messages_list = $this->get_messages() ) {
            foreach ( $messages_list as $type => $messages ) {
                if ( ! empty( $messages ) ) {
                    foreach ( $messages as $message ) {
                        $message = $type == 'error' ?
                            '<i class="fa fa-times-circle-o" aria-hidden="true"></i> ' . $message :
                            '<i class="fa fa-check-circle-o" aria-hidden="true"></i> ' . $message;

                        echo '<p class="es-message es-message-' . $type . '" >' . $message . '</p>';
                    }
                }
            }

            $this->clean_container();
        }
    }

    /**
     * Return message container.
     *
     * @return null|array
     */
    public function get_messages()
    {
        return get_transient( $this->_key );
    }

    /**
     * Clean message container.
     *
     * @return void.
     */
    public function clean_container()
    {
        delete_transient( $this->_key );
    }
}
