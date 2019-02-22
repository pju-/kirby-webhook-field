!function n(r,u,i){function c(s,t){if(!u[s]){if(!r[s]){var e="function"==typeof require&&require;if(!t&&e)return e(s,!0);if(l)return l(s,!0);var a=new Error("Cannot find module '"+s+"'");throw a.code="MODULE_NOT_FOUND",a}var o=u[s]={exports:{}};r[s][0].call(o.exports,function(t){return c(r[s][1][t]||t)},o,o.exports,n,r,u,i)}return u[s].exports}for(var l="function"==typeof require&&require,t=0;t<i.length;t++)c(i[t]);return c}({1:[function(o,t,n){!function(){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var t,s=o(2),e=(t=s)&&t.__esModule?t:{default:t},a=o(3);n.default={components:{WebhooksStatus:e.default},props:{initialStatus:Object,label:String,name:String,hook:Object,deployed:String,changed:String,labels:Object},data:function(){return{status:this.initialStatus.status,timer:null}},computed:{statusCta:function(){return this.labels[this.status]?this.labels[this.status].cta:"Run now"},ctaDisabled:function(){return["hooksEmpty","hookNotfound","hookNoUrl"].includes(this.status)}},methods:{triggerHook:function(){var t=this;this.setStatus("progress");var s=this.hook.url;(0,a.request)(s,"POST",function(){return console.info("Webhook triggered")},function(){console.info("Could not reach webhook URL"),t.setStatus("error")})},getStatus:function(){var e=this,t="/webhooks/"+this.hook.name+"/status";(0,a.request)(t,"GET",function(t){var s=JSON.parse(t.response);s&&s.status!==e.status&&(e.status=s.status)},function(){return console.info("There was an error with checking the status :(")})},setStatus:function(t){this.status=t;var s="/webhooks/"+this.hook.name+"/"+t;(0,a.request)(s,"GET",function(){return console.info("Webhook status successfully updated")},function(){return console.info("There was an error with updating the status :(")})}},watch:{status:{immediate:!0,handler:function(t){"progress"===t?this.timer=setInterval(this.getStatus,1e3):clearInterval(this.timer)}}}}}(),t.exports.__esModule&&(t.exports=t.exports.default);var s="function"==typeof t.exports?t.exports.options:t.exports;s.render=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("k-field",{staticClass:"pju-webhooks",attrs:{label:t.label}},[e("WebhooksStatus",{attrs:{status:t.status,hookName:t.hook.name,labels:t.labels}}),t._v(" "),e("k-button",{staticClass:"pju-webhooks--btn",attrs:{icon:"upload",type:"submit",disabled:t.ctaDisabled},on:{click:t.triggerHook}},[t._v("\n    "+t._s(t.statusCta)+"\n  ")])],1)},s.staticRenderFns=[]},{2:2,3:3}],2:[function(t,s,e){!function(){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:{status:String,hookName:String,labels:Object},computed:{icon:function(){var t=void 0;switch(this.status){case"success":t="check";break;case"progress":t="loader";break;case"error":t="cancel";break;case"new":t="bolt";break;case"outdated":t="alert";break;default:t="cancel"}return t},statusName:function(){return this.labels[this.status]?this.labels[this.status].name:this.status},statusText:function(){if(!this.labels[this.status])return"";var t=this.labels[this.status].text;return t=t.replace("%hookName%",this.hookName)},statusClass:function(){return"status-"+this.status},iconClass:function(){return"icon-"+this.icon}}}}(),s.exports.__esModule&&(s.exports=s.exports.default);var a="function"==typeof s.exports?s.exports.options:s.exports;a.render=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("div",{staticClass:"pju-webhooks--status",class:[t.statusClass,t.iconClass]},[e("span",{staticClass:"visuallyhidden"},[t._v("Status")]),t._v(" "),e("k-icon",{staticClass:"pju-webhooks--status--icon",attrs:{type:t.icon}}),t._v(" "),e("div",{staticClass:"pju-webhooks--status--label"},[e("p",{staticClass:"pju-webhooks--status--name"},[t._v("\n      "+t._s(t.statusName)+"\n    ")]),t._v(" "),e("p",{staticClass:"pju-webhooks--status--description"},[e("small",[t._v("\n        "+t._s(t.statusText)+"\n      ")])])])],1)},a.staticRenderFns=[]},{}],3:[function(t,s,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.request=function(t,s,e,a){var o=new XMLHttpRequest;o.open(s,t,!0),o.onreadystatechange=function(){200==o.status&&4==o.readyState?e&&e(o):200!==o.status&&a&&a(o)},o.send()}},{}],4:[function(t,s,e){"use strict";var a,o=t(1),n=(a=o)&&a.__esModule?a:{default:a};panel.plugin("pju/webhooks",{fields:{webhook:n.default}})},{1:1}]},{},[4]);
