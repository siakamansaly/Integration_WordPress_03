<?php

/**
 * @var stdClass $taxonomy
 * @var WP_Term[] $terms
 * @var Es_Data_Manager_Item $this
 */

global $es_settings;
$values = get_option( 'es_currency_values', array() ); ?>

<div class="es-data-manager-item<?php echo !empty($this->_options['id']) ? ' item-' . $this->_options['id'] : ''; ?>">
    <form method="post" data-storage="<?php echo $this->_option_storage_name; ?>" data-container="<?php echo $this->_current_option_name; ?>" data-remove-action="es_ajax_data_manager_remove_currency">
        <?php if (!empty($this->_options['label'])): ?>
            <h3><?php echo $this->_options['label']; ?></h3>
        <?php endif; ?>
        <ul>
            <?php if ( $items = $this->getItems() ) : ?>
                <?php foreach ( $items as $key => $item ) : ?>
                    <li><label>
                            <input type="radio" class="js-item-radio" data-action="es_ajax_data_manager_check_currency"
                                   name="id" <?php checked( $es_settings->currency, $key ); ?>
                                   value="<?php echo esc_attr( $key ); ?>"><?php echo $key; ?></label>
                        <?php if ( ! empty( $values[ $key ] ) ): ?>
                            <a href="#" class="es-item-remove js-item-remove" data-action="es_ajax_data_manager_remove_currency"
                               data-id="<?php echo esc_attr( $key ); ?>"><span class="es-sprite es-sprite-close"></span>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <span class="es-data-manager-item-msg"></span>

        <div class="es-data-manager-item-nav">
            <label><input type="text" name="currency_code" required placeholder="<?php _e( 'Currency code (USD)', 'es-plugin' ); ?>"/></label>
            <label><input type="text" name="currency_label" required placeholder="<?php _e( 'Currency label ($)', 'es-plugin' ); ?>"/></label>
            <a href="" class="es-button-add-item es-data-manager-submit">
                <span><?php _e( 'Add new item', 'es-plugin' ); ?></span></a>
        </div>

        <input type="hidden" name="option_storage_name" value="<?php echo esc_attr( $this->_option_storage_name ); ?>"/>
        <input type="hidden" name="current_option_name" value="<?php echo esc_attr( $this->_current_option_name ); ?>"/>
        <?php wp_nonce_field( 'es_add_data_manager_currency', 'es_add_data_manager_currency' ); ?>
        <input type="hidden" name="action" value="es_ajax_data_manager_add_currency"/>
    </form>
</div>
