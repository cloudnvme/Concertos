function makeEditor(elm, extra = {}) {
    let options = {
        toolbarExclude: 'emoticon',
        icons: 'monocons',
        format: 'bbcode',
        emoticonsEnabled: false,
        resizeWidth: false
    };

    options = Object.assign(options, extra);
    sceditor.create(elm, options);

    return sceditor.instance(elm);
}