(function($) {
    $.fn.esCheckbox = function(options) {
        var $list = $(this);

        $list.each(function() {
            var $el = $(this);

            build($el);

            $el.on('change', changeChecked);
        });

        function build($el) {
            if ($el.is(':checkbox')) {

                $el.addClass('es-checkbox').addClass('es-hide');

                $el.wrap('<div class="es-checkbox-wrap"><div class="es-checkbox-inner"></div></div>').parent().parent().on( 'click', changeChecked);
                $el.parent().append('<div class="es-checkbox-slider"></div>');

                var $wrap = $el.closest('.es-checkbox-wrap');

                if (options.labelTrue) {
                    $wrap.after(createLabel(options.labelTrue));
                }

                if (options.labelFalse) {
                    $wrap.before(createLabel(options.labelFalse));
                }

                setState($el);

                if ($el.is(':disabled')) {
                    $el.closest('.es-checkbox-wrap').addClass('es-disabled');
                }
            }
        }

        function createLabel(content) {
            var span = document.createElement('span');
            span.classList.add('es-checkbox-answer');
            span.innerHTML = content;

            return span;
        }

        function changeChecked(e) {
            var $el = $(this).find('input:checkbox');
            if ($el.is(':disabled')) return false;
            e.preventDefault();
            e.stopPropagation();
            $el.prop('checked', $el.is(':checked') ? false : true);
            setState($el);
            $($el).trigger( 'es-checkbox-changed' );
        }

        function setState($el) {
            if ($el.is(':checked')) {
                $el.closest('.es-checkbox-wrap').addClass('active');
            } else {
                $el.closest('.es-checkbox-wrap').removeClass('active');
            }
        }
    }
})(jQuery);
