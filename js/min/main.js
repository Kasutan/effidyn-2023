if(!function(r){r(document).ready(function(){r(window).width();var i=r(".volet-navigation .ouvrir-sous-menu"),e=(0<i.length&&i.click(function(e){var s=r(this).siblings(".sub-menu");r(this).hasClass("js-ouvert")?(r(this).removeClass("js-ouvert"),r(s).slideUp()):(i.removeClass("js-ouvert"),r(".volet-navigation .sub-menu").slideUp(),r(this).addClass("js-ouvert"),r(s).slideDown())}),r(".volet-navigation .menu-item-has-children > a")),e=(0<e.length&&e.click(function(e){e.preventDefault();var e=r(this).siblings(".sub-menu"),s=r(this).siblings(".picto");r(s).hasClass("js-ouvert")?(r(s).removeClass("js-ouvert"),r(e).slideUp()):(r(".volet-navigation .ouvrir-sous-menu").removeClass("js-ouvert"),r(".volet-navigation .sub-menu").slideUp(),r(s).addClass("js-ouvert"),r(e).slideDown())}),r(".site-header")),s=r(".site-main"),s=(r(e).addClass("js-sticky"),s.css("margin-top",e.outerHeight()),r(".forminator-col.pleine-largeur")),e=(0<s.length&&r(s).each(function(e){r(this).parent(".forminator-row").addClass("pleine-largeur")}),r("input[aria-describedby]"));0<e.length&&r(e).each(function(){var e=r(this).attr("aria-describedby");0===r("#"+e).length&&r(this).removeAttr("aria-describedby")})})}(jQuery),"IntersectionObserver"in window){const p={},q=(observer=new IntersectionObserver(e=>{e.forEach(e=>{0<e.intersectionRatio?(e.target.classList.add("fade-in"),observer.unobserve(e.target)):e.target.classList.remove("fade-in")},p)}),document.querySelectorAll(".js-fade-in-on-visible")),r=(q.forEach(e=>{observer.observe(e)}),observer2=new IntersectionObserver(e=>{e.forEach(e=>{0<e.intersectionRatio&&(jQuery(e.target).children().addClass("cascade"),observer.unobserve(e.target))},p)}),document.querySelectorAll(".js-cascade-on-visible"));r.forEach(e=>{observer2.observe(e)})}else jQuery(".js-animate-on-visible-cascade").addClass("cascade"),jQuery(".js-animate-on-visible").addClass("fade-in");