<?php
namespace common\assets;

use yii\web\AssetBundle;

class ReactMapAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'themes/admin/custom/css/loader.css',
        'libs/bower/roboto-fontface/css/roboto/roboto-fontface.css',
        'themes/admin/main/css/icons/fontawesome/styles.min.css',
        'themes/admin/main/css/icons/icomoon/styles.css',

        'themes/admin/main/css/bootstrap.min.css',
        'themes/admin/main/css/bootstrap_limitless.min.css',
        'themes/admin/main/css/layout.min.css',
        'themes/admin/main/css/components.min.css',
        'themes/admin/main/css/colors.min.css',
        'themes/admin/main/css/extras/animate.min.css',

        # -- MAP --------------------------------------------
        'libs/bower/leaflet/dist/leaflet.css',

        # -- JQUERY --------------------------------------------
        'libs/bower/dependent-dropdown/css/dependent-dropdown.min.css',
        'libs/bower/jQuery-contextMenu/dist/jquery.contextMenu.min.css',
        'libs/bower/noty/lib/noty.css',

        # -- OTHER --------------------------------------------
        'libs/bower/jspanel3/source/jquery.jspanel.min.css',
        'libs/bower/perfect-scrollbar/css/perfect-scrollbar.min.css',
        'libs/bower/amcharts3/amcharts/plugins/export/export.css',
        'libs/bower/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.min.css',

        'themes/admin/custom/css/theme.css'
    ];

    public $js = [
        # -- UTILS --------------------------------------------
//        'libs/node/react.development.js',
//        'libs/node/react-dom.development.js',

        'themes/admin/main/js/main/jquery.min.js',
        'libs/bower/lodash/dist/lodash.min.js',
        'libs/bower/urijs/src/URI.min.js',
        'libs/bower/moment/min/moment-with-locales.min.js',
        'libs/bower/jquery-serialize-object/dist/jquery.serialize-object.min.js',
        'libs/bower/axios/dist/axios.min.js',


        # -- MAP --------------------------------------------
        'libs/bower/leaflet/dist/leaflet.js',
        'libs/node/@turf/turf.min.js',

        # -- THEMES --------------------------------------------
        'themes/admin/main/js/main/bootstrap.bundle.min.js',
        'themes/admin/main/js/plugins/ui/ripple.min.js',
        'themes/admin/main/js/plugins/forms/styling/uniform.min.js',

        # -- JQUERY --------------------------------------------
        'libs/bower/jquery-ui/jquery-ui.min.js',
        'libs/bower/dependent-dropdown/js/dependent-dropdown.js',
        'libs/bower/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'libs/bower/bootstrap-datepicker/dist/locales/bootstrap-datepicker.vi.min.js',
        'libs/bower/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js',
        'themes/admin/main/js/plugins/loaders/blockui.min.js',

        # -- CHART --------------------------------------------
        'libs/bower/amcharts3/amcharts/amcharts.js',
        'libs/bower/amcharts3/amcharts/serial.js',
        'libs/bower/amcharts3/amcharts/plugins/export/export.min.js',
        'libs/bower/amcharts3/amcharts/plugins/responsive/responsive.min.js',

        # -- OTHER --------------------------------------------
        'libs/bower/noty/lib/noty.min.js',
        'libs/bower/jspanel3/source/jquery.jspanel.min.js',
        'libs/bower/jquery.fancytree/dist/jquery.fancytree-all-deps.js',
        'libs/bower/jquery.fancytree/dist/modules/jquery.fancytree.dnd.js',
        'libs/bower/jquery.fancytree/dist/modules/jquery.fancytree.childcounter.js',
        'libs/bower/jquery.fancytree/dist/modules/jquery.fancytree.filter.js',
        'libs/bower/printThis/printThis.js',
        'libs/bower/jQuery-contextMenu/dist/jquery.contextMenu.min.js',

        'libs/bower/js-xlsx/dist/xlsx.full.min.js',
        'libs/bower/file-saverjs/FileSaver.min.js',
//        'libs/bower/tableexport.js/dist/js/tableexport.min.js',
        'libs/bower/tableexport.jquery.plugin/tableExport.min.js',
        'libs/bower/html2canvas/build/html2canvas.min.js', // export the table in PNG format
        'libs/bower/html2canvas/build/html2canvas.min.js', // export the table as a PDF file
        'libs/bower/jspdf/dist/jspdf.min.js', // export the table as a PDF file
        'libs/bower/jspdf-autotable/dist/jspdf.plugin.autotable.js',

        'libs/bower/jquery.floatThead/dist/jquery.floatThead.min.js',
        'themes/admin/main/js/plugins/forms/selects/select2.min.js',
        'libs/bower/gasparesganga-jquery-loading-overlay/dist/loadingoverlay.min.js',

        'libs/yii2/yii.js',
        'libs/yii2/ModalRemote.min.js',
        'libs/yii2/ajaxcrud.min.js',
        'libs/yii2/bootstrap-dialog.min.js',
        'libs/yii2/dialog-yii.min.js',
        'libs/yii2/kv-grid-export.min.js',
        'libs/yii2/kv-grid-toggle.min.js',
        'libs/yii2/yii.gridView.js',
        'libs/yii2/jquery.pjax.js',
    ];

    public $depends = [
//        'yii\widgets\PjaxAsset'
//        'yii\web\YiiAsset',
    ];
}

