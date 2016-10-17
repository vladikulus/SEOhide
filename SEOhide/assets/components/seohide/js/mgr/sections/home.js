SEOhide.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'seohide-panel-home',
            renderTo: 'seohide-panel-home-div'
        }]
    });
    SEOhide.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(SEOhide.page.Home, MODx.Component);
Ext.reg('seohide-page-home', SEOhide.page.Home);