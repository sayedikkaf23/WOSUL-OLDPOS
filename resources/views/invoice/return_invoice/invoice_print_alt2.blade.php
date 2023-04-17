@php 
    $invoice = json_decode($invoice); 
    $invoice_products = json_decode($invoice_products); 
    $supplier = json_decode($supplier); 
@endphp

<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>WOSUL - Thanks</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Reports" name="description" />
        <meta content="Wasl Certificate" name="author" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">        

        <!-- Font Awesome Starts -->

             <style type="text/css">
                 
              
            @font-face {
              font-family: 'FontAwesome';
              src: url('../fonts/fontawesome-webfont.eot?v=4.7.0');
              src: url('../fonts/fontawesome-webfont.eot?#iefix&v=4.7.0') format('embedded-opentype'), url('../fonts/fontawesome-webfont.woff2?v=4.7.0') format('woff2'), url('../fonts/fontawesome-webfont.woff?v=4.7.0') format('woff'), url('../fonts/fontawesome-webfont.ttf?v=4.7.0') format('truetype'), url('../fonts/fontawesome-webfont.svg?v=4.7.0#fontawesomeregular') format('svg');
              font-weight: normal;
              font-style: normal;
            }
            .fa {
              display: inline-block;
              font: normal normal normal 14px/1 FontAwesome;
              font-size: inherit;
              text-rendering: auto;
              -webkit-font-smoothing: antialiased;
              -moz-osx-font-smoothing: grayscale;
            }
            /* makes the font 33% larger relative to the icon container */
            .fa-lg {
              font-size: 1.33333333em;
              line-height: 0.75em;
              vertical-align: -15%;
            }
            .fa-2x {
              font-size: 2em;
            }
            .fa-3x {
              font-size: 3em;
            }
            .fa-4x {
              font-size: 4em;
            }
            .fa-5x {
              font-size: 5em;
            }
            .fa-fw {
              width: 1.28571429em;
              text-align: center;
            }
            .fa-ul {
              padding-left: 0;
              margin-left: 2.14285714em;
              list-style-type: none;
            }
            .fa-ul > li {
              position: relative;
            }
            .fa-li {
              position: absolute;
              left: -2.14285714em;
              width: 2.14285714em;
              top: 0.14285714em;
              text-align: center;
            }
            .fa-li.fa-lg {
              left: -1.85714286em;
            }
            .fa-border {
              padding: .2em .25em .15em;
              border: solid 0.08em #eeeeee;
              border-radius: .1em;
            }
            .fa-pull-left {
              float: left;
            }
            .fa-pull-right {
              float: right;
            }
            .fa.fa-pull-left {
              margin-right: .3em;
            }
            .fa.fa-pull-right {
              margin-left: .3em;
            }
            /* Deprecated as of 4.4.0 */
            .pull-right {
              float: right;
            }
            .pull-left {
              float: left;
            }
            .fa.pull-left {
              margin-right: .3em;
            }
            .fa.pull-right {
              margin-left: .3em;
            }
            .fa-spin {
              -webkit-animation: fa-spin 2s infinite linear;
              animation: fa-spin 2s infinite linear;
            }
            .fa-pulse {
              -webkit-animation: fa-spin 1s infinite steps(8);
              animation: fa-spin 1s infinite steps(8);
            }
            @-webkit-keyframes fa-spin {
              0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
              }
            }
            @keyframes fa-spin {
              0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
              }
              100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
              }
            }
            .fa-rotate-90 {
              -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=1)";
              -webkit-transform: rotate(90deg);
              -ms-transform: rotate(90deg);
              transform: rotate(90deg);
            }
            .fa-rotate-180 {
              -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2)";
              -webkit-transform: rotate(180deg);
              -ms-transform: rotate(180deg);
              transform: rotate(180deg);
            }
            .fa-rotate-270 {
              -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=3)";
              -webkit-transform: rotate(270deg);
              -ms-transform: rotate(270deg);
              transform: rotate(270deg);
            }
            .fa-flip-horizontal {
              -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0, mirror=1)";
              -webkit-transform: scale(-1, 1);
              -ms-transform: scale(-1, 1);
              transform: scale(-1, 1);
            }
            .fa-flip-vertical {
              -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=2, mirror=1)";
              -webkit-transform: scale(1, -1);
              -ms-transform: scale(1, -1);
              transform: scale(1, -1);
            }
            :root .fa-rotate-90,
            :root .fa-rotate-180,
            :root .fa-rotate-270,
            :root .fa-flip-horizontal,
            :root .fa-flip-vertical {
              filter: none;
            }
            .fa-stack {
              position: relative;
              display: inline-block;
              width: 2em;
              height: 2em;
              line-height: 2em;
              vertical-align: middle;
            }
            .fa-stack-1x,
            .fa-stack-2x {
              position: absolute;
              left: 0;
              width: 100%;
              text-align: center;
            }
            .fa-stack-1x {
              line-height: inherit;
            }
            .fa-stack-2x {
              font-size: 2em;
            }
            .fa-inverse {
              color: #ffffff;
            }
            /* Font Awesome uses the Unicode Private Use Area (PUA) to ensure screen
               readers do not read off random characters that represent icons */
            .fa-glass:before {
              content: "\f000";
            }
            .fa-music:before {
              content: "\f001";
            }
            .fa-search:before {
              content: "\f002";
            }
            .fa-envelope-o:before {
              content: "\f003";
            }
            .fa-heart:before {
              content: "\f004";
            }
            .fa-star:before {
              content: "\f005";
            }
            .fa-star-o:before {
              content: "\f006";
            }
            .fa-user:before {
              content: "\f007";
            }
            .fa-film:before {
              content: "\f008";
            }
            .fa-th-large:before {
              content: "\f009";
            }
            .fa-th:before {
              content: "\f00a";
            }
            .fa-th-list:before {
              content: "\f00b";
            }
            .fa-check:before {
              content: "\f00c";
            }
            .fa-remove:before,
            .fa-close:before,
            .fa-times:before {
              content: "\f00d";
            }
            .fa-search-plus:before {
              content: "\f00e";
            }
            .fa-search-minus:before {
              content: "\f010";
            }
            .fa-power-off:before {
              content: "\f011";
            }
            .fa-signal:before {
              content: "\f012";
            }
            .fa-gear:before,
            .fa-cog:before {
              content: "\f013";
            }
            .fa-trash-o:before {
              content: "\f014";
            }
            .fa-home:before {
              content: "\f015";
            }
            .fa-file-o:before {
              content: "\f016";
            }
            .fa-clock-o:before {
              content: "\f017";
            }
            .fa-road:before {
              content: "\f018";
            }
            .fa-download:before {
              content: "\f019";
            }
            .fa-arrow-circle-o-down:before {
              content: "\f01a";
            }
            .fa-arrow-circle-o-up:before {
              content: "\f01b";
            }
            .fa-inbox:before {
              content: "\f01c";
            }
            .fa-play-circle-o:before {
              content: "\f01d";
            }
            .fa-rotate-right:before,
            .fa-repeat:before {
              content: "\f01e";
            }
            .fa-refresh:before {
              content: "\f021";
            }
            .fa-list-alt:before {
              content: "\f022";
            }
            .fa-lock:before {
              content: "\f023";
            }
            .fa-flag:before {
              content: "\f024";
            }
            .fa-headphones:before {
              content: "\f025";
            }
            .fa-volume-off:before {
              content: "\f026";
            }
            .fa-volume-down:before {
              content: "\f027";
            }
            .fa-volume-up:before {
              content: "\f028";
            }
            .fa-qrcode:before {
              content: "\f029";
            }
            .fa-barcode:before {
              content: "\f02a";
            }
            .fa-tag:before {
              content: "\f02b";
            }
            .fa-tags:before {
              content: "\f02c";
            }
            .fa-book:before {
              content: "\f02d";
            }
            .fa-bookmark:before {
              content: "\f02e";
            }
            .fa-print:before {
              content: "\f02f";
            }
            .fa-camera:before {
              content: "\f030";
            }
            .fa-font:before {
              content: "\f031";
            }
            .fa-bold:before {
              content: "\f032";
            }
            .fa-italic:before {
              content: "\f033";
            }
            .fa-text-height:before {
              content: "\f034";
            }
            .fa-text-width:before {
              content: "\f035";
            }
            .fa-align-left:before {
              content: "\f036";
            }
            .fa-align-center:before {
              content: "\f037";
            }
            .fa-align-right:before {
              content: "\f038";
            }
            .fa-align-justify:before {
              content: "\f039";
            }
            .fa-list:before {
              content: "\f03a";
            }
            .fa-dedent:before,
            .fa-outdent:before {
              content: "\f03b";
            }
            .fa-indent:before {
              content: "\f03c";
            }
            .fa-video-camera:before {
              content: "\f03d";
            }
            .fa-photo:before,
            .fa-image:before,
            .fa-picture-o:before {
              content: "\f03e";
            }
            .fa-pencil:before {
              content: "\f040";
            }
            .fa-map-marker:before {
              content: "\f041";
            }
            .fa-adjust:before {
              content: "\f042";
            }
            .fa-tint:before {
              content: "\f043";
            }
            .fa-edit:before,
            .fa-pencil-square-o:before {
              content: "\f044";
            }
            .fa-share-square-o:before {
              content: "\f045";
            }
            .fa-check-square-o:before {
              content: "\f046";
            }
            .fa-arrows:before {
              content: "\f047";
            }
            .fa-step-backward:before {
              content: "\f048";
            }
            .fa-fast-backward:before {
              content: "\f049";
            }
            .fa-backward:before {
              content: "\f04a";
            }
            .fa-play:before {
              content: "\f04b";
            }
            .fa-pause:before {
              content: "\f04c";
            }
            .fa-stop:before {
              content: "\f04d";
            }
            .fa-forward:before {
              content: "\f04e";
            }
            .fa-fast-forward:before {
              content: "\f050";
            }
            .fa-step-forward:before {
              content: "\f051";
            }
            .fa-eject:before {
              content: "\f052";
            }
            .fa-chevron-left:before {
              content: "\f053";
            }
            .fa-chevron-right:before {
              content: "\f054";
            }
            .fa-plus-circle:before {
              content: "\f055";
            }
            .fa-minus-circle:before {
              content: "\f056";
            }
            .fa-times-circle:before {
              content: "\f057";
            }
            .fa-check-circle:before {
              content: "\f058";
            }
            .fa-question-circle:before {
              content: "\f059";
            }
            .fa-info-circle:before {
              content: "\f05a";
            }
            .fa-crosshairs:before {
              content: "\f05b";
            }
            .fa-times-circle-o:before {
              content: "\f05c";
            }
            .fa-check-circle-o:before {
              content: "\f05d";
            }
            .fa-ban:before {
              content: "\f05e";
            }
            .fa-arrow-left:before {
              content: "\f060";
            }
            .fa-arrow-right:before {
              content: "\f061";
            }
            .fa-arrow-up:before {
              content: "\f062";
            }
            .fa-arrow-down:before {
              content: "\f063";
            }
            .fa-mail-forward:before,
            .fa-share:before {
              content: "\f064";
            }
            .fa-expand:before {
              content: "\f065";
            }
            .fa-compress:before {
              content: "\f066";
            }
            .fa-plus:before {
              content: "\f067";
            }
            .fa-minus:before {
              content: "\f068";
            }
            .fa-asterisk:before {
              content: "\f069";
            }
            .fa-exclamation-circle:before {
              content: "\f06a";
            }
            .fa-gift:before {
              content: "\f06b";
            }
            .fa-leaf:before {
              content: "\f06c";
            }
            .fa-fire:before {
              content: "\f06d";
            }
            .fa-eye:before {
              content: "\f06e";
            }
            .fa-eye-slash:before {
              content: "\f070";
            }
            .fa-warning:before,
            .fa-exclamation-triangle:before {
              content: "\f071";
            }
            .fa-plane:before {
              content: "\f072";
            }
            .fa-calendar:before {
              content: "\f073";
            }
            .fa-random:before {
              content: "\f074";
            }
            .fa-comment:before {
              content: "\f075";
            }
            .fa-magnet:before {
              content: "\f076";
            }
            .fa-chevron-up:before {
              content: "\f077";
            }
            .fa-chevron-down:before {
              content: "\f078";
            }
            .fa-retweet:before {
              content: "\f079";
            }
            .fa-shopping-cart:before {
              content: "\f07a";
            }
            .fa-folder:before {
              content: "\f07b";
            }
            .fa-folder-open:before {
              content: "\f07c";
            }
            .fa-arrows-v:before {
              content: "\f07d";
            }
            .fa-arrows-h:before {
              content: "\f07e";
            }
            .fa-bar-chart-o:before,
            .fa-bar-chart:before {
              content: "\f080";
            }
            .fa-twitter-square:before {
              content: "\f081";
            }
            .fa-facebook-square:before {
              content: "\f082";
            }
            .fa-camera-retro:before {
              content: "\f083";
            }
            .fa-key:before {
              content: "\f084";
            }
            .fa-gears:before,
            .fa-cogs:before {
              content: "\f085";
            }
            .fa-comments:before {
              content: "\f086";
            }
            .fa-thumbs-o-up:before {
              content: "\f087";
            }
            .fa-thumbs-o-down:before {
              content: "\f088";
            }
            .fa-star-half:before {
              content: "\f089";
            }
            .fa-heart-o:before {
              content: "\f08a";
            }
            .fa-sign-out:before {
              content: "\f08b";
            }
            .fa-linkedin-square:before {
              content: "\f08c";
            }
            .fa-thumb-tack:before {
              content: "\f08d";
            }
            .fa-external-link:before {
              content: "\f08e";
            }
            .fa-sign-in:before {
              content: "\f090";
            }
            .fa-trophy:before {
              content: "\f091";
            }
            .fa-github-square:before {
              content: "\f092";
            }
            .fa-upload:before {
              content: "\f093";
            }
            .fa-lemon-o:before {
              content: "\f094";
            }
            .fa-phone:before {
              content: "\f095";
            }
            .fa-square-o:before {
              content: "\f096";
            }
            .fa-bookmark-o:before {
              content: "\f097";
            }
            .fa-phone-square:before {
              content: "\f098";
            }
            .fa-twitter:before {
              content: "\f099";
            }
            .fa-facebook-f:before,
            .fa-facebook:before {
              content: "\f09a";
            }
            .fa-github:before {
              content: "\f09b";
            }
            .fa-unlock:before {
              content: "\f09c";
            }
            .fa-credit-card:before {
              content: "\f09d";
            }
            .fa-feed:before,
            .fa-rss:before {
              content: "\f09e";
            }
            .fa-hdd-o:before {
              content: "\f0a0";
            }
            .fa-bullhorn:before {
              content: "\f0a1";
            }
            .fa-bell:before {
              content: "\f0f3";
            }
            .fa-certificate:before {
              content: "\f0a3";
            }
            .fa-hand-o-right:before {
              content: "\f0a4";
            }
            .fa-hand-o-left:before {
              content: "\f0a5";
            }
            .fa-hand-o-up:before {
              content: "\f0a6";
            }
            .fa-hand-o-down:before {
              content: "\f0a7";
            }
            .fa-arrow-circle-left:before {
              content: "\f0a8";
            }
            .fa-arrow-circle-right:before {
              content: "\f0a9";
            }
            .fa-arrow-circle-up:before {
              content: "\f0aa";
            }
            .fa-arrow-circle-down:before {
              content: "\f0ab";
            }
            .fa-globe:before {
              content: "\f0ac";
            }
            .fa-wrench:before {
              content: "\f0ad";
            }
            .fa-tasks:before {
              content: "\f0ae";
            }
            .fa-filter:before {
              content: "\f0b0";
            }
            .fa-briefcase:before {
              content: "\f0b1";
            }
            .fa-arrows-alt:before {
              content: "\f0b2";
            }
            .fa-group:before,
            .fa-users:before {
              content: "\f0c0";
            }
            .fa-chain:before,
            .fa-link:before {
              content: "\f0c1";
            }
            .fa-cloud:before {
              content: "\f0c2";
            }
            .fa-flask:before {
              content: "\f0c3";
            }
            .fa-cut:before,
            .fa-scissors:before {
              content: "\f0c4";
            }
            .fa-copy:before,
            .fa-files-o:before {
              content: "\f0c5";
            }
            .fa-paperclip:before {
              content: "\f0c6";
            }
            .fa-save:before,
            .fa-floppy-o:before {
              content: "\f0c7";
            }
            .fa-square:before {
              content: "\f0c8";
            }
            .fa-navicon:before,
            .fa-reorder:before,
            .fa-bars:before {
              content: "\f0c9";
            }
            .fa-list-ul:before {
              content: "\f0ca";
            }
            .fa-list-ol:before {
              content: "\f0cb";
            }
            .fa-strikethrough:before {
              content: "\f0cc";
            }
            .fa-underline:before {
              content: "\f0cd";
            }
            .fa-table:before {
              content: "\f0ce";
            }
            .fa-magic:before {
              content: "\f0d0";
            }
            .fa-truck:before {
              content: "\f0d1";
            }
            .fa-pinterest:before {
              content: "\f0d2";
            }
            .fa-pinterest-square:before {
              content: "\f0d3";
            }
            .fa-google-plus-square:before {
              content: "\f0d4";
            }
            .fa-google-plus:before {
              content: "\f0d5";
            }
            .fa-money:before {
              content: "\f0d6";
            }
            .fa-caret-down:before {
              content: "\f0d7";
            }
            .fa-caret-up:before {
              content: "\f0d8";
            }
            .fa-caret-left:before {
              content: "\f0d9";
            }
            .fa-caret-right:before {
              content: "\f0da";
            }
            .fa-columns:before {
              content: "\f0db";
            }
            .fa-unsorted:before,
            .fa-sort:before {
              content: "\f0dc";
            }
            .fa-sort-down:before,
            .fa-sort-desc:before {
              content: "\f0dd";
            }
            .fa-sort-up:before,
            .fa-sort-asc:before {
              content: "\f0de";
            }
            .fa-envelope:before {
              content: "\f0e0";
            }
            .fa-linkedin:before {
              content: "\f0e1";
            }
            .fa-rotate-left:before,
            .fa-undo:before {
              content: "\f0e2";
            }
            .fa-legal:before,
            .fa-gavel:before {
              content: "\f0e3";
            }
            .fa-dashboard:before,
            .fa-tachometer:before {
              content: "\f0e4";
            }
            .fa-comment-o:before {
              content: "\f0e5";
            }
            .fa-comments-o:before {
              content: "\f0e6";
            }
            .fa-flash:before,
            .fa-bolt:before {
              content: "\f0e7";
            }
            .fa-sitemap:before {
              content: "\f0e8";
            }
            .fa-umbrella:before {
              content: "\f0e9";
            }
            .fa-paste:before,
            .fa-clipboard:before {
              content: "\f0ea";
            }
            .fa-lightbulb-o:before {
              content: "\f0eb";
            }
            .fa-exchange:before {
              content: "\f0ec";
            }
            .fa-cloud-download:before {
              content: "\f0ed";
            }
            .fa-cloud-upload:before {
              content: "\f0ee";
            }
            .fa-user-md:before {
              content: "\f0f0";
            }
            .fa-stethoscope:before {
              content: "\f0f1";
            }
            .fa-suitcase:before {
              content: "\f0f2";
            }
            .fa-bell-o:before {
              content: "\f0a2";
            }
            .fa-coffee:before {
              content: "\f0f4";
            }
            .fa-cutlery:before {
              content: "\f0f5";
            }
            .fa-file-text-o:before {
              content: "\f0f6";
            }
            .fa-building-o:before {
              content: "\f0f7";
            }
            .fa-hospital-o:before {
              content: "\f0f8";
            }
            .fa-ambulance:before {
              content: "\f0f9";
            }
            .fa-medkit:before {
              content: "\f0fa";
            }
            .fa-fighter-jet:before {
              content: "\f0fb";
            }
            .fa-beer:before {
              content: "\f0fc";
            }
            .fa-h-square:before {
              content: "\f0fd";
            }
            .fa-plus-square:before {
              content: "\f0fe";
            }
            .fa-angle-double-left:before {
              content: "\f100";
            }
            .fa-angle-double-right:before {
              content: "\f101";
            }
            .fa-angle-double-up:before {
              content: "\f102";
            }
            .fa-angle-double-down:before {
              content: "\f103";
            }
            .fa-angle-left:before {
              content: "\f104";
            }
            .fa-angle-right:before {
              content: "\f105";
            }
            .fa-angle-up:before {
              content: "\f106";
            }
            .fa-angle-down:before {
              content: "\f107";
            }
            .fa-desktop:before {
              content: "\f108";
            }
            .fa-laptop:before {
              content: "\f109";
            }
            .fa-tablet:before {
              content: "\f10a";
            }
            .fa-mobile-phone:before,
            .fa-mobile:before {
              content: "\f10b";
            }
            .fa-circle-o:before {
              content: "\f10c";
            }
            .fa-quote-left:before {
              content: "\f10d";
            }
            .fa-quote-right:before {
              content: "\f10e";
            }
            .fa-spinner:before {
              content: "\f110";
            }
            .fa-circle:before {
              content: "\f111";
            }
            .fa-mail-reply:before,
            .fa-reply:before {
              content: "\f112";
            }
            .fa-github-alt:before {
              content: "\f113";
            }
            .fa-folder-o:before {
              content: "\f114";
            }
            .fa-folder-open-o:before {
              content: "\f115";
            }
            .fa-smile-o:before {
              content: "\f118";
            }
            .fa-frown-o:before {
              content: "\f119";
            }
            .fa-meh-o:before {
              content: "\f11a";
            }
            .fa-gamepad:before {
              content: "\f11b";
            }
            .fa-keyboard-o:before {
              content: "\f11c";
            }
            .fa-flag-o:before {
              content: "\f11d";
            }
            .fa-flag-checkered:before {
              content: "\f11e";
            }
            .fa-terminal:before {
              content: "\f120";
            }
            .fa-code:before {
              content: "\f121";
            }
            .fa-mail-reply-all:before,
            .fa-reply-all:before {
              content: "\f122";
            }
            .fa-star-half-empty:before,
            .fa-star-half-full:before,
            .fa-star-half-o:before {
              content: "\f123";
            }
            .fa-location-arrow:before {
              content: "\f124";
            }
            .fa-crop:before {
              content: "\f125";
            }
            .fa-code-fork:before {
              content: "\f126";
            }
            .fa-unlink:before,
            .fa-chain-broken:before {
              content: "\f127";
            }
            .fa-question:before {
              content: "\f128";
            }
            .fa-info:before {
              content: "\f129";
            }
            .fa-exclamation:before {
              content: "\f12a";
            }
            .fa-superscript:before {
              content: "\f12b";
            }
            .fa-subscript:before {
              content: "\f12c";
            }
            .fa-eraser:before {
              content: "\f12d";
            }
            .fa-puzzle-piece:before {
              content: "\f12e";
            }
            .fa-microphone:before {
              content: "\f130";
            }
            .fa-microphone-slash:before {
              content: "\f131";
            }
            .fa-shield:before {
              content: "\f132";
            }
            .fa-calendar-o:before {
              content: "\f133";
            }
            .fa-fire-extinguisher:before {
              content: "\f134";
            }
            .fa-rocket:before {
              content: "\f135";
            }
            .fa-maxcdn:before {
              content: "\f136";
            }
            .fa-chevron-circle-left:before {
              content: "\f137";
            }
            .fa-chevron-circle-right:before {
              content: "\f138";
            }
            .fa-chevron-circle-up:before {
              content: "\f139";
            }
            .fa-chevron-circle-down:before {
              content: "\f13a";
            }
            .fa-html5:before {
              content: "\f13b";
            }
            .fa-css3:before {
              content: "\f13c";
            }
            .fa-anchor:before {
              content: "\f13d";
            }
            .fa-unlock-alt:before {
              content: "\f13e";
            }
            .fa-bullseye:before {
              content: "\f140";
            }
            .fa-ellipsis-h:before {
              content: "\f141";
            }
            .fa-ellipsis-v:before {
              content: "\f142";
            }
            .fa-rss-square:before {
              content: "\f143";
            }
            .fa-play-circle:before {
              content: "\f144";
            }
            .fa-ticket:before {
              content: "\f145";
            }
            .fa-minus-square:before {
              content: "\f146";
            }
            .fa-minus-square-o:before {
              content: "\f147";
            }
            .fa-level-up:before {
              content: "\f148";
            }
            .fa-level-down:before {
              content: "\f149";
            }
            .fa-check-square:before {
              content: "\f14a";
            }
            .fa-pencil-square:before {
              content: "\f14b";
            }
            .fa-external-link-square:before {
              content: "\f14c";
            }
            .fa-share-square:before {
              content: "\f14d";
            }
            .fa-compass:before {
              content: "\f14e";
            }
            .fa-toggle-down:before,
            .fa-caret-square-o-down:before {
              content: "\f150";
            }
            .fa-toggle-up:before,
            .fa-caret-square-o-up:before {
              content: "\f151";
            }
            .fa-toggle-right:before,
            .fa-caret-square-o-right:before {
              content: "\f152";
            }
            .fa-euro:before,
            .fa-eur:before {
              content: "\f153";
            }
            .fa-gbp:before {
              content: "\f154";
            }
            .fa-dollar:before,
            .fa-usd:before {
              content: "\f155";
            }
            .fa-rupee:before,
            .fa-inr:before {
              content: "\f156";
            }
            .fa-cny:before,
            .fa-rmb:before,
            .fa-yen:before,
            .fa-jpy:before {
              content: "\f157";
            }
            .fa-ruble:before,
            .fa-rouble:before,
            .fa-rub:before {
              content: "\f158";
            }
            .fa-won:before,
            .fa-krw:before {
              content: "\f159";
            }
            .fa-bitcoin:before,
            .fa-btc:before {
              content: "\f15a";
            }
            .fa-file:before {
              content: "\f15b";
            }
            .fa-file-text:before {
              content: "\f15c";
            }
            .fa-sort-alpha-asc:before {
              content: "\f15d";
            }
            .fa-sort-alpha-desc:before {
              content: "\f15e";
            }
            .fa-sort-amount-asc:before {
              content: "\f160";
            }
            .fa-sort-amount-desc:before {
              content: "\f161";
            }
            .fa-sort-numeric-asc:before {
              content: "\f162";
            }
            .fa-sort-numeric-desc:before {
              content: "\f163";
            }
            .fa-thumbs-up:before {
              content: "\f164";
            }
            .fa-thumbs-down:before {
              content: "\f165";
            }
            .fa-youtube-square:before {
              content: "\f166";
            }
            .fa-youtube:before {
              content: "\f167";
            }
            .fa-xing:before {
              content: "\f168";
            }
            .fa-xing-square:before {
              content: "\f169";
            }
            .fa-youtube-play:before {
              content: "\f16a";
            }
            .fa-dropbox:before {
              content: "\f16b";
            }
            .fa-stack-overflow:before {
              content: "\f16c";
            }
            .fa-instagram:before {
              content: "\f16d";
            }
            .fa-flickr:before {
              content: "\f16e";
            }
            .fa-adn:before {
              content: "\f170";
            }
            .fa-bitbucket:before {
              content: "\f171";
            }
            .fa-bitbucket-square:before {
              content: "\f172";
            }
            .fa-tumblr:before {
              content: "\f173";
            }
            .fa-tumblr-square:before {
              content: "\f174";
            }
            .fa-long-arrow-down:before {
              content: "\f175";
            }
            .fa-long-arrow-up:before {
              content: "\f176";
            }
            .fa-long-arrow-left:before {
              content: "\f177";
            }
            .fa-long-arrow-right:before {
              content: "\f178";
            }
            .fa-apple:before {
              content: "\f179";
            }
            .fa-windows:before {
              content: "\f17a";
            }
            .fa-android:before {
              content: "\f17b";
            }
            .fa-linux:before {
              content: "\f17c";
            }
            .fa-dribbble:before {
              content: "\f17d";
            }
            .fa-skype:before {
              content: "\f17e";
            }
            .fa-foursquare:before {
              content: "\f180";
            }
            .fa-trello:before {
              content: "\f181";
            }
            .fa-female:before {
              content: "\f182";
            }
            .fa-male:before {
              content: "\f183";
            }
            .fa-gittip:before,
            .fa-gratipay:before {
              content: "\f184";
            }
            .fa-sun-o:before {
              content: "\f185";
            }
            .fa-moon-o:before {
              content: "\f186";
            }
            .fa-archive:before {
              content: "\f187";
            }
            .fa-bug:before {
              content: "\f188";
            }
            .fa-vk:before {
              content: "\f189";
            }
            .fa-weibo:before {
              content: "\f18a";
            }
            .fa-renren:before {
              content: "\f18b";
            }
            .fa-pagelines:before {
              content: "\f18c";
            }
            .fa-stack-exchange:before {
              content: "\f18d";
            }
            .fa-arrow-circle-o-right:before {
              content: "\f18e";
            }
            .fa-arrow-circle-o-left:before {
              content: "\f190";
            }
            .fa-toggle-left:before,
            .fa-caret-square-o-left:before {
              content: "\f191";
            }
            .fa-dot-circle-o:before {
              content: "\f192";
            }
            .fa-wheelchair:before {
              content: "\f193";
            }
            .fa-vimeo-square:before {
              content: "\f194";
            }
            .fa-turkish-lira:before,
            .fa-try:before {
              content: "\f195";
            }
            .fa-plus-square-o:before {
              content: "\f196";
            }
            .fa-space-shuttle:before {
              content: "\f197";
            }
            .fa-slack:before {
              content: "\f198";
            }
            .fa-envelope-square:before {
              content: "\f199";
            }
            .fa-wordpress:before {
              content: "\f19a";
            }
            .fa-openid:before {
              content: "\f19b";
            }
            .fa-institution:before,
            .fa-bank:before,
            .fa-university:before {
              content: "\f19c";
            }
            .fa-mortar-board:before,
            .fa-graduation-cap:before {
              content: "\f19d";
            }
            .fa-yahoo:before {
              content: "\f19e";
            }
            .fa-google:before {
              content: "\f1a0";
            }
            .fa-reddit:before {
              content: "\f1a1";
            }
            .fa-reddit-square:before {
              content: "\f1a2";
            }
            .fa-stumbleupon-circle:before {
              content: "\f1a3";
            }
            .fa-stumbleupon:before {
              content: "\f1a4";
            }
            .fa-delicious:before {
              content: "\f1a5";
            }
            .fa-digg:before {
              content: "\f1a6";
            }
            .fa-pied-piper-pp:before {
              content: "\f1a7";
            }
            .fa-pied-piper-alt:before {
              content: "\f1a8";
            }
            .fa-drupal:before {
              content: "\f1a9";
            }
            .fa-joomla:before {
              content: "\f1aa";
            }
            .fa-language:before {
              content: "\f1ab";
            }
            .fa-fax:before {
              content: "\f1ac";
            }
            .fa-building:before {
              content: "\f1ad";
            }
            .fa-child:before {
              content: "\f1ae";
            }
            .fa-paw:before {
              content: "\f1b0";
            }
            .fa-spoon:before {
              content: "\f1b1";
            }
            .fa-cube:before {
              content: "\f1b2";
            }
            .fa-cubes:before {
              content: "\f1b3";
            }
            .fa-behance:before {
              content: "\f1b4";
            }
            .fa-behance-square:before {
              content: "\f1b5";
            }
            .fa-steam:before {
              content: "\f1b6";
            }
            .fa-steam-square:before {
              content: "\f1b7";
            }
            .fa-recycle:before {
              content: "\f1b8";
            }
            .fa-automobile:before,
            .fa-car:before {
              content: "\f1b9";
            }
            .fa-cab:before,
            .fa-taxi:before {
              content: "\f1ba";
            }
            .fa-tree:before {
              content: "\f1bb";
            }
            .fa-spotify:before {
              content: "\f1bc";
            }
            .fa-deviantart:before {
              content: "\f1bd";
            }
            .fa-soundcloud:before {
              content: "\f1be";
            }
            .fa-database:before {
              content: "\f1c0";
            }
            .fa-file-pdf-o:before {
              content: "\f1c1";
            }
            .fa-file-word-o:before {
              content: "\f1c2";
            }
            .fa-file-excel-o:before {
              content: "\f1c3";
            }
            .fa-file-powerpoint-o:before {
              content: "\f1c4";
            }
            .fa-file-photo-o:before,
            .fa-file-picture-o:before,
            .fa-file-image-o:before {
              content: "\f1c5";
            }
            .fa-file-zip-o:before,
            .fa-file-archive-o:before {
              content: "\f1c6";
            }
            .fa-file-sound-o:before,
            .fa-file-audio-o:before {
              content: "\f1c7";
            }
            .fa-file-movie-o:before,
            .fa-file-video-o:before {
              content: "\f1c8";
            }
            .fa-file-code-o:before {
              content: "\f1c9";
            }
            .fa-vine:before {
              content: "\f1ca";
            }
            .fa-codepen:before {
              content: "\f1cb";
            }
            .fa-jsfiddle:before {
              content: "\f1cc";
            }
            .fa-life-bouy:before,
            .fa-life-buoy:before,
            .fa-life-saver:before,
            .fa-support:before,
            .fa-life-ring:before {
              content: "\f1cd";
            }
            .fa-circle-o-notch:before {
              content: "\f1ce";
            }
            .fa-ra:before,
            .fa-resistance:before,
            .fa-rebel:before {
              content: "\f1d0";
            }
            .fa-ge:before,
            .fa-empire:before {
              content: "\f1d1";
            }
            .fa-git-square:before {
              content: "\f1d2";
            }
            .fa-git:before {
              content: "\f1d3";
            }
            .fa-y-combinator-square:before,
            .fa-yc-square:before,
            .fa-hacker-news:before {
              content: "\f1d4";
            }
            .fa-tencent-weibo:before {
              content: "\f1d5";
            }
            .fa-qq:before {
              content: "\f1d6";
            }
            .fa-wechat:before,
            .fa-weixin:before {
              content: "\f1d7";
            }
            .fa-send:before,
            .fa-paper-plane:before {
              content: "\f1d8";
            }
            .fa-send-o:before,
            .fa-paper-plane-o:before {
              content: "\f1d9";
            }
            .fa-history:before {
              content: "\f1da";
            }
            .fa-circle-thin:before {
              content: "\f1db";
            }
            .fa-header:before {
              content: "\f1dc";
            }
            .fa-paragraph:before {
              content: "\f1dd";
            }
            .fa-sliders:before {
              content: "\f1de";
            }
            .fa-share-alt:before {
              content: "\f1e0";
            }
            .fa-share-alt-square:before {
              content: "\f1e1";
            }
            .fa-bomb:before {
              content: "\f1e2";
            }
            .fa-soccer-ball-o:before,
            .fa-futbol-o:before {
              content: "\f1e3";
            }
            .fa-tty:before {
              content: "\f1e4";
            }
            .fa-binoculars:before {
              content: "\f1e5";
            }
            .fa-plug:before {
              content: "\f1e6";
            }
            .fa-slideshare:before {
              content: "\f1e7";
            }
            .fa-twitch:before {
              content: "\f1e8";
            }
            .fa-yelp:before {
              content: "\f1e9";
            }
            .fa-newspaper-o:before {
              content: "\f1ea";
            }
            .fa-wifi:before {
              content: "\f1eb";
            }
            .fa-calculator:before {
              content: "\f1ec";
            }
            .fa-paypal:before {
              content: "\f1ed";
            }
            .fa-google-wallet:before {
              content: "\f1ee";
            }
            .fa-cc-visa:before {
              content: "\f1f0";
            }
            .fa-cc-mastercard:before {
              content: "\f1f1";
            }
            .fa-cc-discover:before {
              content: "\f1f2";
            }
            .fa-cc-amex:before {
              content: "\f1f3";
            }
            .fa-cc-paypal:before {
              content: "\f1f4";
            }
            .fa-cc-stripe:before {
              content: "\f1f5";
            }
            .fa-bell-slash:before {
              content: "\f1f6";
            }
            .fa-bell-slash-o:before {
              content: "\f1f7";
            }
            .fa-trash:before {
              content: "\f1f8";
            }
            .fa-copyright:before {
              content: "\f1f9";
            }
            .fa-at:before {
              content: "\f1fa";
            }
            .fa-eyedropper:before {
              content: "\f1fb";
            }
            .fa-paint-brush:before {
              content: "\f1fc";
            }
            .fa-birthday-cake:before {
              content: "\f1fd";
            }
            .fa-area-chart:before {
              content: "\f1fe";
            }
            .fa-pie-chart:before {
              content: "\f200";
            }
            .fa-line-chart:before {
              content: "\f201";
            }
            .fa-lastfm:before {
              content: "\f202";
            }
            .fa-lastfm-square:before {
              content: "\f203";
            }
            .fa-toggle-off:before {
              content: "\f204";
            }
            .fa-toggle-on:before {
              content: "\f205";
            }
            .fa-bicycle:before {
              content: "\f206";
            }
            .fa-bus:before {
              content: "\f207";
            }
            .fa-ioxhost:before {
              content: "\f208";
            }
            .fa-angellist:before {
              content: "\f209";
            }
            .fa-cc:before {
              content: "\f20a";
            }
            .fa-shekel:before,
            .fa-sheqel:before,
            .fa-ils:before {
              content: "\f20b";
            }
            .fa-meanpath:before {
              content: "\f20c";
            }
            .fa-buysellads:before {
              content: "\f20d";
            }
            .fa-connectdevelop:before {
              content: "\f20e";
            }
            .fa-dashcube:before {
              content: "\f210";
            }
            .fa-forumbee:before {
              content: "\f211";
            }
            .fa-leanpub:before {
              content: "\f212";
            }
            .fa-sellsy:before {
              content: "\f213";
            }
            .fa-shirtsinbulk:before {
              content: "\f214";
            }
            .fa-simplybuilt:before {
              content: "\f215";
            }
            .fa-skyatlas:before {
              content: "\f216";
            }
            .fa-cart-plus:before {
              content: "\f217";
            }
            .fa-cart-arrow-down:before {
              content: "\f218";
            }
            .fa-diamond:before {
              content: "\f219";
            }
            .fa-ship:before {
              content: "\f21a";
            }
            .fa-user-secret:before {
              content: "\f21b";
            }
            .fa-motorcycle:before {
              content: "\f21c";
            }
            .fa-street-view:before {
              content: "\f21d";
            }
            .fa-heartbeat:before {
              content: "\f21e";
            }
            .fa-venus:before {
              content: "\f221";
            }
            .fa-mars:before {
              content: "\f222";
            }
            .fa-mercury:before {
              content: "\f223";
            }
            .fa-intersex:before,
            .fa-transgender:before {
              content: "\f224";
            }
            .fa-transgender-alt:before {
              content: "\f225";
            }
            .fa-venus-double:before {
              content: "\f226";
            }
            .fa-mars-double:before {
              content: "\f227";
            }
            .fa-venus-mars:before {
              content: "\f228";
            }
            .fa-mars-stroke:before {
              content: "\f229";
            }
            .fa-mars-stroke-v:before {
              content: "\f22a";
            }
            .fa-mars-stroke-h:before {
              content: "\f22b";
            }
            .fa-neuter:before {
              content: "\f22c";
            }
            .fa-genderless:before {
              content: "\f22d";
            }
            .fa-facebook-official:before {
              content: "\f230";
            }
            .fa-pinterest-p:before {
              content: "\f231";
            }
            .fa-whatsapp:before {
              content: "\f232";
            }
            .fa-server:before {
              content: "\f233";
            }
            .fa-user-plus:before {
              content: "\f234";
            }
            .fa-user-times:before {
              content: "\f235";
            }
            .fa-hotel:before,
            .fa-bed:before {
              content: "\f236";
            }
            .fa-viacoin:before {
              content: "\f237";
            }
            .fa-train:before {
              content: "\f238";
            }
            .fa-subway:before {
              content: "\f239";
            }
            .fa-medium:before {
              content: "\f23a";
            }
            .fa-yc:before,
            .fa-y-combinator:before {
              content: "\f23b";
            }
            .fa-optin-monster:before {
              content: "\f23c";
            }
            .fa-opencart:before {
              content: "\f23d";
            }
            .fa-expeditedssl:before {
              content: "\f23e";
            }
            .fa-battery-4:before,
            .fa-battery:before,
            .fa-battery-full:before {
              content: "\f240";
            }
            .fa-battery-3:before,
            .fa-battery-three-quarters:before {
              content: "\f241";
            }
            .fa-battery-2:before,
            .fa-battery-half:before {
              content: "\f242";
            }
            .fa-battery-1:before,
            .fa-battery-quarter:before {
              content: "\f243";
            }
            .fa-battery-0:before,
            .fa-battery-empty:before {
              content: "\f244";
            }
            .fa-mouse-pointer:before {
              content: "\f245";
            }
            .fa-i-cursor:before {
              content: "\f246";
            }
            .fa-object-group:before {
              content: "\f247";
            }
            .fa-object-ungroup:before {
              content: "\f248";
            }
            .fa-sticky-note:before {
              content: "\f249";
            }
            .fa-sticky-note-o:before {
              content: "\f24a";
            }
            .fa-cc-jcb:before {
              content: "\f24b";
            }
            .fa-cc-diners-club:before {
              content: "\f24c";
            }
            .fa-clone:before {
              content: "\f24d";
            }
            .fa-balance-scale:before {
              content: "\f24e";
            }
            .fa-hourglass-o:before {
              content: "\f250";
            }
            .fa-hourglass-1:before,
            .fa-hourglass-start:before {
              content: "\f251";
            }
            .fa-hourglass-2:before,
            .fa-hourglass-half:before {
              content: "\f252";
            }
            .fa-hourglass-3:before,
            .fa-hourglass-end:before {
              content: "\f253";
            }
            .fa-hourglass:before {
              content: "\f254";
            }
            .fa-hand-grab-o:before,
            .fa-hand-rock-o:before {
              content: "\f255";
            }
            .fa-hand-stop-o:before,
            .fa-hand-paper-o:before {
              content: "\f256";
            }
            .fa-hand-scissors-o:before {
              content: "\f257";
            }
            .fa-hand-lizard-o:before {
              content: "\f258";
            }
            .fa-hand-spock-o:before {
              content: "\f259";
            }
            .fa-hand-pointer-o:before {
              content: "\f25a";
            }
            .fa-hand-peace-o:before {
              content: "\f25b";
            }
            .fa-trademark:before {
              content: "\f25c";
            }
            .fa-registered:before {
              content: "\f25d";
            }
            .fa-creative-commons:before {
              content: "\f25e";
            }
            .fa-gg:before {
              content: "\f260";
            }
            .fa-gg-circle:before {
              content: "\f261";
            }
            .fa-tripadvisor:before {
              content: "\f262";
            }
            .fa-odnoklassniki:before {
              content: "\f263";
            }
            .fa-odnoklassniki-square:before {
              content: "\f264";
            }
            .fa-get-pocket:before {
              content: "\f265";
            }
            .fa-wikipedia-w:before {
              content: "\f266";
            }
            .fa-safari:before {
              content: "\f267";
            }
            .fa-chrome:before {
              content: "\f268";
            }
            .fa-firefox:before {
              content: "\f269";
            }
            .fa-opera:before {
              content: "\f26a";
            }
            .fa-internet-explorer:before {
              content: "\f26b";
            }
            .fa-tv:before,
            .fa-television:before {
              content: "\f26c";
            }
            .fa-contao:before {
              content: "\f26d";
            }
            .fa-500px:before {
              content: "\f26e";
            }
            .fa-amazon:before {
              content: "\f270";
            }
            .fa-calendar-plus-o:before {
              content: "\f271";
            }
            .fa-calendar-minus-o:before {
              content: "\f272";
            }
            .fa-calendar-times-o:before {
              content: "\f273";
            }
            .fa-calendar-check-o:before {
              content: "\f274";
            }
            .fa-industry:before {
              content: "\f275";
            }
            .fa-map-pin:before {
              content: "\f276";
            }
            .fa-map-signs:before {
              content: "\f277";
            }
            .fa-map-o:before {
              content: "\f278";
            }
            .fa-map:before {
              content: "\f279";
            }
            .fa-commenting:before {
              content: "\f27a";
            }
            .fa-commenting-o:before {
              content: "\f27b";
            }
            .fa-houzz:before {
              content: "\f27c";
            }
            .fa-vimeo:before {
              content: "\f27d";
            }
            .fa-black-tie:before {
              content: "\f27e";
            }
            .fa-fonticons:before {
              content: "\f280";
            }
            .fa-reddit-alien:before {
              content: "\f281";
            }
            .fa-edge:before {
              content: "\f282";
            }
            .fa-credit-card-alt:before {
              content: "\f283";
            }
            .fa-codiepie:before {
              content: "\f284";
            }
            .fa-modx:before {
              content: "\f285";
            }
            .fa-fort-awesome:before {
              content: "\f286";
            }
            .fa-usb:before {
              content: "\f287";
            }
            .fa-product-hunt:before {
              content: "\f288";
            }
            .fa-mixcloud:before {
              content: "\f289";
            }
            .fa-scribd:before {
              content: "\f28a";
            }
            .fa-pause-circle:before {
              content: "\f28b";
            }
            .fa-pause-circle-o:before {
              content: "\f28c";
            }
            .fa-stop-circle:before {
              content: "\f28d";
            }
            .fa-stop-circle-o:before {
              content: "\f28e";
            }
            .fa-shopping-bag:before {
              content: "\f290";
            }
            .fa-shopping-basket:before {
              content: "\f291";
            }
            .fa-hashtag:before {
              content: "\f292";
            }
            .fa-bluetooth:before {
              content: "\f293";
            }
            .fa-bluetooth-b:before {
              content: "\f294";
            }
            .fa-percent:before {
              content: "\f295";
            }
            .fa-gitlab:before {
              content: "\f296";
            }
            .fa-wpbeginner:before {
              content: "\f297";
            }
            .fa-wpforms:before {
              content: "\f298";
            }
            .fa-envira:before {
              content: "\f299";
            }
            .fa-universal-access:before {
              content: "\f29a";
            }
            .fa-wheelchair-alt:before {
              content: "\f29b";
            }
            .fa-question-circle-o:before {
              content: "\f29c";
            }
            .fa-blind:before {
              content: "\f29d";
            }
            .fa-audio-description:before {
              content: "\f29e";
            }
            .fa-volume-control-phone:before {
              content: "\f2a0";
            }
            .fa-braille:before {
              content: "\f2a1";
            }
            .fa-assistive-listening-systems:before {
              content: "\f2a2";
            }
            .fa-asl-interpreting:before,
            .fa-american-sign-language-interpreting:before {
              content: "\f2a3";
            }
            .fa-deafness:before,
            .fa-hard-of-hearing:before,
            .fa-deaf:before {
              content: "\f2a4";
            }
            .fa-glide:before {
              content: "\f2a5";
            }
            .fa-glide-g:before {
              content: "\f2a6";
            }
            .fa-signing:before,
            .fa-sign-language:before {
              content: "\f2a7";
            }
            .fa-low-vision:before {
              content: "\f2a8";
            }
            .fa-viadeo:before {
              content: "\f2a9";
            }
            .fa-viadeo-square:before {
              content: "\f2aa";
            }
            .fa-snapchat:before {
              content: "\f2ab";
            }
            .fa-snapchat-ghost:before {
              content: "\f2ac";
            }
            .fa-snapchat-square:before {
              content: "\f2ad";
            }
            .fa-pied-piper:before {
              content: "\f2ae";
            }
            .fa-first-order:before {
              content: "\f2b0";
            }
            .fa-yoast:before {
              content: "\f2b1";
            }
            .fa-themeisle:before {
              content: "\f2b2";
            }
            .fa-google-plus-circle:before,
            .fa-google-plus-official:before {
              content: "\f2b3";
            }
            .fa-fa:before,
            .fa-font-awesome:before {
              content: "\f2b4";
            }
            .fa-handshake-o:before {
              content: "\f2b5";
            }
            .fa-envelope-open:before {
              content: "\f2b6";
            }
            .fa-envelope-open-o:before {
              content: "\f2b7";
            }
            .fa-linode:before {
              content: "\f2b8";
            }
            .fa-address-book:before {
              content: "\f2b9";
            }
            .fa-address-book-o:before {
              content: "\f2ba";
            }
            .fa-vcard:before,
            .fa-address-card:before {
              content: "\f2bb";
            }
            .fa-vcard-o:before,
            .fa-address-card-o:before {
              content: "\f2bc";
            }
            .fa-user-circle:before {
              content: "\f2bd";
            }
            .fa-user-circle-o:before {
              content: "\f2be";
            }
            .fa-user-o:before {
              content: "\f2c0";
            }
            .fa-id-badge:before {
              content: "\f2c1";
            }
            .fa-drivers-license:before,
            .fa-id-card:before {
              content: "\f2c2";
            }
            .fa-drivers-license-o:before,
            .fa-id-card-o:before {
              content: "\f2c3";
            }
            .fa-quora:before {
              content: "\f2c4";
            }
            .fa-free-code-camp:before {
              content: "\f2c5";
            }
            .fa-telegram:before {
              content: "\f2c6";
            }
            .fa-thermometer-4:before,
            .fa-thermometer:before,
            .fa-thermometer-full:before {
              content: "\f2c7";
            }
            .fa-thermometer-3:before,
            .fa-thermometer-three-quarters:before {
              content: "\f2c8";
            }
            .fa-thermometer-2:before,
            .fa-thermometer-half:before {
              content: "\f2c9";
            }
            .fa-thermometer-1:before,
            .fa-thermometer-quarter:before {
              content: "\f2ca";
            }
            .fa-thermometer-0:before,
            .fa-thermometer-empty:before {
              content: "\f2cb";
            }
            .fa-shower:before {
              content: "\f2cc";
            }
            .fa-bathtub:before,
            .fa-s15:before,
            .fa-bath:before {
              content: "\f2cd";
            }
            .fa-podcast:before {
              content: "\f2ce";
            }
            .fa-window-maximize:before {
              content: "\f2d0";
            }
            .fa-window-minimize:before {
              content: "\f2d1";
            }
            .fa-window-restore:before {
              content: "\f2d2";
            }
            .fa-times-rectangle:before,
            .fa-window-close:before {
              content: "\f2d3";
            }
            .fa-times-rectangle-o:before,
            .fa-window-close-o:before {
              content: "\f2d4";
            }
            .fa-bandcamp:before {
              content: "\f2d5";
            }
            .fa-grav:before {
              content: "\f2d6";
            }
            .fa-etsy:before {
              content: "\f2d7";
            }
            .fa-imdb:before {
              content: "\f2d8";
            }
            .fa-ravelry:before {
              content: "\f2d9";
            }
            .fa-eercast:before {
              content: "\f2da";
            }
            .fa-microchip:before {
              content: "\f2db";
            }
            .fa-snowflake-o:before {
              content: "\f2dc";
            }
            .fa-superpowers:before {
              content: "\f2dd";
            }
            .fa-wpexplorer:before {
              content: "\f2de";
            }
            .fa-meetup:before {
              content: "\f2e0";
            }
            .sr-only {
              position: absolute;
              width: 1px;
              height: 1px;
              padding: 0;
              margin: -1px;
              overflow: hidden;
              clip: rect(0, 0, 0, 0);
              border: 0;
            }
            .sr-only-focusable:active,
            .sr-only-focusable:focus {
              position: static;
              width: auto;
              height: auto;
              margin: 0;
              overflow: visible;
              clip: auto;
            }

             </style>   
        
        <!-- Font Awesome Ends -->
        
        <!-- Bootstrap Css Starts -->

            <!-- File Name bootstrap.min.css -->

            <style type="text/css">
                
                    /*!
 * Bootstrap v4.4.1 (https://getbootstrap.com/)
 * Copyright 2011-2019 The Bootstrap Authors
 * Copyright 2011-2019 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 */:root{--blue:#3d8ef8;--indigo:#564ab1;--purple:#6f42c1;--pink:#e83e8c;--red:#fb4d53;--orange:#f1734f;--yellow:#f1b44c;--green:#11c46e;--teal:#008080;--cyan:#0db4d6;--white:#fff;--gray:#7c8a96;--gray-dark:#343a40;--primary:#3d8ef8;--secondary:#7c8a96;--success:#11c46e;--info:#0db4d6;--warning:#f1b44c;--danger:#fb4d53;--light:#eff2f7;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:"Rubik",sans-serif;--font-family-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}*,::after,::before{-webkit-box-sizing:border-box;box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-webkit-tap-highlight-color:transparent}article,aside,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}body{margin:0;font-family:Rubik,sans-serif;font-size:.85rem;font-weight:400;line-height:1.5;color:#505d69;text-align:left;background-color:#f4f8f9}[tabindex="-1"]:focus:not(:focus-visible){outline:0!important}hr{-webkit-box-sizing:content-box;box-sizing:content-box;height:0;overflow:visible}h1,h2,h3,h4,h5,h6{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}abbr[data-original-title],abbr[title]{text-decoration:underline;-webkit-text-decoration:underline dotted;text-decoration:underline dotted;cursor:help;border-bottom:0;-webkit-text-decoration-skip-ink:none;text-decoration-skip-ink:none}address{margin-bottom:1rem;font-style:normal;line-height:inherit}dl,ol,ul{margin-top:0;margin-bottom:1rem}ol ol,ol ul,ul ol,ul ul{margin-bottom:0}dt{font-weight:500}dd{margin-bottom:.5rem;margin-left:0}blockquote{margin:0 0 1rem}b,strong{font-weight:bolder}small{font-size:80%}sub,sup{position:relative;font-size:75%;line-height:0;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}a{color:#3d8ef8;text-decoration:none;background-color:transparent}a:hover{color:#0866e0;text-decoration:underline}a:not([href]){color:inherit;text-decoration:none}a:not([href]):hover{color:inherit;text-decoration:none}code,kbd,pre,samp{font-family:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;font-size:1em}pre{margin-top:0;margin-bottom:1rem;overflow:auto}figure{margin:0 0 1rem}img{vertical-align:middle;border-style:none}svg{overflow:hidden;vertical-align:middle}table{border-collapse:collapse}caption{padding-top:.75rem;padding-bottom:.75rem;color:#7c8a96;text-align:left;caption-side:bottom}th{text-align:inherit}label{display:inline-block;margin-bottom:.5rem}button{border-radius:0}button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color}button,input,optgroup,select,textarea{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button,input{overflow:visible}button,select{text-transform:none}select{word-wrap:normal}[type=button],[type=reset],[type=submit],button{-webkit-appearance:button}[type=button]:not(:disabled),[type=reset]:not(:disabled),[type=submit]:not(:disabled),button:not(:disabled){cursor:pointer}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}input[type=checkbox],input[type=radio]{-webkit-box-sizing:border-box;box-sizing:border-box;padding:0}input[type=date],input[type=datetime-local],input[type=month],input[type=time]{-webkit-appearance:listbox}textarea{overflow:auto;resize:vertical}fieldset{min-width:0;padding:0;margin:0;border:0}legend{display:block;width:100%;max-width:100%;padding:0;margin-bottom:.5rem;font-size:1.5rem;line-height:inherit;color:inherit;white-space:normal}progress{vertical-align:baseline}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{outline-offset:-2px;-webkit-appearance:none}[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}output{display:inline-block}summary{display:list-item;cursor:pointer}template{display:none}[hidden]{display:none!important}.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{margin-bottom:.5rem;font-weight:500;line-height:1.2}.h1,h1{font-size:2.125rem}.h2,h2{font-size:1.7rem}.h3,h3{font-size:1.4875rem}.h4,h4{font-size:1.275rem}.h5,h5{font-size:1.0625rem}.h6,h6{font-size:.85rem}.lead{font-size:1.0625rem;font-weight:300}.display-1{font-size:6rem;font-weight:300;line-height:1.2}.display-2{font-size:5.5rem;font-weight:300;line-height:1.2}.display-3{font-size:4.5rem;font-weight:300;line-height:1.2}.display-4{font-size:3.5rem;font-weight:300;line-height:1.2}hr{margin-top:1rem;margin-bottom:1rem;border:0;border-top:1px solid rgba(0,0,0,.1)}.small,small{font-size:80%;font-weight:400}.mark,mark{padding:.2em;background-color:#fcf8e3}.list-unstyled{padding-left:0;list-style:none}.list-inline{padding-left:0;list-style:none}.list-inline-item{display:inline-block}.list-inline-item:not(:last-child){margin-right:.5rem}.initialism{font-size:90%;text-transform:uppercase}.blockquote{margin-bottom:1rem;font-size:1.0625rem}.blockquote-footer{display:block;font-size:80%;color:#7c8a96}.blockquote-footer::before{content:"\2014\00A0"}.img-fluid{max-width:100%;height:auto}.img-thumbnail{padding:.25rem;background-color:#f4f8f9;border:1px solid #f4f8f9;border-radius:.25rem;max-width:100%;height:auto}.figure{display:inline-block}.figure-img{margin-bottom:.5rem;line-height:1}.figure-caption{font-size:90%;color:#7c8a96}code{font-size:87.5%;color:#e83e8c;word-wrap:break-word}a>code{color:inherit}kbd{padding:.2rem .4rem;font-size:87.5%;color:#fff;background-color:#212529;border-radius:.2rem}kbd kbd{padding:0;font-size:100%;font-weight:600}pre{display:block;font-size:87.5%;color:#212529}pre code{font-size:inherit;color:inherit;word-break:normal}.pre-scrollable{max-height:340px;overflow-y:scroll}.container{width:100%;padding-right:12px;padding-left:12px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){.container{max-width:1140px}}.container-fluid,.container-lg,.container-md,.container-sm,.container-xl{width:100%;padding-right:12px;padding-left:12px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container,.container-sm{max-width:540px}}@media (min-width:768px){.container,.container-md,.container-sm{max-width:720px}}@media (min-width:992px){.container,.container-lg,.container-md,.container-sm{max-width:960px}}@media (min-width:1200px){.container,.container-lg,.container-md,.container-sm,.container-xl{max-width:1140px}}.row{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-12px;margin-left:-12px}.no-gutters{margin-right:0;margin-left:0}.no-gutters>.col,.no-gutters>[class*=col-]{padding-right:0;padding-left:0}.col,.col-1,.col-10,.col-11,.col-12,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9,.col-auto,.col-lg,.col-lg-1,.col-lg-10,.col-lg-11,.col-lg-12,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-lg-auto,.col-md,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-auto,.col-sm,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-sm-auto,.col-xl,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xl-auto{position:relative;width:100%;padding-right:12px;padding-left:12px}.col{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;max-width:100%}.row-cols-1>*{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.row-cols-2>*{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.row-cols-3>*{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.row-cols-4>*{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.row-cols-5>*{-webkit-box-flex:0;-ms-flex:0 0 20%;flex:0 0 20%;max-width:20%}.row-cols-6>*{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-auto{-webkit-box-flex:0;-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:100%}.col-1{-webkit-box-flex:0;-ms-flex:0 0 8.33333%;flex:0 0 8.33333%;max-width:8.33333%}.col-2{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-3{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-4{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.col-5{-webkit-box-flex:0;-ms-flex:0 0 41.66667%;flex:0 0 41.66667%;max-width:41.66667%}.col-6{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-7{-webkit-box-flex:0;-ms-flex:0 0 58.33333%;flex:0 0 58.33333%;max-width:58.33333%}.col-8{-webkit-box-flex:0;-ms-flex:0 0 66.66667%;flex:0 0 66.66667%;max-width:66.66667%}.col-9{-webkit-box-flex:0;-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-10{-webkit-box-flex:0;-ms-flex:0 0 83.33333%;flex:0 0 83.33333%;max-width:83.33333%}.col-11{-webkit-box-flex:0;-ms-flex:0 0 91.66667%;flex:0 0 91.66667%;max-width:91.66667%}.col-12{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.order-first{-webkit-box-ordinal-group:0;-ms-flex-order:-1;order:-1}.order-last{-webkit-box-ordinal-group:14;-ms-flex-order:13;order:13}.order-0{-webkit-box-ordinal-group:1;-ms-flex-order:0;order:0}.order-1{-webkit-box-ordinal-group:2;-ms-flex-order:1;order:1}.order-2{-webkit-box-ordinal-group:3;-ms-flex-order:2;order:2}.order-3{-webkit-box-ordinal-group:4;-ms-flex-order:3;order:3}.order-4{-webkit-box-ordinal-group:5;-ms-flex-order:4;order:4}.order-5{-webkit-box-ordinal-group:6;-ms-flex-order:5;order:5}.order-6{-webkit-box-ordinal-group:7;-ms-flex-order:6;order:6}.order-7{-webkit-box-ordinal-group:8;-ms-flex-order:7;order:7}.order-8{-webkit-box-ordinal-group:9;-ms-flex-order:8;order:8}.order-9{-webkit-box-ordinal-group:10;-ms-flex-order:9;order:9}.order-10{-webkit-box-ordinal-group:11;-ms-flex-order:10;order:10}.order-11{-webkit-box-ordinal-group:12;-ms-flex-order:11;order:11}.order-12{-webkit-box-ordinal-group:13;-ms-flex-order:12;order:12}.offset-1{margin-left:8.33333%}.offset-2{margin-left:16.66667%}.offset-3{margin-left:25%}.offset-4{margin-left:33.33333%}.offset-5{margin-left:41.66667%}.offset-6{margin-left:50%}.offset-7{margin-left:58.33333%}.offset-8{margin-left:66.66667%}.offset-9{margin-left:75%}.offset-10{margin-left:83.33333%}.offset-11{margin-left:91.66667%}@media (min-width:576px){.col-sm{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;max-width:100%}.row-cols-sm-1>*{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.row-cols-sm-2>*{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.row-cols-sm-3>*{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.row-cols-sm-4>*{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.row-cols-sm-5>*{-webkit-box-flex:0;-ms-flex:0 0 20%;flex:0 0 20%;max-width:20%}.row-cols-sm-6>*{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-sm-auto{-webkit-box-flex:0;-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:100%}.col-sm-1{-webkit-box-flex:0;-ms-flex:0 0 8.33333%;flex:0 0 8.33333%;max-width:8.33333%}.col-sm-2{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-sm-3{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-sm-4{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.col-sm-5{-webkit-box-flex:0;-ms-flex:0 0 41.66667%;flex:0 0 41.66667%;max-width:41.66667%}.col-sm-6{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-sm-7{-webkit-box-flex:0;-ms-flex:0 0 58.33333%;flex:0 0 58.33333%;max-width:58.33333%}.col-sm-8{-webkit-box-flex:0;-ms-flex:0 0 66.66667%;flex:0 0 66.66667%;max-width:66.66667%}.col-sm-9{-webkit-box-flex:0;-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-sm-10{-webkit-box-flex:0;-ms-flex:0 0 83.33333%;flex:0 0 83.33333%;max-width:83.33333%}.col-sm-11{-webkit-box-flex:0;-ms-flex:0 0 91.66667%;flex:0 0 91.66667%;max-width:91.66667%}.col-sm-12{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.order-sm-first{-webkit-box-ordinal-group:0;-ms-flex-order:-1;order:-1}.order-sm-last{-webkit-box-ordinal-group:14;-ms-flex-order:13;order:13}.order-sm-0{-webkit-box-ordinal-group:1;-ms-flex-order:0;order:0}.order-sm-1{-webkit-box-ordinal-group:2;-ms-flex-order:1;order:1}.order-sm-2{-webkit-box-ordinal-group:3;-ms-flex-order:2;order:2}.order-sm-3{-webkit-box-ordinal-group:4;-ms-flex-order:3;order:3}.order-sm-4{-webkit-box-ordinal-group:5;-ms-flex-order:4;order:4}.order-sm-5{-webkit-box-ordinal-group:6;-ms-flex-order:5;order:5}.order-sm-6{-webkit-box-ordinal-group:7;-ms-flex-order:6;order:6}.order-sm-7{-webkit-box-ordinal-group:8;-ms-flex-order:7;order:7}.order-sm-8{-webkit-box-ordinal-group:9;-ms-flex-order:8;order:8}.order-sm-9{-webkit-box-ordinal-group:10;-ms-flex-order:9;order:9}.order-sm-10{-webkit-box-ordinal-group:11;-ms-flex-order:10;order:10}.order-sm-11{-webkit-box-ordinal-group:12;-ms-flex-order:11;order:11}.order-sm-12{-webkit-box-ordinal-group:13;-ms-flex-order:12;order:12}.offset-sm-0{margin-left:0}.offset-sm-1{margin-left:8.33333%}.offset-sm-2{margin-left:16.66667%}.offset-sm-3{margin-left:25%}.offset-sm-4{margin-left:33.33333%}.offset-sm-5{margin-left:41.66667%}.offset-sm-6{margin-left:50%}.offset-sm-7{margin-left:58.33333%}.offset-sm-8{margin-left:66.66667%}.offset-sm-9{margin-left:75%}.offset-sm-10{margin-left:83.33333%}.offset-sm-11{margin-left:91.66667%}}@media (min-width:768px){.col-md{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;max-width:100%}.row-cols-md-1>*{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.row-cols-md-2>*{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.row-cols-md-3>*{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.row-cols-md-4>*{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.row-cols-md-5>*{-webkit-box-flex:0;-ms-flex:0 0 20%;flex:0 0 20%;max-width:20%}.row-cols-md-6>*{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-md-auto{-webkit-box-flex:0;-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:100%}.col-md-1{-webkit-box-flex:0;-ms-flex:0 0 8.33333%;flex:0 0 8.33333%;max-width:8.33333%}.col-md-2{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-md-3{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-md-4{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.col-md-5{-webkit-box-flex:0;-ms-flex:0 0 41.66667%;flex:0 0 41.66667%;max-width:41.66667%}.col-md-6{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-md-7{-webkit-box-flex:0;-ms-flex:0 0 58.33333%;flex:0 0 58.33333%;max-width:58.33333%}.col-md-8{-webkit-box-flex:0;-ms-flex:0 0 66.66667%;flex:0 0 66.66667%;max-width:66.66667%}.col-md-9{-webkit-box-flex:0;-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-md-10{-webkit-box-flex:0;-ms-flex:0 0 83.33333%;flex:0 0 83.33333%;max-width:83.33333%}.col-md-11{-webkit-box-flex:0;-ms-flex:0 0 91.66667%;flex:0 0 91.66667%;max-width:91.66667%}.col-md-12{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.order-md-first{-webkit-box-ordinal-group:0;-ms-flex-order:-1;order:-1}.order-md-last{-webkit-box-ordinal-group:14;-ms-flex-order:13;order:13}.order-md-0{-webkit-box-ordinal-group:1;-ms-flex-order:0;order:0}.order-md-1{-webkit-box-ordinal-group:2;-ms-flex-order:1;order:1}.order-md-2{-webkit-box-ordinal-group:3;-ms-flex-order:2;order:2}.order-md-3{-webkit-box-ordinal-group:4;-ms-flex-order:3;order:3}.order-md-4{-webkit-box-ordinal-group:5;-ms-flex-order:4;order:4}.order-md-5{-webkit-box-ordinal-group:6;-ms-flex-order:5;order:5}.order-md-6{-webkit-box-ordinal-group:7;-ms-flex-order:6;order:6}.order-md-7{-webkit-box-ordinal-group:8;-ms-flex-order:7;order:7}.order-md-8{-webkit-box-ordinal-group:9;-ms-flex-order:8;order:8}.order-md-9{-webkit-box-ordinal-group:10;-ms-flex-order:9;order:9}.order-md-10{-webkit-box-ordinal-group:11;-ms-flex-order:10;order:10}.order-md-11{-webkit-box-ordinal-group:12;-ms-flex-order:11;order:11}.order-md-12{-webkit-box-ordinal-group:13;-ms-flex-order:12;order:12}.offset-md-0{margin-left:0}.offset-md-1{margin-left:8.33333%}.offset-md-2{margin-left:16.66667%}.offset-md-3{margin-left:25%}.offset-md-4{margin-left:33.33333%}.offset-md-5{margin-left:41.66667%}.offset-md-6{margin-left:50%}.offset-md-7{margin-left:58.33333%}.offset-md-8{margin-left:66.66667%}.offset-md-9{margin-left:75%}.offset-md-10{margin-left:83.33333%}.offset-md-11{margin-left:91.66667%}}@media (min-width:992px){.col-lg{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;max-width:100%}.row-cols-lg-1>*{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.row-cols-lg-2>*{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.row-cols-lg-3>*{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.row-cols-lg-4>*{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.row-cols-lg-5>*{-webkit-box-flex:0;-ms-flex:0 0 20%;flex:0 0 20%;max-width:20%}.row-cols-lg-6>*{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-lg-auto{-webkit-box-flex:0;-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:100%}.col-lg-1{-webkit-box-flex:0;-ms-flex:0 0 8.33333%;flex:0 0 8.33333%;max-width:8.33333%}.col-lg-2{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-lg-3{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-lg-4{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.col-lg-5{-webkit-box-flex:0;-ms-flex:0 0 41.66667%;flex:0 0 41.66667%;max-width:41.66667%}.col-lg-6{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-lg-7{-webkit-box-flex:0;-ms-flex:0 0 58.33333%;flex:0 0 58.33333%;max-width:58.33333%}.col-lg-8{-webkit-box-flex:0;-ms-flex:0 0 66.66667%;flex:0 0 66.66667%;max-width:66.66667%}.col-lg-9{-webkit-box-flex:0;-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-lg-10{-webkit-box-flex:0;-ms-flex:0 0 83.33333%;flex:0 0 83.33333%;max-width:83.33333%}.col-lg-11{-webkit-box-flex:0;-ms-flex:0 0 91.66667%;flex:0 0 91.66667%;max-width:91.66667%}.col-lg-12{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.order-lg-first{-webkit-box-ordinal-group:0;-ms-flex-order:-1;order:-1}.order-lg-last{-webkit-box-ordinal-group:14;-ms-flex-order:13;order:13}.order-lg-0{-webkit-box-ordinal-group:1;-ms-flex-order:0;order:0}.order-lg-1{-webkit-box-ordinal-group:2;-ms-flex-order:1;order:1}.order-lg-2{-webkit-box-ordinal-group:3;-ms-flex-order:2;order:2}.order-lg-3{-webkit-box-ordinal-group:4;-ms-flex-order:3;order:3}.order-lg-4{-webkit-box-ordinal-group:5;-ms-flex-order:4;order:4}.order-lg-5{-webkit-box-ordinal-group:6;-ms-flex-order:5;order:5}.order-lg-6{-webkit-box-ordinal-group:7;-ms-flex-order:6;order:6}.order-lg-7{-webkit-box-ordinal-group:8;-ms-flex-order:7;order:7}.order-lg-8{-webkit-box-ordinal-group:9;-ms-flex-order:8;order:8}.order-lg-9{-webkit-box-ordinal-group:10;-ms-flex-order:9;order:9}.order-lg-10{-webkit-box-ordinal-group:11;-ms-flex-order:10;order:10}.order-lg-11{-webkit-box-ordinal-group:12;-ms-flex-order:11;order:11}.order-lg-12{-webkit-box-ordinal-group:13;-ms-flex-order:12;order:12}.offset-lg-0{margin-left:0}.offset-lg-1{margin-left:8.33333%}.offset-lg-2{margin-left:16.66667%}.offset-lg-3{margin-left:25%}.offset-lg-4{margin-left:33.33333%}.offset-lg-5{margin-left:41.66667%}.offset-lg-6{margin-left:50%}.offset-lg-7{margin-left:58.33333%}.offset-lg-8{margin-left:66.66667%}.offset-lg-9{margin-left:75%}.offset-lg-10{margin-left:83.33333%}.offset-lg-11{margin-left:91.66667%}}@media (min-width:1200px){.col-xl{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;max-width:100%}.row-cols-xl-1>*{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.row-cols-xl-2>*{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.row-cols-xl-3>*{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.row-cols-xl-4>*{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.row-cols-xl-5>*{-webkit-box-flex:0;-ms-flex:0 0 20%;flex:0 0 20%;max-width:20%}.row-cols-xl-6>*{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-xl-auto{-webkit-box-flex:0;-ms-flex:0 0 auto;flex:0 0 auto;width:auto;max-width:100%}.col-xl-1{-webkit-box-flex:0;-ms-flex:0 0 8.33333%;flex:0 0 8.33333%;max-width:8.33333%}.col-xl-2{-webkit-box-flex:0;-ms-flex:0 0 16.66667%;flex:0 0 16.66667%;max-width:16.66667%}.col-xl-3{-webkit-box-flex:0;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-xl-4{-webkit-box-flex:0;-ms-flex:0 0 33.33333%;flex:0 0 33.33333%;max-width:33.33333%}.col-xl-5{-webkit-box-flex:0;-ms-flex:0 0 41.66667%;flex:0 0 41.66667%;max-width:41.66667%}.col-xl-6{-webkit-box-flex:0;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-xl-7{-webkit-box-flex:0;-ms-flex:0 0 58.33333%;flex:0 0 58.33333%;max-width:58.33333%}.col-xl-8{-webkit-box-flex:0;-ms-flex:0 0 66.66667%;flex:0 0 66.66667%;max-width:66.66667%}.col-xl-9{-webkit-box-flex:0;-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-xl-10{-webkit-box-flex:0;-ms-flex:0 0 83.33333%;flex:0 0 83.33333%;max-width:83.33333%}.col-xl-11{-webkit-box-flex:0;-ms-flex:0 0 91.66667%;flex:0 0 91.66667%;max-width:91.66667%}.col-xl-12{-webkit-box-flex:0;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}.order-xl-first{-webkit-box-ordinal-group:0;-ms-flex-order:-1;order:-1}.order-xl-last{-webkit-box-ordinal-group:14;-ms-flex-order:13;order:13}.order-xl-0{-webkit-box-ordinal-group:1;-ms-flex-order:0;order:0}.order-xl-1{-webkit-box-ordinal-group:2;-ms-flex-order:1;order:1}.order-xl-2{-webkit-box-ordinal-group:3;-ms-flex-order:2;order:2}.order-xl-3{-webkit-box-ordinal-group:4;-ms-flex-order:3;order:3}.order-xl-4{-webkit-box-ordinal-group:5;-ms-flex-order:4;order:4}.order-xl-5{-webkit-box-ordinal-group:6;-ms-flex-order:5;order:5}.order-xl-6{-webkit-box-ordinal-group:7;-ms-flex-order:6;order:6}.order-xl-7{-webkit-box-ordinal-group:8;-ms-flex-order:7;order:7}.order-xl-8{-webkit-box-ordinal-group:9;-ms-flex-order:8;order:8}.order-xl-9{-webkit-box-ordinal-group:10;-ms-flex-order:9;order:9}.order-xl-10{-webkit-box-ordinal-group:11;-ms-flex-order:10;order:10}.order-xl-11{-webkit-box-ordinal-group:12;-ms-flex-order:11;order:11}.order-xl-12{-webkit-box-ordinal-group:13;-ms-flex-order:12;order:12}.offset-xl-0{margin-left:0}.offset-xl-1{margin-left:8.33333%}.offset-xl-2{margin-left:16.66667%}.offset-xl-3{margin-left:25%}.offset-xl-4{margin-left:33.33333%}.offset-xl-5{margin-left:41.66667%}.offset-xl-6{margin-left:50%}.offset-xl-7{margin-left:58.33333%}.offset-xl-8{margin-left:66.66667%}.offset-xl-9{margin-left:75%}.offset-xl-10{margin-left:83.33333%}.offset-xl-11{margin-left:91.66667%}}.table{width:100%;margin-bottom:1rem;color:#505d69}.table td,.table th{padding:.75rem;vertical-align:top;border-top:1px solid #eff2f7}.table thead th{vertical-align:bottom;border-bottom:2px solid #eff2f7}.table tbody+tbody{border-top:2px solid #eff2f7}.table-sm td,.table-sm th{padding:.3rem}.table-bordered{border:1px solid #eff2f7}.table-bordered td,.table-bordered th{border:1px solid #eff2f7}.table-bordered thead td,.table-bordered thead th{border-bottom-width:2px}.table-borderless tbody+tbody,.table-borderless td,.table-borderless th,.table-borderless thead th{border:0}.table-striped tbody tr:nth-of-type(odd){background-color:#f8f9fa}.table-hover tbody tr:hover{color:#505d69;background-color:#f8f9fa}.table-primary,.table-primary>td,.table-primary>th{background-color:#c9dffd}.table-primary tbody+tbody,.table-primary td,.table-primary th,.table-primary thead th{border-color:#9ac4fb}.table-hover .table-primary:hover{background-color:#b0d0fc}.table-hover .table-primary:hover>td,.table-hover .table-primary:hover>th{background-color:#b0d0fc}.table-secondary,.table-secondary>td,.table-secondary>th{background-color:#dadee2}.table-secondary tbody+tbody,.table-secondary td,.table-secondary th,.table-secondary thead th{border-color:#bbc2c8}.table-hover .table-secondary:hover{background-color:#ccd1d7}.table-hover .table-secondary:hover>td,.table-hover .table-secondary:hover>th{background-color:#ccd1d7}.table-success,.table-success>td,.table-success>th{background-color:#bceed6}.table-success tbody+tbody,.table-success td,.table-success th,.table-success thead th{border-color:#83e0b4}.table-hover .table-success:hover{background-color:#a8e9ca}.table-hover .table-success:hover>td,.table-hover .table-success:hover>th{background-color:#a8e9ca}.table-info,.table-info>td,.table-info>th{background-color:#bbeaf4}.table-info tbody+tbody,.table-info td,.table-info th,.table-info thead th{border-color:#81d8ea}.table-hover .table-info:hover{background-color:#a5e3f0}.table-hover .table-info:hover>td,.table-hover .table-info:hover>th{background-color:#a5e3f0}.table-warning,.table-warning>td,.table-warning>th{background-color:#fbeacd}.table-warning tbody+tbody,.table-warning td,.table-warning th,.table-warning thead th{border-color:#f8d8a2}.table-hover .table-warning:hover{background-color:#f9e0b5}.table-hover .table-warning:hover>td,.table-hover .table-warning:hover>th{background-color:#f9e0b5}.table-danger,.table-danger>td,.table-danger>th{background-color:#fecdcf}.table-danger tbody+tbody,.table-danger td,.table-danger th,.table-danger thead th{border-color:#fda2a6}.table-hover .table-danger:hover{background-color:#feb4b7}.table-hover .table-danger:hover>td,.table-hover .table-danger:hover>th{background-color:#feb4b7}.table-light,.table-light>td,.table-light>th{background-color:#fbfbfd}.table-light tbody+tbody,.table-light td,.table-light th,.table-light thead th{border-color:#f7f8fb}.table-hover .table-light:hover{background-color:#eaeaf5}.table-hover .table-light:hover>td,.table-hover .table-light:hover>th{background-color:#eaeaf5}.table-dark,.table-dark>td,.table-dark>th{background-color:#c6c8ca}.table-dark tbody+tbody,.table-dark td,.table-dark th,.table-dark thead th{border-color:#95999c}.table-hover .table-dark:hover{background-color:#b9bbbe}.table-hover .table-dark:hover>td,.table-hover .table-dark:hover>th{background-color:#b9bbbe}.table-active,.table-active>td,.table-active>th{background-color:#f8f9fa}.table-hover .table-active:hover{background-color:#e9ecef}.table-hover .table-active:hover>td,.table-hover .table-active:hover>th{background-color:#e9ecef}.table .thead-dark th{color:#fff;background-color:#343a40;border-color:#454d55}.table .thead-light th{color:#495057;background-color:#f8f9fa;border-color:#eff2f7}.table-dark{color:#fff;background-color:#343a40}.table-dark td,.table-dark th,.table-dark thead th{border-color:#454d55}.table-dark.table-bordered{border:0}.table-dark.table-striped tbody tr:nth-of-type(odd){background-color:rgba(255,255,255,.05)}.table-dark.table-hover tbody tr:hover{color:#fff;background-color:rgba(255,255,255,.075)}@media (max-width:575.98px){.table-responsive-sm{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-sm>.table-bordered{border:0}}@media (max-width:767.98px){.table-responsive-md{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-md>.table-bordered{border:0}}@media (max-width:991.98px){.table-responsive-lg{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-lg>.table-bordered{border:0}}@media (max-width:1199.98px){.table-responsive-xl{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive-xl>.table-bordered{border:0}}.table-responsive{display:block;width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch}.table-responsive>.table-bordered{border:0}.form-control{display:block;width:100%;height:calc(1.5em + .94rem + 2px);padding:.47rem .75rem;font-size:.85rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;-webkit-transition:border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out}@media (prefers-reduced-motion:reduce){.form-control{-webkit-transition:none;transition:none}}.form-control::-ms-expand{background-color:transparent;border:0}.form-control:-moz-focusring{color:transparent;text-shadow:0 0 0 #495057}.form-control:focus{color:#495057;background-color:#fff;border-color:#b1bbc4;outline:0;-webkit-box-shadow:none;box-shadow:none}.form-control::-webkit-input-placeholder{color:#7c8a96;opacity:1}.form-control::-moz-placeholder{color:#7c8a96;opacity:1}.form-control:-ms-input-placeholder{color:#7c8a96;opacity:1}.form-control::-ms-input-placeholder{color:#7c8a96;opacity:1}.form-control::placeholder{color:#7c8a96;opacity:1}.form-control:disabled,.form-control[readonly]{background-color:#fff;opacity:1}select.form-control:focus::-ms-value{color:#495057;background-color:#fff}.form-control-file,.form-control-range{display:block;width:100%}.col-form-label{padding-top:calc(.47rem + 1px);padding-bottom:calc(.47rem + 1px);margin-bottom:0;font-size:inherit;line-height:1.5}.col-form-label-lg{padding-top:calc(.5rem + 1px);padding-bottom:calc(.5rem + 1px);font-size:1.0625rem;line-height:1.5}.col-form-label-sm{padding-top:calc(.25rem + 1px);padding-bottom:calc(.25rem + 1px);font-size:.74375rem;line-height:1.5}.form-control-plaintext{display:block;width:100%;padding:.47rem 0;margin-bottom:0;font-size:.85rem;line-height:1.5;color:#505d69;background-color:transparent;border:solid transparent;border-width:1px 0}.form-control-plaintext.form-control-lg,.form-control-plaintext.form-control-sm{padding-right:0;padding-left:0}.form-control-sm{height:calc(1.5em + .5rem + 2px);padding:.25rem .5rem;font-size:.74375rem;line-height:1.5;border-radius:.2rem}.form-control-lg{height:calc(1.5em + 1rem + 2px);padding:.5rem 1rem;font-size:1.0625rem;line-height:1.5;border-radius:.5rem}select.form-control[multiple],select.form-control[size]{height:auto}textarea.form-control{height:auto}.form-group{margin-bottom:1rem}.form-text{display:block;margin-top:.25rem}.form-row{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-5px;margin-left:-5px}.form-row>.col,.form-row>[class*=col-]{padding-right:5px;padding-left:5px}.form-check{position:relative;display:block;padding-left:1.25rem}.form-check-input{position:absolute;margin-top:.3rem;margin-left:-1.25rem}.form-check-input:disabled~.form-check-label,.form-check-input[disabled]~.form-check-label{color:#7c8a96}.form-check-label{margin-bottom:0}.form-check-inline{display:-webkit-inline-box;display:-ms-inline-flexbox;display:inline-flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;padding-left:0;margin-right:.75rem}.form-check-inline .form-check-input{position:static;margin-top:0;margin-right:.3125rem;margin-left:0}.valid-feedback{display:none;width:100%;margin-top:.25rem;font-size:80%;color:#11c46e}.valid-tooltip{position:absolute;top:100%;z-index:5;display:none;max-width:100%;padding:.4rem .7rem;margin-top:.1rem;font-size:.74375rem;line-height:1.5;color:#fff;background-color:rgba(17,196,110,.9);border-radius:.25rem}.is-valid~.valid-feedback,.is-valid~.valid-tooltip,.was-validated :valid~.valid-feedback,.was-validated :valid~.valid-tooltip{display:block}.form-control.is-valid,.was-validated .form-control:valid{border-color:#11c46e;padding-right:calc(1.5em + .94rem);background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2311c46e' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right calc(.375em + .235rem) center;background-size:calc(.75em + .47rem) calc(.75em + .47rem)}.form-control.is-valid:focus,.was-validated .form-control:valid:focus{border-color:#11c46e;-webkit-box-shadow:0 0 0 .15rem rgba(17,196,110,.25);box-shadow:0 0 0 .15rem rgba(17,196,110,.25)}.was-validated textarea.form-control:valid,textarea.form-control.is-valid{padding-right:calc(1.5em + .94rem);background-position:top calc(.375em + .235rem) right calc(.375em + .235rem)}.custom-select.is-valid,.was-validated .custom-select:valid{border-color:#11c46e;padding-right:calc((1em + .94rem) * 3 / 4 + 1.75rem);background:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right .75rem center/8px 10px,url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2311c46e' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") #fff no-repeat center right 1.75rem/calc(.75em + .47rem) calc(.75em + .47rem)}.custom-select.is-valid:focus,.was-validated .custom-select:valid:focus{border-color:#11c46e;-webkit-box-shadow:0 0 0 .15rem rgba(17,196,110,.25);box-shadow:0 0 0 .15rem rgba(17,196,110,.25)}.form-check-input.is-valid~.form-check-label,.was-validated .form-check-input:valid~.form-check-label{color:#11c46e}.form-check-input.is-valid~.valid-feedback,.form-check-input.is-valid~.valid-tooltip,.was-validated .form-check-input:valid~.valid-feedback,.was-validated .form-check-input:valid~.valid-tooltip{display:block}.custom-control-input.is-valid~.custom-control-label,.was-validated .custom-control-input:valid~.custom-control-label{color:#11c46e}.custom-control-input.is-valid~.custom-control-label::before,.was-validated .custom-control-input:valid~.custom-control-label::before{border-color:#11c46e}.custom-control-input.is-valid:checked~.custom-control-label::before,.was-validated .custom-control-input:valid:checked~.custom-control-label::before{border-color:#1deb88;background-color:#1deb88}.custom-control-input.is-valid:focus~.custom-control-label::before,.was-validated .custom-control-input:valid:focus~.custom-control-label::before{-webkit-box-shadow:0 0 0 .15rem rgba(17,196,110,.25);box-shadow:0 0 0 .15rem rgba(17,196,110,.25)}.custom-control-input.is-valid:focus:not(:checked)~.custom-control-label::before,.was-validated .custom-control-input:valid:focus:not(:checked)~.custom-control-label::before{border-color:#11c46e}.custom-file-input.is-valid~.custom-file-label,.was-validated .custom-file-input:valid~.custom-file-label{border-color:#11c46e}.custom-file-input.is-valid:focus~.custom-file-label,.was-validated .custom-file-input:valid:focus~.custom-file-label{border-color:#11c46e;-webkit-box-shadow:0 0 0 .15rem rgba(17,196,110,.25);box-shadow:0 0 0 .15rem rgba(17,196,110,.25)}.invalid-feedback{display:none;width:100%;margin-top:.25rem;font-size:80%;color:#fb4d53}.invalid-tooltip{position:absolute;top:100%;z-index:5;display:none;max-width:100%;padding:.4rem .7rem;margin-top:.1rem;font-size:.74375rem;line-height:1.5;color:#fff;background-color:rgba(251,77,83,.9);border-radius:.25rem}.is-invalid~.invalid-feedback,.is-invalid~.invalid-tooltip,.was-validated :invalid~.invalid-feedback,.was-validated :invalid~.invalid-tooltip{display:block}.form-control.is-invalid,.was-validated .form-control:invalid{border-color:#fb4d53;padding-right:calc(1.5em + .94rem);background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fb4d53' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23fb4d53' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E");background-repeat:no-repeat;background-position:right calc(.375em + .235rem) center;background-size:calc(.75em + .47rem) calc(.75em + .47rem)}.form-control.is-invalid:focus,.was-validated .form-control:invalid:focus{border-color:#fb4d53;-webkit-box-shadow:0 0 0 .15rem rgba(251,77,83,.25);box-shadow:0 0 0 .15rem rgba(251,77,83,.25)}.was-validated textarea.form-control:invalid,textarea.form-control.is-invalid{padding-right:calc(1.5em + .94rem);background-position:top calc(.375em + .235rem) right calc(.375em + .235rem)}.custom-select.is-invalid,.was-validated .custom-select:invalid{border-color:#fb4d53;padding-right:calc((1em + .94rem) * 3 / 4 + 1.75rem);background:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right .75rem center/8px 10px,url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fb4d53' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23fb4d53' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") #fff no-repeat center right 1.75rem/calc(.75em + .47rem) calc(.75em + .47rem)}.custom-select.is-invalid:focus,.was-validated .custom-select:invalid:focus{border-color:#fb4d53;-webkit-box-shadow:0 0 0 .15rem rgba(251,77,83,.25);box-shadow:0 0 0 .15rem rgba(251,77,83,.25)}.form-check-input.is-invalid~.form-check-label,.was-validated .form-check-input:invalid~.form-check-label{color:#fb4d53}.form-check-input.is-invalid~.invalid-feedback,.form-check-input.is-invalid~.invalid-tooltip,.was-validated .form-check-input:invalid~.invalid-feedback,.was-validated .form-check-input:invalid~.invalid-tooltip{display:block}.custom-control-input.is-invalid~.custom-control-label,.was-validated .custom-control-input:invalid~.custom-control-label{color:#fb4d53}.custom-control-input.is-invalid~.custom-control-label::before,.was-validated .custom-control-input:invalid~.custom-control-label::before{border-color:#fb4d53}.custom-control-input.is-invalid:checked~.custom-control-label::before,.was-validated .custom-control-input:invalid:checked~.custom-control-label::before{border-color:#fc7f83;background-color:#fc7f83}.custom-control-input.is-invalid:focus~.custom-control-label::before,.was-validated .custom-control-input:invalid:focus~.custom-control-label::before{-webkit-box-shadow:0 0 0 .15rem rgba(251,77,83,.25);box-shadow:0 0 0 .15rem rgba(251,77,83,.25)}.custom-control-input.is-invalid:focus:not(:checked)~.custom-control-label::before,.was-validated .custom-control-input:invalid:focus:not(:checked)~.custom-control-label::before{border-color:#fb4d53}.custom-file-input.is-invalid~.custom-file-label,.was-validated .custom-file-input:invalid~.custom-file-label{border-color:#fb4d53}.custom-file-input.is-invalid:focus~.custom-file-label,.was-validated .custom-file-input:invalid:focus~.custom-file-label{border-color:#fb4d53;-webkit-box-shadow:0 0 0 .15rem rgba(251,77,83,.25);box-shadow:0 0 0 .15rem rgba(251,77,83,.25)}.form-inline{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row wrap;flex-flow:row wrap;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.form-inline .form-check{width:100%}@media (min-width:576px){.form-inline label{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;margin-bottom:0}.form-inline .form-group{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-flex:0;-ms-flex:0 0 auto;flex:0 0 auto;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row wrap;flex-flow:row wrap;-webkit-box-align:center;-ms-flex-align:center;align-items:center;margin-bottom:0}.form-inline .form-control{display:inline-block;width:auto;vertical-align:middle}.form-inline .form-control-plaintext{display:inline-block}.form-inline .custom-select,.form-inline .input-group{width:auto}.form-inline .form-check{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;width:auto;padding-left:0}.form-inline .form-check-input{position:relative;-ms-flex-negative:0;flex-shrink:0;margin-top:0;margin-right:.25rem;margin-left:0}.form-inline .custom-control{-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center}.form-inline .custom-control-label{margin-bottom:0}}.btn{display:inline-block;font-weight:400;color:#505d69;text-align:center;vertical-align:middle;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-color:transparent;border:1px solid transparent;padding:.47rem .75rem;font-size:.85rem;line-height:1.5;border-radius:.25rem;-webkit-transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out}@media (prefers-reduced-motion:reduce){.btn{-webkit-transition:none;transition:none}}.btn:hover{color:#505d69;text-decoration:none}.btn.focus,.btn:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(61,142,248,.25);box-shadow:0 0 0 .15rem rgba(61,142,248,.25)}.btn.disabled,.btn:disabled{opacity:.65}a.btn.disabled,fieldset:disabled a.btn{pointer-events:none}.btn-primary{color:#fff;background-color:#3d8ef8;border-color:#3d8ef8}.btn-primary:hover{color:#fff;background-color:#1878f7;border-color:#0c71f6}.btn-primary.focus,.btn-primary:focus{color:#fff;background-color:#1878f7;border-color:#0c71f6;-webkit-box-shadow:0 0 0 .15rem rgba(90,159,249,.5);box-shadow:0 0 0 .15rem rgba(90,159,249,.5)}.btn-primary.disabled,.btn-primary:disabled{color:#fff;background-color:#3d8ef8;border-color:#3d8ef8}.btn-primary:not(:disabled):not(.disabled).active,.btn-primary:not(:disabled):not(.disabled):active,.show>.btn-primary.dropdown-toggle{color:#fff;background-color:#0c71f6;border-color:#096bed}.btn-primary:not(:disabled):not(.disabled).active:focus,.btn-primary:not(:disabled):not(.disabled):active:focus,.show>.btn-primary.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(90,159,249,.5);box-shadow:0 0 0 .15rem rgba(90,159,249,.5)}.btn-secondary{color:#fff;background-color:#7c8a96;border-color:#7c8a96}.btn-secondary:hover{color:#fff;background-color:#697783;border-color:#63707c}.btn-secondary.focus,.btn-secondary:focus{color:#fff;background-color:#697783;border-color:#63707c;-webkit-box-shadow:0 0 0 .15rem rgba(144,156,166,.5);box-shadow:0 0 0 .15rem rgba(144,156,166,.5)}.btn-secondary.disabled,.btn-secondary:disabled{color:#fff;background-color:#7c8a96;border-color:#7c8a96}.btn-secondary:not(:disabled):not(.disabled).active,.btn-secondary:not(:disabled):not(.disabled):active,.show>.btn-secondary.dropdown-toggle{color:#fff;background-color:#63707c;border-color:#5e6a75}.btn-secondary:not(:disabled):not(.disabled).active:focus,.btn-secondary:not(:disabled):not(.disabled):active:focus,.show>.btn-secondary.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(144,156,166,.5);box-shadow:0 0 0 .15rem rgba(144,156,166,.5)}.btn-success{color:#fff;background-color:#11c46e;border-color:#11c46e}.btn-success:hover{color:#fff;background-color:#0ea15a;border-color:#0d9554}.btn-success.focus,.btn-success:focus{color:#fff;background-color:#0ea15a;border-color:#0d9554;-webkit-box-shadow:0 0 0 .15rem rgba(53,205,132,.5);box-shadow:0 0 0 .15rem rgba(53,205,132,.5)}.btn-success.disabled,.btn-success:disabled{color:#fff;background-color:#11c46e;border-color:#11c46e}.btn-success:not(:disabled):not(.disabled).active,.btn-success:not(:disabled):not(.disabled):active,.show>.btn-success.dropdown-toggle{color:#fff;background-color:#0d9554;border-color:#0c894d}.btn-success:not(:disabled):not(.disabled).active:focus,.btn-success:not(:disabled):not(.disabled):active:focus,.show>.btn-success.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(53,205,132,.5);box-shadow:0 0 0 .15rem rgba(53,205,132,.5)}.btn-info{color:#fff;background-color:#0db4d6;border-color:#0db4d6}.btn-info:hover{color:#fff;background-color:#0b96b2;border-color:#0a8ca6}.btn-info.focus,.btn-info:focus{color:#fff;background-color:#0b96b2;border-color:#0a8ca6;-webkit-box-shadow:0 0 0 .15rem rgba(49,191,220,.5);box-shadow:0 0 0 .15rem rgba(49,191,220,.5)}.btn-info.disabled,.btn-info:disabled{color:#fff;background-color:#0db4d6;border-color:#0db4d6}.btn-info:not(:disabled):not(.disabled).active,.btn-info:not(:disabled):not(.disabled):active,.show>.btn-info.dropdown-toggle{color:#fff;background-color:#0a8ca6;border-color:#09819a}.btn-info:not(:disabled):not(.disabled).active:focus,.btn-info:not(:disabled):not(.disabled):active:focus,.show>.btn-info.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(49,191,220,.5);box-shadow:0 0 0 .15rem rgba(49,191,220,.5)}.btn-warning{color:#fff;background-color:#f1b44c;border-color:#f1b44c}.btn-warning:hover{color:#fff;background-color:#eea529;border-color:#eda01d}.btn-warning.focus,.btn-warning:focus{color:#fff;background-color:#eea529;border-color:#eda01d;-webkit-box-shadow:0 0 0 .15rem rgba(243,191,103,.5);box-shadow:0 0 0 .15rem rgba(243,191,103,.5)}.btn-warning.disabled,.btn-warning:disabled{color:#fff;background-color:#f1b44c;border-color:#f1b44c}.btn-warning:not(:disabled):not(.disabled).active,.btn-warning:not(:disabled):not(.disabled):active,.show>.btn-warning.dropdown-toggle{color:#fff;background-color:#eda01d;border-color:#eb9b12}.btn-warning:not(:disabled):not(.disabled).active:focus,.btn-warning:not(:disabled):not(.disabled):active:focus,.show>.btn-warning.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(243,191,103,.5);box-shadow:0 0 0 .15rem rgba(243,191,103,.5)}.btn-danger{color:#fff;background-color:#fb4d53;border-color:#fb4d53}.btn-danger:hover{color:#fff;background-color:#fa282f;border-color:#fa1b23}.btn-danger.focus,.btn-danger:focus{color:#fff;background-color:#fa282f;border-color:#fa1b23;-webkit-box-shadow:0 0 0 .15rem rgba(252,104,109,.5);box-shadow:0 0 0 .15rem rgba(252,104,109,.5)}.btn-danger.disabled,.btn-danger:disabled{color:#fff;background-color:#fb4d53;border-color:#fb4d53}.btn-danger:not(:disabled):not(.disabled).active,.btn-danger:not(:disabled):not(.disabled):active,.show>.btn-danger.dropdown-toggle{color:#fff;background-color:#fa1b23;border-color:#fa0f17}.btn-danger:not(:disabled):not(.disabled).active:focus,.btn-danger:not(:disabled):not(.disabled):active:focus,.show>.btn-danger.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(252,104,109,.5);box-shadow:0 0 0 .15rem rgba(252,104,109,.5)}.btn-light{color:#212529;background-color:#eff2f7;border-color:#eff2f7}.btn-light:hover{color:#212529;background-color:#d6ddea;border-color:#cdd6e6}.btn-light.focus,.btn-light:focus{color:#212529;background-color:#d6ddea;border-color:#cdd6e6;-webkit-box-shadow:0 0 0 .15rem rgba(208,211,216,.5);box-shadow:0 0 0 .15rem rgba(208,211,216,.5)}.btn-light.disabled,.btn-light:disabled{color:#212529;background-color:#eff2f7;border-color:#eff2f7}.btn-light:not(:disabled):not(.disabled).active,.btn-light:not(:disabled):not(.disabled):active,.show>.btn-light.dropdown-toggle{color:#212529;background-color:#cdd6e6;border-color:#c5cfe2}.btn-light:not(:disabled):not(.disabled).active:focus,.btn-light:not(:disabled):not(.disabled):active:focus,.show>.btn-light.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(208,211,216,.5);box-shadow:0 0 0 .15rem rgba(208,211,216,.5)}.btn-dark{color:#fff;background-color:#343a40;border-color:#343a40}.btn-dark:hover{color:#fff;background-color:#23272b;border-color:#1d2124}.btn-dark.focus,.btn-dark:focus{color:#fff;background-color:#23272b;border-color:#1d2124;-webkit-box-shadow:0 0 0 .15rem rgba(82,88,93,.5);box-shadow:0 0 0 .15rem rgba(82,88,93,.5)}.btn-dark.disabled,.btn-dark:disabled{color:#fff;background-color:#343a40;border-color:#343a40}.btn-dark:not(:disabled):not(.disabled).active,.btn-dark:not(:disabled):not(.disabled):active,.show>.btn-dark.dropdown-toggle{color:#fff;background-color:#1d2124;border-color:#171a1d}.btn-dark:not(:disabled):not(.disabled).active:focus,.btn-dark:not(:disabled):not(.disabled):active:focus,.show>.btn-dark.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(82,88,93,.5);box-shadow:0 0 0 .15rem rgba(82,88,93,.5)}.btn-outline-primary{color:#3d8ef8;border-color:#3d8ef8}.btn-outline-primary:hover{color:#fff;background-color:#3d8ef8;border-color:#3d8ef8}.btn-outline-primary.focus,.btn-outline-primary:focus{-webkit-box-shadow:0 0 0 .15rem rgba(61,142,248,.5);box-shadow:0 0 0 .15rem rgba(61,142,248,.5)}.btn-outline-primary.disabled,.btn-outline-primary:disabled{color:#3d8ef8;background-color:transparent}.btn-outline-primary:not(:disabled):not(.disabled).active,.btn-outline-primary:not(:disabled):not(.disabled):active,.show>.btn-outline-primary.dropdown-toggle{color:#fff;background-color:#3d8ef8;border-color:#3d8ef8}.btn-outline-primary:not(:disabled):not(.disabled).active:focus,.btn-outline-primary:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-primary.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(61,142,248,.5);box-shadow:0 0 0 .15rem rgba(61,142,248,.5)}.btn-outline-secondary{color:#7c8a96;border-color:#7c8a96}.btn-outline-secondary:hover{color:#fff;background-color:#7c8a96;border-color:#7c8a96}.btn-outline-secondary.focus,.btn-outline-secondary:focus{-webkit-box-shadow:0 0 0 .15rem rgba(124,138,150,.5);box-shadow:0 0 0 .15rem rgba(124,138,150,.5)}.btn-outline-secondary.disabled,.btn-outline-secondary:disabled{color:#7c8a96;background-color:transparent}.btn-outline-secondary:not(:disabled):not(.disabled).active,.btn-outline-secondary:not(:disabled):not(.disabled):active,.show>.btn-outline-secondary.dropdown-toggle{color:#fff;background-color:#7c8a96;border-color:#7c8a96}.btn-outline-secondary:not(:disabled):not(.disabled).active:focus,.btn-outline-secondary:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-secondary.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(124,138,150,.5);box-shadow:0 0 0 .15rem rgba(124,138,150,.5)}.btn-outline-success{color:#11c46e;border-color:#11c46e}.btn-outline-success:hover{color:#fff;background-color:#11c46e;border-color:#11c46e}.btn-outline-success.focus,.btn-outline-success:focus{-webkit-box-shadow:0 0 0 .15rem rgba(17,196,110,.5);box-shadow:0 0 0 .15rem rgba(17,196,110,.5)}.btn-outline-success.disabled,.btn-outline-success:disabled{color:#11c46e;background-color:transparent}.btn-outline-success:not(:disabled):not(.disabled).active,.btn-outline-success:not(:disabled):not(.disabled):active,.show>.btn-outline-success.dropdown-toggle{color:#fff;background-color:#11c46e;border-color:#11c46e}.btn-outline-success:not(:disabled):not(.disabled).active:focus,.btn-outline-success:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-success.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(17,196,110,.5);box-shadow:0 0 0 .15rem rgba(17,196,110,.5)}.btn-outline-info{color:#0db4d6;border-color:#0db4d6}.btn-outline-info:hover{color:#fff;background-color:#0db4d6;border-color:#0db4d6}.btn-outline-info.focus,.btn-outline-info:focus{-webkit-box-shadow:0 0 0 .15rem rgba(13,180,214,.5);box-shadow:0 0 0 .15rem rgba(13,180,214,.5)}.btn-outline-info.disabled,.btn-outline-info:disabled{color:#0db4d6;background-color:transparent}.btn-outline-info:not(:disabled):not(.disabled).active,.btn-outline-info:not(:disabled):not(.disabled):active,.show>.btn-outline-info.dropdown-toggle{color:#fff;background-color:#0db4d6;border-color:#0db4d6}.btn-outline-info:not(:disabled):not(.disabled).active:focus,.btn-outline-info:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-info.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(13,180,214,.5);box-shadow:0 0 0 .15rem rgba(13,180,214,.5)}.btn-outline-warning{color:#f1b44c;border-color:#f1b44c}.btn-outline-warning:hover{color:#fff;background-color:#f1b44c;border-color:#f1b44c}.btn-outline-warning.focus,.btn-outline-warning:focus{-webkit-box-shadow:0 0 0 .15rem rgba(241,180,76,.5);box-shadow:0 0 0 .15rem rgba(241,180,76,.5)}.btn-outline-warning.disabled,.btn-outline-warning:disabled{color:#f1b44c;background-color:transparent}.btn-outline-warning:not(:disabled):not(.disabled).active,.btn-outline-warning:not(:disabled):not(.disabled):active,.show>.btn-outline-warning.dropdown-toggle{color:#fff;background-color:#f1b44c;border-color:#f1b44c}.btn-outline-warning:not(:disabled):not(.disabled).active:focus,.btn-outline-warning:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-warning.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(241,180,76,.5);box-shadow:0 0 0 .15rem rgba(241,180,76,.5)}.btn-outline-danger{color:#fb4d53;border-color:#fb4d53}.btn-outline-danger:hover{color:#fff;background-color:#fb4d53;border-color:#fb4d53}.btn-outline-danger.focus,.btn-outline-danger:focus{-webkit-box-shadow:0 0 0 .15rem rgba(251,77,83,.5);box-shadow:0 0 0 .15rem rgba(251,77,83,.5)}.btn-outline-danger.disabled,.btn-outline-danger:disabled{color:#fb4d53;background-color:transparent}.btn-outline-danger:not(:disabled):not(.disabled).active,.btn-outline-danger:not(:disabled):not(.disabled):active,.show>.btn-outline-danger.dropdown-toggle{color:#fff;background-color:#fb4d53;border-color:#fb4d53}.btn-outline-danger:not(:disabled):not(.disabled).active:focus,.btn-outline-danger:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-danger.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(251,77,83,.5);box-shadow:0 0 0 .15rem rgba(251,77,83,.5)}.btn-outline-light{color:#eff2f7;border-color:#eff2f7}.btn-outline-light:hover{color:#212529;background-color:#eff2f7;border-color:#eff2f7}.btn-outline-light.focus,.btn-outline-light:focus{-webkit-box-shadow:0 0 0 .15rem rgba(239,242,247,.5);box-shadow:0 0 0 .15rem rgba(239,242,247,.5)}.btn-outline-light.disabled,.btn-outline-light:disabled{color:#eff2f7;background-color:transparent}.btn-outline-light:not(:disabled):not(.disabled).active,.btn-outline-light:not(:disabled):not(.disabled):active,.show>.btn-outline-light.dropdown-toggle{color:#212529;background-color:#eff2f7;border-color:#eff2f7}.btn-outline-light:not(:disabled):not(.disabled).active:focus,.btn-outline-light:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-light.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(239,242,247,.5);box-shadow:0 0 0 .15rem rgba(239,242,247,.5)}.btn-outline-dark{color:#343a40;border-color:#343a40}.btn-outline-dark:hover{color:#fff;background-color:#343a40;border-color:#343a40}.btn-outline-dark.focus,.btn-outline-dark:focus{-webkit-box-shadow:0 0 0 .15rem rgba(52,58,64,.5);box-shadow:0 0 0 .15rem rgba(52,58,64,.5)}.btn-outline-dark.disabled,.btn-outline-dark:disabled{color:#343a40;background-color:transparent}.btn-outline-dark:not(:disabled):not(.disabled).active,.btn-outline-dark:not(:disabled):not(.disabled):active,.show>.btn-outline-dark.dropdown-toggle{color:#fff;background-color:#343a40;border-color:#343a40}.btn-outline-dark:not(:disabled):not(.disabled).active:focus,.btn-outline-dark:not(:disabled):not(.disabled):active:focus,.show>.btn-outline-dark.dropdown-toggle:focus{-webkit-box-shadow:0 0 0 .15rem rgba(52,58,64,.5);box-shadow:0 0 0 .15rem rgba(52,58,64,.5)}.btn-link{font-weight:400;color:#3d8ef8;text-decoration:none}.btn-link:hover{color:#0866e0;text-decoration:underline}.btn-link.focus,.btn-link:focus{text-decoration:underline;-webkit-box-shadow:none;box-shadow:none}.btn-link.disabled,.btn-link:disabled{color:#7c8a96;pointer-events:none}.btn-group-lg>.btn,.btn-lg{padding:.5rem 1rem;font-size:1.0625rem;line-height:1.5;border-radius:.5rem}.btn-group-sm>.btn,.btn-sm{padding:.25rem .5rem;font-size:.74375rem;line-height:1.5;border-radius:.2rem}.btn-block{display:block;width:100%}.btn-block+.btn-block{margin-top:.5rem}input[type=button].btn-block,input[type=reset].btn-block,input[type=submit].btn-block{width:100%}.fade{-webkit-transition:opacity .15s linear;transition:opacity .15s linear}@media (prefers-reduced-motion:reduce){.fade{-webkit-transition:none;transition:none}}.fade:not(.show){opacity:0}.collapse:not(.show){display:none}.collapsing{position:relative;height:0;overflow:hidden;-webkit-transition:height .35s ease;transition:height .35s ease}@media (prefers-reduced-motion:reduce){.collapsing{-webkit-transition:none;transition:none}}.dropdown,.dropleft,.dropright,.dropup{position:relative}.dropdown-toggle{white-space:nowrap}.dropdown-menu{position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:10rem;padding:.5rem 0;margin:.125rem 0 0;font-size:.85rem;color:#505d69;text-align:left;list-style:none;background-color:#fff;background-clip:padding-box;border:1px solid #e2e6e9;border-radius:.25rem}.dropdown-menu-left{right:auto;left:0}.dropdown-menu-right{right:0;left:auto}@media (min-width:576px){.dropdown-menu-sm-left{right:auto;left:0}.dropdown-menu-sm-right{right:0;left:auto}}@media (min-width:768px){.dropdown-menu-md-left{right:auto;left:0}.dropdown-menu-md-right{right:0;left:auto}}@media (min-width:992px){.dropdown-menu-lg-left{right:auto;left:0}.dropdown-menu-lg-right{right:0;left:auto}}@media (min-width:1200px){.dropdown-menu-xl-left{right:auto;left:0}.dropdown-menu-xl-right{right:0;left:auto}}.dropup .dropdown-menu{top:auto;bottom:100%;margin-top:0;margin-bottom:.125rem}.dropright .dropdown-menu{top:0;right:auto;left:100%;margin-top:0;margin-left:.125rem}.dropright .dropdown-toggle::after{vertical-align:0}.dropleft .dropdown-menu{top:0;right:100%;left:auto;margin-top:0;margin-right:.125rem}.dropleft .dropdown-toggle::before{vertical-align:0}.dropdown-menu[x-placement^=bottom],.dropdown-menu[x-placement^=left],.dropdown-menu[x-placement^=right],.dropdown-menu[x-placement^=top]{right:auto;bottom:auto}.dropdown-divider{height:0;margin:.5rem 0;overflow:hidden;border-top:1px solid #eff2f7}.dropdown-item{display:block;width:100%;padding:.4rem 1rem;clear:both;font-weight:400;color:#7c8a96;text-align:inherit;white-space:nowrap;background-color:transparent;border:0}.dropdown-item:focus,.dropdown-item:hover{color:#16181b;text-decoration:none;background-color:#f8f9fa}.dropdown-item.active,.dropdown-item:active{color:#16181b;text-decoration:none;background-color:#f8f9fa}.dropdown-item.disabled,.dropdown-item:disabled{color:#7c8a96;pointer-events:none;background-color:transparent}.dropdown-menu.show{display:block}.dropdown-header{display:block;padding:.5rem 1rem;margin-bottom:0;font-size:.74375rem;color:#7c8a96;white-space:nowrap}.dropdown-item-text{display:block;padding:.4rem 1rem;color:#7c8a96}.btn-group,.btn-group-vertical{position:relative;display:-webkit-inline-box;display:-ms-inline-flexbox;display:inline-flex;vertical-align:middle}.btn-group-vertical>.btn,.btn-group>.btn{position:relative;-webkit-box-flex:1;-ms-flex:1 1 auto;flex:1 1 auto}.btn-group-vertical>.btn:hover,.btn-group>.btn:hover{z-index:1}.btn-group-vertical>.btn.active,.btn-group-vertical>.btn:active,.btn-group-vertical>.btn:focus,.btn-group>.btn.active,.btn-group>.btn:active,.btn-group>.btn:focus{z-index:1}.btn-toolbar{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:start;-ms-flex-pack:start;justify-content:flex-start}.btn-toolbar .input-group{width:auto}.btn-group>.btn-group:not(:first-child),.btn-group>.btn:not(:first-child){margin-left:-1px}.btn-group>.btn-group:not(:last-child)>.btn,.btn-group>.btn:not(:last-child):not(.dropdown-toggle){border-top-right-radius:0;border-bottom-right-radius:0}.btn-group>.btn-group:not(:first-child)>.btn,.btn-group>.btn:not(:first-child){border-top-left-radius:0;border-bottom-left-radius:0}.dropdown-toggle-split{padding-right:.5625rem;padding-left:.5625rem}.dropdown-toggle-split::after,.dropright .dropdown-toggle-split::after,.dropup .dropdown-toggle-split::after{margin-left:0}.dropleft .dropdown-toggle-split::before{margin-right:0}.btn-group-sm>.btn+.dropdown-toggle-split,.btn-sm+.dropdown-toggle-split{padding-right:.375rem;padding-left:.375rem}.btn-group-lg>.btn+.dropdown-toggle-split,.btn-lg+.dropdown-toggle-split{padding-right:.75rem;padding-left:.75rem}.btn-group-vertical{-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:start;-ms-flex-align:start;align-items:flex-start;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center}.btn-group-vertical>.btn,.btn-group-vertical>.btn-group{width:100%}.btn-group-vertical>.btn-group:not(:first-child),.btn-group-vertical>.btn:not(:first-child){margin-top:-1px}.btn-group-vertical>.btn-group:not(:last-child)>.btn,.btn-group-vertical>.btn:not(:last-child):not(.dropdown-toggle){border-bottom-right-radius:0;border-bottom-left-radius:0}.btn-group-vertical>.btn-group:not(:first-child)>.btn,.btn-group-vertical>.btn:not(:first-child){border-top-left-radius:0;border-top-right-radius:0}.btn-group-toggle>.btn,.btn-group-toggle>.btn-group>.btn{margin-bottom:0}.btn-group-toggle>.btn input[type=checkbox],.btn-group-toggle>.btn input[type=radio],.btn-group-toggle>.btn-group>.btn input[type=checkbox],.btn-group-toggle>.btn-group>.btn input[type=radio]{position:absolute;clip:rect(0,0,0,0);pointer-events:none}.input-group{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-align:stretch;-ms-flex-align:stretch;align-items:stretch;width:100%}.input-group>.custom-file,.input-group>.custom-select,.input-group>.form-control,.input-group>.form-control-plaintext{position:relative;-webkit-box-flex:1;-ms-flex:1 1 0%;flex:1 1 0%;min-width:0;margin-bottom:0}.input-group>.custom-file+.custom-file,.input-group>.custom-file+.custom-select,.input-group>.custom-file+.form-control,.input-group>.custom-select+.custom-file,.input-group>.custom-select+.custom-select,.input-group>.custom-select+.form-control,.input-group>.form-control+.custom-file,.input-group>.form-control+.custom-select,.input-group>.form-control+.form-control,.input-group>.form-control-plaintext+.custom-file,.input-group>.form-control-plaintext+.custom-select,.input-group>.form-control-plaintext+.form-control{margin-left:-1px}.input-group>.custom-file .custom-file-input:focus~.custom-file-label,.input-group>.custom-select:focus,.input-group>.form-control:focus{z-index:3}.input-group>.custom-file .custom-file-input:focus{z-index:4}.input-group>.custom-select:not(:last-child),.input-group>.form-control:not(:last-child){border-top-right-radius:0;border-bottom-right-radius:0}.input-group>.custom-select:not(:first-child),.input-group>.form-control:not(:first-child){border-top-left-radius:0;border-bottom-left-radius:0}.input-group>.custom-file{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.input-group>.custom-file:not(:last-child) .custom-file-label,.input-group>.custom-file:not(:last-child) .custom-file-label::after{border-top-right-radius:0;border-bottom-right-radius:0}.input-group>.custom-file:not(:first-child) .custom-file-label{border-top-left-radius:0;border-bottom-left-radius:0}.input-group-append,.input-group-prepend{display:-webkit-box;display:-ms-flexbox;display:flex}.input-group-append .btn,.input-group-prepend .btn{position:relative;z-index:2}.input-group-append .btn:focus,.input-group-prepend .btn:focus{z-index:3}.input-group-append .btn+.btn,.input-group-append .btn+.input-group-text,.input-group-append .input-group-text+.btn,.input-group-append .input-group-text+.input-group-text,.input-group-prepend .btn+.btn,.input-group-prepend .btn+.input-group-text,.input-group-prepend .input-group-text+.btn,.input-group-prepend .input-group-text+.input-group-text{margin-left:-1px}.input-group-prepend{margin-right:-1px}.input-group-append{margin-left:-1px}.input-group-text{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;padding:.47rem .75rem;margin-bottom:0;font-size:.85rem;font-weight:400;line-height:1.5;color:#495057;text-align:center;white-space:nowrap;background-color:#eff2f7;border:1px solid #ced4da;border-radius:.25rem}.input-group-text input[type=checkbox],.input-group-text input[type=radio]{margin-top:0}.input-group-lg>.custom-select,.input-group-lg>.form-control:not(textarea){height:calc(1.5em + 1rem + 2px)}.input-group-lg>.custom-select,.input-group-lg>.form-control,.input-group-lg>.input-group-append>.btn,.input-group-lg>.input-group-append>.input-group-text,.input-group-lg>.input-group-prepend>.btn,.input-group-lg>.input-group-prepend>.input-group-text{padding:.5rem 1rem;font-size:1.0625rem;line-height:1.5;border-radius:.5rem}.input-group-sm>.custom-select,.input-group-sm>.form-control:not(textarea){height:calc(1.5em + .5rem + 2px)}.input-group-sm>.custom-select,.input-group-sm>.form-control,.input-group-sm>.input-group-append>.btn,.input-group-sm>.input-group-append>.input-group-text,.input-group-sm>.input-group-prepend>.btn,.input-group-sm>.input-group-prepend>.input-group-text{padding:.25rem .5rem;font-size:.74375rem;line-height:1.5;border-radius:.2rem}.input-group-lg>.custom-select,.input-group-sm>.custom-select{padding-right:1.75rem}.input-group>.input-group-append:last-child>.btn:not(:last-child):not(.dropdown-toggle),.input-group>.input-group-append:last-child>.input-group-text:not(:last-child),.input-group>.input-group-append:not(:last-child)>.btn,.input-group>.input-group-append:not(:last-child)>.input-group-text,.input-group>.input-group-prepend>.btn,.input-group>.input-group-prepend>.input-group-text{border-top-right-radius:0;border-bottom-right-radius:0}.input-group>.input-group-append>.btn,.input-group>.input-group-append>.input-group-text,.input-group>.input-group-prepend:first-child>.btn:not(:first-child),.input-group>.input-group-prepend:first-child>.input-group-text:not(:first-child),.input-group>.input-group-prepend:not(:first-child)>.btn,.input-group>.input-group-prepend:not(:first-child)>.input-group-text{border-top-left-radius:0;border-bottom-left-radius:0}.custom-control{position:relative;display:block;min-height:1.275rem;padding-left:1.5rem}.custom-control-inline{display:-webkit-inline-box;display:-ms-inline-flexbox;display:inline-flex;margin-right:1rem}.custom-control-input{position:absolute;left:0;z-index:-1;width:1rem;height:1.1375rem;opacity:0}.custom-control-input:checked~.custom-control-label::before{color:#fff;border-color:#3d8ef8;background-color:#3d8ef8}.custom-control-input:focus~.custom-control-label::before{-webkit-box-shadow:none;box-shadow:none}.custom-control-input:focus:not(:checked)~.custom-control-label::before{border-color:#b1bbc4}.custom-control-input:not(:disabled):active~.custom-control-label::before{color:#fff;background-color:#e9f2fe;border-color:#e9f2fe}.custom-control-input:disabled~.custom-control-label,.custom-control-input[disabled]~.custom-control-label{color:#7c8a96}.custom-control-input:disabled~.custom-control-label::before,.custom-control-input[disabled]~.custom-control-label::before{background-color:#fff}.custom-control-label{position:relative;margin-bottom:0;vertical-align:top}.custom-control-label::before{position:absolute;top:.1375rem;left:-1.5rem;display:block;width:1rem;height:1rem;pointer-events:none;content:"";background-color:#fff;border:#adb5bd solid 1px}.custom-control-label::after{position:absolute;top:.1375rem;left:-1.5rem;display:block;width:1rem;height:1rem;content:"";background:no-repeat 50%/50% 50%}.custom-checkbox .custom-control-label::before{border-radius:.25rem}.custom-checkbox .custom-control-input:checked~.custom-control-label::after{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e")}.custom-checkbox .custom-control-input:indeterminate~.custom-control-label::before{border-color:#3d8ef8;background-color:#3d8ef8}.custom-checkbox .custom-control-input:indeterminate~.custom-control-label::after{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 4'%3e%3cpath stroke='%23fff' d='M0 2h4'/%3e%3c/svg%3e")}.custom-checkbox .custom-control-input:disabled:checked~.custom-control-label::before{background-color:rgba(61,142,248,.5)}.custom-checkbox .custom-control-input:disabled:indeterminate~.custom-control-label::before{background-color:rgba(61,142,248,.5)}.custom-radio .custom-control-label::before{border-radius:50%}.custom-radio .custom-control-input:checked~.custom-control-label::after{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e")}.custom-radio .custom-control-input:disabled:checked~.custom-control-label::before{background-color:rgba(61,142,248,.5)}.custom-switch{padding-left:2.25rem}.custom-switch .custom-control-label::before{left:-2.25rem;width:1.75rem;pointer-events:all;border-radius:.5rem}.custom-switch .custom-control-label::after{top:calc(.1375rem + 2px);left:calc(-2.25rem + 2px);width:calc(1rem - 4px);height:calc(1rem - 4px);background-color:#adb5bd;border-radius:.5rem;-webkit-transition:background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-transform .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-transform .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:transform .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:transform .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-transform .15s ease-in-out,-webkit-box-shadow .15s ease-in-out}@media (prefers-reduced-motion:reduce){.custom-switch .custom-control-label::after{-webkit-transition:none;transition:none}}.custom-switch .custom-control-input:checked~.custom-control-label::after{background-color:#fff;-webkit-transform:translateX(.75rem);transform:translateX(.75rem)}.custom-switch .custom-control-input:disabled:checked~.custom-control-label::before{background-color:rgba(61,142,248,.5)}.custom-select{display:inline-block;width:100%;height:calc(1.5em + .94rem + 2px);padding:.47rem 1.75rem .47rem .75rem;font-size:.85rem;font-weight:400;line-height:1.5;color:#495057;vertical-align:middle;background:#fff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right .75rem center/8px 10px;border:1px solid #ced4da;border-radius:.25rem;-webkit-appearance:none;-moz-appearance:none;appearance:none}.custom-select:focus{border-color:#b1bbc4;outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(61,142,248,.25);box-shadow:0 0 0 .15rem rgba(61,142,248,.25)}.custom-select:focus::-ms-value{color:#495057;background-color:#fff}.custom-select[multiple],.custom-select[size]:not([size="1"]){height:auto;padding-right:.75rem;background-image:none}.custom-select:disabled{color:#7c8a96;background-color:#eff2f7}.custom-select::-ms-expand{display:none}.custom-select:-moz-focusring{color:transparent;text-shadow:0 0 0 #495057}.custom-select-sm{height:calc(1.5em + .5rem + 2px);padding-top:.25rem;padding-bottom:.25rem;padding-left:.5rem;font-size:.74375rem}.custom-select-lg{height:calc(1.5em + 1rem + 2px);padding-top:.5rem;padding-bottom:.5rem;padding-left:1rem;font-size:1.0625rem}.custom-file{position:relative;display:inline-block;width:100%;height:calc(1.5em + .94rem + 2px);margin-bottom:0}.custom-file-input{position:relative;z-index:2;width:100%;height:calc(1.5em + .94rem + 2px);margin:0;opacity:0}.custom-file-input:focus~.custom-file-label{border-color:#b1bbc4;-webkit-box-shadow:none;box-shadow:none}.custom-file-input:disabled~.custom-file-label,.custom-file-input[disabled]~.custom-file-label{background-color:#fff}.custom-file-input:lang(en)~.custom-file-label::after{content:"Browse"}.custom-file-input~.custom-file-label[data-browse]::after{content:attr(data-browse)}.custom-file-label{position:absolute;top:0;right:0;left:0;z-index:1;height:calc(1.5em + .94rem + 2px);padding:.47rem .75rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;border:1px solid #ced4da;border-radius:.25rem}.custom-file-label::after{position:absolute;top:0;right:0;bottom:0;z-index:3;display:block;height:calc(1.5em + .94rem);padding:.47rem .75rem;line-height:1.5;color:#495057;content:"Browse";background-color:#eff2f7;border-left:inherit;border-radius:0 .25rem .25rem 0}.custom-range{width:100%;height:1.3rem;padding:0;background-color:transparent;-webkit-appearance:none;-moz-appearance:none;appearance:none}.custom-range:focus{outline:0}.custom-range:focus::-webkit-slider-thumb{-webkit-box-shadow:0 0 0 1px #f4f8f9,none;box-shadow:0 0 0 1px #f4f8f9,none}.custom-range:focus::-moz-range-thumb{box-shadow:0 0 0 1px #f4f8f9,none}.custom-range:focus::-ms-thumb{box-shadow:0 0 0 1px #f4f8f9,none}.custom-range::-moz-focus-outer{border:0}.custom-range::-webkit-slider-thumb{width:1rem;height:1rem;margin-top:-.25rem;background-color:#3d8ef8;border:0;border-radius:1rem;-webkit-transition:background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;-webkit-appearance:none;appearance:none}@media (prefers-reduced-motion:reduce){.custom-range::-webkit-slider-thumb{-webkit-transition:none;transition:none}}.custom-range::-webkit-slider-thumb:active{background-color:#e9f2fe}.custom-range::-webkit-slider-runnable-track{width:100%;height:.5rem;color:transparent;cursor:pointer;background-color:#f4f8f9;border-color:transparent;border-radius:1rem}.custom-range::-moz-range-thumb{width:1rem;height:1rem;background-color:#3d8ef8;border:0;border-radius:1rem;-moz-transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;-moz-appearance:none;appearance:none}@media (prefers-reduced-motion:reduce){.custom-range::-moz-range-thumb{-moz-transition:none;transition:none}}.custom-range::-moz-range-thumb:active{background-color:#e9f2fe}.custom-range::-moz-range-track{width:100%;height:.5rem;color:transparent;cursor:pointer;background-color:#f4f8f9;border-color:transparent;border-radius:1rem}.custom-range::-ms-thumb{width:1rem;height:1rem;margin-top:0;margin-right:.15rem;margin-left:.15rem;background-color:#3d8ef8;border:0;border-radius:1rem;-ms-transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;appearance:none}@media (prefers-reduced-motion:reduce){.custom-range::-ms-thumb{-ms-transition:none;transition:none}}.custom-range::-ms-thumb:active{background-color:#e9f2fe}.custom-range::-ms-track{width:100%;height:.5rem;color:transparent;cursor:pointer;background-color:transparent;border-color:transparent;border-width:.5rem}.custom-range::-ms-fill-lower{background-color:#f4f8f9;border-radius:1rem}.custom-range::-ms-fill-upper{margin-right:15px;background-color:#f4f8f9;border-radius:1rem}.custom-range:disabled::-webkit-slider-thumb{background-color:#adb5bd}.custom-range:disabled::-webkit-slider-runnable-track{cursor:default}.custom-range:disabled::-moz-range-thumb{background-color:#adb5bd}.custom-range:disabled::-moz-range-track{cursor:default}.custom-range:disabled::-ms-thumb{background-color:#adb5bd}.custom-control-label::before,.custom-file-label,.custom-select{-webkit-transition:background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out}@media (prefers-reduced-motion:reduce){.custom-control-label::before,.custom-file-label,.custom-select{-webkit-transition:none;transition:none}}.nav{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;padding-left:0;margin-bottom:0;list-style:none}.nav-link{display:block;padding:.5rem 1rem}.nav-link:focus,.nav-link:hover{text-decoration:none}.nav-link.disabled{color:#7c8a96;pointer-events:none;cursor:default}.nav-tabs{border-bottom:1px solid #f4f8f9}.nav-tabs .nav-item{margin-bottom:-1px}.nav-tabs .nav-link{border:1px solid transparent;border-top-left-radius:.25rem;border-top-right-radius:.25rem}.nav-tabs .nav-link:focus,.nav-tabs .nav-link:hover{border-color:#eff2f7 #eff2f7 #f4f8f9}.nav-tabs .nav-link.disabled{color:#7c8a96;background-color:transparent;border-color:transparent}.nav-tabs .nav-item.show .nav-link,.nav-tabs .nav-link.active{color:#495057;background-color:#fff;border-color:#f4f8f9 #f4f8f9 #fff}.nav-tabs .dropdown-menu{margin-top:-1px;border-top-left-radius:0;border-top-right-radius:0}.nav-pills .nav-link{border-radius:.25rem}.nav-pills .nav-link.active,.nav-pills .show>.nav-link{color:#fff;background-color:#3d8ef8}.nav-fill .nav-item{-webkit-box-flex:1;-ms-flex:1 1 auto;flex:1 1 auto;text-align:center}.nav-justified .nav-item{-ms-flex-preferred-size:0;flex-basis:0;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;text-align:center}.tab-content>.tab-pane{display:none}.tab-content>.active{display:block}.navbar{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between;padding:.5rem 1rem}.navbar .container,.navbar .container-fluid,.navbar .container-lg,.navbar .container-md,.navbar .container-sm,.navbar .container-xl{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between}.navbar-brand{display:inline-block;padding-top:.34062rem;padding-bottom:.34062rem;margin-right:1rem;font-size:1.0625rem;line-height:inherit;white-space:nowrap}.navbar-brand:focus,.navbar-brand:hover{text-decoration:none}.navbar-nav{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;padding-left:0;margin-bottom:0;list-style:none}.navbar-nav .nav-link{padding-right:0;padding-left:0}.navbar-nav .dropdown-menu{position:static;float:none}.navbar-text{display:inline-block;padding-top:.5rem;padding-bottom:.5rem}.navbar-collapse{-ms-flex-preferred-size:100%;flex-basis:100%;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.navbar-toggler{padding:.25rem .75rem;font-size:1.0625rem;line-height:1;background-color:transparent;border:1px solid transparent;border-radius:.25rem}.navbar-toggler:focus,.navbar-toggler:hover{text-decoration:none}.navbar-toggler-icon{display:inline-block;width:1.5em;height:1.5em;vertical-align:middle;content:"";background:no-repeat center center;background-size:100% 100%}@media (max-width:575.98px){.navbar-expand-sm>.container,.navbar-expand-sm>.container-fluid,.navbar-expand-sm>.container-lg,.navbar-expand-sm>.container-md,.navbar-expand-sm>.container-sm,.navbar-expand-sm>.container-xl{padding-right:0;padding-left:0}}@media (min-width:576px){.navbar-expand-sm{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row nowrap;flex-flow:row nowrap;-webkit-box-pack:start;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-sm .navbar-nav{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.navbar-expand-sm .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-sm .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-sm>.container,.navbar-expand-sm>.container-fluid,.navbar-expand-sm>.container-lg,.navbar-expand-sm>.container-md,.navbar-expand-sm>.container-sm,.navbar-expand-sm>.container-xl{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-sm .navbar-collapse{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-sm .navbar-toggler{display:none}}@media (max-width:767.98px){.navbar-expand-md>.container,.navbar-expand-md>.container-fluid,.navbar-expand-md>.container-lg,.navbar-expand-md>.container-md,.navbar-expand-md>.container-sm,.navbar-expand-md>.container-xl{padding-right:0;padding-left:0}}@media (min-width:768px){.navbar-expand-md{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row nowrap;flex-flow:row nowrap;-webkit-box-pack:start;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-md .navbar-nav{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.navbar-expand-md .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-md .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-md>.container,.navbar-expand-md>.container-fluid,.navbar-expand-md>.container-lg,.navbar-expand-md>.container-md,.navbar-expand-md>.container-sm,.navbar-expand-md>.container-xl{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-md .navbar-collapse{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-md .navbar-toggler{display:none}}@media (max-width:991.98px){.navbar-expand-lg>.container,.navbar-expand-lg>.container-fluid,.navbar-expand-lg>.container-lg,.navbar-expand-lg>.container-md,.navbar-expand-lg>.container-sm,.navbar-expand-lg>.container-xl{padding-right:0;padding-left:0}}@media (min-width:992px){.navbar-expand-lg{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row nowrap;flex-flow:row nowrap;-webkit-box-pack:start;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-lg .navbar-nav{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.navbar-expand-lg .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-lg .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-lg>.container,.navbar-expand-lg>.container-fluid,.navbar-expand-lg>.container-lg,.navbar-expand-lg>.container-md,.navbar-expand-lg>.container-sm,.navbar-expand-lg>.container-xl{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-lg .navbar-collapse{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-lg .navbar-toggler{display:none}}@media (max-width:1199.98px){.navbar-expand-xl>.container,.navbar-expand-xl>.container-fluid,.navbar-expand-xl>.container-lg,.navbar-expand-xl>.container-md,.navbar-expand-xl>.container-sm,.navbar-expand-xl>.container-xl{padding-right:0;padding-left:0}}@media (min-width:1200px){.navbar-expand-xl{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row nowrap;flex-flow:row nowrap;-webkit-box-pack:start;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand-xl .navbar-nav{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.navbar-expand-xl .navbar-nav .dropdown-menu{position:absolute}.navbar-expand-xl .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand-xl>.container,.navbar-expand-xl>.container-fluid,.navbar-expand-xl>.container-lg,.navbar-expand-xl>.container-md,.navbar-expand-xl>.container-sm,.navbar-expand-xl>.container-xl{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand-xl .navbar-collapse{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand-xl .navbar-toggler{display:none}}.navbar-expand{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row nowrap;flex-flow:row nowrap;-webkit-box-pack:start;-ms-flex-pack:start;justify-content:flex-start}.navbar-expand>.container,.navbar-expand>.container-fluid,.navbar-expand>.container-lg,.navbar-expand>.container-md,.navbar-expand>.container-sm,.navbar-expand>.container-xl{padding-right:0;padding-left:0}.navbar-expand .navbar-nav{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.navbar-expand .navbar-nav .dropdown-menu{position:absolute}.navbar-expand .navbar-nav .nav-link{padding-right:.5rem;padding-left:.5rem}.navbar-expand>.container,.navbar-expand>.container-fluid,.navbar-expand>.container-lg,.navbar-expand>.container-md,.navbar-expand>.container-sm,.navbar-expand>.container-xl{-ms-flex-wrap:nowrap;flex-wrap:nowrap}.navbar-expand .navbar-collapse{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important;-ms-flex-preferred-size:auto;flex-basis:auto}.navbar-expand .navbar-toggler{display:none}.navbar-light .navbar-brand{color:rgba(0,0,0,.9)}.navbar-light .navbar-brand:focus,.navbar-light .navbar-brand:hover{color:rgba(0,0,0,.9)}.navbar-light .navbar-nav .nav-link{color:rgba(0,0,0,.5)}.navbar-light .navbar-nav .nav-link:focus,.navbar-light .navbar-nav .nav-link:hover{color:rgba(0,0,0,.7)}.navbar-light .navbar-nav .nav-link.disabled{color:rgba(0,0,0,.3)}.navbar-light .navbar-nav .active>.nav-link,.navbar-light .navbar-nav .nav-link.active,.navbar-light .navbar-nav .nav-link.show,.navbar-light .navbar-nav .show>.nav-link{color:rgba(0,0,0,.9)}.navbar-light .navbar-toggler{color:rgba(0,0,0,.5);border-color:rgba(0,0,0,.1)}.navbar-light .navbar-toggler-icon{background-image:url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(0, 0, 0, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e")}.navbar-light .navbar-text{color:rgba(0,0,0,.5)}.navbar-light .navbar-text a{color:rgba(0,0,0,.9)}.navbar-light .navbar-text a:focus,.navbar-light .navbar-text a:hover{color:rgba(0,0,0,.9)}.navbar-dark .navbar-brand{color:#fff}.navbar-dark .navbar-brand:focus,.navbar-dark .navbar-brand:hover{color:#fff}.navbar-dark .navbar-nav .nav-link{color:rgba(255,255,255,.5)}.navbar-dark .navbar-nav .nav-link:focus,.navbar-dark .navbar-nav .nav-link:hover{color:rgba(255,255,255,.75)}.navbar-dark .navbar-nav .nav-link.disabled{color:rgba(255,255,255,.25)}.navbar-dark .navbar-nav .active>.nav-link,.navbar-dark .navbar-nav .nav-link.active,.navbar-dark .navbar-nav .nav-link.show,.navbar-dark .navbar-nav .show>.nav-link{color:#fff}.navbar-dark .navbar-toggler{color:rgba(255,255,255,.5);border-color:rgba(255,255,255,.1)}.navbar-dark .navbar-toggler-icon{background-image:url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(255, 255, 255, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e")}.navbar-dark .navbar-text{color:rgba(255,255,255,.5)}.navbar-dark .navbar-text a{color:#fff}.navbar-dark .navbar-text a:focus,.navbar-dark .navbar-text a:hover{color:#fff}.card{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:1px solid #f4f8f9;border-radius:.25rem}.card>hr{margin-right:0;margin-left:0}.card>.list-group:first-child .list-group-item:first-child{border-top-left-radius:.25rem;border-top-right-radius:.25rem}.card>.list-group:last-child .list-group-item:last-child{border-bottom-right-radius:.25rem;border-bottom-left-radius:.25rem}.card-body{-webkit-box-flex:1;-ms-flex:1 1 auto;flex:1 1 auto;min-height:1px;padding:1.25rem}.card-title{margin-bottom:.75rem}.card-subtitle{margin-top:-.375rem;margin-bottom:0}.card-text:last-child{margin-bottom:0}.card-link:hover{text-decoration:none}.card-link+.card-link{margin-left:1.25rem}.card-header{padding:.75rem 1.25rem;margin-bottom:0;background-color:#f4f8f9;border-bottom:1px solid #f4f8f9}.card-header:first-child{border-radius:calc(.25rem - 1px) calc(.25rem - 1px) 0 0}.card-header+.list-group .list-group-item:first-child{border-top:0}.card-footer{padding:.75rem 1.25rem;background-color:#f4f8f9;border-top:1px solid #f4f8f9}.card-footer:last-child{border-radius:0 0 calc(.25rem - 1px) calc(.25rem - 1px)}.card-header-tabs{margin-right:-.625rem;margin-bottom:-.75rem;margin-left:-.625rem;border-bottom:0}.card-header-pills{margin-right:-.625rem;margin-left:-.625rem}.card-img-overlay{position:absolute;top:0;right:0;bottom:0;left:0;padding:1.25rem}.card-img,.card-img-bottom,.card-img-top{-ms-flex-negative:0;flex-shrink:0;width:100%}.card-img,.card-img-top{border-top-left-radius:calc(.25rem - 1px);border-top-right-radius:calc(.25rem - 1px)}.card-img,.card-img-bottom{border-bottom-right-radius:calc(.25rem - 1px);border-bottom-left-radius:calc(.25rem - 1px)}.card-deck .card{margin-bottom:12px}@media (min-width:576px){.card-deck{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row wrap;flex-flow:row wrap;margin-right:-12px;margin-left:-12px}.card-deck .card{-webkit-box-flex:1;-ms-flex:1 0 0%;flex:1 0 0%;margin-right:12px;margin-bottom:0;margin-left:12px}}.card-group>.card{margin-bottom:12px}@media (min-width:576px){.card-group{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-flow:row wrap;flex-flow:row wrap}.card-group>.card{-webkit-box-flex:1;-ms-flex:1 0 0%;flex:1 0 0%;margin-bottom:0}.card-group>.card+.card{margin-left:0;border-left:0}.card-group>.card:not(:last-child){border-top-right-radius:0;border-bottom-right-radius:0}.card-group>.card:not(:last-child) .card-header,.card-group>.card:not(:last-child) .card-img-top{border-top-right-radius:0}.card-group>.card:not(:last-child) .card-footer,.card-group>.card:not(:last-child) .card-img-bottom{border-bottom-right-radius:0}.card-group>.card:not(:first-child){border-top-left-radius:0;border-bottom-left-radius:0}.card-group>.card:not(:first-child) .card-header,.card-group>.card:not(:first-child) .card-img-top{border-top-left-radius:0}.card-group>.card:not(:first-child) .card-footer,.card-group>.card:not(:first-child) .card-img-bottom{border-bottom-left-radius:0}}.card-columns .card{margin-bottom:24px}@media (min-width:576px){.card-columns{-webkit-column-count:3;-moz-column-count:3;column-count:3;-webkit-column-gap:24px;-moz-column-gap:24px;column-gap:24px;orphans:1;widows:1}.card-columns .card{display:inline-block;width:100%}}.accordion>.card{overflow:hidden}.accordion>.card:not(:last-of-type){border-bottom:0;border-bottom-right-radius:0;border-bottom-left-radius:0}.accordion>.card:not(:first-of-type){border-top-left-radius:0;border-top-right-radius:0}.accordion>.card>.card-header{border-radius:0;margin-bottom:-1px}.breadcrumb{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;padding:.75rem 1rem;margin-bottom:1rem;list-style:none;background-color:#eff2f7;border-radius:.25rem}.breadcrumb-item+.breadcrumb-item{padding-left:.5rem}.breadcrumb-item+.breadcrumb-item::before{display:inline-block;padding-right:.5rem;color:#7c8a96;content:"/"}.breadcrumb-item+.breadcrumb-item:hover::before{text-decoration:underline}.breadcrumb-item+.breadcrumb-item:hover::before{text-decoration:none}.breadcrumb-item.active{color:#7c8a96}.pagination{display:-webkit-box;display:-ms-flexbox;display:flex;padding-left:0;list-style:none;border-radius:.25rem}.page-link{position:relative;display:block;padding:.5rem .75rem;margin-left:-1px;line-height:1.25;color:#7c8a96;background-color:#fff;border:1px solid #d7dce1}.page-link:hover{z-index:2;color:#0866e0;text-decoration:none;background-color:#eff2f7;border-color:#d7dce1}.page-link:focus{z-index:3;outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(61,142,248,.25);box-shadow:0 0 0 .15rem rgba(61,142,248,.25)}.page-item:first-child .page-link{margin-left:0;border-top-left-radius:.25rem;border-bottom-left-radius:.25rem}.page-item:last-child .page-link{border-top-right-radius:.25rem;border-bottom-right-radius:.25rem}.page-item.active .page-link{z-index:3;color:#fff;background-color:#3d8ef8;border-color:#3d8ef8}.page-item.disabled .page-link{color:#7c8a96;pointer-events:none;cursor:auto;background-color:#f8f9fa;border-color:#d7dce1}.pagination-lg .page-link{padding:.75rem 1.5rem;font-size:1.0625rem;line-height:1.5}.pagination-lg .page-item:first-child .page-link{border-top-left-radius:.5rem;border-bottom-left-radius:.5rem}.pagination-lg .page-item:last-child .page-link{border-top-right-radius:.5rem;border-bottom-right-radius:.5rem}.pagination-sm .page-link{padding:.25rem .5rem;font-size:.74375rem;line-height:1.5}.pagination-sm .page-item:first-child .page-link{border-top-left-radius:.2rem;border-bottom-left-radius:.2rem}.pagination-sm .page-item:last-child .page-link{border-top-right-radius:.2rem;border-bottom-right-radius:.2rem}.badge{display:inline-block;padding:.25em .4em;font-size:75%;font-weight:500;line-height:1;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25rem;-webkit-transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out}@media (prefers-reduced-motion:reduce){.badge{-webkit-transition:none;transition:none}}a.badge:focus,a.badge:hover{text-decoration:none}.badge:empty{display:none}.btn .badge{position:relative;top:-1px}.badge-pill{padding-right:.6em;padding-left:.6em;border-radius:10rem}.badge-primary{color:#fff;background-color:#3d8ef8}a.badge-primary:focus,a.badge-primary:hover{color:#fff;background-color:#0c71f6}a.badge-primary.focus,a.badge-primary:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(61,142,248,.5);box-shadow:0 0 0 .15rem rgba(61,142,248,.5)}.badge-secondary{color:#fff;background-color:#7c8a96}a.badge-secondary:focus,a.badge-secondary:hover{color:#fff;background-color:#63707c}a.badge-secondary.focus,a.badge-secondary:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(124,138,150,.5);box-shadow:0 0 0 .15rem rgba(124,138,150,.5)}.badge-success{color:#fff;background-color:#11c46e}a.badge-success:focus,a.badge-success:hover{color:#fff;background-color:#0d9554}a.badge-success.focus,a.badge-success:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(17,196,110,.5);box-shadow:0 0 0 .15rem rgba(17,196,110,.5)}.badge-info{color:#fff;background-color:#0db4d6}a.badge-info:focus,a.badge-info:hover{color:#fff;background-color:#0a8ca6}a.badge-info.focus,a.badge-info:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(13,180,214,.5);box-shadow:0 0 0 .15rem rgba(13,180,214,.5)}.badge-warning{color:#fff;background-color:#f1b44c}a.badge-warning:focus,a.badge-warning:hover{color:#fff;background-color:#eda01d}a.badge-warning.focus,a.badge-warning:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(241,180,76,.5);box-shadow:0 0 0 .15rem rgba(241,180,76,.5)}.badge-danger{color:#fff;background-color:#fb4d53}a.badge-danger:focus,a.badge-danger:hover{color:#fff;background-color:#fa1b23}a.badge-danger.focus,a.badge-danger:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(251,77,83,.5);box-shadow:0 0 0 .15rem rgba(251,77,83,.5)}.badge-light{color:#212529;background-color:#eff2f7}a.badge-light:focus,a.badge-light:hover{color:#212529;background-color:#cdd6e6}a.badge-light.focus,a.badge-light:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(239,242,247,.5);box-shadow:0 0 0 .15rem rgba(239,242,247,.5)}.badge-dark{color:#fff;background-color:#343a40}a.badge-dark:focus,a.badge-dark:hover{color:#fff;background-color:#1d2124}a.badge-dark.focus,a.badge-dark:focus{outline:0;-webkit-box-shadow:0 0 0 .15rem rgba(52,58,64,.5);box-shadow:0 0 0 .15rem rgba(52,58,64,.5)}.jumbotron{padding:2rem 1rem;margin-bottom:2rem;background-color:#eff2f7;border-radius:.5rem}@media (min-width:576px){.jumbotron{padding:4rem 2rem}}.jumbotron-fluid{padding-right:0;padding-left:0;border-radius:0}.alert{position:relative;padding:.75rem 1.25rem;margin-bottom:1rem;border:1px solid transparent;border-radius:.25rem}.alert-heading{color:inherit}.alert-link{font-weight:600}.alert-dismissible{padding-right:3.775rem}.alert-dismissible .close{position:absolute;top:0;right:0;padding:.75rem 1.25rem;color:inherit}.alert-primary{color:#204a81;background-color:#d8e8fe;border-color:#c9dffd}.alert-primary hr{border-top-color:#b0d0fc}.alert-primary .alert-link{color:#163358}.alert-secondary{color:#40484e;background-color:#e5e8ea;border-color:#dadee2}.alert-secondary hr{border-top-color:#ccd1d7}.alert-secondary .alert-link{color:#292e32}.alert-success{color:#096639;background-color:#cff3e2;border-color:#bceed6}.alert-success hr{border-top-color:#a8e9ca}.alert-success .alert-link{color:#05371f}.alert-info{color:#075e6f;background-color:#cff0f7;border-color:#bbeaf4}.alert-info hr{border-top-color:#a5e3f0}.alert-info .alert-link{color:#04353f}.alert-warning{color:#7d5e28;background-color:#fcf0db;border-color:#fbeacd}.alert-warning hr{border-top-color:#f9e0b5}.alert-warning .alert-link{color:#56411c}.alert-danger{color:#83282b;background-color:#fedbdd;border-color:#fecdcf}.alert-danger hr{border-top-color:#feb4b7}.alert-danger .alert-link{color:#5c1c1e}.alert-light{color:#7c7e80;background-color:#fcfcfd;border-color:#fbfbfd}.alert-light hr{border-top-color:#eaeaf5}.alert-light .alert-link{color:#636566}.alert-dark{color:#1b1e21;background-color:#d6d8d9;border-color:#c6c8ca}.alert-dark hr{border-top-color:#b9bbbe}.alert-dark .alert-link{color:#040505}@-webkit-keyframes progress-bar-stripes{from{background-position:.625rem 0}to{background-position:0 0}}@keyframes progress-bar-stripes{from{background-position:.625rem 0}to{background-position:0 0}}.progress{display:-webkit-box;display:-ms-flexbox;display:flex;height:.625rem;overflow:hidden;font-size:.6375rem;background-color:#f4f8f9;border-radius:.25rem}.progress-bar{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;overflow:hidden;color:#fff;text-align:center;white-space:nowrap;background-color:#3d8ef8;-webkit-transition:width .6s ease;transition:width .6s ease}@media (prefers-reduced-motion:reduce){.progress-bar{-webkit-transition:none;transition:none}}.progress-bar-striped{background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-size:.625rem .625rem}.progress-bar-animated{-webkit-animation:progress-bar-stripes 1s linear infinite;animation:progress-bar-stripes 1s linear infinite}@media (prefers-reduced-motion:reduce){.progress-bar-animated{-webkit-animation:none;animation:none}}.media{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:start;-ms-flex-align:start;align-items:flex-start}.media-body{-webkit-box-flex:1;-ms-flex:1;flex:1}.list-group{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;padding-left:0;margin-bottom:0}.list-group-item-action{width:100%;color:#495057;text-align:inherit}.list-group-item-action:focus,.list-group-item-action:hover{z-index:1;color:#495057;text-decoration:none;background-color:#f8f9fa}.list-group-item-action:active{color:#505d69;background-color:#eff2f7}.list-group-item{position:relative;display:block;padding:.75rem 1.25rem;background-color:#fff;border:1px solid rgba(0,0,0,.125)}.list-group-item:first-child{border-top-left-radius:.25rem;border-top-right-radius:.25rem}.list-group-item:last-child{border-bottom-right-radius:.25rem;border-bottom-left-radius:.25rem}.list-group-item.disabled,.list-group-item:disabled{color:#7c8a96;pointer-events:none;background-color:#fff}.list-group-item.active{z-index:2;color:#fff;background-color:#3d8ef8;border-color:#3d8ef8}.list-group-item+.list-group-item{border-top-width:0}.list-group-item+.list-group-item.active{margin-top:-1px;border-top-width:1px}.list-group-horizontal{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.list-group-horizontal .list-group-item:first-child{border-bottom-left-radius:.25rem;border-top-right-radius:0}.list-group-horizontal .list-group-item:last-child{border-top-right-radius:.25rem;border-bottom-left-radius:0}.list-group-horizontal .list-group-item.active{margin-top:0}.list-group-horizontal .list-group-item+.list-group-item{border-top-width:1px;border-left-width:0}.list-group-horizontal .list-group-item+.list-group-item.active{margin-left:-1px;border-left-width:1px}@media (min-width:576px){.list-group-horizontal-sm{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.list-group-horizontal-sm .list-group-item:first-child{border-bottom-left-radius:.25rem;border-top-right-radius:0}.list-group-horizontal-sm .list-group-item:last-child{border-top-right-radius:.25rem;border-bottom-left-radius:0}.list-group-horizontal-sm .list-group-item.active{margin-top:0}.list-group-horizontal-sm .list-group-item+.list-group-item{border-top-width:1px;border-left-width:0}.list-group-horizontal-sm .list-group-item+.list-group-item.active{margin-left:-1px;border-left-width:1px}}@media (min-width:768px){.list-group-horizontal-md{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.list-group-horizontal-md .list-group-item:first-child{border-bottom-left-radius:.25rem;border-top-right-radius:0}.list-group-horizontal-md .list-group-item:last-child{border-top-right-radius:.25rem;border-bottom-left-radius:0}.list-group-horizontal-md .list-group-item.active{margin-top:0}.list-group-horizontal-md .list-group-item+.list-group-item{border-top-width:1px;border-left-width:0}.list-group-horizontal-md .list-group-item+.list-group-item.active{margin-left:-1px;border-left-width:1px}}@media (min-width:992px){.list-group-horizontal-lg{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.list-group-horizontal-lg .list-group-item:first-child{border-bottom-left-radius:.25rem;border-top-right-radius:0}.list-group-horizontal-lg .list-group-item:last-child{border-top-right-radius:.25rem;border-bottom-left-radius:0}.list-group-horizontal-lg .list-group-item.active{margin-top:0}.list-group-horizontal-lg .list-group-item+.list-group-item{border-top-width:1px;border-left-width:0}.list-group-horizontal-lg .list-group-item+.list-group-item.active{margin-left:-1px;border-left-width:1px}}@media (min-width:1200px){.list-group-horizontal-xl{-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row}.list-group-horizontal-xl .list-group-item:first-child{border-bottom-left-radius:.25rem;border-top-right-radius:0}.list-group-horizontal-xl .list-group-item:last-child{border-top-right-radius:.25rem;border-bottom-left-radius:0}.list-group-horizontal-xl .list-group-item.active{margin-top:0}.list-group-horizontal-xl .list-group-item+.list-group-item{border-top-width:1px;border-left-width:0}.list-group-horizontal-xl .list-group-item+.list-group-item.active{margin-left:-1px;border-left-width:1px}}.list-group-flush .list-group-item{border-right-width:0;border-left-width:0;border-radius:0}.list-group-flush .list-group-item:first-child{border-top-width:0}.list-group-flush:last-child .list-group-item:last-child{border-bottom-width:0}.list-group-item-primary{color:#204a81;background-color:#c9dffd}.list-group-item-primary.list-group-item-action:focus,.list-group-item-primary.list-group-item-action:hover{color:#204a81;background-color:#b0d0fc}.list-group-item-primary.list-group-item-action.active{color:#fff;background-color:#204a81;border-color:#204a81}.list-group-item-secondary{color:#40484e;background-color:#dadee2}.list-group-item-secondary.list-group-item-action:focus,.list-group-item-secondary.list-group-item-action:hover{color:#40484e;background-color:#ccd1d7}.list-group-item-secondary.list-group-item-action.active{color:#fff;background-color:#40484e;border-color:#40484e}.list-group-item-success{color:#096639;background-color:#bceed6}.list-group-item-success.list-group-item-action:focus,.list-group-item-success.list-group-item-action:hover{color:#096639;background-color:#a8e9ca}.list-group-item-success.list-group-item-action.active{color:#fff;background-color:#096639;border-color:#096639}.list-group-item-info{color:#075e6f;background-color:#bbeaf4}.list-group-item-info.list-group-item-action:focus,.list-group-item-info.list-group-item-action:hover{color:#075e6f;background-color:#a5e3f0}.list-group-item-info.list-group-item-action.active{color:#fff;background-color:#075e6f;border-color:#075e6f}.list-group-item-warning{color:#7d5e28;background-color:#fbeacd}.list-group-item-warning.list-group-item-action:focus,.list-group-item-warning.list-group-item-action:hover{color:#7d5e28;background-color:#f9e0b5}.list-group-item-warning.list-group-item-action.active{color:#fff;background-color:#7d5e28;border-color:#7d5e28}.list-group-item-danger{color:#83282b;background-color:#fecdcf}.list-group-item-danger.list-group-item-action:focus,.list-group-item-danger.list-group-item-action:hover{color:#83282b;background-color:#feb4b7}.list-group-item-danger.list-group-item-action.active{color:#fff;background-color:#83282b;border-color:#83282b}.list-group-item-light{color:#7c7e80;background-color:#fbfbfd}.list-group-item-light.list-group-item-action:focus,.list-group-item-light.list-group-item-action:hover{color:#7c7e80;background-color:#eaeaf5}.list-group-item-light.list-group-item-action.active{color:#fff;background-color:#7c7e80;border-color:#7c7e80}.list-group-item-dark{color:#1b1e21;background-color:#c6c8ca}.list-group-item-dark.list-group-item-action:focus,.list-group-item-dark.list-group-item-action:hover{color:#1b1e21;background-color:#b9bbbe}.list-group-item-dark.list-group-item-action.active{color:#fff;background-color:#1b1e21;border-color:#1b1e21}.close{float:right;font-size:1.275rem;font-weight:600;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5}.close:hover{color:#000;text-decoration:none}.close:not(:disabled):not(.disabled):focus,.close:not(:disabled):not(.disabled):hover{opacity:.75}button.close{padding:0;background-color:transparent;border:0;-webkit-appearance:none;-moz-appearance:none;appearance:none}a.close.disabled{pointer-events:none}.toast{max-width:350px;overflow:hidden;font-size:.875rem;background-color:rgba(255,255,255,.85);background-clip:padding-box;border:1px solid rgba(0,0,0,.1);-webkit-box-shadow:0 .25rem .75rem rgba(0,0,0,.1);box-shadow:0 .25rem .75rem rgba(0,0,0,.1);-webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px);opacity:0;border-radius:.25rem}.toast:not(:last-child){margin-bottom:.75rem}.toast.showing{opacity:1}.toast.show{display:block;opacity:1}.toast.hide{display:none}.toast-header{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;padding:.25rem .75rem;color:#7c8a96;background-color:rgba(255,255,255,.85);background-clip:padding-box;border-bottom:1px solid rgba(0,0,0,.05)}.toast-body{padding:.75rem}.modal-open{overflow:hidden}.modal-open .modal{overflow-x:hidden;overflow-y:auto}.modal{position:fixed;top:0;left:0;z-index:1050;display:none;width:100%;height:100%;overflow:hidden;outline:0}.modal-dialog{position:relative;width:auto;margin:.5rem;pointer-events:none}.modal.fade .modal-dialog{-webkit-transition:-webkit-transform .3s ease-out;transition:-webkit-transform .3s ease-out;transition:transform .3s ease-out;transition:transform .3s ease-out,-webkit-transform .3s ease-out;-webkit-transform:translate(0,-50px);transform:translate(0,-50px)}@media (prefers-reduced-motion:reduce){.modal.fade .modal-dialog{-webkit-transition:none;transition:none}}.modal.show .modal-dialog{-webkit-transform:none;transform:none}.modal.modal-static .modal-dialog{-webkit-transform:scale(1.02);transform:scale(1.02)}.modal-dialog-scrollable{display:-webkit-box;display:-ms-flexbox;display:flex;max-height:calc(100% - 1rem)}.modal-dialog-scrollable .modal-content{max-height:calc(100vh - 1rem);overflow:hidden}.modal-dialog-scrollable .modal-footer,.modal-dialog-scrollable .modal-header{-ms-flex-negative:0;flex-shrink:0}.modal-dialog-scrollable .modal-body{overflow-y:auto}.modal-dialog-centered{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;min-height:calc(100% - 1rem)}.modal-dialog-centered::before{display:block;height:calc(100vh - 1rem);content:""}.modal-dialog-centered.modal-dialog-scrollable{-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;height:100%}.modal-dialog-centered.modal-dialog-scrollable .modal-content{max-height:none}.modal-dialog-centered.modal-dialog-scrollable::before{content:none}.modal-content{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;width:100%;pointer-events:auto;background-color:#fff;background-clip:padding-box;border:1px solid #f4f8f9;border-radius:.5rem;outline:0}.modal-backdrop{position:fixed;top:0;left:0;z-index:1040;width:100vw;height:100vh;background-color:#000}.modal-backdrop.fade{opacity:0}.modal-backdrop.show{opacity:.5}.modal-header{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:start;-ms-flex-align:start;align-items:flex-start;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between;padding:1rem 1rem;border-bottom:1px solid #eff2f7;border-top-left-radius:calc(.5rem - 1px);border-top-right-radius:calc(.5rem - 1px)}.modal-header .close{padding:1rem 1rem;margin:-1rem -1rem -1rem auto}.modal-title{margin-bottom:0;line-height:1.5}.modal-body{position:relative;-webkit-box-flex:1;-ms-flex:1 1 auto;flex:1 1 auto;padding:1rem}.modal-footer{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:end;-ms-flex-pack:end;justify-content:flex-end;padding:.75rem;border-top:1px solid #eff2f7;border-bottom-right-radius:calc(.5rem - 1px);border-bottom-left-radius:calc(.5rem - 1px)}.modal-footer>*{margin:.25rem}.modal-scrollbar-measure{position:absolute;top:-9999px;width:50px;height:50px;overflow:scroll}@media (min-width:576px){.modal-dialog{max-width:500px;margin:1.75rem auto}.modal-dialog-scrollable{max-height:calc(100% - 3.5rem)}.modal-dialog-scrollable .modal-content{max-height:calc(100vh - 3.5rem)}.modal-dialog-centered{min-height:calc(100% - 3.5rem)}.modal-dialog-centered::before{height:calc(100vh - 3.5rem)}.modal-sm{max-width:300px}}@media (min-width:992px){.modal-lg,.modal-xl{max-width:800px}}@media (min-width:1200px){.modal-xl{max-width:1140px}}.tooltip{position:absolute;z-index:1070;display:block;margin:0;font-family:Rubik,sans-serif;font-style:normal;font-weight:400;line-height:1.5;text-align:left;text-align:start;text-decoration:none;text-shadow:none;text-transform:none;letter-spacing:normal;word-break:normal;word-spacing:normal;white-space:normal;line-break:auto;font-size:.74375rem;word-wrap:break-word;opacity:0}.tooltip.show{opacity:.9}.tooltip .arrow{position:absolute;display:block;width:.8rem;height:.4rem}.tooltip .arrow::before{position:absolute;content:"";border-color:transparent;border-style:solid}.bs-tooltip-auto[x-placement^=top],.bs-tooltip-top{padding:.4rem 0}.bs-tooltip-auto[x-placement^=top] .arrow,.bs-tooltip-top .arrow{bottom:0}.bs-tooltip-auto[x-placement^=top] .arrow::before,.bs-tooltip-top .arrow::before{top:0;border-width:.4rem .4rem 0;border-top-color:#212529}.bs-tooltip-auto[x-placement^=right],.bs-tooltip-right{padding:0 .4rem}.bs-tooltip-auto[x-placement^=right] .arrow,.bs-tooltip-right .arrow{left:0;width:.4rem;height:.8rem}.bs-tooltip-auto[x-placement^=right] .arrow::before,.bs-tooltip-right .arrow::before{right:0;border-width:.4rem .4rem .4rem 0;border-right-color:#212529}.bs-tooltip-auto[x-placement^=bottom],.bs-tooltip-bottom{padding:.4rem 0}.bs-tooltip-auto[x-placement^=bottom] .arrow,.bs-tooltip-bottom .arrow{top:0}.bs-tooltip-auto[x-placement^=bottom] .arrow::before,.bs-tooltip-bottom .arrow::before{bottom:0;border-width:0 .4rem .4rem;border-bottom-color:#212529}.bs-tooltip-auto[x-placement^=left],.bs-tooltip-left{padding:0 .4rem}.bs-tooltip-auto[x-placement^=left] .arrow,.bs-tooltip-left .arrow{right:0;width:.4rem;height:.8rem}.bs-tooltip-auto[x-placement^=left] .arrow::before,.bs-tooltip-left .arrow::before{left:0;border-width:.4rem 0 .4rem .4rem;border-left-color:#212529}.tooltip-inner{max-width:200px;padding:.4rem .7rem;color:#fff;text-align:center;background-color:#212529;border-radius:.25rem}.popover{position:absolute;top:0;left:0;z-index:1060;display:block;max-width:276px;font-family:Rubik,sans-serif;font-style:normal;font-weight:400;line-height:1.5;text-align:left;text-align:start;text-decoration:none;text-shadow:none;text-transform:none;letter-spacing:normal;word-break:normal;word-spacing:normal;white-space:normal;line-break:auto;font-size:.74375rem;word-wrap:break-word;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem}.popover .arrow{position:absolute;display:block;width:1rem;height:.5rem;margin:0 .25rem}.popover .arrow::after,.popover .arrow::before{position:absolute;display:block;content:"";border-color:transparent;border-style:solid}.bs-popover-auto[x-placement^=top],.bs-popover-top{margin-bottom:.5rem}.bs-popover-auto[x-placement^=top]>.arrow,.bs-popover-top>.arrow{bottom:calc(-.5rem - 1px)}.bs-popover-auto[x-placement^=top]>.arrow::before,.bs-popover-top>.arrow::before{bottom:0;border-width:.5rem .5rem 0;border-top-color:#ced4da}.bs-popover-auto[x-placement^=top]>.arrow::after,.bs-popover-top>.arrow::after{bottom:1px;border-width:.5rem .5rem 0;border-top-color:#fff}.bs-popover-auto[x-placement^=right],.bs-popover-right{margin-left:.5rem}.bs-popover-auto[x-placement^=right]>.arrow,.bs-popover-right>.arrow{left:calc(-.5rem - 1px);width:.5rem;height:1rem;margin:.25rem 0}.bs-popover-auto[x-placement^=right]>.arrow::before,.bs-popover-right>.arrow::before{left:0;border-width:.5rem .5rem .5rem 0;border-right-color:#ced4da}.bs-popover-auto[x-placement^=right]>.arrow::after,.bs-popover-right>.arrow::after{left:1px;border-width:.5rem .5rem .5rem 0;border-right-color:#fff}.bs-popover-auto[x-placement^=bottom],.bs-popover-bottom{margin-top:.5rem}.bs-popover-auto[x-placement^=bottom]>.arrow,.bs-popover-bottom>.arrow{top:calc(-.5rem - 1px)}.bs-popover-auto[x-placement^=bottom]>.arrow::before,.bs-popover-bottom>.arrow::before{top:0;border-width:0 .5rem .5rem .5rem;border-bottom-color:#ced4da}.bs-popover-auto[x-placement^=bottom]>.arrow::after,.bs-popover-bottom>.arrow::after{top:1px;border-width:0 .5rem .5rem .5rem;border-bottom-color:#fff}.bs-popover-auto[x-placement^=bottom] .popover-header::before,.bs-popover-bottom .popover-header::before{position:absolute;top:0;left:50%;display:block;width:1rem;margin-left:-.5rem;content:"";border-bottom:1px solid #f7f7f7}.bs-popover-auto[x-placement^=left],.bs-popover-left{margin-right:.5rem}.bs-popover-auto[x-placement^=left]>.arrow,.bs-popover-left>.arrow{right:calc(-.5rem - 1px);width:.5rem;height:1rem;margin:.25rem 0}.bs-popover-auto[x-placement^=left]>.arrow::before,.bs-popover-left>.arrow::before{right:0;border-width:.5rem 0 .5rem .5rem;border-left-color:#ced4da}.bs-popover-auto[x-placement^=left]>.arrow::after,.bs-popover-left>.arrow::after{right:1px;border-width:.5rem 0 .5rem .5rem;border-left-color:#fff}.popover-header{padding:.5rem .75rem;margin-bottom:0;font-size:.85rem;background-color:#f7f7f7;border-bottom:1px solid #ebebeb;border-top-left-radius:calc(.3rem - 1px);border-top-right-radius:calc(.3rem - 1px)}.popover-header:empty{display:none}.popover-body{padding:.5rem .75rem;color:#505d69}.carousel{position:relative}.carousel.pointer-event{-ms-touch-action:pan-y;touch-action:pan-y}.carousel-inner{position:relative;width:100%;overflow:hidden}.carousel-inner::after{display:block;clear:both;content:""}.carousel-item{position:relative;display:none;float:left;width:100%;margin-right:-100%;-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-transition:-webkit-transform .6s ease-in-out;transition:-webkit-transform .6s ease-in-out;transition:transform .6s ease-in-out;transition:transform .6s ease-in-out,-webkit-transform .6s ease-in-out}@media (prefers-reduced-motion:reduce){.carousel-item{-webkit-transition:none;transition:none}}.carousel-item-next,.carousel-item-prev,.carousel-item.active{display:block}.active.carousel-item-right,.carousel-item-next:not(.carousel-item-left){-webkit-transform:translateX(100%);transform:translateX(100%)}.active.carousel-item-left,.carousel-item-prev:not(.carousel-item-right){-webkit-transform:translateX(-100%);transform:translateX(-100%)}.carousel-fade .carousel-item{opacity:0;-webkit-transition-property:opacity;transition-property:opacity;-webkit-transform:none;transform:none}.carousel-fade .carousel-item-next.carousel-item-left,.carousel-fade .carousel-item-prev.carousel-item-right,.carousel-fade .carousel-item.active{z-index:1;opacity:1}.carousel-fade .active.carousel-item-left,.carousel-fade .active.carousel-item-right{z-index:0;opacity:0;-webkit-transition:opacity 0s .6s;transition:opacity 0s .6s}@media (prefers-reduced-motion:reduce){.carousel-fade .active.carousel-item-left,.carousel-fade .active.carousel-item-right{-webkit-transition:none;transition:none}}.carousel-control-next,.carousel-control-prev{position:absolute;top:0;bottom:0;z-index:1;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;width:15%;color:#fff;text-align:center;opacity:.5;-webkit-transition:opacity .15s ease;transition:opacity .15s ease}@media (prefers-reduced-motion:reduce){.carousel-control-next,.carousel-control-prev{-webkit-transition:none;transition:none}}.carousel-control-next:focus,.carousel-control-next:hover,.carousel-control-prev:focus,.carousel-control-prev:hover{color:#fff;text-decoration:none;outline:0;opacity:.9}.carousel-control-prev{left:0}.carousel-control-next{right:0}.carousel-control-next-icon,.carousel-control-prev-icon{display:inline-block;width:20px;height:20px;background:no-repeat 50%/100% 100%}.carousel-control-prev-icon{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3e%3c/svg%3e")}.carousel-control-next-icon{background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3e%3c/svg%3e")}.carousel-indicators{position:absolute;right:0;bottom:0;left:0;z-index:15;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;padding-left:0;margin-right:15%;margin-left:15%;list-style:none}.carousel-indicators li{-webkit-box-sizing:content-box;box-sizing:content-box;-webkit-box-flex:0;-ms-flex:0 1 auto;flex:0 1 auto;width:30px;height:3px;margin-right:3px;margin-left:3px;text-indent:-999px;cursor:pointer;background-color:#fff;background-clip:padding-box;border-top:10px solid transparent;border-bottom:10px solid transparent;opacity:.5;-webkit-transition:opacity .6s ease;transition:opacity .6s ease}@media (prefers-reduced-motion:reduce){.carousel-indicators li{-webkit-transition:none;transition:none}}.carousel-indicators .active{opacity:1}.carousel-caption{position:absolute;right:15%;bottom:20px;left:15%;z-index:10;padding-top:20px;padding-bottom:20px;color:#fff;text-align:center}@-webkit-keyframes spinner-border{to{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes spinner-border{to{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}.spinner-border{display:inline-block;width:2rem;height:2rem;vertical-align:text-bottom;border:.25em solid currentColor;border-right-color:transparent;border-radius:50%;-webkit-animation:spinner-border .75s linear infinite;animation:spinner-border .75s linear infinite}.spinner-border-sm{width:1rem;height:1rem;border-width:.2em}@-webkit-keyframes spinner-grow{0%{-webkit-transform:scale(0);transform:scale(0)}50%{opacity:1}}@keyframes spinner-grow{0%{-webkit-transform:scale(0);transform:scale(0)}50%{opacity:1}}.spinner-grow{display:inline-block;width:2rem;height:2rem;vertical-align:text-bottom;background-color:currentColor;border-radius:50%;opacity:0;-webkit-animation:spinner-grow .75s linear infinite;animation:spinner-grow .75s linear infinite}.spinner-grow-sm{width:1rem;height:1rem}.align-baseline{vertical-align:baseline!important}.align-top{vertical-align:top!important}.align-middle{vertical-align:middle!important}.align-bottom{vertical-align:bottom!important}.align-text-bottom{vertical-align:text-bottom!important}.align-text-top{vertical-align:text-top!important}.bg-primary{background-color:#3d8ef8!important}a.bg-primary:focus,a.bg-primary:hover,button.bg-primary:focus,button.bg-primary:hover{background-color:#0c71f6!important}.bg-secondary{background-color:#7c8a96!important}a.bg-secondary:focus,a.bg-secondary:hover,button.bg-secondary:focus,button.bg-secondary:hover{background-color:#63707c!important}.bg-success{background-color:#11c46e!important}a.bg-success:focus,a.bg-success:hover,button.bg-success:focus,button.bg-success:hover{background-color:#0d9554!important}.bg-info{background-color:#0db4d6!important}a.bg-info:focus,a.bg-info:hover,button.bg-info:focus,button.bg-info:hover{background-color:#0a8ca6!important}.bg-warning{background-color:#f1b44c!important}a.bg-warning:focus,a.bg-warning:hover,button.bg-warning:focus,button.bg-warning:hover{background-color:#eda01d!important}.bg-danger{background-color:#fb4d53!important}a.bg-danger:focus,a.bg-danger:hover,button.bg-danger:focus,button.bg-danger:hover{background-color:#fa1b23!important}.bg-light{background-color:#eff2f7!important}a.bg-light:focus,a.bg-light:hover,button.bg-light:focus,button.bg-light:hover{background-color:#cdd6e6!important}.bg-dark{background-color:#343a40!important}a.bg-dark:focus,a.bg-dark:hover,button.bg-dark:focus,button.bg-dark:hover{background-color:#1d2124!important}.bg-white{background-color:#fff!important}.bg-transparent{background-color:transparent!important}.border{border:1px solid #eff2f7!important}.border-top{border-top:1px solid #eff2f7!important}.border-right{border-right:1px solid #eff2f7!important}.border-bottom{border-bottom:1px solid #eff2f7!important}.border-left{border-left:1px solid #eff2f7!important}.border-0{border:0!important}.border-top-0{border-top:0!important}.border-right-0{border-right:0!important}.border-bottom-0{border-bottom:0!important}.border-left-0{border-left:0!important}.border-primary{border-color:#3d8ef8!important}.border-secondary{border-color:#7c8a96!important}.border-success{border-color:#11c46e!important}.border-info{border-color:#0db4d6!important}.border-warning{border-color:#f1b44c!important}.border-danger{border-color:#fb4d53!important}.border-light{border-color:#eff2f7!important}.border-dark{border-color:#343a40!important}.border-white{border-color:#fff!important}.rounded-sm{border-radius:.2rem!important}.rounded{border-radius:.25rem!important}.rounded-top{border-top-left-radius:.25rem!important;border-top-right-radius:.25rem!important}.rounded-right{border-top-right-radius:.25rem!important;border-bottom-right-radius:.25rem!important}.rounded-bottom{border-bottom-right-radius:.25rem!important;border-bottom-left-radius:.25rem!important}.rounded-left{border-top-left-radius:.25rem!important;border-bottom-left-radius:.25rem!important}.rounded-lg{border-radius:.5rem!important}.rounded-circle{border-radius:50%!important}.rounded-pill{border-radius:50rem!important}.rounded-0{border-radius:0!important}.clearfix::after{display:block;clear:both;content:""}.d-none{display:none!important}.d-inline{display:inline!important}.d-inline-block{display:inline-block!important}.d-block{display:block!important}.d-table{display:table!important}.d-table-row{display:table-row!important}.d-table-cell{display:table-cell!important}.d-flex{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important}.d-inline-flex{display:-webkit-inline-box!important;display:-ms-inline-flexbox!important;display:inline-flex!important}@media (min-width:576px){.d-sm-none{display:none!important}.d-sm-inline{display:inline!important}.d-sm-inline-block{display:inline-block!important}.d-sm-block{display:block!important}.d-sm-table{display:table!important}.d-sm-table-row{display:table-row!important}.d-sm-table-cell{display:table-cell!important}.d-sm-flex{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important}.d-sm-inline-flex{display:-webkit-inline-box!important;display:-ms-inline-flexbox!important;display:inline-flex!important}}@media (min-width:768px){.d-md-none{display:none!important}.d-md-inline{display:inline!important}.d-md-inline-block{display:inline-block!important}.d-md-block{display:block!important}.d-md-table{display:table!important}.d-md-table-row{display:table-row!important}.d-md-table-cell{display:table-cell!important}.d-md-flex{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important}.d-md-inline-flex{display:-webkit-inline-box!important;display:-ms-inline-flexbox!important;display:inline-flex!important}}@media (min-width:992px){.d-lg-none{display:none!important}.d-lg-inline{display:inline!important}.d-lg-inline-block{display:inline-block!important}.d-lg-block{display:block!important}.d-lg-table{display:table!important}.d-lg-table-row{display:table-row!important}.d-lg-table-cell{display:table-cell!important}.d-lg-flex{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important}.d-lg-inline-flex{display:-webkit-inline-box!important;display:-ms-inline-flexbox!important;display:inline-flex!important}}@media (min-width:1200px){.d-xl-none{display:none!important}.d-xl-inline{display:inline!important}.d-xl-inline-block{display:inline-block!important}.d-xl-block{display:block!important}.d-xl-table{display:table!important}.d-xl-table-row{display:table-row!important}.d-xl-table-cell{display:table-cell!important}.d-xl-flex{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important}.d-xl-inline-flex{display:-webkit-inline-box!important;display:-ms-inline-flexbox!important;display:inline-flex!important}}@media print{.d-print-none{display:none!important}.d-print-inline{display:inline!important}.d-print-inline-block{display:inline-block!important}.d-print-block{display:block!important}.d-print-table{display:table!important}.d-print-table-row{display:table-row!important}.d-print-table-cell{display:table-cell!important}.d-print-flex{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important}.d-print-inline-flex{display:-webkit-inline-box!important;display:-ms-inline-flexbox!important;display:inline-flex!important}}.embed-responsive{position:relative;display:block;width:100%;padding:0;overflow:hidden}.embed-responsive::before{display:block;content:""}.embed-responsive .embed-responsive-item,.embed-responsive embed,.embed-responsive iframe,.embed-responsive object,.embed-responsive video{position:absolute;top:0;bottom:0;left:0;width:100%;height:100%;border:0}.embed-responsive-21by9::before{padding-top:42.85714%}.embed-responsive-16by9::before{padding-top:56.25%}.embed-responsive-4by3::before{padding-top:75%}.embed-responsive-1by1::before{padding-top:100%}.embed-responsive-21by9::before{padding-top:42.85714%}.embed-responsive-16by9::before{padding-top:56.25%}.embed-responsive-4by3::before{padding-top:75%}.embed-responsive-1by1::before{padding-top:100%}.flex-row{-webkit-box-orient:horizontal!important;-webkit-box-direction:normal!important;-ms-flex-direction:row!important;flex-direction:row!important}.flex-column{-webkit-box-orient:vertical!important;-webkit-box-direction:normal!important;-ms-flex-direction:column!important;flex-direction:column!important}.flex-row-reverse{-webkit-box-orient:horizontal!important;-webkit-box-direction:reverse!important;-ms-flex-direction:row-reverse!important;flex-direction:row-reverse!important}.flex-column-reverse{-webkit-box-orient:vertical!important;-webkit-box-direction:reverse!important;-ms-flex-direction:column-reverse!important;flex-direction:column-reverse!important}.flex-wrap{-ms-flex-wrap:wrap!important;flex-wrap:wrap!important}.flex-nowrap{-ms-flex-wrap:nowrap!important;flex-wrap:nowrap!important}.flex-wrap-reverse{-ms-flex-wrap:wrap-reverse!important;flex-wrap:wrap-reverse!important}.flex-fill{-webkit-box-flex:1!important;-ms-flex:1 1 auto!important;flex:1 1 auto!important}.flex-grow-0{-webkit-box-flex:0!important;-ms-flex-positive:0!important;flex-grow:0!important}.flex-grow-1{-webkit-box-flex:1!important;-ms-flex-positive:1!important;flex-grow:1!important}.flex-shrink-0{-ms-flex-negative:0!important;flex-shrink:0!important}.flex-shrink-1{-ms-flex-negative:1!important;flex-shrink:1!important}.justify-content-start{-webkit-box-pack:start!important;-ms-flex-pack:start!important;justify-content:flex-start!important}.justify-content-end{-webkit-box-pack:end!important;-ms-flex-pack:end!important;justify-content:flex-end!important}.justify-content-center{-webkit-box-pack:center!important;-ms-flex-pack:center!important;justify-content:center!important}.justify-content-between{-webkit-box-pack:justify!important;-ms-flex-pack:justify!important;justify-content:space-between!important}.justify-content-around{-ms-flex-pack:distribute!important;justify-content:space-around!important}.align-items-start{-webkit-box-align:start!important;-ms-flex-align:start!important;align-items:flex-start!important}.align-items-end{-webkit-box-align:end!important;-ms-flex-align:end!important;align-items:flex-end!important}.align-items-center{-webkit-box-align:center!important;-ms-flex-align:center!important;align-items:center!important}.align-items-baseline{-webkit-box-align:baseline!important;-ms-flex-align:baseline!important;align-items:baseline!important}.align-items-stretch{-webkit-box-align:stretch!important;-ms-flex-align:stretch!important;align-items:stretch!important}.align-content-start{-ms-flex-line-pack:start!important;align-content:flex-start!important}.align-content-end{-ms-flex-line-pack:end!important;align-content:flex-end!important}.align-content-center{-ms-flex-line-pack:center!important;align-content:center!important}.align-content-between{-ms-flex-line-pack:justify!important;align-content:space-between!important}.align-content-around{-ms-flex-line-pack:distribute!important;align-content:space-around!important}.align-content-stretch{-ms-flex-line-pack:stretch!important;align-content:stretch!important}.align-self-auto{-ms-flex-item-align:auto!important;align-self:auto!important}.align-self-start{-ms-flex-item-align:start!important;align-self:flex-start!important}.align-self-end{-ms-flex-item-align:end!important;align-self:flex-end!important}.align-self-center{-ms-flex-item-align:center!important;align-self:center!important}.align-self-baseline{-ms-flex-item-align:baseline!important;align-self:baseline!important}.align-self-stretch{-ms-flex-item-align:stretch!important;align-self:stretch!important}@media (min-width:576px){.flex-sm-row{-webkit-box-orient:horizontal!important;-webkit-box-direction:normal!important;-ms-flex-direction:row!important;flex-direction:row!important}.flex-sm-column{-webkit-box-orient:vertical!important;-webkit-box-direction:normal!important;-ms-flex-direction:column!important;flex-direction:column!important}.flex-sm-row-reverse{-webkit-box-orient:horizontal!important;-webkit-box-direction:reverse!important;-ms-flex-direction:row-reverse!important;flex-direction:row-reverse!important}.flex-sm-column-reverse{-webkit-box-orient:vertical!important;-webkit-box-direction:reverse!important;-ms-flex-direction:column-reverse!important;flex-direction:column-reverse!important}.flex-sm-wrap{-ms-flex-wrap:wrap!important;flex-wrap:wrap!important}.flex-sm-nowrap{-ms-flex-wrap:nowrap!important;flex-wrap:nowrap!important}.flex-sm-wrap-reverse{-ms-flex-wrap:wrap-reverse!important;flex-wrap:wrap-reverse!important}.flex-sm-fill{-webkit-box-flex:1!important;-ms-flex:1 1 auto!important;flex:1 1 auto!important}.flex-sm-grow-0{-webkit-box-flex:0!important;-ms-flex-positive:0!important;flex-grow:0!important}.flex-sm-grow-1{-webkit-box-flex:1!important;-ms-flex-positive:1!important;flex-grow:1!important}.flex-sm-shrink-0{-ms-flex-negative:0!important;flex-shrink:0!important}.flex-sm-shrink-1{-ms-flex-negative:1!important;flex-shrink:1!important}.justify-content-sm-start{-webkit-box-pack:start!important;-ms-flex-pack:start!important;justify-content:flex-start!important}.justify-content-sm-end{-webkit-box-pack:end!important;-ms-flex-pack:end!important;justify-content:flex-end!important}.justify-content-sm-center{-webkit-box-pack:center!important;-ms-flex-pack:center!important;justify-content:center!important}.justify-content-sm-between{-webkit-box-pack:justify!important;-ms-flex-pack:justify!important;justify-content:space-between!important}.justify-content-sm-around{-ms-flex-pack:distribute!important;justify-content:space-around!important}.align-items-sm-start{-webkit-box-align:start!important;-ms-flex-align:start!important;align-items:flex-start!important}.align-items-sm-end{-webkit-box-align:end!important;-ms-flex-align:end!important;align-items:flex-end!important}.align-items-sm-center{-webkit-box-align:center!important;-ms-flex-align:center!important;align-items:center!important}.align-items-sm-baseline{-webkit-box-align:baseline!important;-ms-flex-align:baseline!important;align-items:baseline!important}.align-items-sm-stretch{-webkit-box-align:stretch!important;-ms-flex-align:stretch!important;align-items:stretch!important}.align-content-sm-start{-ms-flex-line-pack:start!important;align-content:flex-start!important}.align-content-sm-end{-ms-flex-line-pack:end!important;align-content:flex-end!important}.align-content-sm-center{-ms-flex-line-pack:center!important;align-content:center!important}.align-content-sm-between{-ms-flex-line-pack:justify!important;align-content:space-between!important}.align-content-sm-around{-ms-flex-line-pack:distribute!important;align-content:space-around!important}.align-content-sm-stretch{-ms-flex-line-pack:stretch!important;align-content:stretch!important}.align-self-sm-auto{-ms-flex-item-align:auto!important;align-self:auto!important}.align-self-sm-start{-ms-flex-item-align:start!important;align-self:flex-start!important}.align-self-sm-end{-ms-flex-item-align:end!important;align-self:flex-end!important}.align-self-sm-center{-ms-flex-item-align:center!important;align-self:center!important}.align-self-sm-baseline{-ms-flex-item-align:baseline!important;align-self:baseline!important}.align-self-sm-stretch{-ms-flex-item-align:stretch!important;align-self:stretch!important}}@media (min-width:768px){.flex-md-row{-webkit-box-orient:horizontal!important;-webkit-box-direction:normal!important;-ms-flex-direction:row!important;flex-direction:row!important}.flex-md-column{-webkit-box-orient:vertical!important;-webkit-box-direction:normal!important;-ms-flex-direction:column!important;flex-direction:column!important}.flex-md-row-reverse{-webkit-box-orient:horizontal!important;-webkit-box-direction:reverse!important;-ms-flex-direction:row-reverse!important;flex-direction:row-reverse!important}.flex-md-column-reverse{-webkit-box-orient:vertical!important;-webkit-box-direction:reverse!important;-ms-flex-direction:column-reverse!important;flex-direction:column-reverse!important}.flex-md-wrap{-ms-flex-wrap:wrap!important;flex-wrap:wrap!important}.flex-md-nowrap{-ms-flex-wrap:nowrap!important;flex-wrap:nowrap!important}.flex-md-wrap-reverse{-ms-flex-wrap:wrap-reverse!important;flex-wrap:wrap-reverse!important}.flex-md-fill{-webkit-box-flex:1!important;-ms-flex:1 1 auto!important;flex:1 1 auto!important}.flex-md-grow-0{-webkit-box-flex:0!important;-ms-flex-positive:0!important;flex-grow:0!important}.flex-md-grow-1{-webkit-box-flex:1!important;-ms-flex-positive:1!important;flex-grow:1!important}.flex-md-shrink-0{-ms-flex-negative:0!important;flex-shrink:0!important}.flex-md-shrink-1{-ms-flex-negative:1!important;flex-shrink:1!important}.justify-content-md-start{-webkit-box-pack:start!important;-ms-flex-pack:start!important;justify-content:flex-start!important}.justify-content-md-end{-webkit-box-pack:end!important;-ms-flex-pack:end!important;justify-content:flex-end!important}.justify-content-md-center{-webkit-box-pack:center!important;-ms-flex-pack:center!important;justify-content:center!important}.justify-content-md-between{-webkit-box-pack:justify!important;-ms-flex-pack:justify!important;justify-content:space-between!important}.justify-content-md-around{-ms-flex-pack:distribute!important;justify-content:space-around!important}.align-items-md-start{-webkit-box-align:start!important;-ms-flex-align:start!important;align-items:flex-start!important}.align-items-md-end{-webkit-box-align:end!important;-ms-flex-align:end!important;align-items:flex-end!important}.align-items-md-center{-webkit-box-align:center!important;-ms-flex-align:center!important;align-items:center!important}.align-items-md-baseline{-webkit-box-align:baseline!important;-ms-flex-align:baseline!important;align-items:baseline!important}.align-items-md-stretch{-webkit-box-align:stretch!important;-ms-flex-align:stretch!important;align-items:stretch!important}.align-content-md-start{-ms-flex-line-pack:start!important;align-content:flex-start!important}.align-content-md-end{-ms-flex-line-pack:end!important;align-content:flex-end!important}.align-content-md-center{-ms-flex-line-pack:center!important;align-content:center!important}.align-content-md-between{-ms-flex-line-pack:justify!important;align-content:space-between!important}.align-content-md-around{-ms-flex-line-pack:distribute!important;align-content:space-around!important}.align-content-md-stretch{-ms-flex-line-pack:stretch!important;align-content:stretch!important}.align-self-md-auto{-ms-flex-item-align:auto!important;align-self:auto!important}.align-self-md-start{-ms-flex-item-align:start!important;align-self:flex-start!important}.align-self-md-end{-ms-flex-item-align:end!important;align-self:flex-end!important}.align-self-md-center{-ms-flex-item-align:center!important;align-self:center!important}.align-self-md-baseline{-ms-flex-item-align:baseline!important;align-self:baseline!important}.align-self-md-stretch{-ms-flex-item-align:stretch!important;align-self:stretch!important}}@media (min-width:992px){.flex-lg-row{-webkit-box-orient:horizontal!important;-webkit-box-direction:normal!important;-ms-flex-direction:row!important;flex-direction:row!important}.flex-lg-column{-webkit-box-orient:vertical!important;-webkit-box-direction:normal!important;-ms-flex-direction:column!important;flex-direction:column!important}.flex-lg-row-reverse{-webkit-box-orient:horizontal!important;-webkit-box-direction:reverse!important;-ms-flex-direction:row-reverse!important;flex-direction:row-reverse!important}.flex-lg-column-reverse{-webkit-box-orient:vertical!important;-webkit-box-direction:reverse!important;-ms-flex-direction:column-reverse!important;flex-direction:column-reverse!important}.flex-lg-wrap{-ms-flex-wrap:wrap!important;flex-wrap:wrap!important}.flex-lg-nowrap{-ms-flex-wrap:nowrap!important;flex-wrap:nowrap!important}.flex-lg-wrap-reverse{-ms-flex-wrap:wrap-reverse!important;flex-wrap:wrap-reverse!important}.flex-lg-fill{-webkit-box-flex:1!important;-ms-flex:1 1 auto!important;flex:1 1 auto!important}.flex-lg-grow-0{-webkit-box-flex:0!important;-ms-flex-positive:0!important;flex-grow:0!important}.flex-lg-grow-1{-webkit-box-flex:1!important;-ms-flex-positive:1!important;flex-grow:1!important}.flex-lg-shrink-0{-ms-flex-negative:0!important;flex-shrink:0!important}.flex-lg-shrink-1{-ms-flex-negative:1!important;flex-shrink:1!important}.justify-content-lg-start{-webkit-box-pack:start!important;-ms-flex-pack:start!important;justify-content:flex-start!important}.justify-content-lg-end{-webkit-box-pack:end!important;-ms-flex-pack:end!important;justify-content:flex-end!important}.justify-content-lg-center{-webkit-box-pack:center!important;-ms-flex-pack:center!important;justify-content:center!important}.justify-content-lg-between{-webkit-box-pack:justify!important;-ms-flex-pack:justify!important;justify-content:space-between!important}.justify-content-lg-around{-ms-flex-pack:distribute!important;justify-content:space-around!important}.align-items-lg-start{-webkit-box-align:start!important;-ms-flex-align:start!important;align-items:flex-start!important}.align-items-lg-end{-webkit-box-align:end!important;-ms-flex-align:end!important;align-items:flex-end!important}.align-items-lg-center{-webkit-box-align:center!important;-ms-flex-align:center!important;align-items:center!important}.align-items-lg-baseline{-webkit-box-align:baseline!important;-ms-flex-align:baseline!important;align-items:baseline!important}.align-items-lg-stretch{-webkit-box-align:stretch!important;-ms-flex-align:stretch!important;align-items:stretch!important}.align-content-lg-start{-ms-flex-line-pack:start!important;align-content:flex-start!important}.align-content-lg-end{-ms-flex-line-pack:end!important;align-content:flex-end!important}.align-content-lg-center{-ms-flex-line-pack:center!important;align-content:center!important}.align-content-lg-between{-ms-flex-line-pack:justify!important;align-content:space-between!important}.align-content-lg-around{-ms-flex-line-pack:distribute!important;align-content:space-around!important}.align-content-lg-stretch{-ms-flex-line-pack:stretch!important;align-content:stretch!important}.align-self-lg-auto{-ms-flex-item-align:auto!important;align-self:auto!important}.align-self-lg-start{-ms-flex-item-align:start!important;align-self:flex-start!important}.align-self-lg-end{-ms-flex-item-align:end!important;align-self:flex-end!important}.align-self-lg-center{-ms-flex-item-align:center!important;align-self:center!important}.align-self-lg-baseline{-ms-flex-item-align:baseline!important;align-self:baseline!important}.align-self-lg-stretch{-ms-flex-item-align:stretch!important;align-self:stretch!important}}@media (min-width:1200px){.flex-xl-row{-webkit-box-orient:horizontal!important;-webkit-box-direction:normal!important;-ms-flex-direction:row!important;flex-direction:row!important}.flex-xl-column{-webkit-box-orient:vertical!important;-webkit-box-direction:normal!important;-ms-flex-direction:column!important;flex-direction:column!important}.flex-xl-row-reverse{-webkit-box-orient:horizontal!important;-webkit-box-direction:reverse!important;-ms-flex-direction:row-reverse!important;flex-direction:row-reverse!important}.flex-xl-column-reverse{-webkit-box-orient:vertical!important;-webkit-box-direction:reverse!important;-ms-flex-direction:column-reverse!important;flex-direction:column-reverse!important}.flex-xl-wrap{-ms-flex-wrap:wrap!important;flex-wrap:wrap!important}.flex-xl-nowrap{-ms-flex-wrap:nowrap!important;flex-wrap:nowrap!important}.flex-xl-wrap-reverse{-ms-flex-wrap:wrap-reverse!important;flex-wrap:wrap-reverse!important}.flex-xl-fill{-webkit-box-flex:1!important;-ms-flex:1 1 auto!important;flex:1 1 auto!important}.flex-xl-grow-0{-webkit-box-flex:0!important;-ms-flex-positive:0!important;flex-grow:0!important}.flex-xl-grow-1{-webkit-box-flex:1!important;-ms-flex-positive:1!important;flex-grow:1!important}.flex-xl-shrink-0{-ms-flex-negative:0!important;flex-shrink:0!important}.flex-xl-shrink-1{-ms-flex-negative:1!important;flex-shrink:1!important}.justify-content-xl-start{-webkit-box-pack:start!important;-ms-flex-pack:start!important;justify-content:flex-start!important}.justify-content-xl-end{-webkit-box-pack:end!important;-ms-flex-pack:end!important;justify-content:flex-end!important}.justify-content-xl-center{-webkit-box-pack:center!important;-ms-flex-pack:center!important;justify-content:center!important}.justify-content-xl-between{-webkit-box-pack:justify!important;-ms-flex-pack:justify!important;justify-content:space-between!important}.justify-content-xl-around{-ms-flex-pack:distribute!important;justify-content:space-around!important}.align-items-xl-start{-webkit-box-align:start!important;-ms-flex-align:start!important;align-items:flex-start!important}.align-items-xl-end{-webkit-box-align:end!important;-ms-flex-align:end!important;align-items:flex-end!important}.align-items-xl-center{-webkit-box-align:center!important;-ms-flex-align:center!important;align-items:center!important}.align-items-xl-baseline{-webkit-box-align:baseline!important;-ms-flex-align:baseline!important;align-items:baseline!important}.align-items-xl-stretch{-webkit-box-align:stretch!important;-ms-flex-align:stretch!important;align-items:stretch!important}.align-content-xl-start{-ms-flex-line-pack:start!important;align-content:flex-start!important}.align-content-xl-end{-ms-flex-line-pack:end!important;align-content:flex-end!important}.align-content-xl-center{-ms-flex-line-pack:center!important;align-content:center!important}.align-content-xl-between{-ms-flex-line-pack:justify!important;align-content:space-between!important}.align-content-xl-around{-ms-flex-line-pack:distribute!important;align-content:space-around!important}.align-content-xl-stretch{-ms-flex-line-pack:stretch!important;align-content:stretch!important}.align-self-xl-auto{-ms-flex-item-align:auto!important;align-self:auto!important}.align-self-xl-start{-ms-flex-item-align:start!important;align-self:flex-start!important}.align-self-xl-end{-ms-flex-item-align:end!important;align-self:flex-end!important}.align-self-xl-center{-ms-flex-item-align:center!important;align-self:center!important}.align-self-xl-baseline{-ms-flex-item-align:baseline!important;align-self:baseline!important}.align-self-xl-stretch{-ms-flex-item-align:stretch!important;align-self:stretch!important}}.float-left{float:left!important}.float-right{float:right!important}.float-none{float:none!important}@media (min-width:576px){.float-sm-left{float:left!important}.float-sm-right{float:right!important}.float-sm-none{float:none!important}}@media (min-width:768px){.float-md-left{float:left!important}.float-md-right{float:right!important}.float-md-none{float:none!important}}@media (min-width:992px){.float-lg-left{float:left!important}.float-lg-right{float:right!important}.float-lg-none{float:none!important}}@media (min-width:1200px){.float-xl-left{float:left!important}.float-xl-right{float:right!important}.float-xl-none{float:none!important}}.overflow-auto{overflow:auto!important}.overflow-hidden{overflow:hidden!important}.position-static{position:static!important}.position-relative{position:relative!important}.position-absolute{position:absolute!important}.position-fixed{position:fixed!important}.position-sticky{position:-webkit-sticky!important;position:sticky!important}.fixed-top{position:fixed;top:0;right:0;left:0;z-index:1030}.fixed-bottom{position:fixed;right:0;bottom:0;left:0;z-index:1030}@supports ((position:-webkit-sticky) or (position:sticky)){.sticky-top{position:-webkit-sticky;position:sticky;top:0;z-index:1020}}.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}.sr-only-focusable:active,.sr-only-focusable:focus{position:static;width:auto;height:auto;overflow:visible;clip:auto;white-space:normal}.shadow-sm{-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05)!important;box-shadow:0 1px 1px rgba(0,0,0,.05)!important}.shadow{-webkit-box-shadow:0 2px 4px rgba(0,0,0,.08)!important;box-shadow:0 2px 4px rgba(0,0,0,.08)!important}.shadow-lg{-webkit-box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important;box-shadow:0 1rem 3rem rgba(0,0,0,.175)!important}.shadow-none{-webkit-box-shadow:none!important;box-shadow:none!important}.w-25{width:25%!important}.w-50{width:50%!important}.w-75{width:75%!important}.w-100{width:100%!important}.w-auto{width:auto!important}.h-25{height:25%!important}.h-50{height:50%!important}.h-75{height:75%!important}.h-100{height:100%!important}.h-auto{height:auto!important}.mw-100{max-width:100%!important}.mh-100{max-height:100%!important}.min-vw-100{min-width:100vw!important}.min-vh-100{min-height:100vh!important}.vw-100{width:100vw!important}.vh-100{height:100vh!important}.stretched-link::after{position:absolute;top:0;right:0;bottom:0;left:0;z-index:1;pointer-events:auto;content:"";background-color:rgba(0,0,0,0)}.m-0{margin:0!important}.mt-0,.my-0{margin-top:0!important}.mr-0,.mx-0{margin-right:0!important}.mb-0,.my-0{margin-bottom:0!important}.ml-0,.mx-0{margin-left:0!important}.m-1{margin:.25rem!important}.mt-1,.my-1{margin-top:.25rem!important}.mr-1,.mx-1{margin-right:.25rem!important}.mb-1,.my-1{margin-bottom:.25rem!important}.ml-1,.mx-1{margin-left:.25rem!important}.m-2{margin:.5rem!important}.mt-2,.my-2{margin-top:.5rem!important}.mr-2,.mx-2{margin-right:.5rem!important}.mb-2,.my-2{margin-bottom:.5rem!important}.ml-2,.mx-2{margin-left:.5rem!important}.m-3{margin:1rem!important}.mt-3,.my-3{margin-top:1rem!important}.mr-3,.mx-3{margin-right:1rem!important}.mb-3,.my-3{margin-bottom:1rem!important}.ml-3,.mx-3{margin-left:1rem!important}.m-4{margin:1.5rem!important}.mt-4,.my-4{margin-top:1.5rem!important}.mr-4,.mx-4{margin-right:1.5rem!important}.mb-4,.my-4{margin-bottom:1.5rem!important}.ml-4,.mx-4{margin-left:1.5rem!important}.m-5{margin:3rem!important}.mt-5,.my-5{margin-top:3rem!important}.mr-5,.mx-5{margin-right:3rem!important}.mb-5,.my-5{margin-bottom:3rem!important}.ml-5,.mx-5{margin-left:3rem!important}.p-0{padding:0!important}.pt-0,.py-0{padding-top:0!important}.pr-0,.px-0{padding-right:0!important}.pb-0,.py-0{padding-bottom:0!important}.pl-0,.px-0{padding-left:0!important}.p-1{padding:.25rem!important}.pt-1,.py-1{padding-top:.25rem!important}.pr-1,.px-1{padding-right:.25rem!important}.pb-1,.py-1{padding-bottom:.25rem!important}.pl-1,.px-1{padding-left:.25rem!important}.p-2{padding:.5rem!important}.pt-2,.py-2{padding-top:.5rem!important}.pr-2,.px-2{padding-right:.5rem!important}.pb-2,.py-2{padding-bottom:.5rem!important}.pl-2,.px-2{padding-left:.5rem!important}.p-3{padding:1rem!important}.pt-3,.py-3{padding-top:1rem!important}.pr-3,.px-3{padding-right:1rem!important}.pb-3,.py-3{padding-bottom:1rem!important}.pl-3,.px-3{padding-left:1rem!important}.p-4{padding:1.5rem!important}.pt-4,.py-4{padding-top:1.5rem!important}.pr-4,.px-4{padding-right:1.5rem!important}.pb-4,.py-4{padding-bottom:1.5rem!important}.pl-4,.px-4{padding-left:1.5rem!important}.p-5{padding:3rem!important}.pt-5,.py-5{padding-top:3rem!important}.pr-5,.px-5{padding-right:3rem!important}.pb-5,.py-5{padding-bottom:3rem!important}.pl-5,.px-5{padding-left:3rem!important}.m-n1{margin:-.25rem!important}.mt-n1,.my-n1{margin-top:-.25rem!important}.mr-n1,.mx-n1{margin-right:-.25rem!important}.mb-n1,.my-n1{margin-bottom:-.25rem!important}.ml-n1,.mx-n1{margin-left:-.25rem!important}.m-n2{margin:-.5rem!important}.mt-n2,.my-n2{margin-top:-.5rem!important}.mr-n2,.mx-n2{margin-right:-.5rem!important}.mb-n2,.my-n2{margin-bottom:-.5rem!important}.ml-n2,.mx-n2{margin-left:-.5rem!important}.m-n3{margin:-1rem!important}.mt-n3,.my-n3{margin-top:-1rem!important}.mr-n3,.mx-n3{margin-right:-1rem!important}.mb-n3,.my-n3{margin-bottom:-1rem!important}.ml-n3,.mx-n3{margin-left:-1rem!important}.m-n4{margin:-1.5rem!important}.mt-n4,.my-n4{margin-top:-1.5rem!important}.mr-n4,.mx-n4{margin-right:-1.5rem!important}.mb-n4,.my-n4{margin-bottom:-1.5rem!important}.ml-n4,.mx-n4{margin-left:-1.5rem!important}.m-n5{margin:-3rem!important}.mt-n5,.my-n5{margin-top:-3rem!important}.mr-n5,.mx-n5{margin-right:-3rem!important}.mb-n5,.my-n5{margin-bottom:-3rem!important}.ml-n5,.mx-n5{margin-left:-3rem!important}.m-auto{margin:auto!important}.mt-auto,.my-auto{margin-top:auto!important}.mr-auto,.mx-auto{margin-right:auto!important}.mb-auto,.my-auto{margin-bottom:auto!important}.ml-auto,.mx-auto{margin-left:auto!important}@media (min-width:576px){.m-sm-0{margin:0!important}.mt-sm-0,.my-sm-0{margin-top:0!important}.mr-sm-0,.mx-sm-0{margin-right:0!important}.mb-sm-0,.my-sm-0{margin-bottom:0!important}.ml-sm-0,.mx-sm-0{margin-left:0!important}.m-sm-1{margin:.25rem!important}.mt-sm-1,.my-sm-1{margin-top:.25rem!important}.mr-sm-1,.mx-sm-1{margin-right:.25rem!important}.mb-sm-1,.my-sm-1{margin-bottom:.25rem!important}.ml-sm-1,.mx-sm-1{margin-left:.25rem!important}.m-sm-2{margin:.5rem!important}.mt-sm-2,.my-sm-2{margin-top:.5rem!important}.mr-sm-2,.mx-sm-2{margin-right:.5rem!important}.mb-sm-2,.my-sm-2{margin-bottom:.5rem!important}.ml-sm-2,.mx-sm-2{margin-left:.5rem!important}.m-sm-3{margin:1rem!important}.mt-sm-3,.my-sm-3{margin-top:1rem!important}.mr-sm-3,.mx-sm-3{margin-right:1rem!important}.mb-sm-3,.my-sm-3{margin-bottom:1rem!important}.ml-sm-3,.mx-sm-3{margin-left:1rem!important}.m-sm-4{margin:1.5rem!important}.mt-sm-4,.my-sm-4{margin-top:1.5rem!important}.mr-sm-4,.mx-sm-4{margin-right:1.5rem!important}.mb-sm-4,.my-sm-4{margin-bottom:1.5rem!important}.ml-sm-4,.mx-sm-4{margin-left:1.5rem!important}.m-sm-5{margin:3rem!important}.mt-sm-5,.my-sm-5{margin-top:3rem!important}.mr-sm-5,.mx-sm-5{margin-right:3rem!important}.mb-sm-5,.my-sm-5{margin-bottom:3rem!important}.ml-sm-5,.mx-sm-5{margin-left:3rem!important}.p-sm-0{padding:0!important}.pt-sm-0,.py-sm-0{padding-top:0!important}.pr-sm-0,.px-sm-0{padding-right:0!important}.pb-sm-0,.py-sm-0{padding-bottom:0!important}.pl-sm-0,.px-sm-0{padding-left:0!important}.p-sm-1{padding:.25rem!important}.pt-sm-1,.py-sm-1{padding-top:.25rem!important}.pr-sm-1,.px-sm-1{padding-right:.25rem!important}.pb-sm-1,.py-sm-1{padding-bottom:.25rem!important}.pl-sm-1,.px-sm-1{padding-left:.25rem!important}.p-sm-2{padding:.5rem!important}.pt-sm-2,.py-sm-2{padding-top:.5rem!important}.pr-sm-2,.px-sm-2{padding-right:.5rem!important}.pb-sm-2,.py-sm-2{padding-bottom:.5rem!important}.pl-sm-2,.px-sm-2{padding-left:.5rem!important}.p-sm-3{padding:1rem!important}.pt-sm-3,.py-sm-3{padding-top:1rem!important}.pr-sm-3,.px-sm-3{padding-right:1rem!important}.pb-sm-3,.py-sm-3{padding-bottom:1rem!important}.pl-sm-3,.px-sm-3{padding-left:1rem!important}.p-sm-4{padding:1.5rem!important}.pt-sm-4,.py-sm-4{padding-top:1.5rem!important}.pr-sm-4,.px-sm-4{padding-right:1.5rem!important}.pb-sm-4,.py-sm-4{padding-bottom:1.5rem!important}.pl-sm-4,.px-sm-4{padding-left:1.5rem!important}.p-sm-5{padding:3rem!important}.pt-sm-5,.py-sm-5{padding-top:3rem!important}.pr-sm-5,.px-sm-5{padding-right:3rem!important}.pb-sm-5,.py-sm-5{padding-bottom:3rem!important}.pl-sm-5,.px-sm-5{padding-left:3rem!important}.m-sm-n1{margin:-.25rem!important}.mt-sm-n1,.my-sm-n1{margin-top:-.25rem!important}.mr-sm-n1,.mx-sm-n1{margin-right:-.25rem!important}.mb-sm-n1,.my-sm-n1{margin-bottom:-.25rem!important}.ml-sm-n1,.mx-sm-n1{margin-left:-.25rem!important}.m-sm-n2{margin:-.5rem!important}.mt-sm-n2,.my-sm-n2{margin-top:-.5rem!important}.mr-sm-n2,.mx-sm-n2{margin-right:-.5rem!important}.mb-sm-n2,.my-sm-n2{margin-bottom:-.5rem!important}.ml-sm-n2,.mx-sm-n2{margin-left:-.5rem!important}.m-sm-n3{margin:-1rem!important}.mt-sm-n3,.my-sm-n3{margin-top:-1rem!important}.mr-sm-n3,.mx-sm-n3{margin-right:-1rem!important}.mb-sm-n3,.my-sm-n3{margin-bottom:-1rem!important}.ml-sm-n3,.mx-sm-n3{margin-left:-1rem!important}.m-sm-n4{margin:-1.5rem!important}.mt-sm-n4,.my-sm-n4{margin-top:-1.5rem!important}.mr-sm-n4,.mx-sm-n4{margin-right:-1.5rem!important}.mb-sm-n4,.my-sm-n4{margin-bottom:-1.5rem!important}.ml-sm-n4,.mx-sm-n4{margin-left:-1.5rem!important}.m-sm-n5{margin:-3rem!important}.mt-sm-n5,.my-sm-n5{margin-top:-3rem!important}.mr-sm-n5,.mx-sm-n5{margin-right:-3rem!important}.mb-sm-n5,.my-sm-n5{margin-bottom:-3rem!important}.ml-sm-n5,.mx-sm-n5{margin-left:-3rem!important}.m-sm-auto{margin:auto!important}.mt-sm-auto,.my-sm-auto{margin-top:auto!important}.mr-sm-auto,.mx-sm-auto{margin-right:auto!important}.mb-sm-auto,.my-sm-auto{margin-bottom:auto!important}.ml-sm-auto,.mx-sm-auto{margin-left:auto!important}}@media (min-width:768px){.m-md-0{margin:0!important}.mt-md-0,.my-md-0{margin-top:0!important}.mr-md-0,.mx-md-0{margin-right:0!important}.mb-md-0,.my-md-0{margin-bottom:0!important}.ml-md-0,.mx-md-0{margin-left:0!important}.m-md-1{margin:.25rem!important}.mt-md-1,.my-md-1{margin-top:.25rem!important}.mr-md-1,.mx-md-1{margin-right:.25rem!important}.mb-md-1,.my-md-1{margin-bottom:.25rem!important}.ml-md-1,.mx-md-1{margin-left:.25rem!important}.m-md-2{margin:.5rem!important}.mt-md-2,.my-md-2{margin-top:.5rem!important}.mr-md-2,.mx-md-2{margin-right:.5rem!important}.mb-md-2,.my-md-2{margin-bottom:.5rem!important}.ml-md-2,.mx-md-2{margin-left:.5rem!important}.m-md-3{margin:1rem!important}.mt-md-3,.my-md-3{margin-top:1rem!important}.mr-md-3,.mx-md-3{margin-right:1rem!important}.mb-md-3,.my-md-3{margin-bottom:1rem!important}.ml-md-3,.mx-md-3{margin-left:1rem!important}.m-md-4{margin:1.5rem!important}.mt-md-4,.my-md-4{margin-top:1.5rem!important}.mr-md-4,.mx-md-4{margin-right:1.5rem!important}.mb-md-4,.my-md-4{margin-bottom:1.5rem!important}.ml-md-4,.mx-md-4{margin-left:1.5rem!important}.m-md-5{margin:3rem!important}.mt-md-5,.my-md-5{margin-top:3rem!important}.mr-md-5,.mx-md-5{margin-right:3rem!important}.mb-md-5,.my-md-5{margin-bottom:3rem!important}.ml-md-5,.mx-md-5{margin-left:3rem!important}.p-md-0{padding:0!important}.pt-md-0,.py-md-0{padding-top:0!important}.pr-md-0,.px-md-0{padding-right:0!important}.pb-md-0,.py-md-0{padding-bottom:0!important}.pl-md-0,.px-md-0{padding-left:0!important}.p-md-1{padding:.25rem!important}.pt-md-1,.py-md-1{padding-top:.25rem!important}.pr-md-1,.px-md-1{padding-right:.25rem!important}.pb-md-1,.py-md-1{padding-bottom:.25rem!important}.pl-md-1,.px-md-1{padding-left:.25rem!important}.p-md-2{padding:.5rem!important}.pt-md-2,.py-md-2{padding-top:.5rem!important}.pr-md-2,.px-md-2{padding-right:.5rem!important}.pb-md-2,.py-md-2{padding-bottom:.5rem!important}.pl-md-2,.px-md-2{padding-left:.5rem!important}.p-md-3{padding:1rem!important}.pt-md-3,.py-md-3{padding-top:1rem!important}.pr-md-3,.px-md-3{padding-right:1rem!important}.pb-md-3,.py-md-3{padding-bottom:1rem!important}.pl-md-3,.px-md-3{padding-left:1rem!important}.p-md-4{padding:1.5rem!important}.pt-md-4,.py-md-4{padding-top:1.5rem!important}.pr-md-4,.px-md-4{padding-right:1.5rem!important}.pb-md-4,.py-md-4{padding-bottom:1.5rem!important}.pl-md-4,.px-md-4{padding-left:1.5rem!important}.p-md-5{padding:3rem!important}.pt-md-5,.py-md-5{padding-top:3rem!important}.pr-md-5,.px-md-5{padding-right:3rem!important}.pb-md-5,.py-md-5{padding-bottom:3rem!important}.pl-md-5,.px-md-5{padding-left:3rem!important}.m-md-n1{margin:-.25rem!important}.mt-md-n1,.my-md-n1{margin-top:-.25rem!important}.mr-md-n1,.mx-md-n1{margin-right:-.25rem!important}.mb-md-n1,.my-md-n1{margin-bottom:-.25rem!important}.ml-md-n1,.mx-md-n1{margin-left:-.25rem!important}.m-md-n2{margin:-.5rem!important}.mt-md-n2,.my-md-n2{margin-top:-.5rem!important}.mr-md-n2,.mx-md-n2{margin-right:-.5rem!important}.mb-md-n2,.my-md-n2{margin-bottom:-.5rem!important}.ml-md-n2,.mx-md-n2{margin-left:-.5rem!important}.m-md-n3{margin:-1rem!important}.mt-md-n3,.my-md-n3{margin-top:-1rem!important}.mr-md-n3,.mx-md-n3{margin-right:-1rem!important}.mb-md-n3,.my-md-n3{margin-bottom:-1rem!important}.ml-md-n3,.mx-md-n3{margin-left:-1rem!important}.m-md-n4{margin:-1.5rem!important}.mt-md-n4,.my-md-n4{margin-top:-1.5rem!important}.mr-md-n4,.mx-md-n4{margin-right:-1.5rem!important}.mb-md-n4,.my-md-n4{margin-bottom:-1.5rem!important}.ml-md-n4,.mx-md-n4{margin-left:-1.5rem!important}.m-md-n5{margin:-3rem!important}.mt-md-n5,.my-md-n5{margin-top:-3rem!important}.mr-md-n5,.mx-md-n5{margin-right:-3rem!important}.mb-md-n5,.my-md-n5{margin-bottom:-3rem!important}.ml-md-n5,.mx-md-n5{margin-left:-3rem!important}.m-md-auto{margin:auto!important}.mt-md-auto,.my-md-auto{margin-top:auto!important}.mr-md-auto,.mx-md-auto{margin-right:auto!important}.mb-md-auto,.my-md-auto{margin-bottom:auto!important}.ml-md-auto,.mx-md-auto{margin-left:auto!important}}@media (min-width:992px){.m-lg-0{margin:0!important}.mt-lg-0,.my-lg-0{margin-top:0!important}.mr-lg-0,.mx-lg-0{margin-right:0!important}.mb-lg-0,.my-lg-0{margin-bottom:0!important}.ml-lg-0,.mx-lg-0{margin-left:0!important}.m-lg-1{margin:.25rem!important}.mt-lg-1,.my-lg-1{margin-top:.25rem!important}.mr-lg-1,.mx-lg-1{margin-right:.25rem!important}.mb-lg-1,.my-lg-1{margin-bottom:.25rem!important}.ml-lg-1,.mx-lg-1{margin-left:.25rem!important}.m-lg-2{margin:.5rem!important}.mt-lg-2,.my-lg-2{margin-top:.5rem!important}.mr-lg-2,.mx-lg-2{margin-right:.5rem!important}.mb-lg-2,.my-lg-2{margin-bottom:.5rem!important}.ml-lg-2,.mx-lg-2{margin-left:.5rem!important}.m-lg-3{margin:1rem!important}.mt-lg-3,.my-lg-3{margin-top:1rem!important}.mr-lg-3,.mx-lg-3{margin-right:1rem!important}.mb-lg-3,.my-lg-3{margin-bottom:1rem!important}.ml-lg-3,.mx-lg-3{margin-left:1rem!important}.m-lg-4{margin:1.5rem!important}.mt-lg-4,.my-lg-4{margin-top:1.5rem!important}.mr-lg-4,.mx-lg-4{margin-right:1.5rem!important}.mb-lg-4,.my-lg-4{margin-bottom:1.5rem!important}.ml-lg-4,.mx-lg-4{margin-left:1.5rem!important}.m-lg-5{margin:3rem!important}.mt-lg-5,.my-lg-5{margin-top:3rem!important}.mr-lg-5,.mx-lg-5{margin-right:3rem!important}.mb-lg-5,.my-lg-5{margin-bottom:3rem!important}.ml-lg-5,.mx-lg-5{margin-left:3rem!important}.p-lg-0{padding:0!important}.pt-lg-0,.py-lg-0{padding-top:0!important}.pr-lg-0,.px-lg-0{padding-right:0!important}.pb-lg-0,.py-lg-0{padding-bottom:0!important}.pl-lg-0,.px-lg-0{padding-left:0!important}.p-lg-1{padding:.25rem!important}.pt-lg-1,.py-lg-1{padding-top:.25rem!important}.pr-lg-1,.px-lg-1{padding-right:.25rem!important}.pb-lg-1,.py-lg-1{padding-bottom:.25rem!important}.pl-lg-1,.px-lg-1{padding-left:.25rem!important}.p-lg-2{padding:.5rem!important}.pt-lg-2,.py-lg-2{padding-top:.5rem!important}.pr-lg-2,.px-lg-2{padding-right:.5rem!important}.pb-lg-2,.py-lg-2{padding-bottom:.5rem!important}.pl-lg-2,.px-lg-2{padding-left:.5rem!important}.p-lg-3{padding:1rem!important}.pt-lg-3,.py-lg-3{padding-top:1rem!important}.pr-lg-3,.px-lg-3{padding-right:1rem!important}.pb-lg-3,.py-lg-3{padding-bottom:1rem!important}.pl-lg-3,.px-lg-3{padding-left:1rem!important}.p-lg-4{padding:1.5rem!important}.pt-lg-4,.py-lg-4{padding-top:1.5rem!important}.pr-lg-4,.px-lg-4{padding-right:1.5rem!important}.pb-lg-4,.py-lg-4{padding-bottom:1.5rem!important}.pl-lg-4,.px-lg-4{padding-left:1.5rem!important}.p-lg-5{padding:3rem!important}.pt-lg-5,.py-lg-5{padding-top:3rem!important}.pr-lg-5,.px-lg-5{padding-right:3rem!important}.pb-lg-5,.py-lg-5{padding-bottom:3rem!important}.pl-lg-5,.px-lg-5{padding-left:3rem!important}.m-lg-n1{margin:-.25rem!important}.mt-lg-n1,.my-lg-n1{margin-top:-.25rem!important}.mr-lg-n1,.mx-lg-n1{margin-right:-.25rem!important}.mb-lg-n1,.my-lg-n1{margin-bottom:-.25rem!important}.ml-lg-n1,.mx-lg-n1{margin-left:-.25rem!important}.m-lg-n2{margin:-.5rem!important}.mt-lg-n2,.my-lg-n2{margin-top:-.5rem!important}.mr-lg-n2,.mx-lg-n2{margin-right:-.5rem!important}.mb-lg-n2,.my-lg-n2{margin-bottom:-.5rem!important}.ml-lg-n2,.mx-lg-n2{margin-left:-.5rem!important}.m-lg-n3{margin:-1rem!important}.mt-lg-n3,.my-lg-n3{margin-top:-1rem!important}.mr-lg-n3,.mx-lg-n3{margin-right:-1rem!important}.mb-lg-n3,.my-lg-n3{margin-bottom:-1rem!important}.ml-lg-n3,.mx-lg-n3{margin-left:-1rem!important}.m-lg-n4{margin:-1.5rem!important}.mt-lg-n4,.my-lg-n4{margin-top:-1.5rem!important}.mr-lg-n4,.mx-lg-n4{margin-right:-1.5rem!important}.mb-lg-n4,.my-lg-n4{margin-bottom:-1.5rem!important}.ml-lg-n4,.mx-lg-n4{margin-left:-1.5rem!important}.m-lg-n5{margin:-3rem!important}.mt-lg-n5,.my-lg-n5{margin-top:-3rem!important}.mr-lg-n5,.mx-lg-n5{margin-right:-3rem!important}.mb-lg-n5,.my-lg-n5{margin-bottom:-3rem!important}.ml-lg-n5,.mx-lg-n5{margin-left:-3rem!important}.m-lg-auto{margin:auto!important}.mt-lg-auto,.my-lg-auto{margin-top:auto!important}.mr-lg-auto,.mx-lg-auto{margin-right:auto!important}.mb-lg-auto,.my-lg-auto{margin-bottom:auto!important}.ml-lg-auto,.mx-lg-auto{margin-left:auto!important}}@media (min-width:1200px){.m-xl-0{margin:0!important}.mt-xl-0,.my-xl-0{margin-top:0!important}.mr-xl-0,.mx-xl-0{margin-right:0!important}.mb-xl-0,.my-xl-0{margin-bottom:0!important}.ml-xl-0,.mx-xl-0{margin-left:0!important}.m-xl-1{margin:.25rem!important}.mt-xl-1,.my-xl-1{margin-top:.25rem!important}.mr-xl-1,.mx-xl-1{margin-right:.25rem!important}.mb-xl-1,.my-xl-1{margin-bottom:.25rem!important}.ml-xl-1,.mx-xl-1{margin-left:.25rem!important}.m-xl-2{margin:.5rem!important}.mt-xl-2,.my-xl-2{margin-top:.5rem!important}.mr-xl-2,.mx-xl-2{margin-right:.5rem!important}.mb-xl-2,.my-xl-2{margin-bottom:.5rem!important}.ml-xl-2,.mx-xl-2{margin-left:.5rem!important}.m-xl-3{margin:1rem!important}.mt-xl-3,.my-xl-3{margin-top:1rem!important}.mr-xl-3,.mx-xl-3{margin-right:1rem!important}.mb-xl-3,.my-xl-3{margin-bottom:1rem!important}.ml-xl-3,.mx-xl-3{margin-left:1rem!important}.m-xl-4{margin:1.5rem!important}.mt-xl-4,.my-xl-4{margin-top:1.5rem!important}.mr-xl-4,.mx-xl-4{margin-right:1.5rem!important}.mb-xl-4,.my-xl-4{margin-bottom:1.5rem!important}.ml-xl-4,.mx-xl-4{margin-left:1.5rem!important}.m-xl-5{margin:3rem!important}.mt-xl-5,.my-xl-5{margin-top:3rem!important}.mr-xl-5,.mx-xl-5{margin-right:3rem!important}.mb-xl-5,.my-xl-5{margin-bottom:3rem!important}.ml-xl-5,.mx-xl-5{margin-left:3rem!important}.p-xl-0{padding:0!important}.pt-xl-0,.py-xl-0{padding-top:0!important}.pr-xl-0,.px-xl-0{padding-right:0!important}.pb-xl-0,.py-xl-0{padding-bottom:0!important}.pl-xl-0,.px-xl-0{padding-left:0!important}.p-xl-1{padding:.25rem!important}.pt-xl-1,.py-xl-1{padding-top:.25rem!important}.pr-xl-1,.px-xl-1{padding-right:.25rem!important}.pb-xl-1,.py-xl-1{padding-bottom:.25rem!important}.pl-xl-1,.px-xl-1{padding-left:.25rem!important}.p-xl-2{padding:.5rem!important}.pt-xl-2,.py-xl-2{padding-top:.5rem!important}.pr-xl-2,.px-xl-2{padding-right:.5rem!important}.pb-xl-2,.py-xl-2{padding-bottom:.5rem!important}.pl-xl-2,.px-xl-2{padding-left:.5rem!important}.p-xl-3{padding:1rem!important}.pt-xl-3,.py-xl-3{padding-top:1rem!important}.pr-xl-3,.px-xl-3{padding-right:1rem!important}.pb-xl-3,.py-xl-3{padding-bottom:1rem!important}.pl-xl-3,.px-xl-3{padding-left:1rem!important}.p-xl-4{padding:1.5rem!important}.pt-xl-4,.py-xl-4{padding-top:1.5rem!important}.pr-xl-4,.px-xl-4{padding-right:1.5rem!important}.pb-xl-4,.py-xl-4{padding-bottom:1.5rem!important}.pl-xl-4,.px-xl-4{padding-left:1.5rem!important}.p-xl-5{padding:3rem!important}.pt-xl-5,.py-xl-5{padding-top:3rem!important}.pr-xl-5,.px-xl-5{padding-right:3rem!important}.pb-xl-5,.py-xl-5{padding-bottom:3rem!important}.pl-xl-5,.px-xl-5{padding-left:3rem!important}.m-xl-n1{margin:-.25rem!important}.mt-xl-n1,.my-xl-n1{margin-top:-.25rem!important}.mr-xl-n1,.mx-xl-n1{margin-right:-.25rem!important}.mb-xl-n1,.my-xl-n1{margin-bottom:-.25rem!important}.ml-xl-n1,.mx-xl-n1{margin-left:-.25rem!important}.m-xl-n2{margin:-.5rem!important}.mt-xl-n2,.my-xl-n2{margin-top:-.5rem!important}.mr-xl-n2,.mx-xl-n2{margin-right:-.5rem!important}.mb-xl-n2,.my-xl-n2{margin-bottom:-.5rem!important}.ml-xl-n2,.mx-xl-n2{margin-left:-.5rem!important}.m-xl-n3{margin:-1rem!important}.mt-xl-n3,.my-xl-n3{margin-top:-1rem!important}.mr-xl-n3,.mx-xl-n3{margin-right:-1rem!important}.mb-xl-n3,.my-xl-n3{margin-bottom:-1rem!important}.ml-xl-n3,.mx-xl-n3{margin-left:-1rem!important}.m-xl-n4{margin:-1.5rem!important}.mt-xl-n4,.my-xl-n4{margin-top:-1.5rem!important}.mr-xl-n4,.mx-xl-n4{margin-right:-1.5rem!important}.mb-xl-n4,.my-xl-n4{margin-bottom:-1.5rem!important}.ml-xl-n4,.mx-xl-n4{margin-left:-1.5rem!important}.m-xl-n5{margin:-3rem!important}.mt-xl-n5,.my-xl-n5{margin-top:-3rem!important}.mr-xl-n5,.mx-xl-n5{margin-right:-3rem!important}.mb-xl-n5,.my-xl-n5{margin-bottom:-3rem!important}.ml-xl-n5,.mx-xl-n5{margin-left:-3rem!important}.m-xl-auto{margin:auto!important}.mt-xl-auto,.my-xl-auto{margin-top:auto!important}.mr-xl-auto,.mx-xl-auto{margin-right:auto!important}.mb-xl-auto,.my-xl-auto{margin-bottom:auto!important}.ml-xl-auto,.mx-xl-auto{margin-left:auto!important}}.text-monospace{font-family:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace!important}.text-justify{text-align:justify!important}.text-wrap{white-space:normal!important}.text-nowrap{white-space:nowrap!important}.text-truncate{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.text-left{text-align:left!important}.text-right{text-align:right!important}.text-center{text-align:center!important}@media (min-width:576px){.text-sm-left{text-align:left!important}.text-sm-right{text-align:right!important}.text-sm-center{text-align:center!important}}@media (min-width:768px){.text-md-left{text-align:left!important}.text-md-right{text-align:right!important}.text-md-center{text-align:center!important}}@media (min-width:992px){.text-lg-left{text-align:left!important}.text-lg-right{text-align:right!important}.text-lg-center{text-align:center!important}}@media (min-width:1200px){.text-xl-left{text-align:left!important}.text-xl-right{text-align:right!important}.text-xl-center{text-align:center!important}}.text-lowercase{text-transform:lowercase!important}.text-uppercase{text-transform:uppercase!important}.text-capitalize{text-transform:capitalize!important}.font-weight-light{font-weight:300!important}.font-weight-lighter{font-weight:lighter!important}.font-weight-normal{font-weight:400!important}.font-weight-bold{font-weight:600!important}.font-weight-bolder{font-weight:bolder!important}.font-italic{font-style:italic!important}.text-white{color:#fff!important}.text-primary{color:#3d8ef8!important}a.text-primary:focus,a.text-primary:hover{color:#0866e0!important}.text-secondary{color:#7c8a96!important}a.text-secondary:focus,a.text-secondary:hover{color:#58646e!important}.text-success{color:#11c46e!important}a.text-success:focus,a.text-success:hover{color:#0b7e46!important}.text-info{color:#0db4d6!important}a.text-info:focus,a.text-info:hover{color:#09778e!important}.text-warning{color:#f1b44c!important}a.text-warning:focus,a.text-warning:hover{color:#df9311!important}.text-danger{color:#fb4d53!important}a.text-danger:focus,a.text-danger:hover{color:#f6060e!important}.text-light{color:#eff2f7!important}a.text-light:focus,a.text-light:hover{color:#bcc9de!important}.text-dark{color:#343a40!important}a.text-dark:focus,a.text-dark:hover{color:#121416!important}.text-body{color:#505d69!important}.text-muted{color:#7c8a96!important}.text-black-50{color:rgba(0,0,0,.5)!important}.text-white-50{color:rgba(255,255,255,.5)!important}.text-hide{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0}.text-decoration-none{text-decoration:none!important}.text-break{word-break:break-word!important;overflow-wrap:break-word!important}.text-reset{color:inherit!important}.visible{visibility:visible!important}.invisible{visibility:hidden!important}@media print{*,::after,::before{text-shadow:none!important;-webkit-box-shadow:none!important;box-shadow:none!important}a:not(.btn){text-decoration:underline}abbr[title]::after{content:" (" attr(title) ")"}pre{white-space:pre-wrap!important}blockquote,pre{border:1px solid #adb5bd;page-break-inside:avoid}thead{display:table-header-group}img,tr{page-break-inside:avoid}h2,h3,p{orphans:3;widows:3}h2,h3{page-break-after:avoid}@page{size:a3}body{min-width:992px!important}.container{min-width:992px!important}.navbar{display:none}.badge{border:1px solid #000}.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}.table-bordered td,.table-bordered th{border:1px solid #f4f8f9!important}.table-dark{color:inherit}.table-dark tbody+tbody,.table-dark td,.table-dark th,.table-dark thead th{border-color:#eff2f7}.table .thead-dark th{color:inherit;border-color:#eff2f7}}html{position:relative;min-height:100%}.h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{color:#495057;font-weight:500}a{text-decoration:none!important}label{font-weight:500}.blockquote{padding:10px 20px;border-left:4px solid #f4f8f9;font-size:16px}.blockquote-reverse{border-left:0;border-right:4px solid #f4f8f9;text-align:right}.bg-soft-primary{background-color:rgba(61,142,248,.25)!important}.bg-soft-secondary{background-color:rgba(124,138,150,.25)!important}.bg-soft-success{background-color:rgba(17,196,110,.25)!important}.bg-soft-info{background-color:rgba(13,180,214,.25)!important}.bg-soft-warning{background-color:rgba(241,180,76,.25)!important}.bg-soft-danger{background-color:rgba(251,77,83,.25)!important}.bg-soft-light{background-color:rgba(239,242,247,.25)!important}.bg-soft-dark{background-color:rgba(52,58,64,.25)!important}.badge-soft-primary{color:#3d8ef8;background-color:rgba(61,142,248,.18)}.badge-soft-primary[href]:focus,.badge-soft-primary[href]:hover{color:#3d8ef8;text-decoration:none;background-color:rgba(61,142,248,.4)}.badge-soft-secondary{color:#7c8a96;background-color:rgba(124,138,150,.18)}.badge-soft-secondary[href]:focus,.badge-soft-secondary[href]:hover{color:#7c8a96;text-decoration:none;background-color:rgba(124,138,150,.4)}.badge-soft-success{color:#11c46e;background-color:rgba(17,196,110,.18)}.badge-soft-success[href]:focus,.badge-soft-success[href]:hover{color:#11c46e;text-decoration:none;background-color:rgba(17,196,110,.4)}.badge-soft-info{color:#0db4d6;background-color:rgba(13,180,214,.18)}.badge-soft-info[href]:focus,.badge-soft-info[href]:hover{color:#0db4d6;text-decoration:none;background-color:rgba(13,180,214,.4)}.badge-soft-warning{color:#f1b44c;background-color:rgba(241,180,76,.18)}.badge-soft-warning[href]:focus,.badge-soft-warning[href]:hover{color:#f1b44c;text-decoration:none;background-color:rgba(241,180,76,.4)}.badge-soft-danger{color:#fb4d53;background-color:rgba(251,77,83,.18)}.badge-soft-danger[href]:focus,.badge-soft-danger[href]:hover{color:#fb4d53;text-decoration:none;background-color:rgba(251,77,83,.4)}.badge-soft-light{color:#eff2f7;background-color:rgba(239,242,247,.18)}.badge-soft-light[href]:focus,.badge-soft-light[href]:hover{color:#eff2f7;text-decoration:none;background-color:rgba(239,242,247,.4)}.badge-soft-dark{color:#343a40;background-color:rgba(52,58,64,.18)}.badge-soft-dark[href]:focus,.badge-soft-dark[href]:hover{color:#343a40;text-decoration:none;background-color:rgba(52,58,64,.4)}.badge-dark{color:#eff2f7}a,button{outline:0!important}.btn-rounded{border-radius:30px}.btn-dark{color:#eff2f7!important}.breadcrumb-item>a{color:#495057}.breadcrumb-item+.breadcrumb-item::before{font-family:"Material Design Icons"}.card{margin-bottom:24px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}.card-drop{color:#505d69}.header-title{font-size:.975rem;margin:0 0 7px 0}.card-title-desc{color:#7c8a96;margin-bottom:24px;font-size:13px}.dropdown-menu{-webkit-box-shadow:0 2px 4px rgba(0,0,0,.08);box-shadow:0 2px 4px rgba(0,0,0,.08);-webkit-animation-name:DropDownSlide;animation-name:DropDownSlide;-webkit-animation-duration:.3s;animation-duration:.3s;-webkit-animation-fill-mode:both;animation-fill-mode:both;margin:0;position:absolute;z-index:1000;padding:.5rem .5rem}.dropdown-menu.show{top:100%!important}.dropdown-divider{border-top-color:#eff2f7}.dropdown-menu-right{right:0!important;left:auto!important}.dropdown-menu[x-placement^=left],.dropdown-menu[x-placement^=right],.dropdown-menu[x-placement^=top]{top:auto!important;-webkit-animation:none!important;animation:none!important}@-webkit-keyframes DropDownSlide{100%{-webkit-transform:translateY(0);transform:translateY(0)}0%{-webkit-transform:translateY(10px);transform:translateY(10px)}}@keyframes DropDownSlide{100%{-webkit-transform:translateY(0);transform:translateY(0)}0%{-webkit-transform:translateY(10px);transform:translateY(10px)}}@media (min-width:600px){.dropdown-menu-lg{width:320px}}.dropdown-mega{position:static!important}.dropdown-megamenu{padding:20px;left:20px!important;right:20px!important}.custom-control-label,.form-check-label{font-weight:400}.nav-pills>li>a,.nav-tabs>li>a{color:#7c8a96}.nav-pills>a{color:#7c8a96}.nav-tabs-custom .nav-item{margin:0 7px;position:relative}.nav-tabs-custom .nav-item:first-child{margin-left:0}.nav-tabs-custom .nav-item:last-child{margin-right:0}.nav-tabs-custom .nav-item .nav-link{background-color:#f4f8f9}.nav-tabs-custom .nav-item .nav-link.active{color:#3d8ef8}.nav-tabs-custom .nav-item .nav-link.active:after{-webkit-transform:scale(1);transform:scale(1)}.nav-tabs-custom .nav-item .nav-link:after{content:"";background:#3d8ef8;height:2px;position:absolute;width:100%;left:0;bottom:-1px;-webkit-transition:all 250ms ease 0s;transition:all 250ms ease 0s;-webkit-transform:scale(0);transform:scale(0)}.table th{font-weight:500}.table-centered td,.table-centered th{vertical-align:middle!important}.table-nowrap td,.table-nowrap th{white-space:nowrap}.pagination-rounded .page-link{border-radius:30px!important;margin:0 3px;border:none}.progress-sm{height:5px}.progress-md{height:8px}.progress-lg{height:12px}.progress-animate{position:relative;overflow:visible}.progress-animate .progress-bar{position:relative;border-radius:6px;-webkit-animation:animate-positive 2s;animation:animate-positive 2s}.progress-animate .progress-value{display:block;position:absolute;top:-26px;right:-14px}.progress-label{display:inline-block;position:relative;padding:1px 8px;background-color:#fff;border:1px solid;border-radius:.25rem}.progress-label::after{content:"";position:absolute;height:10px;width:3px;background:#adb5bd;left:0;right:0;margin:0 auto;bottom:-11px}@-webkit-keyframes animate-positive{0%{width:0}}@keyframes animate-positive{0%{width:0}}
/*# sourceMappingURL=bootstrap.min.css.map */

            </style>

        <!-- Bootstrap Css Ends -->

        <!-- Icons Css Starts -->

            <!-- File Name icon.min.css -->
            <style type="text/css">
                
                    @font-face{font-family:"Material Design Icons";src:url(../fonts/materialdesignicons-webfont.eot?v=4.4.95);src:url(../fonts/materialdesignicons-webfont.eot?#iefix&v=4.4.95) format("embedded-opentype"),url(../fonts/materialdesignicons-webfont.woff2?v=4.4.95) format("woff2"),url(../fonts/materialdesignicons-webfont.woff?v=4.4.95) format("woff"),url(../fonts/materialdesignicons-webfont.ttf?v=4.4.95) format("truetype");font-weight:400;font-style:normal}.mdi-set,.mdi:before{display:inline-block;font:normal normal normal 24px/1 "Material Design Icons";font-size:inherit;text-rendering:auto;line-height:inherit;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.mdi-ab-testing::before{content:"\F001C"}.mdi-access-point::before{content:"\F002"}.mdi-access-point-network::before{content:"\F003"}.mdi-access-point-network-off::before{content:"\FBBD"}.mdi-account::before{content:"\F004"}.mdi-account-alert::before{content:"\F005"}.mdi-account-alert-outline::before{content:"\FB2C"}.mdi-account-arrow-left::before{content:"\FB2D"}.mdi-account-arrow-left-outline::before{content:"\FB2E"}.mdi-account-arrow-right::before{content:"\FB2F"}.mdi-account-arrow-right-outline::before{content:"\FB30"}.mdi-account-badge::before{content:"\FD83"}.mdi-account-badge-alert::before{content:"\FD84"}.mdi-account-badge-alert-outline::before{content:"\FD85"}.mdi-account-badge-horizontal::before{content:"\FDF0"}.mdi-account-badge-horizontal-outline::before{content:"\FDF1"}.mdi-account-badge-outline::before{content:"\FD86"}.mdi-account-box::before{content:"\F006"}.mdi-account-box-multiple::before{content:"\F933"}.mdi-account-box-multiple-outline::before{content:"\F002C"}.mdi-account-box-outline::before{content:"\F007"}.mdi-account-card-details::before{content:"\F5D2"}.mdi-account-card-details-outline::before{content:"\FD87"}.mdi-account-cash::before{content:"\F00C2"}.mdi-account-cash-outline::before{content:"\F00C3"}.mdi-account-check::before{content:"\F008"}.mdi-account-check-outline::before{content:"\FBBE"}.mdi-account-child::before{content:"\FA88"}.mdi-account-child-circle::before{content:"\FA89"}.mdi-account-child-outline::before{content:"\F00F3"}.mdi-account-circle::before{content:"\F009"}.mdi-account-circle-outline::before{content:"\FB31"}.mdi-account-clock::before{content:"\FB32"}.mdi-account-clock-outline::before{content:"\FB33"}.mdi-account-convert::before{content:"\F00A"}.mdi-account-details::before{content:"\F631"}.mdi-account-edit::before{content:"\F6BB"}.mdi-account-edit-outline::before{content:"\F001D"}.mdi-account-group::before{content:"\F848"}.mdi-account-group-outline::before{content:"\FB34"}.mdi-account-heart::before{content:"\F898"}.mdi-account-heart-outline::before{content:"\FBBF"}.mdi-account-key::before{content:"\F00B"}.mdi-account-key-outline::before{content:"\FBC0"}.mdi-account-lock::before{content:"\F0189"}.mdi-account-lock-outline::before{content:"\F018A"}.mdi-account-minus::before{content:"\F00D"}.mdi-account-minus-outline::before{content:"\FAEB"}.mdi-account-multiple::before{content:"\F00E"}.mdi-account-multiple-check::before{content:"\F8C4"}.mdi-account-multiple-minus::before{content:"\F5D3"}.mdi-account-multiple-minus-outline::before{content:"\FBC1"}.mdi-account-multiple-outline::before{content:"\F00F"}.mdi-account-multiple-plus::before{content:"\F010"}.mdi-account-multiple-plus-outline::before{content:"\F7FF"}.mdi-account-network::before{content:"\F011"}.mdi-account-network-outline::before{content:"\FBC2"}.mdi-account-off::before{content:"\F012"}.mdi-account-off-outline::before{content:"\FBC3"}.mdi-account-outline::before{content:"\F013"}.mdi-account-plus::before{content:"\F014"}.mdi-account-plus-outline::before{content:"\F800"}.mdi-account-question::before{content:"\FB35"}.mdi-account-question-outline::before{content:"\FB36"}.mdi-account-remove::before{content:"\F015"}.mdi-account-remove-outline::before{content:"\FAEC"}.mdi-account-search::before{content:"\F016"}.mdi-account-search-outline::before{content:"\F934"}.mdi-account-settings::before{content:"\F630"}.mdi-account-settings-outline::before{content:"\F00F4"}.mdi-account-star::before{content:"\F017"}.mdi-account-star-outline::before{content:"\FBC4"}.mdi-account-supervisor::before{content:"\FA8A"}.mdi-account-supervisor-circle::before{content:"\FA8B"}.mdi-account-supervisor-outline::before{content:"\F0158"}.mdi-account-switch::before{content:"\F019"}.mdi-account-tie::before{content:"\FCBF"}.mdi-account-tie-outline::before{content:"\F00F5"}.mdi-accusoft::before{content:"\F849"}.mdi-adchoices::before{content:"\FD1E"}.mdi-adjust::before{content:"\F01A"}.mdi-adobe::before{content:"\F935"}.mdi-adobe-acrobat::before{content:"\FFBD"}.mdi-air-conditioner::before{content:"\F01B"}.mdi-air-filter::before{content:"\FD1F"}.mdi-air-horn::before{content:"\FD88"}.mdi-air-humidifier::before{content:"\F00C4"}.mdi-air-purifier::before{content:"\FD20"}.mdi-airbag::before{content:"\FBC5"}.mdi-airballoon::before{content:"\F01C"}.mdi-airballoon-outline::before{content:"\F002D"}.mdi-airplane::before{content:"\F01D"}.mdi-airplane-landing::before{content:"\F5D4"}.mdi-airplane-off::before{content:"\F01E"}.mdi-airplane-takeoff::before{content:"\F5D5"}.mdi-airplay::before{content:"\F01F"}.mdi-airport::before{content:"\F84A"}.mdi-alarm::before{content:"\F020"}.mdi-alarm-bell::before{content:"\F78D"}.mdi-alarm-check::before{content:"\F021"}.mdi-alarm-light::before{content:"\F78E"}.mdi-alarm-light-outline::before{content:"\FBC6"}.mdi-alarm-multiple::before{content:"\F022"}.mdi-alarm-note::before{content:"\FE8E"}.mdi-alarm-note-off::before{content:"\FE8F"}.mdi-alarm-off::before{content:"\F023"}.mdi-alarm-plus::before{content:"\F024"}.mdi-alarm-snooze::before{content:"\F68D"}.mdi-album::before{content:"\F025"}.mdi-alert::before{content:"\F026"}.mdi-alert-box::before{content:"\F027"}.mdi-alert-box-outline::before{content:"\FCC0"}.mdi-alert-circle::before{content:"\F028"}.mdi-alert-circle-outline::before{content:"\F5D6"}.mdi-alert-decagram::before{content:"\F6BC"}.mdi-alert-decagram-outline::before{content:"\FCC1"}.mdi-alert-octagon::before{content:"\F029"}.mdi-alert-octagon-outline::before{content:"\FCC2"}.mdi-alert-octagram::before{content:"\F766"}.mdi-alert-octagram-outline::before{content:"\FCC3"}.mdi-alert-outline::before{content:"\F02A"}.mdi-alien::before{content:"\F899"}.mdi-alien-outline::before{content:"\F00F6"}.mdi-all-inclusive::before{content:"\F6BD"}.mdi-alpha::before{content:"\F02B"}.mdi-alpha-a::before{content:"\41"}.mdi-alpha-a-box::before{content:"\FAED"}.mdi-alpha-a-box-outline::before{content:"\FBC7"}.mdi-alpha-a-circle::before{content:"\FBC8"}.mdi-alpha-a-circle-outline::before{content:"\FBC9"}.mdi-alpha-b::before{content:"\42"}.mdi-alpha-b-box::before{content:"\FAEE"}.mdi-alpha-b-box-outline::before{content:"\FBCA"}.mdi-alpha-b-circle::before{content:"\FBCB"}.mdi-alpha-b-circle-outline::before{content:"\FBCC"}.mdi-alpha-c::before{content:"\43"}.mdi-alpha-c-box::before{content:"\FAEF"}.mdi-alpha-c-box-outline::before{content:"\FBCD"}.mdi-alpha-c-circle::before{content:"\FBCE"}.mdi-alpha-c-circle-outline::before{content:"\FBCF"}.mdi-alpha-d::before{content:"\44"}.mdi-alpha-d-box::before{content:"\FAF0"}.mdi-alpha-d-box-outline::before{content:"\FBD0"}.mdi-alpha-d-circle::before{content:"\FBD1"}.mdi-alpha-d-circle-outline::before{content:"\FBD2"}.mdi-alpha-e::before{content:"\45"}.mdi-alpha-e-box::before{content:"\FAF1"}.mdi-alpha-e-box-outline::before{content:"\FBD3"}.mdi-alpha-e-circle::before{content:"\FBD4"}.mdi-alpha-e-circle-outline::before{content:"\FBD5"}.mdi-alpha-f::before{content:"\46"}.mdi-alpha-f-box::before{content:"\FAF2"}.mdi-alpha-f-box-outline::before{content:"\FBD6"}.mdi-alpha-f-circle::before{content:"\FBD7"}.mdi-alpha-f-circle-outline::before{content:"\FBD8"}.mdi-alpha-g::before{content:"\47"}.mdi-alpha-g-box::before{content:"\FAF3"}.mdi-alpha-g-box-outline::before{content:"\FBD9"}.mdi-alpha-g-circle::before{content:"\FBDA"}.mdi-alpha-g-circle-outline::before{content:"\FBDB"}.mdi-alpha-h::before{content:"\48"}.mdi-alpha-h-box::before{content:"\FAF4"}.mdi-alpha-h-box-outline::before{content:"\FBDC"}.mdi-alpha-h-circle::before{content:"\FBDD"}.mdi-alpha-h-circle-outline::before{content:"\FBDE"}.mdi-alpha-i::before{content:"\49"}.mdi-alpha-i-box::before{content:"\FAF5"}.mdi-alpha-i-box-outline::before{content:"\FBDF"}.mdi-alpha-i-circle::before{content:"\FBE0"}.mdi-alpha-i-circle-outline::before{content:"\FBE1"}.mdi-alpha-j::before{content:"\4A"}.mdi-alpha-j-box::before{content:"\FAF6"}.mdi-alpha-j-box-outline::before{content:"\FBE2"}.mdi-alpha-j-circle::before{content:"\FBE3"}.mdi-alpha-j-circle-outline::before{content:"\FBE4"}.mdi-alpha-k::before{content:"\4B"}.mdi-alpha-k-box::before{content:"\FAF7"}.mdi-alpha-k-box-outline::before{content:"\FBE5"}.mdi-alpha-k-circle::before{content:"\FBE6"}.mdi-alpha-k-circle-outline::before{content:"\FBE7"}.mdi-alpha-l::before{content:"\4C"}.mdi-alpha-l-box::before{content:"\FAF8"}.mdi-alpha-l-box-outline::before{content:"\FBE8"}.mdi-alpha-l-circle::before{content:"\FBE9"}.mdi-alpha-l-circle-outline::before{content:"\FBEA"}.mdi-alpha-m::before{content:"\4D"}.mdi-alpha-m-box::before{content:"\FAF9"}.mdi-alpha-m-box-outline::before{content:"\FBEB"}.mdi-alpha-m-circle::before{content:"\FBEC"}.mdi-alpha-m-circle-outline::before{content:"\FBED"}.mdi-alpha-n::before{content:"\4E"}.mdi-alpha-n-box::before{content:"\FAFA"}.mdi-alpha-n-box-outline::before{content:"\FBEE"}.mdi-alpha-n-circle::before{content:"\FBEF"}.mdi-alpha-n-circle-outline::before{content:"\FBF0"}.mdi-alpha-o::before{content:"\4F"}.mdi-alpha-o-box::before{content:"\FAFB"}.mdi-alpha-o-box-outline::before{content:"\FBF1"}.mdi-alpha-o-circle::before{content:"\FBF2"}.mdi-alpha-o-circle-outline::before{content:"\FBF3"}.mdi-alpha-p::before{content:"\50"}.mdi-alpha-p-box::before{content:"\FAFC"}.mdi-alpha-p-box-outline::before{content:"\FBF4"}.mdi-alpha-p-circle::before{content:"\FBF5"}.mdi-alpha-p-circle-outline::before{content:"\FBF6"}.mdi-alpha-q::before{content:"\51"}.mdi-alpha-q-box::before{content:"\FAFD"}.mdi-alpha-q-box-outline::before{content:"\FBF7"}.mdi-alpha-q-circle::before{content:"\FBF8"}.mdi-alpha-q-circle-outline::before{content:"\FBF9"}.mdi-alpha-r::before{content:"\52"}.mdi-alpha-r-box::before{content:"\FAFE"}.mdi-alpha-r-box-outline::before{content:"\FBFA"}.mdi-alpha-r-circle::before{content:"\FBFB"}.mdi-alpha-r-circle-outline::before{content:"\FBFC"}.mdi-alpha-s::before{content:"\53"}.mdi-alpha-s-box::before{content:"\FAFF"}.mdi-alpha-s-box-outline::before{content:"\FBFD"}.mdi-alpha-s-circle::before{content:"\FBFE"}.mdi-alpha-s-circle-outline::before{content:"\FBFF"}.mdi-alpha-t::before{content:"\54"}.mdi-alpha-t-box::before{content:"\FB00"}.mdi-alpha-t-box-outline::before{content:"\FC00"}.mdi-alpha-t-circle::before{content:"\FC01"}.mdi-alpha-t-circle-outline::before{content:"\FC02"}.mdi-alpha-u::before{content:"\55"}.mdi-alpha-u-box::before{content:"\FB01"}.mdi-alpha-u-box-outline::before{content:"\FC03"}.mdi-alpha-u-circle::before{content:"\FC04"}.mdi-alpha-u-circle-outline::before{content:"\FC05"}.mdi-alpha-v::before{content:"\56"}.mdi-alpha-v-box::before{content:"\FB02"}.mdi-alpha-v-box-outline::before{content:"\FC06"}.mdi-alpha-v-circle::before{content:"\FC07"}.mdi-alpha-v-circle-outline::before{content:"\FC08"}.mdi-alpha-w::before{content:"\57"}.mdi-alpha-w-box::before{content:"\FB03"}.mdi-alpha-w-box-outline::before{content:"\FC09"}.mdi-alpha-w-circle::before{content:"\FC0A"}.mdi-alpha-w-circle-outline::before{content:"\FC0B"}.mdi-alpha-x::before{content:"\58"}.mdi-alpha-x-box::before{content:"\FB04"}.mdi-alpha-x-box-outline::before{content:"\FC0C"}.mdi-alpha-x-circle::before{content:"\FC0D"}.mdi-alpha-x-circle-outline::before{content:"\FC0E"}.mdi-alpha-y::before{content:"\59"}.mdi-alpha-y-box::before{content:"\FB05"}.mdi-alpha-y-box-outline::before{content:"\FC0F"}.mdi-alpha-y-circle::before{content:"\FC10"}.mdi-alpha-y-circle-outline::before{content:"\FC11"}.mdi-alpha-z::before{content:"\5A"}.mdi-alpha-z-box::before{content:"\FB06"}.mdi-alpha-z-box-outline::before{content:"\FC12"}.mdi-alpha-z-circle::before{content:"\FC13"}.mdi-alpha-z-circle-outline::before{content:"\FC14"}.mdi-alphabetical::before{content:"\F02C"}.mdi-alphabetical-off::before{content:"\F002E"}.mdi-alphabetical-variant::before{content:"\F002F"}.mdi-alphabetical-variant-off::before{content:"\F0030"}.mdi-altimeter::before{content:"\F5D7"}.mdi-amazon::before{content:"\F02D"}.mdi-amazon-alexa::before{content:"\F8C5"}.mdi-amazon-drive::before{content:"\F02E"}.mdi-ambulance::before{content:"\F02F"}.mdi-ammunition::before{content:"\FCC4"}.mdi-ampersand::before{content:"\FA8C"}.mdi-amplifier::before{content:"\F030"}.mdi-anchor::before{content:"\F031"}.mdi-android::before{content:"\F032"}.mdi-android-auto::before{content:"\FA8D"}.mdi-android-debug-bridge::before{content:"\F033"}.mdi-android-head::before{content:"\F78F"}.mdi-android-messages::before{content:"\FD21"}.mdi-android-studio::before{content:"\F034"}.mdi-angle-acute::before{content:"\F936"}.mdi-angle-obtuse::before{content:"\F937"}.mdi-angle-right::before{content:"\F938"}.mdi-angular::before{content:"\F6B1"}.mdi-angularjs::before{content:"\F6BE"}.mdi-animation::before{content:"\F5D8"}.mdi-animation-outline::before{content:"\FA8E"}.mdi-animation-play::before{content:"\F939"}.mdi-animation-play-outline::before{content:"\FA8F"}.mdi-ansible::before{content:"\F00C5"}.mdi-antenna::before{content:"\F0144"}.mdi-anvil::before{content:"\F89A"}.mdi-apache-kafka::before{content:"\F0031"}.mdi-api::before{content:"\F00C6"}.mdi-apple::before{content:"\F035"}.mdi-apple-finder::before{content:"\F036"}.mdi-apple-icloud::before{content:"\F038"}.mdi-apple-ios::before{content:"\F037"}.mdi-apple-keyboard-caps::before{content:"\F632"}.mdi-apple-keyboard-command::before{content:"\F633"}.mdi-apple-keyboard-control::before{content:"\F634"}.mdi-apple-keyboard-option::before{content:"\F635"}.mdi-apple-keyboard-shift::before{content:"\F636"}.mdi-apple-safari::before{content:"\F039"}.mdi-application::before{content:"\F614"}.mdi-application-export::before{content:"\FD89"}.mdi-application-import::before{content:"\FD8A"}.mdi-approximately-equal::before{content:"\FFBE"}.mdi-approximately-equal-box::before{content:"\FFBF"}.mdi-apps::before{content:"\F03B"}.mdi-apps-box::before{content:"\FD22"}.mdi-arch::before{content:"\F8C6"}.mdi-archive::before{content:"\F03C"}.mdi-arm-flex::before{content:"\F008F"}.mdi-arm-flex-outline::before{content:"\F0090"}.mdi-arrange-bring-forward::before{content:"\F03D"}.mdi-arrange-bring-to-front::before{content:"\F03E"}.mdi-arrange-send-backward::before{content:"\F03F"}.mdi-arrange-send-to-back::before{content:"\F040"}.mdi-arrow-all::before{content:"\F041"}.mdi-arrow-bottom-left::before{content:"\F042"}.mdi-arrow-bottom-left-bold-outline::before{content:"\F9B6"}.mdi-arrow-bottom-left-thick::before{content:"\F9B7"}.mdi-arrow-bottom-right::before{content:"\F043"}.mdi-arrow-bottom-right-bold-outline::before{content:"\F9B8"}.mdi-arrow-bottom-right-thick::before{content:"\F9B9"}.mdi-arrow-collapse::before{content:"\F615"}.mdi-arrow-collapse-all::before{content:"\F044"}.mdi-arrow-collapse-down::before{content:"\F791"}.mdi-arrow-collapse-horizontal::before{content:"\F84B"}.mdi-arrow-collapse-left::before{content:"\F792"}.mdi-arrow-collapse-right::before{content:"\F793"}.mdi-arrow-collapse-up::before{content:"\F794"}.mdi-arrow-collapse-vertical::before{content:"\F84C"}.mdi-arrow-decision::before{content:"\F9BA"}.mdi-arrow-decision-auto::before{content:"\F9BB"}.mdi-arrow-decision-auto-outline::before{content:"\F9BC"}.mdi-arrow-decision-outline::before{content:"\F9BD"}.mdi-arrow-down::before{content:"\F045"}.mdi-arrow-down-bold::before{content:"\F72D"}.mdi-arrow-down-bold-box::before{content:"\F72E"}.mdi-arrow-down-bold-box-outline::before{content:"\F72F"}.mdi-arrow-down-bold-circle::before{content:"\F047"}.mdi-arrow-down-bold-circle-outline::before{content:"\F048"}.mdi-arrow-down-bold-hexagon-outline::before{content:"\F049"}.mdi-arrow-down-bold-outline::before{content:"\F9BE"}.mdi-arrow-down-box::before{content:"\F6BF"}.mdi-arrow-down-circle::before{content:"\FCB7"}.mdi-arrow-down-circle-outline::before{content:"\FCB8"}.mdi-arrow-down-drop-circle::before{content:"\F04A"}.mdi-arrow-down-drop-circle-outline::before{content:"\F04B"}.mdi-arrow-down-thick::before{content:"\F046"}.mdi-arrow-expand::before{content:"\F616"}.mdi-arrow-expand-all::before{content:"\F04C"}.mdi-arrow-expand-down::before{content:"\F795"}.mdi-arrow-expand-horizontal::before{content:"\F84D"}.mdi-arrow-expand-left::before{content:"\F796"}.mdi-arrow-expand-right::before{content:"\F797"}.mdi-arrow-expand-up::before{content:"\F798"}.mdi-arrow-expand-vertical::before{content:"\F84E"}.mdi-arrow-horizontal-lock::before{content:"\F0186"}.mdi-arrow-left::before{content:"\F04D"}.mdi-arrow-left-bold::before{content:"\F730"}.mdi-arrow-left-bold-box::before{content:"\F731"}.mdi-arrow-left-bold-box-outline::before{content:"\F732"}.mdi-arrow-left-bold-circle::before{content:"\F04F"}.mdi-arrow-left-bold-circle-outline::before{content:"\F050"}.mdi-arrow-left-bold-hexagon-outline::before{content:"\F051"}.mdi-arrow-left-bold-outline::before{content:"\F9BF"}.mdi-arrow-left-box::before{content:"\F6C0"}.mdi-arrow-left-circle::before{content:"\FCB9"}.mdi-arrow-left-circle-outline::before{content:"\FCBA"}.mdi-arrow-left-drop-circle::before{content:"\F052"}.mdi-arrow-left-drop-circle-outline::before{content:"\F053"}.mdi-arrow-left-right::before{content:"\FE90"}.mdi-arrow-left-right-bold::before{content:"\FE91"}.mdi-arrow-left-right-bold-outline::before{content:"\F9C0"}.mdi-arrow-left-thick::before{content:"\F04E"}.mdi-arrow-right::before{content:"\F054"}.mdi-arrow-right-bold::before{content:"\F733"}.mdi-arrow-right-bold-box::before{content:"\F734"}.mdi-arrow-right-bold-box-outline::before{content:"\F735"}.mdi-arrow-right-bold-circle::before{content:"\F056"}.mdi-arrow-right-bold-circle-outline::before{content:"\F057"}.mdi-arrow-right-bold-hexagon-outline::before{content:"\F058"}.mdi-arrow-right-bold-outline::before{content:"\F9C1"}.mdi-arrow-right-box::before{content:"\F6C1"}.mdi-arrow-right-circle::before{content:"\FCBB"}.mdi-arrow-right-circle-outline::before{content:"\FCBC"}.mdi-arrow-right-drop-circle::before{content:"\F059"}.mdi-arrow-right-drop-circle-outline::before{content:"\F05A"}.mdi-arrow-right-thick::before{content:"\F055"}.mdi-arrow-split-horizontal::before{content:"\F93A"}.mdi-arrow-split-vertical::before{content:"\F93B"}.mdi-arrow-top-left::before{content:"\F05B"}.mdi-arrow-top-left-bold-outline::before{content:"\F9C2"}.mdi-arrow-top-left-bottom-right::before{content:"\FE92"}.mdi-arrow-top-left-bottom-right-bold::before{content:"\FE93"}.mdi-arrow-top-left-thick::before{content:"\F9C3"}.mdi-arrow-top-right::before{content:"\F05C"}.mdi-arrow-top-right-bold-outline::before{content:"\F9C4"}.mdi-arrow-top-right-bottom-left::before{content:"\FE94"}.mdi-arrow-top-right-bottom-left-bold::before{content:"\FE95"}.mdi-arrow-top-right-thick::before{content:"\F9C5"}.mdi-arrow-up::before{content:"\F05D"}.mdi-arrow-up-bold::before{content:"\F736"}.mdi-arrow-up-bold-box::before{content:"\F737"}.mdi-arrow-up-bold-box-outline::before{content:"\F738"}.mdi-arrow-up-bold-circle::before{content:"\F05F"}.mdi-arrow-up-bold-circle-outline::before{content:"\F060"}.mdi-arrow-up-bold-hexagon-outline::before{content:"\F061"}.mdi-arrow-up-bold-outline::before{content:"\F9C6"}.mdi-arrow-up-box::before{content:"\F6C2"}.mdi-arrow-up-circle::before{content:"\FCBD"}.mdi-arrow-up-circle-outline::before{content:"\FCBE"}.mdi-arrow-up-down::before{content:"\FE96"}.mdi-arrow-up-down-bold::before{content:"\FE97"}.mdi-arrow-up-down-bold-outline::before{content:"\F9C7"}.mdi-arrow-up-drop-circle::before{content:"\F062"}.mdi-arrow-up-drop-circle-outline::before{content:"\F063"}.mdi-arrow-up-thick::before{content:"\F05E"}.mdi-arrow-vertical-lock::before{content:"\F0187"}.mdi-artist::before{content:"\F802"}.mdi-artist-outline::before{content:"\FCC5"}.mdi-artstation::before{content:"\FB37"}.mdi-aspect-ratio::before{content:"\FA23"}.mdi-assistant::before{content:"\F064"}.mdi-asterisk::before{content:"\F6C3"}.mdi-at::before{content:"\F065"}.mdi-atlassian::before{content:"\F803"}.mdi-atm::before{content:"\FD23"}.mdi-atom::before{content:"\F767"}.mdi-atom-variant::before{content:"\FE98"}.mdi-attachment::before{content:"\F066"}.mdi-audio-video::before{content:"\F93C"}.mdi-audiobook::before{content:"\F067"}.mdi-augmented-reality::before{content:"\F84F"}.mdi-auto-fix::before{content:"\F068"}.mdi-auto-upload::before{content:"\F069"}.mdi-autorenew::before{content:"\F06A"}.mdi-av-timer::before{content:"\F06B"}.mdi-aws::before{content:"\FDF2"}.mdi-axe::before{content:"\F8C7"}.mdi-axis::before{content:"\FD24"}.mdi-axis-arrow::before{content:"\FD25"}.mdi-axis-arrow-lock::before{content:"\FD26"}.mdi-axis-lock::before{content:"\FD27"}.mdi-axis-x-arrow::before{content:"\FD28"}.mdi-axis-x-arrow-lock::before{content:"\FD29"}.mdi-axis-x-rotate-clockwise::before{content:"\FD2A"}.mdi-axis-x-rotate-counterclockwise::before{content:"\FD2B"}.mdi-axis-x-y-arrow-lock::before{content:"\FD2C"}.mdi-axis-y-arrow::before{content:"\FD2D"}.mdi-axis-y-arrow-lock::before{content:"\FD2E"}.mdi-axis-y-rotate-clockwise::before{content:"\FD2F"}.mdi-axis-y-rotate-counterclockwise::before{content:"\FD30"}.mdi-axis-z-arrow::before{content:"\FD31"}.mdi-axis-z-arrow-lock::before{content:"\FD32"}.mdi-axis-z-rotate-clockwise::before{content:"\FD33"}.mdi-axis-z-rotate-counterclockwise::before{content:"\FD34"}.mdi-azure::before{content:"\F804"}.mdi-azure-devops::before{content:"\F0091"}.mdi-babel::before{content:"\FA24"}.mdi-baby::before{content:"\F06C"}.mdi-baby-bottle::before{content:"\FF56"}.mdi-baby-bottle-outline::before{content:"\FF57"}.mdi-baby-carriage::before{content:"\F68E"}.mdi-baby-carriage-off::before{content:"\FFC0"}.mdi-baby-face::before{content:"\FE99"}.mdi-baby-face-outline::before{content:"\FE9A"}.mdi-backburger::before{content:"\F06D"}.mdi-backspace::before{content:"\F06E"}.mdi-backspace-outline::before{content:"\FB38"}.mdi-backspace-reverse::before{content:"\FE9B"}.mdi-backspace-reverse-outline::before{content:"\FE9C"}.mdi-backup-restore::before{content:"\F06F"}.mdi-bacteria::before{content:"\FEF2"}.mdi-bacteria-outline::before{content:"\FEF3"}.mdi-badminton::before{content:"\F850"}.mdi-bag-carry-on::before{content:"\FF58"}.mdi-bag-carry-on-check::before{content:"\FD41"}.mdi-bag-carry-on-off::before{content:"\FF59"}.mdi-bag-checked::before{content:"\FF5A"}.mdi-bag-personal::before{content:"\FDF3"}.mdi-bag-personal-off::before{content:"\FDF4"}.mdi-bag-personal-off-outline::before{content:"\FDF5"}.mdi-bag-personal-outline::before{content:"\FDF6"}.mdi-baguette::before{content:"\FF5B"}.mdi-balloon::before{content:"\FA25"}.mdi-ballot::before{content:"\F9C8"}.mdi-ballot-outline::before{content:"\F9C9"}.mdi-ballot-recount::before{content:"\FC15"}.mdi-ballot-recount-outline::before{content:"\FC16"}.mdi-bandage::before{content:"\FD8B"}.mdi-bandcamp::before{content:"\F674"}.mdi-bank::before{content:"\F070"}.mdi-bank-minus::before{content:"\FD8C"}.mdi-bank-outline::before{content:"\FE9D"}.mdi-bank-plus::before{content:"\FD8D"}.mdi-bank-remove::before{content:"\FD8E"}.mdi-bank-transfer::before{content:"\FA26"}.mdi-bank-transfer-in::before{content:"\FA27"}.mdi-bank-transfer-out::before{content:"\FA28"}.mdi-barcode::before{content:"\F071"}.mdi-barcode-scan::before{content:"\F072"}.mdi-barley::before{content:"\F073"}.mdi-barley-off::before{content:"\FB39"}.mdi-barn::before{content:"\FB3A"}.mdi-barrel::before{content:"\F074"}.mdi-baseball::before{content:"\F851"}.mdi-baseball-bat::before{content:"\F852"}.mdi-basecamp::before{content:"\F075"}.mdi-bash::before{content:"\F01AE"}.mdi-basket::before{content:"\F076"}.mdi-basket-fill::before{content:"\F077"}.mdi-basket-outline::before{content:"\F01AC"}.mdi-basket-unfill::before{content:"\F078"}.mdi-basketball::before{content:"\F805"}.mdi-basketball-hoop::before{content:"\FC17"}.mdi-basketball-hoop-outline::before{content:"\FC18"}.mdi-bat::before{content:"\FB3B"}.mdi-battery::before{content:"\F079"}.mdi-battery-10::before{content:"\F07A"}.mdi-battery-10-bluetooth::before{content:"\F93D"}.mdi-battery-20::before{content:"\F07B"}.mdi-battery-20-bluetooth::before{content:"\F93E"}.mdi-battery-30::before{content:"\F07C"}.mdi-battery-30-bluetooth::before{content:"\F93F"}.mdi-battery-40::before{content:"\F07D"}.mdi-battery-40-bluetooth::before{content:"\F940"}.mdi-battery-50::before{content:"\F07E"}.mdi-battery-50-bluetooth::before{content:"\F941"}.mdi-battery-60::before{content:"\F07F"}.mdi-battery-60-bluetooth::before{content:"\F942"}.mdi-battery-70::before{content:"\F080"}.mdi-battery-70-bluetooth::before{content:"\F943"}.mdi-battery-80::before{content:"\F081"}.mdi-battery-80-bluetooth::before{content:"\F944"}.mdi-battery-90::before{content:"\F082"}.mdi-battery-90-bluetooth::before{content:"\F945"}.mdi-battery-alert::before{content:"\F083"}.mdi-battery-alert-bluetooth::before{content:"\F946"}.mdi-battery-alert-variant::before{content:"\F00F7"}.mdi-battery-alert-variant-outline::before{content:"\F00F8"}.mdi-battery-bluetooth::before{content:"\F947"}.mdi-battery-bluetooth-variant::before{content:"\F948"}.mdi-battery-charging::before{content:"\F084"}.mdi-battery-charging-10::before{content:"\F89B"}.mdi-battery-charging-100::before{content:"\F085"}.mdi-battery-charging-20::before{content:"\F086"}.mdi-battery-charging-30::before{content:"\F087"}.mdi-battery-charging-40::before{content:"\F088"}.mdi-battery-charging-50::before{content:"\F89C"}.mdi-battery-charging-60::before{content:"\F089"}.mdi-battery-charging-70::before{content:"\F89D"}.mdi-battery-charging-80::before{content:"\F08A"}.mdi-battery-charging-90::before{content:"\F08B"}.mdi-battery-charging-outline::before{content:"\F89E"}.mdi-battery-charging-wireless::before{content:"\F806"}.mdi-battery-charging-wireless-10::before{content:"\F807"}.mdi-battery-charging-wireless-20::before{content:"\F808"}.mdi-battery-charging-wireless-30::before{content:"\F809"}.mdi-battery-charging-wireless-40::before{content:"\F80A"}.mdi-battery-charging-wireless-50::before{content:"\F80B"}.mdi-battery-charging-wireless-60::before{content:"\F80C"}.mdi-battery-charging-wireless-70::before{content:"\F80D"}.mdi-battery-charging-wireless-80::before{content:"\F80E"}.mdi-battery-charging-wireless-90::before{content:"\F80F"}.mdi-battery-charging-wireless-alert::before{content:"\F810"}.mdi-battery-charging-wireless-outline::before{content:"\F811"}.mdi-battery-minus::before{content:"\F08C"}.mdi-battery-negative::before{content:"\F08D"}.mdi-battery-outline::before{content:"\F08E"}.mdi-battery-plus::before{content:"\F08F"}.mdi-battery-positive::before{content:"\F090"}.mdi-battery-unknown::before{content:"\F091"}.mdi-battery-unknown-bluetooth::before{content:"\F949"}.mdi-battlenet::before{content:"\FB3C"}.mdi-beach::before{content:"\F092"}.mdi-beaker::before{content:"\FCC6"}.mdi-beaker-outline::before{content:"\F68F"}.mdi-beats::before{content:"\F097"}.mdi-bed-double::before{content:"\F0092"}.mdi-bed-double-outline::before{content:"\F0093"}.mdi-bed-empty::before{content:"\F89F"}.mdi-bed-king::before{content:"\F0094"}.mdi-bed-king-outline::before{content:"\F0095"}.mdi-bed-queen::before{content:"\F0096"}.mdi-bed-queen-outline::before{content:"\F0097"}.mdi-bed-single::before{content:"\F0098"}.mdi-bed-single-outline::before{content:"\F0099"}.mdi-bee::before{content:"\FFC1"}.mdi-bee-flower::before{content:"\FFC2"}.mdi-beehive-outline::before{content:"\F00F9"}.mdi-beer::before{content:"\F098"}.mdi-behance::before{content:"\F099"}.mdi-bell::before{content:"\F09A"}.mdi-bell-alert::before{content:"\FD35"}.mdi-bell-alert-outline::before{content:"\FE9E"}.mdi-bell-circle::before{content:"\FD36"}.mdi-bell-circle-outline::before{content:"\FD37"}.mdi-bell-off::before{content:"\F09B"}.mdi-bell-off-outline::before{content:"\FA90"}.mdi-bell-outline::before{content:"\F09C"}.mdi-bell-plus::before{content:"\F09D"}.mdi-bell-plus-outline::before{content:"\FA91"}.mdi-bell-ring::before{content:"\F09E"}.mdi-bell-ring-outline::before{content:"\F09F"}.mdi-bell-sleep::before{content:"\F0A0"}.mdi-bell-sleep-outline::before{content:"\FA92"}.mdi-beta::before{content:"\F0A1"}.mdi-betamax::before{content:"\F9CA"}.mdi-biathlon::before{content:"\FDF7"}.mdi-bible::before{content:"\F0A2"}.mdi-bicycle::before{content:"\F00C7"}.mdi-bike::before{content:"\F0A3"}.mdi-bike-fast::before{content:"\F014A"}.mdi-billboard::before{content:"\F0032"}.mdi-billiards::before{content:"\FB3D"}.mdi-billiards-rack::before{content:"\FB3E"}.mdi-bing::before{content:"\F0A4"}.mdi-binoculars::before{content:"\F0A5"}.mdi-bio::before{content:"\F0A6"}.mdi-biohazard::before{content:"\F0A7"}.mdi-bitbucket::before{content:"\F0A8"}.mdi-bitcoin::before{content:"\F812"}.mdi-black-mesa::before{content:"\F0A9"}.mdi-blackberry::before{content:"\F0AA"}.mdi-blender::before{content:"\FCC7"}.mdi-blender-software::before{content:"\F0AB"}.mdi-blinds::before{content:"\F0AC"}.mdi-blinds-open::before{content:"\F0033"}.mdi-block-helper::before{content:"\F0AD"}.mdi-blogger::before{content:"\F0AE"}.mdi-blood-bag::before{content:"\FCC8"}.mdi-bluetooth::before{content:"\F0AF"}.mdi-bluetooth-audio::before{content:"\F0B0"}.mdi-bluetooth-connect::before{content:"\F0B1"}.mdi-bluetooth-off::before{content:"\F0B2"}.mdi-bluetooth-settings::before{content:"\F0B3"}.mdi-bluetooth-transfer::before{content:"\F0B4"}.mdi-blur::before{content:"\F0B5"}.mdi-blur-linear::before{content:"\F0B6"}.mdi-blur-off::before{content:"\F0B7"}.mdi-blur-radial::before{content:"\F0B8"}.mdi-bolnisi-cross::before{content:"\FCC9"}.mdi-bolt::before{content:"\FD8F"}.mdi-bomb::before{content:"\F690"}.mdi-bomb-off::before{content:"\F6C4"}.mdi-bone::before{content:"\F0B9"}.mdi-book::before{content:"\F0BA"}.mdi-book-information-variant::before{content:"\F009A"}.mdi-book-lock::before{content:"\F799"}.mdi-book-lock-open::before{content:"\F79A"}.mdi-book-minus::before{content:"\F5D9"}.mdi-book-minus-multiple::before{content:"\FA93"}.mdi-book-multiple::before{content:"\F0BB"}.mdi-book-open::before{content:"\F0BD"}.mdi-book-open-outline::before{content:"\FB3F"}.mdi-book-open-page-variant::before{content:"\F5DA"}.mdi-book-open-variant::before{content:"\F0BE"}.mdi-book-outline::before{content:"\FB40"}.mdi-book-play::before{content:"\FE9F"}.mdi-book-play-outline::before{content:"\FEA0"}.mdi-book-plus::before{content:"\F5DB"}.mdi-book-plus-multiple::before{content:"\FA94"}.mdi-book-remove::before{content:"\FA96"}.mdi-book-remove-multiple::before{content:"\FA95"}.mdi-book-search::before{content:"\FEA1"}.mdi-book-search-outline::before{content:"\FEA2"}.mdi-book-variant::before{content:"\F0BF"}.mdi-book-variant-multiple::before{content:"\F0BC"}.mdi-bookmark::before{content:"\F0C0"}.mdi-bookmark-check::before{content:"\F0C1"}.mdi-bookmark-minus::before{content:"\F9CB"}.mdi-bookmark-minus-outline::before{content:"\F9CC"}.mdi-bookmark-multiple::before{content:"\FDF8"}.mdi-bookmark-multiple-outline::before{content:"\FDF9"}.mdi-bookmark-music::before{content:"\F0C2"}.mdi-bookmark-off::before{content:"\F9CD"}.mdi-bookmark-off-outline::before{content:"\F9CE"}.mdi-bookmark-outline::before{content:"\F0C3"}.mdi-bookmark-plus::before{content:"\F0C5"}.mdi-bookmark-plus-outline::before{content:"\F0C4"}.mdi-bookmark-remove::before{content:"\F0C6"}.mdi-boom-gate::before{content:"\FEA3"}.mdi-boom-gate-alert::before{content:"\FEA4"}.mdi-boom-gate-alert-outline::before{content:"\FEA5"}.mdi-boom-gate-down::before{content:"\FEA6"}.mdi-boom-gate-down-outline::before{content:"\FEA7"}.mdi-boom-gate-outline::before{content:"\FEA8"}.mdi-boom-gate-up::before{content:"\FEA9"}.mdi-boom-gate-up-outline::before{content:"\FEAA"}.mdi-boombox::before{content:"\F5DC"}.mdi-boomerang::before{content:"\F00FA"}.mdi-bootstrap::before{content:"\F6C5"}.mdi-border-all::before{content:"\F0C7"}.mdi-border-all-variant::before{content:"\F8A0"}.mdi-border-bottom::before{content:"\F0C8"}.mdi-border-bottom-variant::before{content:"\F8A1"}.mdi-border-color::before{content:"\F0C9"}.mdi-border-horizontal::before{content:"\F0CA"}.mdi-border-inside::before{content:"\F0CB"}.mdi-border-left::before{content:"\F0CC"}.mdi-border-left-variant::before{content:"\F8A2"}.mdi-border-none::before{content:"\F0CD"}.mdi-border-none-variant::before{content:"\F8A3"}.mdi-border-outside::before{content:"\F0CE"}.mdi-border-right::before{content:"\F0CF"}.mdi-border-right-variant::before{content:"\F8A4"}.mdi-border-style::before{content:"\F0D0"}.mdi-border-top::before{content:"\F0D1"}.mdi-border-top-variant::before{content:"\F8A5"}.mdi-border-vertical::before{content:"\F0D2"}.mdi-bottle-soda::before{content:"\F009B"}.mdi-bottle-soda-classic::before{content:"\F009C"}.mdi-bottle-soda-outline::before{content:"\F009D"}.mdi-bottle-tonic::before{content:"\F0159"}.mdi-bottle-tonic-outline::before{content:"\F015A"}.mdi-bottle-tonic-plus::before{content:"\F015B"}.mdi-bottle-tonic-plus-outline::before{content:"\F015C"}.mdi-bottle-tonic-skull::before{content:"\F015D"}.mdi-bottle-tonic-skull-outline::before{content:"\F015E"}.mdi-bottle-wine::before{content:"\F853"}.mdi-bow-tie::before{content:"\F677"}.mdi-bowl::before{content:"\F617"}.mdi-bowling::before{content:"\F0D3"}.mdi-box::before{content:"\F0D4"}.mdi-box-cutter::before{content:"\F0D5"}.mdi-box-shadow::before{content:"\F637"}.mdi-boxing-glove::before{content:"\FB41"}.mdi-braille::before{content:"\F9CF"}.mdi-brain::before{content:"\F9D0"}.mdi-bread-slice::before{content:"\FCCA"}.mdi-bread-slice-outline::before{content:"\FCCB"}.mdi-bridge::before{content:"\F618"}.mdi-briefcase::before{content:"\F0D6"}.mdi-briefcase-account::before{content:"\FCCC"}.mdi-briefcase-account-outline::before{content:"\FCCD"}.mdi-briefcase-check::before{content:"\F0D7"}.mdi-briefcase-clock::before{content:"\F00FB"}.mdi-briefcase-clock-outline::before{content:"\F00FC"}.mdi-briefcase-download::before{content:"\F0D8"}.mdi-briefcase-download-outline::before{content:"\FC19"}.mdi-briefcase-edit::before{content:"\FA97"}.mdi-briefcase-edit-outline::before{content:"\FC1A"}.mdi-briefcase-minus::before{content:"\FA29"}.mdi-briefcase-minus-outline::before{content:"\FC1B"}.mdi-briefcase-outline::before{content:"\F813"}.mdi-briefcase-plus::before{content:"\FA2A"}.mdi-briefcase-plus-outline::before{content:"\FC1C"}.mdi-briefcase-remove::before{content:"\FA2B"}.mdi-briefcase-remove-outline::before{content:"\FC1D"}.mdi-briefcase-search::before{content:"\FA2C"}.mdi-briefcase-search-outline::before{content:"\FC1E"}.mdi-briefcase-upload::before{content:"\F0D9"}.mdi-briefcase-upload-outline::before{content:"\FC1F"}.mdi-brightness-1::before{content:"\F0DA"}.mdi-brightness-2::before{content:"\F0DB"}.mdi-brightness-3::before{content:"\F0DC"}.mdi-brightness-4::before{content:"\F0DD"}.mdi-brightness-5::before{content:"\F0DE"}.mdi-brightness-6::before{content:"\F0DF"}.mdi-brightness-7::before{content:"\F0E0"}.mdi-brightness-auto::before{content:"\F0E1"}.mdi-brightness-percent::before{content:"\FCCE"}.mdi-broom::before{content:"\F0E2"}.mdi-brush::before{content:"\F0E3"}.mdi-buddhism::before{content:"\F94A"}.mdi-buffer::before{content:"\F619"}.mdi-bug::before{content:"\F0E4"}.mdi-bug-check::before{content:"\FA2D"}.mdi-bug-check-outline::before{content:"\FA2E"}.mdi-bug-outline::before{content:"\FA2F"}.mdi-bugle::before{content:"\FD90"}.mdi-bulldozer::before{content:"\FB07"}.mdi-bullet::before{content:"\FCCF"}.mdi-bulletin-board::before{content:"\F0E5"}.mdi-bullhorn::before{content:"\F0E6"}.mdi-bullhorn-outline::before{content:"\FB08"}.mdi-bullseye::before{content:"\F5DD"}.mdi-bullseye-arrow::before{content:"\F8C8"}.mdi-bus::before{content:"\F0E7"}.mdi-bus-alert::before{content:"\FA98"}.mdi-bus-articulated-end::before{content:"\F79B"}.mdi-bus-articulated-front::before{content:"\F79C"}.mdi-bus-clock::before{content:"\F8C9"}.mdi-bus-double-decker::before{content:"\F79D"}.mdi-bus-multiple::before{content:"\FF5C"}.mdi-bus-school::before{content:"\F79E"}.mdi-bus-side::before{content:"\F79F"}.mdi-bus-stop::before{content:"\F0034"}.mdi-bus-stop-covered::before{content:"\F0035"}.mdi-bus-stop-uncovered::before{content:"\F0036"}.mdi-cached::before{content:"\F0E8"}.mdi-cactus::before{content:"\FD91"}.mdi-cake::before{content:"\F0E9"}.mdi-cake-layered::before{content:"\F0EA"}.mdi-cake-variant::before{content:"\F0EB"}.mdi-calculator::before{content:"\F0EC"}.mdi-calculator-variant::before{content:"\FA99"}.mdi-calendar::before{content:"\F0ED"}.mdi-calendar-account::before{content:"\FEF4"}.mdi-calendar-account-outline::before{content:"\FEF5"}.mdi-calendar-alert::before{content:"\FA30"}.mdi-calendar-arrow-left::before{content:"\F015F"}.mdi-calendar-arrow-right::before{content:"\F0160"}.mdi-calendar-blank::before{content:"\F0EE"}.mdi-calendar-blank-multiple::before{content:"\F009E"}.mdi-calendar-blank-outline::before{content:"\FB42"}.mdi-calendar-check::before{content:"\F0EF"}.mdi-calendar-check-outline::before{content:"\FC20"}.mdi-calendar-clock::before{content:"\F0F0"}.mdi-calendar-edit::before{content:"\F8A6"}.mdi-calendar-export::before{content:"\FB09"}.mdi-calendar-heart::before{content:"\F9D1"}.mdi-calendar-import::before{content:"\FB0A"}.mdi-calendar-minus::before{content:"\FD38"}.mdi-calendar-month::before{content:"\FDFA"}.mdi-calendar-month-outline::before{content:"\FDFB"}.mdi-calendar-multiple::before{content:"\F0F1"}.mdi-calendar-multiple-check::before{content:"\F0F2"}.mdi-calendar-multiselect::before{content:"\FA31"}.mdi-calendar-outline::before{content:"\FB43"}.mdi-calendar-plus::before{content:"\F0F3"}.mdi-calendar-question::before{content:"\F691"}.mdi-calendar-range::before{content:"\F678"}.mdi-calendar-range-outline::before{content:"\FB44"}.mdi-calendar-remove::before{content:"\F0F4"}.mdi-calendar-remove-outline::before{content:"\FC21"}.mdi-calendar-repeat::before{content:"\FEAB"}.mdi-calendar-repeat-outline::before{content:"\FEAC"}.mdi-calendar-search::before{content:"\F94B"}.mdi-calendar-star::before{content:"\F9D2"}.mdi-calendar-text::before{content:"\F0F5"}.mdi-calendar-text-outline::before{content:"\FC22"}.mdi-calendar-today::before{content:"\F0F6"}.mdi-calendar-week::before{content:"\FA32"}.mdi-calendar-week-begin::before{content:"\FA33"}.mdi-calendar-weekend::before{content:"\FEF6"}.mdi-calendar-weekend-outline::before{content:"\FEF7"}.mdi-call-made::before{content:"\F0F7"}.mdi-call-merge::before{content:"\F0F8"}.mdi-call-missed::before{content:"\F0F9"}.mdi-call-received::before{content:"\F0FA"}.mdi-call-split::before{content:"\F0FB"}.mdi-camcorder::before{content:"\F0FC"}.mdi-camcorder-box::before{content:"\F0FD"}.mdi-camcorder-box-off::before{content:"\F0FE"}.mdi-camcorder-off::before{content:"\F0FF"}.mdi-camera::before{content:"\F100"}.mdi-camera-account::before{content:"\F8CA"}.mdi-camera-burst::before{content:"\F692"}.mdi-camera-control::before{content:"\FB45"}.mdi-camera-enhance::before{content:"\F101"}.mdi-camera-enhance-outline::before{content:"\FB46"}.mdi-camera-front::before{content:"\F102"}.mdi-camera-front-variant::before{content:"\F103"}.mdi-camera-gopro::before{content:"\F7A0"}.mdi-camera-image::before{content:"\F8CB"}.mdi-camera-iris::before{content:"\F104"}.mdi-camera-metering-center::before{content:"\F7A1"}.mdi-camera-metering-matrix::before{content:"\F7A2"}.mdi-camera-metering-partial::before{content:"\F7A3"}.mdi-camera-metering-spot::before{content:"\F7A4"}.mdi-camera-off::before{content:"\F5DF"}.mdi-camera-outline::before{content:"\FD39"}.mdi-camera-party-mode::before{content:"\F105"}.mdi-camera-plus::before{content:"\FEF8"}.mdi-camera-plus-outline::before{content:"\FEF9"}.mdi-camera-rear::before{content:"\F106"}.mdi-camera-rear-variant::before{content:"\F107"}.mdi-camera-retake::before{content:"\FDFC"}.mdi-camera-retake-outline::before{content:"\FDFD"}.mdi-camera-switch::before{content:"\F108"}.mdi-camera-timer::before{content:"\F109"}.mdi-camera-wireless::before{content:"\FD92"}.mdi-camera-wireless-outline::before{content:"\FD93"}.mdi-campfire::before{content:"\FEFA"}.mdi-cancel::before{content:"\F739"}.mdi-candle::before{content:"\F5E2"}.mdi-candycane::before{content:"\F10A"}.mdi-cannabis::before{content:"\F7A5"}.mdi-caps-lock::before{content:"\FA9A"}.mdi-car::before{content:"\F10B"}.mdi-car-2-plus::before{content:"\F0037"}.mdi-car-3-plus::before{content:"\F0038"}.mdi-car-back::before{content:"\FDFE"}.mdi-car-battery::before{content:"\F10C"}.mdi-car-brake-abs::before{content:"\FC23"}.mdi-car-brake-alert::before{content:"\FC24"}.mdi-car-brake-hold::before{content:"\FD3A"}.mdi-car-brake-parking::before{content:"\FD3B"}.mdi-car-brake-retarder::before{content:"\F0039"}.mdi-car-child-seat::before{content:"\FFC3"}.mdi-car-clutch::before{content:"\F003A"}.mdi-car-connected::before{content:"\F10D"}.mdi-car-convertible::before{content:"\F7A6"}.mdi-car-coolant-level::before{content:"\F003B"}.mdi-car-cruise-control::before{content:"\FD3C"}.mdi-car-defrost-front::before{content:"\FD3D"}.mdi-car-defrost-rear::before{content:"\FD3E"}.mdi-car-door::before{content:"\FB47"}.mdi-car-door-lock::before{content:"\F00C8"}.mdi-car-electric::before{content:"\FB48"}.mdi-car-esp::before{content:"\FC25"}.mdi-car-estate::before{content:"\F7A7"}.mdi-car-hatchback::before{content:"\F7A8"}.mdi-car-key::before{content:"\FB49"}.mdi-car-light-dimmed::before{content:"\FC26"}.mdi-car-light-fog::before{content:"\FC27"}.mdi-car-light-high::before{content:"\FC28"}.mdi-car-limousine::before{content:"\F8CC"}.mdi-car-multiple::before{content:"\FB4A"}.mdi-car-off::before{content:"\FDFF"}.mdi-car-parking-lights::before{content:"\FD3F"}.mdi-car-pickup::before{content:"\F7A9"}.mdi-car-seat::before{content:"\FFC4"}.mdi-car-seat-cooler::before{content:"\FFC5"}.mdi-car-seat-heater::before{content:"\FFC6"}.mdi-car-shift-pattern::before{content:"\FF5D"}.mdi-car-side::before{content:"\F7AA"}.mdi-car-sports::before{content:"\F7AB"}.mdi-car-tire-alert::before{content:"\FC29"}.mdi-car-traction-control::before{content:"\FD40"}.mdi-car-turbocharger::before{content:"\F003C"}.mdi-car-wash::before{content:"\F10E"}.mdi-car-windshield::before{content:"\F003D"}.mdi-car-windshield-outline::before{content:"\F003E"}.mdi-caravan::before{content:"\F7AC"}.mdi-card::before{content:"\FB4B"}.mdi-card-bulleted::before{content:"\FB4C"}.mdi-card-bulleted-off::before{content:"\FB4D"}.mdi-card-bulleted-off-outline::before{content:"\FB4E"}.mdi-card-bulleted-outline::before{content:"\FB4F"}.mdi-card-bulleted-settings::before{content:"\FB50"}.mdi-card-bulleted-settings-outline::before{content:"\FB51"}.mdi-card-outline::before{content:"\FB52"}.mdi-card-search::before{content:"\F009F"}.mdi-card-search-outline::before{content:"\F00A0"}.mdi-card-text::before{content:"\FB53"}.mdi-card-text-outline::before{content:"\FB54"}.mdi-cards::before{content:"\F638"}.mdi-cards-club::before{content:"\F8CD"}.mdi-cards-diamond::before{content:"\F8CE"}.mdi-cards-diamond-outline::before{content:"\F003F"}.mdi-cards-heart::before{content:"\F8CF"}.mdi-cards-outline::before{content:"\F639"}.mdi-cards-playing-outline::before{content:"\F63A"}.mdi-cards-spade::before{content:"\F8D0"}.mdi-cards-variant::before{content:"\F6C6"}.mdi-carrot::before{content:"\F10F"}.mdi-cart::before{content:"\F110"}.mdi-cart-arrow-down::before{content:"\FD42"}.mdi-cart-arrow-right::before{content:"\FC2A"}.mdi-cart-arrow-up::before{content:"\FD43"}.mdi-cart-minus::before{content:"\FD44"}.mdi-cart-off::before{content:"\F66B"}.mdi-cart-outline::before{content:"\F111"}.mdi-cart-plus::before{content:"\F112"}.mdi-cart-remove::before{content:"\FD45"}.mdi-case-sensitive-alt::before{content:"\F113"}.mdi-cash::before{content:"\F114"}.mdi-cash-100::before{content:"\F115"}.mdi-cash-marker::before{content:"\FD94"}.mdi-cash-multiple::before{content:"\F116"}.mdi-cash-refund::before{content:"\FA9B"}.mdi-cash-register::before{content:"\FCD0"}.mdi-cash-usd::before{content:"\F01A1"}.mdi-cash-usd-outline::before{content:"\F117"}.mdi-cassette::before{content:"\F9D3"}.mdi-cast::before{content:"\F118"}.mdi-cast-audio::before{content:"\F0040"}.mdi-cast-connected::before{content:"\F119"}.mdi-cast-education::before{content:"\FE6D"}.mdi-cast-off::before{content:"\F789"}.mdi-castle::before{content:"\F11A"}.mdi-cat::before{content:"\F11B"}.mdi-cctv::before{content:"\F7AD"}.mdi-ceiling-light::before{content:"\F768"}.mdi-cellphone::before{content:"\F11C"}.mdi-cellphone-android::before{content:"\F11D"}.mdi-cellphone-arrow-down::before{content:"\F9D4"}.mdi-cellphone-basic::before{content:"\F11E"}.mdi-cellphone-dock::before{content:"\F11F"}.mdi-cellphone-erase::before{content:"\F94C"}.mdi-cellphone-information::before{content:"\FF5E"}.mdi-cellphone-iphone::before{content:"\F120"}.mdi-cellphone-key::before{content:"\F94D"}.mdi-cellphone-link::before{content:"\F121"}.mdi-cellphone-link-off::before{content:"\F122"}.mdi-cellphone-lock::before{content:"\F94E"}.mdi-cellphone-message::before{content:"\F8D2"}.mdi-cellphone-message-off::before{content:"\F00FD"}.mdi-cellphone-nfc::before{content:"\FEAD"}.mdi-cellphone-off::before{content:"\F94F"}.mdi-cellphone-play::before{content:"\F0041"}.mdi-cellphone-screenshot::before{content:"\FA34"}.mdi-cellphone-settings::before{content:"\F123"}.mdi-cellphone-settings-variant::before{content:"\F950"}.mdi-cellphone-sound::before{content:"\F951"}.mdi-cellphone-text::before{content:"\F8D1"}.mdi-cellphone-wireless::before{content:"\F814"}.mdi-celtic-cross::before{content:"\FCD1"}.mdi-centos::before{content:"\F0145"}.mdi-certificate::before{content:"\F124"}.mdi-certificate-outline::before{content:"\F01B3"}.mdi-chair-rolling::before{content:"\FFBA"}.mdi-chair-school::before{content:"\F125"}.mdi-charity::before{content:"\FC2B"}.mdi-chart-arc::before{content:"\F126"}.mdi-chart-areaspline::before{content:"\F127"}.mdi-chart-areaspline-variant::before{content:"\FEAE"}.mdi-chart-bar::before{content:"\F128"}.mdi-chart-bar-stacked::before{content:"\F769"}.mdi-chart-bell-curve::before{content:"\FC2C"}.mdi-chart-bell-curve-cumulative::before{content:"\FFC7"}.mdi-chart-bubble::before{content:"\F5E3"}.mdi-chart-donut::before{content:"\F7AE"}.mdi-chart-donut-variant::before{content:"\F7AF"}.mdi-chart-gantt::before{content:"\F66C"}.mdi-chart-histogram::before{content:"\F129"}.mdi-chart-line::before{content:"\F12A"}.mdi-chart-line-stacked::before{content:"\F76A"}.mdi-chart-line-variant::before{content:"\F7B0"}.mdi-chart-multiline::before{content:"\F8D3"}.mdi-chart-pie::before{content:"\F12B"}.mdi-chart-scatter-plot::before{content:"\FEAF"}.mdi-chart-scatter-plot-hexbin::before{content:"\F66D"}.mdi-chart-timeline::before{content:"\F66E"}.mdi-chart-timeline-variant::before{content:"\FEB0"}.mdi-chart-tree::before{content:"\FEB1"}.mdi-chat::before{content:"\FB55"}.mdi-chat-alert::before{content:"\FB56"}.mdi-chat-outline::before{content:"\FEFB"}.mdi-chat-processing::before{content:"\FB57"}.mdi-check::before{content:"\F12C"}.mdi-check-all::before{content:"\F12D"}.mdi-check-bold::before{content:"\FE6E"}.mdi-check-box-multiple-outline::before{content:"\FC2D"}.mdi-check-box-outline::before{content:"\FC2E"}.mdi-check-circle::before{content:"\F5E0"}.mdi-check-circle-outline::before{content:"\F5E1"}.mdi-check-decagram::before{content:"\F790"}.mdi-check-network::before{content:"\FC2F"}.mdi-check-network-outline::before{content:"\FC30"}.mdi-check-outline::before{content:"\F854"}.mdi-check-underline::before{content:"\FE70"}.mdi-check-underline-circle::before{content:"\FE71"}.mdi-check-underline-circle-outline::before{content:"\FE72"}.mdi-checkbook::before{content:"\FA9C"}.mdi-checkbox-blank::before{content:"\F12E"}.mdi-checkbox-blank-circle::before{content:"\F12F"}.mdi-checkbox-blank-circle-outline::before{content:"\F130"}.mdi-checkbox-blank-outline::before{content:"\F131"}.mdi-checkbox-intermediate::before{content:"\F855"}.mdi-checkbox-marked::before{content:"\F132"}.mdi-checkbox-marked-circle::before{content:"\F133"}.mdi-checkbox-marked-circle-outline::before{content:"\F134"}.mdi-checkbox-marked-outline::before{content:"\F135"}.mdi-checkbox-multiple-blank::before{content:"\F136"}.mdi-checkbox-multiple-blank-circle::before{content:"\F63B"}.mdi-checkbox-multiple-blank-circle-outline::before{content:"\F63C"}.mdi-checkbox-multiple-blank-outline::before{content:"\F137"}.mdi-checkbox-multiple-marked::before{content:"\F138"}.mdi-checkbox-multiple-marked-circle::before{content:"\F63D"}.mdi-checkbox-multiple-marked-circle-outline::before{content:"\F63E"}.mdi-checkbox-multiple-marked-outline::before{content:"\F139"}.mdi-checkerboard::before{content:"\F13A"}.mdi-chef-hat::before{content:"\FB58"}.mdi-chemical-weapon::before{content:"\F13B"}.mdi-chess-bishop::before{content:"\F85B"}.mdi-chess-king::before{content:"\F856"}.mdi-chess-knight::before{content:"\F857"}.mdi-chess-pawn::before{content:"\F858"}.mdi-chess-queen::before{content:"\F859"}.mdi-chess-rook::before{content:"\F85A"}.mdi-chevron-double-down::before{content:"\F13C"}.mdi-chevron-double-left::before{content:"\F13D"}.mdi-chevron-double-right::before{content:"\F13E"}.mdi-chevron-double-up::before{content:"\F13F"}.mdi-chevron-down::before{content:"\F140"}.mdi-chevron-down-box::before{content:"\F9D5"}.mdi-chevron-down-box-outline::before{content:"\F9D6"}.mdi-chevron-down-circle::before{content:"\FB0B"}.mdi-chevron-down-circle-outline::before{content:"\FB0C"}.mdi-chevron-left::before{content:"\F141"}.mdi-chevron-left-box::before{content:"\F9D7"}.mdi-chevron-left-box-outline::before{content:"\F9D8"}.mdi-chevron-left-circle::before{content:"\FB0D"}.mdi-chevron-left-circle-outline::before{content:"\FB0E"}.mdi-chevron-right::before{content:"\F142"}.mdi-chevron-right-box::before{content:"\F9D9"}.mdi-chevron-right-box-outline::before{content:"\F9DA"}.mdi-chevron-right-circle::before{content:"\FB0F"}.mdi-chevron-right-circle-outline::before{content:"\FB10"}.mdi-chevron-triple-down::before{content:"\FD95"}.mdi-chevron-triple-left::before{content:"\FD96"}.mdi-chevron-triple-right::before{content:"\FD97"}.mdi-chevron-triple-up::before{content:"\FD98"}.mdi-chevron-up::before{content:"\F143"}.mdi-chevron-up-box::before{content:"\F9DB"}.mdi-chevron-up-box-outline::before{content:"\F9DC"}.mdi-chevron-up-circle::before{content:"\FB11"}.mdi-chevron-up-circle-outline::before{content:"\FB12"}.mdi-chili-hot::before{content:"\F7B1"}.mdi-chili-medium::before{content:"\F7B2"}.mdi-chili-mild::before{content:"\F7B3"}.mdi-chip::before{content:"\F61A"}.mdi-christianity::before{content:"\F952"}.mdi-christianity-outline::before{content:"\FCD2"}.mdi-church::before{content:"\F144"}.mdi-cigar::before{content:"\F01B4"}.mdi-circle::before{content:"\F764"}.mdi-circle-double::before{content:"\FEB2"}.mdi-circle-edit-outline::before{content:"\F8D4"}.mdi-circle-expand::before{content:"\FEB3"}.mdi-circle-medium::before{content:"\F9DD"}.mdi-circle-off-outline::before{content:"\F00FE"}.mdi-circle-outline::before{content:"\F765"}.mdi-circle-slice-1::before{content:"\FA9D"}.mdi-circle-slice-2::before{content:"\FA9E"}.mdi-circle-slice-3::before{content:"\FA9F"}.mdi-circle-slice-4::before{content:"\FAA0"}.mdi-circle-slice-5::before{content:"\FAA1"}.mdi-circle-slice-6::before{content:"\FAA2"}.mdi-circle-slice-7::before{content:"\FAA3"}.mdi-circle-slice-8::before{content:"\FAA4"}.mdi-circle-small::before{content:"\F9DE"}.mdi-circular-saw::before{content:"\FE73"}.mdi-cisco-webex::before{content:"\F145"}.mdi-city::before{content:"\F146"}.mdi-city-variant::before{content:"\FA35"}.mdi-city-variant-outline::before{content:"\FA36"}.mdi-clipboard::before{content:"\F147"}.mdi-clipboard-account::before{content:"\F148"}.mdi-clipboard-account-outline::before{content:"\FC31"}.mdi-clipboard-alert::before{content:"\F149"}.mdi-clipboard-alert-outline::before{content:"\FCD3"}.mdi-clipboard-arrow-down::before{content:"\F14A"}.mdi-clipboard-arrow-down-outline::before{content:"\FC32"}.mdi-clipboard-arrow-left::before{content:"\F14B"}.mdi-clipboard-arrow-left-outline::before{content:"\FCD4"}.mdi-clipboard-arrow-right::before{content:"\FCD5"}.mdi-clipboard-arrow-right-outline::before{content:"\FCD6"}.mdi-clipboard-arrow-up::before{content:"\FC33"}.mdi-clipboard-arrow-up-outline::before{content:"\FC34"}.mdi-clipboard-check::before{content:"\F14C"}.mdi-clipboard-check-outline::before{content:"\F8A7"}.mdi-clipboard-flow::before{content:"\F6C7"}.mdi-clipboard-flow-outline::before{content:"\F0142"}.mdi-clipboard-list::before{content:"\F00FF"}.mdi-clipboard-list-outline::before{content:"\F0100"}.mdi-clipboard-outline::before{content:"\F14D"}.mdi-clipboard-play::before{content:"\FC35"}.mdi-clipboard-play-outline::before{content:"\FC36"}.mdi-clipboard-plus::before{content:"\F750"}.mdi-clipboard-pulse::before{content:"\F85C"}.mdi-clipboard-pulse-outline::before{content:"\F85D"}.mdi-clipboard-text::before{content:"\F14E"}.mdi-clipboard-text-outline::before{content:"\FA37"}.mdi-clipboard-text-play::before{content:"\FC37"}.mdi-clipboard-text-play-outline::before{content:"\FC38"}.mdi-clippy::before{content:"\F14F"}.mdi-clock::before{content:"\F953"}.mdi-clock-alert::before{content:"\F954"}.mdi-clock-alert-outline::before{content:"\F5CE"}.mdi-clock-check::before{content:"\FFC8"}.mdi-clock-check-outline::before{content:"\FFC9"}.mdi-clock-digital::before{content:"\FEB4"}.mdi-clock-end::before{content:"\F151"}.mdi-clock-fast::before{content:"\F152"}.mdi-clock-in::before{content:"\F153"}.mdi-clock-out::before{content:"\F154"}.mdi-clock-outline::before{content:"\F150"}.mdi-clock-start::before{content:"\F155"}.mdi-close::before{content:"\F156"}.mdi-close-box::before{content:"\F157"}.mdi-close-box-multiple::before{content:"\FC39"}.mdi-close-box-multiple-outline::before{content:"\FC3A"}.mdi-close-box-outline::before{content:"\F158"}.mdi-close-circle::before{content:"\F159"}.mdi-close-circle-outline::before{content:"\F15A"}.mdi-close-network::before{content:"\F15B"}.mdi-close-network-outline::before{content:"\FC3B"}.mdi-close-octagon::before{content:"\F15C"}.mdi-close-octagon-outline::before{content:"\F15D"}.mdi-close-outline::before{content:"\F6C8"}.mdi-closed-caption::before{content:"\F15E"}.mdi-closed-caption-outline::before{content:"\FD99"}.mdi-cloud::before{content:"\F15F"}.mdi-cloud-alert::before{content:"\F9DF"}.mdi-cloud-braces::before{content:"\F7B4"}.mdi-cloud-check::before{content:"\F160"}.mdi-cloud-circle::before{content:"\F161"}.mdi-cloud-download::before{content:"\F162"}.mdi-cloud-download-outline::before{content:"\FB59"}.mdi-cloud-off-outline::before{content:"\F164"}.mdi-cloud-outline::before{content:"\F163"}.mdi-cloud-print::before{content:"\F165"}.mdi-cloud-print-outline::before{content:"\F166"}.mdi-cloud-question::before{content:"\FA38"}.mdi-cloud-search::before{content:"\F955"}.mdi-cloud-search-outline::before{content:"\F956"}.mdi-cloud-sync::before{content:"\F63F"}.mdi-cloud-tags::before{content:"\F7B5"}.mdi-cloud-upload::before{content:"\F167"}.mdi-cloud-upload-outline::before{content:"\FB5A"}.mdi-clover::before{content:"\F815"}.mdi-coach-lamp::before{content:"\F0042"}.mdi-coat-rack::before{content:"\F00C9"}.mdi-code-array::before{content:"\F168"}.mdi-code-braces::before{content:"\F169"}.mdi-code-braces-box::before{content:"\F0101"}.mdi-code-brackets::before{content:"\F16A"}.mdi-code-equal::before{content:"\F16B"}.mdi-code-greater-than::before{content:"\F16C"}.mdi-code-greater-than-or-equal::before{content:"\F16D"}.mdi-code-less-than::before{content:"\F16E"}.mdi-code-less-than-or-equal::before{content:"\F16F"}.mdi-code-not-equal::before{content:"\F170"}.mdi-code-not-equal-variant::before{content:"\F171"}.mdi-code-parentheses::before{content:"\F172"}.mdi-code-parentheses-box::before{content:"\F0102"}.mdi-code-string::before{content:"\F173"}.mdi-code-tags::before{content:"\F174"}.mdi-code-tags-check::before{content:"\F693"}.mdi-codepen::before{content:"\F175"}.mdi-coffee::before{content:"\F176"}.mdi-coffee-maker::before{content:"\F00CA"}.mdi-coffee-off::before{content:"\FFCA"}.mdi-coffee-off-outline::before{content:"\FFCB"}.mdi-coffee-outline::before{content:"\F6C9"}.mdi-coffee-to-go::before{content:"\F177"}.mdi-coffin::before{content:"\FB5B"}.mdi-cogs::before{content:"\F8D5"}.mdi-coin::before{content:"\F0196"}.mdi-coin-outline::before{content:"\F178"}.mdi-coins::before{content:"\F694"}.mdi-collage::before{content:"\F640"}.mdi-collapse-all::before{content:"\FAA5"}.mdi-collapse-all-outline::before{content:"\FAA6"}.mdi-color-helper::before{content:"\F179"}.mdi-comma::before{content:"\FE74"}.mdi-comma-box::before{content:"\FE75"}.mdi-comma-box-outline::before{content:"\FE76"}.mdi-comma-circle::before{content:"\FE77"}.mdi-comma-circle-outline::before{content:"\FE78"}.mdi-comment::before{content:"\F17A"}.mdi-comment-account::before{content:"\F17B"}.mdi-comment-account-outline::before{content:"\F17C"}.mdi-comment-alert::before{content:"\F17D"}.mdi-comment-alert-outline::before{content:"\F17E"}.mdi-comment-arrow-left::before{content:"\F9E0"}.mdi-comment-arrow-left-outline::before{content:"\F9E1"}.mdi-comment-arrow-right::before{content:"\F9E2"}.mdi-comment-arrow-right-outline::before{content:"\F9E3"}.mdi-comment-check::before{content:"\F17F"}.mdi-comment-check-outline::before{content:"\F180"}.mdi-comment-eye::before{content:"\FA39"}.mdi-comment-eye-outline::before{content:"\FA3A"}.mdi-comment-multiple::before{content:"\F85E"}.mdi-comment-multiple-outline::before{content:"\F181"}.mdi-comment-outline::before{content:"\F182"}.mdi-comment-plus::before{content:"\F9E4"}.mdi-comment-plus-outline::before{content:"\F183"}.mdi-comment-processing::before{content:"\F184"}.mdi-comment-processing-outline::before{content:"\F185"}.mdi-comment-question::before{content:"\F816"}.mdi-comment-question-outline::before{content:"\F186"}.mdi-comment-quote::before{content:"\F0043"}.mdi-comment-quote-outline::before{content:"\F0044"}.mdi-comment-remove::before{content:"\F5DE"}.mdi-comment-remove-outline::before{content:"\F187"}.mdi-comment-search::before{content:"\FA3B"}.mdi-comment-search-outline::before{content:"\FA3C"}.mdi-comment-text::before{content:"\F188"}.mdi-comment-text-multiple::before{content:"\F85F"}.mdi-comment-text-multiple-outline::before{content:"\F860"}.mdi-comment-text-outline::before{content:"\F189"}.mdi-compare::before{content:"\F18A"}.mdi-compass::before{content:"\F18B"}.mdi-compass-off::before{content:"\FB5C"}.mdi-compass-off-outline::before{content:"\FB5D"}.mdi-compass-outline::before{content:"\F18C"}.mdi-concourse-ci::before{content:"\F00CB"}.mdi-console::before{content:"\F18D"}.mdi-console-line::before{content:"\F7B6"}.mdi-console-network::before{content:"\F8A8"}.mdi-console-network-outline::before{content:"\FC3C"}.mdi-consolidate::before{content:"\F0103"}.mdi-contact-mail::before{content:"\F18E"}.mdi-contact-mail-outline::before{content:"\FEB5"}.mdi-contact-phone::before{content:"\FEB6"}.mdi-contact-phone-outline::before{content:"\FEB7"}.mdi-contactless-payment::before{content:"\FD46"}.mdi-contacts::before{content:"\F6CA"}.mdi-contain::before{content:"\FA3D"}.mdi-contain-end::before{content:"\FA3E"}.mdi-contain-start::before{content:"\FA3F"}.mdi-content-copy::before{content:"\F18F"}.mdi-content-cut::before{content:"\F190"}.mdi-content-duplicate::before{content:"\F191"}.mdi-content-paste::before{content:"\F192"}.mdi-content-save::before{content:"\F193"}.mdi-content-save-alert::before{content:"\FF5F"}.mdi-content-save-alert-outline::before{content:"\FF60"}.mdi-content-save-all::before{content:"\F194"}.mdi-content-save-all-outline::before{content:"\FF61"}.mdi-content-save-edit::before{content:"\FCD7"}.mdi-content-save-edit-outline::before{content:"\FCD8"}.mdi-content-save-move::before{content:"\FE79"}.mdi-content-save-move-outline::before{content:"\FE7A"}.mdi-content-save-outline::before{content:"\F817"}.mdi-content-save-settings::before{content:"\F61B"}.mdi-content-save-settings-outline::before{content:"\FB13"}.mdi-contrast::before{content:"\F195"}.mdi-contrast-box::before{content:"\F196"}.mdi-contrast-circle::before{content:"\F197"}.mdi-controller-classic::before{content:"\FB5E"}.mdi-controller-classic-outline::before{content:"\FB5F"}.mdi-cookie::before{content:"\F198"}.mdi-coolant-temperature::before{content:"\F3C8"}.mdi-copyright::before{content:"\F5E6"}.mdi-cordova::before{content:"\F957"}.mdi-corn::before{content:"\F7B7"}.mdi-counter::before{content:"\F199"}.mdi-cow::before{content:"\F19A"}.mdi-cowboy::before{content:"\FEB8"}.mdi-cpu-32-bit::before{content:"\FEFC"}.mdi-cpu-64-bit::before{content:"\FEFD"}.mdi-crane::before{content:"\F861"}.mdi-creation::before{content:"\F1C9"}.mdi-creative-commons::before{content:"\FD47"}.mdi-credit-card::before{content:"\F0010"}.mdi-credit-card-clock::before{content:"\FEFE"}.mdi-credit-card-clock-outline::before{content:"\FFBC"}.mdi-credit-card-marker::before{content:"\F6A7"}.mdi-credit-card-marker-outline::before{content:"\FD9A"}.mdi-credit-card-minus::before{content:"\FFCC"}.mdi-credit-card-minus-outline::before{content:"\FFCD"}.mdi-credit-card-multiple::before{content:"\F0011"}.mdi-credit-card-multiple-outline::before{content:"\F19C"}.mdi-credit-card-off::before{content:"\F0012"}.mdi-credit-card-off-outline::before{content:"\F5E4"}.mdi-credit-card-outline::before{content:"\F19B"}.mdi-credit-card-plus::before{content:"\F0013"}.mdi-credit-card-plus-outline::before{content:"\F675"}.mdi-credit-card-refund::before{content:"\F0014"}.mdi-credit-card-refund-outline::before{content:"\FAA7"}.mdi-credit-card-remove::before{content:"\FFCE"}.mdi-credit-card-remove-outline::before{content:"\FFCF"}.mdi-credit-card-scan::before{content:"\F0015"}.mdi-credit-card-scan-outline::before{content:"\F19D"}.mdi-credit-card-settings::before{content:"\F0016"}.mdi-credit-card-settings-outline::before{content:"\F8D6"}.mdi-credit-card-wireless::before{content:"\F801"}.mdi-credit-card-wireless-outline::before{content:"\FD48"}.mdi-cricket::before{content:"\FD49"}.mdi-crop::before{content:"\F19E"}.mdi-crop-free::before{content:"\F19F"}.mdi-crop-landscape::before{content:"\F1A0"}.mdi-crop-portrait::before{content:"\F1A1"}.mdi-crop-rotate::before{content:"\F695"}.mdi-crop-square::before{content:"\F1A2"}.mdi-crosshairs::before{content:"\F1A3"}.mdi-crosshairs-gps::before{content:"\F1A4"}.mdi-crosshairs-off::before{content:"\FF62"}.mdi-crosshairs-question::before{content:"\F0161"}.mdi-crown::before{content:"\F1A5"}.mdi-cryengine::before{content:"\F958"}.mdi-crystal-ball::before{content:"\FB14"}.mdi-cube::before{content:"\F1A6"}.mdi-cube-outline::before{content:"\F1A7"}.mdi-cube-scan::before{content:"\FB60"}.mdi-cube-send::before{content:"\F1A8"}.mdi-cube-unfolded::before{content:"\F1A9"}.mdi-cup::before{content:"\F1AA"}.mdi-cup-off::before{content:"\F5E5"}.mdi-cup-water::before{content:"\F1AB"}.mdi-cupboard::before{content:"\FF63"}.mdi-cupboard-outline::before{content:"\FF64"}.mdi-cupcake::before{content:"\F959"}.mdi-curling::before{content:"\F862"}.mdi-currency-bdt::before{content:"\F863"}.mdi-currency-brl::before{content:"\FB61"}.mdi-currency-btc::before{content:"\F1AC"}.mdi-currency-cny::before{content:"\F7B9"}.mdi-currency-eth::before{content:"\F7BA"}.mdi-currency-eur::before{content:"\F1AD"}.mdi-currency-gbp::before{content:"\F1AE"}.mdi-currency-ils::before{content:"\FC3D"}.mdi-currency-inr::before{content:"\F1AF"}.mdi-currency-jpy::before{content:"\F7BB"}.mdi-currency-krw::before{content:"\F7BC"}.mdi-currency-kzt::before{content:"\F864"}.mdi-currency-ngn::before{content:"\F1B0"}.mdi-currency-php::before{content:"\F9E5"}.mdi-currency-rial::before{content:"\FEB9"}.mdi-currency-rub::before{content:"\F1B1"}.mdi-currency-sign::before{content:"\F7BD"}.mdi-currency-try::before{content:"\F1B2"}.mdi-currency-twd::before{content:"\F7BE"}.mdi-currency-usd::before{content:"\F1B3"}.mdi-currency-usd-off::before{content:"\F679"}.mdi-current-ac::before{content:"\F95A"}.mdi-current-dc::before{content:"\F95B"}.mdi-cursor-default::before{content:"\F1B4"}.mdi-cursor-default-click::before{content:"\FCD9"}.mdi-cursor-default-click-outline::before{content:"\FCDA"}.mdi-cursor-default-gesture::before{content:"\F0152"}.mdi-cursor-default-gesture-outline::before{content:"\F0153"}.mdi-cursor-default-outline::before{content:"\F1B5"}.mdi-cursor-move::before{content:"\F1B6"}.mdi-cursor-pointer::before{content:"\F1B7"}.mdi-cursor-text::before{content:"\F5E7"}.mdi-database::before{content:"\F1B8"}.mdi-database-check::before{content:"\FAA8"}.mdi-database-edit::before{content:"\FB62"}.mdi-database-export::before{content:"\F95D"}.mdi-database-import::before{content:"\F95C"}.mdi-database-lock::before{content:"\FAA9"}.mdi-database-minus::before{content:"\F1B9"}.mdi-database-plus::before{content:"\F1BA"}.mdi-database-refresh::before{content:"\FCDB"}.mdi-database-remove::before{content:"\FCDC"}.mdi-database-search::before{content:"\F865"}.mdi-database-settings::before{content:"\FCDD"}.mdi-death-star::before{content:"\F8D7"}.mdi-death-star-variant::before{content:"\F8D8"}.mdi-deathly-hallows::before{content:"\FB63"}.mdi-debian::before{content:"\F8D9"}.mdi-debug-step-into::before{content:"\F1BB"}.mdi-debug-step-out::before{content:"\F1BC"}.mdi-debug-step-over::before{content:"\F1BD"}.mdi-decagram::before{content:"\F76B"}.mdi-decagram-outline::before{content:"\F76C"}.mdi-decimal::before{content:"\F00CC"}.mdi-decimal-comma::before{content:"\F00CD"}.mdi-decimal-comma-decrease::before{content:"\F00CE"}.mdi-decimal-comma-increase::before{content:"\F00CF"}.mdi-decimal-decrease::before{content:"\F1BE"}.mdi-decimal-increase::before{content:"\F1BF"}.mdi-delete::before{content:"\F1C0"}.mdi-delete-alert::before{content:"\F00D0"}.mdi-delete-alert-outline::before{content:"\F00D1"}.mdi-delete-circle::before{content:"\F682"}.mdi-delete-circle-outline::before{content:"\FB64"}.mdi-delete-empty::before{content:"\F6CB"}.mdi-delete-empty-outline::before{content:"\FEBA"}.mdi-delete-forever::before{content:"\F5E8"}.mdi-delete-forever-outline::before{content:"\FB65"}.mdi-delete-off::before{content:"\F00D2"}.mdi-delete-off-outline::before{content:"\F00D3"}.mdi-delete-outline::before{content:"\F9E6"}.mdi-delete-restore::before{content:"\F818"}.mdi-delete-sweep::before{content:"\F5E9"}.mdi-delete-sweep-outline::before{content:"\FC3E"}.mdi-delete-variant::before{content:"\F1C1"}.mdi-delta::before{content:"\F1C2"}.mdi-desk-lamp::before{content:"\F95E"}.mdi-deskphone::before{content:"\F1C3"}.mdi-desktop-classic::before{content:"\F7BF"}.mdi-desktop-mac::before{content:"\F1C4"}.mdi-desktop-mac-dashboard::before{content:"\F9E7"}.mdi-desktop-tower::before{content:"\F1C5"}.mdi-desktop-tower-monitor::before{content:"\FAAA"}.mdi-details::before{content:"\F1C6"}.mdi-dev-to::before{content:"\FD4A"}.mdi-developer-board::before{content:"\F696"}.mdi-deviantart::before{content:"\F1C7"}.mdi-devices::before{content:"\FFD0"}.mdi-diabetes::before{content:"\F0151"}.mdi-dialpad::before{content:"\F61C"}.mdi-diameter::before{content:"\FC3F"}.mdi-diameter-outline::before{content:"\FC40"}.mdi-diameter-variant::before{content:"\FC41"}.mdi-diamond::before{content:"\FB66"}.mdi-diamond-outline::before{content:"\FB67"}.mdi-diamond-stone::before{content:"\F1C8"}.mdi-dice-1::before{content:"\F1CA"}.mdi-dice-1-outline::before{content:"\F0175"}.mdi-dice-2::before{content:"\F1CB"}.mdi-dice-2-outline::before{content:"\F0176"}.mdi-dice-3::before{content:"\F1CC"}.mdi-dice-3-outline::before{content:"\F0177"}.mdi-dice-4::before{content:"\F1CD"}.mdi-dice-4-outline::before{content:"\F0178"}.mdi-dice-5::before{content:"\F1CE"}.mdi-dice-5-outline::before{content:"\F0179"}.mdi-dice-6::before{content:"\F1CF"}.mdi-dice-6-outline::before{content:"\F017A"}.mdi-dice-d10::before{content:"\F017E"}.mdi-dice-d10-outline::before{content:"\F76E"}.mdi-dice-d12::before{content:"\F017F"}.mdi-dice-d12-outline::before{content:"\F866"}.mdi-dice-d20::before{content:"\F0180"}.mdi-dice-d20-outline::before{content:"\F5EA"}.mdi-dice-d4::before{content:"\F017B"}.mdi-dice-d4-outline::before{content:"\F5EB"}.mdi-dice-d6::before{content:"\F017C"}.mdi-dice-d6-outline::before{content:"\F5EC"}.mdi-dice-d8::before{content:"\F017D"}.mdi-dice-d8-outline::before{content:"\F5ED"}.mdi-dice-multiple::before{content:"\F76D"}.mdi-dice-multiple-outline::before{content:"\F0181"}.mdi-dictionary::before{content:"\F61D"}.mdi-dip-switch::before{content:"\F7C0"}.mdi-directions::before{content:"\F1D0"}.mdi-directions-fork::before{content:"\F641"}.mdi-disc::before{content:"\F5EE"}.mdi-disc-alert::before{content:"\F1D1"}.mdi-disc-player::before{content:"\F95F"}.mdi-discord::before{content:"\F66F"}.mdi-dishwasher::before{content:"\FAAB"}.mdi-disqus::before{content:"\F1D2"}.mdi-disqus-outline::before{content:"\F1D3"}.mdi-diving-flippers::before{content:"\FD9B"}.mdi-diving-helmet::before{content:"\FD9C"}.mdi-diving-scuba::before{content:"\FD9D"}.mdi-diving-scuba-flag::before{content:"\FD9E"}.mdi-diving-scuba-tank::before{content:"\FD9F"}.mdi-diving-scuba-tank-multiple::before{content:"\FDA0"}.mdi-diving-snorkel::before{content:"\FDA1"}.mdi-division::before{content:"\F1D4"}.mdi-division-box::before{content:"\F1D5"}.mdi-dlna::before{content:"\FA40"}.mdi-dna::before{content:"\F683"}.mdi-dns::before{content:"\F1D6"}.mdi-dns-outline::before{content:"\FB68"}.mdi-do-not-disturb::before{content:"\F697"}.mdi-do-not-disturb-off::before{content:"\F698"}.mdi-dock-bottom::before{content:"\F00D4"}.mdi-dock-left::before{content:"\F00D5"}.mdi-dock-right::before{content:"\F00D6"}.mdi-dock-window::before{content:"\F00D7"}.mdi-docker::before{content:"\F867"}.mdi-doctor::before{content:"\FA41"}.mdi-dog::before{content:"\FA42"}.mdi-dog-service::before{content:"\FAAC"}.mdi-dog-side::before{content:"\FA43"}.mdi-dolby::before{content:"\F6B2"}.mdi-dolly::before{content:"\FEBB"}.mdi-domain::before{content:"\F1D7"}.mdi-domain-off::before{content:"\FD4B"}.mdi-domain-plus::before{content:"\F00D8"}.mdi-domain-remove::before{content:"\F00D9"}.mdi-domino-mask::before{content:"\F0045"}.mdi-donkey::before{content:"\F7C1"}.mdi-door::before{content:"\F819"}.mdi-door-closed::before{content:"\F81A"}.mdi-door-closed-lock::before{content:"\F00DA"}.mdi-door-open::before{content:"\F81B"}.mdi-doorbell-video::before{content:"\F868"}.mdi-dot-net::before{content:"\FAAD"}.mdi-dots-horizontal::before{content:"\F1D8"}.mdi-dots-horizontal-circle::before{content:"\F7C2"}.mdi-dots-horizontal-circle-outline::before{content:"\FB69"}.mdi-dots-vertical::before{content:"\F1D9"}.mdi-dots-vertical-circle::before{content:"\F7C3"}.mdi-dots-vertical-circle-outline::before{content:"\FB6A"}.mdi-douban::before{content:"\F699"}.mdi-download::before{content:"\F1DA"}.mdi-download-multiple::before{content:"\F9E8"}.mdi-download-network::before{content:"\F6F3"}.mdi-download-network-outline::before{content:"\FC42"}.mdi-download-off::before{content:"\F00DB"}.mdi-download-off-outline::before{content:"\F00DC"}.mdi-download-outline::before{content:"\FB6B"}.mdi-drag::before{content:"\F1DB"}.mdi-drag-horizontal::before{content:"\F1DC"}.mdi-drag-variant::before{content:"\FB6C"}.mdi-drag-vertical::before{content:"\F1DD"}.mdi-drama-masks::before{content:"\FCDE"}.mdi-draw::before{content:"\FF66"}.mdi-drawing::before{content:"\F1DE"}.mdi-drawing-box::before{content:"\F1DF"}.mdi-dresser::before{content:"\FF67"}.mdi-dresser-outline::before{content:"\FF68"}.mdi-dribbble::before{content:"\F1E0"}.mdi-dribbble-box::before{content:"\F1E1"}.mdi-drone::before{content:"\F1E2"}.mdi-dropbox::before{content:"\F1E3"}.mdi-drupal::before{content:"\F1E4"}.mdi-duck::before{content:"\F1E5"}.mdi-dumbbell::before{content:"\F1E6"}.mdi-dump-truck::before{content:"\FC43"}.mdi-ear-hearing::before{content:"\F7C4"}.mdi-ear-hearing-off::before{content:"\FA44"}.mdi-earth::before{content:"\F1E7"}.mdi-earth-box::before{content:"\F6CC"}.mdi-earth-box-off::before{content:"\F6CD"}.mdi-earth-off::before{content:"\F1E8"}.mdi-edge::before{content:"\F1E9"}.mdi-egg::before{content:"\FAAE"}.mdi-egg-easter::before{content:"\FAAF"}.mdi-eight-track::before{content:"\F9E9"}.mdi-eject::before{content:"\F1EA"}.mdi-eject-outline::before{content:"\FB6D"}.mdi-electric-switch::before{content:"\FEBC"}.mdi-electric-switch-closed::before{content:"\F0104"}.mdi-electron-framework::before{content:"\F0046"}.mdi-elephant::before{content:"\F7C5"}.mdi-elevation-decline::before{content:"\F1EB"}.mdi-elevation-rise::before{content:"\F1EC"}.mdi-elevator::before{content:"\F1ED"}.mdi-ellipse::before{content:"\FEBD"}.mdi-ellipse-outline::before{content:"\FEBE"}.mdi-email::before{content:"\F1EE"}.mdi-email-alert::before{content:"\F6CE"}.mdi-email-box::before{content:"\FCDF"}.mdi-email-check::before{content:"\FAB0"}.mdi-email-check-outline::before{content:"\FAB1"}.mdi-email-edit::before{content:"\FF00"}.mdi-email-edit-outline::before{content:"\FF01"}.mdi-email-lock::before{content:"\F1F1"}.mdi-email-mark-as-unread::before{content:"\FB6E"}.mdi-email-minus::before{content:"\FF02"}.mdi-email-minus-outline::before{content:"\FF03"}.mdi-email-multiple::before{content:"\FF04"}.mdi-email-multiple-outline::before{content:"\FF05"}.mdi-email-newsletter::before{content:"\FFD1"}.mdi-email-open::before{content:"\F1EF"}.mdi-email-open-multiple::before{content:"\FF06"}.mdi-email-open-multiple-outline::before{content:"\FF07"}.mdi-email-open-outline::before{content:"\F5EF"}.mdi-email-outline::before{content:"\F1F0"}.mdi-email-plus::before{content:"\F9EA"}.mdi-email-plus-outline::before{content:"\F9EB"}.mdi-email-receive::before{content:"\F0105"}.mdi-email-receive-outline::before{content:"\F0106"}.mdi-email-search::before{content:"\F960"}.mdi-email-search-outline::before{content:"\F961"}.mdi-email-send::before{content:"\F0107"}.mdi-email-send-outline::before{content:"\F0108"}.mdi-email-variant::before{content:"\F5F0"}.mdi-ember::before{content:"\FB15"}.mdi-emby::before{content:"\F6B3"}.mdi-emoticon::before{content:"\FC44"}.mdi-emoticon-angry::before{content:"\FC45"}.mdi-emoticon-angry-outline::before{content:"\FC46"}.mdi-emoticon-confused::before{content:"\F0109"}.mdi-emoticon-confused-outline::before{content:"\F010A"}.mdi-emoticon-cool::before{content:"\FC47"}.mdi-emoticon-cool-outline::before{content:"\F1F3"}.mdi-emoticon-cry::before{content:"\FC48"}.mdi-emoticon-cry-outline::before{content:"\FC49"}.mdi-emoticon-dead::before{content:"\FC4A"}.mdi-emoticon-dead-outline::before{content:"\F69A"}.mdi-emoticon-devil::before{content:"\FC4B"}.mdi-emoticon-devil-outline::before{content:"\F1F4"}.mdi-emoticon-excited::before{content:"\FC4C"}.mdi-emoticon-excited-outline::before{content:"\F69B"}.mdi-emoticon-frown::before{content:"\FF69"}.mdi-emoticon-frown-outline::before{content:"\FF6A"}.mdi-emoticon-happy::before{content:"\FC4D"}.mdi-emoticon-happy-outline::before{content:"\F1F5"}.mdi-emoticon-kiss::before{content:"\FC4E"}.mdi-emoticon-kiss-outline::before{content:"\FC4F"}.mdi-emoticon-neutral::before{content:"\FC50"}.mdi-emoticon-neutral-outline::before{content:"\F1F6"}.mdi-emoticon-outline::before{content:"\F1F2"}.mdi-emoticon-poop::before{content:"\F1F7"}.mdi-emoticon-poop-outline::before{content:"\FC51"}.mdi-emoticon-sad::before{content:"\FC52"}.mdi-emoticon-sad-outline::before{content:"\F1F8"}.mdi-emoticon-tongue::before{content:"\F1F9"}.mdi-emoticon-tongue-outline::before{content:"\FC53"}.mdi-emoticon-wink::before{content:"\FC54"}.mdi-emoticon-wink-outline::before{content:"\FC55"}.mdi-engine::before{content:"\F1FA"}.mdi-engine-off::before{content:"\FA45"}.mdi-engine-off-outline::before{content:"\FA46"}.mdi-engine-outline::before{content:"\F1FB"}.mdi-epsilon::before{content:"\F010B"}.mdi-equal::before{content:"\F1FC"}.mdi-equal-box::before{content:"\F1FD"}.mdi-equalizer::before{content:"\FEBF"}.mdi-equalizer-outline::before{content:"\FEC0"}.mdi-eraser::before{content:"\F1FE"}.mdi-eraser-variant::before{content:"\F642"}.mdi-escalator::before{content:"\F1FF"}.mdi-eslint::before{content:"\FC56"}.mdi-et::before{content:"\FAB2"}.mdi-ethereum::before{content:"\F869"}.mdi-ethernet::before{content:"\F200"}.mdi-ethernet-cable::before{content:"\F201"}.mdi-ethernet-cable-off::before{content:"\F202"}.mdi-etsy::before{content:"\F203"}.mdi-ev-station::before{content:"\F5F1"}.mdi-eventbrite::before{content:"\F7C6"}.mdi-evernote::before{content:"\F204"}.mdi-excavator::before{content:"\F0047"}.mdi-exclamation::before{content:"\F205"}.mdi-exit-run::before{content:"\FA47"}.mdi-exit-to-app::before{content:"\F206"}.mdi-expand-all::before{content:"\FAB3"}.mdi-expand-all-outline::before{content:"\FAB4"}.mdi-expansion-card::before{content:"\F8AD"}.mdi-expansion-card-variant::before{content:"\FFD2"}.mdi-exponent::before{content:"\F962"}.mdi-exponent-box::before{content:"\F963"}.mdi-export::before{content:"\F207"}.mdi-export-variant::before{content:"\FB6F"}.mdi-eye::before{content:"\F208"}.mdi-eye-check::before{content:"\FCE0"}.mdi-eye-check-outline::before{content:"\FCE1"}.mdi-eye-circle::before{content:"\FB70"}.mdi-eye-circle-outline::before{content:"\FB71"}.mdi-eye-minus::before{content:"\F0048"}.mdi-eye-minus-outline::before{content:"\F0049"}.mdi-eye-off::before{content:"\F209"}.mdi-eye-off-outline::before{content:"\F6D0"}.mdi-eye-outline::before{content:"\F6CF"}.mdi-eye-plus::before{content:"\F86A"}.mdi-eye-plus-outline::before{content:"\F86B"}.mdi-eye-settings::before{content:"\F86C"}.mdi-eye-settings-outline::before{content:"\F86D"}.mdi-eyedropper::before{content:"\F20A"}.mdi-eyedropper-variant::before{content:"\F20B"}.mdi-face::before{content:"\F643"}.mdi-face-agent::before{content:"\FD4C"}.mdi-face-outline::before{content:"\FB72"}.mdi-face-profile::before{content:"\F644"}.mdi-face-profile-woman::before{content:"\F00A1"}.mdi-face-recognition::before{content:"\FC57"}.mdi-face-woman::before{content:"\F00A2"}.mdi-face-woman-outline::before{content:"\F00A3"}.mdi-facebook::before{content:"\F20C"}.mdi-facebook-box::before{content:"\F20D"}.mdi-facebook-messenger::before{content:"\F20E"}.mdi-facebook-workplace::before{content:"\FB16"}.mdi-factory::before{content:"\F20F"}.mdi-fan::before{content:"\F210"}.mdi-fan-off::before{content:"\F81C"}.mdi-fast-forward::before{content:"\F211"}.mdi-fast-forward-10::before{content:"\FD4D"}.mdi-fast-forward-30::before{content:"\FCE2"}.mdi-fast-forward-outline::before{content:"\F6D1"}.mdi-fax::before{content:"\F212"}.mdi-feather::before{content:"\F6D2"}.mdi-feature-search::before{content:"\FA48"}.mdi-feature-search-outline::before{content:"\FA49"}.mdi-fedora::before{content:"\F8DA"}.mdi-ferris-wheel::before{content:"\FEC1"}.mdi-ferry::before{content:"\F213"}.mdi-file::before{content:"\F214"}.mdi-file-account::before{content:"\F73A"}.mdi-file-account-outline::before{content:"\F004A"}.mdi-file-alert::before{content:"\FA4A"}.mdi-file-alert-outline::before{content:"\FA4B"}.mdi-file-cabinet::before{content:"\FAB5"}.mdi-file-cad::before{content:"\FF08"}.mdi-file-cad-box::before{content:"\FF09"}.mdi-file-cancel::before{content:"\FDA2"}.mdi-file-cancel-outline::before{content:"\FDA3"}.mdi-file-certificate::before{content:"\F01B1"}.mdi-file-certificate-outline::before{content:"\F01B2"}.mdi-file-chart::before{content:"\F215"}.mdi-file-chart-outline::before{content:"\F004B"}.mdi-file-check::before{content:"\F216"}.mdi-file-check-outline::before{content:"\FE7B"}.mdi-file-cloud::before{content:"\F217"}.mdi-file-cloud-outline::before{content:"\F004C"}.mdi-file-code::before{content:"\F22E"}.mdi-file-code-outline::before{content:"\F004D"}.mdi-file-compare::before{content:"\F8A9"}.mdi-file-delimited::before{content:"\F218"}.mdi-file-delimited-outline::before{content:"\FEC2"}.mdi-file-document::before{content:"\F219"}.mdi-file-document-box::before{content:"\F21A"}.mdi-file-document-box-check::before{content:"\FEC3"}.mdi-file-document-box-check-outline::before{content:"\FEC4"}.mdi-file-document-box-minus::before{content:"\FEC5"}.mdi-file-document-box-minus-outline::before{content:"\FEC6"}.mdi-file-document-box-multiple::before{content:"\FAB6"}.mdi-file-document-box-multiple-outline::before{content:"\FAB7"}.mdi-file-document-box-outline::before{content:"\F9EC"}.mdi-file-document-box-plus::before{content:"\FEC7"}.mdi-file-document-box-plus-outline::before{content:"\FEC8"}.mdi-file-document-box-remove::before{content:"\FEC9"}.mdi-file-document-box-remove-outline::before{content:"\FECA"}.mdi-file-document-box-search::before{content:"\FECB"}.mdi-file-document-box-search-outline::before{content:"\FECC"}.mdi-file-document-edit::before{content:"\FDA4"}.mdi-file-document-edit-outline::before{content:"\FDA5"}.mdi-file-document-outline::before{content:"\F9ED"}.mdi-file-download::before{content:"\F964"}.mdi-file-download-outline::before{content:"\F965"}.mdi-file-excel::before{content:"\F21B"}.mdi-file-excel-box::before{content:"\F21C"}.mdi-file-excel-box-outline::before{content:"\F004E"}.mdi-file-excel-outline::before{content:"\F004F"}.mdi-file-export::before{content:"\F21D"}.mdi-file-export-outline::before{content:"\F0050"}.mdi-file-eye::before{content:"\FDA6"}.mdi-file-eye-outline::before{content:"\FDA7"}.mdi-file-find::before{content:"\F21E"}.mdi-file-find-outline::before{content:"\FB73"}.mdi-file-hidden::before{content:"\F613"}.mdi-file-image::before{content:"\F21F"}.mdi-file-image-outline::before{content:"\FECD"}.mdi-file-import::before{content:"\F220"}.mdi-file-import-outline::before{content:"\F0051"}.mdi-file-key::before{content:"\F01AF"}.mdi-file-key-outline::before{content:"\F01B0"}.mdi-file-link::before{content:"\F01A2"}.mdi-file-link-outline::before{content:"\F01A3"}.mdi-file-lock::before{content:"\F221"}.mdi-file-lock-outline::before{content:"\F0052"}.mdi-file-move::before{content:"\FAB8"}.mdi-file-move-outline::before{content:"\F0053"}.mdi-file-multiple::before{content:"\F222"}.mdi-file-multiple-outline::before{content:"\F0054"}.mdi-file-music::before{content:"\F223"}.mdi-file-music-outline::before{content:"\FE7C"}.mdi-file-outline::before{content:"\F224"}.mdi-file-pdf::before{content:"\F225"}.mdi-file-pdf-box::before{content:"\F226"}.mdi-file-pdf-box-outline::before{content:"\FFD3"}.mdi-file-pdf-outline::before{content:"\FE7D"}.mdi-file-percent::before{content:"\F81D"}.mdi-file-percent-outline::before{content:"\F0055"}.mdi-file-phone::before{content:"\F01A4"}.mdi-file-phone-outline::before{content:"\F01A5"}.mdi-file-plus::before{content:"\F751"}.mdi-file-plus-outline::before{content:"\FF0A"}.mdi-file-powerpoint::before{content:"\F227"}.mdi-file-powerpoint-box::before{content:"\F228"}.mdi-file-powerpoint-box-outline::before{content:"\F0056"}.mdi-file-powerpoint-outline::before{content:"\F0057"}.mdi-file-presentation-box::before{content:"\F229"}.mdi-file-question::before{content:"\F86E"}.mdi-file-question-outline::before{content:"\F0058"}.mdi-file-remove::before{content:"\FB74"}.mdi-file-remove-outline::before{content:"\F0059"}.mdi-file-replace::before{content:"\FB17"}.mdi-file-replace-outline::before{content:"\FB18"}.mdi-file-restore::before{content:"\F670"}.mdi-file-restore-outline::before{content:"\F005A"}.mdi-file-search::before{content:"\FC58"}.mdi-file-search-outline::before{content:"\FC59"}.mdi-file-send::before{content:"\F22A"}.mdi-file-send-outline::before{content:"\F005B"}.mdi-file-settings::before{content:"\F00A4"}.mdi-file-settings-outline::before{content:"\F00A5"}.mdi-file-settings-variant::before{content:"\F00A6"}.mdi-file-settings-variant-outline::before{content:"\F00A7"}.mdi-file-star::before{content:"\F005C"}.mdi-file-star-outline::before{content:"\F005D"}.mdi-file-swap::before{content:"\FFD4"}.mdi-file-swap-outline::before{content:"\FFD5"}.mdi-file-table::before{content:"\FC5A"}.mdi-file-table-box::before{content:"\F010C"}.mdi-file-table-box-multiple::before{content:"\F010D"}.mdi-file-table-box-multiple-outline::before{content:"\F010E"}.mdi-file-table-box-outline::before{content:"\F010F"}.mdi-file-table-outline::before{content:"\FC5B"}.mdi-file-tree::before{content:"\F645"}.mdi-file-undo::before{content:"\F8DB"}.mdi-file-undo-outline::before{content:"\F005E"}.mdi-file-upload::before{content:"\FA4C"}.mdi-file-upload-outline::before{content:"\FA4D"}.mdi-file-video::before{content:"\F22B"}.mdi-file-video-outline::before{content:"\FE10"}.mdi-file-word::before{content:"\F22C"}.mdi-file-word-box::before{content:"\F22D"}.mdi-file-word-box-outline::before{content:"\F005F"}.mdi-file-word-outline::before{content:"\F0060"}.mdi-film::before{content:"\F22F"}.mdi-filmstrip::before{content:"\F230"}.mdi-filmstrip-off::before{content:"\F231"}.mdi-filter::before{content:"\F232"}.mdi-filter-menu::before{content:"\F0110"}.mdi-filter-menu-outline::before{content:"\F0111"}.mdi-filter-minus::before{content:"\FF0B"}.mdi-filter-minus-outline::before{content:"\FF0C"}.mdi-filter-outline::before{content:"\F233"}.mdi-filter-plus::before{content:"\FF0D"}.mdi-filter-plus-outline::before{content:"\FF0E"}.mdi-filter-remove::before{content:"\F234"}.mdi-filter-remove-outline::before{content:"\F235"}.mdi-filter-variant::before{content:"\F236"}.mdi-filter-variant-minus::before{content:"\F013D"}.mdi-filter-variant-plus::before{content:"\F013E"}.mdi-filter-variant-remove::before{content:"\F0061"}.mdi-finance::before{content:"\F81E"}.mdi-find-replace::before{content:"\F6D3"}.mdi-fingerprint::before{content:"\F237"}.mdi-fingerprint-off::before{content:"\FECE"}.mdi-fire::before{content:"\F238"}.mdi-fire-extinguisher::before{content:"\FF0F"}.mdi-fire-hydrant::before{content:"\F0162"}.mdi-fire-hydrant-alert::before{content:"\F0163"}.mdi-fire-hydrant-off::before{content:"\F0164"}.mdi-fire-truck::before{content:"\F8AA"}.mdi-firebase::before{content:"\F966"}.mdi-firefox::before{content:"\F239"}.mdi-fireplace::before{content:"\FE11"}.mdi-fireplace-off::before{content:"\FE12"}.mdi-firework::before{content:"\FE13"}.mdi-fish::before{content:"\F23A"}.mdi-fishbowl::before{content:"\FF10"}.mdi-fishbowl-outline::before{content:"\FF11"}.mdi-fit-to-page::before{content:"\FF12"}.mdi-fit-to-page-outline::before{content:"\FF13"}.mdi-flag::before{content:"\F23B"}.mdi-flag-checkered::before{content:"\F23C"}.mdi-flag-minus::before{content:"\FB75"}.mdi-flag-minus-outline::before{content:"\F00DD"}.mdi-flag-outline::before{content:"\F23D"}.mdi-flag-plus::before{content:"\FB76"}.mdi-flag-plus-outline::before{content:"\F00DE"}.mdi-flag-remove::before{content:"\FB77"}.mdi-flag-remove-outline::before{content:"\F00DF"}.mdi-flag-triangle::before{content:"\F23F"}.mdi-flag-variant::before{content:"\F240"}.mdi-flag-variant-outline::before{content:"\F23E"}.mdi-flare::before{content:"\FD4E"}.mdi-flash::before{content:"\F241"}.mdi-flash-alert::before{content:"\FF14"}.mdi-flash-alert-outline::before{content:"\FF15"}.mdi-flash-auto::before{content:"\F242"}.mdi-flash-circle::before{content:"\F81F"}.mdi-flash-off::before{content:"\F243"}.mdi-flash-outline::before{content:"\F6D4"}.mdi-flash-red-eye::before{content:"\F67A"}.mdi-flashlight::before{content:"\F244"}.mdi-flashlight-off::before{content:"\F245"}.mdi-flask::before{content:"\F093"}.mdi-flask-empty::before{content:"\F094"}.mdi-flask-empty-outline::before{content:"\F095"}.mdi-flask-outline::before{content:"\F096"}.mdi-flattr::before{content:"\F246"}.mdi-flickr::before{content:"\FCE3"}.mdi-flip-horizontal::before{content:"\F0112"}.mdi-flip-to-back::before{content:"\F247"}.mdi-flip-to-front::before{content:"\F248"}.mdi-flip-vertical::before{content:"\F0113"}.mdi-floor-lamp::before{content:"\F8DC"}.mdi-floor-lamp-dual::before{content:"\F0062"}.mdi-floor-lamp-variant::before{content:"\F0063"}.mdi-floor-plan::before{content:"\F820"}.mdi-floppy::before{content:"\F249"}.mdi-floppy-variant::before{content:"\F9EE"}.mdi-flower::before{content:"\F24A"}.mdi-flower-outline::before{content:"\F9EF"}.mdi-flower-poppy::before{content:"\FCE4"}.mdi-flower-tulip::before{content:"\F9F0"}.mdi-flower-tulip-outline::before{content:"\F9F1"}.mdi-focus-auto::before{content:"\FF6B"}.mdi-focus-field::before{content:"\FF6C"}.mdi-focus-field-horizontal::before{content:"\FF6D"}.mdi-focus-field-vertical::before{content:"\FF6E"}.mdi-folder::before{content:"\F24B"}.mdi-folder-account::before{content:"\F24C"}.mdi-folder-account-outline::before{content:"\FB78"}.mdi-folder-alert::before{content:"\FDA8"}.mdi-folder-alert-outline::before{content:"\FDA9"}.mdi-folder-clock::before{content:"\FAB9"}.mdi-folder-clock-outline::before{content:"\FABA"}.mdi-folder-download::before{content:"\F24D"}.mdi-folder-download-outline::before{content:"\F0114"}.mdi-folder-edit::before{content:"\F8DD"}.mdi-folder-edit-outline::before{content:"\FDAA"}.mdi-folder-google-drive::before{content:"\F24E"}.mdi-folder-heart::before{content:"\F0115"}.mdi-folder-heart-outline::before{content:"\F0116"}.mdi-folder-home::before{content:"\F00E0"}.mdi-folder-home-outline::before{content:"\F00E1"}.mdi-folder-image::before{content:"\F24F"}.mdi-folder-information::before{content:"\F00E2"}.mdi-folder-information-outline::before{content:"\F00E3"}.mdi-folder-key::before{content:"\F8AB"}.mdi-folder-key-network::before{content:"\F8AC"}.mdi-folder-key-network-outline::before{content:"\FC5C"}.mdi-folder-key-outline::before{content:"\F0117"}.mdi-folder-lock::before{content:"\F250"}.mdi-folder-lock-open::before{content:"\F251"}.mdi-folder-move::before{content:"\F252"}.mdi-folder-multiple::before{content:"\F253"}.mdi-folder-multiple-image::before{content:"\F254"}.mdi-folder-multiple-outline::before{content:"\F255"}.mdi-folder-network::before{content:"\F86F"}.mdi-folder-network-outline::before{content:"\FC5D"}.mdi-folder-open::before{content:"\F76F"}.mdi-folder-open-outline::before{content:"\FDAB"}.mdi-folder-outline::before{content:"\F256"}.mdi-folder-plus::before{content:"\F257"}.mdi-folder-plus-outline::before{content:"\FB79"}.mdi-folder-pound::before{content:"\FCE5"}.mdi-folder-pound-outline::before{content:"\FCE6"}.mdi-folder-remove::before{content:"\F258"}.mdi-folder-remove-outline::before{content:"\FB7A"}.mdi-folder-search::before{content:"\F967"}.mdi-folder-search-outline::before{content:"\F968"}.mdi-folder-settings::before{content:"\F00A8"}.mdi-folder-settings-outline::before{content:"\F00A9"}.mdi-folder-settings-variant::before{content:"\F00AA"}.mdi-folder-settings-variant-outline::before{content:"\F00AB"}.mdi-folder-star::before{content:"\F69C"}.mdi-folder-star-outline::before{content:"\FB7B"}.mdi-folder-swap::before{content:"\FFD6"}.mdi-folder-swap-outline::before{content:"\FFD7"}.mdi-folder-sync::before{content:"\FCE7"}.mdi-folder-sync-outline::before{content:"\FCE8"}.mdi-folder-text::before{content:"\FC5E"}.mdi-folder-text-outline::before{content:"\FC5F"}.mdi-folder-upload::before{content:"\F259"}.mdi-folder-upload-outline::before{content:"\F0118"}.mdi-folder-zip::before{content:"\F6EA"}.mdi-folder-zip-outline::before{content:"\F7B8"}.mdi-font-awesome::before{content:"\F03A"}.mdi-food::before{content:"\F25A"}.mdi-food-apple::before{content:"\F25B"}.mdi-food-apple-outline::before{content:"\FC60"}.mdi-food-croissant::before{content:"\F7C7"}.mdi-food-fork-drink::before{content:"\F5F2"}.mdi-food-off::before{content:"\F5F3"}.mdi-food-variant::before{content:"\F25C"}.mdi-foot-print::before{content:"\FF6F"}.mdi-football::before{content:"\F25D"}.mdi-football-australian::before{content:"\F25E"}.mdi-football-helmet::before{content:"\F25F"}.mdi-forklift::before{content:"\F7C8"}.mdi-format-align-bottom::before{content:"\F752"}.mdi-format-align-center::before{content:"\F260"}.mdi-format-align-justify::before{content:"\F261"}.mdi-format-align-left::before{content:"\F262"}.mdi-format-align-middle::before{content:"\F753"}.mdi-format-align-right::before{content:"\F263"}.mdi-format-align-top::before{content:"\F754"}.mdi-format-annotation-minus::before{content:"\FABB"}.mdi-format-annotation-plus::before{content:"\F646"}.mdi-format-bold::before{content:"\F264"}.mdi-format-clear::before{content:"\F265"}.mdi-format-color-fill::before{content:"\F266"}.mdi-format-color-highlight::before{content:"\FE14"}.mdi-format-color-text::before{content:"\F69D"}.mdi-format-columns::before{content:"\F8DE"}.mdi-format-float-center::before{content:"\F267"}.mdi-format-float-left::before{content:"\F268"}.mdi-format-float-none::before{content:"\F269"}.mdi-format-float-right::before{content:"\F26A"}.mdi-format-font::before{content:"\F6D5"}.mdi-format-font-size-decrease::before{content:"\F9F2"}.mdi-format-font-size-increase::before{content:"\F9F3"}.mdi-format-header-1::before{content:"\F26B"}.mdi-format-header-2::before{content:"\F26C"}.mdi-format-header-3::before{content:"\F26D"}.mdi-format-header-4::before{content:"\F26E"}.mdi-format-header-5::before{content:"\F26F"}.mdi-format-header-6::before{content:"\F270"}.mdi-format-header-decrease::before{content:"\F271"}.mdi-format-header-equal::before{content:"\F272"}.mdi-format-header-increase::before{content:"\F273"}.mdi-format-header-pound::before{content:"\F274"}.mdi-format-horizontal-align-center::before{content:"\F61E"}.mdi-format-horizontal-align-left::before{content:"\F61F"}.mdi-format-horizontal-align-right::before{content:"\F620"}.mdi-format-indent-decrease::before{content:"\F275"}.mdi-format-indent-increase::before{content:"\F276"}.mdi-format-italic::before{content:"\F277"}.mdi-format-letter-case::before{content:"\FB19"}.mdi-format-letter-case-lower::before{content:"\FB1A"}.mdi-format-letter-case-upper::before{content:"\FB1B"}.mdi-format-letter-ends-with::before{content:"\FFD8"}.mdi-format-letter-matches::before{content:"\FFD9"}.mdi-format-letter-starts-with::before{content:"\FFDA"}.mdi-format-line-spacing::before{content:"\F278"}.mdi-format-line-style::before{content:"\F5C8"}.mdi-format-line-weight::before{content:"\F5C9"}.mdi-format-list-bulleted::before{content:"\F279"}.mdi-format-list-bulleted-square::before{content:"\FDAC"}.mdi-format-list-bulleted-triangle::before{content:"\FECF"}.mdi-format-list-bulleted-type::before{content:"\F27A"}.mdi-format-list-checkbox::before{content:"\F969"}.mdi-format-list-checks::before{content:"\F755"}.mdi-format-list-numbered::before{content:"\F27B"}.mdi-format-list-numbered-rtl::before{content:"\FCE9"}.mdi-format-overline::before{content:"\FED0"}.mdi-format-page-break::before{content:"\F6D6"}.mdi-format-paint::before{content:"\F27C"}.mdi-format-paragraph::before{content:"\F27D"}.mdi-format-pilcrow::before{content:"\F6D7"}.mdi-format-quote-close::before{content:"\F27E"}.mdi-format-quote-open::before{content:"\F756"}.mdi-format-rotate-90::before{content:"\F6A9"}.mdi-format-section::before{content:"\F69E"}.mdi-format-size::before{content:"\F27F"}.mdi-format-strikethrough::before{content:"\F280"}.mdi-format-strikethrough-variant::before{content:"\F281"}.mdi-format-subscript::before{content:"\F282"}.mdi-format-superscript::before{content:"\F283"}.mdi-format-text::before{content:"\F284"}.mdi-format-text-rotation-angle-down::before{content:"\FFDB"}.mdi-format-text-rotation-angle-up::before{content:"\FFDC"}.mdi-format-text-rotation-down::before{content:"\FD4F"}.mdi-format-text-rotation-down-vertical::before{content:"\FFDD"}.mdi-format-text-rotation-none::before{content:"\FD50"}.mdi-format-text-rotation-up::before{content:"\FFDE"}.mdi-format-text-rotation-vertical::before{content:"\FFDF"}.mdi-format-text-variant::before{content:"\FE15"}.mdi-format-text-wrapping-clip::before{content:"\FCEA"}.mdi-format-text-wrapping-overflow::before{content:"\FCEB"}.mdi-format-text-wrapping-wrap::before{content:"\FCEC"}.mdi-format-textbox::before{content:"\FCED"}.mdi-format-textdirection-l-to-r::before{content:"\F285"}.mdi-format-textdirection-r-to-l::before{content:"\F286"}.mdi-format-title::before{content:"\F5F4"}.mdi-format-underline::before{content:"\F287"}.mdi-format-vertical-align-bottom::before{content:"\F621"}.mdi-format-vertical-align-center::before{content:"\F622"}.mdi-format-vertical-align-top::before{content:"\F623"}.mdi-format-wrap-inline::before{content:"\F288"}.mdi-format-wrap-square::before{content:"\F289"}.mdi-format-wrap-tight::before{content:"\F28A"}.mdi-format-wrap-top-bottom::before{content:"\F28B"}.mdi-forum::before{content:"\F28C"}.mdi-forum-outline::before{content:"\F821"}.mdi-forward::before{content:"\F28D"}.mdi-forwardburger::before{content:"\FD51"}.mdi-fountain::before{content:"\F96A"}.mdi-fountain-pen::before{content:"\FCEE"}.mdi-fountain-pen-tip::before{content:"\FCEF"}.mdi-foursquare::before{content:"\F28E"}.mdi-freebsd::before{content:"\F8DF"}.mdi-frequently-asked-questions::before{content:"\FED1"}.mdi-fridge::before{content:"\F290"}.mdi-fridge-bottom::before{content:"\F292"}.mdi-fridge-outline::before{content:"\F28F"}.mdi-fridge-top::before{content:"\F291"}.mdi-fruit-cherries::before{content:"\F0064"}.mdi-fruit-citrus::before{content:"\F0065"}.mdi-fruit-grapes::before{content:"\F0066"}.mdi-fruit-grapes-outline::before{content:"\F0067"}.mdi-fruit-pineapple::before{content:"\F0068"}.mdi-fruit-watermelon::before{content:"\F0069"}.mdi-fuel::before{content:"\F7C9"}.mdi-fullscreen::before{content:"\F293"}.mdi-fullscreen-exit::before{content:"\F294"}.mdi-function::before{content:"\F295"}.mdi-function-variant::before{content:"\F870"}.mdi-furigana-horizontal::before{content:"\F00AC"}.mdi-furigana-vertical::before{content:"\F00AD"}.mdi-fuse::before{content:"\FC61"}.mdi-fuse-blade::before{content:"\FC62"}.mdi-gamepad::before{content:"\F296"}.mdi-gamepad-circle::before{content:"\FE16"}.mdi-gamepad-circle-down::before{content:"\FE17"}.mdi-gamepad-circle-left::before{content:"\FE18"}.mdi-gamepad-circle-outline::before{content:"\FE19"}.mdi-gamepad-circle-right::before{content:"\FE1A"}.mdi-gamepad-circle-up::before{content:"\FE1B"}.mdi-gamepad-down::before{content:"\FE1C"}.mdi-gamepad-left::before{content:"\FE1D"}.mdi-gamepad-right::before{content:"\FE1E"}.mdi-gamepad-round::before{content:"\FE1F"}.mdi-gamepad-round-down::before{content:"\FE7E"}.mdi-gamepad-round-left::before{content:"\FE7F"}.mdi-gamepad-round-outline::before{content:"\FE80"}.mdi-gamepad-round-right::before{content:"\FE81"}.mdi-gamepad-round-up::before{content:"\FE82"}.mdi-gamepad-square::before{content:"\FED2"}.mdi-gamepad-square-outline::before{content:"\FED3"}.mdi-gamepad-up::before{content:"\FE83"}.mdi-gamepad-variant::before{content:"\F297"}.mdi-gamepad-variant-outline::before{content:"\FED4"}.mdi-gamma::before{content:"\F0119"}.mdi-gantry-crane::before{content:"\FDAD"}.mdi-garage::before{content:"\F6D8"}.mdi-garage-alert::before{content:"\F871"}.mdi-garage-open::before{content:"\F6D9"}.mdi-gas-cylinder::before{content:"\F647"}.mdi-gas-station::before{content:"\F298"}.mdi-gas-station-outline::before{content:"\FED5"}.mdi-gate::before{content:"\F299"}.mdi-gate-and::before{content:"\F8E0"}.mdi-gate-arrow-right::before{content:"\F0194"}.mdi-gate-nand::before{content:"\F8E1"}.mdi-gate-nor::before{content:"\F8E2"}.mdi-gate-not::before{content:"\F8E3"}.mdi-gate-open::before{content:"\F0195"}.mdi-gate-or::before{content:"\F8E4"}.mdi-gate-xnor::before{content:"\F8E5"}.mdi-gate-xor::before{content:"\F8E6"}.mdi-gatsby::before{content:"\FE84"}.mdi-gauge::before{content:"\F29A"}.mdi-gauge-empty::before{content:"\F872"}.mdi-gauge-full::before{content:"\F873"}.mdi-gauge-low::before{content:"\F874"}.mdi-gavel::before{content:"\F29B"}.mdi-gender-female::before{content:"\F29C"}.mdi-gender-male::before{content:"\F29D"}.mdi-gender-male-female::before{content:"\F29E"}.mdi-gender-male-female-variant::before{content:"\F016A"}.mdi-gender-non-binary::before{content:"\F016B"}.mdi-gender-transgender::before{content:"\F29F"}.mdi-gentoo::before{content:"\F8E7"}.mdi-gesture::before{content:"\F7CA"}.mdi-gesture-double-tap::before{content:"\F73B"}.mdi-gesture-pinch::before{content:"\FABC"}.mdi-gesture-spread::before{content:"\FABD"}.mdi-gesture-swipe::before{content:"\FD52"}.mdi-gesture-swipe-down::before{content:"\F73C"}.mdi-gesture-swipe-horizontal::before{content:"\FABE"}.mdi-gesture-swipe-left::before{content:"\F73D"}.mdi-gesture-swipe-right::before{content:"\F73E"}.mdi-gesture-swipe-up::before{content:"\F73F"}.mdi-gesture-swipe-vertical::before{content:"\FABF"}.mdi-gesture-tap::before{content:"\F740"}.mdi-gesture-tap-hold::before{content:"\FD53"}.mdi-gesture-two-double-tap::before{content:"\F741"}.mdi-gesture-two-tap::before{content:"\F742"}.mdi-ghost::before{content:"\F2A0"}.mdi-ghost-off::before{content:"\F9F4"}.mdi-gif::before{content:"\FD54"}.mdi-gift::before{content:"\FE85"}.mdi-gift-outline::before{content:"\F2A1"}.mdi-git::before{content:"\F2A2"}.mdi-github-box::before{content:"\F2A3"}.mdi-github-circle::before{content:"\F2A4"}.mdi-github-face::before{content:"\F6DA"}.mdi-gitlab::before{content:"\FB7C"}.mdi-glass-cocktail::before{content:"\F356"}.mdi-glass-flute::before{content:"\F2A5"}.mdi-glass-mug::before{content:"\F2A6"}.mdi-glass-mug-variant::before{content:"\F0141"}.mdi-glass-stange::before{content:"\F2A7"}.mdi-glass-tulip::before{content:"\F2A8"}.mdi-glass-wine::before{content:"\F875"}.mdi-glassdoor::before{content:"\F2A9"}.mdi-glasses::before{content:"\F2AA"}.mdi-globe-model::before{content:"\F8E8"}.mdi-gmail::before{content:"\F2AB"}.mdi-gnome::before{content:"\F2AC"}.mdi-go-kart::before{content:"\FD55"}.mdi-go-kart-track::before{content:"\FD56"}.mdi-gog::before{content:"\FB7D"}.mdi-golf::before{content:"\F822"}.mdi-golf-tee::before{content:"\F00AE"}.mdi-gondola::before{content:"\F685"}.mdi-goodreads::before{content:"\FD57"}.mdi-google::before{content:"\F2AD"}.mdi-google-adwords::before{content:"\FC63"}.mdi-google-analytics::before{content:"\F7CB"}.mdi-google-assistant::before{content:"\F7CC"}.mdi-google-cardboard::before{content:"\F2AE"}.mdi-google-chrome::before{content:"\F2AF"}.mdi-google-circles::before{content:"\F2B0"}.mdi-google-circles-communities::before{content:"\F2B1"}.mdi-google-circles-extended::before{content:"\F2B2"}.mdi-google-circles-group::before{content:"\F2B3"}.mdi-google-classroom::before{content:"\F2C0"}.mdi-google-controller::before{content:"\F2B4"}.mdi-google-controller-off::before{content:"\F2B5"}.mdi-google-drive::before{content:"\F2B6"}.mdi-google-earth::before{content:"\F2B7"}.mdi-google-fit::before{content:"\F96B"}.mdi-google-glass::before{content:"\F2B8"}.mdi-google-hangouts::before{content:"\F2C9"}.mdi-google-home::before{content:"\F823"}.mdi-google-keep::before{content:"\F6DB"}.mdi-google-lens::before{content:"\F9F5"}.mdi-google-maps::before{content:"\F5F5"}.mdi-google-my-business::before{content:"\F006A"}.mdi-google-nearby::before{content:"\F2B9"}.mdi-google-pages::before{content:"\F2BA"}.mdi-google-photos::before{content:"\F6DC"}.mdi-google-physical-web::before{content:"\F2BB"}.mdi-google-play::before{content:"\F2BC"}.mdi-google-plus::before{content:"\F2BD"}.mdi-google-plus-box::before{content:"\F2BE"}.mdi-google-podcast::before{content:"\FED6"}.mdi-google-spreadsheet::before{content:"\F9F6"}.mdi-google-street-view::before{content:"\FC64"}.mdi-google-translate::before{content:"\F2BF"}.mdi-gradient::before{content:"\F69F"}.mdi-grain::before{content:"\FD58"}.mdi-graph::before{content:"\F006B"}.mdi-graph-outline::before{content:"\F006C"}.mdi-graphql::before{content:"\F876"}.mdi-grave-stone::before{content:"\FB7E"}.mdi-grease-pencil::before{content:"\F648"}.mdi-greater-than::before{content:"\F96C"}.mdi-greater-than-or-equal::before{content:"\F96D"}.mdi-grid::before{content:"\F2C1"}.mdi-grid-large::before{content:"\F757"}.mdi-grid-off::before{content:"\F2C2"}.mdi-grill::before{content:"\FE86"}.mdi-grill-outline::before{content:"\F01B5"}.mdi-group::before{content:"\F2C3"}.mdi-guitar-acoustic::before{content:"\F770"}.mdi-guitar-electric::before{content:"\F2C4"}.mdi-guitar-pick::before{content:"\F2C5"}.mdi-guitar-pick-outline::before{content:"\F2C6"}.mdi-guy-fawkes-mask::before{content:"\F824"}.mdi-hackernews::before{content:"\F624"}.mdi-hail::before{content:"\FAC0"}.mdi-hair-dryer::before{content:"\F011A"}.mdi-hair-dryer-outline::before{content:"\F011B"}.mdi-halloween::before{content:"\FB7F"}.mdi-hamburger::before{content:"\F684"}.mdi-hammer::before{content:"\F8E9"}.mdi-hand::before{content:"\FA4E"}.mdi-hand-heart::before{content:"\F011C"}.mdi-hand-left::before{content:"\FE87"}.mdi-hand-okay::before{content:"\FA4F"}.mdi-hand-peace::before{content:"\FA50"}.mdi-hand-peace-variant::before{content:"\FA51"}.mdi-hand-pointing-down::before{content:"\FA52"}.mdi-hand-pointing-left::before{content:"\FA53"}.mdi-hand-pointing-right::before{content:"\F2C7"}.mdi-hand-pointing-up::before{content:"\FA54"}.mdi-hand-right::before{content:"\FE88"}.mdi-hand-saw::before{content:"\FE89"}.mdi-handball::before{content:"\FF70"}.mdi-handcuffs::before{content:"\F0169"}.mdi-hanger::before{content:"\F2C8"}.mdi-hard-hat::before{content:"\F96E"}.mdi-harddisk::before{content:"\F2CA"}.mdi-harddisk-plus::before{content:"\F006D"}.mdi-harddisk-remove::before{content:"\F006E"}.mdi-hat-fedora::before{content:"\FB80"}.mdi-hazard-lights::before{content:"\FC65"}.mdi-hdr::before{content:"\FD59"}.mdi-hdr-off::before{content:"\FD5A"}.mdi-headphones::before{content:"\F2CB"}.mdi-headphones-bluetooth::before{content:"\F96F"}.mdi-headphones-box::before{content:"\F2CC"}.mdi-headphones-off::before{content:"\F7CD"}.mdi-headphones-settings::before{content:"\F2CD"}.mdi-headset::before{content:"\F2CE"}.mdi-headset-dock::before{content:"\F2CF"}.mdi-headset-off::before{content:"\F2D0"}.mdi-heart::before{content:"\F2D1"}.mdi-heart-box::before{content:"\F2D2"}.mdi-heart-box-outline::before{content:"\F2D3"}.mdi-heart-broken::before{content:"\F2D4"}.mdi-heart-broken-outline::before{content:"\FCF0"}.mdi-heart-circle::before{content:"\F970"}.mdi-heart-circle-outline::before{content:"\F971"}.mdi-heart-flash::before{content:"\FF16"}.mdi-heart-half::before{content:"\F6DE"}.mdi-heart-half-full::before{content:"\F6DD"}.mdi-heart-half-outline::before{content:"\F6DF"}.mdi-heart-multiple::before{content:"\FA55"}.mdi-heart-multiple-outline::before{content:"\FA56"}.mdi-heart-off::before{content:"\F758"}.mdi-heart-outline::before{content:"\F2D5"}.mdi-heart-pulse::before{content:"\F5F6"}.mdi-helicopter::before{content:"\FAC1"}.mdi-help::before{content:"\F2D6"}.mdi-help-box::before{content:"\F78A"}.mdi-help-circle::before{content:"\F2D7"}.mdi-help-circle-outline::before{content:"\F625"}.mdi-help-network::before{content:"\F6F4"}.mdi-help-network-outline::before{content:"\FC66"}.mdi-help-rhombus::before{content:"\FB81"}.mdi-help-rhombus-outline::before{content:"\FB82"}.mdi-hexagon::before{content:"\F2D8"}.mdi-hexagon-multiple::before{content:"\F6E0"}.mdi-hexagon-multiple-outline::before{content:"\F011D"}.mdi-hexagon-outline::before{content:"\F2D9"}.mdi-hexagon-slice-1::before{content:"\FAC2"}.mdi-hexagon-slice-2::before{content:"\FAC3"}.mdi-hexagon-slice-3::before{content:"\FAC4"}.mdi-hexagon-slice-4::before{content:"\FAC5"}.mdi-hexagon-slice-5::before{content:"\FAC6"}.mdi-hexagon-slice-6::before{content:"\FAC7"}.mdi-hexagram::before{content:"\FAC8"}.mdi-hexagram-outline::before{content:"\FAC9"}.mdi-high-definition::before{content:"\F7CE"}.mdi-high-definition-box::before{content:"\F877"}.mdi-highway::before{content:"\F5F7"}.mdi-hiking::before{content:"\FD5B"}.mdi-hinduism::before{content:"\F972"}.mdi-history::before{content:"\F2DA"}.mdi-hockey-puck::before{content:"\F878"}.mdi-hockey-sticks::before{content:"\F879"}.mdi-hololens::before{content:"\F2DB"}.mdi-home::before{content:"\F2DC"}.mdi-home-account::before{content:"\F825"}.mdi-home-alert::before{content:"\F87A"}.mdi-home-analytics::before{content:"\FED7"}.mdi-home-assistant::before{content:"\F7CF"}.mdi-home-automation::before{content:"\F7D0"}.mdi-home-circle::before{content:"\F7D1"}.mdi-home-circle-outline::before{content:"\F006F"}.mdi-home-city::before{content:"\FCF1"}.mdi-home-city-outline::before{content:"\FCF2"}.mdi-home-currency-usd::before{content:"\F8AE"}.mdi-home-edit::before{content:"\F0184"}.mdi-home-edit-outline::before{content:"\F0185"}.mdi-home-export-outline::before{content:"\FFB8"}.mdi-home-flood::before{content:"\FF17"}.mdi-home-floor-0::before{content:"\FDAE"}.mdi-home-floor-1::before{content:"\FD5C"}.mdi-home-floor-2::before{content:"\FD5D"}.mdi-home-floor-3::before{content:"\FD5E"}.mdi-home-floor-a::before{content:"\FD5F"}.mdi-home-floor-b::before{content:"\FD60"}.mdi-home-floor-g::before{content:"\FD61"}.mdi-home-floor-l::before{content:"\FD62"}.mdi-home-floor-negative-1::before{content:"\FDAF"}.mdi-home-group::before{content:"\FDB0"}.mdi-home-heart::before{content:"\F826"}.mdi-home-import-outline::before{content:"\FFB9"}.mdi-home-lock::before{content:"\F8EA"}.mdi-home-lock-open::before{content:"\F8EB"}.mdi-home-map-marker::before{content:"\F5F8"}.mdi-home-minus::before{content:"\F973"}.mdi-home-modern::before{content:"\F2DD"}.mdi-home-outline::before{content:"\F6A0"}.mdi-home-plus::before{content:"\F974"}.mdi-home-roof::before{content:"\F0156"}.mdi-home-thermometer::before{content:"\FF71"}.mdi-home-thermometer-outline::before{content:"\FF72"}.mdi-home-variant::before{content:"\F2DE"}.mdi-home-variant-outline::before{content:"\FB83"}.mdi-hook::before{content:"\F6E1"}.mdi-hook-off::before{content:"\F6E2"}.mdi-hops::before{content:"\F2DF"}.mdi-horizontal-rotate-clockwise::before{content:"\F011E"}.mdi-horizontal-rotate-counterclockwise::before{content:"\F011F"}.mdi-horseshoe::before{content:"\FA57"}.mdi-hospital::before{content:"\F0017"}.mdi-hospital-box::before{content:"\F2E0"}.mdi-hospital-box-outline::before{content:"\F0018"}.mdi-hospital-building::before{content:"\F2E1"}.mdi-hospital-marker::before{content:"\F2E2"}.mdi-hot-tub::before{content:"\F827"}.mdi-hotel::before{content:"\F2E3"}.mdi-houzz::before{content:"\F2E4"}.mdi-houzz-box::before{content:"\F2E5"}.mdi-hubspot::before{content:"\FCF3"}.mdi-hulu::before{content:"\F828"}.mdi-human::before{content:"\F2E6"}.mdi-human-child::before{content:"\F2E7"}.mdi-human-female::before{content:"\F649"}.mdi-human-female-boy::before{content:"\FA58"}.mdi-human-female-female::before{content:"\FA59"}.mdi-human-female-girl::before{content:"\FA5A"}.mdi-human-greeting::before{content:"\F64A"}.mdi-human-handsdown::before{content:"\F64B"}.mdi-human-handsup::before{content:"\F64C"}.mdi-human-male::before{content:"\F64D"}.mdi-human-male-boy::before{content:"\FA5B"}.mdi-human-male-female::before{content:"\F2E8"}.mdi-human-male-girl::before{content:"\FA5C"}.mdi-human-male-height::before{content:"\FF18"}.mdi-human-male-height-variant::before{content:"\FF19"}.mdi-human-male-male::before{content:"\FA5D"}.mdi-human-pregnant::before{content:"\F5CF"}.mdi-humble-bundle::before{content:"\F743"}.mdi-ice-cream::before{content:"\F829"}.mdi-ice-pop::before{content:"\FF1A"}.mdi-id-card::before{content:"\FFE0"}.mdi-identifier::before{content:"\FF1B"}.mdi-iframe::before{content:"\FC67"}.mdi-iframe-array::before{content:"\F0120"}.mdi-iframe-array-outline::before{content:"\F0121"}.mdi-iframe-braces::before{content:"\F0122"}.mdi-iframe-braces-outline::before{content:"\F0123"}.mdi-iframe-outline::before{content:"\FC68"}.mdi-iframe-parentheses::before{content:"\F0124"}.mdi-iframe-parentheses-outline::before{content:"\F0125"}.mdi-iframe-variable::before{content:"\F0126"}.mdi-iframe-variable-outline::before{content:"\F0127"}.mdi-image::before{content:"\F2E9"}.mdi-image-album::before{content:"\F2EA"}.mdi-image-area::before{content:"\F2EB"}.mdi-image-area-close::before{content:"\F2EC"}.mdi-image-auto-adjust::before{content:"\FFE1"}.mdi-image-broken::before{content:"\F2ED"}.mdi-image-broken-variant::before{content:"\F2EE"}.mdi-image-filter::before{content:"\F2EF"}.mdi-image-filter-black-white::before{content:"\F2F0"}.mdi-image-filter-center-focus::before{content:"\F2F1"}.mdi-image-filter-center-focus-strong::before{content:"\FF1C"}.mdi-image-filter-center-focus-strong-outline::before{content:"\FF1D"}.mdi-image-filter-center-focus-weak::before{content:"\F2F2"}.mdi-image-filter-drama::before{content:"\F2F3"}.mdi-image-filter-frames::before{content:"\F2F4"}.mdi-image-filter-hdr::before{content:"\F2F5"}.mdi-image-filter-none::before{content:"\F2F6"}.mdi-image-filter-tilt-shift::before{content:"\F2F7"}.mdi-image-filter-vintage::before{content:"\F2F8"}.mdi-image-frame::before{content:"\FE8A"}.mdi-image-move::before{content:"\F9F7"}.mdi-image-multiple::before{content:"\F2F9"}.mdi-image-off::before{content:"\F82A"}.mdi-image-outline::before{content:"\F975"}.mdi-image-plus::before{content:"\F87B"}.mdi-image-search::before{content:"\F976"}.mdi-image-search-outline::before{content:"\F977"}.mdi-image-size-select-actual::before{content:"\FC69"}.mdi-image-size-select-large::before{content:"\FC6A"}.mdi-image-size-select-small::before{content:"\FC6B"}.mdi-import::before{content:"\F2FA"}.mdi-inbox::before{content:"\F686"}.mdi-inbox-arrow-down::before{content:"\F2FB"}.mdi-inbox-arrow-up::before{content:"\F3D1"}.mdi-inbox-multiple::before{content:"\F8AF"}.mdi-inbox-multiple-outline::before{content:"\FB84"}.mdi-incognito::before{content:"\F5F9"}.mdi-infinity::before{content:"\F6E3"}.mdi-information::before{content:"\F2FC"}.mdi-information-outline::before{content:"\F2FD"}.mdi-information-variant::before{content:"\F64E"}.mdi-instagram::before{content:"\F2FE"}.mdi-instapaper::before{content:"\F2FF"}.mdi-instrument-triangle::before{content:"\F0070"}.mdi-internet-explorer::before{content:"\F300"}.mdi-invert-colors::before{content:"\F301"}.mdi-invert-colors-off::before{content:"\FE8B"}.mdi-ip::before{content:"\FA5E"}.mdi-ip-network::before{content:"\FA5F"}.mdi-ip-network-outline::before{content:"\FC6C"}.mdi-ipod::before{content:"\FC6D"}.mdi-islam::before{content:"\F978"}.mdi-island::before{content:"\F0071"}.mdi-itunes::before{content:"\F676"}.mdi-iv-bag::before{content:"\F00E4"}.mdi-jabber::before{content:"\FDB1"}.mdi-jeepney::before{content:"\F302"}.mdi-jellyfish::before{content:"\FF1E"}.mdi-jellyfish-outline::before{content:"\FF1F"}.mdi-jira::before{content:"\F303"}.mdi-jquery::before{content:"\F87C"}.mdi-jsfiddle::before{content:"\F304"}.mdi-json::before{content:"\F626"}.mdi-judaism::before{content:"\F979"}.mdi-kabaddi::before{content:"\FD63"}.mdi-karate::before{content:"\F82B"}.mdi-keg::before{content:"\F305"}.mdi-kettle::before{content:"\F5FA"}.mdi-kettle-outline::before{content:"\FF73"}.mdi-key::before{content:"\F306"}.mdi-key-change::before{content:"\F307"}.mdi-key-link::before{content:"\F01CA"}.mdi-key-minus::before{content:"\F308"}.mdi-key-outline::before{content:"\FDB2"}.mdi-key-plus::before{content:"\F309"}.mdi-key-remove::before{content:"\F30A"}.mdi-key-star::before{content:"\F01C9"}.mdi-key-variant::before{content:"\F30B"}.mdi-key-wireless::before{content:"\FFE2"}.mdi-keyboard::before{content:"\F30C"}.mdi-keyboard-backspace::before{content:"\F30D"}.mdi-keyboard-caps::before{content:"\F30E"}.mdi-keyboard-close::before{content:"\F30F"}.mdi-keyboard-off::before{content:"\F310"}.mdi-keyboard-off-outline::before{content:"\FE8C"}.mdi-keyboard-outline::before{content:"\F97A"}.mdi-keyboard-return::before{content:"\F311"}.mdi-keyboard-settings::before{content:"\F9F8"}.mdi-keyboard-settings-outline::before{content:"\F9F9"}.mdi-keyboard-space::before{content:"\F0072"}.mdi-keyboard-tab::before{content:"\F312"}.mdi-keyboard-variant::before{content:"\F313"}.mdi-khanda::before{content:"\F0128"}.mdi-kickstarter::before{content:"\F744"}.mdi-knife::before{content:"\F9FA"}.mdi-knife-military::before{content:"\F9FB"}.mdi-kodi::before{content:"\F314"}.mdi-kubernetes::before{content:"\F0129"}.mdi-label::before{content:"\F315"}.mdi-label-off::before{content:"\FACA"}.mdi-label-off-outline::before{content:"\FACB"}.mdi-label-outline::before{content:"\F316"}.mdi-label-variant::before{content:"\FACC"}.mdi-label-variant-outline::before{content:"\FACD"}.mdi-ladybug::before{content:"\F82C"}.mdi-lambda::before{content:"\F627"}.mdi-lamp::before{content:"\F6B4"}.mdi-lan::before{content:"\F317"}.mdi-lan-connect::before{content:"\F318"}.mdi-lan-disconnect::before{content:"\F319"}.mdi-lan-pending::before{content:"\F31A"}.mdi-language-c::before{content:"\F671"}.mdi-language-cpp::before{content:"\F672"}.mdi-language-csharp::before{content:"\F31B"}.mdi-language-css3::before{content:"\F31C"}.mdi-language-go::before{content:"\F7D2"}.mdi-language-haskell::before{content:"\FC6E"}.mdi-language-html5::before{content:"\F31D"}.mdi-language-java::before{content:"\FB1C"}.mdi-language-javascript::before{content:"\F31E"}.mdi-language-lua::before{content:"\F8B0"}.mdi-language-php::before{content:"\F31F"}.mdi-language-python::before{content:"\F320"}.mdi-language-python-text::before{content:"\F321"}.mdi-language-r::before{content:"\F7D3"}.mdi-language-ruby-on-rails::before{content:"\FACE"}.mdi-language-swift::before{content:"\F6E4"}.mdi-language-typescript::before{content:"\F6E5"}.mdi-laptop::before{content:"\F322"}.mdi-laptop-chromebook::before{content:"\F323"}.mdi-laptop-mac::before{content:"\F324"}.mdi-laptop-off::before{content:"\F6E6"}.mdi-laptop-windows::before{content:"\F325"}.mdi-laravel::before{content:"\FACF"}.mdi-lasso::before{content:"\FF20"}.mdi-lastfm::before{content:"\F326"}.mdi-lastpass::before{content:"\F446"}.mdi-latitude::before{content:"\FF74"}.mdi-launch::before{content:"\F327"}.mdi-lava-lamp::before{content:"\F7D4"}.mdi-layers::before{content:"\F328"}.mdi-layers-minus::before{content:"\FE8D"}.mdi-layers-off::before{content:"\F329"}.mdi-layers-off-outline::before{content:"\F9FC"}.mdi-layers-outline::before{content:"\F9FD"}.mdi-layers-plus::before{content:"\FE30"}.mdi-layers-remove::before{content:"\FE31"}.mdi-layers-triple::before{content:"\FF75"}.mdi-layers-triple-outline::before{content:"\FF76"}.mdi-lead-pencil::before{content:"\F64F"}.mdi-leaf::before{content:"\F32A"}.mdi-leaf-maple::before{content:"\FC6F"}.mdi-leak::before{content:"\FDB3"}.mdi-leak-off::before{content:"\FDB4"}.mdi-led-off::before{content:"\F32B"}.mdi-led-on::before{content:"\F32C"}.mdi-led-outline::before{content:"\F32D"}.mdi-led-strip::before{content:"\F7D5"}.mdi-led-strip-variant::before{content:"\F0073"}.mdi-led-variant-off::before{content:"\F32E"}.mdi-led-variant-on::before{content:"\F32F"}.mdi-led-variant-outline::before{content:"\F330"}.mdi-leek::before{content:"\F01A8"}.mdi-less-than::before{content:"\F97B"}.mdi-less-than-or-equal::before{content:"\F97C"}.mdi-library::before{content:"\F331"}.mdi-library-books::before{content:"\F332"}.mdi-library-movie::before{content:"\FCF4"}.mdi-library-music::before{content:"\F333"}.mdi-library-music-outline::before{content:"\FF21"}.mdi-library-shelves::before{content:"\FB85"}.mdi-library-video::before{content:"\FCF5"}.mdi-license::before{content:"\FFE3"}.mdi-lifebuoy::before{content:"\F87D"}.mdi-light-switch::before{content:"\F97D"}.mdi-lightbulb::before{content:"\F335"}.mdi-lightbulb-off::before{content:"\FE32"}.mdi-lightbulb-off-outline::before{content:"\FE33"}.mdi-lightbulb-on::before{content:"\F6E7"}.mdi-lightbulb-on-outline::before{content:"\F6E8"}.mdi-lightbulb-outline::before{content:"\F336"}.mdi-lighthouse::before{content:"\F9FE"}.mdi-lighthouse-on::before{content:"\F9FF"}.mdi-link::before{content:"\F337"}.mdi-link-box::before{content:"\FCF6"}.mdi-link-box-outline::before{content:"\FCF7"}.mdi-link-box-variant::before{content:"\FCF8"}.mdi-link-box-variant-outline::before{content:"\FCF9"}.mdi-link-lock::before{content:"\F00E5"}.mdi-link-off::before{content:"\F338"}.mdi-link-plus::before{content:"\FC70"}.mdi-link-variant::before{content:"\F339"}.mdi-link-variant-minus::before{content:"\F012A"}.mdi-link-variant-off::before{content:"\F33A"}.mdi-link-variant-plus::before{content:"\F012B"}.mdi-link-variant-remove::before{content:"\F012C"}.mdi-linkedin::before{content:"\F33B"}.mdi-linkedin-box::before{content:"\F33C"}.mdi-linux::before{content:"\F33D"}.mdi-linux-mint::before{content:"\F8EC"}.mdi-litecoin::before{content:"\FA60"}.mdi-loading::before{content:"\F771"}.mdi-location-enter::before{content:"\FFE4"}.mdi-location-exit::before{content:"\FFE5"}.mdi-lock::before{content:"\F33E"}.mdi-lock-alert::before{content:"\F8ED"}.mdi-lock-clock::before{content:"\F97E"}.mdi-lock-open::before{content:"\F33F"}.mdi-lock-open-outline::before{content:"\F340"}.mdi-lock-open-variant::before{content:"\FFE6"}.mdi-lock-open-variant-outline::before{content:"\FFE7"}.mdi-lock-outline::before{content:"\F341"}.mdi-lock-pattern::before{content:"\F6E9"}.mdi-lock-plus::before{content:"\F5FB"}.mdi-lock-question::before{content:"\F8EE"}.mdi-lock-reset::before{content:"\F772"}.mdi-lock-smart::before{content:"\F8B1"}.mdi-locker::before{content:"\F7D6"}.mdi-locker-multiple::before{content:"\F7D7"}.mdi-login::before{content:"\F342"}.mdi-login-variant::before{content:"\F5FC"}.mdi-logout::before{content:"\F343"}.mdi-logout-variant::before{content:"\F5FD"}.mdi-longitude::before{content:"\FF77"}.mdi-looks::before{content:"\F344"}.mdi-loupe::before{content:"\F345"}.mdi-lumx::before{content:"\F346"}.mdi-lungs::before{content:"\F00AF"}.mdi-lyft::before{content:"\FB1D"}.mdi-magnet::before{content:"\F347"}.mdi-magnet-on::before{content:"\F348"}.mdi-magnify::before{content:"\F349"}.mdi-magnify-close::before{content:"\F97F"}.mdi-magnify-minus::before{content:"\F34A"}.mdi-magnify-minus-cursor::before{content:"\FA61"}.mdi-magnify-minus-outline::before{content:"\F6EB"}.mdi-magnify-plus::before{content:"\F34B"}.mdi-magnify-plus-cursor::before{content:"\FA62"}.mdi-magnify-plus-outline::before{content:"\F6EC"}.mdi-mail::before{content:"\FED8"}.mdi-mail-ru::before{content:"\F34C"}.mdi-mailbox::before{content:"\F6ED"}.mdi-mailbox-open::before{content:"\FD64"}.mdi-mailbox-open-outline::before{content:"\FD65"}.mdi-mailbox-open-up::before{content:"\FD66"}.mdi-mailbox-open-up-outline::before{content:"\FD67"}.mdi-mailbox-outline::before{content:"\FD68"}.mdi-mailbox-up::before{content:"\FD69"}.mdi-mailbox-up-outline::before{content:"\FD6A"}.mdi-map::before{content:"\F34D"}.mdi-map-check::before{content:"\FED9"}.mdi-map-check-outline::before{content:"\FEDA"}.mdi-map-clock::before{content:"\FCFA"}.mdi-map-clock-outline::before{content:"\FCFB"}.mdi-map-legend::before{content:"\FA00"}.mdi-map-marker::before{content:"\F34E"}.mdi-map-marker-alert::before{content:"\FF22"}.mdi-map-marker-alert-outline::before{content:"\FF23"}.mdi-map-marker-check::before{content:"\FC71"}.mdi-map-marker-circle::before{content:"\F34F"}.mdi-map-marker-distance::before{content:"\F8EF"}.mdi-map-marker-down::before{content:"\F012D"}.mdi-map-marker-minus::before{content:"\F650"}.mdi-map-marker-multiple::before{content:"\F350"}.mdi-map-marker-off::before{content:"\F351"}.mdi-map-marker-outline::before{content:"\F7D8"}.mdi-map-marker-path::before{content:"\FCFC"}.mdi-map-marker-plus::before{content:"\F651"}.mdi-map-marker-question::before{content:"\FF24"}.mdi-map-marker-question-outline::before{content:"\FF25"}.mdi-map-marker-radius::before{content:"\F352"}.mdi-map-marker-remove::before{content:"\FF26"}.mdi-map-marker-remove-variant::before{content:"\FF27"}.mdi-map-marker-up::before{content:"\F012E"}.mdi-map-minus::before{content:"\F980"}.mdi-map-outline::before{content:"\F981"}.mdi-map-plus::before{content:"\F982"}.mdi-map-search::before{content:"\F983"}.mdi-map-search-outline::before{content:"\F984"}.mdi-mapbox::before{content:"\FB86"}.mdi-margin::before{content:"\F353"}.mdi-markdown::before{content:"\F354"}.mdi-markdown-outline::before{content:"\FF78"}.mdi-marker::before{content:"\F652"}.mdi-marker-cancel::before{content:"\FDB5"}.mdi-marker-check::before{content:"\F355"}.mdi-mastodon::before{content:"\FAD0"}.mdi-mastodon-variant::before{content:"\FAD1"}.mdi-material-design::before{content:"\F985"}.mdi-material-ui::before{content:"\F357"}.mdi-math-compass::before{content:"\F358"}.mdi-math-cos::before{content:"\FC72"}.mdi-math-integral::before{content:"\FFE8"}.mdi-math-integral-box::before{content:"\FFE9"}.mdi-math-log::before{content:"\F00B0"}.mdi-math-norm::before{content:"\FFEA"}.mdi-math-norm-box::before{content:"\FFEB"}.mdi-math-sin::before{content:"\FC73"}.mdi-math-tan::before{content:"\FC74"}.mdi-matrix::before{content:"\F628"}.mdi-maxcdn::before{content:"\F359"}.mdi-medal::before{content:"\F986"}.mdi-medical-bag::before{content:"\F6EE"}.mdi-meditation::before{content:"\F01A6"}.mdi-medium::before{content:"\F35A"}.mdi-meetup::before{content:"\FAD2"}.mdi-memory::before{content:"\F35B"}.mdi-menu::before{content:"\F35C"}.mdi-menu-down::before{content:"\F35D"}.mdi-menu-down-outline::before{content:"\F6B5"}.mdi-menu-left::before{content:"\F35E"}.mdi-menu-left-outline::before{content:"\FA01"}.mdi-menu-open::before{content:"\FB87"}.mdi-menu-right::before{content:"\F35F"}.mdi-menu-right-outline::before{content:"\FA02"}.mdi-menu-swap::before{content:"\FA63"}.mdi-menu-swap-outline::before{content:"\FA64"}.mdi-menu-up::before{content:"\F360"}.mdi-menu-up-outline::before{content:"\F6B6"}.mdi-merge::before{content:"\FF79"}.mdi-message::before{content:"\F361"}.mdi-message-alert::before{content:"\F362"}.mdi-message-alert-outline::before{content:"\FA03"}.mdi-message-bulleted::before{content:"\F6A1"}.mdi-message-bulleted-off::before{content:"\F6A2"}.mdi-message-draw::before{content:"\F363"}.mdi-message-image::before{content:"\F364"}.mdi-message-image-outline::before{content:"\F0197"}.mdi-message-lock::before{content:"\FFEC"}.mdi-message-lock-outline::before{content:"\F0198"}.mdi-message-minus::before{content:"\F0199"}.mdi-message-minus-outline::before{content:"\F019A"}.mdi-message-outline::before{content:"\F365"}.mdi-message-plus::before{content:"\F653"}.mdi-message-plus-outline::before{content:"\F00E6"}.mdi-message-processing::before{content:"\F366"}.mdi-message-processing-outline::before{content:"\F019B"}.mdi-message-reply::before{content:"\F367"}.mdi-message-reply-text::before{content:"\F368"}.mdi-message-settings::before{content:"\F6EF"}.mdi-message-settings-outline::before{content:"\F019C"}.mdi-message-settings-variant::before{content:"\F6F0"}.mdi-message-settings-variant-outline::before{content:"\F019D"}.mdi-message-text::before{content:"\F369"}.mdi-message-text-clock::before{content:"\F019E"}.mdi-message-text-clock-outline::before{content:"\F019F"}.mdi-message-text-lock::before{content:"\FFED"}.mdi-message-text-lock-outline::before{content:"\F01A0"}.mdi-message-text-outline::before{content:"\F36A"}.mdi-message-video::before{content:"\F36B"}.mdi-meteor::before{content:"\F629"}.mdi-metronome::before{content:"\F7D9"}.mdi-metronome-tick::before{content:"\F7DA"}.mdi-micro-sd::before{content:"\F7DB"}.mdi-microphone::before{content:"\F36C"}.mdi-microphone-minus::before{content:"\F8B2"}.mdi-microphone-off::before{content:"\F36D"}.mdi-microphone-outline::before{content:"\F36E"}.mdi-microphone-plus::before{content:"\F8B3"}.mdi-microphone-settings::before{content:"\F36F"}.mdi-microphone-variant::before{content:"\F370"}.mdi-microphone-variant-off::before{content:"\F371"}.mdi-microscope::before{content:"\F654"}.mdi-microsoft::before{content:"\F372"}.mdi-microsoft-dynamics::before{content:"\F987"}.mdi-microwave::before{content:"\FC75"}.mdi-middleware::before{content:"\FF7A"}.mdi-middleware-outline::before{content:"\FF7B"}.mdi-midi::before{content:"\F8F0"}.mdi-midi-port::before{content:"\F8F1"}.mdi-mine::before{content:"\FDB6"}.mdi-minecraft::before{content:"\F373"}.mdi-mini-sd::before{content:"\FA04"}.mdi-minidisc::before{content:"\FA05"}.mdi-minus::before{content:"\F374"}.mdi-minus-box::before{content:"\F375"}.mdi-minus-box-multiple::before{content:"\F016C"}.mdi-minus-box-multiple-outline::before{content:"\F016D"}.mdi-minus-box-outline::before{content:"\F6F1"}.mdi-minus-circle::before{content:"\F376"}.mdi-minus-circle-outline::before{content:"\F377"}.mdi-minus-network::before{content:"\F378"}.mdi-minus-network-outline::before{content:"\FC76"}.mdi-mixcloud::before{content:"\F62A"}.mdi-mixed-martial-arts::before{content:"\FD6B"}.mdi-mixed-reality::before{content:"\F87E"}.mdi-mixer::before{content:"\F7DC"}.mdi-molecule::before{content:"\FB88"}.mdi-monitor::before{content:"\F379"}.mdi-monitor-cellphone::before{content:"\F988"}.mdi-monitor-cellphone-star::before{content:"\F989"}.mdi-monitor-clean::before{content:"\F012F"}.mdi-monitor-dashboard::before{content:"\FA06"}.mdi-monitor-lock::before{content:"\FDB7"}.mdi-monitor-multiple::before{content:"\F37A"}.mdi-monitor-off::before{content:"\FD6C"}.mdi-monitor-screenshot::before{content:"\FE34"}.mdi-monitor-speaker::before{content:"\FF7C"}.mdi-monitor-speaker-off::before{content:"\FF7D"}.mdi-monitor-star::before{content:"\FDB8"}.mdi-moon-first-quarter::before{content:"\FF7E"}.mdi-moon-full::before{content:"\FF7F"}.mdi-moon-last-quarter::before{content:"\FF80"}.mdi-moon-new::before{content:"\FF81"}.mdi-moon-waning-crescent::before{content:"\FF82"}.mdi-moon-waning-gibbous::before{content:"\FF83"}.mdi-moon-waxing-crescent::before{content:"\FF84"}.mdi-moon-waxing-gibbous::before{content:"\FF85"}.mdi-moped::before{content:"\F00B1"}.mdi-more::before{content:"\F37B"}.mdi-mother-nurse::before{content:"\FCFD"}.mdi-motion-sensor::before{content:"\FD6D"}.mdi-motorbike::before{content:"\F37C"}.mdi-mouse::before{content:"\F37D"}.mdi-mouse-bluetooth::before{content:"\F98A"}.mdi-mouse-off::before{content:"\F37E"}.mdi-mouse-variant::before{content:"\F37F"}.mdi-mouse-variant-off::before{content:"\F380"}.mdi-move-resize::before{content:"\F655"}.mdi-move-resize-variant::before{content:"\F656"}.mdi-movie::before{content:"\F381"}.mdi-movie-edit::before{content:"\F014D"}.mdi-movie-edit-outline::before{content:"\F014E"}.mdi-movie-filter::before{content:"\F014F"}.mdi-movie-filter-outline::before{content:"\F0150"}.mdi-movie-open::before{content:"\FFEE"}.mdi-movie-open-outline::before{content:"\FFEF"}.mdi-movie-outline::before{content:"\FDB9"}.mdi-movie-roll::before{content:"\F7DD"}.mdi-muffin::before{content:"\F98B"}.mdi-multiplication::before{content:"\F382"}.mdi-multiplication-box::before{content:"\F383"}.mdi-mushroom::before{content:"\F7DE"}.mdi-mushroom-outline::before{content:"\F7DF"}.mdi-music::before{content:"\F759"}.mdi-music-accidental-double-flat::before{content:"\FF86"}.mdi-music-accidental-double-sharp::before{content:"\FF87"}.mdi-music-accidental-flat::before{content:"\FF88"}.mdi-music-accidental-natural::before{content:"\FF89"}.mdi-music-accidental-sharp::before{content:"\FF8A"}.mdi-music-box::before{content:"\F384"}.mdi-music-box-outline::before{content:"\F385"}.mdi-music-circle::before{content:"\F386"}.mdi-music-circle-outline::before{content:"\FAD3"}.mdi-music-clef-alto::before{content:"\FF8B"}.mdi-music-clef-bass::before{content:"\FF8C"}.mdi-music-clef-treble::before{content:"\FF8D"}.mdi-music-note::before{content:"\F387"}.mdi-music-note-bluetooth::before{content:"\F5FE"}.mdi-music-note-bluetooth-off::before{content:"\F5FF"}.mdi-music-note-eighth::before{content:"\F388"}.mdi-music-note-eighth-dotted::before{content:"\FF8E"}.mdi-music-note-half::before{content:"\F389"}.mdi-music-note-half-dotted::before{content:"\FF8F"}.mdi-music-note-off::before{content:"\F38A"}.mdi-music-note-off-outline::before{content:"\FF90"}.mdi-music-note-outline::before{content:"\FF91"}.mdi-music-note-plus::before{content:"\FDBA"}.mdi-music-note-quarter::before{content:"\F38B"}.mdi-music-note-quarter-dotted::before{content:"\FF92"}.mdi-music-note-sixteenth::before{content:"\F38C"}.mdi-music-note-sixteenth-dotted::before{content:"\FF93"}.mdi-music-note-whole::before{content:"\F38D"}.mdi-music-note-whole-dotted::before{content:"\FF94"}.mdi-music-off::before{content:"\F75A"}.mdi-music-rest-eighth::before{content:"\FF95"}.mdi-music-rest-half::before{content:"\FF96"}.mdi-music-rest-quarter::before{content:"\FF97"}.mdi-music-rest-sixteenth::before{content:"\FF98"}.mdi-music-rest-whole::before{content:"\FF99"}.mdi-nail::before{content:"\FDBB"}.mdi-nas::before{content:"\F8F2"}.mdi-nativescript::before{content:"\F87F"}.mdi-nature::before{content:"\F38E"}.mdi-nature-people::before{content:"\F38F"}.mdi-navigation::before{content:"\F390"}.mdi-near-me::before{content:"\F5CD"}.mdi-necklace::before{content:"\FF28"}.mdi-needle::before{content:"\F391"}.mdi-netflix::before{content:"\F745"}.mdi-network::before{content:"\F6F2"}.mdi-network-off::before{content:"\FC77"}.mdi-network-off-outline::before{content:"\FC78"}.mdi-network-outline::before{content:"\FC79"}.mdi-network-router::before{content:"\F00B2"}.mdi-network-strength-1::before{content:"\F8F3"}.mdi-network-strength-1-alert::before{content:"\F8F4"}.mdi-network-strength-2::before{content:"\F8F5"}.mdi-network-strength-2-alert::before{content:"\F8F6"}.mdi-network-strength-3::before{content:"\F8F7"}.mdi-network-strength-3-alert::before{content:"\F8F8"}.mdi-network-strength-4::before{content:"\F8F9"}.mdi-network-strength-4-alert::before{content:"\F8FA"}.mdi-network-strength-off::before{content:"\F8FB"}.mdi-network-strength-off-outline::before{content:"\F8FC"}.mdi-network-strength-outline::before{content:"\F8FD"}.mdi-new-box::before{content:"\F394"}.mdi-newspaper::before{content:"\F395"}.mdi-newspaper-minus::before{content:"\FF29"}.mdi-newspaper-plus::before{content:"\FF2A"}.mdi-newspaper-variant::before{content:"\F0023"}.mdi-newspaper-variant-multiple::before{content:"\F0024"}.mdi-newspaper-variant-multiple-outline::before{content:"\F0025"}.mdi-newspaper-variant-outline::before{content:"\F0026"}.mdi-nfc::before{content:"\F396"}.mdi-nfc-off::before{content:"\FE35"}.mdi-nfc-search-variant::before{content:"\FE36"}.mdi-nfc-tap::before{content:"\F397"}.mdi-nfc-variant::before{content:"\F398"}.mdi-nfc-variant-off::before{content:"\FE37"}.mdi-ninja::before{content:"\F773"}.mdi-nintendo-switch::before{content:"\F7E0"}.mdi-nix::before{content:"\F0130"}.mdi-nodejs::before{content:"\F399"}.mdi-noodles::before{content:"\F01A9"}.mdi-not-equal::before{content:"\F98C"}.mdi-not-equal-variant::before{content:"\F98D"}.mdi-note::before{content:"\F39A"}.mdi-note-multiple::before{content:"\F6B7"}.mdi-note-multiple-outline::before{content:"\F6B8"}.mdi-note-outline::before{content:"\F39B"}.mdi-note-plus::before{content:"\F39C"}.mdi-note-plus-outline::before{content:"\F39D"}.mdi-note-text::before{content:"\F39E"}.mdi-notebook::before{content:"\F82D"}.mdi-notebook-multiple::before{content:"\FE38"}.mdi-notebook-outline::before{content:"\FEDC"}.mdi-notification-clear-all::before{content:"\F39F"}.mdi-npm::before{content:"\F6F6"}.mdi-npm-variant::before{content:"\F98E"}.mdi-npm-variant-outline::before{content:"\F98F"}.mdi-nuke::before{content:"\F6A3"}.mdi-null::before{content:"\F7E1"}.mdi-numeric::before{content:"\F3A0"}.mdi-numeric-0::before{content:"\30"}.mdi-numeric-0-box::before{content:"\F3A1"}.mdi-numeric-0-box-multiple::before{content:"\FF2B"}.mdi-numeric-0-box-multiple-outline::before{content:"\F3A2"}.mdi-numeric-0-box-outline::before{content:"\F3A3"}.mdi-numeric-0-circle::before{content:"\FC7A"}.mdi-numeric-0-circle-outline::before{content:"\FC7B"}.mdi-numeric-1::before{content:"\31"}.mdi-numeric-1-box::before{content:"\F3A4"}.mdi-numeric-1-box-multiple::before{content:"\FF2C"}.mdi-numeric-1-box-multiple-outline::before{content:"\F3A5"}.mdi-numeric-1-box-outline::before{content:"\F3A6"}.mdi-numeric-1-circle::before{content:"\FC7C"}.mdi-numeric-1-circle-outline::before{content:"\FC7D"}.mdi-numeric-10::before{content:"\F000A"}.mdi-numeric-10-box::before{content:"\FF9A"}.mdi-numeric-10-box-multiple::before{content:"\F000B"}.mdi-numeric-10-box-multiple-outline::before{content:"\F000C"}.mdi-numeric-10-box-outline::before{content:"\FF9B"}.mdi-numeric-10-circle::before{content:"\F000D"}.mdi-numeric-10-circle-outline::before{content:"\F000E"}.mdi-numeric-2::before{content:"\32"}.mdi-numeric-2-box::before{content:"\F3A7"}.mdi-numeric-2-box-multiple::before{content:"\FF2D"}.mdi-numeric-2-box-multiple-outline::before{content:"\F3A8"}.mdi-numeric-2-box-outline::before{content:"\F3A9"}.mdi-numeric-2-circle::before{content:"\FC7E"}.mdi-numeric-2-circle-outline::before{content:"\FC7F"}.mdi-numeric-3::before{content:"\33"}.mdi-numeric-3-box::before{content:"\F3AA"}.mdi-numeric-3-box-multiple::before{content:"\FF2E"}.mdi-numeric-3-box-multiple-outline::before{content:"\F3AB"}.mdi-numeric-3-box-outline::before{content:"\F3AC"}.mdi-numeric-3-circle::before{content:"\FC80"}.mdi-numeric-3-circle-outline::before{content:"\FC81"}.mdi-numeric-4::before{content:"\34"}.mdi-numeric-4-box::before{content:"\F3AD"}.mdi-numeric-4-box-multiple::before{content:"\FF2F"}.mdi-numeric-4-box-multiple-outline::before{content:"\F3AE"}.mdi-numeric-4-box-outline::before{content:"\F3AF"}.mdi-numeric-4-circle::before{content:"\FC82"}.mdi-numeric-4-circle-outline::before{content:"\FC83"}.mdi-numeric-5::before{content:"\35"}.mdi-numeric-5-box::before{content:"\F3B0"}.mdi-numeric-5-box-multiple::before{content:"\FF30"}.mdi-numeric-5-box-multiple-outline::before{content:"\F3B1"}.mdi-numeric-5-box-outline::before{content:"\F3B2"}.mdi-numeric-5-circle::before{content:"\FC84"}.mdi-numeric-5-circle-outline::before{content:"\FC85"}.mdi-numeric-6::before{content:"\36"}.mdi-numeric-6-box::before{content:"\F3B3"}.mdi-numeric-6-box-multiple::before{content:"\FF31"}.mdi-numeric-6-box-multiple-outline::before{content:"\F3B4"}.mdi-numeric-6-box-outline::before{content:"\F3B5"}.mdi-numeric-6-circle::before{content:"\FC86"}.mdi-numeric-6-circle-outline::before{content:"\FC87"}.mdi-numeric-7::before{content:"\37"}.mdi-numeric-7-box::before{content:"\F3B6"}.mdi-numeric-7-box-multiple::before{content:"\FF32"}.mdi-numeric-7-box-multiple-outline::before{content:"\F3B7"}.mdi-numeric-7-box-outline::before{content:"\F3B8"}.mdi-numeric-7-circle::before{content:"\FC88"}.mdi-numeric-7-circle-outline::before{content:"\FC89"}.mdi-numeric-8::before{content:"\38"}.mdi-numeric-8-box::before{content:"\F3B9"}.mdi-numeric-8-box-multiple::before{content:"\FF33"}.mdi-numeric-8-box-multiple-outline::before{content:"\F3BA"}.mdi-numeric-8-box-outline::before{content:"\F3BB"}.mdi-numeric-8-circle::before{content:"\FC8A"}.mdi-numeric-8-circle-outline::before{content:"\FC8B"}.mdi-numeric-9::before{content:"\39"}.mdi-numeric-9-box::before{content:"\F3BC"}.mdi-numeric-9-box-multiple::before{content:"\FF34"}.mdi-numeric-9-box-multiple-outline::before{content:"\F3BD"}.mdi-numeric-9-box-outline::before{content:"\F3BE"}.mdi-numeric-9-circle::before{content:"\FC8C"}.mdi-numeric-9-circle-outline::before{content:"\FC8D"}.mdi-numeric-9-plus::before{content:"\F000F"}.mdi-numeric-9-plus-box::before{content:"\F3BF"}.mdi-numeric-9-plus-box-multiple::before{content:"\FF35"}.mdi-numeric-9-plus-box-multiple-outline::before{content:"\F3C0"}.mdi-numeric-9-plus-box-outline::before{content:"\F3C1"}.mdi-numeric-9-plus-circle::before{content:"\FC8E"}.mdi-numeric-9-plus-circle-outline::before{content:"\FC8F"}.mdi-numeric-negative-1::before{content:"\F0074"}.mdi-nut::before{content:"\F6F7"}.mdi-nutrition::before{content:"\F3C2"}.mdi-nuxt::before{content:"\F0131"}.mdi-oar::before{content:"\F67B"}.mdi-ocarina::before{content:"\FDBC"}.mdi-ocr::before{content:"\F0165"}.mdi-octagon::before{content:"\F3C3"}.mdi-octagon-outline::before{content:"\F3C4"}.mdi-octagram::before{content:"\F6F8"}.mdi-octagram-outline::before{content:"\F774"}.mdi-odnoklassniki::before{content:"\F3C5"}.mdi-office::before{content:"\F3C6"}.mdi-office-building::before{content:"\F990"}.mdi-oil::before{content:"\F3C7"}.mdi-oil-lamp::before{content:"\FF36"}.mdi-oil-level::before{content:"\F0075"}.mdi-oil-temperature::before{content:"\F0019"}.mdi-omega::before{content:"\F3C9"}.mdi-one-up::before{content:"\FB89"}.mdi-onedrive::before{content:"\F3CA"}.mdi-onenote::before{content:"\F746"}.mdi-onepassword::before{content:"\F880"}.mdi-opacity::before{content:"\F5CC"}.mdi-open-in-app::before{content:"\F3CB"}.mdi-open-in-new::before{content:"\F3CC"}.mdi-open-source-initiative::before{content:"\FB8A"}.mdi-openid::before{content:"\F3CD"}.mdi-opera::before{content:"\F3CE"}.mdi-orbit::before{content:"\F018"}.mdi-origin::before{content:"\FB2B"}.mdi-ornament::before{content:"\F3CF"}.mdi-ornament-variant::before{content:"\F3D0"}.mdi-outdoor-lamp::before{content:"\F0076"}.mdi-outlook::before{content:"\FCFE"}.mdi-overscan::before{content:"\F0027"}.mdi-owl::before{content:"\F3D2"}.mdi-pac-man::before{content:"\FB8B"}.mdi-package::before{content:"\F3D3"}.mdi-package-down::before{content:"\F3D4"}.mdi-package-up::before{content:"\F3D5"}.mdi-package-variant::before{content:"\F3D6"}.mdi-package-variant-closed::before{content:"\F3D7"}.mdi-page-first::before{content:"\F600"}.mdi-page-last::before{content:"\F601"}.mdi-page-layout-body::before{content:"\F6F9"}.mdi-page-layout-footer::before{content:"\F6FA"}.mdi-page-layout-header::before{content:"\F6FB"}.mdi-page-layout-header-footer::before{content:"\FF9C"}.mdi-page-layout-sidebar-left::before{content:"\F6FC"}.mdi-page-layout-sidebar-right::before{content:"\F6FD"}.mdi-page-next::before{content:"\FB8C"}.mdi-page-next-outline::before{content:"\FB8D"}.mdi-page-previous::before{content:"\FB8E"}.mdi-page-previous-outline::before{content:"\FB8F"}.mdi-palette::before{content:"\F3D8"}.mdi-palette-advanced::before{content:"\F3D9"}.mdi-palette-outline::before{content:"\FE6C"}.mdi-palette-swatch::before{content:"\F8B4"}.mdi-palm-tree::before{content:"\F0077"}.mdi-pan::before{content:"\FB90"}.mdi-pan-bottom-left::before{content:"\FB91"}.mdi-pan-bottom-right::before{content:"\FB92"}.mdi-pan-down::before{content:"\FB93"}.mdi-pan-horizontal::before{content:"\FB94"}.mdi-pan-left::before{content:"\FB95"}.mdi-pan-right::before{content:"\FB96"}.mdi-pan-top-left::before{content:"\FB97"}.mdi-pan-top-right::before{content:"\FB98"}.mdi-pan-up::before{content:"\FB99"}.mdi-pan-vertical::before{content:"\FB9A"}.mdi-panda::before{content:"\F3DA"}.mdi-pandora::before{content:"\F3DB"}.mdi-panorama::before{content:"\F3DC"}.mdi-panorama-fisheye::before{content:"\F3DD"}.mdi-panorama-horizontal::before{content:"\F3DE"}.mdi-panorama-vertical::before{content:"\F3DF"}.mdi-panorama-wide-angle::before{content:"\F3E0"}.mdi-paper-cut-vertical::before{content:"\F3E1"}.mdi-paper-roll::before{content:"\F0182"}.mdi-paper-roll-outline::before{content:"\F0183"}.mdi-paperclip::before{content:"\F3E2"}.mdi-parachute::before{content:"\FC90"}.mdi-parachute-outline::before{content:"\FC91"}.mdi-parking::before{content:"\F3E3"}.mdi-party-popper::before{content:"\F0078"}.mdi-passport::before{content:"\F7E2"}.mdi-passport-biometric::before{content:"\FDBD"}.mdi-pasta::before{content:"\F018B"}.mdi-patio-heater::before{content:"\FF9D"}.mdi-patreon::before{content:"\F881"}.mdi-pause::before{content:"\F3E4"}.mdi-pause-circle::before{content:"\F3E5"}.mdi-pause-circle-outline::before{content:"\F3E6"}.mdi-pause-octagon::before{content:"\F3E7"}.mdi-pause-octagon-outline::before{content:"\F3E8"}.mdi-paw::before{content:"\F3E9"}.mdi-paw-off::before{content:"\F657"}.mdi-paypal::before{content:"\F882"}.mdi-pdf-box::before{content:"\FE39"}.mdi-peace::before{content:"\F883"}.mdi-peanut::before{content:"\F001E"}.mdi-peanut-off::before{content:"\F001F"}.mdi-peanut-off-outline::before{content:"\F0021"}.mdi-peanut-outline::before{content:"\F0020"}.mdi-pen::before{content:"\F3EA"}.mdi-pen-lock::before{content:"\FDBE"}.mdi-pen-minus::before{content:"\FDBF"}.mdi-pen-off::before{content:"\FDC0"}.mdi-pen-plus::before{content:"\FDC1"}.mdi-pen-remove::before{content:"\FDC2"}.mdi-pencil::before{content:"\F3EB"}.mdi-pencil-box::before{content:"\F3EC"}.mdi-pencil-box-multiple::before{content:"\F016F"}.mdi-pencil-box-multiple-outline::before{content:"\F0170"}.mdi-pencil-box-outline::before{content:"\F3ED"}.mdi-pencil-circle::before{content:"\F6FE"}.mdi-pencil-circle-outline::before{content:"\F775"}.mdi-pencil-lock::before{content:"\F3EE"}.mdi-pencil-lock-outline::before{content:"\FDC3"}.mdi-pencil-minus::before{content:"\FDC4"}.mdi-pencil-minus-outline::before{content:"\FDC5"}.mdi-pencil-off::before{content:"\F3EF"}.mdi-pencil-off-outline::before{content:"\FDC6"}.mdi-pencil-outline::before{content:"\FC92"}.mdi-pencil-plus::before{content:"\FDC7"}.mdi-pencil-plus-outline::before{content:"\FDC8"}.mdi-pencil-remove::before{content:"\FDC9"}.mdi-pencil-remove-outline::before{content:"\FDCA"}.mdi-penguin::before{content:"\FEDD"}.mdi-pentagon::before{content:"\F6FF"}.mdi-pentagon-outline::before{content:"\F700"}.mdi-percent::before{content:"\F3F0"}.mdi-periodic-table::before{content:"\F8B5"}.mdi-periodic-table-co2::before{content:"\F7E3"}.mdi-periscope::before{content:"\F747"}.mdi-perspective-less::before{content:"\FCFF"}.mdi-perspective-more::before{content:"\FD00"}.mdi-pharmacy::before{content:"\F3F1"}.mdi-phone::before{content:"\F3F2"}.mdi-phone-alert::before{content:"\FF37"}.mdi-phone-bluetooth::before{content:"\F3F3"}.mdi-phone-cancel::before{content:"\F00E7"}.mdi-phone-classic::before{content:"\F602"}.mdi-phone-forward::before{content:"\F3F4"}.mdi-phone-hangup::before{content:"\F3F5"}.mdi-phone-in-talk::before{content:"\F3F6"}.mdi-phone-in-talk-outline::before{content:"\F01AD"}.mdi-phone-incoming::before{content:"\F3F7"}.mdi-phone-lock::before{content:"\F3F8"}.mdi-phone-log::before{content:"\F3F9"}.mdi-phone-minus::before{content:"\F658"}.mdi-phone-missed::before{content:"\F3FA"}.mdi-phone-off::before{content:"\FDCB"}.mdi-phone-outgoing::before{content:"\F3FB"}.mdi-phone-outline::before{content:"\FDCC"}.mdi-phone-paused::before{content:"\F3FC"}.mdi-phone-plus::before{content:"\F659"}.mdi-phone-return::before{content:"\F82E"}.mdi-phone-rotate-landscape::before{content:"\F884"}.mdi-phone-rotate-portrait::before{content:"\F885"}.mdi-phone-settings::before{content:"\F3FD"}.mdi-phone-voip::before{content:"\F3FE"}.mdi-pi::before{content:"\F3FF"}.mdi-pi-box::before{content:"\F400"}.mdi-pi-hole::before{content:"\FDCD"}.mdi-piano::before{content:"\F67C"}.mdi-pickaxe::before{content:"\F8B6"}.mdi-picture-in-picture-bottom-right::before{content:"\FE3A"}.mdi-picture-in-picture-bottom-right-outline::before{content:"\FE3B"}.mdi-picture-in-picture-top-right::before{content:"\FE3C"}.mdi-picture-in-picture-top-right-outline::before{content:"\FE3D"}.mdi-pier::before{content:"\F886"}.mdi-pier-crane::before{content:"\F887"}.mdi-pig::before{content:"\F401"}.mdi-pig-variant::before{content:"\F0028"}.mdi-piggy-bank::before{content:"\F0029"}.mdi-pill::before{content:"\F402"}.mdi-pillar::before{content:"\F701"}.mdi-pin::before{content:"\F403"}.mdi-pin-off::before{content:"\F404"}.mdi-pin-off-outline::before{content:"\F92F"}.mdi-pin-outline::before{content:"\F930"}.mdi-pine-tree::before{content:"\F405"}.mdi-pine-tree-box::before{content:"\F406"}.mdi-pinterest::before{content:"\F407"}.mdi-pinterest-box::before{content:"\F408"}.mdi-pinwheel::before{content:"\FAD4"}.mdi-pinwheel-outline::before{content:"\FAD5"}.mdi-pipe::before{content:"\F7E4"}.mdi-pipe-disconnected::before{content:"\F7E5"}.mdi-pipe-leak::before{content:"\F888"}.mdi-pirate::before{content:"\FA07"}.mdi-pistol::before{content:"\F702"}.mdi-piston::before{content:"\F889"}.mdi-pizza::before{content:"\F409"}.mdi-play::before{content:"\F40A"}.mdi-play-box-outline::before{content:"\F40B"}.mdi-play-circle::before{content:"\F40C"}.mdi-play-circle-outline::before{content:"\F40D"}.mdi-play-network::before{content:"\F88A"}.mdi-play-network-outline::before{content:"\FC93"}.mdi-play-outline::before{content:"\FF38"}.mdi-play-pause::before{content:"\F40E"}.mdi-play-protected-content::before{content:"\F40F"}.mdi-play-speed::before{content:"\F8FE"}.mdi-playlist-check::before{content:"\F5C7"}.mdi-playlist-edit::before{content:"\F8FF"}.mdi-playlist-minus::before{content:"\F410"}.mdi-playlist-music::before{content:"\FC94"}.mdi-playlist-music-outline::before{content:"\FC95"}.mdi-playlist-play::before{content:"\F411"}.mdi-playlist-plus::before{content:"\F412"}.mdi-playlist-remove::before{content:"\F413"}.mdi-playlist-star::before{content:"\FDCE"}.mdi-playstation::before{content:"\F414"}.mdi-plex::before{content:"\F6B9"}.mdi-plus::before{content:"\F415"}.mdi-plus-box::before{content:"\F416"}.mdi-plus-box-multiple::before{content:"\F334"}.mdi-plus-box-multiple-outline::before{content:"\F016E"}.mdi-plus-box-outline::before{content:"\F703"}.mdi-plus-circle::before{content:"\F417"}.mdi-plus-circle-multiple-outline::before{content:"\F418"}.mdi-plus-circle-outline::before{content:"\F419"}.mdi-plus-minus::before{content:"\F991"}.mdi-plus-minus-box::before{content:"\F992"}.mdi-plus-network::before{content:"\F41A"}.mdi-plus-network-outline::before{content:"\FC96"}.mdi-plus-one::before{content:"\F41B"}.mdi-plus-outline::before{content:"\F704"}.mdi-pocket::before{content:"\F41C"}.mdi-podcast::before{content:"\F993"}.mdi-podium::before{content:"\FD01"}.mdi-podium-bronze::before{content:"\FD02"}.mdi-podium-gold::before{content:"\FD03"}.mdi-podium-silver::before{content:"\FD04"}.mdi-point-of-sale::before{content:"\FD6E"}.mdi-pokeball::before{content:"\F41D"}.mdi-pokemon-go::before{content:"\FA08"}.mdi-poker-chip::before{content:"\F82F"}.mdi-polaroid::before{content:"\F41E"}.mdi-police-badge::before{content:"\F0192"}.mdi-police-badge-outline::before{content:"\F0193"}.mdi-poll::before{content:"\F41F"}.mdi-poll-box::before{content:"\F420"}.mdi-polymer::before{content:"\F421"}.mdi-pool::before{content:"\F606"}.mdi-popcorn::before{content:"\F422"}.mdi-post::before{content:"\F002A"}.mdi-post-outline::before{content:"\F002B"}.mdi-postage-stamp::before{content:"\FC97"}.mdi-pot::before{content:"\F65A"}.mdi-pot-mix::before{content:"\F65B"}.mdi-pound::before{content:"\F423"}.mdi-pound-box::before{content:"\F424"}.mdi-pound-box-outline::before{content:"\F01AA"}.mdi-power::before{content:"\F425"}.mdi-power-cycle::before{content:"\F900"}.mdi-power-off::before{content:"\F901"}.mdi-power-on::before{content:"\F902"}.mdi-power-plug::before{content:"\F6A4"}.mdi-power-plug-off::before{content:"\F6A5"}.mdi-power-settings::before{content:"\F426"}.mdi-power-sleep::before{content:"\F903"}.mdi-power-socket::before{content:"\F427"}.mdi-power-socket-au::before{content:"\F904"}.mdi-power-socket-de::before{content:"\F0132"}.mdi-power-socket-eu::before{content:"\F7E6"}.mdi-power-socket-fr::before{content:"\F0133"}.mdi-power-socket-jp::before{content:"\F0134"}.mdi-power-socket-uk::before{content:"\F7E7"}.mdi-power-socket-us::before{content:"\F7E8"}.mdi-power-standby::before{content:"\F905"}.mdi-powershell::before{content:"\FA09"}.mdi-prescription::before{content:"\F705"}.mdi-presentation::before{content:"\F428"}.mdi-presentation-play::before{content:"\F429"}.mdi-printer::before{content:"\F42A"}.mdi-printer-3d::before{content:"\F42B"}.mdi-printer-3d-nozzle::before{content:"\FE3E"}.mdi-printer-3d-nozzle-outline::before{content:"\FE3F"}.mdi-printer-alert::before{content:"\F42C"}.mdi-printer-check::before{content:"\F0171"}.mdi-printer-off::before{content:"\FE40"}.mdi-printer-pos::before{content:"\F0079"}.mdi-printer-settings::before{content:"\F706"}.mdi-printer-wireless::before{content:"\FA0A"}.mdi-priority-high::before{content:"\F603"}.mdi-priority-low::before{content:"\F604"}.mdi-professional-hexagon::before{content:"\F42D"}.mdi-progress-alert::before{content:"\FC98"}.mdi-progress-check::before{content:"\F994"}.mdi-progress-clock::before{content:"\F995"}.mdi-progress-close::before{content:"\F0135"}.mdi-progress-download::before{content:"\F996"}.mdi-progress-upload::before{content:"\F997"}.mdi-progress-wrench::before{content:"\FC99"}.mdi-projector::before{content:"\F42E"}.mdi-projector-screen::before{content:"\F42F"}.mdi-protocol::before{content:"\FFF9"}.mdi-publish::before{content:"\F6A6"}.mdi-pulse::before{content:"\F430"}.mdi-pumpkin::before{content:"\FB9B"}.mdi-purse::before{content:"\FF39"}.mdi-purse-outline::before{content:"\FF3A"}.mdi-puzzle::before{content:"\F431"}.mdi-puzzle-outline::before{content:"\FA65"}.mdi-qi::before{content:"\F998"}.mdi-qqchat::before{content:"\F605"}.mdi-qrcode::before{content:"\F432"}.mdi-qrcode-edit::before{content:"\F8B7"}.mdi-qrcode-minus::before{content:"\F01B7"}.mdi-qrcode-plus::before{content:"\F01B6"}.mdi-qrcode-remove::before{content:"\F01B8"}.mdi-qrcode-scan::before{content:"\F433"}.mdi-quadcopter::before{content:"\F434"}.mdi-quality-high::before{content:"\F435"}.mdi-quality-low::before{content:"\FA0B"}.mdi-quality-medium::before{content:"\FA0C"}.mdi-quicktime::before{content:"\F436"}.mdi-quora::before{content:"\FD05"}.mdi-rabbit::before{content:"\F906"}.mdi-racing-helmet::before{content:"\FD6F"}.mdi-racquetball::before{content:"\FD70"}.mdi-radar::before{content:"\F437"}.mdi-radiator::before{content:"\F438"}.mdi-radiator-disabled::before{content:"\FAD6"}.mdi-radiator-off::before{content:"\FAD7"}.mdi-radio::before{content:"\F439"}.mdi-radio-am::before{content:"\FC9A"}.mdi-radio-fm::before{content:"\FC9B"}.mdi-radio-handheld::before{content:"\F43A"}.mdi-radio-tower::before{content:"\F43B"}.mdi-radioactive::before{content:"\F43C"}.mdi-radioactive-off::before{content:"\FEDE"}.mdi-radiobox-blank::before{content:"\F43D"}.mdi-radiobox-marked::before{content:"\F43E"}.mdi-radius::before{content:"\FC9C"}.mdi-radius-outline::before{content:"\FC9D"}.mdi-railroad-light::before{content:"\FF3B"}.mdi-raspberry-pi::before{content:"\F43F"}.mdi-ray-end::before{content:"\F440"}.mdi-ray-end-arrow::before{content:"\F441"}.mdi-ray-start::before{content:"\F442"}.mdi-ray-start-arrow::before{content:"\F443"}.mdi-ray-start-end::before{content:"\F444"}.mdi-ray-vertex::before{content:"\F445"}.mdi-react::before{content:"\F707"}.mdi-read::before{content:"\F447"}.mdi-receipt::before{content:"\F449"}.mdi-record::before{content:"\F44A"}.mdi-record-circle::before{content:"\FEDF"}.mdi-record-circle-outline::before{content:"\FEE0"}.mdi-record-player::before{content:"\F999"}.mdi-record-rec::before{content:"\F44B"}.mdi-rectangle::before{content:"\FE41"}.mdi-rectangle-outline::before{content:"\FE42"}.mdi-recycle::before{content:"\F44C"}.mdi-reddit::before{content:"\F44D"}.mdi-redhat::before{content:"\F0146"}.mdi-redo::before{content:"\F44E"}.mdi-redo-variant::before{content:"\F44F"}.mdi-reflect-horizontal::before{content:"\FA0D"}.mdi-reflect-vertical::before{content:"\FA0E"}.mdi-refresh::before{content:"\F450"}.mdi-regex::before{content:"\F451"}.mdi-registered-trademark::before{content:"\FA66"}.mdi-relative-scale::before{content:"\F452"}.mdi-reload::before{content:"\F453"}.mdi-reload-alert::before{content:"\F0136"}.mdi-reminder::before{content:"\F88B"}.mdi-remote::before{content:"\F454"}.mdi-remote-desktop::before{content:"\F8B8"}.mdi-remote-off::before{content:"\FEE1"}.mdi-remote-tv::before{content:"\FEE2"}.mdi-remote-tv-off::before{content:"\FEE3"}.mdi-rename-box::before{content:"\F455"}.mdi-reorder-horizontal::before{content:"\F687"}.mdi-reorder-vertical::before{content:"\F688"}.mdi-repeat::before{content:"\F456"}.mdi-repeat-off::before{content:"\F457"}.mdi-repeat-once::before{content:"\F458"}.mdi-replay::before{content:"\F459"}.mdi-reply::before{content:"\F45A"}.mdi-reply-all::before{content:"\F45B"}.mdi-reply-all-outline::before{content:"\FF3C"}.mdi-reply-outline::before{content:"\FF3D"}.mdi-reproduction::before{content:"\F45C"}.mdi-resistor::before{content:"\FB1F"}.mdi-resistor-nodes::before{content:"\FB20"}.mdi-resize::before{content:"\FA67"}.mdi-resize-bottom-right::before{content:"\F45D"}.mdi-responsive::before{content:"\F45E"}.mdi-restart::before{content:"\F708"}.mdi-restart-alert::before{content:"\F0137"}.mdi-restart-off::before{content:"\FD71"}.mdi-restore::before{content:"\F99A"}.mdi-restore-alert::before{content:"\F0138"}.mdi-rewind::before{content:"\F45F"}.mdi-rewind-10::before{content:"\FD06"}.mdi-rewind-30::before{content:"\FD72"}.mdi-rewind-outline::before{content:"\F709"}.mdi-rhombus::before{content:"\F70A"}.mdi-rhombus-medium::before{content:"\FA0F"}.mdi-rhombus-outline::before{content:"\F70B"}.mdi-rhombus-split::before{content:"\FA10"}.mdi-ribbon::before{content:"\F460"}.mdi-rice::before{content:"\F7E9"}.mdi-ring::before{content:"\F7EA"}.mdi-rivet::before{content:"\FE43"}.mdi-road::before{content:"\F461"}.mdi-road-variant::before{content:"\F462"}.mdi-robber::before{content:"\F007A"}.mdi-robot::before{content:"\F6A8"}.mdi-robot-industrial::before{content:"\FB21"}.mdi-robot-vacuum::before{content:"\F70C"}.mdi-robot-vacuum-variant::before{content:"\F907"}.mdi-rocket::before{content:"\F463"}.mdi-roller-skate::before{content:"\FD07"}.mdi-rollerblade::before{content:"\FD08"}.mdi-rollupjs::before{content:"\FB9C"}.mdi-roman-numeral-1::before{content:"\F00B3"}.mdi-roman-numeral-10::before{content:"\F00BC"}.mdi-roman-numeral-2::before{content:"\F00B4"}.mdi-roman-numeral-3::before{content:"\F00B5"}.mdi-roman-numeral-4::before{content:"\F00B6"}.mdi-roman-numeral-5::before{content:"\F00B7"}.mdi-roman-numeral-6::before{content:"\F00B8"}.mdi-roman-numeral-7::before{content:"\F00B9"}.mdi-roman-numeral-8::before{content:"\F00BA"}.mdi-roman-numeral-9::before{content:"\F00BB"}.mdi-room-service::before{content:"\F88C"}.mdi-room-service-outline::before{content:"\FD73"}.mdi-rotate-3d::before{content:"\FEE4"}.mdi-rotate-3d-variant::before{content:"\F464"}.mdi-rotate-left::before{content:"\F465"}.mdi-rotate-left-variant::before{content:"\F466"}.mdi-rotate-orbit::before{content:"\FD74"}.mdi-rotate-right::before{content:"\F467"}.mdi-rotate-right-variant::before{content:"\F468"}.mdi-rounded-corner::before{content:"\F607"}.mdi-router-wireless::before{content:"\F469"}.mdi-router-wireless-settings::before{content:"\FA68"}.mdi-routes::before{content:"\F46A"}.mdi-routes-clock::before{content:"\F007B"}.mdi-rowing::before{content:"\F608"}.mdi-rss::before{content:"\F46B"}.mdi-rss-box::before{content:"\F46C"}.mdi-rss-off::before{content:"\FF3E"}.mdi-ruby::before{content:"\FD09"}.mdi-rugby::before{content:"\FD75"}.mdi-ruler::before{content:"\F46D"}.mdi-ruler-square::before{content:"\FC9E"}.mdi-ruler-square-compass::before{content:"\FEDB"}.mdi-run::before{content:"\F70D"}.mdi-run-fast::before{content:"\F46E"}.mdi-sack::before{content:"\FD0A"}.mdi-sack-percent::before{content:"\FD0B"}.mdi-safe::before{content:"\FA69"}.mdi-safety-goggles::before{content:"\FD0C"}.mdi-sailing::before{content:"\FEE5"}.mdi-sale::before{content:"\F46F"}.mdi-salesforce::before{content:"\F88D"}.mdi-sass::before{content:"\F7EB"}.mdi-satellite::before{content:"\F470"}.mdi-satellite-uplink::before{content:"\F908"}.mdi-satellite-variant::before{content:"\F471"}.mdi-sausage::before{content:"\F8B9"}.mdi-saw-blade::before{content:"\FE44"}.mdi-saxophone::before{content:"\F609"}.mdi-scale::before{content:"\F472"}.mdi-scale-balance::before{content:"\F5D1"}.mdi-scale-bathroom::before{content:"\F473"}.mdi-scale-off::before{content:"\F007C"}.mdi-scanner::before{content:"\F6AA"}.mdi-scanner-off::before{content:"\F909"}.mdi-scatter-plot::before{content:"\FEE6"}.mdi-scatter-plot-outline::before{content:"\FEE7"}.mdi-school::before{content:"\F474"}.mdi-school-outline::before{content:"\F01AB"}.mdi-scissors-cutting::before{content:"\FA6A"}.mdi-screen-rotation::before{content:"\F475"}.mdi-screen-rotation-lock::before{content:"\F476"}.mdi-screw-flat-top::before{content:"\FDCF"}.mdi-screw-lag::before{content:"\FE54"}.mdi-screw-machine-flat-top::before{content:"\FE55"}.mdi-screw-machine-round-top::before{content:"\FE56"}.mdi-screw-round-top::before{content:"\FE57"}.mdi-screwdriver::before{content:"\F477"}.mdi-script::before{content:"\FB9D"}.mdi-script-outline::before{content:"\F478"}.mdi-script-text::before{content:"\FB9E"}.mdi-script-text-outline::before{content:"\FB9F"}.mdi-sd::before{content:"\F479"}.mdi-seal::before{content:"\F47A"}.mdi-seal-variant::before{content:"\FFFA"}.mdi-search-web::before{content:"\F70E"}.mdi-seat::before{content:"\FC9F"}.mdi-seat-flat::before{content:"\F47B"}.mdi-seat-flat-angled::before{content:"\F47C"}.mdi-seat-individual-suite::before{content:"\F47D"}.mdi-seat-legroom-extra::before{content:"\F47E"}.mdi-seat-legroom-normal::before{content:"\F47F"}.mdi-seat-legroom-reduced::before{content:"\F480"}.mdi-seat-outline::before{content:"\FCA0"}.mdi-seat-recline-extra::before{content:"\F481"}.mdi-seat-recline-normal::before{content:"\F482"}.mdi-seatbelt::before{content:"\FCA1"}.mdi-security::before{content:"\F483"}.mdi-security-network::before{content:"\F484"}.mdi-seed::before{content:"\FE45"}.mdi-seed-outline::before{content:"\FE46"}.mdi-segment::before{content:"\FEE8"}.mdi-select::before{content:"\F485"}.mdi-select-all::before{content:"\F486"}.mdi-select-color::before{content:"\FD0D"}.mdi-select-compare::before{content:"\FAD8"}.mdi-select-drag::before{content:"\FA6B"}.mdi-select-group::before{content:"\FF9F"}.mdi-select-inverse::before{content:"\F487"}.mdi-select-off::before{content:"\F488"}.mdi-select-place::before{content:"\FFFB"}.mdi-selection::before{content:"\F489"}.mdi-selection-drag::before{content:"\FA6C"}.mdi-selection-ellipse::before{content:"\FD0E"}.mdi-selection-ellipse-arrow-inside::before{content:"\FF3F"}.mdi-selection-off::before{content:"\F776"}.mdi-send::before{content:"\F48A"}.mdi-send-check::before{content:"\F018C"}.mdi-send-check-outline::before{content:"\F018D"}.mdi-send-circle::before{content:"\FE58"}.mdi-send-circle-outline::before{content:"\FE59"}.mdi-send-clock::before{content:"\F018E"}.mdi-send-clock-outline::before{content:"\F018F"}.mdi-send-lock::before{content:"\F7EC"}.mdi-send-lock-outline::before{content:"\F0191"}.mdi-send-outline::before{content:"\F0190"}.mdi-serial-port::before{content:"\F65C"}.mdi-server::before{content:"\F48B"}.mdi-server-minus::before{content:"\F48C"}.mdi-server-network::before{content:"\F48D"}.mdi-server-network-off::before{content:"\F48E"}.mdi-server-off::before{content:"\F48F"}.mdi-server-plus::before{content:"\F490"}.mdi-server-remove::before{content:"\F491"}.mdi-server-security::before{content:"\F492"}.mdi-set-all::before{content:"\F777"}.mdi-set-center::before{content:"\F778"}.mdi-set-center-right::before{content:"\F779"}.mdi-set-left::before{content:"\F77A"}.mdi-set-left-center::before{content:"\F77B"}.mdi-set-left-right::before{content:"\F77C"}.mdi-set-none::before{content:"\F77D"}.mdi-set-right::before{content:"\F77E"}.mdi-set-top-box::before{content:"\F99E"}.mdi-settings::before{content:"\F493"}.mdi-settings-box::before{content:"\F494"}.mdi-settings-helper::before{content:"\FA6D"}.mdi-settings-outline::before{content:"\F8BA"}.mdi-settings-transfer::before{content:"\F007D"}.mdi-settings-transfer-outline::before{content:"\F007E"}.mdi-shaker::before{content:"\F0139"}.mdi-shaker-outline::before{content:"\F013A"}.mdi-shape::before{content:"\F830"}.mdi-shape-circle-plus::before{content:"\F65D"}.mdi-shape-outline::before{content:"\F831"}.mdi-shape-plus::before{content:"\F495"}.mdi-shape-polygon-plus::before{content:"\F65E"}.mdi-shape-rectangle-plus::before{content:"\F65F"}.mdi-shape-square-plus::before{content:"\F660"}.mdi-share::before{content:"\F496"}.mdi-share-off::before{content:"\FF40"}.mdi-share-off-outline::before{content:"\FF41"}.mdi-share-outline::before{content:"\F931"}.mdi-share-variant::before{content:"\F497"}.mdi-sheep::before{content:"\FCA2"}.mdi-shield::before{content:"\F498"}.mdi-shield-account::before{content:"\F88E"}.mdi-shield-account-outline::before{content:"\FA11"}.mdi-shield-airplane::before{content:"\F6BA"}.mdi-shield-airplane-outline::before{content:"\FCA3"}.mdi-shield-alert::before{content:"\FEE9"}.mdi-shield-alert-outline::before{content:"\FEEA"}.mdi-shield-car::before{content:"\FFA0"}.mdi-shield-check::before{content:"\F565"}.mdi-shield-check-outline::before{content:"\FCA4"}.mdi-shield-cross::before{content:"\FCA5"}.mdi-shield-cross-outline::before{content:"\FCA6"}.mdi-shield-half-full::before{content:"\F77F"}.mdi-shield-home::before{content:"\F689"}.mdi-shield-home-outline::before{content:"\FCA7"}.mdi-shield-key::before{content:"\FBA0"}.mdi-shield-key-outline::before{content:"\FBA1"}.mdi-shield-link-variant::before{content:"\FD0F"}.mdi-shield-link-variant-outline::before{content:"\FD10"}.mdi-shield-lock::before{content:"\F99C"}.mdi-shield-lock-outline::before{content:"\FCA8"}.mdi-shield-off::before{content:"\F99D"}.mdi-shield-off-outline::before{content:"\F99B"}.mdi-shield-outline::before{content:"\F499"}.mdi-shield-plus::before{content:"\FAD9"}.mdi-shield-plus-outline::before{content:"\FADA"}.mdi-shield-remove::before{content:"\FADB"}.mdi-shield-remove-outline::before{content:"\FADC"}.mdi-shield-search::before{content:"\FD76"}.mdi-shield-star::before{content:"\F0166"}.mdi-shield-star-outline::before{content:"\F0167"}.mdi-shield-sun::before{content:"\F007F"}.mdi-shield-sun-outline::before{content:"\F0080"}.mdi-ship-wheel::before{content:"\F832"}.mdi-shoe-formal::before{content:"\FB22"}.mdi-shoe-heel::before{content:"\FB23"}.mdi-shoe-print::before{content:"\FE5A"}.mdi-shopify::before{content:"\FADD"}.mdi-shopping::before{content:"\F49A"}.mdi-shopping-music::before{content:"\F49B"}.mdi-shopping-search::before{content:"\FFA1"}.mdi-shovel::before{content:"\F70F"}.mdi-shovel-off::before{content:"\F710"}.mdi-shower::before{content:"\F99F"}.mdi-shower-head::before{content:"\F9A0"}.mdi-shredder::before{content:"\F49C"}.mdi-shuffle::before{content:"\F49D"}.mdi-shuffle-disabled::before{content:"\F49E"}.mdi-shuffle-variant::before{content:"\F49F"}.mdi-sigma::before{content:"\F4A0"}.mdi-sigma-lower::before{content:"\F62B"}.mdi-sign-caution::before{content:"\F4A1"}.mdi-sign-direction::before{content:"\F780"}.mdi-sign-direction-minus::before{content:"\F0022"}.mdi-sign-direction-plus::before{content:"\FFFD"}.mdi-sign-direction-remove::before{content:"\FFFE"}.mdi-sign-real-estate::before{content:"\F0143"}.mdi-sign-text::before{content:"\F781"}.mdi-signal::before{content:"\F4A2"}.mdi-signal-2g::before{content:"\F711"}.mdi-signal-3g::before{content:"\F712"}.mdi-signal-4g::before{content:"\F713"}.mdi-signal-5g::before{content:"\FA6E"}.mdi-signal-cellular-1::before{content:"\F8BB"}.mdi-signal-cellular-2::before{content:"\F8BC"}.mdi-signal-cellular-3::before{content:"\F8BD"}.mdi-signal-cellular-outline::before{content:"\F8BE"}.mdi-signal-distance-variant::before{content:"\FE47"}.mdi-signal-hspa::before{content:"\F714"}.mdi-signal-hspa-plus::before{content:"\F715"}.mdi-signal-off::before{content:"\F782"}.mdi-signal-variant::before{content:"\F60A"}.mdi-signature::before{content:"\FE5B"}.mdi-signature-freehand::before{content:"\FE5C"}.mdi-signature-image::before{content:"\FE5D"}.mdi-signature-text::before{content:"\FE5E"}.mdi-silo::before{content:"\FB24"}.mdi-silverware::before{content:"\F4A3"}.mdi-silverware-clean::before{content:"\FFFF"}.mdi-silverware-fork::before{content:"\F4A4"}.mdi-silverware-fork-knife::before{content:"\FA6F"}.mdi-silverware-spoon::before{content:"\F4A5"}.mdi-silverware-variant::before{content:"\F4A6"}.mdi-sim::before{content:"\F4A7"}.mdi-sim-alert::before{content:"\F4A8"}.mdi-sim-off::before{content:"\F4A9"}.mdi-sina-weibo::before{content:"\FADE"}.mdi-sitemap::before{content:"\F4AA"}.mdi-skate::before{content:"\FD11"}.mdi-skew-less::before{content:"\FD12"}.mdi-skew-more::before{content:"\FD13"}.mdi-skip-backward::before{content:"\F4AB"}.mdi-skip-backward-outline::before{content:"\FF42"}.mdi-skip-forward::before{content:"\F4AC"}.mdi-skip-forward-outline::before{content:"\FF43"}.mdi-skip-next::before{content:"\F4AD"}.mdi-skip-next-circle::before{content:"\F661"}.mdi-skip-next-circle-outline::before{content:"\F662"}.mdi-skip-next-outline::before{content:"\FF44"}.mdi-skip-previous::before{content:"\F4AE"}.mdi-skip-previous-circle::before{content:"\F663"}.mdi-skip-previous-circle-outline::before{content:"\F664"}.mdi-skip-previous-outline::before{content:"\FF45"}.mdi-skull::before{content:"\F68B"}.mdi-skull-crossbones::before{content:"\FBA2"}.mdi-skull-crossbones-outline::before{content:"\FBA3"}.mdi-skull-outline::before{content:"\FBA4"}.mdi-skype::before{content:"\F4AF"}.mdi-skype-business::before{content:"\F4B0"}.mdi-slack::before{content:"\F4B1"}.mdi-slackware::before{content:"\F90A"}.mdi-slash-forward::before{content:"\F0000"}.mdi-slash-forward-box::before{content:"\F0001"}.mdi-sleep::before{content:"\F4B2"}.mdi-sleep-off::before{content:"\F4B3"}.mdi-slope-downhill::before{content:"\FE5F"}.mdi-slope-uphill::before{content:"\FE60"}.mdi-slot-machine::before{content:"\F013F"}.mdi-slot-machine-outline::before{content:"\F0140"}.mdi-smart-card::before{content:"\F00E8"}.mdi-smart-card-outline::before{content:"\F00E9"}.mdi-smart-card-reader::before{content:"\F00EA"}.mdi-smart-card-reader-outline::before{content:"\F00EB"}.mdi-smog::before{content:"\FA70"}.mdi-smoke-detector::before{content:"\F392"}.mdi-smoking::before{content:"\F4B4"}.mdi-smoking-off::before{content:"\F4B5"}.mdi-snapchat::before{content:"\F4B6"}.mdi-snowflake::before{content:"\F716"}.mdi-snowflake-alert::before{content:"\FF46"}.mdi-snowflake-variant::before{content:"\FF47"}.mdi-snowman::before{content:"\F4B7"}.mdi-soccer::before{content:"\F4B8"}.mdi-soccer-field::before{content:"\F833"}.mdi-sofa::before{content:"\F4B9"}.mdi-solar-panel::before{content:"\FD77"}.mdi-solar-panel-large::before{content:"\FD78"}.mdi-solar-power::before{content:"\FA71"}.mdi-soldering-iron::before{content:"\F00BD"}.mdi-solid::before{content:"\F68C"}.mdi-sort::before{content:"\F4BA"}.mdi-sort-alphabetical::before{content:"\F4BB"}.mdi-sort-alphabetical-ascending::before{content:"\F0173"}.mdi-sort-alphabetical-descending::before{content:"\F0174"}.mdi-sort-ascending::before{content:"\F4BC"}.mdi-sort-descending::before{content:"\F4BD"}.mdi-sort-numeric::before{content:"\F4BE"}.mdi-sort-variant::before{content:"\F4BF"}.mdi-sort-variant-lock::before{content:"\FCA9"}.mdi-sort-variant-lock-open::before{content:"\FCAA"}.mdi-sort-variant-remove::before{content:"\F0172"}.mdi-soundcloud::before{content:"\F4C0"}.mdi-source-branch::before{content:"\F62C"}.mdi-source-commit::before{content:"\F717"}.mdi-source-commit-end::before{content:"\F718"}.mdi-source-commit-end-local::before{content:"\F719"}.mdi-source-commit-local::before{content:"\F71A"}.mdi-source-commit-next-local::before{content:"\F71B"}.mdi-source-commit-start::before{content:"\F71C"}.mdi-source-commit-start-next-local::before{content:"\F71D"}.mdi-source-fork::before{content:"\F4C1"}.mdi-source-merge::before{content:"\F62D"}.mdi-source-pull::before{content:"\F4C2"}.mdi-source-repository::before{content:"\FCAB"}.mdi-source-repository-multiple::before{content:"\FCAC"}.mdi-soy-sauce::before{content:"\F7ED"}.mdi-spa::before{content:"\FCAD"}.mdi-spa-outline::before{content:"\FCAE"}.mdi-space-invaders::before{content:"\FBA5"}.mdi-spade::before{content:"\FE48"}.mdi-speaker::before{content:"\F4C3"}.mdi-speaker-bluetooth::before{content:"\F9A1"}.mdi-speaker-multiple::before{content:"\FD14"}.mdi-speaker-off::before{content:"\F4C4"}.mdi-speaker-wireless::before{content:"\F71E"}.mdi-speedometer::before{content:"\F4C5"}.mdi-speedometer-medium::before{content:"\FFA2"}.mdi-speedometer-slow::before{content:"\FFA3"}.mdi-spellcheck::before{content:"\F4C6"}.mdi-spider-web::before{content:"\FBA6"}.mdi-spotify::before{content:"\F4C7"}.mdi-spotlight::before{content:"\F4C8"}.mdi-spotlight-beam::before{content:"\F4C9"}.mdi-spray::before{content:"\F665"}.mdi-spray-bottle::before{content:"\FADF"}.mdi-sprinkler::before{content:"\F0081"}.mdi-sprinkler-variant::before{content:"\F0082"}.mdi-sprout::before{content:"\FE49"}.mdi-sprout-outline::before{content:"\FE4A"}.mdi-square::before{content:"\F763"}.mdi-square-edit-outline::before{content:"\F90B"}.mdi-square-inc::before{content:"\F4CA"}.mdi-square-inc-cash::before{content:"\F4CB"}.mdi-square-medium::before{content:"\FA12"}.mdi-square-medium-outline::before{content:"\FA13"}.mdi-square-outline::before{content:"\F762"}.mdi-square-root::before{content:"\F783"}.mdi-square-root-box::before{content:"\F9A2"}.mdi-square-small::before{content:"\FA14"}.mdi-squeegee::before{content:"\FAE0"}.mdi-ssh::before{content:"\F8BF"}.mdi-stack-exchange::before{content:"\F60B"}.mdi-stack-overflow::before{content:"\F4CC"}.mdi-stadium::before{content:"\F001A"}.mdi-stadium-variant::before{content:"\F71F"}.mdi-stairs::before{content:"\F4CD"}.mdi-stamper::before{content:"\FD15"}.mdi-standard-definition::before{content:"\F7EE"}.mdi-star::before{content:"\F4CE"}.mdi-star-box::before{content:"\FA72"}.mdi-star-box-outline::before{content:"\FA73"}.mdi-star-circle::before{content:"\F4CF"}.mdi-star-circle-outline::before{content:"\F9A3"}.mdi-star-face::before{content:"\F9A4"}.mdi-star-four-points::before{content:"\FAE1"}.mdi-star-four-points-outline::before{content:"\FAE2"}.mdi-star-half::before{content:"\F4D0"}.mdi-star-off::before{content:"\F4D1"}.mdi-star-outline::before{content:"\F4D2"}.mdi-star-three-points::before{content:"\FAE3"}.mdi-star-three-points-outline::before{content:"\FAE4"}.mdi-steam::before{content:"\F4D3"}.mdi-steam-box::before{content:"\F90C"}.mdi-steering::before{content:"\F4D4"}.mdi-steering-off::before{content:"\F90D"}.mdi-step-backward::before{content:"\F4D5"}.mdi-step-backward-2::before{content:"\F4D6"}.mdi-step-forward::before{content:"\F4D7"}.mdi-step-forward-2::before{content:"\F4D8"}.mdi-stethoscope::before{content:"\F4D9"}.mdi-sticker::before{content:"\F5D0"}.mdi-sticker-emoji::before{content:"\F784"}.mdi-stocking::before{content:"\F4DA"}.mdi-stomach::before{content:"\F00BE"}.mdi-stop::before{content:"\F4DB"}.mdi-stop-circle::before{content:"\F666"}.mdi-stop-circle-outline::before{content:"\F667"}.mdi-store::before{content:"\F4DC"}.mdi-store-24-hour::before{content:"\F4DD"}.mdi-storefront::before{content:"\F00EC"}.mdi-stove::before{content:"\F4DE"}.mdi-strava::before{content:"\FB25"}.mdi-stretch-to-page::before{content:"\FF48"}.mdi-stretch-to-page-outline::before{content:"\FF49"}.mdi-subdirectory-arrow-left::before{content:"\F60C"}.mdi-subdirectory-arrow-right::before{content:"\F60D"}.mdi-subtitles::before{content:"\FA15"}.mdi-subtitles-outline::before{content:"\FA16"}.mdi-subway::before{content:"\F6AB"}.mdi-subway-alert-variant::before{content:"\FD79"}.mdi-subway-variant::before{content:"\F4DF"}.mdi-summit::before{content:"\F785"}.mdi-sunglasses::before{content:"\F4E0"}.mdi-surround-sound::before{content:"\F5C5"}.mdi-surround-sound-2-0::before{content:"\F7EF"}.mdi-surround-sound-3-1::before{content:"\F7F0"}.mdi-surround-sound-5-1::before{content:"\F7F1"}.mdi-surround-sound-7-1::before{content:"\F7F2"}.mdi-svg::before{content:"\F720"}.mdi-swap-horizontal::before{content:"\F4E1"}.mdi-swap-horizontal-bold::before{content:"\FBA9"}.mdi-swap-horizontal-circle::before{content:"\F0002"}.mdi-swap-horizontal-circle-outline::before{content:"\F0003"}.mdi-swap-horizontal-variant::before{content:"\F8C0"}.mdi-swap-vertical::before{content:"\F4E2"}.mdi-swap-vertical-bold::before{content:"\FBAA"}.mdi-swap-vertical-circle::before{content:"\F0004"}.mdi-swap-vertical-circle-outline::before{content:"\F0005"}.mdi-swap-vertical-variant::before{content:"\F8C1"}.mdi-swim::before{content:"\F4E3"}.mdi-switch::before{content:"\F4E4"}.mdi-sword::before{content:"\F4E5"}.mdi-sword-cross::before{content:"\F786"}.mdi-symfony::before{content:"\FAE5"}.mdi-sync::before{content:"\F4E6"}.mdi-sync-alert::before{content:"\F4E7"}.mdi-sync-off::before{content:"\F4E8"}.mdi-tab::before{content:"\F4E9"}.mdi-tab-minus::before{content:"\FB26"}.mdi-tab-plus::before{content:"\F75B"}.mdi-tab-remove::before{content:"\FB27"}.mdi-tab-unselected::before{content:"\F4EA"}.mdi-table::before{content:"\F4EB"}.mdi-table-border::before{content:"\FA17"}.mdi-table-chair::before{content:"\F0083"}.mdi-table-column::before{content:"\F834"}.mdi-table-column-plus-after::before{content:"\F4EC"}.mdi-table-column-plus-before::before{content:"\F4ED"}.mdi-table-column-remove::before{content:"\F4EE"}.mdi-table-column-width::before{content:"\F4EF"}.mdi-table-edit::before{content:"\F4F0"}.mdi-table-eye::before{content:"\F00BF"}.mdi-table-large::before{content:"\F4F1"}.mdi-table-large-plus::before{content:"\FFA4"}.mdi-table-large-remove::before{content:"\FFA5"}.mdi-table-merge-cells::before{content:"\F9A5"}.mdi-table-of-contents::before{content:"\F835"}.mdi-table-plus::before{content:"\FA74"}.mdi-table-remove::before{content:"\FA75"}.mdi-table-row::before{content:"\F836"}.mdi-table-row-height::before{content:"\F4F2"}.mdi-table-row-plus-after::before{content:"\F4F3"}.mdi-table-row-plus-before::before{content:"\F4F4"}.mdi-table-row-remove::before{content:"\F4F5"}.mdi-table-search::before{content:"\F90E"}.mdi-table-settings::before{content:"\F837"}.mdi-table-tennis::before{content:"\FE4B"}.mdi-tablet::before{content:"\F4F6"}.mdi-tablet-android::before{content:"\F4F7"}.mdi-tablet-cellphone::before{content:"\F9A6"}.mdi-tablet-dashboard::before{content:"\FEEB"}.mdi-tablet-ipad::before{content:"\F4F8"}.mdi-taco::before{content:"\F761"}.mdi-tag::before{content:"\F4F9"}.mdi-tag-faces::before{content:"\F4FA"}.mdi-tag-heart::before{content:"\F68A"}.mdi-tag-heart-outline::before{content:"\FBAB"}.mdi-tag-minus::before{content:"\F90F"}.mdi-tag-multiple::before{content:"\F4FB"}.mdi-tag-outline::before{content:"\F4FC"}.mdi-tag-plus::before{content:"\F721"}.mdi-tag-remove::before{content:"\F722"}.mdi-tag-text-outline::before{content:"\F4FD"}.mdi-tank::before{content:"\FD16"}.mdi-tanker-truck::before{content:"\F0006"}.mdi-tape-measure::before{content:"\FB28"}.mdi-target::before{content:"\F4FE"}.mdi-target-account::before{content:"\FBAC"}.mdi-target-variant::before{content:"\FA76"}.mdi-taxi::before{content:"\F4FF"}.mdi-tea::before{content:"\FD7A"}.mdi-tea-outline::before{content:"\FD7B"}.mdi-teach::before{content:"\F88F"}.mdi-teamviewer::before{content:"\F500"}.mdi-telegram::before{content:"\F501"}.mdi-telescope::before{content:"\FB29"}.mdi-television::before{content:"\F502"}.mdi-television-box::before{content:"\F838"}.mdi-television-classic::before{content:"\F7F3"}.mdi-television-classic-off::before{content:"\F839"}.mdi-television-clean::before{content:"\F013B"}.mdi-television-guide::before{content:"\F503"}.mdi-television-off::before{content:"\F83A"}.mdi-television-pause::before{content:"\FFA6"}.mdi-television-play::before{content:"\FEEC"}.mdi-television-stop::before{content:"\FFA7"}.mdi-temperature-celsius::before{content:"\F504"}.mdi-temperature-fahrenheit::before{content:"\F505"}.mdi-temperature-kelvin::before{content:"\F506"}.mdi-tennis::before{content:"\FD7C"}.mdi-tennis-ball::before{content:"\F507"}.mdi-tent::before{content:"\F508"}.mdi-terraform::before{content:"\F0084"}.mdi-terrain::before{content:"\F509"}.mdi-test-tube::before{content:"\F668"}.mdi-test-tube-empty::before{content:"\F910"}.mdi-test-tube-off::before{content:"\F911"}.mdi-text::before{content:"\F9A7"}.mdi-text-recognition::before{content:"\F0168"}.mdi-text-shadow::before{content:"\F669"}.mdi-text-short::before{content:"\F9A8"}.mdi-text-subject::before{content:"\F9A9"}.mdi-text-to-speech::before{content:"\F50A"}.mdi-text-to-speech-off::before{content:"\F50B"}.mdi-textarea::before{content:"\F00C0"}.mdi-textbox::before{content:"\F60E"}.mdi-textbox-password::before{content:"\F7F4"}.mdi-texture::before{content:"\F50C"}.mdi-texture-box::before{content:"\F0007"}.mdi-theater::before{content:"\F50D"}.mdi-theme-light-dark::before{content:"\F50E"}.mdi-thermometer::before{content:"\F50F"}.mdi-thermometer-alert::before{content:"\FE61"}.mdi-thermometer-chevron-down::before{content:"\FE62"}.mdi-thermometer-chevron-up::before{content:"\FE63"}.mdi-thermometer-high::before{content:"\F00ED"}.mdi-thermometer-lines::before{content:"\F510"}.mdi-thermometer-low::before{content:"\F00EE"}.mdi-thermometer-minus::before{content:"\FE64"}.mdi-thermometer-plus::before{content:"\FE65"}.mdi-thermostat::before{content:"\F393"}.mdi-thermostat-box::before{content:"\F890"}.mdi-thought-bubble::before{content:"\F7F5"}.mdi-thought-bubble-outline::before{content:"\F7F6"}.mdi-thumb-down::before{content:"\F511"}.mdi-thumb-down-outline::before{content:"\F512"}.mdi-thumb-up::before{content:"\F513"}.mdi-thumb-up-outline::before{content:"\F514"}.mdi-thumbs-up-down::before{content:"\F515"}.mdi-ticket::before{content:"\F516"}.mdi-ticket-account::before{content:"\F517"}.mdi-ticket-confirmation::before{content:"\F518"}.mdi-ticket-outline::before{content:"\F912"}.mdi-ticket-percent::before{content:"\F723"}.mdi-tie::before{content:"\F519"}.mdi-tilde::before{content:"\F724"}.mdi-timelapse::before{content:"\F51A"}.mdi-timeline::before{content:"\FBAD"}.mdi-timeline-alert::before{content:"\FFB2"}.mdi-timeline-alert-outline::before{content:"\FFB5"}.mdi-timeline-help::before{content:"\FFB6"}.mdi-timeline-help-outline::before{content:"\FFB7"}.mdi-timeline-outline::before{content:"\FBAE"}.mdi-timeline-plus::before{content:"\FFB3"}.mdi-timeline-plus-outline::before{content:"\FFB4"}.mdi-timeline-text::before{content:"\FBAF"}.mdi-timeline-text-outline::before{content:"\FBB0"}.mdi-timer::before{content:"\F51B"}.mdi-timer-10::before{content:"\F51C"}.mdi-timer-3::before{content:"\F51D"}.mdi-timer-off::before{content:"\F51E"}.mdi-timer-sand::before{content:"\F51F"}.mdi-timer-sand-empty::before{content:"\F6AC"}.mdi-timer-sand-full::before{content:"\F78B"}.mdi-timetable::before{content:"\F520"}.mdi-toaster::before{content:"\F0085"}.mdi-toaster-oven::before{content:"\FCAF"}.mdi-toggle-switch::before{content:"\F521"}.mdi-toggle-switch-off::before{content:"\F522"}.mdi-toggle-switch-off-outline::before{content:"\FA18"}.mdi-toggle-switch-outline::before{content:"\FA19"}.mdi-toilet::before{content:"\F9AA"}.mdi-toolbox::before{content:"\F9AB"}.mdi-toolbox-outline::before{content:"\F9AC"}.mdi-tools::before{content:"\F0086"}.mdi-tooltip::before{content:"\F523"}.mdi-tooltip-account::before{content:"\F00C"}.mdi-tooltip-edit::before{content:"\F524"}.mdi-tooltip-image::before{content:"\F525"}.mdi-tooltip-image-outline::before{content:"\FBB1"}.mdi-tooltip-outline::before{content:"\F526"}.mdi-tooltip-plus::before{content:"\FBB2"}.mdi-tooltip-plus-outline::before{content:"\F527"}.mdi-tooltip-text::before{content:"\F528"}.mdi-tooltip-text-outline::before{content:"\FBB3"}.mdi-tooth::before{content:"\F8C2"}.mdi-tooth-outline::before{content:"\F529"}.mdi-toothbrush::before{content:"\F0154"}.mdi-toothbrush-electric::before{content:"\F0157"}.mdi-toothbrush-paste::before{content:"\F0155"}.mdi-tor::before{content:"\F52A"}.mdi-tortoise::before{content:"\FD17"}.mdi-tournament::before{content:"\F9AD"}.mdi-tower-beach::before{content:"\F680"}.mdi-tower-fire::before{content:"\F681"}.mdi-towing::before{content:"\F83B"}.mdi-track-light::before{content:"\F913"}.mdi-trackpad::before{content:"\F7F7"}.mdi-trackpad-lock::before{content:"\F932"}.mdi-tractor::before{content:"\F891"}.mdi-trademark::before{content:"\FA77"}.mdi-traffic-light::before{content:"\F52B"}.mdi-train::before{content:"\F52C"}.mdi-train-car::before{content:"\FBB4"}.mdi-train-variant::before{content:"\F8C3"}.mdi-tram::before{content:"\F52D"}.mdi-tram-side::before{content:"\F0008"}.mdi-transcribe::before{content:"\F52E"}.mdi-transcribe-close::before{content:"\F52F"}.mdi-transfer::before{content:"\F0087"}.mdi-transfer-down::before{content:"\FD7D"}.mdi-transfer-left::before{content:"\FD7E"}.mdi-transfer-right::before{content:"\F530"}.mdi-transfer-up::before{content:"\FD7F"}.mdi-transit-connection::before{content:"\FD18"}.mdi-transit-connection-variant::before{content:"\FD19"}.mdi-transit-detour::before{content:"\FFA8"}.mdi-transit-transfer::before{content:"\F6AD"}.mdi-transition::before{content:"\F914"}.mdi-transition-masked::before{content:"\F915"}.mdi-translate::before{content:"\F5CA"}.mdi-translate-off::before{content:"\FE66"}.mdi-transmission-tower::before{content:"\FD1A"}.mdi-trash-can::before{content:"\FA78"}.mdi-trash-can-outline::before{content:"\FA79"}.mdi-treasure-chest::before{content:"\F725"}.mdi-tree::before{content:"\F531"}.mdi-tree-outline::before{content:"\FE4C"}.mdi-trello::before{content:"\F532"}.mdi-trending-down::before{content:"\F533"}.mdi-trending-neutral::before{content:"\F534"}.mdi-trending-up::before{content:"\F535"}.mdi-triangle::before{content:"\F536"}.mdi-triangle-outline::before{content:"\F537"}.mdi-triforce::before{content:"\FBB5"}.mdi-trophy::before{content:"\F538"}.mdi-trophy-award::before{content:"\F539"}.mdi-trophy-broken::before{content:"\FD80"}.mdi-trophy-outline::before{content:"\F53A"}.mdi-trophy-variant::before{content:"\F53B"}.mdi-trophy-variant-outline::before{content:"\F53C"}.mdi-truck::before{content:"\F53D"}.mdi-truck-check::before{content:"\FCB0"}.mdi-truck-delivery::before{content:"\F53E"}.mdi-truck-fast::before{content:"\F787"}.mdi-truck-trailer::before{content:"\F726"}.mdi-trumpet::before{content:"\F00C1"}.mdi-tshirt-crew::before{content:"\FA7A"}.mdi-tshirt-crew-outline::before{content:"\F53F"}.mdi-tshirt-v::before{content:"\FA7B"}.mdi-tshirt-v-outline::before{content:"\F540"}.mdi-tumble-dryer::before{content:"\F916"}.mdi-tumblr::before{content:"\F541"}.mdi-tumblr-box::before{content:"\F917"}.mdi-tumblr-reblog::before{content:"\F542"}.mdi-tune::before{content:"\F62E"}.mdi-tune-vertical::before{content:"\F66A"}.mdi-turnstile::before{content:"\FCB1"}.mdi-turnstile-outline::before{content:"\FCB2"}.mdi-turtle::before{content:"\FCB3"}.mdi-twitch::before{content:"\F543"}.mdi-twitter::before{content:"\F544"}.mdi-twitter-box::before{content:"\F545"}.mdi-twitter-circle::before{content:"\F546"}.mdi-twitter-retweet::before{content:"\F547"}.mdi-two-factor-authentication::before{content:"\F9AE"}.mdi-typewriter::before{content:"\FF4A"}.mdi-uber::before{content:"\F748"}.mdi-ubisoft::before{content:"\FBB6"}.mdi-ubuntu::before{content:"\F548"}.mdi-ufo::before{content:"\F00EF"}.mdi-ufo-outline::before{content:"\F00F0"}.mdi-ultra-high-definition::before{content:"\F7F8"}.mdi-umbraco::before{content:"\F549"}.mdi-umbrella::before{content:"\F54A"}.mdi-umbrella-closed::before{content:"\F9AF"}.mdi-umbrella-outline::before{content:"\F54B"}.mdi-undo::before{content:"\F54C"}.mdi-undo-variant::before{content:"\F54D"}.mdi-unfold-less-horizontal::before{content:"\F54E"}.mdi-unfold-less-vertical::before{content:"\F75F"}.mdi-unfold-more-horizontal::before{content:"\F54F"}.mdi-unfold-more-vertical::before{content:"\F760"}.mdi-ungroup::before{content:"\F550"}.mdi-unicode::before{content:"\FEED"}.mdi-unity::before{content:"\F6AE"}.mdi-unreal::before{content:"\F9B0"}.mdi-untappd::before{content:"\F551"}.mdi-update::before{content:"\F6AF"}.mdi-upload::before{content:"\F552"}.mdi-upload-multiple::before{content:"\F83C"}.mdi-upload-network::before{content:"\F6F5"}.mdi-upload-network-outline::before{content:"\FCB4"}.mdi-upload-off::before{content:"\F00F1"}.mdi-upload-off-outline::before{content:"\F00F2"}.mdi-upload-outline::before{content:"\FE67"}.mdi-usb::before{content:"\F553"}.mdi-valve::before{content:"\F0088"}.mdi-valve-closed::before{content:"\F0089"}.mdi-valve-open::before{content:"\F008A"}.mdi-van-passenger::before{content:"\F7F9"}.mdi-van-utility::before{content:"\F7FA"}.mdi-vanish::before{content:"\F7FB"}.mdi-variable::before{content:"\FAE6"}.mdi-variable-box::before{content:"\F013C"}.mdi-vector-arrange-above::before{content:"\F554"}.mdi-vector-arrange-below::before{content:"\F555"}.mdi-vector-bezier::before{content:"\FAE7"}.mdi-vector-circle::before{content:"\F556"}.mdi-vector-circle-variant::before{content:"\F557"}.mdi-vector-combine::before{content:"\F558"}.mdi-vector-curve::before{content:"\F559"}.mdi-vector-difference::before{content:"\F55A"}.mdi-vector-difference-ab::before{content:"\F55B"}.mdi-vector-difference-ba::before{content:"\F55C"}.mdi-vector-ellipse::before{content:"\F892"}.mdi-vector-intersection::before{content:"\F55D"}.mdi-vector-line::before{content:"\F55E"}.mdi-vector-link::before{content:"\F0009"}.mdi-vector-point::before{content:"\F55F"}.mdi-vector-polygon::before{content:"\F560"}.mdi-vector-polyline::before{content:"\F561"}.mdi-vector-radius::before{content:"\F749"}.mdi-vector-rectangle::before{content:"\F5C6"}.mdi-vector-selection::before{content:"\F562"}.mdi-vector-square::before{content:"\F001"}.mdi-vector-triangle::before{content:"\F563"}.mdi-vector-union::before{content:"\F564"}.mdi-venmo::before{content:"\F578"}.mdi-vhs::before{content:"\FA1A"}.mdi-vibrate::before{content:"\F566"}.mdi-vibrate-off::before{content:"\FCB5"}.mdi-video::before{content:"\F567"}.mdi-video-3d::before{content:"\F7FC"}.mdi-video-3d-variant::before{content:"\FEEE"}.mdi-video-4k-box::before{content:"\F83D"}.mdi-video-account::before{content:"\F918"}.mdi-video-check::before{content:"\F008B"}.mdi-video-check-outline::before{content:"\F008C"}.mdi-video-image::before{content:"\F919"}.mdi-video-input-antenna::before{content:"\F83E"}.mdi-video-input-component::before{content:"\F83F"}.mdi-video-input-hdmi::before{content:"\F840"}.mdi-video-input-scart::before{content:"\FFA9"}.mdi-video-input-svideo::before{content:"\F841"}.mdi-video-minus::before{content:"\F9B1"}.mdi-video-off::before{content:"\F568"}.mdi-video-off-outline::before{content:"\FBB7"}.mdi-video-outline::before{content:"\FBB8"}.mdi-video-plus::before{content:"\F9B2"}.mdi-video-stabilization::before{content:"\F91A"}.mdi-video-switch::before{content:"\F569"}.mdi-video-vintage::before{content:"\FA1B"}.mdi-video-wireless::before{content:"\FEEF"}.mdi-video-wireless-outline::before{content:"\FEF0"}.mdi-view-agenda::before{content:"\F56A"}.mdi-view-array::before{content:"\F56B"}.mdi-view-carousel::before{content:"\F56C"}.mdi-view-column::before{content:"\F56D"}.mdi-view-comfy::before{content:"\FE4D"}.mdi-view-compact::before{content:"\FE4E"}.mdi-view-compact-outline::before{content:"\FE4F"}.mdi-view-dashboard::before{content:"\F56E"}.mdi-view-dashboard-outline::before{content:"\FA1C"}.mdi-view-dashboard-variant::before{content:"\F842"}.mdi-view-day::before{content:"\F56F"}.mdi-view-grid::before{content:"\F570"}.mdi-view-grid-plus::before{content:"\FFAA"}.mdi-view-headline::before{content:"\F571"}.mdi-view-list::before{content:"\F572"}.mdi-view-module::before{content:"\F573"}.mdi-view-parallel::before{content:"\F727"}.mdi-view-quilt::before{content:"\F574"}.mdi-view-sequential::before{content:"\F728"}.mdi-view-split-horizontal::before{content:"\FBA7"}.mdi-view-split-vertical::before{content:"\FBA8"}.mdi-view-stream::before{content:"\F575"}.mdi-view-week::before{content:"\F576"}.mdi-vimeo::before{content:"\F577"}.mdi-violin::before{content:"\F60F"}.mdi-virtual-reality::before{content:"\F893"}.mdi-visual-studio::before{content:"\F610"}.mdi-visual-studio-code::before{content:"\FA1D"}.mdi-vk::before{content:"\F579"}.mdi-vk-box::before{content:"\F57A"}.mdi-vk-circle::before{content:"\F57B"}.mdi-vlc::before{content:"\F57C"}.mdi-voice::before{content:"\F5CB"}.mdi-voice-off::before{content:"\FEF1"}.mdi-voicemail::before{content:"\F57D"}.mdi-volleyball::before{content:"\F9B3"}.mdi-volume-high::before{content:"\F57E"}.mdi-volume-low::before{content:"\F57F"}.mdi-volume-medium::before{content:"\F580"}.mdi-volume-minus::before{content:"\F75D"}.mdi-volume-mute::before{content:"\F75E"}.mdi-volume-off::before{content:"\F581"}.mdi-volume-plus::before{content:"\F75C"}.mdi-volume-source::before{content:"\F014B"}.mdi-volume-variant-off::before{content:"\FE68"}.mdi-volume-vibrate::before{content:"\F014C"}.mdi-vote::before{content:"\FA1E"}.mdi-vote-outline::before{content:"\FA1F"}.mdi-vpn::before{content:"\F582"}.mdi-vuejs::before{content:"\F843"}.mdi-vuetify::before{content:"\FE50"}.mdi-walk::before{content:"\F583"}.mdi-wall::before{content:"\F7FD"}.mdi-wall-sconce::before{content:"\F91B"}.mdi-wall-sconce-flat::before{content:"\F91C"}.mdi-wall-sconce-variant::before{content:"\F91D"}.mdi-wallet::before{content:"\F584"}.mdi-wallet-giftcard::before{content:"\F585"}.mdi-wallet-membership::before{content:"\F586"}.mdi-wallet-outline::before{content:"\FBB9"}.mdi-wallet-plus::before{content:"\FFAB"}.mdi-wallet-plus-outline::before{content:"\FFAC"}.mdi-wallet-travel::before{content:"\F587"}.mdi-wallpaper::before{content:"\FE69"}.mdi-wan::before{content:"\F588"}.mdi-wardrobe::before{content:"\FFAD"}.mdi-wardrobe-outline::before{content:"\FFAE"}.mdi-warehouse::before{content:"\FFBB"}.mdi-washing-machine::before{content:"\F729"}.mdi-watch::before{content:"\F589"}.mdi-watch-export::before{content:"\F58A"}.mdi-watch-export-variant::before{content:"\F894"}.mdi-watch-import::before{content:"\F58B"}.mdi-watch-import-variant::before{content:"\F895"}.mdi-watch-variant::before{content:"\F896"}.mdi-watch-vibrate::before{content:"\F6B0"}.mdi-watch-vibrate-off::before{content:"\FCB6"}.mdi-water::before{content:"\F58C"}.mdi-water-boiler::before{content:"\FFAF"}.mdi-water-off::before{content:"\F58D"}.mdi-water-outline::before{content:"\FE6A"}.mdi-water-percent::before{content:"\F58E"}.mdi-water-pump::before{content:"\F58F"}.mdi-water-pump-off::before{content:"\FFB0"}.mdi-water-well::before{content:"\F008D"}.mdi-water-well-outline::before{content:"\F008E"}.mdi-watermark::before{content:"\F612"}.mdi-wave::before{content:"\FF4B"}.mdi-waves::before{content:"\F78C"}.mdi-waze::before{content:"\FBBA"}.mdi-weather-cloudy::before{content:"\F590"}.mdi-weather-cloudy-alert::before{content:"\FF4C"}.mdi-weather-cloudy-arrow-right::before{content:"\FE51"}.mdi-weather-fog::before{content:"\F591"}.mdi-weather-hail::before{content:"\F592"}.mdi-weather-hazy::before{content:"\FF4D"}.mdi-weather-hurricane::before{content:"\F897"}.mdi-weather-lightning::before{content:"\F593"}.mdi-weather-lightning-rainy::before{content:"\F67D"}.mdi-weather-night::before{content:"\F594"}.mdi-weather-night-partly-cloudy::before{content:"\FF4E"}.mdi-weather-partly-cloudy::before{content:"\F595"}.mdi-weather-partly-lightning::before{content:"\FF4F"}.mdi-weather-partly-rainy::before{content:"\FF50"}.mdi-weather-partly-snowy::before{content:"\FF51"}.mdi-weather-partly-snowy-rainy::before{content:"\FF52"}.mdi-weather-pouring::before{content:"\F596"}.mdi-weather-rainy::before{content:"\F597"}.mdi-weather-snowy::before{content:"\F598"}.mdi-weather-snowy-heavy::before{content:"\FF53"}.mdi-weather-snowy-rainy::before{content:"\F67E"}.mdi-weather-sunny::before{content:"\F599"}.mdi-weather-sunny-alert::before{content:"\FF54"}.mdi-weather-sunset::before{content:"\F59A"}.mdi-weather-sunset-down::before{content:"\F59B"}.mdi-weather-sunset-up::before{content:"\F59C"}.mdi-weather-tornado::before{content:"\FF55"}.mdi-weather-windy::before{content:"\F59D"}.mdi-weather-windy-variant::before{content:"\F59E"}.mdi-web::before{content:"\F59F"}.mdi-web-box::before{content:"\FFB1"}.mdi-webcam::before{content:"\F5A0"}.mdi-webhook::before{content:"\F62F"}.mdi-webpack::before{content:"\F72A"}.mdi-wechat::before{content:"\F611"}.mdi-weight::before{content:"\F5A1"}.mdi-weight-gram::before{content:"\FD1B"}.mdi-weight-kilogram::before{content:"\F5A2"}.mdi-weight-lifter::before{content:"\F0188"}.mdi-weight-pound::before{content:"\F9B4"}.mdi-whatsapp::before{content:"\F5A3"}.mdi-wheelchair-accessibility::before{content:"\F5A4"}.mdi-whistle::before{content:"\F9B5"}.mdi-white-balance-auto::before{content:"\F5A5"}.mdi-white-balance-incandescent::before{content:"\F5A6"}.mdi-white-balance-iridescent::before{content:"\F5A7"}.mdi-white-balance-sunny::before{content:"\F5A8"}.mdi-widgets::before{content:"\F72B"}.mdi-wifi::before{content:"\F5A9"}.mdi-wifi-off::before{content:"\F5AA"}.mdi-wifi-star::before{content:"\FE6B"}.mdi-wifi-strength-1::before{content:"\F91E"}.mdi-wifi-strength-1-alert::before{content:"\F91F"}.mdi-wifi-strength-1-lock::before{content:"\F920"}.mdi-wifi-strength-2::before{content:"\F921"}.mdi-wifi-strength-2-alert::before{content:"\F922"}.mdi-wifi-strength-2-lock::before{content:"\F923"}.mdi-wifi-strength-3::before{content:"\F924"}.mdi-wifi-strength-3-alert::before{content:"\F925"}.mdi-wifi-strength-3-lock::before{content:"\F926"}.mdi-wifi-strength-4::before{content:"\F927"}.mdi-wifi-strength-4-alert::before{content:"\F928"}.mdi-wifi-strength-4-lock::before{content:"\F929"}.mdi-wifi-strength-alert-outline::before{content:"\F92A"}.mdi-wifi-strength-lock-outline::before{content:"\F92B"}.mdi-wifi-strength-off::before{content:"\F92C"}.mdi-wifi-strength-off-outline::before{content:"\F92D"}.mdi-wifi-strength-outline::before{content:"\F92E"}.mdi-wii::before{content:"\F5AB"}.mdi-wiiu::before{content:"\F72C"}.mdi-wikipedia::before{content:"\F5AC"}.mdi-wind-turbine::before{content:"\FD81"}.mdi-window-close::before{content:"\F5AD"}.mdi-window-closed::before{content:"\F5AE"}.mdi-window-maximize::before{content:"\F5AF"}.mdi-window-minimize::before{content:"\F5B0"}.mdi-window-open::before{content:"\F5B1"}.mdi-window-restore::before{content:"\F5B2"}.mdi-window-shutter::before{content:"\F0147"}.mdi-window-shutter-alert::before{content:"\F0148"}.mdi-window-shutter-open::before{content:"\F0149"}.mdi-windows::before{content:"\F5B3"}.mdi-windows-classic::before{content:"\FA20"}.mdi-wiper::before{content:"\FAE8"}.mdi-wiper-wash::before{content:"\FD82"}.mdi-wordpress::before{content:"\F5B4"}.mdi-worker::before{content:"\F5B5"}.mdi-wrap::before{content:"\F5B6"}.mdi-wrap-disabled::before{content:"\FBBB"}.mdi-wrench::before{content:"\F5B7"}.mdi-wrench-outline::before{content:"\FBBC"}.mdi-wunderlist::before{content:"\F5B8"}.mdi-xamarin::before{content:"\F844"}.mdi-xamarin-outline::before{content:"\F845"}.mdi-xaml::before{content:"\F673"}.mdi-xbox::before{content:"\F5B9"}.mdi-xbox-controller::before{content:"\F5BA"}.mdi-xbox-controller-battery-alert::before{content:"\F74A"}.mdi-xbox-controller-battery-charging::before{content:"\FA21"}.mdi-xbox-controller-battery-empty::before{content:"\F74B"}.mdi-xbox-controller-battery-full::before{content:"\F74C"}.mdi-xbox-controller-battery-low::before{content:"\F74D"}.mdi-xbox-controller-battery-medium::before{content:"\F74E"}.mdi-xbox-controller-battery-unknown::before{content:"\F74F"}.mdi-xbox-controller-menu::before{content:"\FE52"}.mdi-xbox-controller-off::before{content:"\F5BB"}.mdi-xbox-controller-view::before{content:"\FE53"}.mdi-xda::before{content:"\F5BC"}.mdi-xing::before{content:"\F5BD"}.mdi-xing-box::before{content:"\F5BE"}.mdi-xing-circle::before{content:"\F5BF"}.mdi-xml::before{content:"\F5C0"}.mdi-xmpp::before{content:"\F7FE"}.mdi-yahoo::before{content:"\FB2A"}.mdi-yammer::before{content:"\F788"}.mdi-yeast::before{content:"\F5C1"}.mdi-yelp::before{content:"\F5C2"}.mdi-yin-yang::before{content:"\F67F"}.mdi-yoga::before{content:"\F01A7"}.mdi-youtube::before{content:"\F5C3"}.mdi-youtube-creator-studio::before{content:"\F846"}.mdi-youtube-gaming::before{content:"\F847"}.mdi-youtube-subscription::before{content:"\FD1C"}.mdi-youtube-tv::before{content:"\F448"}.mdi-z-wave::before{content:"\FAE9"}.mdi-zend::before{content:"\FAEA"}.mdi-zigbee::before{content:"\FD1D"}.mdi-zip-box::before{content:"\F5C4"}.mdi-zip-box-outline::before{content:"\F001B"}.mdi-zip-disk::before{content:"\FA22"}.mdi-zodiac-aquarius::before{content:"\FA7C"}.mdi-zodiac-aries::before{content:"\FA7D"}.mdi-zodiac-cancer::before{content:"\FA7E"}.mdi-zodiac-capricorn::before{content:"\FA7F"}.mdi-zodiac-gemini::before{content:"\FA80"}.mdi-zodiac-leo::before{content:"\FA81"}.mdi-zodiac-libra::before{content:"\FA82"}.mdi-zodiac-pisces::before{content:"\FA83"}.mdi-zodiac-sagittarius::before{content:"\FA84"}.mdi-zodiac-scorpio::before{content:"\FA85"}.mdi-zodiac-taurus::before{content:"\FA86"}.mdi-zodiac-virgo::before{content:"\FA87"}.mdi-blank::before{content:"\F68C";visibility:hidden}.mdi-18px.mdi-set,.mdi-18px.mdi:before{font-size:18px}.mdi-24px.mdi-set,.mdi-24px.mdi:before{font-size:24px}.mdi-36px.mdi-set,.mdi-36px.mdi:before{font-size:36px}.mdi-48px.mdi-set,.mdi-48px.mdi:before{font-size:48px}.mdi-dark:before{color:rgba(0,0,0,.54)}.mdi-dark.mdi-inactive:before{color:rgba(0,0,0,.26)}.mdi-light:before{color:#fff}.mdi-light.mdi-inactive:before{color:rgba(255,255,255,.3)}.mdi-rotate-45:before{-webkit-transform:rotate(45deg);transform:rotate(45deg)}.mdi-rotate-90:before{-webkit-transform:rotate(90deg);transform:rotate(90deg)}.mdi-rotate-135:before{-webkit-transform:rotate(135deg);transform:rotate(135deg)}.mdi-rotate-180:before{-webkit-transform:rotate(180deg);transform:rotate(180deg)}.mdi-rotate-225:before{-webkit-transform:rotate(225deg);transform:rotate(225deg)}.mdi-rotate-270:before{-webkit-transform:rotate(270deg);transform:rotate(270deg)}.mdi-rotate-315:before{-webkit-transform:rotate(315deg);transform:rotate(315deg)}.mdi-flip-h:before{-webkit-transform:scaleX(-1);transform:scaleX(-1);-webkit-filter:FlipH;filter:FlipH;-ms-filter:FlipH}.mdi-flip-v:before{-webkit-transform:scaleY(-1);transform:scaleY(-1);-webkit-filter:FlipV;filter:FlipV;-ms-filter:FlipV}.mdi-spin:before{-webkit-animation:mdi-spin 2s infinite linear;animation:mdi-spin 2s infinite linear}@-webkit-keyframes mdi-spin{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}@keyframes mdi-spin{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}/*!
 * Font Awesome Free 5.10.1 by @fontawesome - https://fontawesome.com
 * License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License)
 */.fa,.fab,.fad,.fal,.far,.fas{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1}.fa-lg{font-size:1.33333em;line-height:.75em;vertical-align:-.0667em}.fa-xs{font-size:.75em}.fa-sm{font-size:.875em}.fa-1x{font-size:1em}.fa-2x{font-size:2em}.fa-3x{font-size:3em}.fa-4x{font-size:4em}.fa-5x{font-size:5em}.fa-6x{font-size:6em}.fa-7x{font-size:7em}.fa-8x{font-size:8em}.fa-9x{font-size:9em}.fa-10x{font-size:10em}.fa-fw{text-align:center;width:1.25em}.fa-ul{list-style-type:none;margin-left:2.5em;padding-left:0}.fa-ul>li{position:relative}.fa-li{left:-2em;position:absolute;text-align:center;width:2em;line-height:inherit}.fa-border{border:solid .08em #eee;border-radius:.1em;padding:.2em .25em .15em}.fa-pull-left{float:left}.fa-pull-right{float:right}.fa.fa-pull-left,.fab.fa-pull-left,.fal.fa-pull-left,.far.fa-pull-left,.fas.fa-pull-left{margin-right:.3em}.fa.fa-pull-right,.fab.fa-pull-right,.fal.fa-pull-right,.far.fa-pull-right,.fas.fa-pull-right{margin-left:.3em}.fa-spin{-webkit-animation:fa-spin 2s infinite linear;animation:fa-spin 2s infinite linear}.fa-pulse{-webkit-animation:fa-spin 1s infinite steps(8);animation:fa-spin 1s infinite steps(8)}@-webkit-keyframes fa-spin{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes fa-spin{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}.fa-rotate-90{-webkit-transform:rotate(90deg);transform:rotate(90deg)}.fa-rotate-180{-webkit-transform:rotate(180deg);transform:rotate(180deg)}.fa-rotate-270{-webkit-transform:rotate(270deg);transform:rotate(270deg)}.fa-flip-horizontal{-webkit-transform:scale(-1,1);transform:scale(-1,1)}.fa-flip-vertical{-webkit-transform:scale(1,-1);transform:scale(1,-1)}.fa-flip-both,.fa-flip-horizontal.fa-flip-vertical{-webkit-transform:scale(-1,-1);transform:scale(-1,-1)}:root .fa-flip-both,:root .fa-flip-horizontal,:root .fa-flip-vertical,:root .fa-rotate-180,:root .fa-rotate-270,:root .fa-rotate-90{-webkit-filter:none;filter:none}.fa-stack{display:inline-block;height:2em;line-height:2em;position:relative;vertical-align:middle;width:2.5em}.fa-stack-1x,.fa-stack-2x{left:0;position:absolute;text-align:center;width:100%}.fa-stack-1x{line-height:inherit}.fa-stack-2x{font-size:2em}.fa-inverse{color:#fff}.fa-500px:before{content:"\f26e"}.fa-accessible-icon:before{content:"\f368"}.fa-accusoft:before{content:"\f369"}.fa-acquisitions-incorporated:before{content:"\f6af"}.fa-ad:before{content:"\f641"}.fa-address-book:before{content:"\f2b9"}.fa-address-card:before{content:"\f2bb"}.fa-adjust:before{content:"\f042"}.fa-adn:before{content:"\f170"}.fa-adobe:before{content:"\f778"}.fa-adversal:before{content:"\f36a"}.fa-affiliatetheme:before{content:"\f36b"}.fa-air-freshener:before{content:"\f5d0"}.fa-airbnb:before{content:"\f834"}.fa-algolia:before{content:"\f36c"}.fa-align-center:before{content:"\f037"}.fa-align-justify:before{content:"\f039"}.fa-align-left:before{content:"\f036"}.fa-align-right:before{content:"\f038"}.fa-alipay:before{content:"\f642"}.fa-allergies:before{content:"\f461"}.fa-amazon:before{content:"\f270"}.fa-amazon-pay:before{content:"\f42c"}.fa-ambulance:before{content:"\f0f9"}.fa-american-sign-language-interpreting:before{content:"\f2a3"}.fa-amilia:before{content:"\f36d"}.fa-anchor:before{content:"\f13d"}.fa-android:before{content:"\f17b"}.fa-angellist:before{content:"\f209"}.fa-angle-double-down:before{content:"\f103"}.fa-angle-double-left:before{content:"\f100"}.fa-angle-double-right:before{content:"\f101"}.fa-angle-double-up:before{content:"\f102"}.fa-angle-down:before{content:"\f107"}.fa-angle-left:before{content:"\f104"}.fa-angle-right:before{content:"\f105"}.fa-angle-up:before{content:"\f106"}.fa-angry:before{content:"\f556"}.fa-angrycreative:before{content:"\f36e"}.fa-angular:before{content:"\f420"}.fa-ankh:before{content:"\f644"}.fa-app-store:before{content:"\f36f"}.fa-app-store-ios:before{content:"\f370"}.fa-apper:before{content:"\f371"}.fa-apple:before{content:"\f179"}.fa-apple-alt:before{content:"\f5d1"}.fa-apple-pay:before{content:"\f415"}.fa-archive:before{content:"\f187"}.fa-archway:before{content:"\f557"}.fa-arrow-alt-circle-down:before{content:"\f358"}.fa-arrow-alt-circle-left:before{content:"\f359"}.fa-arrow-alt-circle-right:before{content:"\f35a"}.fa-arrow-alt-circle-up:before{content:"\f35b"}.fa-arrow-circle-down:before{content:"\f0ab"}.fa-arrow-circle-left:before{content:"\f0a8"}.fa-arrow-circle-right:before{content:"\f0a9"}.fa-arrow-circle-up:before{content:"\f0aa"}.fa-arrow-down:before{content:"\f063"}.fa-arrow-left:before{content:"\f060"}.fa-arrow-right:before{content:"\f061"}.fa-arrow-up:before{content:"\f062"}.fa-arrows-alt:before{content:"\f0b2"}.fa-arrows-alt-h:before{content:"\f337"}.fa-arrows-alt-v:before{content:"\f338"}.fa-artstation:before{content:"\f77a"}.fa-assistive-listening-systems:before{content:"\f2a2"}.fa-asterisk:before{content:"\f069"}.fa-asymmetrik:before{content:"\f372"}.fa-at:before{content:"\f1fa"}.fa-atlas:before{content:"\f558"}.fa-atlassian:before{content:"\f77b"}.fa-atom:before{content:"\f5d2"}.fa-audible:before{content:"\f373"}.fa-audio-description:before{content:"\f29e"}.fa-autoprefixer:before{content:"\f41c"}.fa-avianex:before{content:"\f374"}.fa-aviato:before{content:"\f421"}.fa-award:before{content:"\f559"}.fa-aws:before{content:"\f375"}.fa-baby:before{content:"\f77c"}.fa-baby-carriage:before{content:"\f77d"}.fa-backspace:before{content:"\f55a"}.fa-backward:before{content:"\f04a"}.fa-bacon:before{content:"\f7e5"}.fa-balance-scale:before{content:"\f24e"}.fa-balance-scale-left:before{content:"\f515"}.fa-balance-scale-right:before{content:"\f516"}.fa-ban:before{content:"\f05e"}.fa-band-aid:before{content:"\f462"}.fa-bandcamp:before{content:"\f2d5"}.fa-barcode:before{content:"\f02a"}.fa-bars:before{content:"\f0c9"}.fa-baseball-ball:before{content:"\f433"}.fa-basketball-ball:before{content:"\f434"}.fa-bath:before{content:"\f2cd"}.fa-battery-empty:before{content:"\f244"}.fa-battery-full:before{content:"\f240"}.fa-battery-half:before{content:"\f242"}.fa-battery-quarter:before{content:"\f243"}.fa-battery-three-quarters:before{content:"\f241"}.fa-battle-net:before{content:"\f835"}.fa-bed:before{content:"\f236"}.fa-beer:before{content:"\f0fc"}.fa-behance:before{content:"\f1b4"}.fa-behance-square:before{content:"\f1b5"}.fa-bell:before{content:"\f0f3"}.fa-bell-slash:before{content:"\f1f6"}.fa-bezier-curve:before{content:"\f55b"}.fa-bible:before{content:"\f647"}.fa-bicycle:before{content:"\f206"}.fa-biking:before{content:"\f84a"}.fa-bimobject:before{content:"\f378"}.fa-binoculars:before{content:"\f1e5"}.fa-biohazard:before{content:"\f780"}.fa-birthday-cake:before{content:"\f1fd"}.fa-bitbucket:before{content:"\f171"}.fa-bitcoin:before{content:"\f379"}.fa-bity:before{content:"\f37a"}.fa-black-tie:before{content:"\f27e"}.fa-blackberry:before{content:"\f37b"}.fa-blender:before{content:"\f517"}.fa-blender-phone:before{content:"\f6b6"}.fa-blind:before{content:"\f29d"}.fa-blog:before{content:"\f781"}.fa-blogger:before{content:"\f37c"}.fa-blogger-b:before{content:"\f37d"}.fa-bluetooth:before{content:"\f293"}.fa-bluetooth-b:before{content:"\f294"}.fa-bold:before{content:"\f032"}.fa-bolt:before{content:"\f0e7"}.fa-bomb:before{content:"\f1e2"}.fa-bone:before{content:"\f5d7"}.fa-bong:before{content:"\f55c"}.fa-book:before{content:"\f02d"}.fa-book-dead:before{content:"\f6b7"}.fa-book-medical:before{content:"\f7e6"}.fa-book-open:before{content:"\f518"}.fa-book-reader:before{content:"\f5da"}.fa-bookmark:before{content:"\f02e"}.fa-bootstrap:before{content:"\f836"}.fa-border-all:before{content:"\f84c"}.fa-border-none:before{content:"\f850"}.fa-border-style:before{content:"\f853"}.fa-bowling-ball:before{content:"\f436"}.fa-box:before{content:"\f466"}.fa-box-open:before{content:"\f49e"}.fa-boxes:before{content:"\f468"}.fa-braille:before{content:"\f2a1"}.fa-brain:before{content:"\f5dc"}.fa-bread-slice:before{content:"\f7ec"}.fa-briefcase:before{content:"\f0b1"}.fa-briefcase-medical:before{content:"\f469"}.fa-broadcast-tower:before{content:"\f519"}.fa-broom:before{content:"\f51a"}.fa-brush:before{content:"\f55d"}.fa-btc:before{content:"\f15a"}.fa-buffer:before{content:"\f837"}.fa-bug:before{content:"\f188"}.fa-building:before{content:"\f1ad"}.fa-bullhorn:before{content:"\f0a1"}.fa-bullseye:before{content:"\f140"}.fa-burn:before{content:"\f46a"}.fa-buromobelexperte:before{content:"\f37f"}.fa-bus:before{content:"\f207"}.fa-bus-alt:before{content:"\f55e"}.fa-business-time:before{content:"\f64a"}.fa-buysellads:before{content:"\f20d"}.fa-calculator:before{content:"\f1ec"}.fa-calendar:before{content:"\f133"}.fa-calendar-alt:before{content:"\f073"}.fa-calendar-check:before{content:"\f274"}.fa-calendar-day:before{content:"\f783"}.fa-calendar-minus:before{content:"\f272"}.fa-calendar-plus:before{content:"\f271"}.fa-calendar-times:before{content:"\f273"}.fa-calendar-week:before{content:"\f784"}.fa-camera:before{content:"\f030"}.fa-camera-retro:before{content:"\f083"}.fa-campground:before{content:"\f6bb"}.fa-canadian-maple-leaf:before{content:"\f785"}.fa-candy-cane:before{content:"\f786"}.fa-cannabis:before{content:"\f55f"}.fa-capsules:before{content:"\f46b"}.fa-car:before{content:"\f1b9"}.fa-car-alt:before{content:"\f5de"}.fa-car-battery:before{content:"\f5df"}.fa-car-crash:before{content:"\f5e1"}.fa-car-side:before{content:"\f5e4"}.fa-caret-down:before{content:"\f0d7"}.fa-caret-left:before{content:"\f0d9"}.fa-caret-right:before{content:"\f0da"}.fa-caret-square-down:before{content:"\f150"}.fa-caret-square-left:before{content:"\f191"}.fa-caret-square-right:before{content:"\f152"}.fa-caret-square-up:before{content:"\f151"}.fa-caret-up:before{content:"\f0d8"}.fa-carrot:before{content:"\f787"}.fa-cart-arrow-down:before{content:"\f218"}.fa-cart-plus:before{content:"\f217"}.fa-cash-register:before{content:"\f788"}.fa-cat:before{content:"\f6be"}.fa-cc-amazon-pay:before{content:"\f42d"}.fa-cc-amex:before{content:"\f1f3"}.fa-cc-apple-pay:before{content:"\f416"}.fa-cc-diners-club:before{content:"\f24c"}.fa-cc-discover:before{content:"\f1f2"}.fa-cc-jcb:before{content:"\f24b"}.fa-cc-mastercard:before{content:"\f1f1"}.fa-cc-paypal:before{content:"\f1f4"}.fa-cc-stripe:before{content:"\f1f5"}.fa-cc-visa:before{content:"\f1f0"}.fa-centercode:before{content:"\f380"}.fa-centos:before{content:"\f789"}.fa-certificate:before{content:"\f0a3"}.fa-chair:before{content:"\f6c0"}.fa-chalkboard:before{content:"\f51b"}.fa-chalkboard-teacher:before{content:"\f51c"}.fa-charging-station:before{content:"\f5e7"}.fa-chart-area:before{content:"\f1fe"}.fa-chart-bar:before{content:"\f080"}.fa-chart-line:before{content:"\f201"}.fa-chart-pie:before{content:"\f200"}.fa-check:before{content:"\f00c"}.fa-check-circle:before{content:"\f058"}.fa-check-double:before{content:"\f560"}.fa-check-square:before{content:"\f14a"}.fa-cheese:before{content:"\f7ef"}.fa-chess:before{content:"\f439"}.fa-chess-bishop:before{content:"\f43a"}.fa-chess-board:before{content:"\f43c"}.fa-chess-king:before{content:"\f43f"}.fa-chess-knight:before{content:"\f441"}.fa-chess-pawn:before{content:"\f443"}.fa-chess-queen:before{content:"\f445"}.fa-chess-rook:before{content:"\f447"}.fa-chevron-circle-down:before{content:"\f13a"}.fa-chevron-circle-left:before{content:"\f137"}.fa-chevron-circle-right:before{content:"\f138"}.fa-chevron-circle-up:before{content:"\f139"}.fa-chevron-down:before{content:"\f078"}.fa-chevron-left:before{content:"\f053"}.fa-chevron-right:before{content:"\f054"}.fa-chevron-up:before{content:"\f077"}.fa-child:before{content:"\f1ae"}.fa-chrome:before{content:"\f268"}.fa-chromecast:before{content:"\f838"}.fa-church:before{content:"\f51d"}.fa-circle:before{content:"\f111"}.fa-circle-notch:before{content:"\f1ce"}.fa-city:before{content:"\f64f"}.fa-clinic-medical:before{content:"\f7f2"}.fa-clipboard:before{content:"\f328"}.fa-clipboard-check:before{content:"\f46c"}.fa-clipboard-list:before{content:"\f46d"}.fa-clock:before{content:"\f017"}.fa-clone:before{content:"\f24d"}.fa-closed-captioning:before{content:"\f20a"}.fa-cloud:before{content:"\f0c2"}.fa-cloud-download-alt:before{content:"\f381"}.fa-cloud-meatball:before{content:"\f73b"}.fa-cloud-moon:before{content:"\f6c3"}.fa-cloud-moon-rain:before{content:"\f73c"}.fa-cloud-rain:before{content:"\f73d"}.fa-cloud-showers-heavy:before{content:"\f740"}.fa-cloud-sun:before{content:"\f6c4"}.fa-cloud-sun-rain:before{content:"\f743"}.fa-cloud-upload-alt:before{content:"\f382"}.fa-cloudscale:before{content:"\f383"}.fa-cloudsmith:before{content:"\f384"}.fa-cloudversify:before{content:"\f385"}.fa-cocktail:before{content:"\f561"}.fa-code:before{content:"\f121"}.fa-code-branch:before{content:"\f126"}.fa-codepen:before{content:"\f1cb"}.fa-codiepie:before{content:"\f284"}.fa-coffee:before{content:"\f0f4"}.fa-cog:before{content:"\f013"}.fa-cogs:before{content:"\f085"}.fa-coins:before{content:"\f51e"}.fa-columns:before{content:"\f0db"}.fa-comment:before{content:"\f075"}.fa-comment-alt:before{content:"\f27a"}.fa-comment-dollar:before{content:"\f651"}.fa-comment-dots:before{content:"\f4ad"}.fa-comment-medical:before{content:"\f7f5"}.fa-comment-slash:before{content:"\f4b3"}.fa-comments:before{content:"\f086"}.fa-comments-dollar:before{content:"\f653"}.fa-compact-disc:before{content:"\f51f"}.fa-compass:before{content:"\f14e"}.fa-compress:before{content:"\f066"}.fa-compress-arrows-alt:before{content:"\f78c"}.fa-concierge-bell:before{content:"\f562"}.fa-confluence:before{content:"\f78d"}.fa-connectdevelop:before{content:"\f20e"}.fa-contao:before{content:"\f26d"}.fa-cookie:before{content:"\f563"}.fa-cookie-bite:before{content:"\f564"}.fa-copy:before{content:"\f0c5"}.fa-copyright:before{content:"\f1f9"}.fa-cotton-bureau:before{content:"\f89e"}.fa-couch:before{content:"\f4b8"}.fa-cpanel:before{content:"\f388"}.fa-creative-commons:before{content:"\f25e"}.fa-creative-commons-by:before{content:"\f4e7"}.fa-creative-commons-nc:before{content:"\f4e8"}.fa-creative-commons-nc-eu:before{content:"\f4e9"}.fa-creative-commons-nc-jp:before{content:"\f4ea"}.fa-creative-commons-nd:before{content:"\f4eb"}.fa-creative-commons-pd:before{content:"\f4ec"}.fa-creative-commons-pd-alt:before{content:"\f4ed"}.fa-creative-commons-remix:before{content:"\f4ee"}.fa-creative-commons-sa:before{content:"\f4ef"}.fa-creative-commons-sampling:before{content:"\f4f0"}.fa-creative-commons-sampling-plus:before{content:"\f4f1"}.fa-creative-commons-share:before{content:"\f4f2"}.fa-creative-commons-zero:before{content:"\f4f3"}.fa-credit-card:before{content:"\f09d"}.fa-critical-role:before{content:"\f6c9"}.fa-crop:before{content:"\f125"}.fa-crop-alt:before{content:"\f565"}.fa-cross:before{content:"\f654"}.fa-crosshairs:before{content:"\f05b"}.fa-crow:before{content:"\f520"}.fa-crown:before{content:"\f521"}.fa-crutch:before{content:"\f7f7"}.fa-css3:before{content:"\f13c"}.fa-css3-alt:before{content:"\f38b"}.fa-cube:before{content:"\f1b2"}.fa-cubes:before{content:"\f1b3"}.fa-cut:before{content:"\f0c4"}.fa-cuttlefish:before{content:"\f38c"}.fa-d-and-d:before{content:"\f38d"}.fa-d-and-d-beyond:before{content:"\f6ca"}.fa-dashcube:before{content:"\f210"}.fa-database:before{content:"\f1c0"}.fa-deaf:before{content:"\f2a4"}.fa-delicious:before{content:"\f1a5"}.fa-democrat:before{content:"\f747"}.fa-deploydog:before{content:"\f38e"}.fa-deskpro:before{content:"\f38f"}.fa-desktop:before{content:"\f108"}.fa-dev:before{content:"\f6cc"}.fa-deviantart:before{content:"\f1bd"}.fa-dharmachakra:before{content:"\f655"}.fa-dhl:before{content:"\f790"}.fa-diagnoses:before{content:"\f470"}.fa-diaspora:before{content:"\f791"}.fa-dice:before{content:"\f522"}.fa-dice-d20:before{content:"\f6cf"}.fa-dice-d6:before{content:"\f6d1"}.fa-dice-five:before{content:"\f523"}.fa-dice-four:before{content:"\f524"}.fa-dice-one:before{content:"\f525"}.fa-dice-six:before{content:"\f526"}.fa-dice-three:before{content:"\f527"}.fa-dice-two:before{content:"\f528"}.fa-digg:before{content:"\f1a6"}.fa-digital-ocean:before{content:"\f391"}.fa-digital-tachograph:before{content:"\f566"}.fa-directions:before{content:"\f5eb"}.fa-discord:before{content:"\f392"}.fa-discourse:before{content:"\f393"}.fa-divide:before{content:"\f529"}.fa-dizzy:before{content:"\f567"}.fa-dna:before{content:"\f471"}.fa-dochub:before{content:"\f394"}.fa-docker:before{content:"\f395"}.fa-dog:before{content:"\f6d3"}.fa-dollar-sign:before{content:"\f155"}.fa-dolly:before{content:"\f472"}.fa-dolly-flatbed:before{content:"\f474"}.fa-donate:before{content:"\f4b9"}.fa-door-closed:before{content:"\f52a"}.fa-door-open:before{content:"\f52b"}.fa-dot-circle:before{content:"\f192"}.fa-dove:before{content:"\f4ba"}.fa-download:before{content:"\f019"}.fa-draft2digital:before{content:"\f396"}.fa-drafting-compass:before{content:"\f568"}.fa-dragon:before{content:"\f6d5"}.fa-draw-polygon:before{content:"\f5ee"}.fa-dribbble:before{content:"\f17d"}.fa-dribbble-square:before{content:"\f397"}.fa-dropbox:before{content:"\f16b"}.fa-drum:before{content:"\f569"}.fa-drum-steelpan:before{content:"\f56a"}.fa-drumstick-bite:before{content:"\f6d7"}.fa-drupal:before{content:"\f1a9"}.fa-dumbbell:before{content:"\f44b"}.fa-dumpster:before{content:"\f793"}.fa-dumpster-fire:before{content:"\f794"}.fa-dungeon:before{content:"\f6d9"}.fa-dyalog:before{content:"\f399"}.fa-earlybirds:before{content:"\f39a"}.fa-ebay:before{content:"\f4f4"}.fa-edge:before{content:"\f282"}.fa-edit:before{content:"\f044"}.fa-egg:before{content:"\f7fb"}.fa-eject:before{content:"\f052"}.fa-elementor:before{content:"\f430"}.fa-ellipsis-h:before{content:"\f141"}.fa-ellipsis-v:before{content:"\f142"}.fa-ello:before{content:"\f5f1"}.fa-ember:before{content:"\f423"}.fa-empire:before{content:"\f1d1"}.fa-envelope:before{content:"\f0e0"}.fa-envelope-open:before{content:"\f2b6"}.fa-envelope-open-text:before{content:"\f658"}.fa-envelope-square:before{content:"\f199"}.fa-envira:before{content:"\f299"}.fa-equals:before{content:"\f52c"}.fa-eraser:before{content:"\f12d"}.fa-erlang:before{content:"\f39d"}.fa-ethereum:before{content:"\f42e"}.fa-ethernet:before{content:"\f796"}.fa-etsy:before{content:"\f2d7"}.fa-euro-sign:before{content:"\f153"}.fa-evernote:before{content:"\f839"}.fa-exchange-alt:before{content:"\f362"}.fa-exclamation:before{content:"\f12a"}.fa-exclamation-circle:before{content:"\f06a"}.fa-exclamation-triangle:before{content:"\f071"}.fa-expand:before{content:"\f065"}.fa-expand-arrows-alt:before{content:"\f31e"}.fa-expeditedssl:before{content:"\f23e"}.fa-external-link-alt:before{content:"\f35d"}.fa-external-link-square-alt:before{content:"\f360"}.fa-eye:before{content:"\f06e"}.fa-eye-dropper:before{content:"\f1fb"}.fa-eye-slash:before{content:"\f070"}.fa-facebook:before{content:"\f09a"}.fa-facebook-f:before{content:"\f39e"}.fa-facebook-messenger:before{content:"\f39f"}.fa-facebook-square:before{content:"\f082"}.fa-fan:before{content:"\f863"}.fa-fantasy-flight-games:before{content:"\f6dc"}.fa-fast-backward:before{content:"\f049"}.fa-fast-forward:before{content:"\f050"}.fa-fax:before{content:"\f1ac"}.fa-feather:before{content:"\f52d"}.fa-feather-alt:before{content:"\f56b"}.fa-fedex:before{content:"\f797"}.fa-fedora:before{content:"\f798"}.fa-female:before{content:"\f182"}.fa-fighter-jet:before{content:"\f0fb"}.fa-figma:before{content:"\f799"}.fa-file:before{content:"\f15b"}.fa-file-alt:before{content:"\f15c"}.fa-file-archive:before{content:"\f1c6"}.fa-file-audio:before{content:"\f1c7"}.fa-file-code:before{content:"\f1c9"}.fa-file-contract:before{content:"\f56c"}.fa-file-csv:before{content:"\f6dd"}.fa-file-download:before{content:"\f56d"}.fa-file-excel:before{content:"\f1c3"}.fa-file-export:before{content:"\f56e"}.fa-file-image:before{content:"\f1c5"}.fa-file-import:before{content:"\f56f"}.fa-file-invoice:before{content:"\f570"}.fa-file-invoice-dollar:before{content:"\f571"}.fa-file-medical:before{content:"\f477"}.fa-file-medical-alt:before{content:"\f478"}.fa-file-pdf:before{content:"\f1c1"}.fa-file-powerpoint:before{content:"\f1c4"}.fa-file-prescription:before{content:"\f572"}.fa-file-signature:before{content:"\f573"}.fa-file-upload:before{content:"\f574"}.fa-file-video:before{content:"\f1c8"}.fa-file-word:before{content:"\f1c2"}.fa-fill:before{content:"\f575"}.fa-fill-drip:before{content:"\f576"}.fa-film:before{content:"\f008"}.fa-filter:before{content:"\f0b0"}.fa-fingerprint:before{content:"\f577"}.fa-fire:before{content:"\f06d"}.fa-fire-alt:before{content:"\f7e4"}.fa-fire-extinguisher:before{content:"\f134"}.fa-firefox:before{content:"\f269"}.fa-first-aid:before{content:"\f479"}.fa-first-order:before{content:"\f2b0"}.fa-first-order-alt:before{content:"\f50a"}.fa-firstdraft:before{content:"\f3a1"}.fa-fish:before{content:"\f578"}.fa-fist-raised:before{content:"\f6de"}.fa-flag:before{content:"\f024"}.fa-flag-checkered:before{content:"\f11e"}.fa-flag-usa:before{content:"\f74d"}.fa-flask:before{content:"\f0c3"}.fa-flickr:before{content:"\f16e"}.fa-flipboard:before{content:"\f44d"}.fa-flushed:before{content:"\f579"}.fa-fly:before{content:"\f417"}.fa-folder:before{content:"\f07b"}.fa-folder-minus:before{content:"\f65d"}.fa-folder-open:before{content:"\f07c"}.fa-folder-plus:before{content:"\f65e"}.fa-font:before{content:"\f031"}.fa-font-awesome:before{content:"\f2b4"}.fa-font-awesome-alt:before{content:"\f35c"}.fa-font-awesome-flag:before{content:"\f425"}.fa-font-awesome-logo-full:before{content:"\f4e6"}.fa-fonticons:before{content:"\f280"}.fa-fonticons-fi:before{content:"\f3a2"}.fa-football-ball:before{content:"\f44e"}.fa-fort-awesome:before{content:"\f286"}.fa-fort-awesome-alt:before{content:"\f3a3"}.fa-forumbee:before{content:"\f211"}.fa-forward:before{content:"\f04e"}.fa-foursquare:before{content:"\f180"}.fa-free-code-camp:before{content:"\f2c5"}.fa-freebsd:before{content:"\f3a4"}.fa-frog:before{content:"\f52e"}.fa-frown:before{content:"\f119"}.fa-frown-open:before{content:"\f57a"}.fa-fulcrum:before{content:"\f50b"}.fa-funnel-dollar:before{content:"\f662"}.fa-futbol:before{content:"\f1e3"}.fa-galactic-republic:before{content:"\f50c"}.fa-galactic-senate:before{content:"\f50d"}.fa-gamepad:before{content:"\f11b"}.fa-gas-pump:before{content:"\f52f"}.fa-gavel:before{content:"\f0e3"}.fa-gem:before{content:"\f3a5"}.fa-genderless:before{content:"\f22d"}.fa-get-pocket:before{content:"\f265"}.fa-gg:before{content:"\f260"}.fa-gg-circle:before{content:"\f261"}.fa-ghost:before{content:"\f6e2"}.fa-gift:before{content:"\f06b"}.fa-gifts:before{content:"\f79c"}.fa-git:before{content:"\f1d3"}.fa-git-alt:before{content:"\f841"}.fa-git-square:before{content:"\f1d2"}.fa-github:before{content:"\f09b"}.fa-github-alt:before{content:"\f113"}.fa-github-square:before{content:"\f092"}.fa-gitkraken:before{content:"\f3a6"}.fa-gitlab:before{content:"\f296"}.fa-gitter:before{content:"\f426"}.fa-glass-cheers:before{content:"\f79f"}.fa-glass-martini:before{content:"\f000"}.fa-glass-martini-alt:before{content:"\f57b"}.fa-glass-whiskey:before{content:"\f7a0"}.fa-glasses:before{content:"\f530"}.fa-glide:before{content:"\f2a5"}.fa-glide-g:before{content:"\f2a6"}.fa-globe:before{content:"\f0ac"}.fa-globe-africa:before{content:"\f57c"}.fa-globe-americas:before{content:"\f57d"}.fa-globe-asia:before{content:"\f57e"}.fa-globe-europe:before{content:"\f7a2"}.fa-gofore:before{content:"\f3a7"}.fa-golf-ball:before{content:"\f450"}.fa-goodreads:before{content:"\f3a8"}.fa-goodreads-g:before{content:"\f3a9"}.fa-google:before{content:"\f1a0"}.fa-google-drive:before{content:"\f3aa"}.fa-google-play:before{content:"\f3ab"}.fa-google-plus:before{content:"\f2b3"}.fa-google-plus-g:before{content:"\f0d5"}.fa-google-plus-square:before{content:"\f0d4"}.fa-google-wallet:before{content:"\f1ee"}.fa-gopuram:before{content:"\f664"}.fa-graduation-cap:before{content:"\f19d"}.fa-gratipay:before{content:"\f184"}.fa-grav:before{content:"\f2d6"}.fa-greater-than:before{content:"\f531"}.fa-greater-than-equal:before{content:"\f532"}.fa-grimace:before{content:"\f57f"}.fa-grin:before{content:"\f580"}.fa-grin-alt:before{content:"\f581"}.fa-grin-beam:before{content:"\f582"}.fa-grin-beam-sweat:before{content:"\f583"}.fa-grin-hearts:before{content:"\f584"}.fa-grin-squint:before{content:"\f585"}.fa-grin-squint-tears:before{content:"\f586"}.fa-grin-stars:before{content:"\f587"}.fa-grin-tears:before{content:"\f588"}.fa-grin-tongue:before{content:"\f589"}.fa-grin-tongue-squint:before{content:"\f58a"}.fa-grin-tongue-wink:before{content:"\f58b"}.fa-grin-wink:before{content:"\f58c"}.fa-grip-horizontal:before{content:"\f58d"}.fa-grip-lines:before{content:"\f7a4"}.fa-grip-lines-vertical:before{content:"\f7a5"}.fa-grip-vertical:before{content:"\f58e"}.fa-gripfire:before{content:"\f3ac"}.fa-grunt:before{content:"\f3ad"}.fa-guitar:before{content:"\f7a6"}.fa-gulp:before{content:"\f3ae"}.fa-h-square:before{content:"\f0fd"}.fa-hacker-news:before{content:"\f1d4"}.fa-hacker-news-square:before{content:"\f3af"}.fa-hackerrank:before{content:"\f5f7"}.fa-hamburger:before{content:"\f805"}.fa-hammer:before{content:"\f6e3"}.fa-hamsa:before{content:"\f665"}.fa-hand-holding:before{content:"\f4bd"}.fa-hand-holding-heart:before{content:"\f4be"}.fa-hand-holding-usd:before{content:"\f4c0"}.fa-hand-lizard:before{content:"\f258"}.fa-hand-middle-finger:before{content:"\f806"}.fa-hand-paper:before{content:"\f256"}.fa-hand-peace:before{content:"\f25b"}.fa-hand-point-down:before{content:"\f0a7"}.fa-hand-point-left:before{content:"\f0a5"}.fa-hand-point-right:before{content:"\f0a4"}.fa-hand-point-up:before{content:"\f0a6"}.fa-hand-pointer:before{content:"\f25a"}.fa-hand-rock:before{content:"\f255"}.fa-hand-scissors:before{content:"\f257"}.fa-hand-spock:before{content:"\f259"}.fa-hands:before{content:"\f4c2"}.fa-hands-helping:before{content:"\f4c4"}.fa-handshake:before{content:"\f2b5"}.fa-hanukiah:before{content:"\f6e6"}.fa-hard-hat:before{content:"\f807"}.fa-hashtag:before{content:"\f292"}.fa-hat-wizard:before{content:"\f6e8"}.fa-haykal:before{content:"\f666"}.fa-hdd:before{content:"\f0a0"}.fa-heading:before{content:"\f1dc"}.fa-headphones:before{content:"\f025"}.fa-headphones-alt:before{content:"\f58f"}.fa-headset:before{content:"\f590"}.fa-heart:before{content:"\f004"}.fa-heart-broken:before{content:"\f7a9"}.fa-heartbeat:before{content:"\f21e"}.fa-helicopter:before{content:"\f533"}.fa-highlighter:before{content:"\f591"}.fa-hiking:before{content:"\f6ec"}.fa-hippo:before{content:"\f6ed"}.fa-hips:before{content:"\f452"}.fa-hire-a-helper:before{content:"\f3b0"}.fa-history:before{content:"\f1da"}.fa-hockey-puck:before{content:"\f453"}.fa-holly-berry:before{content:"\f7aa"}.fa-home:before{content:"\f015"}.fa-hooli:before{content:"\f427"}.fa-hornbill:before{content:"\f592"}.fa-horse:before{content:"\f6f0"}.fa-horse-head:before{content:"\f7ab"}.fa-hospital:before{content:"\f0f8"}.fa-hospital-alt:before{content:"\f47d"}.fa-hospital-symbol:before{content:"\f47e"}.fa-hot-tub:before{content:"\f593"}.fa-hotdog:before{content:"\f80f"}.fa-hotel:before{content:"\f594"}.fa-hotjar:before{content:"\f3b1"}.fa-hourglass:before{content:"\f254"}.fa-hourglass-end:before{content:"\f253"}.fa-hourglass-half:before{content:"\f252"}.fa-hourglass-start:before{content:"\f251"}.fa-house-damage:before{content:"\f6f1"}.fa-houzz:before{content:"\f27c"}.fa-hryvnia:before{content:"\f6f2"}.fa-html5:before{content:"\f13b"}.fa-hubspot:before{content:"\f3b2"}.fa-i-cursor:before{content:"\f246"}.fa-ice-cream:before{content:"\f810"}.fa-icicles:before{content:"\f7ad"}.fa-icons:before{content:"\f86d"}.fa-id-badge:before{content:"\f2c1"}.fa-id-card:before{content:"\f2c2"}.fa-id-card-alt:before{content:"\f47f"}.fa-igloo:before{content:"\f7ae"}.fa-image:before{content:"\f03e"}.fa-images:before{content:"\f302"}.fa-imdb:before{content:"\f2d8"}.fa-inbox:before{content:"\f01c"}.fa-indent:before{content:"\f03c"}.fa-industry:before{content:"\f275"}.fa-infinity:before{content:"\f534"}.fa-info:before{content:"\f129"}.fa-info-circle:before{content:"\f05a"}.fa-instagram:before{content:"\f16d"}.fa-intercom:before{content:"\f7af"}.fa-internet-explorer:before{content:"\f26b"}.fa-invision:before{content:"\f7b0"}.fa-ioxhost:before{content:"\f208"}.fa-italic:before{content:"\f033"}.fa-itch-io:before{content:"\f83a"}.fa-itunes:before{content:"\f3b4"}.fa-itunes-note:before{content:"\f3b5"}.fa-java:before{content:"\f4e4"}.fa-jedi:before{content:"\f669"}.fa-jedi-order:before{content:"\f50e"}.fa-jenkins:before{content:"\f3b6"}.fa-jira:before{content:"\f7b1"}.fa-joget:before{content:"\f3b7"}.fa-joint:before{content:"\f595"}.fa-joomla:before{content:"\f1aa"}.fa-journal-whills:before{content:"\f66a"}.fa-js:before{content:"\f3b8"}.fa-js-square:before{content:"\f3b9"}.fa-jsfiddle:before{content:"\f1cc"}.fa-kaaba:before{content:"\f66b"}.fa-kaggle:before{content:"\f5fa"}.fa-key:before{content:"\f084"}.fa-keybase:before{content:"\f4f5"}.fa-keyboard:before{content:"\f11c"}.fa-keycdn:before{content:"\f3ba"}.fa-khanda:before{content:"\f66d"}.fa-kickstarter:before{content:"\f3bb"}.fa-kickstarter-k:before{content:"\f3bc"}.fa-kiss:before{content:"\f596"}.fa-kiss-beam:before{content:"\f597"}.fa-kiss-wink-heart:before{content:"\f598"}.fa-kiwi-bird:before{content:"\f535"}.fa-korvue:before{content:"\f42f"}.fa-landmark:before{content:"\f66f"}.fa-language:before{content:"\f1ab"}.fa-laptop:before{content:"\f109"}.fa-laptop-code:before{content:"\f5fc"}.fa-laptop-medical:before{content:"\f812"}.fa-laravel:before{content:"\f3bd"}.fa-lastfm:before{content:"\f202"}.fa-lastfm-square:before{content:"\f203"}.fa-laugh:before{content:"\f599"}.fa-laugh-beam:before{content:"\f59a"}.fa-laugh-squint:before{content:"\f59b"}.fa-laugh-wink:before{content:"\f59c"}.fa-layer-group:before{content:"\f5fd"}.fa-leaf:before{content:"\f06c"}.fa-leanpub:before{content:"\f212"}.fa-lemon:before{content:"\f094"}.fa-less:before{content:"\f41d"}.fa-less-than:before{content:"\f536"}.fa-less-than-equal:before{content:"\f537"}.fa-level-down-alt:before{content:"\f3be"}.fa-level-up-alt:before{content:"\f3bf"}.fa-life-ring:before{content:"\f1cd"}.fa-lightbulb:before{content:"\f0eb"}.fa-line:before{content:"\f3c0"}.fa-link:before{content:"\f0c1"}.fa-linkedin:before{content:"\f08c"}.fa-linkedin-in:before{content:"\f0e1"}.fa-linode:before{content:"\f2b8"}.fa-linux:before{content:"\f17c"}.fa-lira-sign:before{content:"\f195"}.fa-list:before{content:"\f03a"}.fa-list-alt:before{content:"\f022"}.fa-list-ol:before{content:"\f0cb"}.fa-list-ul:before{content:"\f0ca"}.fa-location-arrow:before{content:"\f124"}.fa-lock:before{content:"\f023"}.fa-lock-open:before{content:"\f3c1"}.fa-long-arrow-alt-down:before{content:"\f309"}.fa-long-arrow-alt-left:before{content:"\f30a"}.fa-long-arrow-alt-right:before{content:"\f30b"}.fa-long-arrow-alt-up:before{content:"\f30c"}.fa-low-vision:before{content:"\f2a8"}.fa-luggage-cart:before{content:"\f59d"}.fa-lyft:before{content:"\f3c3"}.fa-magento:before{content:"\f3c4"}.fa-magic:before{content:"\f0d0"}.fa-magnet:before{content:"\f076"}.fa-mail-bulk:before{content:"\f674"}.fa-mailchimp:before{content:"\f59e"}.fa-male:before{content:"\f183"}.fa-mandalorian:before{content:"\f50f"}.fa-map:before{content:"\f279"}.fa-map-marked:before{content:"\f59f"}.fa-map-marked-alt:before{content:"\f5a0"}.fa-map-marker:before{content:"\f041"}.fa-map-marker-alt:before{content:"\f3c5"}.fa-map-pin:before{content:"\f276"}.fa-map-signs:before{content:"\f277"}.fa-markdown:before{content:"\f60f"}.fa-marker:before{content:"\f5a1"}.fa-mars:before{content:"\f222"}.fa-mars-double:before{content:"\f227"}.fa-mars-stroke:before{content:"\f229"}.fa-mars-stroke-h:before{content:"\f22b"}.fa-mars-stroke-v:before{content:"\f22a"}.fa-mask:before{content:"\f6fa"}.fa-mastodon:before{content:"\f4f6"}.fa-maxcdn:before{content:"\f136"}.fa-medal:before{content:"\f5a2"}.fa-medapps:before{content:"\f3c6"}.fa-medium:before{content:"\f23a"}.fa-medium-m:before{content:"\f3c7"}.fa-medkit:before{content:"\f0fa"}.fa-medrt:before{content:"\f3c8"}.fa-meetup:before{content:"\f2e0"}.fa-megaport:before{content:"\f5a3"}.fa-meh:before{content:"\f11a"}.fa-meh-blank:before{content:"\f5a4"}.fa-meh-rolling-eyes:before{content:"\f5a5"}.fa-memory:before{content:"\f538"}.fa-mendeley:before{content:"\f7b3"}.fa-menorah:before{content:"\f676"}.fa-mercury:before{content:"\f223"}.fa-meteor:before{content:"\f753"}.fa-microchip:before{content:"\f2db"}.fa-microphone:before{content:"\f130"}.fa-microphone-alt:before{content:"\f3c9"}.fa-microphone-alt-slash:before{content:"\f539"}.fa-microphone-slash:before{content:"\f131"}.fa-microscope:before{content:"\f610"}.fa-microsoft:before{content:"\f3ca"}.fa-minus:before{content:"\f068"}.fa-minus-circle:before{content:"\f056"}.fa-minus-square:before{content:"\f146"}.fa-mitten:before{content:"\f7b5"}.fa-mix:before{content:"\f3cb"}.fa-mixcloud:before{content:"\f289"}.fa-mizuni:before{content:"\f3cc"}.fa-mobile:before{content:"\f10b"}.fa-mobile-alt:before{content:"\f3cd"}.fa-modx:before{content:"\f285"}.fa-monero:before{content:"\f3d0"}.fa-money-bill:before{content:"\f0d6"}.fa-money-bill-alt:before{content:"\f3d1"}.fa-money-bill-wave:before{content:"\f53a"}.fa-money-bill-wave-alt:before{content:"\f53b"}.fa-money-check:before{content:"\f53c"}.fa-money-check-alt:before{content:"\f53d"}.fa-monument:before{content:"\f5a6"}.fa-moon:before{content:"\f186"}.fa-mortar-pestle:before{content:"\f5a7"}.fa-mosque:before{content:"\f678"}.fa-motorcycle:before{content:"\f21c"}.fa-mountain:before{content:"\f6fc"}.fa-mouse-pointer:before{content:"\f245"}.fa-mug-hot:before{content:"\f7b6"}.fa-music:before{content:"\f001"}.fa-napster:before{content:"\f3d2"}.fa-neos:before{content:"\f612"}.fa-network-wired:before{content:"\f6ff"}.fa-neuter:before{content:"\f22c"}.fa-newspaper:before{content:"\f1ea"}.fa-nimblr:before{content:"\f5a8"}.fa-node:before{content:"\f419"}.fa-node-js:before{content:"\f3d3"}.fa-not-equal:before{content:"\f53e"}.fa-notes-medical:before{content:"\f481"}.fa-npm:before{content:"\f3d4"}.fa-ns8:before{content:"\f3d5"}.fa-nutritionix:before{content:"\f3d6"}.fa-object-group:before{content:"\f247"}.fa-object-ungroup:before{content:"\f248"}.fa-odnoklassniki:before{content:"\f263"}.fa-odnoklassniki-square:before{content:"\f264"}.fa-oil-can:before{content:"\f613"}.fa-old-republic:before{content:"\f510"}.fa-om:before{content:"\f679"}.fa-opencart:before{content:"\f23d"}.fa-openid:before{content:"\f19b"}.fa-opera:before{content:"\f26a"}.fa-optin-monster:before{content:"\f23c"}.fa-osi:before{content:"\f41a"}.fa-otter:before{content:"\f700"}.fa-outdent:before{content:"\f03b"}.fa-page4:before{content:"\f3d7"}.fa-pagelines:before{content:"\f18c"}.fa-pager:before{content:"\f815"}.fa-paint-brush:before{content:"\f1fc"}.fa-paint-roller:before{content:"\f5aa"}.fa-palette:before{content:"\f53f"}.fa-palfed:before{content:"\f3d8"}.fa-pallet:before{content:"\f482"}.fa-paper-plane:before{content:"\f1d8"}.fa-paperclip:before{content:"\f0c6"}.fa-parachute-box:before{content:"\f4cd"}.fa-paragraph:before{content:"\f1dd"}.fa-parking:before{content:"\f540"}.fa-passport:before{content:"\f5ab"}.fa-pastafarianism:before{content:"\f67b"}.fa-paste:before{content:"\f0ea"}.fa-patreon:before{content:"\f3d9"}.fa-pause:before{content:"\f04c"}.fa-pause-circle:before{content:"\f28b"}.fa-paw:before{content:"\f1b0"}.fa-paypal:before{content:"\f1ed"}.fa-peace:before{content:"\f67c"}.fa-pen:before{content:"\f304"}.fa-pen-alt:before{content:"\f305"}.fa-pen-fancy:before{content:"\f5ac"}.fa-pen-nib:before{content:"\f5ad"}.fa-pen-square:before{content:"\f14b"}.fa-pencil-alt:before{content:"\f303"}.fa-pencil-ruler:before{content:"\f5ae"}.fa-penny-arcade:before{content:"\f704"}.fa-people-carry:before{content:"\f4ce"}.fa-pepper-hot:before{content:"\f816"}.fa-percent:before{content:"\f295"}.fa-percentage:before{content:"\f541"}.fa-periscope:before{content:"\f3da"}.fa-person-booth:before{content:"\f756"}.fa-phabricator:before{content:"\f3db"}.fa-phoenix-framework:before{content:"\f3dc"}.fa-phoenix-squadron:before{content:"\f511"}.fa-phone:before{content:"\f095"}.fa-phone-alt:before{content:"\f879"}.fa-phone-slash:before{content:"\f3dd"}.fa-phone-square:before{content:"\f098"}.fa-phone-square-alt:before{content:"\f87b"}.fa-phone-volume:before{content:"\f2a0"}.fa-photo-video:before{content:"\f87c"}.fa-php:before{content:"\f457"}.fa-pied-piper:before{content:"\f2ae"}.fa-pied-piper-alt:before{content:"\f1a8"}.fa-pied-piper-hat:before{content:"\f4e5"}.fa-pied-piper-pp:before{content:"\f1a7"}.fa-piggy-bank:before{content:"\f4d3"}.fa-pills:before{content:"\f484"}.fa-pinterest:before{content:"\f0d2"}.fa-pinterest-p:before{content:"\f231"}.fa-pinterest-square:before{content:"\f0d3"}.fa-pizza-slice:before{content:"\f818"}.fa-place-of-worship:before{content:"\f67f"}.fa-plane:before{content:"\f072"}.fa-plane-arrival:before{content:"\f5af"}.fa-plane-departure:before{content:"\f5b0"}.fa-play:before{content:"\f04b"}.fa-play-circle:before{content:"\f144"}.fa-playstation:before{content:"\f3df"}.fa-plug:before{content:"\f1e6"}.fa-plus:before{content:"\f067"}.fa-plus-circle:before{content:"\f055"}.fa-plus-square:before{content:"\f0fe"}.fa-podcast:before{content:"\f2ce"}.fa-poll:before{content:"\f681"}.fa-poll-h:before{content:"\f682"}.fa-poo:before{content:"\f2fe"}.fa-poo-storm:before{content:"\f75a"}.fa-poop:before{content:"\f619"}.fa-portrait:before{content:"\f3e0"}.fa-pound-sign:before{content:"\f154"}.fa-power-off:before{content:"\f011"}.fa-pray:before{content:"\f683"}.fa-praying-hands:before{content:"\f684"}.fa-prescription:before{content:"\f5b1"}.fa-prescription-bottle:before{content:"\f485"}.fa-prescription-bottle-alt:before{content:"\f486"}.fa-print:before{content:"\f02f"}.fa-procedures:before{content:"\f487"}.fa-product-hunt:before{content:"\f288"}.fa-project-diagram:before{content:"\f542"}.fa-pushed:before{content:"\f3e1"}.fa-puzzle-piece:before{content:"\f12e"}.fa-python:before{content:"\f3e2"}.fa-qq:before{content:"\f1d6"}.fa-qrcode:before{content:"\f029"}.fa-question:before{content:"\f128"}.fa-question-circle:before{content:"\f059"}.fa-quidditch:before{content:"\f458"}.fa-quinscape:before{content:"\f459"}.fa-quora:before{content:"\f2c4"}.fa-quote-left:before{content:"\f10d"}.fa-quote-right:before{content:"\f10e"}.fa-quran:before{content:"\f687"}.fa-r-project:before{content:"\f4f7"}.fa-radiation:before{content:"\f7b9"}.fa-radiation-alt:before{content:"\f7ba"}.fa-rainbow:before{content:"\f75b"}.fa-random:before{content:"\f074"}.fa-raspberry-pi:before{content:"\f7bb"}.fa-ravelry:before{content:"\f2d9"}.fa-react:before{content:"\f41b"}.fa-reacteurope:before{content:"\f75d"}.fa-readme:before{content:"\f4d5"}.fa-rebel:before{content:"\f1d0"}.fa-receipt:before{content:"\f543"}.fa-recycle:before{content:"\f1b8"}.fa-red-river:before{content:"\f3e3"}.fa-reddit:before{content:"\f1a1"}.fa-reddit-alien:before{content:"\f281"}.fa-reddit-square:before{content:"\f1a2"}.fa-redhat:before{content:"\f7bc"}.fa-redo:before{content:"\f01e"}.fa-redo-alt:before{content:"\f2f9"}.fa-registered:before{content:"\f25d"}.fa-remove-format:before{content:"\f87d"}.fa-renren:before{content:"\f18b"}.fa-reply:before{content:"\f3e5"}.fa-reply-all:before{content:"\f122"}.fa-replyd:before{content:"\f3e6"}.fa-republican:before{content:"\f75e"}.fa-researchgate:before{content:"\f4f8"}.fa-resolving:before{content:"\f3e7"}.fa-restroom:before{content:"\f7bd"}.fa-retweet:before{content:"\f079"}.fa-rev:before{content:"\f5b2"}.fa-ribbon:before{content:"\f4d6"}.fa-ring:before{content:"\f70b"}.fa-road:before{content:"\f018"}.fa-robot:before{content:"\f544"}.fa-rocket:before{content:"\f135"}.fa-rocketchat:before{content:"\f3e8"}.fa-rockrms:before{content:"\f3e9"}.fa-route:before{content:"\f4d7"}.fa-rss:before{content:"\f09e"}.fa-rss-square:before{content:"\f143"}.fa-ruble-sign:before{content:"\f158"}.fa-ruler:before{content:"\f545"}.fa-ruler-combined:before{content:"\f546"}.fa-ruler-horizontal:before{content:"\f547"}.fa-ruler-vertical:before{content:"\f548"}.fa-running:before{content:"\f70c"}.fa-rupee-sign:before{content:"\f156"}.fa-sad-cry:before{content:"\f5b3"}.fa-sad-tear:before{content:"\f5b4"}.fa-safari:before{content:"\f267"}.fa-salesforce:before{content:"\f83b"}.fa-sass:before{content:"\f41e"}.fa-satellite:before{content:"\f7bf"}.fa-satellite-dish:before{content:"\f7c0"}.fa-save:before{content:"\f0c7"}.fa-schlix:before{content:"\f3ea"}.fa-school:before{content:"\f549"}.fa-screwdriver:before{content:"\f54a"}.fa-scribd:before{content:"\f28a"}.fa-scroll:before{content:"\f70e"}.fa-sd-card:before{content:"\f7c2"}.fa-search:before{content:"\f002"}.fa-search-dollar:before{content:"\f688"}.fa-search-location:before{content:"\f689"}.fa-search-minus:before{content:"\f010"}.fa-search-plus:before{content:"\f00e"}.fa-searchengin:before{content:"\f3eb"}.fa-seedling:before{content:"\f4d8"}.fa-sellcast:before{content:"\f2da"}.fa-sellsy:before{content:"\f213"}.fa-server:before{content:"\f233"}.fa-servicestack:before{content:"\f3ec"}.fa-shapes:before{content:"\f61f"}.fa-share:before{content:"\f064"}.fa-share-alt:before{content:"\f1e0"}.fa-share-alt-square:before{content:"\f1e1"}.fa-share-square:before{content:"\f14d"}.fa-shekel-sign:before{content:"\f20b"}.fa-shield-alt:before{content:"\f3ed"}.fa-ship:before{content:"\f21a"}.fa-shipping-fast:before{content:"\f48b"}.fa-shirtsinbulk:before{content:"\f214"}.fa-shoe-prints:before{content:"\f54b"}.fa-shopping-bag:before{content:"\f290"}.fa-shopping-basket:before{content:"\f291"}.fa-shopping-cart:before{content:"\f07a"}.fa-shopware:before{content:"\f5b5"}.fa-shower:before{content:"\f2cc"}.fa-shuttle-van:before{content:"\f5b6"}.fa-sign:before{content:"\f4d9"}.fa-sign-in-alt:before{content:"\f2f6"}.fa-sign-language:before{content:"\f2a7"}.fa-sign-out-alt:before{content:"\f2f5"}.fa-signal:before{content:"\f012"}.fa-signature:before{content:"\f5b7"}.fa-sim-card:before{content:"\f7c4"}.fa-simplybuilt:before{content:"\f215"}.fa-sistrix:before{content:"\f3ee"}.fa-sitemap:before{content:"\f0e8"}.fa-sith:before{content:"\f512"}.fa-skating:before{content:"\f7c5"}.fa-sketch:before{content:"\f7c6"}.fa-skiing:before{content:"\f7c9"}.fa-skiing-nordic:before{content:"\f7ca"}.fa-skull:before{content:"\f54c"}.fa-skull-crossbones:before{content:"\f714"}.fa-skyatlas:before{content:"\f216"}.fa-skype:before{content:"\f17e"}.fa-slack:before{content:"\f198"}.fa-slack-hash:before{content:"\f3ef"}.fa-slash:before{content:"\f715"}.fa-sleigh:before{content:"\f7cc"}.fa-sliders-h:before{content:"\f1de"}.fa-slideshare:before{content:"\f1e7"}.fa-smile:before{content:"\f118"}.fa-smile-beam:before{content:"\f5b8"}.fa-smile-wink:before{content:"\f4da"}.fa-smog:before{content:"\f75f"}.fa-smoking:before{content:"\f48d"}.fa-smoking-ban:before{content:"\f54d"}.fa-sms:before{content:"\f7cd"}.fa-snapchat:before{content:"\f2ab"}.fa-snapchat-ghost:before{content:"\f2ac"}.fa-snapchat-square:before{content:"\f2ad"}.fa-snowboarding:before{content:"\f7ce"}.fa-snowflake:before{content:"\f2dc"}.fa-snowman:before{content:"\f7d0"}.fa-snowplow:before{content:"\f7d2"}.fa-socks:before{content:"\f696"}.fa-solar-panel:before{content:"\f5ba"}.fa-sort:before{content:"\f0dc"}.fa-sort-alpha-down:before{content:"\f15d"}.fa-sort-alpha-down-alt:before{content:"\f881"}.fa-sort-alpha-up:before{content:"\f15e"}.fa-sort-alpha-up-alt:before{content:"\f882"}.fa-sort-amount-down:before{content:"\f160"}.fa-sort-amount-down-alt:before{content:"\f884"}.fa-sort-amount-up:before{content:"\f161"}.fa-sort-amount-up-alt:before{content:"\f885"}.fa-sort-down:before{content:"\f0dd"}.fa-sort-numeric-down:before{content:"\f162"}.fa-sort-numeric-down-alt:before{content:"\f886"}.fa-sort-numeric-up:before{content:"\f163"}.fa-sort-numeric-up-alt:before{content:"\f887"}.fa-sort-up:before{content:"\f0de"}.fa-soundcloud:before{content:"\f1be"}.fa-sourcetree:before{content:"\f7d3"}.fa-spa:before{content:"\f5bb"}.fa-space-shuttle:before{content:"\f197"}.fa-speakap:before{content:"\f3f3"}.fa-speaker-deck:before{content:"\f83c"}.fa-spell-check:before{content:"\f891"}.fa-spider:before{content:"\f717"}.fa-spinner:before{content:"\f110"}.fa-splotch:before{content:"\f5bc"}.fa-spotify:before{content:"\f1bc"}.fa-spray-can:before{content:"\f5bd"}.fa-square:before{content:"\f0c8"}.fa-square-full:before{content:"\f45c"}.fa-square-root-alt:before{content:"\f698"}.fa-squarespace:before{content:"\f5be"}.fa-stack-exchange:before{content:"\f18d"}.fa-stack-overflow:before{content:"\f16c"}.fa-stackpath:before{content:"\f842"}.fa-stamp:before{content:"\f5bf"}.fa-star:before{content:"\f005"}.fa-star-and-crescent:before{content:"\f699"}.fa-star-half:before{content:"\f089"}.fa-star-half-alt:before{content:"\f5c0"}.fa-star-of-david:before{content:"\f69a"}.fa-star-of-life:before{content:"\f621"}.fa-staylinked:before{content:"\f3f5"}.fa-steam:before{content:"\f1b6"}.fa-steam-square:before{content:"\f1b7"}.fa-steam-symbol:before{content:"\f3f6"}.fa-step-backward:before{content:"\f048"}.fa-step-forward:before{content:"\f051"}.fa-stethoscope:before{content:"\f0f1"}.fa-sticker-mule:before{content:"\f3f7"}.fa-sticky-note:before{content:"\f249"}.fa-stop:before{content:"\f04d"}.fa-stop-circle:before{content:"\f28d"}.fa-stopwatch:before{content:"\f2f2"}.fa-store:before{content:"\f54e"}.fa-store-alt:before{content:"\f54f"}.fa-strava:before{content:"\f428"}.fa-stream:before{content:"\f550"}.fa-street-view:before{content:"\f21d"}.fa-strikethrough:before{content:"\f0cc"}.fa-stripe:before{content:"\f429"}.fa-stripe-s:before{content:"\f42a"}.fa-stroopwafel:before{content:"\f551"}.fa-studiovinari:before{content:"\f3f8"}.fa-stumbleupon:before{content:"\f1a4"}.fa-stumbleupon-circle:before{content:"\f1a3"}.fa-subscript:before{content:"\f12c"}.fa-subway:before{content:"\f239"}.fa-suitcase:before{content:"\f0f2"}.fa-suitcase-rolling:before{content:"\f5c1"}.fa-sun:before{content:"\f185"}.fa-superpowers:before{content:"\f2dd"}.fa-superscript:before{content:"\f12b"}.fa-supple:before{content:"\f3f9"}.fa-surprise:before{content:"\f5c2"}.fa-suse:before{content:"\f7d6"}.fa-swatchbook:before{content:"\f5c3"}.fa-swimmer:before{content:"\f5c4"}.fa-swimming-pool:before{content:"\f5c5"}.fa-symfony:before{content:"\f83d"}.fa-synagogue:before{content:"\f69b"}.fa-sync:before{content:"\f021"}.fa-sync-alt:before{content:"\f2f1"}.fa-syringe:before{content:"\f48e"}.fa-table:before{content:"\f0ce"}.fa-table-tennis:before{content:"\f45d"}.fa-tablet:before{content:"\f10a"}.fa-tablet-alt:before{content:"\f3fa"}.fa-tablets:before{content:"\f490"}.fa-tachometer-alt:before{content:"\f3fd"}.fa-tag:before{content:"\f02b"}.fa-tags:before{content:"\f02c"}.fa-tape:before{content:"\f4db"}.fa-tasks:before{content:"\f0ae"}.fa-taxi:before{content:"\f1ba"}.fa-teamspeak:before{content:"\f4f9"}.fa-teeth:before{content:"\f62e"}.fa-teeth-open:before{content:"\f62f"}.fa-telegram:before{content:"\f2c6"}.fa-telegram-plane:before{content:"\f3fe"}.fa-temperature-high:before{content:"\f769"}.fa-temperature-low:before{content:"\f76b"}.fa-tencent-weibo:before{content:"\f1d5"}.fa-tenge:before{content:"\f7d7"}.fa-terminal:before{content:"\f120"}.fa-text-height:before{content:"\f034"}.fa-text-width:before{content:"\f035"}.fa-th:before{content:"\f00a"}.fa-th-large:before{content:"\f009"}.fa-th-list:before{content:"\f00b"}.fa-the-red-yeti:before{content:"\f69d"}.fa-theater-masks:before{content:"\f630"}.fa-themeco:before{content:"\f5c6"}.fa-themeisle:before{content:"\f2b2"}.fa-thermometer:before{content:"\f491"}.fa-thermometer-empty:before{content:"\f2cb"}.fa-thermometer-full:before{content:"\f2c7"}.fa-thermometer-half:before{content:"\f2c9"}.fa-thermometer-quarter:before{content:"\f2ca"}.fa-thermometer-three-quarters:before{content:"\f2c8"}.fa-think-peaks:before{content:"\f731"}.fa-thumbs-down:before{content:"\f165"}.fa-thumbs-up:before{content:"\f164"}.fa-thumbtack:before{content:"\f08d"}.fa-ticket-alt:before{content:"\f3ff"}.fa-times:before{content:"\f00d"}.fa-times-circle:before{content:"\f057"}.fa-tint:before{content:"\f043"}.fa-tint-slash:before{content:"\f5c7"}.fa-tired:before{content:"\f5c8"}.fa-toggle-off:before{content:"\f204"}.fa-toggle-on:before{content:"\f205"}.fa-toilet:before{content:"\f7d8"}.fa-toilet-paper:before{content:"\f71e"}.fa-toolbox:before{content:"\f552"}.fa-tools:before{content:"\f7d9"}.fa-tooth:before{content:"\f5c9"}.fa-torah:before{content:"\f6a0"}.fa-torii-gate:before{content:"\f6a1"}.fa-tractor:before{content:"\f722"}.fa-trade-federation:before{content:"\f513"}.fa-trademark:before{content:"\f25c"}.fa-traffic-light:before{content:"\f637"}.fa-train:before{content:"\f238"}.fa-tram:before{content:"\f7da"}.fa-transgender:before{content:"\f224"}.fa-transgender-alt:before{content:"\f225"}.fa-trash:before{content:"\f1f8"}.fa-trash-alt:before{content:"\f2ed"}.fa-trash-restore:before{content:"\f829"}.fa-trash-restore-alt:before{content:"\f82a"}.fa-tree:before{content:"\f1bb"}.fa-trello:before{content:"\f181"}.fa-tripadvisor:before{content:"\f262"}.fa-trophy:before{content:"\f091"}.fa-truck:before{content:"\f0d1"}.fa-truck-loading:before{content:"\f4de"}.fa-truck-monster:before{content:"\f63b"}.fa-truck-moving:before{content:"\f4df"}.fa-truck-pickup:before{content:"\f63c"}.fa-tshirt:before{content:"\f553"}.fa-tty:before{content:"\f1e4"}.fa-tumblr:before{content:"\f173"}.fa-tumblr-square:before{content:"\f174"}.fa-tv:before{content:"\f26c"}.fa-twitch:before{content:"\f1e8"}.fa-twitter:before{content:"\f099"}.fa-twitter-square:before{content:"\f081"}.fa-typo3:before{content:"\f42b"}.fa-uber:before{content:"\f402"}.fa-ubuntu:before{content:"\f7df"}.fa-uikit:before{content:"\f403"}.fa-umbrella:before{content:"\f0e9"}.fa-umbrella-beach:before{content:"\f5ca"}.fa-underline:before{content:"\f0cd"}.fa-undo:before{content:"\f0e2"}.fa-undo-alt:before{content:"\f2ea"}.fa-uniregistry:before{content:"\f404"}.fa-universal-access:before{content:"\f29a"}.fa-university:before{content:"\f19c"}.fa-unlink:before{content:"\f127"}.fa-unlock:before{content:"\f09c"}.fa-unlock-alt:before{content:"\f13e"}.fa-untappd:before{content:"\f405"}.fa-upload:before{content:"\f093"}.fa-ups:before{content:"\f7e0"}.fa-usb:before{content:"\f287"}.fa-user:before{content:"\f007"}.fa-user-alt:before{content:"\f406"}.fa-user-alt-slash:before{content:"\f4fa"}.fa-user-astronaut:before{content:"\f4fb"}.fa-user-check:before{content:"\f4fc"}.fa-user-circle:before{content:"\f2bd"}.fa-user-clock:before{content:"\f4fd"}.fa-user-cog:before{content:"\f4fe"}.fa-user-edit:before{content:"\f4ff"}.fa-user-friends:before{content:"\f500"}.fa-user-graduate:before{content:"\f501"}.fa-user-injured:before{content:"\f728"}.fa-user-lock:before{content:"\f502"}.fa-user-md:before{content:"\f0f0"}.fa-user-minus:before{content:"\f503"}.fa-user-ninja:before{content:"\f504"}.fa-user-nurse:before{content:"\f82f"}.fa-user-plus:before{content:"\f234"}.fa-user-secret:before{content:"\f21b"}.fa-user-shield:before{content:"\f505"}.fa-user-slash:before{content:"\f506"}.fa-user-tag:before{content:"\f507"}.fa-user-tie:before{content:"\f508"}.fa-user-times:before{content:"\f235"}.fa-users:before{content:"\f0c0"}.fa-users-cog:before{content:"\f509"}.fa-usps:before{content:"\f7e1"}.fa-ussunnah:before{content:"\f407"}.fa-utensil-spoon:before{content:"\f2e5"}.fa-utensils:before{content:"\f2e7"}.fa-vaadin:before{content:"\f408"}.fa-vector-square:before{content:"\f5cb"}.fa-venus:before{content:"\f221"}.fa-venus-double:before{content:"\f226"}.fa-venus-mars:before{content:"\f228"}.fa-viacoin:before{content:"\f237"}.fa-viadeo:before{content:"\f2a9"}.fa-viadeo-square:before{content:"\f2aa"}.fa-vial:before{content:"\f492"}.fa-vials:before{content:"\f493"}.fa-viber:before{content:"\f409"}.fa-video:before{content:"\f03d"}.fa-video-slash:before{content:"\f4e2"}.fa-vihara:before{content:"\f6a7"}.fa-vimeo:before{content:"\f40a"}.fa-vimeo-square:before{content:"\f194"}.fa-vimeo-v:before{content:"\f27d"}.fa-vine:before{content:"\f1ca"}.fa-vk:before{content:"\f189"}.fa-vnv:before{content:"\f40b"}.fa-voicemail:before{content:"\f897"}.fa-volleyball-ball:before{content:"\f45f"}.fa-volume-down:before{content:"\f027"}.fa-volume-mute:before{content:"\f6a9"}.fa-volume-off:before{content:"\f026"}.fa-volume-up:before{content:"\f028"}.fa-vote-yea:before{content:"\f772"}.fa-vr-cardboard:before{content:"\f729"}.fa-vuejs:before{content:"\f41f"}.fa-walking:before{content:"\f554"}.fa-wallet:before{content:"\f555"}.fa-warehouse:before{content:"\f494"}.fa-water:before{content:"\f773"}.fa-wave-square:before{content:"\f83e"}.fa-waze:before{content:"\f83f"}.fa-weebly:before{content:"\f5cc"}.fa-weibo:before{content:"\f18a"}.fa-weight:before{content:"\f496"}.fa-weight-hanging:before{content:"\f5cd"}.fa-weixin:before{content:"\f1d7"}.fa-whatsapp:before{content:"\f232"}.fa-whatsapp-square:before{content:"\f40c"}.fa-wheelchair:before{content:"\f193"}.fa-whmcs:before{content:"\f40d"}.fa-wifi:before{content:"\f1eb"}.fa-wikipedia-w:before{content:"\f266"}.fa-wind:before{content:"\f72e"}.fa-window-close:before{content:"\f410"}.fa-window-maximize:before{content:"\f2d0"}.fa-window-minimize:before{content:"\f2d1"}.fa-window-restore:before{content:"\f2d2"}.fa-windows:before{content:"\f17a"}.fa-wine-bottle:before{content:"\f72f"}.fa-wine-glass:before{content:"\f4e3"}.fa-wine-glass-alt:before{content:"\f5ce"}.fa-wix:before{content:"\f5cf"}.fa-wizards-of-the-coast:before{content:"\f730"}.fa-wolf-pack-battalion:before{content:"\f514"}.fa-won-sign:before{content:"\f159"}.fa-wordpress:before{content:"\f19a"}.fa-wordpress-simple:before{content:"\f411"}.fa-wpbeginner:before{content:"\f297"}.fa-wpexplorer:before{content:"\f2de"}.fa-wpforms:before{content:"\f298"}.fa-wpressr:before{content:"\f3e4"}.fa-wrench:before{content:"\f0ad"}.fa-x-ray:before{content:"\f497"}.fa-xbox:before{content:"\f412"}.fa-xing:before{content:"\f168"}.fa-xing-square:before{content:"\f169"}.fa-y-combinator:before{content:"\f23b"}.fa-yahoo:before{content:"\f19e"}.fa-yammer:before{content:"\f840"}.fa-yandex:before{content:"\f413"}.fa-yandex-international:before{content:"\f414"}.fa-yarn:before{content:"\f7e3"}.fa-yelp:before{content:"\f1e9"}.fa-yen-sign:before{content:"\f157"}.fa-yin-yang:before{content:"\f6ad"}.fa-yoast:before{content:"\f2b1"}.fa-youtube:before{content:"\f167"}.fa-youtube-square:before{content:"\f431"}.fa-zhihu:before{content:"\f63f"}.sr-only{border:0;clip:rect(0,0,0,0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}.sr-only-focusable:active,.sr-only-focusable:focus{clip:auto;height:auto;margin:0;overflow:visible;position:static;width:auto}@font-face{font-family:'Font Awesome 5 Brands';font-style:normal;font-weight:400;font-display:auto;src:url(../fonts/fa-brands-400.eot);src:url(../fonts/fa-brands-400.eot?#iefix) format("embedded-opentype"),url(../fonts/fa-brands-400.woff2) format("woff2"),url(../fonts/fa-brands-400.woff) format("woff"),url(../fonts/fa-brands-400.ttf) format("truetype"),url(../fonts/fa-brands-400.svg#fontawesome) format("svg")}.fab{font-family:'Font Awesome 5 Brands'}@font-face{font-family:'Font Awesome 5 Free';font-style:normal;font-weight:400;font-display:auto;src:url(../fonts/fa-regular-400.eot);src:url(../fonts/fa-regular-400.eot?#iefix) format("embedded-opentype"),url(../fonts/fa-regular-400.woff2) format("woff2"),url(../fonts/fa-regular-400.woff) format("woff"),url(../fonts/fa-regular-400.ttf) format("truetype"),url(../fonts/fa-regular-400.svg#fontawesome) format("svg")}.far{font-family:'Font Awesome 5 Free';font-weight:400}@font-face{font-family:'Font Awesome 5 Free';font-style:normal;font-weight:900;font-display:auto;src:url(../fonts/fa-solid-900.eot);src:url(../fonts/fa-solid-900.eot?#iefix) format("embedded-opentype"),url(../fonts/fa-solid-900.woff2) format("woff2"),url(../fonts/fa-solid-900.woff) format("woff"),url(../fonts/fa-solid-900.ttf) format("truetype"),url(../fonts/fa-solid-900.svg#fontawesome) format("svg")}.fa,.fas{font-family:'Font Awesome 5 Free';font-weight:900}@font-face{font-family:dripicons-v2;src:url(../fonts/dripicons-v2.eot);src:url(../fonts/dripicons-v2.eot?#iefix) format("embedded-opentype"),url(../fonts/dripicons-v2.woff) format("woff"),url(../fonts/dripicons-v2.ttf) format("truetype"),url(../fonts/dripicons-v2.svg#dripicons-v2) format("svg");font-weight:400;font-style:normal}[data-icon]:before{font-family:dripicons-v2!important;content:attr(data-icon);font-style:normal!important;font-weight:400!important;font-variant:normal!important;text-transform:none!important;speak:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}[class*=" dripicons-"]:before,[class^=dripicons-]:before{font-family:dripicons-v2!important;font-style:normal!important;font-weight:400!important;font-variant:normal!important;text-transform:none!important;speak:none;line-height:inherit;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.dripicons-alarm:before{content:"\61"}.dripicons-align-center:before{content:"\62"}.dripicons-align-justify:before{content:"\63"}.dripicons-align-left:before{content:"\64"}.dripicons-align-right:before{content:"\65"}.dripicons-anchor:before{content:"\66"}.dripicons-archive:before{content:"\67"}.dripicons-arrow-down:before{content:"\68"}.dripicons-arrow-left:before{content:"\69"}.dripicons-arrow-right:before{content:"\6a"}.dripicons-arrow-thin-down:before{content:"\6b"}.dripicons-arrow-thin-left:before{content:"\6c"}.dripicons-arrow-thin-right:before{content:"\6d"}.dripicons-arrow-thin-up:before{content:"\6e"}.dripicons-arrow-up:before{content:"\6f"}.dripicons-article:before{content:"\70"}.dripicons-backspace:before{content:"\71"}.dripicons-basket:before{content:"\72"}.dripicons-basketball:before{content:"\73"}.dripicons-battery-empty:before{content:"\74"}.dripicons-battery-full:before{content:"\75"}.dripicons-battery-low:before{content:"\76"}.dripicons-battery-medium:before{content:"\77"}.dripicons-bell:before{content:"\78"}.dripicons-blog:before{content:"\79"}.dripicons-bluetooth:before{content:"\7a"}.dripicons-bold:before{content:"\41"}.dripicons-bookmark:before{content:"\42"}.dripicons-bookmarks:before{content:"\43"}.dripicons-box:before{content:"\44"}.dripicons-briefcase:before{content:"\45"}.dripicons-brightness-low:before{content:"\46"}.dripicons-brightness-max:before{content:"\47"}.dripicons-brightness-medium:before{content:"\48"}.dripicons-broadcast:before{content:"\49"}.dripicons-browser:before{content:"\4a"}.dripicons-browser-upload:before{content:"\4b"}.dripicons-brush:before{content:"\4c"}.dripicons-calendar:before{content:"\4d"}.dripicons-camcorder:before{content:"\4e"}.dripicons-camera:before{content:"\4f"}.dripicons-card:before{content:"\50"}.dripicons-cart:before{content:"\51"}.dripicons-checklist:before{content:"\52"}.dripicons-checkmark:before{content:"\53"}.dripicons-chevron-down:before{content:"\54"}.dripicons-chevron-left:before{content:"\55"}.dripicons-chevron-right:before{content:"\56"}.dripicons-chevron-up:before{content:"\57"}.dripicons-clipboard:before{content:"\58"}.dripicons-clock:before{content:"\59"}.dripicons-clockwise:before{content:"\5a"}.dripicons-cloud:before{content:"\30"}.dripicons-cloud-download:before{content:"\31"}.dripicons-cloud-upload:before{content:"\32"}.dripicons-code:before{content:"\33"}.dripicons-contract:before{content:"\34"}.dripicons-contract-2:before{content:"\35"}.dripicons-conversation:before{content:"\36"}.dripicons-copy:before{content:"\37"}.dripicons-crop:before{content:"\38"}.dripicons-cross:before{content:"\39"}.dripicons-crosshair:before{content:"\21"}.dripicons-cutlery:before{content:"\22"}.dripicons-device-desktop:before{content:"\23"}.dripicons-device-mobile:before{content:"\24"}.dripicons-device-tablet:before{content:"\25"}.dripicons-direction:before{content:"\26"}.dripicons-disc:before{content:"\27"}.dripicons-document:before{content:"\28"}.dripicons-document-delete:before{content:"\29"}.dripicons-document-edit:before{content:"\2a"}.dripicons-document-new:before{content:"\2b"}.dripicons-document-remove:before{content:"\2c"}.dripicons-dot:before{content:"\2d"}.dripicons-dots-2:before{content:"\2e"}.dripicons-dots-3:before{content:"\2f"}.dripicons-download:before{content:"\3a"}.dripicons-duplicate:before{content:"\3b"}.dripicons-enter:before{content:"\3c"}.dripicons-exit:before{content:"\3d"}.dripicons-expand:before{content:"\3e"}.dripicons-expand-2:before{content:"\3f"}.dripicons-experiment:before{content:"\40"}.dripicons-export:before{content:"\5b"}.dripicons-feed:before{content:"\5d"}.dripicons-flag:before{content:"\5e"}.dripicons-flashlight:before{content:"\5f"}.dripicons-folder:before{content:"\60"}.dripicons-folder-open:before{content:"\7b"}.dripicons-forward:before{content:"\7c"}.dripicons-gaming:before{content:"\7d"}.dripicons-gear:before{content:"\7e"}.dripicons-graduation:before{content:"\5c"}.dripicons-graph-bar:before{content:"\e000"}.dripicons-graph-line:before{content:"\e001"}.dripicons-graph-pie:before{content:"\e002"}.dripicons-headset:before{content:"\e003"}.dripicons-heart:before{content:"\e004"}.dripicons-help:before{content:"\e005"}.dripicons-home:before{content:"\e006"}.dripicons-hourglass:before{content:"\e007"}.dripicons-inbox:before{content:"\e008"}.dripicons-information:before{content:"\e009"}.dripicons-italic:before{content:"\e00a"}.dripicons-jewel:before{content:"\e00b"}.dripicons-lifting:before{content:"\e00c"}.dripicons-lightbulb:before{content:"\e00d"}.dripicons-link:before{content:"\e00e"}.dripicons-link-broken:before{content:"\e00f"}.dripicons-list:before{content:"\e010"}.dripicons-loading:before{content:"\e011"}.dripicons-location:before{content:"\e012"}.dripicons-lock:before{content:"\e013"}.dripicons-lock-open:before{content:"\e014"}.dripicons-mail:before{content:"\e015"}.dripicons-map:before{content:"\e016"}.dripicons-media-loop:before{content:"\e017"}.dripicons-media-next:before{content:"\e018"}.dripicons-media-pause:before{content:"\e019"}.dripicons-media-play:before{content:"\e01a"}.dripicons-media-previous:before{content:"\e01b"}.dripicons-media-record:before{content:"\e01c"}.dripicons-media-shuffle:before{content:"\e01d"}.dripicons-media-stop:before{content:"\e01e"}.dripicons-medical:before{content:"\e01f"}.dripicons-menu:before{content:"\e020"}.dripicons-message:before{content:"\e021"}.dripicons-meter:before{content:"\e022"}.dripicons-microphone:before{content:"\e023"}.dripicons-minus:before{content:"\e024"}.dripicons-monitor:before{content:"\e025"}.dripicons-move:before{content:"\e026"}.dripicons-music:before{content:"\e027"}.dripicons-network-1:before{content:"\e028"}.dripicons-network-2:before{content:"\e029"}.dripicons-network-3:before{content:"\e02a"}.dripicons-network-4:before{content:"\e02b"}.dripicons-network-5:before{content:"\e02c"}.dripicons-pamphlet:before{content:"\e02d"}.dripicons-paperclip:before{content:"\e02e"}.dripicons-pencil:before{content:"\e02f"}.dripicons-phone:before{content:"\e030"}.dripicons-photo:before{content:"\e031"}.dripicons-photo-group:before{content:"\e032"}.dripicons-pill:before{content:"\e033"}.dripicons-pin:before{content:"\e034"}.dripicons-plus:before{content:"\e035"}.dripicons-power:before{content:"\e036"}.dripicons-preview:before{content:"\e037"}.dripicons-print:before{content:"\e038"}.dripicons-pulse:before{content:"\e039"}.dripicons-question:before{content:"\e03a"}.dripicons-reply:before{content:"\e03b"}.dripicons-reply-all:before{content:"\e03c"}.dripicons-return:before{content:"\e03d"}.dripicons-retweet:before{content:"\e03e"}.dripicons-rocket:before{content:"\e03f"}.dripicons-scale:before{content:"\e040"}.dripicons-search:before{content:"\e041"}.dripicons-shopping-bag:before{content:"\e042"}.dripicons-skip:before{content:"\e043"}.dripicons-stack:before{content:"\e044"}.dripicons-star:before{content:"\e045"}.dripicons-stopwatch:before{content:"\e046"}.dripicons-store:before{content:"\e047"}.dripicons-suitcase:before{content:"\e048"}.dripicons-swap:before{content:"\e049"}.dripicons-tag:before{content:"\e04a"}.dripicons-tag-delete:before{content:"\e04b"}.dripicons-tags:before{content:"\e04c"}.dripicons-thumbs-down:before{content:"\e04d"}.dripicons-thumbs-up:before{content:"\e04e"}.dripicons-ticket:before{content:"\e04f"}.dripicons-time-reverse:before{content:"\e050"}.dripicons-to-do:before{content:"\e051"}.dripicons-toggles:before{content:"\e052"}.dripicons-trash:before{content:"\e053"}.dripicons-trophy:before{content:"\e054"}.dripicons-upload:before{content:"\e055"}.dripicons-user:before{content:"\e056"}.dripicons-user-group:before{content:"\e057"}.dripicons-user-id:before{content:"\e058"}.dripicons-vibrate:before{content:"\e059"}.dripicons-view-apps:before{content:"\e05a"}.dripicons-view-list:before{content:"\e05b"}.dripicons-view-list-large:before{content:"\e05c"}.dripicons-view-thumb:before{content:"\e05d"}.dripicons-volume-full:before{content:"\e05e"}.dripicons-volume-low:before{content:"\e05f"}.dripicons-volume-medium:before{content:"\e060"}.dripicons-volume-off:before{content:"\e061"}.dripicons-wallet:before{content:"\e062"}.dripicons-warning:before{content:"\e063"}.dripicons-web:before{content:"\e064"}.dripicons-weight:before{content:"\e065"}.dripicons-wifi:before{content:"\e066"}.dripicons-wrong:before{content:"\e067"}.dripicons-zoom-in:before{content:"\e068"}.dripicons-zoom-out:before{content:"\e069"}@font-face{font-family:themify;src:url(../fonts/themify.eot?-fvbane);src:url(../fonts/themify.eot?#iefix-fvbane) format("embedded-opentype"),url(../fonts/themify.woff?-fvbane) format("woff"),url(../fonts/themify.ttf?-fvbane) format("truetype"),url(../fonts/themify.svg?-fvbane#themify) format("svg");font-weight:400;font-style:normal}[class*=" ti-"],[class^=ti-]{font-family:themify;speak:none;font-style:normal;font-weight:400;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.ti-wand:before{content:"\e600"}.ti-volume:before{content:"\e601"}.ti-user:before{content:"\e602"}.ti-unlock:before{content:"\e603"}.ti-unlink:before{content:"\e604"}.ti-trash:before{content:"\e605"}.ti-thought:before{content:"\e606"}.ti-target:before{content:"\e607"}.ti-tag:before{content:"\e608"}.ti-tablet:before{content:"\e609"}.ti-star:before{content:"\e60a"}.ti-spray:before{content:"\e60b"}.ti-signal:before{content:"\e60c"}.ti-shopping-cart:before{content:"\e60d"}.ti-shopping-cart-full:before{content:"\e60e"}.ti-settings:before{content:"\e60f"}.ti-search:before{content:"\e610"}.ti-zoom-in:before{content:"\e611"}.ti-zoom-out:before{content:"\e612"}.ti-cut:before{content:"\e613"}.ti-ruler:before{content:"\e614"}.ti-ruler-pencil:before{content:"\e615"}.ti-ruler-alt:before{content:"\e616"}.ti-bookmark:before{content:"\e617"}.ti-bookmark-alt:before{content:"\e618"}.ti-reload:before{content:"\e619"}.ti-plus:before{content:"\e61a"}.ti-pin:before{content:"\e61b"}.ti-pencil:before{content:"\e61c"}.ti-pencil-alt:before{content:"\e61d"}.ti-paint-roller:before{content:"\e61e"}.ti-paint-bucket:before{content:"\e61f"}.ti-na:before{content:"\e620"}.ti-mobile:before{content:"\e621"}.ti-minus:before{content:"\e622"}.ti-medall:before{content:"\e623"}.ti-medall-alt:before{content:"\e624"}.ti-marker:before{content:"\e625"}.ti-marker-alt:before{content:"\e626"}.ti-arrow-up:before{content:"\e627"}.ti-arrow-right:before{content:"\e628"}.ti-arrow-left:before{content:"\e629"}.ti-arrow-down:before{content:"\e62a"}.ti-lock:before{content:"\e62b"}.ti-location-arrow:before{content:"\e62c"}.ti-link:before{content:"\e62d"}.ti-layout:before{content:"\e62e"}.ti-layers:before{content:"\e62f"}.ti-layers-alt:before{content:"\e630"}.ti-key:before{content:"\e631"}.ti-import:before{content:"\e632"}.ti-image:before{content:"\e633"}.ti-heart:before{content:"\e634"}.ti-heart-broken:before{content:"\e635"}.ti-hand-stop:before{content:"\e636"}.ti-hand-open:before{content:"\e637"}.ti-hand-drag:before{content:"\e638"}.ti-folder:before{content:"\e639"}.ti-flag:before{content:"\e63a"}.ti-flag-alt:before{content:"\e63b"}.ti-flag-alt-2:before{content:"\e63c"}.ti-eye:before{content:"\e63d"}.ti-export:before{content:"\e63e"}.ti-exchange-vertical:before{content:"\e63f"}.ti-desktop:before{content:"\e640"}.ti-cup:before{content:"\e641"}.ti-crown:before{content:"\e642"}.ti-comments:before{content:"\e643"}.ti-comment:before{content:"\e644"}.ti-comment-alt:before{content:"\e645"}.ti-close:before{content:"\e646"}.ti-clip:before{content:"\e647"}.ti-angle-up:before{content:"\e648"}.ti-angle-right:before{content:"\e649"}.ti-angle-left:before{content:"\e64a"}.ti-angle-down:before{content:"\e64b"}.ti-check:before{content:"\e64c"}.ti-check-box:before{content:"\e64d"}.ti-camera:before{content:"\e64e"}.ti-announcement:before{content:"\e64f"}.ti-brush:before{content:"\e650"}.ti-briefcase:before{content:"\e651"}.ti-bolt:before{content:"\e652"}.ti-bolt-alt:before{content:"\e653"}.ti-blackboard:before{content:"\e654"}.ti-bag:before{content:"\e655"}.ti-move:before{content:"\e656"}.ti-arrows-vertical:before{content:"\e657"}.ti-arrows-horizontal:before{content:"\e658"}.ti-fullscreen:before{content:"\e659"}.ti-arrow-top-right:before{content:"\e65a"}.ti-arrow-top-left:before{content:"\e65b"}.ti-arrow-circle-up:before{content:"\e65c"}.ti-arrow-circle-right:before{content:"\e65d"}.ti-arrow-circle-left:before{content:"\e65e"}.ti-arrow-circle-down:before{content:"\e65f"}.ti-angle-double-up:before{content:"\e660"}.ti-angle-double-right:before{content:"\e661"}.ti-angle-double-left:before{content:"\e662"}.ti-angle-double-down:before{content:"\e663"}.ti-zip:before{content:"\e664"}.ti-world:before{content:"\e665"}.ti-wheelchair:before{content:"\e666"}.ti-view-list:before{content:"\e667"}.ti-view-list-alt:before{content:"\e668"}.ti-view-grid:before{content:"\e669"}.ti-uppercase:before{content:"\e66a"}.ti-upload:before{content:"\e66b"}.ti-underline:before{content:"\e66c"}.ti-truck:before{content:"\e66d"}.ti-timer:before{content:"\e66e"}.ti-ticket:before{content:"\e66f"}.ti-thumb-up:before{content:"\e670"}.ti-thumb-down:before{content:"\e671"}.ti-text:before{content:"\e672"}.ti-stats-up:before{content:"\e673"}.ti-stats-down:before{content:"\e674"}.ti-split-v:before{content:"\e675"}.ti-split-h:before{content:"\e676"}.ti-smallcap:before{content:"\e677"}.ti-shine:before{content:"\e678"}.ti-shift-right:before{content:"\e679"}.ti-shift-left:before{content:"\e67a"}.ti-shield:before{content:"\e67b"}.ti-notepad:before{content:"\e67c"}.ti-server:before{content:"\e67d"}.ti-quote-right:before{content:"\e67e"}.ti-quote-left:before{content:"\e67f"}.ti-pulse:before{content:"\e680"}.ti-printer:before{content:"\e681"}.ti-power-off:before{content:"\e682"}.ti-plug:before{content:"\e683"}.ti-pie-chart:before{content:"\e684"}.ti-paragraph:before{content:"\e685"}.ti-panel:before{content:"\e686"}.ti-package:before{content:"\e687"}.ti-music:before{content:"\e688"}.ti-music-alt:before{content:"\e689"}.ti-mouse:before{content:"\e68a"}.ti-mouse-alt:before{content:"\e68b"}.ti-money:before{content:"\e68c"}.ti-microphone:before{content:"\e68d"}.ti-menu:before{content:"\e68e"}.ti-menu-alt:before{content:"\e68f"}.ti-map:before{content:"\e690"}.ti-map-alt:before{content:"\e691"}.ti-loop:before{content:"\e692"}.ti-location-pin:before{content:"\e693"}.ti-list:before{content:"\e694"}.ti-light-bulb:before{content:"\e695"}.ti-Italic:before{content:"\e696"}.ti-info:before{content:"\e697"}.ti-infinite:before{content:"\e698"}.ti-id-badge:before{content:"\e699"}.ti-hummer:before{content:"\e69a"}.ti-home:before{content:"\e69b"}.ti-help:before{content:"\e69c"}.ti-headphone:before{content:"\e69d"}.ti-harddrives:before{content:"\e69e"}.ti-harddrive:before{content:"\e69f"}.ti-gift:before{content:"\e6a0"}.ti-game:before{content:"\e6a1"}.ti-filter:before{content:"\e6a2"}.ti-files:before{content:"\e6a3"}.ti-file:before{content:"\e6a4"}.ti-eraser:before{content:"\e6a5"}.ti-envelope:before{content:"\e6a6"}.ti-download:before{content:"\e6a7"}.ti-direction:before{content:"\e6a8"}.ti-direction-alt:before{content:"\e6a9"}.ti-dashboard:before{content:"\e6aa"}.ti-control-stop:before{content:"\e6ab"}.ti-control-shuffle:before{content:"\e6ac"}.ti-control-play:before{content:"\e6ad"}.ti-control-pause:before{content:"\e6ae"}.ti-control-forward:before{content:"\e6af"}.ti-control-backward:before{content:"\e6b0"}.ti-cloud:before{content:"\e6b1"}.ti-cloud-up:before{content:"\e6b2"}.ti-cloud-down:before{content:"\e6b3"}.ti-clipboard:before{content:"\e6b4"}.ti-car:before{content:"\e6b5"}.ti-calendar:before{content:"\e6b6"}.ti-book:before{content:"\e6b7"}.ti-bell:before{content:"\e6b8"}.ti-basketball:before{content:"\e6b9"}.ti-bar-chart:before{content:"\e6ba"}.ti-bar-chart-alt:before{content:"\e6bb"}.ti-back-right:before{content:"\e6bc"}.ti-back-left:before{content:"\e6bd"}.ti-arrows-corner:before{content:"\e6be"}.ti-archive:before{content:"\e6bf"}.ti-anchor:before{content:"\e6c0"}.ti-align-right:before{content:"\e6c1"}.ti-align-left:before{content:"\e6c2"}.ti-align-justify:before{content:"\e6c3"}.ti-align-center:before{content:"\e6c4"}.ti-alert:before{content:"\e6c5"}.ti-alarm-clock:before{content:"\e6c6"}.ti-agenda:before{content:"\e6c7"}.ti-write:before{content:"\e6c8"}.ti-window:before{content:"\e6c9"}.ti-widgetized:before{content:"\e6ca"}.ti-widget:before{content:"\e6cb"}.ti-widget-alt:before{content:"\e6cc"}.ti-wallet:before{content:"\e6cd"}.ti-video-clapper:before{content:"\e6ce"}.ti-video-camera:before{content:"\e6cf"}.ti-vector:before{content:"\e6d0"}.ti-themify-logo:before{content:"\e6d1"}.ti-themify-favicon:before{content:"\e6d2"}.ti-themify-favicon-alt:before{content:"\e6d3"}.ti-support:before{content:"\e6d4"}.ti-stamp:before{content:"\e6d5"}.ti-split-v-alt:before{content:"\e6d6"}.ti-slice:before{content:"\e6d7"}.ti-shortcode:before{content:"\e6d8"}.ti-shift-right-alt:before{content:"\e6d9"}.ti-shift-left-alt:before{content:"\e6da"}.ti-ruler-alt-2:before{content:"\e6db"}.ti-receipt:before{content:"\e6dc"}.ti-pin2:before{content:"\e6dd"}.ti-pin-alt:before{content:"\e6de"}.ti-pencil-alt2:before{content:"\e6df"}.ti-palette:before{content:"\e6e0"}.ti-more:before{content:"\e6e1"}.ti-more-alt:before{content:"\e6e2"}.ti-microphone-alt:before{content:"\e6e3"}.ti-magnet:before{content:"\e6e4"}.ti-line-double:before{content:"\e6e5"}.ti-line-dotted:before{content:"\e6e6"}.ti-line-dashed:before{content:"\e6e7"}.ti-layout-width-full:before{content:"\e6e8"}.ti-layout-width-default:before{content:"\e6e9"}.ti-layout-width-default-alt:before{content:"\e6ea"}.ti-layout-tab:before{content:"\e6eb"}.ti-layout-tab-window:before{content:"\e6ec"}.ti-layout-tab-v:before{content:"\e6ed"}.ti-layout-tab-min:before{content:"\e6ee"}.ti-layout-slider:before{content:"\e6ef"}.ti-layout-slider-alt:before{content:"\e6f0"}.ti-layout-sidebar-right:before{content:"\e6f1"}.ti-layout-sidebar-none:before{content:"\e6f2"}.ti-layout-sidebar-left:before{content:"\e6f3"}.ti-layout-placeholder:before{content:"\e6f4"}.ti-layout-menu:before{content:"\e6f5"}.ti-layout-menu-v:before{content:"\e6f6"}.ti-layout-menu-separated:before{content:"\e6f7"}.ti-layout-menu-full:before{content:"\e6f8"}.ti-layout-media-right-alt:before{content:"\e6f9"}.ti-layout-media-right:before{content:"\e6fa"}.ti-layout-media-overlay:before{content:"\e6fb"}.ti-layout-media-overlay-alt:before{content:"\e6fc"}.ti-layout-media-overlay-alt-2:before{content:"\e6fd"}.ti-layout-media-left-alt:before{content:"\e6fe"}.ti-layout-media-left:before{content:"\e6ff"}.ti-layout-media-center-alt:before{content:"\e700"}.ti-layout-media-center:before{content:"\e701"}.ti-layout-list-thumb:before{content:"\e702"}.ti-layout-list-thumb-alt:before{content:"\e703"}.ti-layout-list-post:before{content:"\e704"}.ti-layout-list-large-image:before{content:"\e705"}.ti-layout-line-solid:before{content:"\e706"}.ti-layout-grid4:before{content:"\e707"}.ti-layout-grid3:before{content:"\e708"}.ti-layout-grid2:before{content:"\e709"}.ti-layout-grid2-thumb:before{content:"\e70a"}.ti-layout-cta-right:before{content:"\e70b"}.ti-layout-cta-left:before{content:"\e70c"}.ti-layout-cta-center:before{content:"\e70d"}.ti-layout-cta-btn-right:before{content:"\e70e"}.ti-layout-cta-btn-left:before{content:"\e70f"}.ti-layout-column4:before{content:"\e710"}.ti-layout-column3:before{content:"\e711"}.ti-layout-column2:before{content:"\e712"}.ti-layout-accordion-separated:before{content:"\e713"}.ti-layout-accordion-merged:before{content:"\e714"}.ti-layout-accordion-list:before{content:"\e715"}.ti-ink-pen:before{content:"\e716"}.ti-info-alt:before{content:"\e717"}.ti-help-alt:before{content:"\e718"}.ti-headphone-alt:before{content:"\e719"}.ti-hand-point-up:before{content:"\e71a"}.ti-hand-point-right:before{content:"\e71b"}.ti-hand-point-left:before{content:"\e71c"}.ti-hand-point-down:before{content:"\e71d"}.ti-gallery:before{content:"\e71e"}.ti-face-smile:before{content:"\e71f"}.ti-face-sad:before{content:"\e720"}.ti-credit-card:before{content:"\e721"}.ti-control-skip-forward:before{content:"\e722"}.ti-control-skip-backward:before{content:"\e723"}.ti-control-record:before{content:"\e724"}.ti-control-eject:before{content:"\e725"}.ti-comments-smiley:before{content:"\e726"}.ti-brush-alt:before{content:"\e727"}.ti-youtube:before{content:"\e728"}.ti-vimeo:before{content:"\e729"}.ti-twitter:before{content:"\e72a"}.ti-time:before{content:"\e72b"}.ti-tumblr:before{content:"\e72c"}.ti-skype:before{content:"\e72d"}.ti-share:before{content:"\e72e"}.ti-share-alt:before{content:"\e72f"}.ti-rocket:before{content:"\e730"}.ti-pinterest:before{content:"\e731"}.ti-new-window:before{content:"\e732"}.ti-microsoft:before{content:"\e733"}.ti-list-ol:before{content:"\e734"}.ti-linkedin:before{content:"\e735"}.ti-layout-sidebar-2:before{content:"\e736"}.ti-layout-grid4-alt:before{content:"\e737"}.ti-layout-grid3-alt:before{content:"\e738"}.ti-layout-grid2-alt:before{content:"\e739"}.ti-layout-column4-alt:before{content:"\e73a"}.ti-layout-column3-alt:before{content:"\e73b"}.ti-layout-column2-alt:before{content:"\e73c"}.ti-instagram:before{content:"\e73d"}.ti-google:before{content:"\e73e"}.ti-github:before{content:"\e73f"}.ti-flickr:before{content:"\e740"}.ti-facebook:before{content:"\e741"}.ti-dropbox:before{content:"\e742"}.ti-dribbble:before{content:"\e743"}.ti-apple:before{content:"\e744"}.ti-android:before{content:"\e745"}.ti-save:before{content:"\e746"}.ti-save-alt:before{content:"\e747"}.ti-yahoo:before{content:"\e748"}.ti-wordpress:before{content:"\e749"}.ti-vimeo-alt:before{content:"\e74a"}.ti-twitter-alt:before{content:"\e74b"}.ti-tumblr-alt:before{content:"\e74c"}.ti-trello:before{content:"\e74d"}.ti-stack-overflow:before{content:"\e74e"}.ti-soundcloud:before{content:"\e74f"}.ti-sharethis:before{content:"\e750"}.ti-sharethis-alt:before{content:"\e751"}.ti-reddit:before{content:"\e752"}.ti-pinterest-alt:before{content:"\e753"}.ti-microsoft-alt:before{content:"\e754"}.ti-linux:before{content:"\e755"}.ti-jsfiddle:before{content:"\e756"}.ti-joomla:before{content:"\e757"}.ti-html5:before{content:"\e758"}.ti-flickr-alt:before{content:"\e759"}.ti-email:before{content:"\e75a"}.ti-drupal:before{content:"\e75b"}.ti-dropbox-alt:before{content:"\e75c"}.ti-css3:before{content:"\e75d"}.ti-rss:before{content:"\e75e"}.ti-rss-alt:before{content:"\e75f"}
/*# sourceMappingURL=icons.min.css.map */



            </style>

        <!-- Icons Css Ends -->

        <!-- App Css Starts -->

            <!-- File Name app.min.css -->

            <style>
                
                #page-topbar {
                  position: fixed;
                  top: 0;
                  right: 0;
                  left: 0;
                  z-index: 1002;
                  background-color: #ffffff;
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); }

                .navbar-header {
                  display: -webkit-box;
                  display: -ms-flexbox;
                  display: flex;
                  -ms-flex-pack: justify;
                  -webkit-box-pack: justify;
                          justify-content: space-between;
                  -webkit-box-align: center;
                      -ms-flex-align: center;
                          align-items: center;
                  margin: 0 auto;
                  height: 70px;
                  padding: 0 calc(24px / 2) 0 0; }
                  .navbar-header .dropdown.show .header-item {
                    background-color: #f8f9fa; }

                .navbar-brand-box {
                  padding: 0 1.5rem;
                  text-align: center;
                  width: 250px; }

                .logo {
                  line-height: 70px; }
                  .logo .logo-sm {
                    display: none; }

                .logo-light {
                  display: none; }

                .page-title-box {
                  padding-bottom: 24px; }
                  .page-title-box .breadcrumb {
                    background-color: transparent;
                    padding: 0; }

                /* Search */
                .app-search {
                  padding: calc(32px / 2) 0; }
                  .app-search .form-control {
                    border: none;
                    height: 38px;
                    padding-left: 40px;
                    padding-right: 20px;
                    background-color: #f4f8f9;
                    -webkit-box-shadow: none;
                            box-shadow: none;
                    border-radius: 30px; }
                  .app-search span {
                    position: absolute;
                    z-index: 10;
                    font-size: 16px;
                    line-height: 38px;
                    left: 13px;
                    top: 0;
                    color: #7c8a96; }

                .megamenu-list li {
                  position: relative;
                  padding: 5px 0px; }
                  .megamenu-list li a {
                    color: #7c8a96; }

                @media (max-width: 992px) {
                  .navbar-brand-box {
                    width: auto; }
                  .logo span.logo-lg {
                    display: none; }
                  .logo span.logo-sm {
                    display: inline-block; } }

                .page-content {
                  padding: calc(70px + 24px) calc(24px / 2) 60px calc(24px / 2); }

                .header-item {
                  height: 70px;
                  -webkit-box-shadow: none !important;
                          box-shadow: none !important;
                  color: #636e75;
                  border: 0;
                  border-radius: 0px; }
                  .header-item:hover {
                    color: #636e75; }

                .header-profile-user {
                  height: 36px;
                  width: 36px;
                  background-color: #f4f8f9;
                  padding: 2px; }

                .noti-icon i {
                  font-size: 24px;
                  color: #636e75; }

                .noti-icon .badge {
                  position: absolute;
                  top: 12px; }

                .notification-item .media {
                  padding: 0.75rem 1rem; }
                  .notification-item .media:hover {
                    background-color: #f8f9fa; }

                .notification-item .user-status {
                  position: absolute;
                  right: 0px;
                  bottom: -4px;
                  font-size: 10px; }
                  .notification-item .user-status.online {
                    color: #11c46e; }
                  .notification-item .user-status.away {
                    color: #f1b44c; }
                  .notification-item .user-status.busy {
                    color: #fb4d53; }

                .dropdown-icon-item {
                  display: block;
                  border-radius: 3px;
                  line-height: 34px;
                  text-align: center;
                  padding: 15px 0 9px;
                  display: block;
                  border: 1px solid transparent;
                  color: #7c8a96; }
                  .dropdown-icon-item img {
                    height: 24px; }
                  .dropdown-icon-item span {
                    display: block;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap; }
                  .dropdown-icon-item:hover {
                    background-color: #f8f9fa; }

                .fullscreen-enable [data-toggle="fullscreen"] .mdi-fullscreen::before {
                  content: "\F294"; }

                body[data-topbar="dark"] #page-topbar, body[data-topbar="colored"] #page-topbar {
                  background-color: #27333a; }

                body[data-topbar="dark"] .navbar-header .dropdown.show .header-item, body[data-topbar="colored"] .navbar-header .dropdown.show .header-item {
                  background-color: rgba(255, 255, 255, 0.05); }

                body[data-topbar="dark"] .navbar-header .waves-effect .waves-ripple, body[data-topbar="colored"] .navbar-header .waves-effect .waves-ripple {
                  background: rgba(255, 255, 255, 0.1); }

                body[data-topbar="dark"] .header-item, body[data-topbar="colored"] .header-item {
                  color: #e9ecef; }
                  body[data-topbar="dark"] .header-item:hover, body[data-topbar="colored"] .header-item:hover {
                    color: #e9ecef; }

                body[data-topbar="dark"] .header-profile-user, body[data-topbar="colored"] .header-profile-user {
                  background-color: rgba(255, 255, 255, 0.25); }

                body[data-topbar="dark"] .noti-icon i, body[data-topbar="colored"] .noti-icon i {
                  color: #e9ecef; }

                body[data-topbar="dark"] .logo-dark, body[data-topbar="colored"] .logo-dark {
                  display: none; }

                body[data-topbar="dark"] .logo-light, body[data-topbar="colored"] .logo-light {
                  display: block; }

                body[data-topbar="dark"] .app-search .form-control, body[data-topbar="colored"] .app-search .form-control {
                  background-color: rgba(244, 248, 249, 0.07);
                  color: #fff; }

                body[data-topbar="dark"] .app-search span,
                body[data-topbar="dark"] .app-search input.form-control::-webkit-input-placeholder, body[data-topbar="colored"] .app-search span,
                body[data-topbar="colored"] .app-search input.form-control::-webkit-input-placeholder {
                  color: rgba(255, 255, 255, 0.4); }

                body[data-topbar="colored"] .navbar-brand-box, body[data-topbar="colored"] #page-topbar {
                  background-color: #3d8ef8; }

                body[data-sidebar="dark"] .navbar-brand-box {
                  background: #27333a; }

                body[data-sidebar="dark"] .logo-dark {
                  display: none; }

                body[data-sidebar="dark"] .logo-light {
                  display: block; }

                @media (max-width: 600px) {
                  .navbar-header .dropdown {
                    position: static; }
                    .navbar-header .dropdown .dropdown-menu {
                      left: 10px !important;
                      right: 10px !important; } }

                @media (max-width: 380px) {
                  .navbar-brand-box {
                    display: none; } }

                body[data-layout="horizontal"] .navbar-brand-box {
                  width: auto; }

                body[data-layout="horizontal"] .page-content {
                  margin-top: 70px;
                  padding: calc(55px + 24px) calc(24px / 2) 60px calc(24px / 2); }

                @media (max-width: 992px) {
                  body[data-layout="horizontal"] .page-content {
                    margin-top: 15px; } }

                .footer {
                  bottom: 0;
                  padding: 20px calc(24px / 2);
                  position: absolute;
                  right: 0;
                  color: #74788d;
                  left: 250px;
                  height: 60px;
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                  background-color: #ffffff; }

                @media (max-width: 992px) {
                  .footer {
                    left: 0; } }

                .vertical-collpsed .footer {
                  left: 70px; }

                body[data-layout="horizontal"] .footer {
                  left: 0 !important; }

                .right-bar {
                  background-color: #fff;
                  -webkit-box-shadow: 0 0 24px 0 rgba(0, 0, 0, 0.06), 0 1px 0 0 rgba(0, 0, 0, 0.02);
                          box-shadow: 0 0 24px 0 rgba(0, 0, 0, 0.06), 0 1px 0 0 rgba(0, 0, 0, 0.02);
                  display: block;
                  position: fixed;
                  -webkit-transition: all 200ms ease-out;
                  transition: all 200ms ease-out;
                  width: 280px;
                  z-index: 9999;
                  float: right !important;
                  right: -290px;
                  top: 0;
                  bottom: 0; }
                  .right-bar .rightbar-title {
                    background-color: #3d8ef8;
                    padding: 27px 25px;
                    color: #fff; }
                  .right-bar .right-bar-toggle {
                    background-color: #444c54;
                    height: 24px;
                    width: 24px;
                    line-height: 27px;
                    color: #fff;
                    text-align: center;
                    border-radius: 50%;
                    margin-top: -4px; }
                    .right-bar .right-bar-toggle:hover {
                      background-color: #4b545c; }
                  .right-bar .user-box {
                    padding: 25px;
                    text-align: center; }
                    .right-bar .user-box .user-img {
                      position: relative;
                      height: 64px;
                      width: 64px;
                      margin: 0 auto 15px auto; }
                      .right-bar .user-box .user-img .user-edit {
                        position: absolute;
                        right: -5px;
                        bottom: 0px;
                        height: 24px;
                        width: 24px;
                        background-color: #fff;
                        line-height: 24px;
                        border-radius: 50%;
                        -webkit-box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
                                box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175); }
                    .right-bar .user-box h5 {
                      margin-bottom: 2px; }
                      .right-bar .user-box h5 a {
                        color: #343a40; }
                  .right-bar .rightbar-nav-tab .nav-item .nav-link {
                    background-color: #fff;
                    border: none; }

                .rightbar-overlay {
                  background-color: rgba(52, 58, 64, 0.55);
                  position: absolute;
                  left: 0;
                  right: 0;
                  top: 0;
                  bottom: 0;
                  display: none;
                  z-index: 9998;
                  -webkit-transition: all .2s ease-out;
                  transition: all .2s ease-out; }

                .right-bar-enabled .right-bar {
                  right: 0; }

                .right-bar-enabled .rightbar-overlay {
                  display: block; }

                @media (max-width: 767.98px) {
                  .right-bar {
                    overflow: auto; }
                    .right-bar .slimscroll-menu {
                      height: auto !important; } }

                .metismenu {
                  margin: 0; }
                  .metismenu li {
                    display: block;
                    width: 100%; }
                  .metismenu .mm-collapse {
                    display: none; }
                    .metismenu .mm-collapse:not(.mm-show) {
                      display: none; }
                    .metismenu .mm-collapse.mm-show {
                      display: block; }
                  .metismenu .mm-collapsing {
                    position: relative;
                    height: 0;
                    overflow: hidden;
                    -webkit-transition-timing-function: ease;
                            transition-timing-function: ease;
                    -webkit-transition-duration: .35s;
                            transition-duration: .35s;
                    -webkit-transition-property: height, visibility;
                    transition-property: height, visibility; }

                .vertical-menu {
                  width: 250px;
                  z-index: 1002;
                  background: #ffffff;
                  bottom: 0;
                  margin-top: 0;
                  position: fixed;
                  top: 70px;
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); }

                .main-content {
                  margin-left: 250px;
                  overflow: hidden; }
                  .main-content .content {
                    padding: 0 15px 10px 15px;
                    margin-top: 70px; }

                #sidebar-menu {
                  padding: 10px 0 30px 0; }
                  #sidebar-menu .mm-active > .has-arrow:after {
                    -webkit-transform: rotate(-180deg);
                            transform: rotate(-180deg); }
                  #sidebar-menu .has-arrow:after {
                    content: "\F140";
                    font-family: 'Material Design Icons';
                    display: block;
                    float: right;
                    -webkit-transition: -webkit-transform .2s;
                    transition: -webkit-transform .2s;
                    transition: transform .2s;
                    transition: transform .2s, -webkit-transform .2s;
                    font-size: 1rem; }
                  #sidebar-menu ul li a {
                    display: block;
                    padding: .625rem 1.5rem;
                    color: #7c8a96;
                    position: relative;
                    font-size: 14.5px;
                    -webkit-transition: all .4s;
                    transition: all .4s; }
                    #sidebar-menu ul li a i {
                      display: inline-block;
                      min-width: 1.75rem;
                      padding-bottom: .125em;
                      font-size: 1.07rem;
                      line-height: 1.40625rem;
                      vertical-align: middle;
                      color: #7c8a96;
                      -webkit-transition: all .4s;
                      transition: all .4s; }
                    #sidebar-menu ul li a:hover {
                      color: #383c40; }
                      #sidebar-menu ul li a:hover i {
                        color: #383c40; }
                  #sidebar-menu ul li .badge {
                    margin-top: 4px; }
                  #sidebar-menu ul li ul.sub-menu {
                    padding: 0; }
                    #sidebar-menu ul li ul.sub-menu li a {
                      padding: .4rem 1.5rem .4rem 3.5rem;
                      font-size: 13.5px;
                      color: #7c8a96; }
                    #sidebar-menu ul li ul.sub-menu li ul.sub-menu {
                      padding: 0; }
                      #sidebar-menu ul li ul.sub-menu li ul.sub-menu li a {
                        padding: .4rem 1.5rem .4rem 4.5rem;
                        font-size: 13.5px; }

                .menu-title {
                  padding: 12px 20px !important;
                  letter-spacing: .05em;
                  pointer-events: none;
                  cursor: default;
                  font-size: 11px;
                  text-transform: uppercase;
                  color: #7c8a96;
                  font-weight: 600; }

                .mm-active {
                  color: #3d8ef8 !important; }
                  .mm-active .active {
                    color: #3d8ef8 !important; }
                    .mm-active .active i {
                      color: #3d8ef8 !important; }
                  .mm-active > i {
                    color: #3d8ef8 !important; }

                .sidebar-section {
                  padding: .625rem 1.5rem; }

                @media (max-width: 992px) {
                  .vertical-menu {
                    display: none; }
                  .main-content {
                    margin-left: 0 !important; }
                  body.sidebar-enable .vertical-menu {
                    display: block; } }

                .vertical-collpsed {
                  min-height: 1200px; }
                  .vertical-collpsed .main-content {
                    margin-left: 70px; }
                  .vertical-collpsed .navbar-brand-box {
                    width: 70px !important; }
                  .vertical-collpsed .logo span.logo-lg {
                    display: none; }
                  .vertical-collpsed .logo span.logo-sm {
                    display: block; }
                  .vertical-collpsed .vertical-menu {
                    position: absolute;
                    width: 70px !important;
                    z-index: 5; }
                    .vertical-collpsed .vertical-menu .simplebar-mask,
                    .vertical-collpsed .vertical-menu .simplebar-content-wrapper {
                      overflow: visible !important; }
                    .vertical-collpsed .vertical-menu .simplebar-scrollbar {
                      display: none !important; }
                    .vertical-collpsed .vertical-menu .simplebar-offset {
                      bottom: 0 !important; }
                    .vertical-collpsed .vertical-menu #sidebar-menu .menu-title,
                    .vertical-collpsed .vertical-menu #sidebar-menu .badge,
                    .vertical-collpsed .vertical-menu #sidebar-menu .collapse.in,
                    .vertical-collpsed .vertical-menu #sidebar-menu .sidebar-section {
                      display: none !important; }
                    .vertical-collpsed .vertical-menu #sidebar-menu .nav.collapse {
                      height: inherit !important; }
                    .vertical-collpsed .vertical-menu #sidebar-menu .has-arrow:after {
                      display: none; }
                    .vertical-collpsed .vertical-menu #sidebar-menu > ul > li {
                      position: relative;
                      white-space: nowrap; }
                      .vertical-collpsed .vertical-menu #sidebar-menu > ul > li > a {
                        padding: 15px 20px;
                        min-height: 55px;
                        -webkit-transition: none;
                        transition: none; }
                        .vertical-collpsed .vertical-menu #sidebar-menu > ul > li > a:hover, .vertical-collpsed .vertical-menu #sidebar-menu > ul > li > a:active, .vertical-collpsed .vertical-menu #sidebar-menu > ul > li > a:focus {
                          color: #383c40; }
                        .vertical-collpsed .vertical-menu #sidebar-menu > ul > li > a i {
                          font-size: 1.25rem;
                          margin-left: 5px; }
                        .vertical-collpsed .vertical-menu #sidebar-menu > ul > li > a span {
                          display: none;
                          padding-left: 25px; }
                      .vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > a {
                        position: relative;
                        width: calc(190px + 70px);
                        color: #3d8ef8;
                        background-color: whitesmoke;
                        -webkit-transition: none;
                        transition: none; }
                        .vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > a i {
                          color: #3d8ef8; }
                        .vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > a span {
                          display: inline; }
                      .vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > ul {
                        display: block;
                        left: 70px;
                        position: absolute;
                        width: 190px;
                        height: auto !important;
                        -webkit-box-shadow: 3px 5px 10px 0 rgba(54, 61, 71, 0.1);
                                box-shadow: 3px 5px 10px 0 rgba(54, 61, 71, 0.1); }
                        .vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > ul ul {
                          -webkit-box-shadow: 3px 5px 10px 0 rgba(54, 61, 71, 0.1);
                                  box-shadow: 3px 5px 10px 0 rgba(54, 61, 71, 0.1); }
                        .vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > ul a {
                          -webkit-box-shadow: none;
                                  box-shadow: none;
                          padding: 8px 20px;
                          position: relative;
                          width: 190px;
                          z-index: 6;
                          color: #7c8a96; }
                          .vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > ul a:hover {
                            color: #383c40; }
                    .vertical-collpsed .vertical-menu #sidebar-menu > ul ul {
                      padding: 5px 0;
                      z-index: 9999;
                      display: none;
                      background-color: #ffffff; }
                      .vertical-collpsed .vertical-menu #sidebar-menu > ul ul li:hover > ul {
                        display: block;
                        left: 190px;
                        height: auto !important;
                        margin-top: -36px;
                        position: absolute;
                        width: 190px; }
                      .vertical-collpsed .vertical-menu #sidebar-menu > ul ul li > a span.pull-right {
                        position: absolute;
                        right: 20px;
                        top: 12px;
                        -webkit-transform: rotate(270deg);
                                transform: rotate(270deg); }
                      .vertical-collpsed .vertical-menu #sidebar-menu > ul ul li.active a {
                        color: #f8f9fa; }

                body[data-sidebar="dark"] .vertical-menu {
                  background: #27333a; }

                body[data-sidebar="dark"] #sidebar-menu ul li a {
                  color: #7b919e; }
                  body[data-sidebar="dark"] #sidebar-menu ul li a i {
                    color: #7b919e; }
                  body[data-sidebar="dark"] #sidebar-menu ul li a:hover {
                    color: #d7e4ec; }
                    body[data-sidebar="dark"] #sidebar-menu ul li a:hover i {
                      color: #d7e4ec; }

                body[data-sidebar="dark"] #sidebar-menu ul li ul.sub-menu li a {
                  color: #7b919e; }
                  body[data-sidebar="dark"] #sidebar-menu ul li ul.sub-menu li a:hover {
                    color: #d7e4ec; }

                body[data-sidebar="dark"].vertical-collpsed {
                  min-height: 1200px; }
                  body[data-sidebar="dark"].vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > a {
                    background: #2b3840;
                    color: #d7e4ec; }
                    body[data-sidebar="dark"].vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > a i {
                      color: #d7e4ec; }
                  body[data-sidebar="dark"].vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > ul a {
                    color: #7b919e; }
                    body[data-sidebar="dark"].vertical-collpsed .vertical-menu #sidebar-menu > ul > li:hover > ul a:hover {
                      color: #d7e4ec; }
                  body[data-sidebar="dark"].vertical-collpsed .vertical-menu #sidebar-menu > ul ul {
                    background-color: #27333a; }
                  body[data-sidebar="dark"].vertical-collpsed .vertical-menu #sidebar-menu ul li.mm-active .active {
                    color: #d7e4ec !important; }
                    body[data-sidebar="dark"].vertical-collpsed .vertical-menu #sidebar-menu ul li.mm-active .active i {
                      color: #d7e4ec !important; }

                body[data-sidebar="dark"] .mm-active {
                  color: #d7e4ec !important; }
                  body[data-sidebar="dark"] .mm-active > i {
                    color: #d7e4ec !important; }
                  body[data-sidebar="dark"] .mm-active .active {
                    color: #d7e4ec !important; }
                    body[data-sidebar="dark"] .mm-active .active i {
                      color: #d7e4ec !important; }

                body[data-sidebar="dark"] .menu-title {
                  color: #7b919e; }

                body[data-layout="horizontal"] .main-content {
                  margin-left: 0 !important; }

                body[data-sidebar-size="small"] .navbar-brand-box {
                  width: 160px; }

                body[data-sidebar-size="small"] .vertical-menu {
                  width: 160px;
                  text-align: center; }

                body[data-sidebar-size="small"] .main-content {
                  margin-left: 160px; }

                body[data-sidebar-size="small"] .footer {
                  left: 160px; }

                body[data-sidebar-size="small"] .has-arrow:after,
                body[data-sidebar-size="small"] .badge {
                  display: none !important; }

                body[data-sidebar-size="small"] #sidebar-menu ul li.menu-title {
                  background-color: #2b3840; }

                body[data-sidebar-size="small"] #sidebar-menu ul li a i {
                  display: block; }

                body[data-sidebar-size="small"] #sidebar-menu ul li ul.sub-menu li a {
                  padding-left: 1.5rem; }

                body[data-sidebar-size="small"].vertical-collpsed .main-content {
                  margin-left: 70px; }

                body[data-sidebar-size="small"].vertical-collpsed .vertical-menu #sidebar-menu {
                  text-align: left; }
                  body[data-sidebar-size="small"].vertical-collpsed .vertical-menu #sidebar-menu > ul > li > a i {
                    display: inline-block; }

                body[data-sidebar-size="small"].vertical-collpsed .footer {
                  left: 70px; }

                .topnav {
                  background: #fff;
                  padding: 0 calc(24px / 2);
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                  margin-top: 70px;
                  position: fixed;
                  left: 0;
                  right: 0;
                  z-index: 1; }
                  .topnav .topnav-menu {
                    margin: 0;
                    padding: 0; }
                  .topnav .navbar-nav .nav-link {
                    font-size: 14px;
                    position: relative;
                    padding: 1rem 1.3rem;
                    color: #7c8a96; }
                    .topnav .navbar-nav .nav-link i {
                      font-size: 16px; }
                    .topnav .navbar-nav .nav-link:focus, .topnav .navbar-nav .nav-link:hover {
                      color: #3d8ef8;
                      background-color: transparent; }
                  .topnav .navbar-nav .dropdown-item {
                    color: #7c8a96; }
                    .topnav .navbar-nav .dropdown-item.active, .topnav .navbar-nav .dropdown-item:hover {
                      color: #3d8ef8; }
                  .topnav .navbar-nav .nav-item .nav-link.active {
                    color: #3d8ef8; }
                  .topnav .navbar-nav .dropdown.active > a {
                    color: #3d8ef8;
                    background-color: transparent; }
                  .topnav .navbar-nav .dropdown-menu {
                    padding: 0px; }

                @media (min-width: 1200px) {
                  body[data-layout="horizontal"] .container-fluid,
                  body[data-layout="horizontal"] .navbar-header {
                    max-width: 85%; } }

                @media (min-width: 992px) {
                  .topnav .navbar-nav .nav-item:first-of-type .nav-link {
                    padding-left: 0; }
                  .topnav .dropdown-item {
                    padding: .5rem 1.5rem;
                    min-width: 180px; }
                  .topnav .dropdown.mega-dropdown {
                    position: static; }
                    .topnav .dropdown.mega-dropdown .mega-dropdown-menu {
                      left: 0px;
                      right: auto; }
                  .topnav .dropdown .dropdown-menu {
                    margin-top: 0;
                    border-radius: 0 0 0.25rem 0.25rem; }
                    .topnav .dropdown .dropdown-menu .arrow-down::after {
                      right: 15px;
                      -webkit-transform: rotate(-135deg) translateY(-50%);
                              transform: rotate(-135deg) translateY(-50%);
                      position: absolute; }
                    .topnav .dropdown .dropdown-menu .dropdown .dropdown-menu {
                      position: absolute;
                      top: 0;
                      left: 100%;
                      display: none; }
                  .topnav .dropdown:hover > .dropdown-menu {
                    display: block; }
                  .topnav .dropdown:hover > .dropdown-menu > .dropdown:hover > .dropdown-menu {
                    display: block; }
                  .navbar-toggle {
                    display: none; } }

                .arrow-down {
                  display: inline-block; }
                  .arrow-down:after {
                    border-color: initial;
                    border-style: solid;
                    border-width: 0 0 1px 1px;
                    content: "";
                    height: .4em;
                    display: inline-block;
                    right: 5px;
                    top: 50%;
                    margin-left: 10px;
                    -webkit-transform: rotate(-45deg) translateY(-50%);
                            transform: rotate(-45deg) translateY(-50%);
                    -webkit-transform-origin: top;
                            transform-origin: top;
                    -webkit-transition: all .3s ease-out;
                    transition: all .3s ease-out;
                    width: .4em; }

                @media (max-width: 1199.98px) {
                  .topnav-menu .navbar-nav li:last-of-type .dropdown .dropdown-menu {
                    right: 100%;
                    left: auto; } }

                @media (max-width: 991.98px) {
                  .topnav {
                    max-height: 360px;
                    overflow-y: auto;
                    padding: 0; }
                    .topnav .navbar-nav .nav-link {
                      padding: 0.75rem 1.1rem; }
                    .topnav .dropdown .dropdown-menu {
                      background-color: transparent;
                      border: none;
                      -webkit-box-shadow: none;
                              box-shadow: none;
                      padding-left: 15px; }
                    .topnav .dropdown .dropdown-item {
                      position: relative;
                      background-color: transparent; }
                      .topnav .dropdown .dropdown-item.active, .topnav .dropdown .dropdown-item:active {
                        color: #3d8ef8; }
                    .topnav .arrow-down::after {
                      right: 15px;
                      position: absolute; } }

                body[data-layout-size="boxed"] {
                  background-color: #e7f0f2; }
                  body[data-layout-size="boxed"] #layout-wrapper {
                    background-color: #f4f8f9;
                    max-width: 1300px;
                    margin: 0 auto;
                    -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); }
                  body[data-layout-size="boxed"] #page-topbar {
                    max-width: 1300px;
                    margin: 0 auto; }
                  body[data-layout-size="boxed"] .footer {
                    margin: 0 auto;
                    max-width: calc(1300px - 250px); }
                  body[data-layout-size="boxed"].vertical-collpsed .footer {
                    max-width: calc(1300px - 70px); }

                /*!
                 * Waves v0.7.6
                 * http://fian.my.id/Waves 
                 * 
                 * Copyright 2014-2018 Alfiana E. Sibuea and other contributors 
                 * Released under the MIT license 
                 * https://github.com/fians/Waves/blob/master/LICENSE */
                .waves-effect {
                  position: relative;
                  cursor: pointer;
                  display: inline-block;
                  overflow: hidden;
                  -webkit-user-select: none;
                  -moz-user-select: none;
                  -ms-user-select: none;
                  user-select: none;
                  -webkit-tap-highlight-color: transparent; }

                .waves-effect .waves-ripple {
                  position: absolute;
                  border-radius: 50%;
                  width: 100px;
                  height: 100px;
                  margin-top: -50px;
                  margin-left: -50px;
                  opacity: 0;
                  background: rgba(0, 0, 0, 0.2);
                  background: radial-gradient(rgba(0, 0, 0, 0.2) 0, rgba(0, 0, 0, 0.3) 40%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0.5) 60%, rgba(255, 255, 255, 0) 70%);
                  -webkit-transition: all 0.5s ease-out;
                  transition: all 0.5s ease-out;
                  -webkit-transition-property: -webkit-transform, opacity;
                  -webkit-transition-property: opacity, -webkit-transform;
                  transition-property: opacity, -webkit-transform;
                  transition-property: transform, opacity;
                  transition-property: transform, opacity, -webkit-transform;
                  -webkit-transform: scale(0) translate(0, 0);
                  transform: scale(0) translate(0, 0);
                  pointer-events: none; }

                .waves-effect.waves-light .waves-ripple {
                  background: rgba(255, 255, 255, 0.4);
                  background: radial-gradient(rgba(255, 255, 255, 0.2) 0, rgba(255, 255, 255, 0.3) 40%, rgba(255, 255, 255, 0.4) 50%, rgba(255, 255, 255, 0.5) 60%, rgba(255, 255, 255, 0) 70%); }

                .waves-effect.waves-classic .waves-ripple {
                  background: rgba(0, 0, 0, 0.2); }

                .waves-effect.waves-classic.waves-light .waves-ripple {
                  background: rgba(255, 255, 255, 0.4); }

                .waves-notransition {
                  -webkit-transition: none !important;
                  transition: none !important; }

                .waves-button,
                .waves-circle {
                  -webkit-transform: translateZ(0);
                  transform: translateZ(0);
                  -webkit-mask-image: -webkit-radial-gradient(circle, white 100%, black 100%); }

                .waves-button,
                .waves-button:hover,
                .waves-button:visited,
                .waves-button-input {
                  white-space: nowrap;
                  vertical-align: middle;
                  cursor: pointer;
                  border: none;
                  outline: none;
                  color: inherit;
                  background-color: rgba(0, 0, 0, 0);
                  font-size: 1em;
                  line-height: 1em;
                  text-align: center;
                  text-decoration: none;
                  z-index: 1; }

                .waves-button {
                  padding: 0.85em 1.1em;
                  border-radius: 0.2em; }

                .waves-button-input {
                  margin: 0;
                  padding: 0.85em 1.1em; }

                .waves-input-wrapper {
                  border-radius: 0.2em;
                  vertical-align: bottom; }

                .waves-input-wrapper.waves-button {
                  padding: 0; }

                .waves-input-wrapper .waves-button-input {
                  position: relative;
                  top: 0;
                  left: 0;
                  z-index: 1; }

                .waves-circle {
                  text-align: center;
                  width: 2.5em;
                  height: 2.5em;
                  line-height: 2.5em;
                  border-radius: 50%; }

                .waves-float {
                  -webkit-mask-image: none;
                  -webkit-box-shadow: 0px 1px 1.5px 1px rgba(0, 0, 0, 0.12);
                  box-shadow: 0px 1px 1.5px 1px rgba(0, 0, 0, 0.12);
                  -webkit-transition: all 300ms;
                  transition: all 300ms; }

                .waves-float:active {
                  -webkit-box-shadow: 0px 8px 20px 1px rgba(0, 0, 0, 0.3);
                  box-shadow: 0px 8px 20px 1px rgba(0, 0, 0, 0.3); }

                .waves-block {
                  display: block; }

                .waves-effect.waves-light .waves-ripple {
                  background-color: rgba(255, 255, 255, 0.4); }

                .waves-effect.waves-primary .waves-ripple {
                  background-color: rgba(61, 142, 248, 0.4); }

                .waves-effect.waves-success .waves-ripple {
                  background-color: rgba(17, 196, 110, 0.4); }

                .waves-effect.waves-info .waves-ripple {
                  background-color: rgba(13, 180, 214, 0.4); }

                .waves-effect.waves-warning .waves-ripple {
                  background-color: rgba(241, 180, 76, 0.4); }

                .waves-effect.waves-danger .waves-ripple {
                  background-color: rgba(251, 77, 83, 0.4); }

                .avatar-xs {
                  height: 2rem;
                  width: 2rem; }

                .avatar-sm {
                  height: 3rem;
                  width: 3rem; }

                .avatar-md {
                  height: 4.5rem;
                  width: 4.5rem; }

                .avatar-lg {
                  height: 6rem;
                  width: 6rem; }

                .avatar-xl {
                  height: 7.5rem;
                  width: 7.5rem; }

                .avatar-title {
                  -webkit-box-align: center;
                      -ms-flex-align: center;
                          align-items: center;
                  background-color: #3d8ef8;
                  color: #fff;
                  display: -webkit-box;
                  display: -ms-flexbox;
                  display: flex;
                  font-weight: 500;
                  height: 100%;
                  -webkit-box-pack: center;
                      -ms-flex-pack: center;
                          justify-content: center;
                  width: 100%; }

                .font-size-11 {
                  font-size: 11px !important; }

                .font-size-12 {
                  font-size: 12px !important; }

                .font-size-13 {
                  font-size: 13px !important; }

                .font-size-14 {
                  font-size: 14px !important; }

                .font-size-15 {
                  font-size: 15px !important; }

                .font-size-16 {
                  font-size: 16px !important; }

                .font-size-17 {
                  font-size: 17px !important; }

                .font-size-18 {
                  font-size: 18px !important; }

                .font-size-20 {
                  font-size: 20px !important; }

                .font-size-22 {
                  font-size: 22px !important; }

                .font-size-24 {
                  font-size: 24px !important; }

                .font-weight-medium {
                  font-weight: 500; }

                .social-list-item {
                  height: 2rem;
                  width: 2rem;
                  line-height: calc(2rem - 2px);
                  display: block;
                  border: 1px solid #adb5bd;
                  border-radius: 50%;
                  color: #adb5bd;
                  text-align: center; }

                .w-xs {
                  min-width: 80px; }

                .w-sm {
                  min-width: 95px; }

                .w-md {
                  min-width: 110px; }

                .w-lg {
                  min-width: 140px; }

                .w-xl {
                  min-width: 160px; }

                .item-hovered:hover {
                  background-color: #f8f9fa; }

                .search-bar .form-control {
                  border: none;
                  height: calc(1.5em + 0.94rem + 2px);
                  padding-left: 40px;
                  padding-right: 20px;
                  background-color: #f4f8f9;
                  -webkit-box-shadow: none;
                          box-shadow: none;
                  border-radius: 30px; }

                .search-bar span {
                  position: absolute;
                  z-index: 10;
                  font-size: 16px;
                  line-height: calc(1.5em + 0.94rem + 2px);
                  left: 13px;
                  top: 0;
                  color: #7c8a96; }

                .button-items {
                  margin-left: -8px;
                  margin-bottom: -12px; }
                  .button-items .btn {
                    margin-bottom: 12px;
                    margin-left: 8px; }

                .mfp-popup-form {
                  max-width: 1140px; }

                .bs-example-modal {
                  position: relative;
                  top: auto;
                  right: auto;
                  bottom: auto;
                  left: auto;
                  z-index: 1;
                  display: block; }

                .icon-demo-content {
                  text-align: center;
                  color: #adb5bd; }
                  .icon-demo-content i {
                    display: block;
                    font-size: 24px;
                    color: #7c8a96;
                    width: 48px;
                    height: 48px;
                    line-height: 46px;
                    margin: 0px auto;
                    margin-bottom: 16px;
                    border-radius: 4px;
                    border: 1px solid #eff2f7;
                    -webkit-transition: all 0.4s;
                    transition: all 0.4s; }
                  .icon-demo-content .col-lg-4 {
                    margin-top: 24px; }
                    .icon-demo-content .col-lg-4:hover i {
                      background-color: #3d8ef8;
                      color: #fff; }

                .grid-structure .grid-container {
                  background-color: #f8f9fa;
                  margin-top: 10px;
                  font-size: .8rem;
                  font-weight: 500;
                  padding: 10px 20px; }

                [data-simplebar] {
                  position: relative;
                  -webkit-box-orient: vertical;
                  -webkit-box-direction: normal;
                      -ms-flex-direction: column;
                          flex-direction: column;
                  -ms-flex-wrap: wrap;
                      flex-wrap: wrap;
                  -webkit-box-pack: start;
                      -ms-flex-pack: start;
                          justify-content: flex-start;
                  -ms-flex-line-pack: start;
                      align-content: flex-start;
                  -webkit-box-align: start;
                      -ms-flex-align: start;
                          align-items: flex-start; }

                .simplebar-wrapper {
                  overflow: hidden;
                  width: inherit;
                  height: inherit;
                  max-width: inherit;
                  max-height: inherit; }

                .simplebar-mask {
                  direction: inherit;
                  position: absolute;
                  overflow: hidden;
                  padding: 0;
                  margin: 0;
                  left: 0;
                  top: 0;
                  bottom: 0;
                  right: 0;
                  width: auto !important;
                  height: auto !important;
                  z-index: 0; }

                .simplebar-offset {
                  direction: inherit !important;
                  -webkit-box-sizing: inherit !important;
                          box-sizing: inherit !important;
                  resize: none !important;
                  position: absolute;
                  top: 0;
                  left: 0;
                  bottom: 0;
                  right: 0;
                  padding: 0;
                  margin: 0;
                  -webkit-overflow-scrolling: touch; }

                .simplebar-content-wrapper {
                  direction: inherit;
                  -webkit-box-sizing: border-box !important;
                          box-sizing: border-box !important;
                  position: relative;
                  display: block;
                  height: 100%;
                  /* Required for horizontal native scrollbar to not appear if parent is taller than natural height */
                  width: auto;
                  visibility: visible;
                  overflow: auto;
                  /* Scroll on this element otherwise element can't have a padding applied properly */
                  max-width: 100%;
                  /* Not required for horizontal scroll to trigger */
                  max-height: 100%;
                  /* Needed for vertical scroll to trigger */
                  scrollbar-width: none; }

                .simplebar-content-wrapper::-webkit-scrollbar,
                .simplebar-hide-scrollbar::-webkit-scrollbar {
                  display: none; }

                .simplebar-content:before,
                .simplebar-content:after {
                  content: ' ';
                  display: table; }

                .simplebar-placeholder {
                  max-height: 100%;
                  max-width: 100%;
                  width: 100%;
                  pointer-events: none; }

                .simplebar-height-auto-observer-wrapper {
                  -webkit-box-sizing: inherit !important;
                          box-sizing: inherit !important;
                  height: 100%;
                  width: 100%;
                  max-width: 1px;
                  position: relative;
                  float: left;
                  max-height: 1px;
                  overflow: hidden;
                  z-index: -1;
                  padding: 0;
                  margin: 0;
                  pointer-events: none;
                  -webkit-box-flex: inherit;
                      -ms-flex-positive: inherit;
                          flex-grow: inherit;
                  -ms-flex-negative: 0;
                      flex-shrink: 0;
                  -ms-flex-preferred-size: 0;
                      flex-basis: 0; }

                .simplebar-height-auto-observer {
                  -webkit-box-sizing: inherit;
                          box-sizing: inherit;
                  display: block;
                  opacity: 0;
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 1000%;
                  width: 1000%;
                  min-height: 1px;
                  min-width: 1px;
                  overflow: hidden;
                  pointer-events: none;
                  z-index: -1; }

                .simplebar-track {
                  z-index: 1;
                  position: absolute;
                  right: 0;
                  bottom: 0;
                  pointer-events: none;
                  overflow: hidden; }

                [data-simplebar].simplebar-dragging .simplebar-content {
                  pointer-events: none;
                  -moz-user-select: none;
                   -ms-user-select: none;
                       user-select: none;
                  -webkit-user-select: none; }

                [data-simplebar].simplebar-dragging .simplebar-track {
                  pointer-events: all; }

                .simplebar-scrollbar {
                  position: absolute;
                  right: 2px;
                  width: 4px;
                  min-height: 10px; }

                .simplebar-scrollbar:before {
                  position: absolute;
                  content: '';
                  background: #a2adb7;
                  border-radius: 7px;
                  left: 0;
                  right: 0;
                  opacity: 0;
                  -webkit-transition: opacity 0.2s linear;
                  transition: opacity 0.2s linear; }

                .simplebar-scrollbar.simplebar-visible:before {
                  /* When hovered, remove all transitions from drag handle */
                  opacity: 0.5;
                  -webkit-transition: opacity 0s linear;
                  transition: opacity 0s linear; }

                .simplebar-track.simplebar-vertical {
                  top: 0;
                  width: 11px; }

                .simplebar-track.simplebar-vertical .simplebar-scrollbar:before {
                  top: 2px;
                  bottom: 2px; }

                .simplebar-track.simplebar-horizontal {
                  left: 0;
                  height: 11px; }

                .simplebar-track.simplebar-horizontal .simplebar-scrollbar:before {
                  height: 100%;
                  left: 2px;
                  right: 2px; }

                .simplebar-track.simplebar-horizontal .simplebar-scrollbar {
                  right: auto;
                  left: 0;
                  top: 2px;
                  height: 7px;
                  min-height: 0;
                  min-width: 10px;
                  width: auto; }

                /* Rtl support */
                [data-simplebar-direction='rtl'] .simplebar-track.simplebar-vertical {
                  right: auto;
                  left: 0; }

                .hs-dummy-scrollbar-size {
                  direction: rtl;
                  position: fixed;
                  opacity: 0;
                  visibility: hidden;
                  height: 500px;
                  width: 500px;
                  overflow-y: hidden;
                  overflow-x: scroll; }

                .simplebar-hide-scrollbar {
                  position: fixed;
                  left: 0;
                  visibility: hidden;
                  overflow-y: scroll;
                  scrollbar-width: none; }

                .custom-scroll {
                  height: 100%; }

                .fc-toolbar h2 {
                  font-size: 16px;
                  line-height: 30px;
                  text-transform: uppercase; }

                .fc th.fc-widget-header {
                  background: #f4f8f9;
                  font-size: 13px;
                  line-height: 20px;
                  padding: 10px 0;
                  text-transform: uppercase;
                  font-weight: 600; }

                .fc-unthemed .fc-content,
                .fc-unthemed .fc-divider,
                .fc-unthemed .fc-list-heading td,
                .fc-unthemed .fc-list-view,
                .fc-unthemed .fc-popover,
                .fc-unthemed .fc-row,
                .fc-unthemed tbody,
                .fc-unthemed td,
                .fc-unthemed th,
                .fc-unthemed thead {
                  border-color: #f4f8f9; }

                .fc-unthemed td.fc-today {
                  background: #f4f8f9; }

                .fc-button {
                  background: #fff;
                  border-color: #eff2f7;
                  color: #495057;
                  text-transform: capitalize;
                  -webkit-box-shadow: none;
                          box-shadow: none;
                  padding: 6px 12px !important;
                  height: auto !important; }

                .fc-state-down,
                .fc-state-active,
                .fc-state-disabled {
                  background-color: #3d8ef8;
                  color: #fff;
                  text-shadow: none; }

                .fc-event {
                  border-radius: 2px;
                  border: none;
                  cursor: move;
                  font-size: 0.8125rem;
                  margin: 5px 7px;
                  padding: 5px 5px;
                  text-align: center; }

                .fc-event, .fc-event-dot {
                  background-color: #3d8ef8; }

                .fc-event .fc-content {
                  color: #fff; }

                .alertify .ajs-dialog {
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); }

                .alertify .ajs-footer .ajs-buttons .ajs-button.ajs-ok {
                  color: #3d8ef8; }

                .alertify-notifier .ajs-message {
                  background-color: #3d8ef8;
                  border-color: #3d8ef8;
                  color: #fff;
                  text-shadow: none !important; }
                  .alertify-notifier .ajs-message.ajs-success {
                    background-color: #11c46e;
                    border-color: #11c46e; }
                  .alertify-notifier .ajs-message.ajs-error {
                    background-color: #fb4d53;
                    border-color: #fb4d53; }
                  .alertify-notifier .ajs-message.ajs-warning {
                    background-color: #f1b44c;
                    border-color: #f1b44c; }

                .rating-symbol-background, .rating-symbol-foreground {
                  font-size: 24px; }

                .rating-symbol-foreground {
                  top: 0px; }

                .dd-list .dd-item .dd-handle {
                  background: #f4f8f9;
                  border: none;
                  padding: 8px 16px;
                  color: #7c8a96;
                  height: auto;
                  font-weight: 500;
                  border-radius: 3px; }
                  .dd-list .dd-item .dd-handle:hover {
                    color: #3d8ef8; }

                .dd-list .dd-item button {
                  height: 36px;
                  font-size: 17px;
                  margin: 0;
                  color: #7c8a96;
                  width: 36px; }

                .dd-list .dd3-item {
                  margin: 5px 0; }
                  .dd-list .dd3-item .dd-item button {
                    width: 36px;
                    height: 36px; }

                .dd-list .dd3-handle {
                  margin: 0;
                  height: 36px !important;
                  float: left; }

                .dd-list .dd3-content {
                  height: auto;
                  border: none;
                  padding: 8px 16px 8px 46px;
                  background: #f8f9fa;
                  font-weight: 600; }
                  .dd-list .dd3-content:hover {
                    color: #3d8ef8; }

                .dd-list .dd3-handle:before {
                  content: "\F35C";
                  font-family: "Material Design Icons";
                  color: #adb5bd; }

                .dd-empty,
                .dd-placeholder {
                  background: rgba(244, 248, 249, 0.2);
                  border-color: #f4f8f9; }

                .dd-dragel .dd-handle {
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); }

                .irs--round .irs-bar, .irs--round .irs-to, .irs--round .irs-from, .irs--round .irs-single {
                  background: #3d8ef8 !important;
                  font-size: 11px; }

                .irs--round .irs-to, .irs--round .irs-from, .irs--round .irs-single {
                  top: 5px; }
                  .irs--round .irs-to:before, .irs--round .irs-from:before, .irs--round .irs-single:before {
                    display: none; }

                .irs--round .irs-line {
                  background: #f4f8f9;
                  border-color: #f4f8f9; }

                .irs--round .irs-grid-text {
                  font-size: 11px;
                  color: #ced4da; }

                .irs--round .irs-min, .irs--round .irs-max {
                  color: #adb5bd;
                  background: #f4f8f9;
                  font-size: 11px; }

                .irs--round .irs-handle {
                  border: 2px solid #3d8ef8;
                  width: 12px;
                  height: 12px;
                  top: 31px;
                  background-color: #fff !important; }

                .swal2-container .swal2-title {
                  font-size: 22px;
                  font-weight: 500; }

                .swal2-icon.swal2-question {
                  border-color: #0db4d6;
                  color: #0db4d6; }

                .swal2-icon.swal2-success [class^=swal2-success-line] {
                  background-color: #11c46e; }

                .swal2-icon.swal2-success .swal2-success-ring {
                  border-color: rgba(17, 196, 110, 0.3); }

                .swal2-icon.swal2-warning {
                  border-color: #f1b44c;
                  color: #f1b44c; }

                .swal2-content {
                  font-size: 16px; }

                .swal2-styled:focus {
                  -webkit-box-shadow: none;
                          box-shadow: none; }

                .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step {
                  background: #3d8ef8; }
                  .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step, .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step-line {
                    background: rgba(61, 142, 248, 0.3); }

                .gmaps, .gmaps-panaroma {
                  height: 300px;
                  background: #f8f9fa;
                  border-radius: 3px; }

                .gmaps-overlay {
                  display: block;
                  text-align: center;
                  color: #fff;
                  font-size: 16px;
                  line-height: 40px;
                  background: #3d8ef8;
                  border-radius: 4px;
                  padding: 10px 20px; }

                .gmaps-overlay_arrow {
                  left: 50%;
                  margin-left: -16px;
                  width: 0;
                  height: 0;
                  position: absolute; }
                  .gmaps-overlay_arrow.above {
                    bottom: -15px;
                    border-left: 16px solid transparent;
                    border-right: 16px solid transparent;
                    border-top: 16px solid #3d8ef8; }
                  .gmaps-overlay_arrow.below {
                    top: -15px;
                    border-left: 16px solid transparent;
                    border-right: 16px solid transparent;
                    border-bottom: 16px solid #3d8ef8; }

                .jqvmap-label {
                  background-color: #343a40;
                  color: #eff2f7;
                  font-size: 0.85rem;
                  padding: 0.3rem 0.6rem; }

                .jqvmap-zoomin, .jqvmap-zoomout {
                  width: 24px;
                  height: 24px;
                  line-height: 18px; }

                .jqvmap-zoomout {
                  top: 40px; }

                .error {
                  color: #fb4d53; }

                .parsley-error {
                  border-color: #fb4d53; }

                .parsley-errors-list {
                  display: none;
                  margin: 0;
                  padding: 0; }
                  .parsley-errors-list.filled {
                    display: block; }
                  .parsley-errors-list > li {
                    font-size: 12px;
                    list-style: none;
                    color: #fb4d53;
                    margin-top: 5px; }

                .select2-container .select2-selection--single {
                  background-color: #fff;
                  border: 1px solid #ced4da;
                  height: 38px; }
                  .select2-container .select2-selection--single:focus {
                    outline: none; }
                  .select2-container .select2-selection--single .select2-selection__rendered {
                    line-height: 36px;
                    padding-left: 12px; }
                  .select2-container .select2-selection--single .select2-selection__arrow {
                    height: 34px;
                    width: 34px;
                    right: 3px; }
                    .select2-container .select2-selection--single .select2-selection__arrow b {
                      border-color: #adb5bd transparent transparent transparent;
                      border-width: 6px 6px 0 6px; }

                .select2-container--open .select2-selection--single .select2-selection__arrow b {
                  border-color: transparent transparent #adb5bd transparent !important;
                  border-width: 0 6px 6px 6px !important; }

                .select2-container--default .select2-search--dropdown {
                  padding: 10px;
                  background-color: #fff; }
                  .select2-container--default .select2-search--dropdown .select2-search__field {
                    border: 1px solid #ced4da;
                    background-color: #fff;
                    color: #7c8a96;
                    outline: none; }

                .select2-container--default .select2-results__option--highlighted[aria-selected] {
                  background-color: #3d8ef8; }

                .select2-container--default .select2-results__option[aria-selected=true] {
                  background-color: #f8f9fa;
                  color: #16181b; }
                  .select2-container--default .select2-results__option[aria-selected=true]:hover {
                    background-color: #3d8ef8;
                    color: #fff; }

                .select2-results__option {
                  padding: 6px 12px; }

                .select2-dropdown {
                  border: #e2e6e9;
                  background-color: #fff;
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); }

                .select2-search input {
                  border: 1px solid #f4f8f9; }

                .select2-container .select2-selection--multiple {
                  min-height: 38px;
                  background-color: #fff;
                  border: 1px solid #ced4da !important; }
                  .select2-container .select2-selection--multiple .select2-selection__rendered {
                    padding: 2px 10px; }
                  .select2-container .select2-selection--multiple .select2-search__field {
                    margin-top: 7px;
                    border: 0; }
                  .select2-container .select2-selection--multiple .select2-selection__choice {
                    background-color: #eff2f7;
                    border: 1px solid #f4f8f9;
                    border-radius: 1px;
                    padding: 0 7px; }

                .select2-container--default.select2-container--focus .select2-selection--multiple {
                  border-color: #ced4da; }

                .datepicker {
                  border: 1px solid #fdfdfe;
                  padding: 8px;
                  z-index: 999 !important; }
                  .datepicker table tr th {
                    font-weight: 500; }
                  .datepicker table tr td.active, .datepicker table tr td.active:hover, .datepicker table tr td .active.disabled, .datepicker table tr td.active.disabled:hover, .datepicker table tr td.today, .datepicker table tr td.today:hover, .datepicker table tr td.today.disabled, .datepicker table tr td.today.disabled:hover, .datepicker table tr td.selected, .datepicker table tr td.selected:hover, .datepicker table tr td.selected.disabled, .datepicker table tr td.selected.disabled:hover {
                    background-color: #3d8ef8 !important;
                    background-image: none;
                    -webkit-box-shadow: none;
                            box-shadow: none;
                    color: #fff !important; }
                  .datepicker table tr td.day.focused, .datepicker table tr td.day:hover,
                  .datepicker table tr td span.focused,
                  .datepicker table tr td span:hover {
                    background: #eff2f7; }
                  .datepicker table tr td.new, .datepicker table tr td.old,
                  .datepicker table tr td span.new,
                  .datepicker table tr td span.old {
                    color: #adb5bd;
                    opacity: 0.6; }
                  .datepicker table tr td.range, .datepicker table tr td.range.disabled, .datepicker table tr td.range.disabled:hover, .datepicker table tr td.range:hover {
                    background-color: #f4f8f9; }

                .table-condensed > thead > tr > th, .table-condensed > tbody > tr > td {
                  padding: 7px; }

                .tox-tinymce {
                  border: 1px solid #ced4da !important; }

                .tox .tox-menubar, .tox .tox-edit-area__iframe, .tox .tox-statusbar {
                  background-color: #fff !important; }

                .tox .tox-menubar {
                  background: none !important;
                  border-bottom: 1px solid #ced4da; }

                .tox .tox-mbtn {
                  color: #495057 !important; }
                  .tox .tox-mbtn:hover:not(:disabled):not(.tox-mbtn--active) {
                    background-color: #f4f8f9 !important; }

                .tox .tox-tbtn:hover {
                  background-color: #f4f8f9 !important; }

                .tox .tox-toolbar, .tox .tox-toolbar__overflow, .tox .tox-toolbar__primary {
                  background-color: #fff !important;
                  background: none !important;
                  border-bottom: 1px solid #ced4da; }

                .tox .tox-tbtn {
                  color: #495057 !important; }
                  .tox .tox-tbtn svg {
                    fill: #495057 !important; }

                .tox .tox-edit-area__iframe,
                .tox .tox-toolbar-overlord {
                  background-color: #fff !important; }

                .tox .tox-statusbar a, .tox .tox-statusbar__path-item, .tox .tox-statusbar__wordcount {
                  color: #495057 !important; }

                .tox .tox-split-button:hover {
                  -webkit-box-shadow: none !important;
                          box-shadow: none !important; }

                .tox .tox-tbtn--enabled,
                .tox .tox-tbtn--enabled:hover {
                  background-color: #f4f8f9 !important; }

                .tox .tox-statusbar {
                  border-top: 1px solid #ced4da !important; }

                .tox:not([dir=rtl]) .tox-toolbar__group:not(:last-of-type) {
                  border-right: 1px solid #ced4da !important; }

                .note-editor.note-frame {
                  border: 1px solid #ced4da;
                  -webkit-box-shadow: none;
                          box-shadow: none;
                  margin: 0; }
                  .note-editor.note-frame .note-statusbar {
                    background-color: #f4f8f9;
                    border-top: 1px solid #eff2f7; }
                  .note-editor.note-frame .note-editing-area .note-editable, .note-editor.note-frame .note-editing-area .note-codable {
                    border: none;
                    color: #adb5bd;
                    background-color: transparent; }

                .note-btn-group .note-btn {
                  background-color: #f4f8f9 !important;
                  border-color: #f4f8f9 !important; }

                .note-status-output {
                  display: none; }

                .note-editable p:last-of-type {
                  margin-bottom: 0; }

                .note-popover .popover-content .note-color .dropdown-menu,
                .card-header.note-toolbar .note-color .dropdown-menu {
                  min-width: 344px; }

                .note-popover {
                  border-color: #f4f8f9; }

                .note-popover .popover-content,
                .card-header.note-toolbar {
                  background-color: #f4f8f9; }

                .note-color-all .note-btn.dropdown-toggle {
                  width: 30px !important; }
                  .note-color-all .note-btn.dropdown-toggle:before {
                    content: "\F35D";
                    font: normal normal normal 24px/1 "Material Design Icons";
                    position: absolute;
                    left: 2px;
                    top: 3px; }

                .note-color-all .dropdown-menu {
                  padding: 0; }

                .note-editable {
                  border: 1px solid #ced4da;
                  border-radius: 0.25rem;
                  padding: 0.47rem 0.75rem; }
                  .note-editable p:last-of-type {
                    margin-bottom: 0; }

                /* Dropzone */
                .dropzone {
                  min-height: 230px;
                  border: 2px dashed #ced4da;
                  background: #fff;
                  border-radius: 6px; }
                  .dropzone .dz-message {
                    font-size: 24px; }

                .table-rep-plugin .btn-toolbar {
                  display: block; }

                .table-rep-plugin .table-responsive {
                  border: none !important; }

                .table-rep-plugin .btn-group .btn-default {
                  background-color: #7c8a96;
                  color: #fff;
                  border: 1px solid #7c8a96; }
                  .table-rep-plugin .btn-group .btn-default.btn-primary {
                    background-color: #3d8ef8;
                    border-color: #3d8ef8;
                    color: #fff;
                    -webkit-box-shadow: 0 0 0 2px rgba(61, 142, 248, 0.5);
                            box-shadow: 0 0 0 2px rgba(61, 142, 248, 0.5); }

                .table-rep-plugin .btn-group.pull-right {
                  float: right; }
                  .table-rep-plugin .btn-group.pull-right .dropdown-menu {
                    right: 0;
                    -webkit-transform: none !important;
                            transform: none !important;
                    top: 100% !important; }

                .table-rep-plugin tbody th {
                  font-size: 14px;
                  font-weight: normal; }

                .table-rep-plugin table.focus-on tbody tr.focused th, .table-rep-plugin table.focus-on tbody tr.focused td, .table-rep-plugin table.focus-on tfoot tr.focused th, .table-rep-plugin table.focus-on tfoot tr.focused td {
                  background-color: #3d8ef8;
                  color: #fff; }

                .table-rep-plugin .checkbox-row {
                  padding-left: 40px;
                  color: #7c8a96 !important; }
                  .table-rep-plugin .checkbox-row:hover, .table-rep-plugin .checkbox-row:active {
                    background-color: #f8f9fa !important; }
                  .table-rep-plugin .checkbox-row label {
                    display: inline-block;
                    padding-left: 5px;
                    position: relative; }
                    .table-rep-plugin .checkbox-row label::before {
                      -o-transition: 0.3s ease-in-out;
                      -webkit-transition: 0.3s ease-in-out;
                      background-color: #eff2f7;
                      border-radius: 3px;
                      border: 1px solid #f4f8f9;
                      content: "";
                      display: inline-block;
                      height: 17px;
                      left: 0;
                      margin-left: -20px;
                      position: absolute;
                      transition: 0.3s ease-in-out;
                      width: 17px;
                      outline: none !important; }
                    .table-rep-plugin .checkbox-row label::after {
                      color: #eff2f7;
                      display: inline-block;
                      font-size: 11px;
                      height: 16px;
                      left: 0;
                      margin-left: -20px;
                      padding-left: 3px;
                      padding-top: 1px;
                      position: absolute;
                      top: -1px;
                      width: 16px; }
                  .table-rep-plugin .checkbox-row input[type="checkbox"] {
                    cursor: pointer;
                    opacity: 0;
                    z-index: 1;
                    outline: none !important; }
                    .table-rep-plugin .checkbox-row input[type="checkbox"]:disabled + label {
                      opacity: 0.65; }
                  .table-rep-plugin .checkbox-row input[type="checkbox"]:focus + label::before {
                    outline-offset: -2px;
                    outline: none; }
                  .table-rep-plugin .checkbox-row input[type="checkbox"]:checked + label::after {
                    content: "\f00c";
                    font-family: 'Font Awesome 5 Free';
                    font-weight: 900; }
                  .table-rep-plugin .checkbox-row input[type="checkbox"]:disabled + label::before {
                    background-color: #f8f9fa;
                    cursor: not-allowed; }
                  .table-rep-plugin .checkbox-row input[type="checkbox"]:checked + label::before {
                    background-color: #3d8ef8;
                    border-color: #3d8ef8; }
                  .table-rep-plugin .checkbox-row input[type="checkbox"]:checked + label::after {
                    color: #fff; }

                .table-rep-plugin .fixed-solution .sticky-table-header {
                  top: 70px !important;
                  background-color: #3d8ef8;
                  border-color: #3d8ef8; }
                  .table-rep-plugin .fixed-solution .sticky-table-header table {
                    color: #fff; }

                .table-editable .editable-input .form-control {
                  height: 2rem; }

                .table-editable a.editable {
                  color: #505d69; }

                .table-editable .editable-buttons .btn.btn-sm {
                  font-size: 12px; }

                .table-editable tbody td.focus {
                  -webkit-box-shadow: inset 0 0 1px 1px #3d8ef8 !important;
                          box-shadow: inset 0 0 1px 1px #3d8ef8 !important; }

                .dt-autofill-list {
                  border: none !important;
                  background-color: #fff !important; }
                  .dt-autofill-list .dt-autofill-question, .dt-autofill-list .dt-autofill-button {
                    border-bottom-color: #f4f8f9 !important; }
                  .dt-autofill-list ul li:hover {
                    background-color: #f4f8f9 !important; }

                .apex-charts {
                  min-height: 10px !important; }
                  .apex-charts text {
                    font-family: "Rubik", sans-serif !important;
                    fill: #adb5bd; }
                  .apex-charts .apexcharts-canvas {
                    margin: 0 auto; }

                .apexcharts-tooltip-title,
                .apexcharts-tooltip-text {
                  font-family: "Rubik", sans-serif !important; }

                .apexcharts-legend-series {
                  font-weight: 500; }

                .apexcharts-gridline {
                  pointer-events: none;
                  stroke: #f6f6f6; }

                .apexcharts-legend-text {
                  color: #adb5bd !important;
                  font-family: "Rubik", sans-serif !important;
                  font-size: 13px !important; }

                .apexcharts-pie-label {
                  fill: #fff !important; }

                .apexcharts-yaxis text,
                .apexcharts-xaxis text {
                  font-family: "Rubik", sans-serif !important;
                  fill: #adb5bd; }

                .apexcharts-radar-series polygon {
                  fill: transparent;
                  stroke: #edf4f5; }

                .apexcharts-radar-series line {
                  stroke: #edf4f5; }

                .flotTip {
                  padding: 8px 12px !important;
                  background-color: #343a40 !important;
                  border: 1px solid #343a40 !important;
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                  z-index: 100;
                  color: #eff2f7;
                  opacity: 1;
                  border-radius: 3px !important;
                  font-size: 14px !important; }

                .legend div {
                  background-color: transparent !important; }

                .legend tr {
                  height: 30px; }

                .legendLabel {
                  padding-left: 5px;
                  line-height: 10px;
                  padding-right: 10px;
                  font-size: 13px;
                  font-weight: 500;
                  color: #adb5bd; }

                .legendColorBox div {
                  border-radius: 3px; }
                  .legendColorBox div div {
                    border-radius: 3px; }

                .float-lable-box table {
                  margin: 0 auto; }

                @media (max-width: 767.98px) {
                  .legendLabel {
                    display: none; } }

                .jqstooltip {
                  -webkit-box-sizing: content-box;
                          box-sizing: content-box;
                  width: auto !important;
                  height: auto !important;
                  background-color: #343a40 !important;
                  -webkit-box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
                          box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
                  padding: 5px 10px !important;
                  border-radius: 3px;
                  border-color: #212529 !important; }

                .jqsfield {
                  color: #eff2f7 !important;
                  font-size: 12px !important;
                  line-height: 18px !important;
                  font-family: "Rubik", sans-serif !important;
                  font-weight: 500 !important; }

                .morris-chart text {
                  font-family: "Rubik", sans-serif !important;
                  fill: #adb5bd; }

                .morris-hover {
                  position: absolute;
                  z-index: 10; }
                  .morris-hover.morris-default-style {
                    font-size: 12px;
                    text-align: center;
                    border-radius: 5px;
                    padding: 10px 12px;
                    background: rgba(248, 249, 250, 0.8);
                    color: #343a40;
                    border: 2px solid #eff2f7;
                    font-family: "Rubik", sans-serif; }
                    .morris-hover.morris-default-style .morris-hover-row-label {
                      font-weight: bold;
                      margin: 0.25em 0;
                      font-family: "Rubik", sans-serif; }
                    .morris-hover.morris-default-style .morris-hover-point {
                      white-space: nowrap;
                      margin: 0.1em 0;
                      color: #fff; }

                .ct-golden-section:before {
                  float: none; }

                .ct-chart {
                  max-height: 300px; }
                  .ct-chart .ct-label {
                    fill: #adb5bd;
                    color: #adb5bd;
                    font-size: 12px;
                    line-height: 1; }

                .ct-chart.simple-pie-chart-chartist .ct-label {
                  color: #fff;
                  fill: #fff;
                  font-size: 16px; }

                .ct-grid {
                  stroke: rgba(52, 58, 64, 0.1); }

                .ct-chart .ct-series.ct-series-a .ct-bar,
                .ct-chart .ct-series.ct-series-a .ct-line,
                .ct-chart .ct-series.ct-series-a .ct-point,
                .ct-chart .ct-series.ct-series-a .ct-slice-donut {
                  stroke: #3d8ef8; }

                .ct-chart .ct-series.ct-series-b .ct-bar,
                .ct-chart .ct-series.ct-series-b .ct-line,
                .ct-chart .ct-series.ct-series-b .ct-point,
                .ct-chart .ct-series.ct-series-b .ct-slice-donut {
                  stroke: #f1b44c; }

                .ct-chart .ct-series.ct-series-c .ct-bar,
                .ct-chart .ct-series.ct-series-c .ct-line,
                .ct-chart .ct-series.ct-series-c .ct-point,
                .ct-chart .ct-series.ct-series-c .ct-slice-donut {
                  stroke: #fb4d53; }

                .ct-chart .ct-series.ct-series-d .ct-bar,
                .ct-chart .ct-series.ct-series-d .ct-line,
                .ct-chart .ct-series.ct-series-d .ct-point,
                .ct-chart .ct-series.ct-series-d .ct-slice-donut {
                  stroke: #0db4d6; }

                .ct-chart .ct-series.ct-series-e .ct-bar,
                .ct-chart .ct-series.ct-series-e .ct-line,
                .ct-chart .ct-series.ct-series-e .ct-point,
                .ct-chart .ct-series.ct-series-e .ct-slice-donut {
                  stroke: #11c46e; }

                .ct-chart .ct-series.ct-series-f .ct-bar,
                .ct-chart .ct-series.ct-series-f .ct-line,
                .ct-chart .ct-series.ct-series-f .ct-point,
                .ct-chart .ct-series.ct-series-f .ct-slice-donut {
                  stroke: #343a40; }

                .ct-chart .ct-series.ct-series-g .ct-bar,
                .ct-chart .ct-series.ct-series-g .ct-line,
                .ct-chart .ct-series.ct-series-g .ct-point,
                .ct-chart .ct-series.ct-series-g .ct-slice-donut {
                  stroke: #6f42c1; }

                .ct-series-a .ct-area,
                .ct-series-a .ct-slice-pie {
                  fill: #3d8ef8; }

                .ct-series-b .ct-area,
                .ct-series-b .ct-slice-pie {
                  fill: #11c46e; }

                .ct-series-c .ct-area,
                .ct-series-c .ct-slice-pie {
                  fill: #f1b44c; }

                .ct-series-d .ct-area,
                .ct-series-d .ct-slice-pie {
                  fill: #11c46e; }

                .ct-area {
                  fill-opacity: .33; }

                .chartist-tooltip {
                  position: absolute;
                  display: inline-block;
                  opacity: 0;
                  min-width: 10px;
                  padding: 2px 10px;
                  border-radius: 3px;
                  background: #343a40;
                  color: #f4f8f9;
                  text-align: center;
                  pointer-events: none;
                  z-index: 1;
                  -webkit-transition: opacity .2s linear;
                  transition: opacity .2s linear; }
                  .chartist-tooltip.tooltip-show {
                    opacity: 1; }

                .ct-line {
                  stroke-width: 3px; }

                .ct-point {
                  stroke-width: 7px; }

                .bg-pattern {
                  background-image: url("../images/bg-pattern.png");
                  background-size: cover;
                  background-position: center; }

                .home-btn {
                  position: absolute;
                  top: 15px;
                  right: 25px; }

                /* ==============
                  Email
                ===================*/
                .mail-list a {
                  display: block;
                  color: #7c8a96;
                  line-height: 24px;
                  padding: 6px 5px; }
                  .mail-list a.active {
                    color: #fb4d53;
                    font-weight: 500; }
                  .mail-list a i {
                    font-size: 18px; }

                .chat-user-box p.user-title {
                  color: #343a40;
                  font-weight: 500; }

                .chat-user-box p {
                  font-size: 13px; }

                .message-list {
                  display: block;
                  padding-left: 0; }
                  .message-list li {
                    position: relative;
                    display: block;
                    height: 50px;
                    line-height: 50px;
                    cursor: default;
                    -webkit-transition-duration: .3s;
                            transition-duration: .3s; }
                    .message-list li a {
                      color: #7c8a96; }
                    .message-list li:hover {
                      background: #f4f8f9;
                      -webkit-transition-duration: .05s;
                              transition-duration: .05s; }
                    .message-list li .col-mail {
                      float: left;
                      position: relative; }
                    .message-list li .col-mail-1 {
                      width: 320px; }
                      .message-list li .col-mail-1 .star-toggle,
                      .message-list li .col-mail-1 .checkbox-wrapper-mail,
                      .message-list li .col-mail-1 .dot {
                        display: block;
                        float: left; }
                      .message-list li .col-mail-1 .dot {
                        border: 4px solid transparent;
                        border-radius: 100px;
                        margin: 22px 26px 0;
                        height: 0;
                        width: 0;
                        line-height: 0;
                        font-size: 0; }
                      .message-list li .col-mail-1 .checkbox-wrapper-mail {
                        margin: 15px 10px 0 20px; }
                      .message-list li .col-mail-1 .star-toggle {
                        margin-top: 18px;
                        margin-left: 5px; }
                      .message-list li .col-mail-1 .title {
                        position: absolute;
                        top: 0;
                        left: 110px;
                        right: 0;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        white-space: nowrap;
                        margin-bottom: 0; }
                    .message-list li .col-mail-2 {
                      position: absolute;
                      top: 0;
                      left: 320px;
                      right: 0;
                      bottom: 0; }
                      .message-list li .col-mail-2 .subject,
                      .message-list li .col-mail-2 .date {
                        position: absolute;
                        top: 0; }
                      .message-list li .col-mail-2 .subject {
                        left: 0;
                        right: 200px;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        white-space: nowrap; }
                      .message-list li .col-mail-2 .date {
                        right: 0;
                        width: 170px;
                        padding-left: 80px; }
                    .message-list li.active, .message-list li.active:hover {
                      -webkit-box-shadow: inset 3px 0 0 #3d8ef8;
                              box-shadow: inset 3px 0 0 #3d8ef8; }
                    .message-list li.unread {
                      background-color: #f4f8f9;
                      font-weight: 500;
                      color: #292d32; }
                      .message-list li.unread a {
                        color: #292d32;
                        font-weight: 500; }
                  .message-list .checkbox-wrapper-mail {
                    cursor: pointer;
                    height: 20px;
                    width: 20px;
                    position: relative;
                    display: inline-block;
                    -webkit-box-shadow: inset 0 0 0 1px #ced4da;
                            box-shadow: inset 0 0 0 1px #ced4da;
                    border-radius: 1px; }
                    .message-list .checkbox-wrapper-mail input {
                      opacity: 0;
                      cursor: pointer; }
                    .message-list .checkbox-wrapper-mail input:checked ~ label {
                      opacity: 1; }
                    .message-list .checkbox-wrapper-mail label {
                      position: absolute;
                      height: 20px;
                      width: 20px;
                      left: 0;
                      cursor: pointer;
                      opacity: 0;
                      margin-bottom: 0;
                      -webkit-transition-duration: .05s;
                              transition-duration: .05s;
                      top: 0; }
                      .message-list .checkbox-wrapper-mail label:before {
                        content: "\F12C";
                        font-family: "Material Design Icons";
                        top: 0;
                        height: 20px;
                        color: #292d32;
                        width: 20px;
                        position: absolute;
                        margin-top: -16px;
                        left: 4px;
                        font-size: 13px; }

                @media (max-width: 575.98px) {
                  .message-list li .col-mail-1 {
                    width: 200px; } }

                .counter-number {
                  font-size: 32px;
                  font-weight: 500;
                  text-align: center; }
                  .counter-number span {
                    font-size: 16px;
                    font-weight: 400;
                    display: block;
                    padding-top: 7px; }

                .coming-box {
                  float: left;
                  width: 21%;
                  padding: 14px 7px;
                  margin: 0px 12px 24px 12px;
                  background-color: #fff;
                  border-radius: calc(0.25rem - 1px);
                  -webkit-box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); }

                @media (max-width: 991.98px) {
                  .coming-box {
                    width: 40%; } }

                /************** Horizontal timeline **************/
                .slick-slide {
                  margin: 10px 12px;
                  -webkit-transition: all ease-in-out .3s;
                  transition: all ease-in-out .3s;
                  opacity: .2;
                  padding: 7px;
                  border-radius: 4px; }
                  .slick-slide:focus {
                    outline: none; }

                .slick-prev:before, .slick-next:before {
                  color: #343a40; }

                .slick-active {
                  opacity: 1; }

                .slick-slider.hori-timeline-desc {
                  padding: 12px 24px 0;
                  text-align: center;
                  border-radius: 7px; }

                .slick-slider.hori-timeline-nav .slick-active {
                  opacity: 0.5; }

                .slick-slider.hori-timeline-nav .slick-current {
                  opacity: 1;
                  background-color: rgba(61, 142, 248, 0.2); }
                  .slick-slider.hori-timeline-nav .slick-current .nav-title {
                    color: #3d8ef8; }

                .slick-slider.hori-timeline-nav .slider-nav-item {
                  text-align: center; }

                .slick-slider.fade:not(slow) {
                  opacity: 1; }

                /************** vertical timeline **************/
                .verti-timeline {
                  border-left: 2px solid #f4f8f9;
                  margin: 0 5px; }
                  .verti-timeline .event-list {
                    position: relative;
                    padding: 0px 0px 25px 30px; }
                    .verti-timeline .event-list:before {
                      content: "";
                      position: absolute;
                      width: 20px;
                      height: 20px;
                      left: -2px;
                      top: 8px;
                      border-top: 6px double #f4f8f9;
                      border-left: 2px solid #f4f8f9;
                      border-top-left-radius: 15px; }
                    .verti-timeline .event-list .event-content {
                      position: relative;
                      border: 2px solid #eff2f7;
                      border-radius: 7px; }
                    .verti-timeline .event-list.active .event-timeline-dot {
                      color: #3d8ef8; }
                    .verti-timeline .event-list:last-child {
                      padding-bottom: 0px; }

                .gallery-box a .gallery-content {
                  border-radius: 4px;
                  overflow: hidden; }

                .gallery-box a .gallery-overlay {
                  position: absolute;
                  left: 0;
                  right: 0;
                  top: 0;
                  bottom: 0;
                  background: #3d8ef8;
                  opacity: 0;
                  -webkit-transition: all 0.5s;
                  transition: all 0.5s; }

                .gallery-box a .overlay-content {
                  position: absolute;
                  left: 0;
                  right: 0;
                  bottom: -60px;
                  background-color: #fff;
                  -webkit-transition: all 0.5s;
                  transition: all 0.5s;
                  padding: 16px 12px 12px 12px;
                  margin: 0px 12px;
                  border-radius: 4px; }

                .gallery-box a .gallery-overlay-icon {
                  position: absolute;
                  top: 50%;
                  left: 0;
                  right: 0;
                  -webkit-transform: translateY(-85%);
                          transform: translateY(-85%);
                  -webkit-transition: all 0.5s;
                  transition: all 0.5s; }
                  .gallery-box a .gallery-overlay-icon i {
                    font-size: 28px; }

                .gallery-box a:hover .gallery-overlay {
                  opacity: 0.7; }

                .gallery-box a:hover .overlay-content {
                  position: absolute;
                  bottom: 12px; }


            </style>
            
        <!-- App Css Ends -->

    </head>

    <body data-sidebar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">

            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <div class="dropdown">
                                <a href="index.html" class="logo logo-light">

                                    <a href="#" class="Logo-plus" data-toggle="dropdown">+</a>
                                    
                                    <!-- <span class="logo-lg">
                                        <img src="assets/images/logo-sales.png" alt="" height="25">
                                    </span> -->
                                </a>
                                <div class="dropdown-menu main-menu-dropdown">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="border-bottom pb-2">Create</h5>
                                        </div>
                                        <div class="clearfix"></div>
                                        <!-- item-->
                                        <div class="col">
                                            <ul class="list-group">
                                              <li class="list-group-item list-item-title"><b>Finance</b></li>
                                              <li class="list-group-item"><a href="">Cash Out</a></li>
                                              <li class="list-group-item"><a href="">Cash In</li>
                                              <li class="list-group-item"><a href="">Local Transfer</a></li>
                                            </ul>
                                        </div>

                                        <!-- item-->
                                        <div class="col">
                                            <ul class="list-group">
                                              <li class="list-group-item list-item-title"><b>Sales</b></li>
                                              <li class="list-group-item"><a href="">Quotation</a></li>
                                              <li class="list-group-item"><a href="">Sales Invoice</li>
                                              <li class="list-group-item"><a href="">Sales Return</a></li>
                                            </ul>
                                        </div>

                                        <!-- item-->
                                        <div class="col">
                                            <ul class="list-group">
                                              <li class="list-group-item list-item-title"><b>Point of Sales</b></li>
                                              <li class="list-group-item"><a href="">Purchase Order</a></li>
                                              <li class="list-group-item"><a href="">Purchases Invoice</li>
                                              <li class="list-group-item"><a href="">Purchases Return</a></li>
                                            </ul>
                                        </div>

                                        <!-- item-->
                                        <div class="col">
                                            <ul class="list-group">
                                              <li class="list-group-item list-item-title"><b>Human Resources</b></li>
                                              <li class="list-group-item"><a href="">Vacation Request</a></li>
                                              <li class="list-group-item"><a href="">Attendance and Departure</li>
                                              <li class="list-group-item"><a href="">Petty Cash</a></li>
                                            </ul>
                                        </div>

                                        <!-- item-->
                                        <div class="col">
                                            <ul class="list-group">
                                              <li class="list-group-item list-item-title"><b>Inventory</b></li>
                                              <li class="list-group-item"><a href="">Add Products</a></li>
                                              <li class="list-group-item"><a href="">Recognation</li>
                                              <li class="list-group-item"><a href="">Inventory Transfer</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </div>

                    <div class="d-flex header-right-block">

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-flag-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="" src="assets/images/us.jpg" alt="Header Language" height="14">
                            </button>
                            <div class="dropdown-menu language-dropdown dropdown-menu-right">
                                <!-- item-->
                                <a href="ar/report.html" class="dropdown-item notify-item">
                                    <img src="assets/images/saudi-arabia.jpg" alt="user-image" class="mr-2" height="12"><span class="align-middle">Arabic</span>
                                </a>
                            </div>
                        </div>

                        <div class="d-inline-block">
                            <button class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="nav-admin">Haytham</span>
                                <img class="nav-profile" src="assets/images/no-pic.png">
                            </button>

                            <div class="dropdown-menu dropdown-menu-right user-dropdown">
                                <!-- item-->
                                <a class="dropdown-item" href="#"><i class="mdi mdi-face-profile font-size-16 align-middle mr-1"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-credit-card-outline font-size-16 align-middle mr-1"></i> Billing</a>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-account-settings font-size-16 align-middle mr-1"></i> Settings</a>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-lock font-size-16 align-middle mr-1"></i> Lock screen</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-logout font-size-16 align-middle mr-1"></i> Logout</a>
                            </div>

                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-th" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                

                                <ul class="nav nav-pills dashboard-menu">


                                <li>

                                  <a href="#">

                                   <i class="fa fa-search-plus" aria-hidden="true"></i><br/>

                                    <span>Human Resource</span> 

                                   </a>

                                </li>

                                <li>

                                  <a href="#">

                                    <i class="fas fa-tags" aria-hidden="true"></i><br/>

                                    <span>Sales</span> 

                                   </a>

                                </li>

                                <li>

                                   <a href="#">

                                    <i class="fa fa-bar-chart" aria-hidden="true"></i><br/>

                                    <span>Statistics</span> 

                                   </a>


                                </li>

                                </ul>

                                <ul class="nav nav-pills dashboard-menu">

                                <li>

                                  <a href="#">

                                    <i class="fas fa-shield-alt"></i><br/>

                                    <span>Inventory</span> 

                                   </a>

                                </li>

                                <li>

                                  <a href="#">

                                    <i class="fa fa-money" aria-hidden="true"></i><br/>

                                    <span>Finances</span> 

                                   </a>

                                </li>

                                <li>

                                  <a href="#">

                                    <i class="fas fa-tags"></i><br/>

                                    <span>Point of sales</span> 

                                   </a>

                                </li>

                                </ul>

                                <ul class="nav nav-pills dashboard-menu">

                                <li>

                                  <a href="#">

                                    <i class="fas fa-shopping-bag" aria-hidden="true"></i><br/>

                                    <span>Purchases</span> 

                                   </a>

                                </li>

                              </ul>

                            </div>
                        </div>
            
                    </div>
                </div>
            </header>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="page-title-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="mb-0 font-size-16">Expences</h4>
                                        </div>
                                        <div class="col-6">  
                                            <div class="dropdown">                                    
                                              <a href="#" class="icon-1 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                              <a href="#" class="icon-1"><i class="fa fa-signal" aria-hidden="true"></i></a>
                                              <div class="dropdown-menu mt-4 user-dropdown dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                              </div>
                                            </div>
                                            <button class="btn btn-drop">
                                                Addition
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>     
                         end page title -->

                        <div class="row">
                            <div class="col-lg-7 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 text-center mb-5">
                                                <img src="assets/images/logo.jpg" class="img-fluid" alt="Logo" width="130" />
                                            </div>
                                            <div class="col-lg-12">
                                                <h5 class="border-bottom pb-2">Bill To
                                                    <a href="#">
                                                        <span style="font-size:85%;" class="pull-right">
                                                            <i style="font-size:90%;" class="fa fa-share-square" aria-hidden="true"></i>
                                                            Invoice
                                                        </span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="invoice-to-logo">
                                                    <img src="assets/images/logo.jpg" class="img-fluid mb-3" alt="Logo" width="100" />
                                                    <p><b>Saudi Arabian Company</b></p>
                                                    <p>Jeddah No. 2751, Kingdom of Saudi Arabia</p>
                                                    <p>Tel : +9952757871773</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">

                                                <table class="table table-borderless table-responsive"style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <tr>
                                                        <td>Invoice number:</td>
                                                        <td>#00525</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Invoice Date:</td>
                                                        <td>06/10/2020</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date of payment:</td>
                                                        <td>06/10/2021</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-lg-12">
                                                <span class="pull-right">
                                                    <button data-jscolor="{
                                                        onChange: 'update(this, \'#pr1\')',
                                                        onInput: 'update(this, \'#pr2\')',
                                                        alpha:1, value:'094269'}">
                                                            
                                                    </button> Edit
                                                </span>

                                                <table class="table table-custom dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead id="pr2" style="background:#094269;color:#fff;">
                                                        <tr>
                                                            <th style="width:50%">Products</th>
                                                            <th>Quantity</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Sample Data</td>
                                                            <td>1</td>
                                                            <td>120</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sample Data</td>
                                                            <td>1</td>
                                                            <td>120</td>
                                                        </tr>
                                                    </tbody>                           
                                                </table>                                    
                                            </div>
                                            <div class="col-lg-6 ml-auto">
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">Tax rate:</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">14%</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">Discount percentage:</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">3.2%</span>
                                                </div>
                                                <div id="pr1" class="blue-box" style="background:#094269!important;color:#fff;">
                                                    <div style="float: left; width: 50%;">
                                                        <span class="font-size-14">Grand Total</span>
                                                    </div>
                                                    <div style="float: left; width: 50%;">
                                                        <span class="font-size-14">0</span> Riyals
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                function update(picker, selector) {
                                                    document.querySelector(selector).style.background = picker.toBackground()
                                                }

                                                // triggers 'onInput' and 'onChange' on all color pickers when they are ready
                                                jscolor.trigger('input change');
                                                </script>
                                            <div class="col-lg-12 mt-5 text-center">
                                                <h5>We thank you for doing business with us</h5>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="sticky-footer">
                    2020 © Wosul
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        
        <!-- JAVASCRIPT -->

            <!-- File name jquery.min.js -->

            <!-- File name bootstrap.bundle.min.js -->
        
        <!-- Javascript Table Header Color Starts -->

            <!-- File name jscolor.js -->

            <script type="text/javascript">
                
                                /**
                 * jscolor - JavaScript Color Picker
                 *
                 * @link    http://jscolor.com
                 * @license For open source use: GPLv3
                 *          For commercial use: JSColor Commercial License
                 * @author  Jan Odvarko - East Desire
                 * @version 2.3.3
                 *
                 * See usage examples at http://jscolor.com/examples/
                 */


                "use strict";


                if (!window.jscolor) {

                window.jscolor = (function () { // BEGIN window.jscolor

                var jsc = {


                    initialized : false,

                    instances : [], // created instances of jscolor

                    triggerQueue : [], // events waiting to be triggered after init


                    register : function () {
                        document.addEventListener('DOMContentLoaded', jsc.init, false);
                        document.addEventListener('mousedown', jsc.onDocumentMouseDown, false);
                        document.addEventListener('keyup', jsc.onDocumentKeyUp, false);
                        window.addEventListener('resize', jsc.onWindowResize, false);
                    },


                    init : function () {
                        if (jsc.initialized) {
                            return;
                        }

                        jsc.pub.install();
                        jsc.initialized = true;

                        // trigger events waiting in the queue
                        while (jsc.triggerQueue.length) {
                            var ev = jsc.triggerQueue.shift();
                            jsc.triggerGlobal(ev);
                        }
                    },


                    installBySelector : function (selector, rootNode) {
                        rootNode = rootNode ? jsc.node(rootNode) : document;
                        if (!rootNode) {
                            throw new Error('Missing root node');
                        }

                        var elms = rootNode.querySelectorAll(selector);

                        // for backward compatibility with DEPRECATED installation/configuration using className
                        var matchClass = new RegExp('(^|\\s)(' + jsc.pub.lookupClass + ')(\\s*(\\{[^}]*\\})|\\s|$)', 'i');

                        for (var i = 0; i < elms.length; i += 1) {

                            if (elms[i].jscolor && elms[i].jscolor instanceof jsc.pub) {
                                continue; // jscolor already installed on this element
                            }

                            if (elms[i].type !== undefined && elms[i].type.toLowerCase() == 'color' && jsc.isColorAttrSupported) {
                                continue; // skips inputs of type 'color' if supported by the browser
                            }

                            var dataOpts, m;

                            if (
                                (dataOpts = jsc.getDataAttr(elms[i], 'jscolor')) !== null ||
                                (elms[i].className && (m = elms[i].className.match(matchClass))) // installation using className (DEPRECATED)
                            ) {
                                var targetElm = elms[i];

                                var optsStr = '';
                                if (dataOpts !== null) {
                                    optsStr = dataOpts;

                                } else if (m) { // installation using className (DEPRECATED)
                                    console.warn('Installation using class name is DEPRECATED. Use data-jscolor="" attribute instead.' + jsc.docsRef);
                                    if (m[4]) {
                                        optsStr = m[4];
                                    }
                                }

                                var opts = null;
                                if (optsStr.trim()) {
                                    try {
                                        opts = jsc.parseOptionsStr(optsStr);
                                    } catch (e) {
                                        console.warn(e + '\n' + optsStr);
                                    }
                                }

                                try {
                                    new jsc.pub(targetElm, opts);
                                } catch (e) {
                                    console.warn(e);
                                }
                            }
                        }
                    },


                    parseOptionsStr : function (str) {
                        var opts = null;

                        try {
                            opts = JSON.parse(str);

                        } catch (eParse) {
                            if (!jsc.pub.looseJSON) {
                                throw new Error('Could not parse jscolor options as JSON: ' + eParse);
                            } else {
                                // loose JSON syntax is enabled -> try to evaluate the options string as JavaScript object
                                try {
                                    opts = (new Function ('var opts = (' + str + '); return typeof opts === "object" ? opts : {};'))();
                                } catch (eEval) {
                                    throw new Error('Could not evaluate jscolor options: ' + eEval);
                                }
                            }
                        }
                        return opts;
                    },


                    getInstances : function () {
                        var inst = [];
                        for (var i = 0; i < jsc.instances.length; i += 1) {
                            // if the targetElement still exists, the instance is considered "alive"
                            if (jsc.instances[i] && jsc.instances[i].targetElement) {
                                inst.push(jsc.instances[i]);
                            }
                        }
                        return inst;
                    },


                    createEl : function (tagName) {
                        var el = document.createElement(tagName);
                        jsc.setData(el, 'gui', true)
                        return el;
                    },


                    node : function (nodeOrSelector) {
                        if (!nodeOrSelector) {
                            return null;
                        }

                        if (typeof nodeOrSelector === 'string') {
                            // query selector
                            var sel = nodeOrSelector;
                            var el = null;
                            try {
                                el = document.querySelector(sel);
                            } catch (e) {
                                console.warn(e);
                                return null;
                            }
                            if (!el) {
                                console.warn('No element matches the selector: %s', sel);
                            }
                            return el;
                        }

                        if (jsc.isNode(nodeOrSelector)) {
                            // DOM node
                            return nodeOrSelector;
                        }

                        console.warn('Invalid node of type %s: %s', typeof nodeOrSelector, nodeOrSelector);
                        return null;
                    },


                    // See https://stackoverflow.com/questions/384286/
                    isNode : function (val) {
                        if (typeof Node === 'object') {
                            return val instanceof Node;
                        }
                        return val && typeof val === 'object' && typeof val.nodeType === 'number' && typeof val.nodeName === 'string';
                    },


                    nodeName : function (node) {
                        if (node && node.nodeName) {
                            return node.nodeName.toLowerCase();
                        }
                        return false;
                    },


                    removeChildren : function (node) {
                        while (node.firstChild) {
                            node.removeChild(node.firstChild);
                        }
                    },


                    isTextInput : function (el) {
                        return el && jsc.nodeName(el) === 'input' && el.type.toLowerCase() === 'text';
                    },


                    isButton : function (el) {
                        if (!el) {
                            return false;
                        }
                        var n = jsc.nodeName(el);
                        return (
                            (n === 'button') ||
                            (n === 'input' && ['button', 'submit', 'reset'].indexOf(el.type.toLowerCase()) > -1)
                        );
                    },


                    isButtonEmpty : function (el) {
                        switch (jsc.nodeName(el)) {
                            case 'input': return (!el.value || el.value.trim() === '');
                            case 'button': return (el.textContent.trim() === '');
                        }
                        return null; // could not determine element's text
                    },


                    // See https://github.com/WICG/EventListenerOptions/blob/gh-pages/explainer.md
                    isPassiveEventSupported : (function () {
                        var supported = false;

                        try {
                            var opts = Object.defineProperty({}, 'passive', {
                                get: function () { supported = true; }
                            });
                            window.addEventListener('testPassive', null, opts);
                            window.removeEventListener('testPassive', null, opts);
                        } catch (e) {}

                        return supported;
                    })(),


                    isColorAttrSupported : (function () {
                        var elm = document.createElement('input');
                        if (elm.setAttribute) {
                            elm.setAttribute('type', 'color');
                            if (elm.type.toLowerCase() == 'color') {
                                return true;
                            }
                        }
                        return false;
                    })(),


                    dataProp : '_data_jscolor',


                    // usage:
                    //   setData(obj, prop, value)
                    //   setData(obj, {prop:value, ...})
                    //
                    setData : function () {
                        var obj = arguments[0];

                        if (arguments.length === 3) {
                            // setting a single property
                            var data = obj.hasOwnProperty(jsc.dataProp) ? obj[jsc.dataProp] : (obj[jsc.dataProp] = {});
                            var prop = arguments[1];
                            var value = arguments[2];

                            data[prop] = value;
                            return true;

                        } else if (arguments.length === 2 && typeof arguments[1] === 'object') {
                            // setting multiple properties
                            var data = obj.hasOwnProperty(jsc.dataProp) ? obj[jsc.dataProp] : (obj[jsc.dataProp] = {});
                            var map = arguments[1];

                            for (var prop in map) {
                                if (map.hasOwnProperty(prop)) {
                                    data[prop] = map[prop];
                                }
                            }
                            return true;
                        }

                        throw new Error('Invalid arguments');
                    },


                    // usage:
                    //   removeData(obj, prop, [prop...])
                    //
                    removeData : function () {
                        var obj = arguments[0];
                        if (!obj.hasOwnProperty(jsc.dataProp)) {
                            return true; // data object does not exist
                        }
                        for (var i = 1; i < arguments.length; i += 1) {
                            var prop = arguments[i];
                            delete obj[jsc.dataProp][prop];
                        }
                        return true;
                    },


                    getData : function (obj, prop, setDefault) {
                        if (!obj.hasOwnProperty(jsc.dataProp)) {
                            // data object does not exist
                            if (setDefault !== undefined) {
                                obj[jsc.dataProp] = {}; // create data object
                            } else {
                                return undefined; // no value to return
                            }
                        }
                        var data = obj[jsc.dataProp];

                        if (!data.hasOwnProperty(prop) && setDefault !== undefined) {
                            data[prop] = setDefault;
                        }
                        return data[prop];
                    },


                    getDataAttr : function (el, name) {
                        var attrName = 'data-' + name;
                        var attrValue = el.getAttribute(attrName);
                        return attrValue;
                    },


                    _attachedGroupEvents : {},


                    attachGroupEvent : function (groupName, el, evnt, func) {
                        if (!jsc._attachedGroupEvents.hasOwnProperty(groupName)) {
                            jsc._attachedGroupEvents[groupName] = [];
                        }
                        jsc._attachedGroupEvents[groupName].push([el, evnt, func]);
                        el.addEventListener(evnt, func, false);
                    },


                    detachGroupEvents : function (groupName) {
                        if (jsc._attachedGroupEvents.hasOwnProperty(groupName)) {
                            for (var i = 0; i < jsc._attachedGroupEvents[groupName].length; i += 1) {
                                var evt = jsc._attachedGroupEvents[groupName][i];
                                evt[0].removeEventListener(evt[1], evt[2], false);
                            }
                            delete jsc._attachedGroupEvents[groupName];
                        }
                    },


                    preventDefault : function (e) {
                        if (e.preventDefault) { e.preventDefault(); }
                        e.returnValue = false;
                    },


                    captureTarget : function (target) {
                        // IE
                        if (target.setCapture) {
                            jsc._capturedTarget = target;
                            jsc._capturedTarget.setCapture();
                        }
                    },


                    releaseTarget : function () {
                        // IE
                        if (jsc._capturedTarget) {
                            jsc._capturedTarget.releaseCapture();
                            jsc._capturedTarget = null;
                        }
                    },


                    triggerEvent : function (el, eventName, bubbles, cancelable) {
                        if (!el) {
                            return;
                        }

                        var ev = null;

                        if (typeof Event === 'function') {
                            ev = new Event(eventName, {
                                bubbles: bubbles,
                                cancelable: cancelable
                            });
                        } else {
                            // IE
                            ev = document.createEvent('Event');
                            ev.initEvent(eventName, bubbles, cancelable);
                        }

                        if (!ev) {
                            return false;
                        }

                        // so that we know that the event was triggered internally
                        jsc.setData(ev, 'internal', true);

                        el.dispatchEvent(ev);
                        return true;
                    },


                    triggerInputEvent : function (el, eventName, bubbles, cancelable) {
                        if (!el) {
                            return;
                        }
                        if (jsc.isTextInput(el)) {
                            jsc.triggerEvent(el, eventName, bubbles, cancelable);
                        }
                    },


                    eventKey : function (ev) {
                        var keys = {
                            9: 'Tab',
                            13: 'Enter',
                            27: 'Escape',
                        };
                        if (typeof ev.code === 'string') {
                            return ev.code;
                        } else if (ev.keyCode !== undefined && keys.hasOwnProperty(ev.keyCode)) {
                            return keys[ev.keyCode];
                        }
                        return null;
                    },


                    strList : function (str) {
                        if (!str) {
                            return [];
                        }
                        return str.replace(/^\s+|\s+$/g, '').split(/\s+/);
                    },


                    // The className parameter (str) can only contain a single class name
                    hasClass : function (elm, className) {
                        if (!className) {
                            return false;
                        }
                        if (elm.classList !== undefined) {
                            return elm.classList.contains(className);
                        }
                        // polyfill
                        return -1 != (' ' + elm.className.replace(/\s+/g, ' ') + ' ').indexOf(' ' + className + ' ');
                    },


                    // The className parameter (str) can contain multiple class names separated by whitespace
                    addClass : function (elm, className) {
                        var classNames = jsc.strList(className);

                        if (elm.classList !== undefined) {
                            for (var i = 0; i < classNames.length; i += 1) {
                                elm.classList.add(classNames[i]);
                            }
                            return;
                        }
                        // polyfill
                        for (var i = 0; i < classNames.length; i += 1) {
                            if (!jsc.hasClass(elm, classNames[i])) {
                                elm.className += (elm.className ? ' ' : '') + classNames[i];
                            }
                        }
                    },


                    // The className parameter (str) can contain multiple class names separated by whitespace
                    removeClass : function (elm, className) {
                        var classNames = jsc.strList(className);

                        if (elm.classList !== undefined) {
                            for (var i = 0; i < classNames.length; i += 1) {
                                elm.classList.remove(classNames[i]);
                            }
                            return;
                        }
                        // polyfill
                        for (var i = 0; i < classNames.length; i += 1) {
                            var repl = new RegExp(
                                '^\\s*' + classNames[i] + '\\s*|' +
                                '\\s*' + classNames[i] + '\\s*$|' +
                                '\\s+' + classNames[i] + '(\\s+)',
                                'g'
                            );
                            elm.className = elm.className.replace(repl, '$1');
                        }
                    },


                    getCompStyle : function (elm) {
                        var compStyle = window.getComputedStyle ? window.getComputedStyle(elm) : elm.currentStyle;

                        // Note: In Firefox, getComputedStyle returns null in a hidden iframe,
                        // that's why we need to check if the returned value is non-empty
                        if (!compStyle) {
                            return {};
                        }
                        return compStyle;
                    },


                    // Note:
                    //   Setting a property to NULL reverts it to the state before it was first set
                    //   with the 'reversible' flag enabled
                    //
                    setStyle : function (elm, styles, important, reversible) {
                        // using '' for standard priority (IE10 apparently doesn't like value undefined)
                        var priority = important ? 'important' : '';
                        var origStyle = null;

                        for (var prop in styles) {
                            if (styles.hasOwnProperty(prop)) {
                                var setVal = null;

                                if (styles[prop] === null) {
                                    // reverting a property value

                                    if (!origStyle) {
                                        // get the original style object, but dont't try to create it if it doesn't exist
                                        origStyle = jsc.getData(elm, 'origStyle');
                                    }
                                    if (origStyle && origStyle.hasOwnProperty(prop)) {
                                        // we have property's original value -> use it
                                        setVal = origStyle[prop];
                                    }

                                } else {
                                    // setting a property value

                                    if (reversible) {
                                        if (!origStyle) {
                                            // get the original style object and if it doesn't exist, create it
                                            origStyle = jsc.getData(elm, 'origStyle', {});
                                        }
                                        if (!origStyle.hasOwnProperty(prop)) {
                                            // original property value not yet stored -> store it
                                            origStyle[prop] = elm.style[prop];
                                        }
                                    }
                                    setVal = styles[prop];
                                }

                                if (setVal !== null) {
                                    elm.style.setProperty(prop, setVal, priority);
                                }
                            }
                        }
                    },


                    linearGradient : (function () {

                        function getFuncName () {
                            var stdName = 'linear-gradient';
                            var prefixes = ['', '-webkit-', '-moz-', '-o-', '-ms-'];
                            var helper = document.createElement('div');

                            for (var i = 0; i < prefixes.length; i += 1) {
                                var tryFunc = prefixes[i] + stdName;
                                var tryVal = tryFunc + '(to right, rgba(0,0,0,0), rgba(0,0,0,0))';

                                helper.style.background = tryVal;
                                if (helper.style.background) { // CSS background successfully set -> function name is supported
                                    return tryFunc;
                                }
                            }
                            return stdName; // fallback to standard 'linear-gradient' without vendor prefix
                        }

                        var funcName = getFuncName();

                        return function () {
                            return funcName + '(' + Array.prototype.join.call(arguments, ', ') + ')';
                        };

                    })(),


                    setBorderRadius : function (elm, value) {
                        jsc.setStyle(elm, {'border-radius' : value || '0'});
                    },


                    setBoxShadow : function (elm, value) {
                        jsc.setStyle(elm, {'box-shadow': value || 'none'});
                    },


                    getElementPos : function (e, relativeToViewport) {
                        var x=0, y=0;
                        var rect = e.getBoundingClientRect();
                        x = rect.left;
                        y = rect.top;
                        if (!relativeToViewport) {
                            var viewPos = jsc.getViewPos();
                            x += viewPos[0];
                            y += viewPos[1];
                        }
                        return [x, y];
                    },


                    getElementSize : function (e) {
                        return [e.offsetWidth, e.offsetHeight];
                    },


                    // get pointer's X/Y coordinates relative to viewport
                    getAbsPointerPos : function (e) {
                        var x = 0, y = 0;
                        if (typeof e.changedTouches !== 'undefined' && e.changedTouches.length) {
                            // touch devices
                            x = e.changedTouches[0].clientX;
                            y = e.changedTouches[0].clientY;
                        } else if (typeof e.clientX === 'number') {
                            x = e.clientX;
                            y = e.clientY;
                        }
                        return { x: x, y: y };
                    },


                    // get pointer's X/Y coordinates relative to target element
                    getRelPointerPos : function (e) {
                        var target = e.target || e.srcElement;
                        var targetRect = target.getBoundingClientRect();

                        var x = 0, y = 0;

                        var clientX = 0, clientY = 0;
                        if (typeof e.changedTouches !== 'undefined' && e.changedTouches.length) {
                            // touch devices
                            clientX = e.changedTouches[0].clientX;
                            clientY = e.changedTouches[0].clientY;
                        } else if (typeof e.clientX === 'number') {
                            clientX = e.clientX;
                            clientY = e.clientY;
                        }

                        x = clientX - targetRect.left;
                        y = clientY - targetRect.top;
                        return { x: x, y: y };
                    },


                    getViewPos : function () {
                        var doc = document.documentElement;
                        return [
                            (window.pageXOffset || doc.scrollLeft) - (doc.clientLeft || 0),
                            (window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0)
                        ];
                    },


                    getViewSize : function () {
                        var doc = document.documentElement;
                        return [
                            (window.innerWidth || doc.clientWidth),
                            (window.innerHeight || doc.clientHeight),
                        ];
                    },


                    // r: 0-255
                    // g: 0-255
                    // b: 0-255
                    //
                    // returns: [ 0-360, 0-100, 0-100 ]
                    //
                    RGB_HSV : function (r, g, b) {
                        r /= 255;
                        g /= 255;
                        b /= 255;
                        var n = Math.min(Math.min(r,g),b);
                        var v = Math.max(Math.max(r,g),b);
                        var m = v - n;
                        if (m === 0) { return [ null, 0, 100 * v ]; }
                        var h = r===n ? 3+(b-g)/m : (g===n ? 5+(r-b)/m : 1+(g-r)/m);
                        return [
                            60 * (h===6?0:h),
                            100 * (m/v),
                            100 * v
                        ];
                    },


                    // h: 0-360
                    // s: 0-100
                    // v: 0-100
                    //
                    // returns: [ 0-255, 0-255, 0-255 ]
                    //
                    HSV_RGB : function (h, s, v) {
                        var u = 255 * (v / 100);

                        if (h === null) {
                            return [ u, u, u ];
                        }

                        h /= 60;
                        s /= 100;

                        var i = Math.floor(h);
                        var f = i%2 ? h-i : 1-(h-i);
                        var m = u * (1 - s);
                        var n = u * (1 - s * f);
                        switch (i) {
                            case 6:
                            case 0: return [u,n,m];
                            case 1: return [n,u,m];
                            case 2: return [m,u,n];
                            case 3: return [m,n,u];
                            case 4: return [n,m,u];
                            case 5: return [u,m,n];
                        }
                    },


                    parseColorString : function (str) {
                        var ret = {
                            rgba: null,
                            format: null // 'hex' | 'rgb' | 'rgba'
                        };

                        var m;
                        if (m = str.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i)) {
                            // HEX notation

                            ret.format = 'hex';

                            if (m[1].length === 6) {
                                // 6-char notation
                                ret.rgba = [
                                    parseInt(m[1].substr(0,2),16),
                                    parseInt(m[1].substr(2,2),16),
                                    parseInt(m[1].substr(4,2),16),
                                    null
                                ];
                            } else {
                                // 3-char notation
                                ret.rgba = [
                                    parseInt(m[1].charAt(0) + m[1].charAt(0),16),
                                    parseInt(m[1].charAt(1) + m[1].charAt(1),16),
                                    parseInt(m[1].charAt(2) + m[1].charAt(2),16),
                                    null
                                ];
                            }
                            return ret;

                        } else if (m = str.match(/^\W*rgba?\(([^)]*)\)\W*$/i)) {
                            // rgb(...) or rgba(...) notation

                            var params = m[1].split(',');
                            var re = /^\s*(\d+|\d*\.\d+|\d+\.\d*)\s*$/;
                            var mR, mG, mB, mA;
                            if (
                                params.length >= 3 &&
                                (mR = params[0].match(re)) &&
                                (mG = params[1].match(re)) &&
                                (mB = params[2].match(re))
                            ) {
                                ret.format = 'rgb';
                                ret.rgba = [
                                    parseFloat(mR[1]) || 0,
                                    parseFloat(mG[1]) || 0,
                                    parseFloat(mB[1]) || 0,
                                    null
                                ];

                                if (
                                    params.length >= 4 &&
                                    (mA = params[3].match(re))
                                ) {
                                    ret.format = 'rgba';
                                    ret.rgba[3] = parseFloat(mA[1]) || 0;
                                }
                                return ret;
                            }
                        }

                        return false;
                    },


                    // Canvas scaling for retina displays
                    //
                    // adapted from https://www.html5rocks.com/en/tutorials/canvas/hidpi/
                    //
                    scaleCanvasForHighDPR : function (canvas) {
                        var dpr = window.devicePixelRatio || 1;
                        canvas.width *= dpr;
                        canvas.height *= dpr;
                        var ctx = canvas.getContext('2d');
                        ctx.scale(dpr, dpr);
                    },


                    genColorPreviewCanvas : function (color, separatorPos, specWidth, scaleForHighDPR) {

                        var sepW = Math.round(jsc.pub.previewSeparator.length);
                        var sqSize = jsc.pub.chessboardSize;
                        var sqColor1 = jsc.pub.chessboardColor1;
                        var sqColor2 = jsc.pub.chessboardColor2;

                        var cWidth = specWidth ? specWidth : sqSize * 2;
                        var cHeight = sqSize * 2;

                        var canvas = jsc.createEl('canvas');
                        var ctx = canvas.getContext('2d');

                        canvas.width = cWidth;
                        canvas.height = cHeight;
                        if (scaleForHighDPR) {
                            jsc.scaleCanvasForHighDPR(canvas);
                        }

                        // transparency chessboard - background
                        ctx.fillStyle = sqColor1;
                        ctx.fillRect(0, 0, cWidth, cHeight);

                        // transparency chessboard - squares
                        ctx.fillStyle = sqColor2;
                        for (var x = 0; x < cWidth; x += sqSize * 2) {
                            ctx.fillRect(x, 0, sqSize, sqSize);
                            ctx.fillRect(x + sqSize, sqSize, sqSize, sqSize);
                        }

                        if (color) {
                            // actual color in foreground
                            ctx.fillStyle = color;
                            ctx.fillRect(0, 0, cWidth, cHeight);
                        }

                        var start = null;
                        switch (separatorPos) {
                            case 'left':
                                start = 0;
                                ctx.clearRect(0, 0, sepW/2, cHeight);
                                break;
                            case 'right':
                                start = cWidth - sepW;
                                ctx.clearRect(cWidth - (sepW/2), 0, sepW/2, cHeight);
                                break;
                        }
                        if (start !== null) {
                            ctx.lineWidth = 1;
                            for (var i = 0; i < jsc.pub.previewSeparator.length; i += 1) {
                                ctx.beginPath();
                                ctx.strokeStyle = jsc.pub.previewSeparator[i];
                                ctx.moveTo(0.5 + start + i, 0);
                                ctx.lineTo(0.5 + start + i, cHeight);
                                ctx.stroke();
                            }
                        }

                        return {
                            canvas: canvas,
                            width: cWidth,
                            height: cHeight,
                        };
                    },


                    // if position or width is not set => fill the entire element (0%-100%)
                    genColorPreviewGradient : function (color, position, width) {
                        var params = [];

                        if (position && width) {
                            params = [
                                'to ' + {'left':'right', 'right':'left'}[position],
                                color + ' 0%',
                                color + ' ' + width + 'px',
                                'rgba(0,0,0,0) ' + (width + 1) + 'px',
                                'rgba(0,0,0,0) 100%',
                            ];
                        } else {
                            params = [
                                'to right',
                                color + ' 0%',
                                color + ' 100%',
                            ];
                        }

                        return jsc.linearGradient.apply(this, params);
                    },


                    redrawPosition : function () {

                        if (jsc.picker && jsc.picker.owner) {
                            var thisObj = jsc.picker.owner;

                            var tp, vp;

                            if (thisObj.fixed) {
                                // Fixed elements are positioned relative to viewport,
                                // therefore we can ignore the scroll offset
                                tp = jsc.getElementPos(thisObj.targetElement, true); // target pos
                                vp = [0, 0]; // view pos
                            } else {
                                tp = jsc.getElementPos(thisObj.targetElement); // target pos
                                vp = jsc.getViewPos(); // view pos
                            }

                            var ts = jsc.getElementSize(thisObj.targetElement); // target size
                            var vs = jsc.getViewSize(); // view size
                            var ps = jsc.getPickerOuterDims(thisObj); // picker size
                            var a, b, c;
                            switch (thisObj.position.toLowerCase()) {
                                case 'left': a=1; b=0; c=-1; break;
                                case 'right':a=1; b=0; c=1; break;
                                case 'top':  a=0; b=1; c=-1; break;
                                default:     a=0; b=1; c=1; break;
                            }
                            var l = (ts[b]+ps[b])/2;

                            // compute picker position
                            if (!thisObj.smartPosition) {
                                var pp = [
                                    tp[a],
                                    tp[b]+ts[b]-l+l*c
                                ];
                            } else {
                                var pp = [
                                    -vp[a]+tp[a]+ps[a] > vs[a] ?
                                        (-vp[a]+tp[a]+ts[a]/2 > vs[a]/2 && tp[a]+ts[a]-ps[a] >= 0 ? tp[a]+ts[a]-ps[a] : tp[a]) :
                                        tp[a],
                                    -vp[b]+tp[b]+ts[b]+ps[b]-l+l*c > vs[b] ?
                                        (-vp[b]+tp[b]+ts[b]/2 > vs[b]/2 && tp[b]+ts[b]-l-l*c >= 0 ? tp[b]+ts[b]-l-l*c : tp[b]+ts[b]-l+l*c) :
                                        (tp[b]+ts[b]-l+l*c >= 0 ? tp[b]+ts[b]-l+l*c : tp[b]+ts[b]-l-l*c)
                                ];
                            }

                            var x = pp[a];
                            var y = pp[b];
                            var positionValue = thisObj.fixed ? 'fixed' : 'absolute';
                            var contractShadow =
                                (pp[0] + ps[0] > tp[0] || pp[0] < tp[0] + ts[0]) &&
                                (pp[1] + ps[1] < tp[1] + ts[1]);

                            jsc._drawPosition(thisObj, x, y, positionValue, contractShadow);
                        }
                    },


                    _drawPosition : function (thisObj, x, y, positionValue, contractShadow) {
                        var vShadow = contractShadow ? 0 : thisObj.shadowBlur; // px

                        jsc.picker.wrap.style.position = positionValue;
                        jsc.picker.wrap.style.left = x + 'px';
                        jsc.picker.wrap.style.top = y + 'px';

                        jsc.setBoxShadow(
                            jsc.picker.boxS,
                            thisObj.shadow ?
                                new jsc.BoxShadow(0, vShadow, thisObj.shadowBlur, 0, thisObj.shadowColor) :
                                null);
                    },


                    getPickerDims : function (thisObj) {
                        var dims = [
                            2 * thisObj.controlBorderWidth + 2 * thisObj.padding + thisObj.width,
                            2 * thisObj.controlBorderWidth + 2 * thisObj.padding + thisObj.height
                        ];
                        var sliderSpace = 2 * thisObj.controlBorderWidth + 2 * jsc.getControlPadding(thisObj) + thisObj.sliderSize;
                        if (jsc.getSliderChannel(thisObj)) {
                            dims[0] += sliderSpace;
                        }
                        if (thisObj.hasAlphaChannel()) {
                            dims[0] += sliderSpace;
                        }
                        if (thisObj.closeButton) {
                            dims[1] += 2 * thisObj.controlBorderWidth + thisObj.padding + thisObj.buttonHeight;
                        }
                        return dims;
                    },


                    getPickerOuterDims : function (thisObj) {
                        var dims = jsc.getPickerDims(thisObj);
                        return [
                            dims[0] + 2 * thisObj.borderWidth,
                            dims[1] + 2 * thisObj.borderWidth
                        ];
                    },


                    getControlPadding : function (thisObj) {
                        return Math.max(
                            thisObj.padding / 2,
                            (2 * thisObj.pointerBorderWidth + thisObj.pointerThickness) - thisObj.controlBorderWidth
                        );
                    },


                    getPadYChannel : function (thisObj) {
                        switch (thisObj.mode.charAt(1).toLowerCase()) {
                            case 'v': return 'v'; break;
                        }
                        return 's';
                    },


                    getSliderChannel : function (thisObj) {
                        if (thisObj.mode.length > 2) {
                            switch (thisObj.mode.charAt(2).toLowerCase()) {
                                case 's': return 's'; break;
                                case 'v': return 'v'; break;
                            }
                        }
                        return null;
                    },


                    onDocumentMouseDown : function (e) {
                        var target = e.target || e.srcElement;

                        if (target.jscolor && target.jscolor instanceof jsc.pub) { // clicked targetElement -> show picker
                            if (target.jscolor.showOnClick && !target.disabled) {
                                target.jscolor.show();
                            }
                        } else if (jsc.getData(target, 'gui')) { // clicked jscolor's GUI element
                            var control = jsc.getData(target, 'control');
                            if (control) {
                                // jscolor's control
                                jsc.onControlPointerStart(e, target, jsc.getData(target, 'control'), 'mouse');
                            }
                        } else {
                            // mouse is outside the picker's controls -> hide the color picker!
                            if (jsc.picker && jsc.picker.owner) {
                                jsc.picker.owner.tryHide();
                            }
                        }
                    },


                    onDocumentKeyUp : function (e) {
                        if (['Tab', 'Escape'].indexOf(jsc.eventKey(e)) !== -1) {
                            if (jsc.picker && jsc.picker.owner) {
                                jsc.picker.owner.tryHide();
                            }
                        }
                    },


                    onWindowResize : function (e) {
                        jsc.redrawPosition();
                    },


                    onParentScroll : function (e) {
                        // hide the picker when one of the parent elements is scrolled
                        if (jsc.picker && jsc.picker.owner) {
                            jsc.picker.owner.tryHide();
                        }
                    },


                    onPickerTouchStart : function (e) {
                        var target = e.target || e.srcElement;

                        if (jsc.getData(target, 'control')) {
                            jsc.onControlPointerStart(e, target, jsc.getData(target, 'control'), 'touch');
                        }
                    },


                    // calls function specified in picker's property
                    triggerCallback : function (thisObj, prop) {
                        if (!thisObj[prop]) {
                            return; // callback func not specified
                        }
                        var callback = null;

                        if (typeof thisObj[prop] === 'string') {
                            // string with code
                            try {
                                callback = new Function (thisObj[prop]);
                            } catch (e) {
                                console.error(e);
                            }
                        } else {
                            // function
                            callback = thisObj[prop];
                        }

                        if (callback) {
                            callback.call(thisObj);
                        }
                    },


                    // Triggers a color change related event(s) on all picker instances.
                    // It is possible to specify multiple events separated with a space.
                    triggerGlobal : function (eventNames) {
                        var inst = jsc.getInstances();
                        for (var i = 0; i < inst.length; i += 1) {
                            inst[i].trigger(eventNames);
                        }
                    },


                    _pointerMoveEvent : {
                        mouse: 'mousemove',
                        touch: 'touchmove'
                    },
                    _pointerEndEvent : {
                        mouse: 'mouseup',
                        touch: 'touchend'
                    },


                    _pointerOrigin : null,
                    _capturedTarget : null,


                    onControlPointerStart : function (e, target, controlName, pointerType) {
                        var thisObj = jsc.getData(target, 'instance');

                        jsc.preventDefault(e);
                        jsc.captureTarget(target);

                        var registerDragEvents = function (doc, offset) {
                            jsc.attachGroupEvent('drag', doc, jsc._pointerMoveEvent[pointerType],
                                jsc.onDocumentPointerMove(e, target, controlName, pointerType, offset));
                            jsc.attachGroupEvent('drag', doc, jsc._pointerEndEvent[pointerType],
                                jsc.onDocumentPointerEnd(e, target, controlName, pointerType));
                        };

                        registerDragEvents(document, [0, 0]);

                        if (window.parent && window.frameElement) {
                            var rect = window.frameElement.getBoundingClientRect();
                            var ofs = [-rect.left, -rect.top];
                            registerDragEvents(window.parent.window.document, ofs);
                        }

                        var abs = jsc.getAbsPointerPos(e);
                        var rel = jsc.getRelPointerPos(e);
                        jsc._pointerOrigin = {
                            x: abs.x - rel.x,
                            y: abs.y - rel.y
                        };

                        switch (controlName) {
                        case 'pad':
                            // if the value slider is at the bottom, move it up
                            if (jsc.getSliderChannel(thisObj) === 'v' && thisObj.channels.v === 0) {
                                thisObj.fromHSVA(null, null, 100, null);
                            }
                            jsc.setPad(thisObj, e, 0, 0);
                            break;

                        case 'sld':
                            jsc.setSld(thisObj, e, 0);
                            break;

                        case 'asld':
                            jsc.setASld(thisObj, e, 0);
                            break;
                        }
                        thisObj.trigger('input');
                    },


                    onDocumentPointerMove : function (e, target, controlName, pointerType, offset) {
                        return function (e) {
                            var thisObj = jsc.getData(target, 'instance');
                            switch (controlName) {
                            case 'pad':
                                jsc.setPad(thisObj, e, offset[0], offset[1]);
                                break;

                            case 'sld':
                                jsc.setSld(thisObj, e, offset[1]);
                                break;

                            case 'asld':
                                jsc.setASld(thisObj, e, offset[1]);
                                break;
                            }
                            thisObj.trigger('input');
                        }
                    },


                    onDocumentPointerEnd : function (e, target, controlName, pointerType) {
                        return function (e) {
                            var thisObj = jsc.getData(target, 'instance');
                            jsc.detachGroupEvents('drag');
                            jsc.releaseTarget();

                            // Always trigger changes AFTER detaching outstanding mouse handlers,
                            // in case some color change occured in user-defined onChange/onInput handler
                            // would intrude into current mouse events
                            thisObj.trigger('input');
                            thisObj.trigger('change');
                        };
                    },


                    setPad : function (thisObj, e, ofsX, ofsY) {
                        var pointerAbs = jsc.getAbsPointerPos(e);
                        var x = ofsX + pointerAbs.x - jsc._pointerOrigin.x - thisObj.padding - thisObj.controlBorderWidth;
                        var y = ofsY + pointerAbs.y - jsc._pointerOrigin.y - thisObj.padding - thisObj.controlBorderWidth;

                        var xVal = x * (360 / (thisObj.width - 1));
                        var yVal = 100 - (y * (100 / (thisObj.height - 1)));

                        switch (jsc.getPadYChannel(thisObj)) {
                        case 's': thisObj.fromHSVA(xVal, yVal, null, null); break;
                        case 'v': thisObj.fromHSVA(xVal, null, yVal, null); break;
                        }
                    },


                    setSld : function (thisObj, e, ofsY) {
                        var pointerAbs = jsc.getAbsPointerPos(e);
                        var y = ofsY + pointerAbs.y - jsc._pointerOrigin.y - thisObj.padding - thisObj.controlBorderWidth;
                        var yVal = 100 - (y * (100 / (thisObj.height - 1)));

                        switch (jsc.getSliderChannel(thisObj)) {
                        case 's': thisObj.fromHSVA(null, yVal, null, null); break;
                        case 'v': thisObj.fromHSVA(null, null, yVal, null); break;
                        }
                    },


                    setASld : function (thisObj, e, ofsY) {
                        var pointerAbs = jsc.getAbsPointerPos(e);
                        var y = ofsY + pointerAbs.y - jsc._pointerOrigin.y - thisObj.padding - thisObj.controlBorderWidth;
                        var yVal = 1.0 - (y * (1.0 / (thisObj.height - 1)));

                        if (yVal < 1.0) {
                            // if format is flexible and the current format doesn't support alpha, switch to a suitable one
                            if (thisObj.format.toLowerCase() === 'any' && thisObj.getFormat() !== 'rgba') {
                                thisObj._currentFormat = 'rgba';
                            }
                        }

                        thisObj.fromHSVA(null, null, null, yVal);
                    },


                    createPalette : function () {

                        var paletteObj = {
                            elm: null,
                            draw: null
                        };

                        var canvas = jsc.createEl('canvas');
                        var ctx = canvas.getContext('2d');

                        var drawFunc = function (width, height, type) {
                            canvas.width = width;
                            canvas.height = height;

                            ctx.clearRect(0, 0, canvas.width, canvas.height);

                            var hGrad = ctx.createLinearGradient(0, 0, canvas.width, 0);
                            hGrad.addColorStop(0 / 6, '#F00');
                            hGrad.addColorStop(1 / 6, '#FF0');
                            hGrad.addColorStop(2 / 6, '#0F0');
                            hGrad.addColorStop(3 / 6, '#0FF');
                            hGrad.addColorStop(4 / 6, '#00F');
                            hGrad.addColorStop(5 / 6, '#F0F');
                            hGrad.addColorStop(6 / 6, '#F00');

                            ctx.fillStyle = hGrad;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);

                            var vGrad = ctx.createLinearGradient(0, 0, 0, canvas.height);
                            switch (type.toLowerCase()) {
                            case 's':
                                vGrad.addColorStop(0, 'rgba(255,255,255,0)');
                                vGrad.addColorStop(1, 'rgba(255,255,255,1)');
                                break;
                            case 'v':
                                vGrad.addColorStop(0, 'rgba(0,0,0,0)');
                                vGrad.addColorStop(1, 'rgba(0,0,0,1)');
                                break;
                            }
                            ctx.fillStyle = vGrad;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                        };

                        paletteObj.elm = canvas;
                        paletteObj.draw = drawFunc;

                        return paletteObj;
                    },


                    createSliderGradient : function () {

                        var sliderObj = {
                            elm: null,
                            draw: null
                        };

                        var canvas = jsc.createEl('canvas');
                        var ctx = canvas.getContext('2d');

                        var drawFunc = function (width, height, color1, color2) {
                            canvas.width = width;
                            canvas.height = height;

                            ctx.clearRect(0, 0, canvas.width, canvas.height);

                            var grad = ctx.createLinearGradient(0, 0, 0, canvas.height);
                            grad.addColorStop(0, color1);
                            grad.addColorStop(1, color2);

                            ctx.fillStyle = grad;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                        };

                        sliderObj.elm = canvas;
                        sliderObj.draw = drawFunc;

                        return sliderObj;
                    },


                    createASliderGradient : function () {

                        var sliderObj = {
                            elm: null,
                            draw: null
                        };

                        var canvas = jsc.createEl('canvas');
                        var ctx = canvas.getContext('2d');

                        var drawFunc = function (width, height, color) {
                            canvas.width = width;
                            canvas.height = height;

                            ctx.clearRect(0, 0, canvas.width, canvas.height);

                            var sqSize = canvas.width / 2;
                            var sqColor1 = jsc.pub.chessboardColor1;
                            var sqColor2 = jsc.pub.chessboardColor2;

                            // dark gray background
                            ctx.fillStyle = sqColor1;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);

                            for (var y = 0; y < canvas.height; y += sqSize * 2) {
                                // light gray squares
                                ctx.fillStyle = sqColor2;
                                ctx.fillRect(0, y, sqSize, sqSize);
                                ctx.fillRect(sqSize, y + sqSize, sqSize, sqSize);
                            }

                            var grad = ctx.createLinearGradient(0, 0, 0, canvas.height);
                            grad.addColorStop(0, color);
                            grad.addColorStop(1, 'rgba(0,0,0,0)');

                            ctx.fillStyle = grad;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                        };

                        sliderObj.elm = canvas;
                        sliderObj.draw = drawFunc;

                        return sliderObj;
                    },


                    BoxShadow : (function () {
                        var BoxShadow = function (hShadow, vShadow, blur, spread, color, inset) {
                            this.hShadow = hShadow;
                            this.vShadow = vShadow;
                            this.blur = blur;
                            this.spread = spread;
                            this.color = color;
                            this.inset = !!inset;
                        };

                        BoxShadow.prototype.toString = function () {
                            var vals = [
                                Math.round(this.hShadow) + 'px',
                                Math.round(this.vShadow) + 'px',
                                Math.round(this.blur) + 'px',
                                Math.round(this.spread) + 'px',
                                this.color
                            ];
                            if (this.inset) {
                                vals.push('inset');
                            }
                            return vals.join(' ');
                        };

                        return BoxShadow;
                    })(),


                    flags : {
                        leaveValue : 1 << 0,
                        leaveAlpha : 1 << 1,
                        leavePreview : 1 << 2,
                    },


                    enumOpts : {
                        format: ['auto', 'any', 'hex', 'rgb', 'rgba'],
                        previewPosition: ['left', 'right'],
                        mode: ['hsv', 'hvs', 'hs', 'hv'],
                        position: ['left', 'right', 'top', 'bottom'],
                        alphaChannel: ['auto', true, false],
                    },


                    deprecatedOpts : {
                        // <old_option>: <new_option>  (<new_option> can be null)
                        'styleElement': 'previewElement',
                        'onFineChange': 'onInput',
                        'overwriteImportant': 'forceStyle',
                        'closable': 'closeButton',
                        'insetWidth': 'controlBorderWidth',
                        'insetColor': 'controlBorderColor',
                        'refine': null,
                    },


                    docsRef : ' ' + 'See https://jscolor.com/docs/',


                    //
                    // Usage:
                    // var myPicker = new JSColor(<targetElement> [, <options>])
                    //
                    // (constructor is accessible via both 'jscolor' and 'JSColor' name)
                    //

                    pub : function (targetElement, opts) {

                        var THIS = this;

                        if (!opts) {
                            opts = {};
                        }

                        this.channels = {
                            r: 255, // red [0-255]
                            g: 255, // green [0-255]
                            b: 255, // blue [0-255]
                            h: 0, // hue [0-360]
                            s: 0, // saturation [0-100]
                            v: 100, // value (brightness) [0-100]
                            a: 1.0, // alpha (opacity) [0.0 - 1.0]
                        };

                        // General options
                        //
                        this.format = 'auto'; // 'auto' | 'any' | 'hex' | 'rgb' | 'rgba' - Format of the input/output value
                        this.value = undefined; // INITIAL color value in any supported format. To change it later, use method fromString(), fromHSVA(), fromRGBA() or channel()
                        this.alpha = undefined; // INITIAL alpha value. To change it later, call method channel('A', <value>)
                        this.onChange = undefined; // called when color changes. Value can be either a function or a string with JS code.
                        this.onInput = undefined; // called repeatedly as the color is being changed, e.g. while dragging a slider. Value can be either a function or a string with JS code.
                        this.valueElement = undefined; // element that will be used to display and input the color value
                        this.alphaElement = undefined; // element that will be used to display and input the alpha (opacity) value
                        this.previewElement = undefined; // element that will preview the picked color using CSS background
                        this.previewPosition = 'left'; // 'left' | 'right' - position of the color preview in previewElement
                        this.previewSize = 32; // (px) width of the color preview displayed in previewElement
                        this.previewPadding = 8; // (px) space between color preview and content of the previewElement
                        this.required = true; // whether the associated text input must always contain a color value. If false, the input can be left empty.
                        this.hash = true; // whether to prefix the HEX color code with # symbol (only applicable for HEX format)
                        this.uppercase = true; // whether to show the HEX color code in upper case (only applicable for HEX format)
                        this.forceStyle = true; // whether to overwrite CSS style of the previewElement using !important flag

                        // Color Picker options
                        //
                        this.width = 181; // width of color palette (in px)
                        this.height = 101; // height of color palette (in px)
                        this.mode = 'HSV'; // 'HSV' | 'HVS' | 'HS' | 'HV' - layout of the color picker controls
                        this.alphaChannel = 'auto'; // 'auto' | true | false - if alpha channel is enabled, the alpha slider will be visible. If 'auto', it will be determined according to color format
                        this.position = 'bottom'; // 'left' | 'right' | 'top' | 'bottom' - position relative to the target element
                        this.smartPosition = true; // automatically change picker position when there is not enough space for it
                        this.showOnClick = true; // whether to show the picker when user clicks its target element
                        this.hideOnLeave = true; // whether to automatically hide the picker when user leaves its target element (e.g. upon clicking the document)
                        this.sliderSize = 16; // px
                        this.crossSize = 8; // px
                        this.closeButton = false; // whether to display the Close button
                        this.closeText = 'Close';
                        this.buttonColor = 'rgba(0,0,0,1)'; // CSS color
                        this.buttonHeight = 18; // px
                        this.padding = 12; // px
                        this.backgroundColor = 'rgba(255,255,255,1)'; // CSS color
                        this.borderWidth = 1; // px
                        this.borderColor = 'rgba(187,187,187,1)'; // CSS color
                        this.borderRadius = 8; // px
                        this.controlBorderWidth = 1; // px
                        this.controlBorderColor = 'rgba(187,187,187,1)'; // CSS color
                        this.shadow = true; // whether to display a shadow
                        this.shadowBlur = 15; // px
                        this.shadowColor = 'rgba(0,0,0,0.2)'; // CSS color
                        this.pointerColor = 'rgba(76,76,76,1)'; // CSS color
                        this.pointerBorderWidth = 1; // px
                        this.pointerBorderColor = 'rgba(255,255,255,1)'; // CSS color
                        this.pointerThickness = 2; // px
                        this.zIndex = 5000;
                        this.container = undefined; // where to append the color picker (BODY element by default)

                        // Experimental
                        //
                        this.minS = 0; // min allowed saturation (0 - 100)
                        this.maxS = 100; // max allowed saturation (0 - 100)
                        this.minV = 0; // min allowed value (brightness) (0 - 100)
                        this.maxV = 100; // max allowed value (brightness) (0 - 100)
                        this.minA = 0.0; // min allowed alpha (opacity) (0.0 - 1.0)
                        this.maxA = 1.0; // max allowed alpha (opacity) (0.0 - 1.0)


                        // let's process the DEPRECATED 'options' property (this will be later removed)
                        if (jsc.pub.options) {
                            // let's set custom default options, if specified
                            for (var opt in jsc.pub.options) {
                                if (jsc.pub.options.hasOwnProperty(opt)) {
                                    try {
                                        setOption(opt, jsc.pub.options[opt]);
                                    } catch (e) {
                                        console.warn(e);
                                    }
                                }
                            }
                        }


                        // let's apply configuration presets
                        //
                        var presetsArr = [];

                        if (opts.preset) {
                            if (typeof opts.preset === 'string') {
                                presetsArr = opts.preset.split(/\s+/);
                            } else if (Array.isArray(opts.preset)) {
                                presetsArr = opts.preset.slice(); // slice() to clone
                            } else {
                                console.warn('Unrecognized preset value');
                            }
                        }

                        // always use the 'default' preset. If it's not listed, append it to the end.
                        if (presetsArr.indexOf('default') === -1) {
                            presetsArr.push('default');
                        }

                        // let's apply the presets in reverse order, so that should there be any overlapping options,
                        // the formerly listed preset will override the latter
                        for (var i = presetsArr.length - 1; i >= 0; i -= 1) {
                            var pres = presetsArr[i];
                            if (!pres) {
                                continue; // preset is empty string
                            }
                            if (!jsc.pub.presets.hasOwnProperty(pres)) {
                                console.warn('Unknown preset: %s', pres);
                                continue;
                            }
                            for (var opt in jsc.pub.presets[pres]) {
                                if (jsc.pub.presets[pres].hasOwnProperty(opt)) {
                                    try {
                                        setOption(opt, jsc.pub.presets[pres][opt]);
                                    } catch (e) {
                                        console.warn(e);
                                    }
                                }
                            }
                        }


                        // let's set specific options for this color picker
                        var nonProperties = [
                            // these options won't be set as instance properties
                            'preset',
                        ];
                        for (var opt in opts) {
                            if (opts.hasOwnProperty(opt)) {
                                if (nonProperties.indexOf(opt) === -1) {
                                    try {
                                        setOption(opt, opts[opt]);
                                    } catch (e) {
                                        console.warn(e);
                                    }
                                }
                            }
                        }


                        // Getter: option(name)
                        // Setter: option(name, value)
                        //         option({name:value, ...})
                        //
                        this.option = function () {
                            if (!arguments.length) {
                                throw new Error('No option specified');
                            }

                            if (arguments.length === 1 && typeof arguments[0] === 'string') {
                                // getting a single option
                                try {
                                    return getOption(arguments[0]);
                                } catch (e) {
                                    console.warn(e);
                                }
                                return false;

                            } else if (arguments.length >= 2 && typeof arguments[0] === 'string') {
                                // setting a single option
                                try {
                                    if (!setOption(arguments[0], arguments[1])) {
                                        return false;
                                    }
                                } catch (e) {
                                    console.warn(e);
                                    return false;
                                }
                                this.redraw(); // immediately redraws the picker, if it's displayed
                                this.exposeColor(); // in case some preview-related or format-related option was changed
                                return true;

                            } else if (arguments.length === 1 && typeof arguments[0] === 'object') {
                                // setting multiple options
                                var opts = arguments[0];
                                var success = true;
                                for (var opt in opts) {
                                    if (opts.hasOwnProperty(opt)) {
                                        try {
                                            if (!setOption(opt, opts[opt])) {
                                                success = false;
                                            }
                                        } catch (e) {
                                            console.warn(e);
                                            success = false;
                                        }
                                    }
                                }
                                this.redraw(); // immediately redraws the picker, if it's displayed
                                this.exposeColor(); // in case some preview-related or format-related option was changed
                                return success;
                            }

                            throw new Error('Invalid arguments');
                        }


                        // Getter: channel(name)
                        // Setter: channel(name, value)
                        //
                        this.channel = function (name, value) {
                            if (typeof name !== 'string') {
                                throw new Error('Invalid value for channel name: ' + name);
                            }

                            if (value === undefined) {
                                // getting channel value
                                if (!this.channels.hasOwnProperty(name.toLowerCase())) {
                                    console.warn('Getting unknown channel: ' + name);
                                    return false;
                                }
                                return this.channels[name.toLowerCase()];

                            } else {
                                // setting channel value
                                var res = false;
                                switch (name.toLowerCase()) {
                                    case 'r': res = this.fromRGBA(value, null, null, null); break;
                                    case 'g': res = this.fromRGBA(null, value, null, null); break;
                                    case 'b': res = this.fromRGBA(null, null, value, null); break;
                                    case 'h': res = this.fromHSVA(value, null, null, null); break;
                                    case 's': res = this.fromHSVA(null, value, null, null); break;
                                    case 'v': res = this.fromHSVA(null, null, value, null); break;
                                    case 'a': res = this.fromHSVA(null, null, null, value); break;
                                    default:
                                        console.warn('Setting unknown channel: ' + name);
                                        return false;
                                }
                                if (res) {
                                    this.redraw(); // immediately redraws the picker, if it's displayed
                                    return true;
                                }
                            }

                            return false;
                        }


                        // Triggers given input event(s) by:
                        // - executing on<Event> callback specified as picker's option
                        // - triggering standard DOM event listeners attached to the value element
                        //
                        // It is possible to specify multiple events separated with a space.
                        //
                        this.trigger = function (eventNames) {
                            var evs = jsc.strList(eventNames);
                            for (var i = 0; i < evs.length; i += 1) {
                                var ev = evs[i].toLowerCase();

                                // trigger a callback
                                var callbackProp = null;
                                switch (ev) {
                                    case 'input': callbackProp = 'onInput'; break;
                                    case 'change': callbackProp = 'onChange'; break;
                                }
                                if (callbackProp) {
                                    jsc.triggerCallback(this, callbackProp);
                                }

                                // trigger standard DOM event listeners on the value element
                                jsc.triggerInputEvent(this.valueElement, ev, true, true);
                            }
                        };


                        // h: 0-360
                        // s: 0-100
                        // v: 0-100
                        // a: 0.0-1.0
                        //
                        this.fromHSVA = function (h, s, v, a, flags) { // null = don't change
                            if (h === undefined) { h = null; }
                            if (s === undefined) { s = null; }
                            if (v === undefined) { v = null; }
                            if (a === undefined) { a = null; }

                            if (h !== null) {
                                if (isNaN(h)) { return false; }
                                this.channels.h = Math.max(0, Math.min(360, h));
                            }
                            if (s !== null) {
                                if (isNaN(s)) { return false; }
                                this.channels.s = Math.max(0, Math.min(100, this.maxS, s), this.minS);
                            }
                            if (v !== null) {
                                if (isNaN(v)) { return false; }
                                this.channels.v = Math.max(0, Math.min(100, this.maxV, v), this.minV);
                            }
                            if (a !== null) {
                                if (isNaN(a)) { return false; }
                                this.channels.a = this.hasAlphaChannel() ?
                                    Math.max(0, Math.min(1, this.maxA, a), this.minA) :
                                    1.0; // if alpha channel is disabled, the color should stay 100% opaque
                            }

                            var rgb = jsc.HSV_RGB(
                                this.channels.h,
                                this.channels.s,
                                this.channels.v
                            );
                            this.channels.r = rgb[0];
                            this.channels.g = rgb[1];
                            this.channels.b = rgb[2];

                            this.exposeColor(flags);
                            return true;
                        };


                        // r: 0-255
                        // g: 0-255
                        // b: 0-255
                        // a: 0.0-1.0
                        //
                        this.fromRGBA = function (r, g, b, a, flags) { // null = don't change
                            if (r === undefined) { r = null; }
                            if (g === undefined) { g = null; }
                            if (b === undefined) { b = null; }
                            if (a === undefined) { a = null; }

                            if (r !== null) {
                                if (isNaN(r)) { return false; }
                                r = Math.max(0, Math.min(255, r));
                            }
                            if (g !== null) {
                                if (isNaN(g)) { return false; }
                                g = Math.max(0, Math.min(255, g));
                            }
                            if (b !== null) {
                                if (isNaN(b)) { return false; }
                                b = Math.max(0, Math.min(255, b));
                            }
                            if (a !== null) {
                                if (isNaN(a)) { return false; }
                                this.channels.a = this.hasAlphaChannel() ?
                                    Math.max(0, Math.min(1, this.maxA, a), this.minA) :
                                    1.0; // if alpha channel is disabled, the color should stay 100% opaque
                            }

                            var hsv = jsc.RGB_HSV(
                                r===null ? this.channels.r : r,
                                g===null ? this.channels.g : g,
                                b===null ? this.channels.b : b
                            );
                            if (hsv[0] !== null) {
                                this.channels.h = Math.max(0, Math.min(360, hsv[0]));
                            }
                            if (hsv[2] !== 0) { // fully black color stays black through entire saturation range, so let's not change saturation
                                this.channels.s = Math.max(0, this.minS, Math.min(100, this.maxS, hsv[1]));
                            }
                            this.channels.v = Math.max(0, this.minV, Math.min(100, this.maxV, hsv[2]));

                            // update RGB according to final HSV, as some values might be trimmed
                            var rgb = jsc.HSV_RGB(this.channels.h, this.channels.s, this.channels.v);
                            this.channels.r = rgb[0];
                            this.channels.g = rgb[1];
                            this.channels.b = rgb[2];

                            this.exposeColor(flags);
                            return true;
                        };


                        // DEPRECATED. Use .fromHSVA() instead
                        //
                        this.fromHSV = function (h, s, v, flags) {
                            console.warn('fromHSV() method is DEPRECATED. Using fromHSVA() instead.' + jsc.docsRef);
                            return this.fromHSVA(h, s, v, null, flags);
                        };


                        // DEPRECATED. Use .fromRGBA() instead
                        //
                        this.fromRGB = function (r, g, b, flags) {
                            console.warn('fromRGB() method is DEPRECATED. Using fromRGBA() instead.' + jsc.docsRef);
                            return this.fromRGBA(r, g, b, null, flags);
                        };


                        this.fromString = function (str, flags) {
                            if (!this.required && str.trim() === '') {
                                // setting empty string to an optional color input
                                this.setPreviewElementBg(null);
                                this.setValueElementValue('');
                                return true;
                            }

                            var color = jsc.parseColorString(str);
                            if (!color) {
                                return false; // could not parse
                            }
                            if (this.format.toLowerCase() === 'any') {
                                this._currentFormat = color.format; // adapt format
                                if (this.getFormat() !== 'rgba') {
                                    color.rgba[3] = 1.0; // when switching to a format that doesn't support alpha, set full opacity
                                }
                                this.redraw(); // to show/hide the alpha slider according to current format
                            }
                            this.fromRGBA(
                                color.rgba[0],
                                color.rgba[1],
                                color.rgba[2],
                                color.rgba[3],
                                flags
                            );
                            return true;
                        };


                        this.toString = function (format) {
                            if (format === undefined) {
                                format = this.getFormat(); // format not specified -> use the current format
                            }
                            switch (format.toLowerCase()) {
                                case 'hex': return this.toHEXString(); break;
                                case 'rgb': return this.toRGBString(); break;
                                case 'rgba': return this.toRGBAString(); break;
                            }
                            return false;
                        };


                        this.toHEXString = function () {
                            return '#' + (
                                ('0' + Math.round(this.channels.r).toString(16)).substr(-2) +
                                ('0' + Math.round(this.channels.g).toString(16)).substr(-2) +
                                ('0' + Math.round(this.channels.b).toString(16)).substr(-2)
                            ).toUpperCase();
                        };


                        this.toRGBString = function () {
                            return ('rgb(' +
                                Math.round(this.channels.r) + ',' +
                                Math.round(this.channels.g) + ',' +
                                Math.round(this.channels.b) +
                            ')');
                        };


                        this.toRGBAString = function () {
                            return ('rgba(' +
                                Math.round(this.channels.r) + ',' +
                                Math.round(this.channels.g) + ',' +
                                Math.round(this.channels.b) + ',' +
                                (Math.round(this.channels.a * 100) / 100) +
                            ')');
                        };


                        this.toGrayscale = function () {
                            return (
                                0.213 * this.channels.r +
                                0.715 * this.channels.g +
                                0.072 * this.channels.b
                            );
                        };


                        this.toCanvas = function () {
                            return jsc.genColorPreviewCanvas(this.toRGBAString()).canvas;
                        };


                        this.toDataURL = function () {
                            return this.toCanvas().toDataURL();
                        };


                        this.toBackground = function () {
                            return jsc.pub.background(this.toRGBAString());
                        };


                        this.isLight = function () {
                            return this.toGrayscale() > 255 / 2;
                        };


                        this.hide = function () {
                            if (isPickerOwner()) {
                                detachPicker();
                            }
                        };


                        this.show = function () {
                            drawPicker();
                        };


                        this.redraw = function () {
                            if (isPickerOwner()) {
                                drawPicker();
                            }
                        };


                        this.getFormat = function () {
                            return this._currentFormat;
                        };


                        this.hasAlphaChannel = function () {
                            if (this.alphaChannel === 'auto') {
                                return (
                                    this.format.toLowerCase() === 'any' || // format can change on the fly (e.g. from hex to rgba), so let's consider the alpha channel enabled
                                    this.getFormat() === 'rgba' || // the current format supports alpha channel
                                    this.alpha !== undefined || // initial alpha value is set, so we're working with alpha channel
                                    this.alphaElement !== undefined // the alpha value is redirected, so we're working with alpha channel
                                );
                            }

                            return this.alphaChannel; // the alpha channel is explicitly set
                        };


                        this.processValueInput = function (str) {
                            if (!this.fromString(str)) {
                                // could not parse the color value - let's just expose the current color
                                this.exposeColor();
                            }
                        };


                        this.processAlphaInput = function (str) {
                            if (!this.fromHSVA(null, null, null, parseFloat(str))) {
                                // could not parse the alpha value - let's just expose the current color
                                this.exposeColor();
                            }
                        };


                        this.exposeColor = function (flags) {

                            if (!(flags & jsc.flags.leaveValue) && this.valueElement) {
                                var value = this.toString();

                                if (this.getFormat() === 'hex') {
                                    if (!this.uppercase) { value = value.toLowerCase(); }
                                    if (!this.hash) { value = value.replace(/^#/, ''); }
                                }

                                this.setValueElementValue(value);
                            }

                            if (!(flags & jsc.flags.leaveAlpha) && this.alphaElement) {
                                var value = Math.round(this.channels.a * 100) / 100;
                                this.setAlphaElementValue(value);
                            }

                            if (!(flags & jsc.flags.leavePreview) && this.previewElement) {
                                var previewPos = null; // 'left' | 'right' (null -> fill the entire element)

                                if (
                                    jsc.isTextInput(this.previewElement) || // text input
                                    (jsc.isButton(this.previewElement) && !jsc.isButtonEmpty(this.previewElement)) // button with text
                                ) {
                                    previewPos = this.previewPosition;
                                }

                                this.setPreviewElementBg(this.toRGBAString());
                            }

                            if (isPickerOwner()) {
                                redrawPad();
                                redrawSld();
                                redrawASld();
                            }
                        };


                        this.setPreviewElementBg = function (color) {
                            if (!this.previewElement) {
                                return;
                            }

                            var position = null; // color preview position:  null | 'left' | 'right'
                            var width = null; // color preview width:  px | null = fill the entire element
                            if (
                                jsc.isTextInput(this.previewElement) || // text input
                                (jsc.isButton(this.previewElement) && !jsc.isButtonEmpty(this.previewElement)) // button with text
                            ) {
                                position = this.previewPosition;
                                width = this.previewSize;
                            }

                            var backgrounds = [];

                            if (!color) {
                                // there is no color preview to display -> let's remove any previous background image
                                backgrounds.push({
                                    image: 'none',
                                    position: 'left top',
                                    size: 'auto',
                                    repeat: 'no-repeat',
                                    origin: 'padding-box',
                                });
                            } else {
                                // CSS gradient for background color preview
                                backgrounds.push({
                                    image: jsc.genColorPreviewGradient(
                                        color,
                                        position,
                                        width ? width - jsc.pub.previewSeparator.length : null
                                    ),
                                    position: 'left top',
                                    size: 'auto',
                                    repeat: position ? 'repeat-y' : 'repeat',
                                    origin: 'padding-box',
                                });

                                // data URL of generated PNG image with a gray transparency chessboard
                                var preview = jsc.genColorPreviewCanvas(
                                    'rgba(0,0,0,0)',
                                    position ? {'left':'right', 'right':'left'}[position] : null,
                                    width,
                                    true
                                );
                                backgrounds.push({
                                    image: 'url(\'' + preview.canvas.toDataURL() + '\')',
                                    position: (position || 'left') + ' top',
                                    size: preview.width + 'px ' + preview.height + 'px',
                                    repeat: position ? 'repeat-y' : 'repeat',
                                    origin: 'padding-box',
                                });
                            }

                            var bg = {
                                image: [],
                                position: [],
                                size: [],
                                repeat: [],
                                origin: [],
                            };
                            for (var i = 0; i < backgrounds.length; i += 1) {
                                bg.image.push(backgrounds[i].image);
                                bg.position.push(backgrounds[i].position);
                                bg.size.push(backgrounds[i].size);
                                bg.repeat.push(backgrounds[i].repeat);
                                bg.origin.push(backgrounds[i].origin);
                            }

                            // set previewElement's background-images
                            var sty = {
                                'background-image': bg.image.join(', '),
                                'background-position': bg.position.join(', '),
                                'background-size': bg.size.join(', '),
                                'background-repeat': bg.repeat.join(', '),
                                'background-origin': bg.origin.join(', '),
                            };
                            jsc.setStyle(this.previewElement, sty, this.forceStyle);


                            // set/restore previewElement's padding
                            var padding = {
                                left: null,
                                right: null,
                            };
                            if (position) {
                                padding[position] = (this.previewSize + this.previewPadding) + 'px';
                            }

                            var sty = {
                                'padding-left': padding.left,
                                'padding-right': padding.right,
                            };
                            jsc.setStyle(this.previewElement, sty, this.forceStyle, true);
                        };


                        this.setValueElementValue = function (str) {
                            if (this.valueElement) {
                                if (jsc.nodeName(this.valueElement) === 'input') {
                                    this.valueElement.value = str;
                                } else {
                                    this.valueElement.innerHTML = str;
                                }
                            }
                        };


                        this.setAlphaElementValue = function (str) {
                            if (this.alphaElement) {
                                if (jsc.nodeName(this.alphaElement) === 'input') {
                                    this.alphaElement.value = str;
                                } else {
                                    this.alphaElement.innerHTML = str;
                                }
                            }
                        };


                        this._processParentElementsInDOM = function () {
                            if (this._linkedElementsProcessed) { return; }
                            this._linkedElementsProcessed = true;

                            var elm = this.targetElement;
                            do {
                                // If the target element or one of its parent nodes has fixed position,
                                // then use fixed positioning instead
                                var compStyle = jsc.getCompStyle(elm);
                                if (compStyle.position && compStyle.position.toLowerCase() === 'fixed') {
                                    this.fixed = true;
                                }

                                if (elm !== this.targetElement) {
                                    // Ensure to attach onParentScroll only once to each parent element
                                    // (multiple targetElements can share the same parent nodes)
                                    //
                                    // Note: It's not just offsetParents that can be scrollable,
                                    // that's why we loop through all parent nodes
                                    if (!jsc.getData(elm, 'hasScrollListener')) {
                                        elm.addEventListener('scroll', jsc.onParentScroll, false);
                                        jsc.setData(elm, 'hasScrollListener', true);
                                    }
                                }
                            } while ((elm = elm.parentNode) && jsc.nodeName(elm) !== 'body');
                        };


                        this.tryHide = function () {
                            if (this.hideOnLeave) {
                                this.hide();
                            }
                        };


                        function setOption (option, value) {
                            if (typeof option !== 'string') {
                                throw new Error('Invalid value for option name: ' + option);
                            }

                            // enum option
                            if (jsc.enumOpts.hasOwnProperty(option)) {
                                if (typeof value === 'string') { // enum string values are case insensitive
                                    value = value.toLowerCase();
                                }
                                if (jsc.enumOpts[option].indexOf(value) === -1) {
                                    throw new Error('Option \'' + option + '\' has invalid value: ' + value);
                                }
                            }

                            // deprecated option
                            if (jsc.deprecatedOpts.hasOwnProperty(option)) {
                                var oldOpt = option;
                                var newOpt = jsc.deprecatedOpts[option];
                                if (newOpt) {
                                    // if we have a new name for this option, let's log a warning and use the new name
                                    console.warn('Option \'%s\' is DEPRECATED, using \'%s\' instead.' + jsc.docsRef, oldOpt, newOpt);
                                    option = newOpt;
                                } else {
                                    // new name not available for the option
                                    throw new Error('Option \'' + option + '\' is DEPRECATED');
                                }
                            }

                            if (!(option in THIS)) {
                                throw new Error('Unrecognized configuration option: ' + option);
                            }

                            THIS[option] = value;
                            return true;
                        }


                        function getOption (option) {
                            // deprecated option
                            if (jsc.deprecatedOpts.hasOwnProperty(option)) {
                                var oldOpt = option;
                                var newOpt = jsc.deprecatedOpts[option];
                                if (newOpt) {
                                    // if we have a new name for this option, let's log a warning and use the new name
                                    console.warn('Option \'%s\' is DEPRECATED, using \'%s\' instead.' + jsc.docsRef, oldOpt, newOpt);
                                    option = newOpt;
                                } else {
                                    // new name not available for the option
                                    throw new Error('Option \'' + option + '\' is DEPRECATED');
                                }
                            }

                            if (!(option in THIS)) {
                                throw new Error('Unrecognized configuration option: ' + option);
                            }

                            return THIS[option];
                        }


                        function detachPicker () {
                            jsc.removeClass(THIS.targetElement, jsc.pub.activeClassName);
                            jsc.picker.wrap.parentNode.removeChild(jsc.picker.wrap);
                            delete jsc.picker.owner;
                        }


                        function drawPicker () {

                            // At this point, when drawing the picker, we know what the parent elements are
                            // and we can do all related DOM operations, such as registering events on them
                            // or checking their positioning
                            THIS._processParentElementsInDOM();

                            if (!jsc.picker) {
                                jsc.picker = {
                                    owner: null, // owner picker instance
                                    wrap : jsc.createEl('div'),
                                    box : jsc.createEl('div'),
                                    boxS : jsc.createEl('div'), // shadow area
                                    boxB : jsc.createEl('div'), // border
                                    pad : jsc.createEl('div'),
                                    padB : jsc.createEl('div'), // border
                                    padM : jsc.createEl('div'), // mouse/touch area
                                    padPal : jsc.createPalette(),
                                    cross : jsc.createEl('div'),
                                    crossBY : jsc.createEl('div'), // border Y
                                    crossBX : jsc.createEl('div'), // border X
                                    crossLY : jsc.createEl('div'), // line Y
                                    crossLX : jsc.createEl('div'), // line X
                                    sld : jsc.createEl('div'), // slider
                                    sldB : jsc.createEl('div'), // border
                                    sldM : jsc.createEl('div'), // mouse/touch area
                                    sldGrad : jsc.createSliderGradient(),
                                    sldPtrS : jsc.createEl('div'), // slider pointer spacer
                                    sldPtrIB : jsc.createEl('div'), // slider pointer inner border
                                    sldPtrMB : jsc.createEl('div'), // slider pointer middle border
                                    sldPtrOB : jsc.createEl('div'), // slider pointer outer border
                                    asld : jsc.createEl('div'), // alpha slider
                                    asldB : jsc.createEl('div'), // border
                                    asldM : jsc.createEl('div'), // mouse/touch area
                                    asldGrad : jsc.createASliderGradient(),
                                    asldPtrS : jsc.createEl('div'), // slider pointer spacer
                                    asldPtrIB : jsc.createEl('div'), // slider pointer inner border
                                    asldPtrMB : jsc.createEl('div'), // slider pointer middle border
                                    asldPtrOB : jsc.createEl('div'), // slider pointer outer border
                                    btn : jsc.createEl('div'),
                                    btnT : jsc.createEl('span'), // text
                                };

                                jsc.picker.pad.appendChild(jsc.picker.padPal.elm);
                                jsc.picker.padB.appendChild(jsc.picker.pad);
                                jsc.picker.cross.appendChild(jsc.picker.crossBY);
                                jsc.picker.cross.appendChild(jsc.picker.crossBX);
                                jsc.picker.cross.appendChild(jsc.picker.crossLY);
                                jsc.picker.cross.appendChild(jsc.picker.crossLX);
                                jsc.picker.padB.appendChild(jsc.picker.cross);
                                jsc.picker.box.appendChild(jsc.picker.padB);
                                jsc.picker.box.appendChild(jsc.picker.padM);

                                jsc.picker.sld.appendChild(jsc.picker.sldGrad.elm);
                                jsc.picker.sldB.appendChild(jsc.picker.sld);
                                jsc.picker.sldB.appendChild(jsc.picker.sldPtrOB);
                                jsc.picker.sldPtrOB.appendChild(jsc.picker.sldPtrMB);
                                jsc.picker.sldPtrMB.appendChild(jsc.picker.sldPtrIB);
                                jsc.picker.sldPtrIB.appendChild(jsc.picker.sldPtrS);
                                jsc.picker.box.appendChild(jsc.picker.sldB);
                                jsc.picker.box.appendChild(jsc.picker.sldM);

                                jsc.picker.asld.appendChild(jsc.picker.asldGrad.elm);
                                jsc.picker.asldB.appendChild(jsc.picker.asld);
                                jsc.picker.asldB.appendChild(jsc.picker.asldPtrOB);
                                jsc.picker.asldPtrOB.appendChild(jsc.picker.asldPtrMB);
                                jsc.picker.asldPtrMB.appendChild(jsc.picker.asldPtrIB);
                                jsc.picker.asldPtrIB.appendChild(jsc.picker.asldPtrS);
                                jsc.picker.box.appendChild(jsc.picker.asldB);
                                jsc.picker.box.appendChild(jsc.picker.asldM);

                                jsc.picker.btn.appendChild(jsc.picker.btnT);
                                jsc.picker.box.appendChild(jsc.picker.btn);

                                jsc.picker.boxB.appendChild(jsc.picker.box);
                                jsc.picker.wrap.appendChild(jsc.picker.boxS);
                                jsc.picker.wrap.appendChild(jsc.picker.boxB);

                                jsc.picker.wrap.addEventListener('touchstart', jsc.onPickerTouchStart,
                                    jsc.isPassiveEventSupported ? {passive: false} : false);
                            }

                            var p = jsc.picker;

                            var displaySlider = !!jsc.getSliderChannel(THIS);
                            var displayAlphaSlider = THIS.hasAlphaChannel();
                            var dims = jsc.getPickerDims(THIS);
                            var crossOuterSize = (2 * THIS.pointerBorderWidth + THIS.pointerThickness + 2 * THIS.crossSize);
                            var controlPadding = jsc.getControlPadding(THIS);
                            var borderRadius = Math.min(
                                THIS.borderRadius,
                                Math.round(THIS.padding * Math.PI)); // px
                            var padCursor = 'crosshair';

                            // wrap
                            p.wrap.className = 'jscolor-picker-wrap';
                            p.wrap.style.clear = 'both';
                            p.wrap.style.width = (dims[0] + 2 * THIS.borderWidth) + 'px';
                            p.wrap.style.height = (dims[1] + 2 * THIS.borderWidth) + 'px';
                            p.wrap.style.zIndex = THIS.zIndex;

                            // picker
                            p.box.className = 'jscolor-picker';
                            p.box.style.width = dims[0] + 'px';
                            p.box.style.height = dims[1] + 'px';
                            p.box.style.position = 'relative';

                            // picker shadow
                            p.boxS.className = 'jscolor-picker-shadow';
                            p.boxS.style.position = 'absolute';
                            p.boxS.style.left = '0';
                            p.boxS.style.top = '0';
                            p.boxS.style.width = '100%';
                            p.boxS.style.height = '100%';
                            jsc.setBorderRadius(p.boxS, borderRadius + 'px');

                            // picker border
                            p.boxB.className = 'jscolor-picker-border';
                            p.boxB.style.position = 'relative';
                            p.boxB.style.border = THIS.borderWidth + 'px solid';
                            p.boxB.style.borderColor = THIS.borderColor;
                            p.boxB.style.background = THIS.backgroundColor;
                            jsc.setBorderRadius(p.boxB, borderRadius + 'px');

                            // IE hack:
                            // If the element is transparent, IE will trigger the event on the elements under it,
                            // e.g. on Canvas or on elements with border
                            p.padM.style.background = 'rgba(255,0,0,.2)';
                            p.sldM.style.background = 'rgba(0,255,0,.2)';
                            p.asldM.style.background = 'rgba(0,0,255,.2)';

                            p.padM.style.opacity =
                            p.sldM.style.opacity =
                            p.asldM.style.opacity =
                                '0';

                            // pad
                            p.pad.style.position = 'relative';
                            p.pad.style.width = THIS.width + 'px';
                            p.pad.style.height = THIS.height + 'px';

                            // pad palettes (HSV and HVS)
                            p.padPal.draw(THIS.width, THIS.height, jsc.getPadYChannel(THIS));

                            // pad border
                            p.padB.style.position = 'absolute';
                            p.padB.style.left = THIS.padding + 'px';
                            p.padB.style.top = THIS.padding + 'px';
                            p.padB.style.border = THIS.controlBorderWidth + 'px solid';
                            p.padB.style.borderColor = THIS.controlBorderColor;

                            // pad mouse area
                            p.padM.style.position = 'absolute';
                            p.padM.style.left = 0 + 'px';
                            p.padM.style.top = 0 + 'px';
                            p.padM.style.width = (THIS.padding + 2 * THIS.controlBorderWidth + THIS.width + controlPadding) + 'px';
                            p.padM.style.height = (2 * THIS.controlBorderWidth + 2 * THIS.padding + THIS.height) + 'px';
                            p.padM.style.cursor = padCursor;
                            jsc.setData(p.padM, {
                                instance: THIS,
                                control: 'pad',
                            })

                            // pad cross
                            p.cross.style.position = 'absolute';
                            p.cross.style.left =
                            p.cross.style.top =
                                '0';
                            p.cross.style.width =
                            p.cross.style.height =
                                crossOuterSize + 'px';

                            // pad cross border Y and X
                            p.crossBY.style.position =
                            p.crossBX.style.position =
                                'absolute';
                            p.crossBY.style.background =
                            p.crossBX.style.background =
                                THIS.pointerBorderColor;
                            p.crossBY.style.width =
                            p.crossBX.style.height =
                                (2 * THIS.pointerBorderWidth + THIS.pointerThickness) + 'px';
                            p.crossBY.style.height =
                            p.crossBX.style.width =
                                crossOuterSize + 'px';
                            p.crossBY.style.left =
                            p.crossBX.style.top =
                                (Math.floor(crossOuterSize / 2) - Math.floor(THIS.pointerThickness / 2) - THIS.pointerBorderWidth) + 'px';
                            p.crossBY.style.top =
                            p.crossBX.style.left =
                                '0';

                            // pad cross line Y and X
                            p.crossLY.style.position =
                            p.crossLX.style.position =
                                'absolute';
                            p.crossLY.style.background =
                            p.crossLX.style.background =
                                THIS.pointerColor;
                            p.crossLY.style.height =
                            p.crossLX.style.width =
                                (crossOuterSize - 2 * THIS.pointerBorderWidth) + 'px';
                            p.crossLY.style.width =
                            p.crossLX.style.height =
                                THIS.pointerThickness + 'px';
                            p.crossLY.style.left =
                            p.crossLX.style.top =
                                (Math.floor(crossOuterSize / 2) - Math.floor(THIS.pointerThickness / 2)) + 'px';
                            p.crossLY.style.top =
                            p.crossLX.style.left =
                                THIS.pointerBorderWidth + 'px';


                            // slider
                            p.sld.style.overflow = 'hidden';
                            p.sld.style.width = THIS.sliderSize + 'px';
                            p.sld.style.height = THIS.height + 'px';

                            // slider gradient
                            p.sldGrad.draw(THIS.sliderSize, THIS.height, '#000', '#000');

                            // slider border
                            p.sldB.style.display = displaySlider ? 'block' : 'none';
                            p.sldB.style.position = 'absolute';
                            p.sldB.style.left = (THIS.padding + THIS.width + 2 * THIS.controlBorderWidth + 2 * controlPadding) + 'px';
                            p.sldB.style.top = THIS.padding + 'px';
                            p.sldB.style.border = THIS.controlBorderWidth + 'px solid';
                            p.sldB.style.borderColor = THIS.controlBorderColor;

                            // slider mouse area
                            p.sldM.style.display = displaySlider ? 'block' : 'none';
                            p.sldM.style.position = 'absolute';
                            p.sldM.style.left = (THIS.padding + THIS.width + 2 * THIS.controlBorderWidth + controlPadding) + 'px';
                            p.sldM.style.top = 0 + 'px';
                            p.sldM.style.width = (
                                    (THIS.sliderSize + 2 * controlPadding + 2 * THIS.controlBorderWidth) +
                                    (displayAlphaSlider ? 0 : Math.max(0, THIS.padding - controlPadding)) // remaining padding to the right edge
                                ) + 'px';
                            p.sldM.style.height = (2 * THIS.controlBorderWidth + 2 * THIS.padding + THIS.height) + 'px';
                            p.sldM.style.cursor = 'default';
                            jsc.setData(p.sldM, {
                                instance: THIS,
                                control: 'sld',
                            })

                            // slider pointer inner and outer border
                            p.sldPtrIB.style.border =
                            p.sldPtrOB.style.border =
                                THIS.pointerBorderWidth + 'px solid ' + THIS.pointerBorderColor;

                            // slider pointer outer border
                            p.sldPtrOB.style.position = 'absolute';
                            p.sldPtrOB.style.left = -(2 * THIS.pointerBorderWidth + THIS.pointerThickness) + 'px';
                            p.sldPtrOB.style.top = '0';

                            // slider pointer middle border
                            p.sldPtrMB.style.border = THIS.pointerThickness + 'px solid ' + THIS.pointerColor;

                            // slider pointer spacer
                            p.sldPtrS.style.width = THIS.sliderSize + 'px';
                            p.sldPtrS.style.height = jsc.pub.sliderInnerSpace + 'px';


                            // alpha slider
                            p.asld.style.overflow = 'hidden';
                            p.asld.style.width = THIS.sliderSize + 'px';
                            p.asld.style.height = THIS.height + 'px';

                            // alpha slider gradient
                            p.asldGrad.draw(THIS.sliderSize, THIS.height, '#000');

                            // alpha slider border
                            p.asldB.style.display = displayAlphaSlider ? 'block' : 'none';
                            p.asldB.style.position = 'absolute';
                            p.asldB.style.left = (
                                    (THIS.padding + THIS.width + 2 * THIS.controlBorderWidth + controlPadding) +
                                    (displaySlider ? (THIS.sliderSize + 3 * controlPadding + 2 * THIS.controlBorderWidth) : 0)
                                ) + 'px';
                            p.asldB.style.top = THIS.padding + 'px';
                            p.asldB.style.border = THIS.controlBorderWidth + 'px solid';
                            p.asldB.style.borderColor = THIS.controlBorderColor;

                            // alpha slider mouse area
                            p.asldM.style.display = displayAlphaSlider ? 'block' : 'none';
                            p.asldM.style.position = 'absolute';
                            p.asldM.style.left = (
                                    (THIS.padding + THIS.width + 2 * THIS.controlBorderWidth + controlPadding) +
                                    (displaySlider ? (THIS.sliderSize + 2 * controlPadding + 2 * THIS.controlBorderWidth) : 0)
                                ) + 'px';
                            p.asldM.style.top = 0 + 'px';
                            p.asldM.style.width = (
                                    (THIS.sliderSize + 2 * controlPadding + 2 * THIS.controlBorderWidth) +
                                    Math.max(0, THIS.padding - controlPadding) // remaining padding to the right edge
                                ) + 'px';
                            p.asldM.style.height = (2 * THIS.controlBorderWidth + 2 * THIS.padding + THIS.height) + 'px';
                            p.asldM.style.cursor = 'default';
                            jsc.setData(p.asldM, {
                                instance: THIS,
                                control: 'asld',
                            })

                            // alpha slider pointer inner and outer border
                            p.asldPtrIB.style.border =
                            p.asldPtrOB.style.border =
                                THIS.pointerBorderWidth + 'px solid ' + THIS.pointerBorderColor;

                            // alpha slider pointer outer border
                            p.asldPtrOB.style.position = 'absolute';
                            p.asldPtrOB.style.left = -(2 * THIS.pointerBorderWidth + THIS.pointerThickness) + 'px';
                            p.asldPtrOB.style.top = '0';

                            // alpha slider pointer middle border
                            p.asldPtrMB.style.border = THIS.pointerThickness + 'px solid ' + THIS.pointerColor;

                            // alpha slider pointer spacer
                            p.asldPtrS.style.width = THIS.sliderSize + 'px';
                            p.asldPtrS.style.height = jsc.pub.sliderInnerSpace + 'px';


                            // the Close button
                            function setBtnBorder () {
                                var insetColors = THIS.controlBorderColor.split(/\s+/);
                                var outsetColor = insetColors.length < 2 ? insetColors[0] : insetColors[1] + ' ' + insetColors[0] + ' ' + insetColors[0] + ' ' + insetColors[1];
                                p.btn.style.borderColor = outsetColor;
                            }
                            var btnPadding = 15; // px
                            p.btn.className = 'jscolor-btn-close';
                            p.btn.style.display = THIS.closeButton ? 'block' : 'none';
                            p.btn.style.position = 'absolute';
                            p.btn.style.left = THIS.padding + 'px';
                            p.btn.style.bottom = THIS.padding + 'px';
                            p.btn.style.padding = '0 ' + btnPadding + 'px';
                            p.btn.style.maxWidth = (dims[0] - 2 * THIS.padding - 2 * THIS.controlBorderWidth - 2 * btnPadding) + 'px';
                            p.btn.style.overflow = 'hidden';
                            p.btn.style.height = THIS.buttonHeight + 'px';
                            p.btn.style.whiteSpace = 'nowrap';
                            p.btn.style.border = THIS.controlBorderWidth + 'px solid';
                            setBtnBorder();
                            p.btn.style.color = THIS.buttonColor;
                            p.btn.style.font = '12px sans-serif';
                            p.btn.style.textAlign = 'center';
                            p.btn.style.cursor = 'pointer';
                            p.btn.onmousedown = function () {
                                THIS.hide();
                            };
                            p.btnT.style.lineHeight = THIS.buttonHeight + 'px';
                            p.btnT.innerHTML = '';
                            p.btnT.appendChild(document.createTextNode(THIS.closeText));

                            // reposition the pointers
                            redrawPad();
                            redrawSld();
                            redrawASld();

                            // If we are changing the owner without first closing the picker,
                            // make sure to first deal with the old owner
                            if (jsc.picker.owner && jsc.picker.owner !== THIS) {
                                jsc.removeClass(jsc.picker.owner.targetElement, jsc.pub.activeClassName);
                            }

                            // Set a new picker owner
                            jsc.picker.owner = THIS;

                            // The redrawPosition() method needs picker.owner to be set, that's why we call it here,
                            // after setting the owner
                            if (THIS.container === document.body) {
                                jsc.redrawPosition();
                            } else {
                                jsc._drawPosition(THIS, 0, 0, 'relative', false);
                            }

                            if (p.wrap.parentNode !== THIS.container) {
                                THIS.container.appendChild(p.wrap);
                            }

                            jsc.addClass(THIS.targetElement, jsc.pub.activeClassName);
                        }


                        function redrawPad () {
                            // redraw the pad pointer
                            var yChannel = jsc.getPadYChannel(THIS);
                            var x = Math.round((THIS.channels.h / 360) * (THIS.width - 1));
                            var y = Math.round((1 - THIS.channels[yChannel] / 100) * (THIS.height - 1));
                            var crossOuterSize = (2 * THIS.pointerBorderWidth + THIS.pointerThickness + 2 * THIS.crossSize);
                            var ofs = -Math.floor(crossOuterSize / 2);
                            jsc.picker.cross.style.left = (x + ofs) + 'px';
                            jsc.picker.cross.style.top = (y + ofs) + 'px';

                            // redraw the slider
                            switch (jsc.getSliderChannel(THIS)) {
                            case 's':
                                var rgb1 = jsc.HSV_RGB(THIS.channels.h, 100, THIS.channels.v);
                                var rgb2 = jsc.HSV_RGB(THIS.channels.h, 0, THIS.channels.v);
                                var color1 = 'rgb(' +
                                    Math.round(rgb1[0]) + ',' +
                                    Math.round(rgb1[1]) + ',' +
                                    Math.round(rgb1[2]) + ')';
                                var color2 = 'rgb(' +
                                    Math.round(rgb2[0]) + ',' +
                                    Math.round(rgb2[1]) + ',' +
                                    Math.round(rgb2[2]) + ')';
                                jsc.picker.sldGrad.draw(THIS.sliderSize, THIS.height, color1, color2);
                                break;
                            case 'v':
                                var rgb = jsc.HSV_RGB(THIS.channels.h, THIS.channels.s, 100);
                                var color1 = 'rgb(' +
                                    Math.round(rgb[0]) + ',' +
                                    Math.round(rgb[1]) + ',' +
                                    Math.round(rgb[2]) + ')';
                                var color2 = '#000';
                                jsc.picker.sldGrad.draw(THIS.sliderSize, THIS.height, color1, color2);
                                break;
                            }

                            // redraw the alpha slider
                            jsc.picker.asldGrad.draw(THIS.sliderSize, THIS.height, THIS.toHEXString());
                        }


                        function redrawSld () {
                            var sldChannel = jsc.getSliderChannel(THIS);
                            if (sldChannel) {
                                // redraw the slider pointer
                                var y = Math.round((1 - THIS.channels[sldChannel] / 100) * (THIS.height - 1));
                                jsc.picker.sldPtrOB.style.top = (y - (2 * THIS.pointerBorderWidth + THIS.pointerThickness) - Math.floor(jsc.pub.sliderInnerSpace / 2)) + 'px';
                            }

                            // redraw the alpha slider
                            jsc.picker.asldGrad.draw(THIS.sliderSize, THIS.height, THIS.toHEXString());
                        }


                        function redrawASld () {
                            var y = Math.round((1 - THIS.channels.a) * (THIS.height - 1));
                            jsc.picker.asldPtrOB.style.top = (y - (2 * THIS.pointerBorderWidth + THIS.pointerThickness) - Math.floor(jsc.pub.sliderInnerSpace / 2)) + 'px';
                        }


                        function isPickerOwner () {
                            return jsc.picker && jsc.picker.owner === THIS;
                        }


                        function onValueKeyDown (ev) {
                            if (jsc.eventKey(ev) === 'Enter') {
                                if (THIS.valueElement) {
                                    THIS.processValueInput(THIS.valueElement.value);
                                }
                                THIS.tryHide();
                            }
                        }


                        function onAlphaKeyDown (ev) {
                            if (jsc.eventKey(ev) === 'Enter') {
                                if (THIS.alphaElement) {
                                    THIS.processAlphaInput(THIS.alphaElement.value);
                                }
                                THIS.tryHide();
                            }
                        }


                        function onValueChange (ev) {
                            if (jsc.getData(ev, 'internal')) {
                                return; // skip if the event was internally triggered by jscolor
                            }

                            var oldVal = THIS.valueElement.value;

                            THIS.processValueInput(THIS.valueElement.value); // this might change the value

                            jsc.triggerCallback(THIS, 'onChange');

                            if (THIS.valueElement.value !== oldVal) {
                                // value was additionally changed -> let's trigger the change event again, even though it was natively dispatched
                                jsc.triggerInputEvent(THIS.valueElement, 'change', true, true);
                            }
                        }


                        function onAlphaChange (ev) {
                            if (jsc.getData(ev, 'internal')) {
                                return; // skip if the event was internally triggered by jscolor
                            }

                            var oldVal = THIS.alphaElement.value;

                            THIS.processAlphaInput(THIS.alphaElement.value); // this might change the value

                            jsc.triggerCallback(THIS, 'onChange');

                            // triggering valueElement's onChange (because changing alpha changes the entire color, e.g. with rgba format)
                            jsc.triggerInputEvent(THIS.valueElement, 'change', true, true);

                            if (THIS.alphaElement.value !== oldVal) {
                                // value was additionally changed -> let's trigger the change event again, even though it was natively dispatched
                                jsc.triggerInputEvent(THIS.alphaElement, 'change', true, true);
                            }
                        }


                        function onValueInput (ev) {
                            if (jsc.getData(ev, 'internal')) {
                                return; // skip if the event was internally triggered by jscolor
                            }

                            if (THIS.valueElement) {
                                THIS.fromString(THIS.valueElement.value, jsc.flags.leaveValue);
                            }

                            jsc.triggerCallback(THIS, 'onInput');

                            // triggering valueElement's onInput
                            // (not needed, it was dispatched normally by the browser)
                        }


                        function onAlphaInput (ev) {
                            if (jsc.getData(ev, 'internal')) {
                                return; // skip if the event was internally triggered by jscolor
                            }

                            if (THIS.alphaElement) {
                                THIS.fromHSVA(null, null, null, parseFloat(THIS.alphaElement.value), jsc.flags.leaveAlpha);
                            }

                            jsc.triggerCallback(THIS, 'onInput');

                            // triggering valueElement's onInput (because changing alpha changes the entire color, e.g. with rgba format)
                            jsc.triggerInputEvent(THIS.valueElement, 'input', true, true);
                        }


                        //
                        // Install the color picker on chosen element(s)
                        //


                        // Determine picker's container element
                        if (this.container === undefined) {
                            this.container = document.body; // default container is BODY element

                        } else { // explicitly set to custom element
                            this.container = jsc.node(this.container);
                        }

                        if (!this.container) {
                            throw new Error('Cannot instantiate color picker without a container element');
                        }


                        // Fetch the target element
                        this.targetElement = jsc.node(targetElement);

                        if (!this.targetElement) {
                            // temporarily customized error message to help with migrating from versions prior to 2.2
                            if (typeof targetElement === 'string' && /^[a-zA-Z][\w:.-]*$/.test(targetElement)) {
                                // targetElement looks like valid ID
                                var possiblyId = targetElement;
                                throw new Error('If \'' + possiblyId + '\' is supposed to be an ID, please use \'#' + possiblyId + '\' or any valid CSS selector.');
                            }

                            throw new Error('Cannot instantiate color picker without a target element');
                        }

                        if (this.targetElement.jscolor && this.targetElement.jscolor instanceof jsc.pub) {
                            throw new Error('Color picker already installed on this element');
                        }


                        // link this instance with the target element
                        this.targetElement.jscolor = this;
                        jsc.addClass(this.targetElement, jsc.pub.className);

                        // register this instance
                        jsc.instances.push(this);


                        // if target is BUTTON
                        if (jsc.isButton(this.targetElement)) {

                            if (this.targetElement.type.toLowerCase() !== 'button') {
                                // on buttons, always force type to be 'button', e.g. in situations the target <button> has no type
                                // and thus defaults to 'submit' and would submit the form when clicked
                                this.targetElement.type = 'button';
                            }

                            if (jsc.isButtonEmpty(this.targetElement)) { // empty button
                                // it is important to clear element's contents first.
                                // if we're re-instantiating color pickers on DOM that has been modified by changing page's innerHTML,
                                // we would keep adding more non-breaking spaces to element's content (because element's contents survive
                                // innerHTML changes, but picker instances don't)
                                jsc.removeChildren(this.targetElement);

                                // let's insert a non-breaking space
                                this.targetElement.appendChild(document.createTextNode('\xa0'));

                                // set min-width = previewSize, if not already greater
                                var compStyle = jsc.getCompStyle(this.targetElement);
                                var currMinWidth = parseFloat(compStyle['min-width']) || 0;
                                if (currMinWidth < this.previewSize) {
                                    jsc.setStyle(this.targetElement, {
                                        'min-width': this.previewSize + 'px',
                                    }, this.forceStyle);
                                }
                            }
                        }

                        // Determine the value element
                        if (this.valueElement === undefined) {
                            if (jsc.isTextInput(this.targetElement)) {
                                // for text inputs, default valueElement is targetElement
                                this.valueElement = this.targetElement;
                            } else {
                                // leave it undefined
                            }

                        } else if (this.valueElement === null) { // explicitly set to null
                            // leave it null

                        } else { // explicitly set to custom element
                            this.valueElement = jsc.node(this.valueElement);
                        }

                        // Determine the alpha element
                        if (this.alphaElement) {
                            this.alphaElement = jsc.node(this.alphaElement);
                        }

                        // Determine the preview element
                        if (this.previewElement === undefined) {
                            this.previewElement = this.targetElement; // default previewElement is targetElement

                        } else if (this.previewElement === null) { // explicitly set to null
                            // leave it null

                        } else { // explicitly set to custom element
                            this.previewElement = jsc.node(this.previewElement);
                        }

                        // valueElement
                        if (this.valueElement && jsc.isTextInput(this.valueElement)) {

                            // If the value element has onInput event already set, we need to detach it and attach AFTER our listener.
                            // otherwise the picker instance would still contain the old color when accessed from the onInput handler.
                            var valueElementOrigEvents = {
                                onInput: this.valueElement.oninput
                            };
                            this.valueElement.oninput = null;

                            this.valueElement.addEventListener('keydown', onValueKeyDown, false);
                            this.valueElement.addEventListener('change', onValueChange, false);
                            this.valueElement.addEventListener('input', onValueInput, false);
                            // the original event listener must be attached AFTER our handler (to let it first set picker's color)
                            if (valueElementOrigEvents.onInput) {
                                this.valueElement.addEventListener('input', valueElementOrigEvents.onInput, false);
                            }

                            this.valueElement.setAttribute('autocomplete', 'off');
                            this.valueElement.setAttribute('autocorrect', 'off');
                            this.valueElement.setAttribute('autocapitalize', 'off');
                            this.valueElement.setAttribute('spellcheck', false);
                        }

                        // alphaElement
                        if (this.alphaElement && jsc.isTextInput(this.alphaElement)) {
                            this.alphaElement.addEventListener('keydown', onAlphaKeyDown, false);
                            this.alphaElement.addEventListener('change', onAlphaChange, false);
                            this.alphaElement.addEventListener('input', onAlphaInput, false);

                            this.alphaElement.setAttribute('autocomplete', 'off');
                            this.alphaElement.setAttribute('autocorrect', 'off');
                            this.alphaElement.setAttribute('autocapitalize', 'off');
                            this.alphaElement.setAttribute('spellcheck', false);
                        }

                        // determine initial color value
                        //
                        var initValue = 'FFFFFF';

                        if (this.value !== undefined) {
                            initValue = this.value; // get initial color from the 'value' property
                        } else if (this.valueElement && this.valueElement.value !== undefined) {
                            initValue = this.valueElement.value; // get initial color from valueElement's value
                        }

                        // determine initial alpha value
                        //
                        var initAlpha = undefined;

                        if (this.alpha !== undefined) {
                            initAlpha = (''+this.alpha); // get initial alpha value from the 'alpha' property
                        } else if (this.alphaElement && this.alphaElement.value !== undefined) {
                            initAlpha = this.alphaElement.value; // get initial color from alphaElement's value
                        }

                        // determine current format based on the initial color value
                        //
                        this._currentFormat = null;

                        if (['auto', 'any'].indexOf(this.format.toLowerCase()) > -1) {
                            // format is 'auto' or 'any' -> let's auto-detect current format
                            var color = jsc.parseColorString(initValue);
                            this._currentFormat = color ? color.format : 'hex';
                        } else {
                            // format is specified
                            this._currentFormat = this.format.toLowerCase();
                        }


                        // let's parse the initial color value and expose color's preview
                        this.processValueInput(initValue);

                        // let's also parse and expose the initial alpha value, if any
                        //
                        // Note: If the initial color value contains alpha value in it (e.g. in rgba format),
                        // this will overwrite it. So we should only process alpha input if there was any initial
                        // alpha explicitly set, otherwise we could needlessly lose initial value's alpha
                        if (initAlpha !== undefined) {
                            this.processAlphaInput(initAlpha);
                        }

                    }

                };


                //================================
                // Public properties and methods
                //================================

                //
                // These will be publicly available via jscolor.<name> and JSColor.<name>
                //


                // class that will be set to elements having jscolor installed on them
                jsc.pub.className = 'jscolor';


                // class that will be set to elements having jscolor active on them
                jsc.pub.activeClassName = 'jscolor-active';


                // whether to try to parse the options string by evaluating it using 'new Function()'
                // in case it could not be parsed with JSON.parse()
                jsc.pub.looseJSON = true;


                // presets
                jsc.pub.presets = {};

                // built-in presets
                jsc.pub.presets['default'] = {}; // baseline for customization

                jsc.pub.presets['light'] = { // default color scheme
                    backgroundColor: 'rgba(255,255,255,1)',
                    controlBorderColor: 'rgba(187,187,187,1)',
                    buttonColor: 'rgba(0,0,0,1)',
                };
                jsc.pub.presets['dark'] = {
                    backgroundColor: 'rgba(51,51,51,1)',
                    controlBorderColor: 'rgba(153,153,153,1)',
                    buttonColor: 'rgba(240,240,240,1)',
                };

                jsc.pub.presets['small'] = { width:101, height:101, padding:10, sliderSize:14 };
                jsc.pub.presets['medium'] = { width:181, height:101, padding:12, sliderSize:16 }; // default size
                jsc.pub.presets['large'] = { width:271, height:151, padding:12, sliderSize:24 };

                jsc.pub.presets['thin'] = { borderWidth:1, controlBorderWidth:1, pointerBorderWidth:1 }; // default thickness
                jsc.pub.presets['thick'] = { borderWidth:2, controlBorderWidth:2, pointerBorderWidth:2 };


                // size of space in the sliders
                jsc.pub.sliderInnerSpace = 3; // px

                // transparency chessboard
                jsc.pub.chessboardSize = 8; // px
                jsc.pub.chessboardColor1 = '#666666';
                jsc.pub.chessboardColor2 = '#999999';

                // preview separator
                jsc.pub.previewSeparator = ['rgba(255,255,255,.65)', 'rgba(128,128,128,.65)'];


                // Installs jscolor on current DOM tree
                jsc.pub.install = function (rootNode) {
                    var success = true;

                    try {
                        jsc.installBySelector('[data-jscolor]', rootNode);
                    } catch (e) {
                        success = false;
                        console.warn(e);
                    }

                    // for backward compatibility with DEPRECATED installation using class name
                    if (jsc.pub.lookupClass) {
                        try {
                            jsc.installBySelector(
                                (
                                    'input.' + jsc.pub.lookupClass + ', ' +
                                    'button.' + jsc.pub.lookupClass
                                ),
                                rootNode
                            );
                        } catch (e) {}
                    }

                    return success;
                };


                // Triggers given input event(s) (e.g. 'input' or 'change') on all color pickers.
                //
                // It is possible to specify multiple events separated with a space.
                // If called before jscolor is initialized, then the events will be triggered after initialization.
                //
                jsc.pub.trigger = function (eventNames) {
                    if (jsc.initialized) {
                        jsc.triggerGlobal(eventNames);
                    } else {
                        jsc.triggerQueue.push(eventNames);
                    }
                };


                // Hides current color picker box
                jsc.pub.hide = function () {
                    if (jsc.picker && jsc.picker.owner) {
                        jsc.picker.owner.hide();
                    }
                };


                // Returns a data URL of a gray chessboard image that indicates transparency
                jsc.pub.chessboard = function (color) {
                    if (!color) {
                        color = 'rgba(0,0,0,0)';
                    }
                    var preview = jsc.genColorPreviewCanvas(color);
                    return preview.canvas.toDataURL();
                };


                // Returns a data URL of a gray chessboard image that indicates transparency
                jsc.pub.background = function (color) {
                    var backgrounds = [];

                    // CSS gradient for background color preview
                    backgrounds.push(jsc.genColorPreviewGradient(color));

                    // data URL of generated PNG image with a gray transparency chessboard
                    var preview = jsc.genColorPreviewCanvas();
                    backgrounds.push([
                        'url(\'' + preview.canvas.toDataURL() + '\')',
                        'left top',
                        'repeat',
                    ].join(' '));

                    return backgrounds.join(', ');
                };


                //
                // DEPRECATED properties and methods
                //


                // DEPRECATED. Use jscolor.presets.default instead.
                //
                // Custom default options for all color pickers, e.g. { hash: true, width: 300 }
                jsc.pub.options = {};


                // DEPRECATED. Use data-jscolor attribute instead, which installs jscolor on given element.
                //
                // By default, we'll search for all elements with class="jscolor" and install a color picker on them.
                //
                // You can change what class name will be looked for by setting the property jscolor.lookupClass
                // anywhere in your HTML document. To completely disable the automatic lookup, set it to null.
                //
                jsc.pub.lookupClass = 'jscolor';


                // DEPRECATED. Use jscolor.install() instead
                //
                jsc.pub.init = function () {
                    console.warn('jscolor.init() is DEPRECATED. Using jscolor.install() instead.' + jsc.docsRef);
                    return jsc.pub.install();
                };


                // DEPRECATED. Use data-jscolor attribute instead, which installs jscolor on given element.
                //
                // Install jscolor on all elements that have the specified class name
                jsc.pub.installByClassName = function () {
                    console.error('jscolor.installByClassName() is DEPRECATED. Use data-jscolor="" attribute instead of a class name.' + jsc.docsRef);
                    return false;
                };


                jsc.register();


                return jsc.pub;


                })(); // END window.jscolor

                window.JSColor = window.jscolor; // 'JSColor' is an alias to 'jscolor'

                } // endif


            </script>


        <!-- Javascript Table Header Color Ends -->

        <script src="assets/js/jscolor.js"></script>

    </body>
</html>
