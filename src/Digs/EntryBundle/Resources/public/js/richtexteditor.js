var RichTextEditor =
{
    init: function(contentCssPath, selectPhotoPath, selectFilePath) {
        tinymce.PluginManager.add('digsimage', function(editor, url) {
            editor.addButton('digsimage', {
                icon: 'image',
                tooltip: 'Insert/edit image',
                onclick: function() {
                    editor.windowManager.open({
                        title: '写真の選択',
                        url: selectPhotoPath,
                        height: 400,
                        width: 800,
                        buttons: [{
                                text: 'Close',
                                onclick: 'close'
                            }]
                    });
                },
                stateSelector: 'img:not([data-mce-object],[data-mce-placeholder])'
            });
        });

        tinymce.PluginManager.add('digsfile', function(editor, url) {
            editor.addButton('digsfile', {
                icon: 'image',
                tooltip: 'Insert/edit file',
                onclick: function() {
                    editor.windowManager.open({
                        title: 'ファイルの選択',
                        url: selectPhotoPath,
                        height: 400,
                        width: 800,
                        buttons: [{
                                text: 'Close',
                                onclick: 'close'
                            }]
                    });
                },
                stateSelector: 'a.digsfile'
            });
        });

        tinymce.PluginManager.add('digscode', function(editor, url) {
            editor.addButton('digscode', {
                icon: 'code',
                tooltip: 'code',
                onclick: function() {
                    editor.execCommand('mceToggleFormat', false, 'pre');
                }
            });
        });
        
        tinymce.init({
            selector: "textarea",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste digsimage digscode digsfile"
            ],
            relative_urls: false,
            content_css: contentCssPath,
            menubar : false,
            //    statusbar : false,
//            toolbar: "undo redo | styleselect | bold italic underline strikethrough removeformat | table blockquote digscode | alignleft aligncenter alignright | bullist numlist | link image | digsimage"
            toolbar: "undo redo | styleselect | bold italic underline strikethrough | table blockquote digscode | alignleft aligncenter alignright | bullist numlist | link digsimage digsfile"
        });
    },

    selectPhoto: function(elm)
    {
        tinymce.activeEditor.windowManager.close();
        var editor = tinymce.activeEditor;
        var dom = editor.dom;
        var imgElm = editor.selection.getNode();
        if (imgElm.nodeName == 'IMG' && !imgElm.getAttribute('data-mce-object') && !imgElm.getAttribute('data-mce-placeholder')) {
        } else {
            imgElm = null;
        }
        var data = {
            src: elm.children('img').attr('src'),
            alt: elm.children('img').attr('alt')
        };

        editor.undoManager.transact(function() {
            if (!imgElm) {
                data.id = '__mcenew';
                editor.selection.setContent(dom.createHTML('img', data));
                imgElm = dom.get('__mcenew');
                dom.setAttrib(imgElm, 'id', null);
            } else {
                dom.setAttribs(imgElm, data);
            }
//                waitLoad(imgElm);
            editor.selection.select(imgElm);
            editor.nodeChanged();
            editor.execCommand('mceInsertLink', false, {
                href: elm.attr('href'),
                target: elm.attr('target'),
                class: "thumbnail"
            });
        });
    }
};
