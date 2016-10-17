SEOhide.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'seohide-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('seohide') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('seohide_items'),
                layout: 'anchor',
                items: [{
                    html: _('seohide_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'seohide-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    SEOhide.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(SEOhide.panel.Home, MODx.Panel);
Ext.reg('seohide-panel-home', SEOhide.panel.Home);
