<?php

/**
 * @var $query WP_Query
 * @var $entity Es_Saved_Search
 */

global $es_settings; ?>

<h2 class="es-profile__tab-title"><?php _e( 'Saved Searches', 'es-plugin' ); ?></h2>
<p class="es-profile__subtitle">
	<?php _e( 'Your saved searches can be found here. You can delete or view new listings matching your searches.', 'es-plugin' ); ?>
</p>

<?php if ( $query->have_posts() ) : ?>
	<div class="es-saved-searches__wrap">
		<?php while ( $query->have_posts() ) :
            $query->the_post();
		    $entity = es_get_saved_search( get_the_ID() );
		    $title = get_the_title() ? get_the_title() : sprintf( __( 'Saved Search #%s', 'es-plugin' ), get_the_ID() ); ?>
			<div class="es-saved-search__item">
                <div class="es-saved-search__inner">
                    <h3>
                        <span class="js-saved-search-title"><?php echo $title; ?></span>
                        <a href="#" class="js-switch-block" data-block="#search-edit-form-<?php the_ID(); ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    </h3>
                    <form action="" class="es-saved-search__quick-form hidden" id="search-edit-form-<?php the_ID(); ?>">
                        <div class="quick-form__fields">
                            <input type="text" name="title" value="<?php echo $title; ?>"/>
                            <a href="#" class="js-saved-search-save"><?php _e( 'Save', 'es-plugin' ); ?></a>
                        </div>
                        <input type="hidden" name="id" value="<?php the_ID(); ?>"/>
                        <input type="hidden" name="action" value="es_saved_search_change_title"/>
                        <?php wp_nonce_field( 'es_saved_search_change_title', 'es_saved_search_change_title' ); ?>
                    </form>
                    <?php if ( ! empty( $entity->address ) ) : ?>
                        <p class="es-saved-search--address"><?php echo $entity->address; ?></p>
                    <?php endif; ?>

                    <?php if ( $entity->fields ) : ?>
                        <table class="es-saved-search--table">
                            <?php foreach ( $entity->fields as $field ) : ?>
                                <?php if ( $value = $entity->get_formatted_field( $field ) ) : ?>
                                    <tr>
                                        <td class="es-saved-search__attribute"><?php echo $entity->get_field_label( $field ); ?>: </td>
                                        <td class="es-saved-search__value">
                                            <?php echo $value; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <div class="es-msg-container"></div>
                </div>

                <ul class="es-inline-buttons">
                    <?php if ( $url = $entity->view_properties_url() ) : ?>
                        <li class="es-i-button-green">
                            <a href="<?php echo $url; ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i><?php _e( 'View Listings', 'es-plugin' ); ?></a>
                        </li>
                    <?php endif; ?>
                    <li class="es-i-button-gray">
                        <a href="<?php echo $entity->delete_url(); ?>"><i class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Delete Search', 'es-plugin' ); ?></a>
                    </li>
                </ul>
			</div>
		<?php endwhile; ?>
	</div>
<?php endif;
