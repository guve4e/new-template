<?php

function printAvailableQuantities($productType)
{
    if ($productType->quantity == 0)
        $color = "danger";
    else if ($productType->quantity > 0 && $productType->quantity <= 5)
        $color = "warning";
    else
        $color = "success";

    if ($productType->quantity == 1)
        $productSting = "product";
    else
        $productSting = "products";

    print "<span class='uk-badge uk-badge-{$color}'>{$productType->quantity} {$productSting} in stock</span>";
}

?>

<div id="sc-page-wrapper">
    <div id="sc-page-content">
        <div class="uk-child-width-1-4@xl uk-child-width-1-2@s" data-uk-grid>
            <div>
                <div class="uk-card">
                    <a href="plugins-data_grid.html" class="uk-card-body sc-padding sc-padding-medium-ends uk-flex uk-flex-middle">
                        <div class="uk-flex-1">
                            <h3 class="uk-card-title">Data Grid</h3>
                            <p class="sc-color-secondary uk-margin-remove uk-text-medium">Display and Edit Data</p>
                        </div>
                        <div class="md-bg-amber-600 uk-flex uk-flex-middle sc-padding-medium sc-padding-small-ends sc-round">
                            <i class="mdi mdi-grid md-color-white"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div>
                <div class="uk-card">
                    <a href="pages-mailbox.html" class="uk-card-body sc-padding sc-padding-medium-ends uk-flex uk-flex-middle">
                        <div class="uk-flex-1">
                            <h3 class="uk-card-title">Mailbox</h3>
                            <p class="sc-color-secondary uk-margin-remove uk-text-medium">Check Your Mail</p>
                        </div>
                        <div class="md-bg-green-600 uk-flex uk-flex-middle sc-padding-medium sc-padding-small-ends sc-round">
                            <i class="mdi mdi-email-outline md-color-white"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div>
                <div class="uk-card">
                    <a href="pages-task_board.html" class="uk-card-body sc-padding sc-padding-medium-ends uk-flex uk-flex-middle">
                        <div class="uk-flex-1">
                            <h3 class="uk-card-title">Task Board</h3>
                            <p class="sc-color-secondary uk-margin-remove uk-text-medium">Get Things Done</p>
                        </div>
                        <div class="md-bg-red-600 uk-flex uk-flex-middle sc-padding-medium sc-padding-small-ends sc-round">
                            <i class="mdi mdi-bug md-color-white"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div>
                <div class="uk-card">
                    <a href="pages-chat.html" class="uk-card-body sc-padding sc-padding-medium-ends uk-flex uk-flex-middle">
                        <div class="uk-flex-1">
                            <h3 class="uk-card-title">Chat</h3>
                            <p class="sc-color-secondary uk-margin-remove uk-text-medium">Get in Touch with Friends</p>
                        </div>
                        <div class="md-bg-deep-purple-600 uk-flex uk-flex-middle sc-padding-medium sc-padding-small-ends sc-round">
                            <i class="mdi mdi-message-outline md-color-white"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="uk-child-width-1-3@l uk-child-width-1-2@m" data-uk-grid>
            <div>
                <div class="uk-card">
                    <h3 class="uk-card-title">Revenue</h3>
                    <div class="uk-card-body">
                        <div class="sc-chart uk-flex uk-flex-center" id="sc-js-chart-revenue">
                            <div class="uk-flex uk-flex-middle uk-height-1-1 uk-flex-center">
                                <div class="sc-spinner"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-card">
                    <h3 class="uk-card-title">Email Subscribers</h3>
                    <div class="uk-card-body">
                        <div class="sc-chart uk-flex uk-flex-center" id="sc-js-chart-email-subscribers">
                            <div class="uk-flex uk-flex-middle uk-height-1-1 uk-flex-center">
                                <div class="sc-spinner"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="uk-card">
                    <h3 class="uk-card-title">Returns</h3>
                    <div class="uk-card-body">
                        <div class="sc-chart uk-flex uk-flex-center" id="sc-js-chart-returns">
                            <div class="uk-flex uk-flex-middle uk-height-1-1 uk-flex-center">
                                <div class="sc-spinner"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div data-uk-grid>
            <div class="uk-width-2-3@l">
                <div class="uk-card">
                    <h3 class="uk-card-title">Sales report</h3>
                    <div class="sc-padding sc-padding-medium-ends md-bg-grey-100">
                        <div class=" uk-flex-middle uk-grid-small" data-uk-grid>
                            <div class="uk-flex-1">
                                <div class="uk-button-group sc-button-group-outline">
                                    <button class="sc-button sc-button-outline sc-button-small sc-js-chart-view" data-view="hours">Hours</button>
                                    <button class="sc-button sc-button-outline sc-button-small sc-js-chart-view" data-view="week">Week</button>
                                    <button class="sc-button sc-button-outline uk-active sc-button-small sc-js-chart-view" data-view="months">Months</button>
                                    <button class="sc-button sc-button-outline sc-button-small sc-js-chart-view" data-view="years">Years</button>
                                </div>
                            </div>
                            <div class="uk-flex uk-width-auto@s">
                                <a href="#" id="sc-chart-reload"><i class="mdi sc-icon-square mdi-reload sc-color-secondary"></i></a>
                                <a href="#" id="sc-chart-save-image"><i class="mdi sc-icon-square mdi-floppy sc-color-secondary"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="sc-card-content">
                        <div class="sc-padding-medium">
                            <div class="sc-chart-large uk-flex uk-flex-center" id="sc-js-chart-sales-report">
                                <div class="uk-flex uk-flex-middle uk-height-1-1 uk-flex-center">
                                    <div class="sc-spinner"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3@l">
                <div class="uk-card">
                    <h3 class="uk-card-title">Top Referals</h3>
                    <div class="uk-card-body">
                        <div class="sc-chart uk-flex uk-flex-center" id="sc-js-chart-referrals">
                            <div class="uk-flex uk-flex-middle uk-height-1-1 uk-flex-center">
                                <div class="sc-spinner"></div>
                            </div>
                        </div>
                        <table class="uk-table uk-table-small uk-table-divider">
                            <thead>
                            <tr>
                                <th class="uk-table-shrink">Rank</th>
                                <th>Referral</th>
                                <th>Visits</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="uk-text-center">1</td>
                                <td>Google</td>
                                <td>125234</td>
                            </tr>
                            <tr>
                                <td class="uk-text-center">2</td>
                                <td>Bookmarks</td>
                                <td>104234</td>
                            </tr>
                            <tr>
                                <td class="uk-text-center">3</td>
                                <td>Facebook</td>
                                <td>78342</td>
                            </tr>
                            <tr>
                                <td class="uk-text-center">4</td>
                                <td>Envato</td>
                                <td>41895</td>
                            </tr>
                            <tr>
                                <td class="uk-text-center">5</td>
                                <td>Twitter</td>
                                <td>23619</td>
                            </tr>
                            <tr>
                                <td class="uk-text-center">6</td>
                                <td>Bing</td>
                                <td>4268</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-card uk-margin-top">
            <h3 class="uk-card-title">Latest Orders</h3>
            <div class="uk-card-body">
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-striped uk-table-hover uk-table-middle">
                        <thead>
                        <tr>
                            <th class="uk-table-shrink"></th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Order ID</th>
                            <th class="uk-text-center">Quantity</th>
                            <th class="uk-text-right">Price</th>
                            <th class="uk-table-shrink">Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="uk-text-right">1</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Samsung 128GB 100MB/s (U3) MicroSD</a></td>
                            <td class="uk-text-nowrap">Corbin Lakin</td>
                            <td>#C1nFg5DSyd</td>
                            <td class="uk-text-center">4</td>
                            <td class="uk-text-right">$19.99</td>
                            <td><span class="uk-label uk-label-danger">canceled</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">2</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Nintendo Switch – Neon Red and Neon Blue Joy-Con</a></td>
                            <td class="uk-text-nowrap">Gussie Larkin</td>
                            <td>#9CRFwZ3ylK</td>
                            <td class="uk-text-center">3</td>
                            <td class="uk-text-right">$299.00</td>
                            <td><span class="uk-label uk-label-default">on hold</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">3</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Oral-B Black Pro 1000 Power Rechargeable Electric Toothbrush</a></td>
                            <td class="uk-text-nowrap">Yasmeen Bogan</td>
                            <td>#uYoBDGcakV</td>
                            <td class="uk-text-center">3</td>
                            <td class="uk-text-right">$39.94</td>
                            <td><span class="uk-label uk-label-success">sent</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">4</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">iRobot Roomba 960 Robot Vacuum with Wi-Fi Connectivity</a></td>
                            <td class="uk-text-nowrap">Gabrielle Larkin</td>
                            <td>#QbZvyATeNp</td>
                            <td class="uk-text-center">1</td>
                            <td class="uk-text-right">$314.30</td>
                            <td><span class="uk-label uk-label-warning">pending</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">5</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Fujitsu ScanSnap iX500 Color Duplex Desk Scanner for Mac and PC</a></td>
                            <td class="uk-text-nowrap">Emma Wuckert</td>
                            <td>#JOLcej0dN8</td>
                            <td class="uk-text-center">3</td>
                            <td class="uk-text-right">$404.95</td>
                            <td><span class="uk-label uk-label-danger">canceled</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">6</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Samsung Galaxy Watch (46mm) Silver (Bluetooth)</a></td>
                            <td class="uk-text-nowrap">Hailie Purdy</td>
                            <td>#BTl4gnXKEe</td>
                            <td class="uk-text-center">4</td>
                            <td class="uk-text-right">$349.99</td>
                            <td><span class="uk-label uk-label-warning">pending</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">7</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Sonos Play:1 – Compact Wireless Home Smart Speaker for Streaming Music</a></td>
                            <td class="uk-text-nowrap">Reggie Treutel</td>
                            <td>#E30PSAljnY</td>
                            <td class="uk-text-center">1</td>
                            <td class="uk-text-right">$149.00</td>
                            <td><span class="uk-label uk-label-default">on hold</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">8</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Fitbit Charge 3 Fitness Activity Tracker</a></td>
                            <td class="uk-text-nowrap">Liliana Keeling</td>
                            <td>#4kcZrsLBdD</td>
                            <td class="uk-text-center">2</td>
                            <td class="uk-text-right">$149.95</td>
                            <td><span class="uk-label uk-label-warning">pending</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">9</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Dyson Cyclone V10 Absolute Lightweight Cordless Stick Vacuum Cleaner</a></td>
                            <td class="uk-text-nowrap">Phoebe Cruickshank</td>
                            <td>#SvrgGunCDj</td>
                            <td class="uk-text-center">1</td>
                            <td class="uk-text-right">$527.94</td>
                            <td><span class="uk-label uk-label-default">on hold</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        <tr>
                            <td class="uk-text-right">10</td>
                            <td class="uk-text-nowrap"><a href="#" class="sc-text-semibold">Logitech Harmony Elite Remote Control</a></td>
                            <td class="uk-text-nowrap">Milo Quigley</td>
                            <td>#eSfpEJPT1C</td>
                            <td class="uk-text-center">4</td>
                            <td class="uk-text-right">$184.99</td>
                            <td><span class="uk-label uk-label-success">sent</span></td>
                            <td><a href="#" class="mdi mdi-file-outline sc-icon-square"></a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // prevent FOUC
    var html = document.getElementsByTagName('html')[0];
    html.style.backgroundColor = '#f5f5f5';
    document.body.style.visibility = 'hidden';
    document.body.style.overflow = 'hidden';
    document.body.style.apacity = '0';
    document.body.style.maxHeight = "100%";
</script>
<!-- polyfills -->
<script src="assets/js/vendor/polyfills.min.js"></script>

<!-- UIKit js -->
<script src="assets/js/uikit.min.js"></script>

<!-- async assets-->
<script>
    // loadjs.js (assets/js/vendor/loadjs.js)
    loadjs=function(){function v(a,d){a=a.push?a:[a];var c=[],b=a.length,e=b,g,k;for(g=function(a,b){b.length&&c.push(a);e--;e||d(c)};b--;){var h=a[b];(k=n[h])?g(h,k):(h=p[h]=p[h]||[],h.push(g))}}function r(a,d){if(a){var c=p[a];n[a]=d;if(c)for(;c.length;)c[0](a,d),c.splice(0,1)}}function t(a,d){a.call&&(a={success:a});d.length?(a.error||q)(d):(a.success||q)(a)}function u(a,d,c,b){var e=document,g=c.async,k=c.preload;try{var h=document.createElement("link").relList.supports("preload")}catch(y){h=0}var l=
        (c.numRetries||0)+1,p=c.before||q,m=a.replace(/^(css|img)!/,"");b=b||0;if(/(^css!|\.css$)/.test(a)){var n=!0;var f=e.createElement("link");k&&h?(f.rel="preload",f.as="style"):f.rel="stylesheet";f.href=m}else/(^img!|\.(png|gif|jpg|svg)$)/.test(a)?(f=e.createElement("img"),f.src=m):(f=e.createElement("script"),f.src=a,f.async=void 0===g?!0:g);f.onload=f.onerror=f.onbeforeload=function(e){var g=e.type[0];if(n&&"hideFocus"in f)try{f.sheet.cssText.length||(g="e")}catch(w){18!=w.code&&(g="e")}if("e"==g&&
        (b+=1,b<l))return u(a,d,c,b);k&&h&&(f.rel="stylesheet");d(a,g,e.defaultPrevented)};!1!==p(a,f)&&(n?e.head.insertBefore(f,document.getElementById("main-stylesheet")):e.head.appendChild(f))}function x(a,d,c){a=a.push?a:[a];var b=a.length,e=b,g=[],k;var h=function(a,c,e){"e"==c&&g.push(a);if("b"==c)if(e)g.push(a);else return;b--;b||d(g)};for(k=0;k<e;k++)u(a[k],h,c)}function l(a,d,c){var b;d&&d.trim&&(b=d);var e=(b?c:d)||{};if(b){if(b in m)throw"LoadJS";m[b]=!0}x(a,function(a){t(e,a);r(b,a)},e)}var q=
        function(){},m={},n={},p={};l.ready=function(a,d){v(a,function(a){t(d,a)});return l};l.done=function(a){r(a,[])};l.reset=function(){m={};n={};p={}};l.isDefined=function(a){return a in m};return l}();
</script>
<script>
    var html = document.getElementsByTagName('html')[0];
    // ----------- CSS
    // md icons
    loadjs('assets/css/materialdesignicons.min.css', {
        preload: true
    });
    // UIkit
    loadjs('node_modules/uikit/dist/css/uikit.min.css', {
        preload: true
    });
    // themes
    loadjs('assets/css/themes/themes_combined.min.css', {
        preload: true
    });
    // mdi icons (base64) & google fonts (base64)
    loadjs(['assets/css/fonts/mdi_fonts.css', 'assets/css/fonts/roboto_base64.css', 'assets/css/fonts/sourceCodePro_base64.css'], {
        preload: true
    });
    // main stylesheet
    loadjs('assets/css/main.min.css', function() {});
    // vendor
    loadjs('assets/js/vendor.min.js', function () {
        // scutum common functions/helpers
        loadjs('assets/js/scutum_common.min.js', function() {
            scutum.init();
            loadjs('assets/js/views/dashboard/dashboard_v2.min.js', { success: function() { $(function(){scutum.dashboard.init()}); } })
            // show page
            setTimeout(function () {
                // clear styles (FOUC)
                $(html).css({
                    'backgroundColor': '',
                });
                $('body').css({
                    'visibility': '',
                    'overflow': '',
                    'apacity': '',
                    'maxHeight': ''
                });
            }, 100);
            // style switcher
            loadjs(['assets/js/style_switcher.min.js', 'assets/css/plugins/style_switcher.min.css'], {
                success: function() {
                    $(function(){
                        scutum.styleSwitcher();
                    });
                }
            });
        });
    });
</script>
