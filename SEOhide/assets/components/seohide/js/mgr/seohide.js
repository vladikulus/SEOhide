var SEOhide = function (config) {
    config = config || {};
    SEOhide.superclass.constructor.call(this, config);
};
Ext.extend(SEOhide, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('seohide', SEOhide);

SEOhide = new SEOhide();