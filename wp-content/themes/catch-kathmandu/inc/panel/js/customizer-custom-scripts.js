/**
 * Theme Customizer custom scripts
 * Control of show/hide events on feature slider type selection
 */
(function($) {
    $.each( catchkathmandu_data.reset_options, function( index, value ) {
        wp.customize( value, function( setting ) {
            setting.bind( function( setting_value ) {
                var code = 'needs_refresh';
                if ( setting_value ) {
                    setting.notifications.add( code, new wp.customize.Notification(
                        code,
                        {
                            type: 'info',
                            message: catchkathmandu_data.reset_message
                        }
                    ) );
                } else {
                    setting.notifications.remove( code );
                }
            } );
        } );
    });
})(jQuery);

//Change value of hidden field below custom checkboxes
jQuery( document ).ready( function() {
    jQuery( '.customize-control-catchkathmandu_custom_checkbox input[type="checkbox"]' ).on(
        'change',
        function() {
        	checkbox_value = "0";

            if ( jQuery( this ).is(":checked") ) {
            	checkbox_value = "1";
            }

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_value ).trigger( 'change' );
        }
    );
} ); // jQuery( document ).ready