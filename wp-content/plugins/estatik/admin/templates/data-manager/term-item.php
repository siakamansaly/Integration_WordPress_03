<?php

/**
 * @var stdClass $taxonomy
 * @var WP_Term[] $terms
 */

?>

<div class="es-data-manager-item item-<?php echo $this->_taxonomy->name; ?>">
    <form method="post" data-remove-action="es_ajax_data_manager_remove_term">
        <h3><?php echo $this->_taxonomy->label; ?></h3>
        <ul>
            <?php if ( $terms = $this->getItems() ) : ?>
                <?php foreach ( $terms as $term ) : ?>
                    <li><label><?php echo $term->name; ?></label> <a href="#" class="es-item-remove js-item-remove"
                          data-id="<?php echo $term->term_id; ?>" data-action="es_ajax_data_manager_remove_term"><span class="es-sprite es-sprite-close"></span></a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <span class="es-data-manager-item-msg"></span>

        <div class="es-data-manager-item-nav">
            <label><input type="text" name="item_name" required placeholder="<?php _e( 'text/number', 'es-plugin' ); ?>"/></label>
            <a href="" class="es-button-add-item es-data-manager-submit">
                <span><?php _e( 'Add new item', 'es-plugin' ); ?></span>
            </a>
        </div>

        <input type="hidden" name="taxonomy" value="<?php echo $this->_taxonomy->name; ?>"/>
        <?php wp_nonce_field( 'es_add_data_manager_term', 'es_add_data_manager_term' ); ?>
        <input type="hidden" name="action" value="es_ajax_data_manager_add_term"/>
    </form>
</div>
