(function() {
    tinymce.PluginManager.add('bf_ad_sc', function( editor, url ) {
        editor.addButton( 'bf_ad_sc', {
            text: bf_tinyMCE_object.button_name,
            icon: false,
            onclick: function() {
                editor.windowManager.open( {
                    title: bf_tinyMCE_object.button_title,
                    body: [
                        {
                            type: 'textbox',
                            name: 'bfid_landscape',
                            label: bf_tinyMCE_object.bfid_landscape,
                            value: '',
                        },
                        {
                            type: 'textbox',
                            name: 'bfid_portrait',
                            label: bf_tinyMCE_object.bfid_portrait,
                            value: '',
                        },
                        {
                            type: 'textbox',
                            name: 'target_url',
                            label: bf_tinyMCE_object.target_url,
                            value: '',
                        },
                        {
                            type   : 'listbox',
                            name   : 'targetwindow',
                            label  : bf_tinyMCE_object.targetwindow,
                            values : [
                                { text: 'New tab/window', value: '_blank' },
                                { text: 'Same frame as opened', value: '_self' },
                                { text: 'Parent frame', value: '_parent' },
                                { text: 'Current window', value: '_top' }
                            ],
                            value : 'test2' // Sets the default
                        },
                        {
                            type   : 'listbox',
                            name   : 'responsive',
                            label  : bf_tinyMCE_object.responsive,
                            values : [
                                { text: 'On', value: 'on' },
                                { text: 'Off', value: 'off' },
                            ],
                        },
                        {
                            type   : 'listbox',
                            name   : 'politeloading',
                            label  : bf_tinyMCE_object.politeloading,
                            values : [
                                { text: 'On', value: 'on' },
                                { text: 'Off', value: 'off' },
                            ],
                        },
                        {
                            type   : 'listbox',
                            name   : 'adblock_detection',
                            label  : bf_tinyMCE_object.adblock_detection,
                            values : [
                                { text: 'True', value: 'true' },
                                { text: 'False', value: 'false' },
                            ],
                        }
                    ],
                    onsubmit: function( e ) {
                        editor.insertContent( '[bannerflow_ad bfid_landscape="' + e.data.bfid_landscape + '" bfid_portrait="' + e.data.bfid_portrait + '" target_url="' + e.data.target_url + '" targetwindow="' + e.data.targetwindow + '" responsive="' + e.data.responsive + '" politeloading="' + e.data.politeloading + '" adblock_detection="' + e.data.adblock_detection + '"]');
                    }
                });
            },
        });
    });
})();