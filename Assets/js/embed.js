

var _dashboardInitWidgetRemoveButtons = Mautic.initWidgetRemoveButtons;
var _dashboardSaveWidgetSorting = Mautic.saveWidgetSorting;

Mautic.embedOnLoad = function (container) {
    Mautic.initWidgetRemoveButtons = function (scope) {
        scope.find('.remove-widget').on('click', function(e) {
            e.preventDefault();
            var button = mQuery(this);
            var wrapper = button.closest('.widget');
            var widgetId = wrapper.attr('data-widget-id');
            wrapper.hide('slow');
            Mautic.ajaxActionRequest('plugin:TheCodeineEmbed:deleteWidget', {widget: widgetId}, function(response) {
                if (!response.success) {
                    wrapper.show('slow');
                }
            });
        });
    };

    Mautic.saveWidgetSorting = function () {
        var widgetsWrapper = mQuery('#dashboard-widgets');
        var embedId = widgetsWrapper.attr('data-embed-id');
        var widgets = widgetsWrapper.children();
        var ordering = [];
        widgets.each(function(index, value) {
            ordering.push(mQuery(this).attr('data-widget-id'));
        });

        Mautic.ajaxActionRequest('plugin:TheCodeineEmbed:updateWidgetOrdering', {'ordering': ordering, 'embed': embedId});
    };

    Mautic.initWidgetSorting();
    Mautic.initWidgetRemoveButtons(mQuery('#dashboard-widgets'));
};

Mautic.embedOnUnload = function(id) {
    Mautic.initWidgetRemoveButtons = _dashboardInitWidgetRemoveButtons;
    Mautic.saveWidgetSorting = _dashboardSaveWidgetSorting;

    Mautic.dashboardOnUnload(id);
}
