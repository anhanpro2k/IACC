!function(){function e(e){if(!(0 in arguments))throw new TypeError("1 argument is required");do{if(this===e)return!0}while(e=e&&e.parentNode);return!1}if("HTMLElement"in self&&"contains"in HTMLElement.prototype)try{delete HTMLElement.prototype.contains}catch(e){}"Node"in self?Node.prototype.contains=e:document.contains=Element.prototype.contains=e}();