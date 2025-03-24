(function($){

    $.fn.email_multiple = function(options) {

        const defaults = {
            reset: false,
            fill: false,
            data: null
        };


        const settings = $.extend(defaults, options);
        console.log(settings);
        let emailList = [];

        return this.each(function() {
            const $orig = $(this);

            // Clear and set up the input structure
            $orig.after(
                `<div class="all-mail"></div>
                <input type="text" name="email" class="enter-mail-id" placeholder="Enter Email ..." />`
            );

            const $element = $orig.siblings('.enter-mail-id');
            const $emailContainer = $orig.siblings('.all-mail');

            const addEmail = (email) => {
                if (emailList.includes(email)) return; // Avoid duplicates

                emailList.push(email);
                $emailContainer.append(
                    `<span class="email-ids">${email}<span class="cancel-email">x</span></span>`
                );
                updateOriginalInput();
            };

            const removeEmail = (email) => {
                emailList = emailList.filter(e => e !== email);
                updateOriginalInput();
            };

            const updateOriginalInput = () => {
                $orig.val(emailList.join(','));
            };

            $element.keydown(function(e) {
                if (e.keyCode === 13) { // Enter key
                    e.preventDefault();
                }
                $element.css('border', '');

                if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                    const getValue = $element.val().trim();
                    if (/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(getValue)) {
                        addEmail(getValue);
                        $element.val('');
                    } else {
                        $element.css('border', '1px solid red');
                    }
                }
            });

            $emailContainer.on('click', '.cancel-email', function() {
                const email = $(this).parent().text().slice(0, -1); // Remove the "x"
                $(this).parent().remove();
                removeEmail(email);
            });

            if (settings.data) {
                const emails = typeof settings.data === 'string' ? settings.data.split(',') : settings.data;
                emails.forEach(email => {
                    if (/^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(email.trim())) {
                        addEmail(email.trim());
                    } else {
                        $element.css('border', '1px solid red');
                    }
                });
            }

            if (settings.reset) {
                emailList = [];
                $emailContainer.empty();
                updateOriginalInput();
            }

            $orig.hide();
        });
    };

})(jQuery);
