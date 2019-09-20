(function() {
    tinymce.PluginManager.add('bf_ad_sc', function( editor, url ) {
        editor.addButton( 'bf_ad_sc', {
            text: 'BannerFlow Ad',
            icon: false,
            onclick: function() {
                editor.windowManager.open( {
                    title: 'BannerFlow Ad Shortcode',
                    body: [
                        {
                            type: 'textbox',
                            name: 'bfid_landscape',
                            label: 'Banner ID Landscape',
                            value: '',
                        },
                        {
                            type: 'textbox',
                            name: 'bfid_portrait',
                            label: 'Banner ID Portrait',
                            value: '',
                        },
                        {
                            type: 'textbox',
                            name: 'target_url',
                            label: 'Target URL',
                            value: '',
                        },
                        {
                            type   : 'listbox',
                            name   : 'targetwindow',
                            label  : 'Target option',
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
                            label  : 'Responsive (fill screen)',
                            values : [
                                { text: 'On', value: 'on' },
                                { text: 'Off', value: 'off' },
                            ],
                        },
                        {
                            type   : 'listbox',
                            name   : 'politeloading',
                            label  : 'Polite loading (show GIF before loading)',
                            values : [
                                { text: 'On', value: 'on' },
                                { text: 'Off', value: 'off' },
                            ],
                        },
                        {
                            type   : 'listbox',
                            name   : 'adblock_detection',
                            label  : 'Ad block detection and auto redirect',
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