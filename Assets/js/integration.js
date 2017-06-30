Mautic.removeEmbedPluginField = function(el, embedId) {
    var form = mQuery(el).closest('form');

    if (!embedId) {
        Mautic.removeFormListOption(el);
        Mautic.stopIconSpinPostEvent();
        return;
    }

    Mautic.ajaxActionRequest('plugin:TheCodeineEmbed:delete', {embed: embedId}, function(response) {
        if (response.success) {
            Mautic.removeFormListOption(el);
            form.submit();
        }
        Mautic.stopIconSpinPostEvent();
    });
}
