<script>
    document.addEventListener("DOMContentLoaded", function() {
        setInterval( function() {
            var minutes = document.querySelector( '.js-es-timer__minutes' ).innerHTML;

            if ( minutes > 0 ) {
                minutes = --minutes;
                document.querySelector( '.js-es-timer__minutes' ).innerHTML = minutes;
            } else {
                document.querySelector( '.es-notice__coupon' ).style.display = 'none';
            }
        }, 1000 * 60 );
    });
</script>
<style>
    .es-notice__coupon {
        box-shadow: none;
        border: 0;
        padding: 0;
        background: #fff;
        font-family: 'Open Sans', sans-serif;
        display: flex;
        align-items: center;
    }

    .es-timer {
        background: #58BF38;
        height: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        vertical-align: middle;
    }

    .es-timer .es-timer__item {
        text-align: center;
        position: relative;
        padding: 15px 20px 12px;
        border-right: 1px solid rgba(255, 255, 255, 0.6);
    }

    .es-timer .es-timer__item:first-child {
        background-image: url(<?php echo ES_ADMIN_IMAGES_URL . 'bunny.svg'; ?>);
        background-repeat: no-repeat;
        padding-left: 110px;
    }

    .es-timer .es-timer__item:last-child {
        border-right: 0;
    }

    .es-timer .es-timer__item b {
        display: block;
        font-weight: 600;
        font-size: 18px;
    }

    .es-timer .es-timer__item span {
        font-weight: normal;
        font-size: 11px;
        position: relative;
        top: -1px;
    }

    .es-notice .es-notice__content {
        display: inline-block;
        vertical-align: middle;
        margin: 3px 0 0 17px;
    }

    .es-coupon {
        color: #5AC03A;
        font-weight: 700;
    }

    p.es-doit-now {
        color: #A7A8A9;
        font-size: 10px;
        font-weight: 600;
        margin: 2px 0 0 !important;
    }

    p.es-become-pro {
        color: #222;
        font-size: 13px;
        font-weight: 400;
    }

    .es-become-pro a {
        color: #409DE1;
        font-weight: 700;
        text-decoration: underline;
    }

    .es-notice .es-notice__content p {
        margin: 0;
        padding: 0;
    }

    @media screen and (max-width: 500px) {
        .es-notice__coupon {
            flex-wrap: wrap;
        }

        .es-timer {
            flex: 1 0 100%;
            border-radius: 0;
            box-sizing: border-box;
        }

        .es-notice__coupon {
            padding: 0 !important;
        }

        .es-notice__content {
            padding: 10px 0px;
        }
    }
</style>
<div data-notice="es-coupon-notice" class="es-notice es-notice__coupon notice is-dismissible">
    <div class="es-timer">
        <div class="es-timer__item">
            <b><?php echo $days; ?></b>
            <span><?php echo _n( 'day', 'days', $days, 'es-plugin' ); ?></span>
        </div>
        <div class="es-timer__item">
            <b><?php echo $hours; ?></b>
            <span><?php echo _n( 'hour', 'hours', $hours, 'es-plugin' ); ?></span>
        </div>
        <div class="es-timer__item">
            <b class="js-es-timer__minutes"><?php echo $minutes; ?></b>
            <span><?php echo _n( 'min', 'mins', $minutes, 'es-plugin' ); ?></span>
        </div>
    </div>

    <div class="es-notice__content">
        <span class="es-coupon">Estatik Easter SALE! </span><span class="es-become-pro"><a href="https://estatik.net/choose-your-version/">Become PRO</a> with a <b>15% OFF</b> discount!</span>
        <p class="es-doit-now">Expiring soon. Do it now.</p>
    </div>
</div>