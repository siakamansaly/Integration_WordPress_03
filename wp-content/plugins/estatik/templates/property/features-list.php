<?php

/**
 * @var array $features_list
 * @var string $features_list_title
 */

?>

<div class="es-features-list-wrap">
    <span><?php echo $features_list_title; ?>:</span>

    <ul>
        <?php foreach ( $features_list as $item ) : ?>
            <li><i class="fa fa-check" aria-hidden="true"></i> <?php echo $item->name; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
