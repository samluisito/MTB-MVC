// ------------step-wizard-------------
$(document).ready(function () {
   $('.nav-tabs > li a[title]').tooltip();

   /*Wizard*/
   $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target);
      if (target.parent().hasClass('disabled')) {
         return false;
      }
   });

//   $(".next-step").click(function (e) {
//      var active = $('.wizard .nav-tabs li.active');
//      active.next().removeClass('disabled');
//      nextTab(active);
//   });

   $(".prev-step").click(function (e) {
      var active = $('.wizard .nav-tabs li.active');
      prevTab(active);
   });
});

function nextTab(elem) {
   $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
   $(elem).prev().find('a[data-toggle="tab"]').click();
}


$('.nav-tabs').on('click', 'li', function () {
   $('.nav-tabs li.active').removeClass('active');
   $(this).addClass('active');
});


/*bootstrap*/
//(function (global, factory) {
//   typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, require('jquery'), require('popper.js')) :
//           typeof define === 'function' && define.amd ? define(['exports', 'jquery', 'popper.js'], factory) :
//           (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.bootstrap = {}, global.jQuery, global.Popper));
//})(this, (function (exports, $, Popper) {
//   'use strict';
//   
//   
//     /* Constants   */
//   var SELECTOR_NAV_ITEMS = '.nav-item';
//
//
//
//  /* Class definition   */
//   var ScrollSpy = /*#__PURE__*/function () {
//
//      function ScrollSpy(element, config) {
//         var _this = this;
//
//         this._element = element;
//         this._scrollElement = element.tagName === 'BODY' ? window : element;
//         this._config = this._getConfig(config);
//         this._selector = this._config.target + " " + SELECTOR_NAV_LINKS + "," + (this._config.target + " " + SELECTOR_LIST_ITEMS + ",") + (this._config.target + " " + SELECTOR_DROPDOWN_ITEMS);
//         this._offsets = [];
//         this._targets = [];
//         this._activeTarget = null;
//         this._scrollHeight = 0;
//         $__default["default"](this._scrollElement).on(EVENT_SCROLL, function (event) {
//            return _this._process(event);
//         });
//         this.refresh();
//
//         this._process();
//      } // Getters
//
//      var _proto = ScrollSpy.prototype;
//
//
//      _proto._activate = function _activate(target) {
//         this._activeTarget = target;
//
//         this._clear();
//
//         var queries = this._selector.split(',').map(function (selector) {
//            return selector + "[data-target=\"" + target + "\"]," + selector + "[href=\"" + target + "\"]";
//         });
//
//         var $link = $__default["default"]([].slice.call(document.querySelectorAll(queries.join(','))));
//
//         if ($link.hasClass(CLASS_NAME_DROPDOWN_ITEM)) {
//            $link.closest(SELECTOR_DROPDOWN$1).find(SELECTOR_DROPDOWN_TOGGLE$1).addClass(CLASS_NAME_ACTIVE$1);
//            $link.addClass(CLASS_NAME_ACTIVE$1);
//         } else {
//            // Set triggered link as active
//            $link.addClass(CLASS_NAME_ACTIVE$1); // Set triggered links parents as active
//            // With both <ul> and <nav> markup a parent is the previous sibling of any nav ancestor
//
//            $link.parents(SELECTOR_NAV_LIST_GROUP$1).prev(SELECTOR_NAV_LINKS + ", " + SELECTOR_LIST_ITEMS).addClass(CLASS_NAME_ACTIVE$1); // Handle special case when .nav-link is inside .nav-item
//
//            $link.parents(SELECTOR_NAV_LIST_GROUP$1).prev(SELECTOR_NAV_ITEMS).children(SELECTOR_NAV_LINKS).addClass(CLASS_NAME_ACTIVE$1);
//         }
//
//         $__default["default"](this._scrollElement).trigger(EVENT_ACTIVATE, {
//            relatedTarget: target
//         });
//      };
//
//   }();
//
//}));