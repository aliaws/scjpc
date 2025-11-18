(function($){

    $.fn.email_multiple = function(options) {

        const defaults = {
            reset: false,
            data: null
        };

        const settings = $.extend(defaults, options);
        let valueList = [];

        return this.each(function() {
            const $orig = $(this);

            if ($orig.siblings('.all-mail').length === 0) {
                $orig.after(
                    `<div class="all-mail"></div>
                     <input type="text" name="email" class="enter-mail-id" placeholder="Enter value..." />`
                );
            }

            const $element = $orig.siblings('.enter-mail-id');
            const $container = $orig.siblings('.all-mail');

            const addValue = (val) => {
                const trimmed = String(val).trim();
                if (!trimmed || valueList.includes(trimmed)) return;
                valueList.push(trimmed);
                $container.append(`<span class="email-ids">${trimmed}<span class="cancel-email">x</span></span>`);
                $orig.val(valueList.join(','));
            };

            const removeValue = (val) => {
                const trimmed = String(val).trim();
                valueList = valueList.filter(e => e !== trimmed);
                $orig.val(valueList.join(','));
            };

            $element.off('keydown.emailMultiple').on('keydown.emailMultiple', function(e){
                if (e.keyCode === 13 || e.keyCode === 32) {
                    e.preventDefault();
                    const val = $element.val().trim();
                    addValue(val);
                    $element.val('');
                }
            });

            $container.off('click.emailMultiple').on('click.emailMultiple', '.cancel-email', function(){
                const $token = $(this).parent();
                const text = $token.clone().children().remove().end().text().trim();
                $token.remove();
                removeValue(text);
            });

            if (settings.data != null) {
                const initialValues = Array.isArray(settings.data) ? settings.data : String(settings.data).split(',');
                initialValues.forEach(addValue);
            }

            if (settings.reset) {
                valueList = [];
                $container.empty();
                $orig.val('');
            }

            $orig.hide();
        });
    };

})(jQuery);
