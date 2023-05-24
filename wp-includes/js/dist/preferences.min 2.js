/*! This file is auto-generated */
!function(){"use strict";var e={d:function(t,n){for(var r in n)e.o(n,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:n[r]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r:function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{PreferenceToggleMenuItem:function(){return m},store:function(){return g}});var n={};e.r(n),e.d(n,{set:function(){return p},setDefaults:function(){return w},toggle:function(){return f}});var r={};e.r(r),e.d(r,{get:function(){return v}});var o=window.wp.element,c=window.wp.data,i=window.wp.components,s=window.wp.i18n,u=window.wp.primitives;var a=(0,o.createElement)(u.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,o.createElement)(u.Path,{d:"M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"})),l=window.wp.a11y;var d=(0,c.combineReducers)({defaults:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1?arguments[1]:void 0;if("SET_PREFERENCE_DEFAULTS"===t.type){const{scope:n,defaults:r}=t;return{...e,[n]:{...e[n],...r}}}return e},preferences:function(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1?arguments[1]:void 0;if("SET_PREFERENCE_VALUE"===t.type){const{scope:n,name:r,value:o}=t;return{...e,[n]:{...e[n],[r]:o}}}return e}});function f(e,t){return function(n){let{select:r,dispatch:o}=n;const c=r.get(e,t);o.set(e,t,!c)}}function p(e,t,n){return{type:"SET_PREFERENCE_VALUE",scope:e,name:t,value:n}}function w(e,t){return{type:"SET_PREFERENCE_DEFAULTS",scope:e,defaults:t}}function v(e,t,n){var r,o;const c=null===(r=e.preferences[t])||void 0===r?void 0:r[n];return void 0!==c?c:null===(o=e.defaults[t])||void 0===o?void 0:o[n]}const E="core/preferences",g=(0,c.createReduxStore)(E,{reducer:d,actions:n,selectors:r,persist:["preferences"]});function m(e){let{scope:t,name:n,label:r,info:u,messageActivated:d,messageDeactivated:f,shortcut:p}=e;const w=(0,c.useSelect)((e=>!!e(g).get(t,n)),[n]),{toggle:v}=(0,c.useDispatch)(g);return(0,o.createElement)(i.MenuItem,{icon:w&&a,isSelected:w,onClick:()=>{v(t,n),(()=>{if(w){const e=f||(0,s.sprintf)((0,s.__)("Preference deactivated - %s"),r);(0,l.speak)(e)}else{const e=d||(0,s.sprintf)((0,s.__)("Preference activated - %s"),r);(0,l.speak)(e)}})()},role:"menuitemcheckbox",info:u,shortcut:p},r)}(0,c.registerStore)(E,{reducer:d,actions:n,selectors:r,persist:["preferences"]}),(window.wp=window.wp||{}).preferences=t}();