(function() {
    tinymce.create('tinymce.plugins.OiiiO', {
        init : function(ed, url) {
			
            ed.addCommand('testimonials', function() {
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = '[testimonials]';
                ed.execCommand('mceInsertContent', 0, return_text);
            });	
            
            ed.addButton('testimonials', {
                title : 'testimonials',
                cmd : 'testimonials',
                image : url + '/quotes.png'
            });
        },

        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                    longname : 'OiiiO Buttons',
                    author : 'Mahmud Hasan Rashel',
                    authorurl : 'http://oiiio.us',
                    version : "0.1"
            };
        }
    });
    tinymce.PluginManager.add('oiiio', tinymce.plugins.OiiiO);
})();