<?php

/**
 * Interface Es_Messenger_Interface
 */
interface Es_Messenger_Interface
{
    public function set_message( $message, $type );
    public function render_messages();
    public function get_messages();
}
