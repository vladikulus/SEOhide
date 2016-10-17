SEOhide.window.CreateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'seohide-item-window-create';
    }
    Ext.applyIf(config, {
        title: _('seohide_item_create'),
        width: 550,
        autoHeight: true,
        url: SEOhide.config.connector_url,
        action: 'mgr/item/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    SEOhide.window.CreateItem.superclass.constructor.call(this, config);
};
Ext.extend(SEOhide.window.CreateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'modx-combo-resource',
            fieldLabel: _('seohide_item_name'),
            name: 'resource_id',
            id: config.id + '-resource_id',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('seohide_item_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('seohide-item-window-create', SEOhide.window.CreateItem);


SEOhide.window.UpdateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'seohide-item-window-update';
    }
    Ext.applyIf(config, {
        title: _('seohide_item_update'),
        width: 550,
        autoHeight: true,
        url: SEOhide.config.connector_url,
        action: 'mgr/item/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    SEOhide.window.UpdateItem.superclass.constructor.call(this, config);
};
Ext.extend(SEOhide.window.UpdateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'modx-combo-resource',
            fieldLabel: _('seohide_item_name'),
            name: 'resource_id',
            id: config.id + '-resource_id',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('seohide_item_active'),
            name: 'active',
            id: config.id + '-active',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('seohide-item-window-update', SEOhide.window.UpdateItem);



MODx.combo.Resource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'resource_id'
        ,hiddenName: 'resource_id'
        ,valueField: 'id'
        ,displayField: 'pagetitle'
        ,fields: ['id','pagetitle']
        ,url: MODx.config.connectors_url+'index.php'
        ,pageSize: 10
        ,hideMode: 'offsets'
        ,baseParams: {
            action: 'resource/getList'
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{pagetitle} ({id})</h4>'
            ,'</div></tpl>')
    });
    MODx.combo.Resource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Resource,MODx.combo.ComboBox);
Ext.reg('modx-combo-resource',MODx.combo.Resource);