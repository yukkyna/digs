var RichTextEditor =
{
    init: function(contentCssPath, selectPhotoPath, selectFilePath, styleSelect) {
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
                icon: 'newdocument',
                tooltip: 'Insert/edit file',
                onclick: function() {
                    editor.windowManager.open({
                        title: 'ファイルの選択',
                        url: selectFilePath,
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

        var toolbar = "undo redo | ";
        if (styleSelect)
        {
            toolbar += "styleselect | ";
        }
        toolbar += "bold italic underline strikethrough | table blockquote digscode | alignleft aligncenter alignright | bullist numlist | link digsimage digsfile";
        
        tinymce.init({
            selector: "textarea",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste digsimage digscode digsfile"
            ],
            relative_urls: false,
            remove_script_host : false,
            content_css: contentCssPath,
            menubar : false,
            style_formats : [
                {title : 'Header 1', block : 'h2'},
                {title : 'Header 2', block : 'h3'},
                {title : 'Header 3', block : 'h4'}
            ],
            //    statusbar : false,
//            toolbar: "undo redo | styleselect | bold italic underline strikethrough removeformat | table blockquote digscode | alignleft aligncenter alignright | bullist numlist | link image | digsimage"
            toolbar: toolbar
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
//            src: elm.children('img').attr('src'),
//            alt: elm.children('img').attr('alt')
            src: elm.children('img').attr('src')
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
    },
    
    selectFile: function(elm)
    {
        tinymce.activeEditor.windowManager.close();
        var editor = tinymce.activeEditor;
        var selection = editor.selection;
        var dom = editor.dom;

	var selectedElm = selection.getNode();
        var anchorElm = dom.getParent(selectedElm, 'a[href]');
        var href = elm.attr('href');
        var data ={};
        data.text = anchorElm ? (anchorElm.innerText || anchorElm.textContent) : selection.getContent({format: 'text'});
        data.href  = anchorElm ? dom.getAttrib(anchorElm, 'href') : '';
//        data.target = anchorElm ? dom.getAttrib(anchorElm, 'target') : '';
//        data.rel = anchorElm ? dom.getAttrib(anchorElm, 'rel') : '';

        if (anchorElm)
        {
            editor.focus();
//            anchorElm.innerHTML = data.text;

            dom.setAttribs(anchorElm, {
                href: href,
                class: 'digsfile'
//                target: data.target ? data.target : null,
//                rel: data.rel ? data.rel : null
            });
            
            selection.select(anchorElm);
        }
        else
        {
            var text = data.text;
            if (selectedElm.nodeName != "IMG" && data.text.length == 0) {
                text = elm.html();
            }
//            alert(href + '[' + data.text + ']');
            editor.insertContent(dom.createHTML('a', {
                href: href,
                class: 'digsfile'
//                target: data.target ? data.target : null,
//                rel: data.rel ? data.rel : null

            }, text));
        }
    }
};
