!function a(r,u,i){function c(e,t){if(!u[e]){if(!r[e]){var s="function"==typeof require&&require;if(!t&&s)return s(e,!0);if(l)return l(e,!0);var o=new Error("Cannot find module '"+e+"'");throw o.code="MODULE_NOT_FOUND",o}var n=u[e]={exports:{}};r[e][0].call(n.exports,function(t){return c(r[e][1][t]||t)},n,n.exports,a,r,u,i)}return u[e].exports}for(var l="function"==typeof require&&require,t=0;t<i.length;t++)c(i[t]);return c}({1:[function(n,t,a){!function(){"use strict";Object.defineProperty(a,"__esModule",{value:!0});var t,e=n(2),s=(t=e)&&t.__esModule?t:{default:t},o=n(3);a.default={components:{WebhooksStatus:s.default},props:{initialStatus:Object,label:String,name:String,hook:{type:Object,required:!0},endpoint:{type:String,required:!0},contentUpdated:Number,hookUpdated:Number,labels:{type:Object,required:!0}},data:function(){return{status:this.contentUpdated>this.hookUpdated?"outdated":this.initialStatus,timer:null,hookUpdatedLive:this.hookUpdated}},computed:{statusCta:function(){return this.labels[this.status]?this.labels[this.status].cta:"Run now"},ctaDisabled:function(){return["hooksEmpty","hookNotfound","hookNoUrl"].includes(this.status)}},methods:{triggerHook:function(){var t=this;this.setStatus("progress");var e=this.hook.url;(0,o.request)(e,this.hook.method,function(){return console.info("Webhook triggered")},function(){console.info("Could not reach webhook URL"),t.setStatus("error")})},getStatus:function(){var s=this,t="/"+this.endpoint+"/"+this.hook.name+"/status";(0,o.request)(t,"GET",function(t){var e=JSON.parse(t.response);e&&e.status!==s.status&&(s.status=e.status,s.updateTime())},function(){return console.info("There was an error with checking the status :(")})},setStatus:function(t){this.status=t,this.updateTime();var e="/"+this.endpoint+"/"+this.hook.name+"/"+t;(0,o.request)(e,"UPDATE",function(){return console.info("Webhook status successfully updated")},function(){return console.info("There was an error with updating the status :(")})},updateTime:function(){"success"!==this.status&&(this.hookUpdatedLive=Date.now()/1e3)}},watch:{status:{immediate:!0,handler:function(t){"progress"===t?this.timer=setInterval(this.getStatus,1e3):clearInterval(this.timer)}}}}}(),t.exports.__esModule&&(t.exports=t.exports.default);var e="function"==typeof t.exports?t.exports.options:t.exports;e.render=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("k-field",{staticClass:"pju-webhooks",attrs:{label:t.label}},[s("WebhooksStatus",{attrs:{status:t.status,hookUpdated:t.hookUpdatedLive,hookName:t.hook.name,labels:t.labels}}),t._v(" "),s("k-button",{staticClass:"pju-webhooks--btn",attrs:{icon:"upload",type:"submit",disabled:t.ctaDisabled},on:{click:t.triggerHook}},[t._v("\n    "+t._s(t.statusCta)+"\n  ")])],1)},e.staticRenderFns=[]},{2:2,3:3}],2:[function(t,e,s){!function(){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};s.default={props:{status:String,hookName:String,hookUpdated:Number,labels:Object},computed:{icon:function(){var t=void 0;switch(this.status){case"success":t="check";break;case"progress":t="loader";break;case"error":t="cancel";break;case"new":t="bolt";break;case"outdated":t="alert";break;default:t="cancel"}return t},statusName:function(){return this.labels[this.status]?this.labels[this.status].name:this.status},statusText:function(){return this.labels[this.status]?this.labels[this.status].text.replace("%hookName%",this.hookName):""},statusClass:function(){return"status-"+this.status},iconClass:function(){return"icon-"+this.icon},updatedText:function(){var t=new Date(1e3*this.hookUpdated);return"undefined"!=typeof window&&window.Intl&&"object"===e(window.Intl)?new Intl.DateTimeFormat(void 0,{year:"numeric",month:"numeric",day:"numeric",hour:"numeric",minute:"numeric"}).format(t):""}}}}(),e.exports.__esModule&&(e.exports=e.exports.default);var o="function"==typeof e.exports?e.exports.options:e.exports;o.render=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("k-box",{staticClass:"pju-webhooks--status",class:[t.statusClass,t.iconClass]},[s("span",{staticClass:"visuallyhidden"},[t._v("Status")]),t._v(" "),s("k-icon",{staticClass:"pju-webhooks--status--icon",attrs:{type:t.icon}}),t._v(" "),s("div",{staticClass:"pju-webhooks--status--label"},[s("p",{staticClass:"pju-webhooks--status--name"},[t._v("\n      "+t._s(t.statusName)+"\n    ")]),t._v(" "),s("p",{staticClass:"pju-webhooks--status--description"},[s("small",[t._v("\n        "+t._s(t.statusText)+"\n      ")])]),t._v(" "),s("p",{staticClass:"pju-webhooks--status--description"},[s("small",[t._v("\n        "+t._s(t.updatedText)+"\n      ")])])])],1)},o.staticRenderFns=[]},{}],3:[function(t,e,s){"use strict";Object.defineProperty(s,"__esModule",{value:!0}),s.request=function(t,e,s,o){var n=new XMLHttpRequest;n.open(e,t,!0),n.onreadystatechange=function(){200==n.status&&4==n.readyState?s&&s(n):200!==n.status&&o&&o(n)},n.send()}},{}],4:[function(t,e,s){"use strict";var o,n=t(1),a=(o=n)&&o.__esModule?o:{default:o};panel.plugin("pju/webhooks",{fields:{webhooks:a.default}})},{1:1}]},{},[4]);
