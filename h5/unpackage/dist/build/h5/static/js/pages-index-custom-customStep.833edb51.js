(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-custom-customStep"],{"247e":function(t,e,o){var i=o("ba4e");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=o("4f06").default;a("f96138a6",i,!0,{sourceMap:!1,shadowMode:!1})},"32b8":function(t,e,o){"use strict";var i={uniPopup:o("70f3").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"result-wrap"},[1===t.curStep?i("v-uni-view",{staticClass:"model-show model-show-step-1"}):t._e(),2===t.curStep?i("v-uni-view",{staticClass:"model-show model-show-step-2"},[i("v-uni-movable-area",{staticStyle:{height:"800rpx",width:"750rpx"},attrs:{"scale-area":!0}},[i("v-uni-movable-view",{staticStyle:{width:"200rpx",height:"300rpx"},attrs:{scale:!0,"out-of-bounds":!0,direction:"all"}},[i("v-uni-image",{staticClass:"img-choosed",style:"transform: rotate("+t.imgRotate[t.imgRotateIndex]+"deg)",attrs:{src:t.templateImg,mode:"widthFix"}})],1)],1)],1):t._e(),3===t.curStep?i("v-uni-view",{staticClass:"model-show model-show-step-3"},[i("v-uni-movable-area",{staticStyle:{height:"800rpx",width:"750rpx"},attrs:{"scale-area":!0}},[i("v-uni-movable-view",{style:"width: 1900rpx; height: 600rpx;",attrs:{scale:!0,"out-of-bounds":!0,direction:"all"}},[t.textChoosed?i("v-uni-view",{staticClass:"text-wrap",class:{"text-wrap-vertical":t.textChoosedVertical},style:{transform:"rotate("+t.textAngle+"deg)"}},[i("v-uni-view",{staticClass:"text-choosed",style:{fontSize:2*t.textSize+"rpx",color:t.curColor}},[t._v(t._s(t.textChoosed))]),i("v-uni-view",{staticClass:"cuIcon-roundclosefill",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.clearInfo("text")}}})],1):t._e()],1)],1)],1):t._e(),4===t.curStep?i("v-uni-view",{staticClass:"model-show model-show-step-4"}):t._e(),i("v-uni-view",{staticClass:"custom-step",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.clsoeStepShowDetail.apply(void 0,arguments)}}},[t.stepShowDetail?i("v-uni-view",t._l(t.customStep,(function(e,o){return i("v-uni-view",{key:o,staticClass:"custom-step-item"},[i("v-uni-view",{staticClass:"step-dot",class:{"step-dot-checked":t.curStep==o+1}}),i("v-uni-view",{staticClass:"step-name",class:{"step-name-checked":t.curStep==o+1}},[t._v(t._s(e))])],1)})),1):i("v-uni-view",{staticClass:"custom-step-icon"},[i("v-uni-view",{staticClass:"custom-step-icon-green"}),i("v-uni-view",{staticClass:"custom-step-icon-white"})],1)],1),1===t.curStep?i("v-uni-view",{staticClass:"custom-appearence-desc",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.togo("customDesc")}}},[i("v-uni-text",{staticClass:"cuIcon-list"}),i("v-uni-text",{staticClass:"desc-text"},[t._v("产品包装说明")])],1):t._e()],1),i("v-uni-view",{staticClass:"control-panel"},[1===t.curStep?i("v-uni-view",{staticClass:"control-panel-step control-panel-step-1"},[i("v-uni-view",{staticClass:"class-path"},t._l(t.classPath,(function(e,o){return i("v-uni-text",{key:o,staticClass:"class-path-item",class:{"cuIcon-right":o==t.classPath.length-1}},[t._v(t._s(e))])})),1),i("v-uni-view",{staticClass:"style-choose"},t._l(t.styleList,(function(e,o){return i("v-uni-view",{key:e.id,staticClass:"style-choose-item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.changeStyle(e,o)}}},[i("v-uni-view",{staticClass:"item-img-wrap"},[i("v-uni-view",{staticClass:"item-title"},[t._v(t._s(e.title))]),i("v-uni-view",{staticClass:"item-check",class:e.check?"cuIcon-roundcheckfill":""}),i("v-uni-image",{staticClass:"item-img",attrs:{src:e.img,mode:"aspectFill"}})],1),i("v-uni-view",{staticClass:"item-hint"},[t._v(t._s(e.hint))])],1)})),1),i("v-uni-view",{staticClass:"next-step btn btn-green",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.nextStepBtn.apply(void 0,arguments)}}},[t._v("下一步")]),i("v-uni-view",{staticClass:"function-btn btn btn-white"},[t._v("求助设计师线下服务")])],1):t._e(),2===t.curStep?i("v-uni-view",{staticClass:"control-panel-step control-panel-step-2"},[i("v-uni-view",{staticClass:"step-2-img-wrap"},[t.templateImg?i("v-uni-view",{staticClass:"step-2-img-choose",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.imgUplaod.apply(void 0,arguments)}}},[i("v-uni-image",{staticClass:"step-2-img-choose-checked",attrs:{src:t.templateImg,mode:"aspectFit"}}),i("v-uni-view",{staticClass:"step-2-img-choose-checked-hint"},[t._v("更换")])],1):i("v-uni-view",{staticClass:"step-2-img-choose",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.imgUplaod.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"cuIcon-add"}),i("v-uni-view",{staticClass:"img-choose-text"},[t._v("上传图片")])],1),i("v-uni-view",{staticClass:"step-2-img-rotate iconfont icon-imgRotate",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.rotateImg.apply(void 0,arguments)}}})],1),i("v-uni-view",{staticClass:"relative-hint-popup",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.popupManger("infoShow")}}},[i("v-uni-text",{staticClass:"cuIcon-info"}),t._v("什么是缩颈线？")],1),i("v-uni-view",{staticClass:"relative-hint"},[t._v("建议图片大小不低于1M，以免影响制作效果")]),i("v-uni-view",{staticClass:"next-step btn btn-green",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.nextStepBtn.apply(void 0,arguments)}}},[t._v("下一步")]),i("v-uni-view",{staticClass:"function-btn btn btn-grey",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.resetStyle.apply(void 0,arguments)}}},[t._v("重新选择模板")])],1):t._e(),3===t.curStep?i("v-uni-view",{staticClass:"control-panel-step control-panel-step-3"},[i("v-uni-view",{staticClass:"step-3-tab"},t._l(["文字","颜色","排版"],(function(e,o){return i("v-uni-view",{key:o,staticClass:"step-3-tab-item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.changeTabStep2(e,o)}}},[i("v-uni-view",{staticClass:"step-3-tab-item-text iconfont",class:{"step-3-tab-item-text-checked":t.curTabIndex===o}},[i("v-uni-text",{class:0===o?"icon-text":1===o?"icon-textColor":2===o?"icon-typesetting":""}),t._v(t._s(e))],1)],1)})),1),0===t.curTabIndex?i("v-uni-view",{staticClass:"step-3-tab-1"},[i("v-uni-view",{staticClass:"step-3-add-text"},[i("v-uni-view",{staticClass:"step-3-add-text-input-wrap"},[i("v-uni-input",{staticClass:"step-3-add-text-input",attrs:{type:"text",placeholder:"添加文本"},model:{value:t.textChoosedTemp,callback:function(e){t.textChoosedTemp=e},expression:"textChoosedTemp"}})],1),i("v-uni-view",{staticClass:"step-3-add-text-check",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.checkText.apply(void 0,arguments)}}},[i("v-uni-text",{staticClass:"cuIcon-check"})],1)],1),i("v-uni-view",{staticClass:"adjust-text-size"},[i("v-uni-view",{staticClass:"adjust-text-left"},[i("v-uni-view",{staticClass:"adjust-text-title"},[t._v("字体大小")]),i("v-uni-view",{staticClass:"adjust-text-reset"},[t._v("重置")])],1),i("v-uni-view",{staticClass:"adjust-text-right"},[i("v-uni-slider",{staticClass:"adjust-text-slider",attrs:{value:t.textSize,"block-size":"20",activeColor:"#e9e9e9",min:"10",max:"60"},on:{changing:function(e){arguments[0]=e=t.$handleEvent(e),t.changeTextSize.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"adjust-text-size-show"},[i("v-uni-view",{staticClass:"adjust-text-size-min"},[t._v("A-")]),i("v-uni-view",{staticClass:"adjust-text-size-now"},[t._v(t._s(t.textSize))]),i("v-uni-view",{staticClass:"adjust-text-size-max"},[t._v("A+")])],1)],1)],1)],1):t._e(),1===t.curTabIndex?i("v-uni-view",{staticClass:"step-3-tab-2"},[i("v-uni-view",{staticClass:"text-color"},[i("v-uni-view",{staticClass:"text-color-hint"},[t._v("字体颜色")]),i("v-uni-view",{staticClass:"text-color-choose"},t._l(t.textColor,(function(e,o){return i("v-uni-view",{key:e.id,staticClass:"text-color-choose-item",class:{"text-color-choose-item-checked":t.curColorIndex===o},style:{backgroundColor:e.hex,border:"2rpx solid"+e.hex},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.changeTextColor(e,o)}}},[i("v-uni-view",{staticClass:"white-circle"})],1)})),1)],1)],1):t._e(),2===t.curTabIndex?i("v-uni-view",{staticClass:"step-3-tab-3"},[i("v-uni-view",{staticClass:"step-3-text-orient"},[i("v-uni-view",{staticClass:"text-orient-item",class:{"text-orient-item-checked":0===t.curOrientType},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeOrient(0)}}},[i("v-uni-view",{staticClass:"text-orient-item-text"},[t._v("「 横版 」")])],1),i("v-uni-view",{staticClass:"text-orient-item",class:{"text-orient-item-checked":1===t.curOrientType},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeOrient(1)}}},[i("v-uni-view",{staticClass:"text-orient-item-text"},[t._v("「 竖版 」")])],1)],1),i("v-uni-view",{staticClass:"adjust-text-angle"},[i("v-uni-view",{staticClass:"adjust-text-left"},[i("v-uni-view",{staticClass:"adjust-text-title"},[t._v("字体旋转")]),i("v-uni-view",{staticClass:"adjust-text-reset"},[t._v("重置")])],1),i("v-uni-view",{staticClass:"adjust-text-right"},[i("v-uni-slider",{staticClass:"adjust-text-slider",attrs:{value:t.textAngle,"block-size":"20",activeColor:"#e9e9e9",min:"-180",max:"180"},on:{changing:function(e){arguments[0]=e=t.$handleEvent(e),t.changeTextAngle.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"adjust-text-angle-show"},[i("v-uni-view",{staticClass:"adjust-text-angle-min"},[t._v("-180")]),i("v-uni-view",{staticClass:"adjust-text-angle-now"},[t._v(t._s(t.textAngle))]),i("v-uni-view",{staticClass:"adjust-text-angle-max"},[t._v("180")])],1)],1)],1)],1):t._e(),i("v-uni-view",{staticClass:"relative-hint-popup",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.popupManger("infoShow")}}},[i("v-uni-text",{staticClass:"cuIcon-info"}),t._v("什么是缩颈线？")],1),i("v-uni-view",{staticClass:"next-step btn btn-green",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.nextStepBtn.apply(void 0,arguments)}}},[t._v("生成")]),i("v-uni-view",{staticClass:"function-btn btn btn-grey",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.resetStyle.apply(void 0,arguments)}}},[t._v("重新选择模板")])],1):t._e(),4===t.curStep?i("v-uni-view",{staticClass:"control-panel-step control-panel-step-4"},[i("v-uni-view",{staticClass:"cube-placeholder-wrap"},[i("v-uni-view",{staticClass:"cube-placeholder iconfont icon-rollCube"}),i("v-uni-view",{staticClass:"cube-placeholder-text"},[t._v("点击查看3D立体效果图")])],1),i("v-uni-view",{staticClass:"next-step btn btn-green",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.checkBtn.apply(void 0,arguments)}}},[t._v("确认定制")]),i("v-uni-view",{staticClass:"function-btn btn btn-grey",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.resetStyle.apply(void 0,arguments)}}},[t._v("重新选择模板")])],1):t._e(),i("customer-service")],1),i("uni-popup",{ref:"resetTemplate"},[i("v-uni-view",{staticClass:"reset-template"},[i("v-uni-view",{staticClass:"reset-template-title"},[t._v("是否重新选择模板？")]),i("v-uni-view",{staticClass:"reset-template-subtitle"},[t._v("当前所做的编辑都将丢失")]),i("v-uni-view",{staticClass:"reset-template-btn"},[i("v-uni-view",{staticClass:"reset-template-btn-item reset-template-btn-cancel",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.resetBtn("cancel")}}},[t._v("取消")]),i("v-uni-view",{staticClass:"reset-template-btn-item reset-template-btn-check",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.resetBtn("check")}}},[t._v("确定")])],1)],1)],1),i("uni-popup",{ref:"infoShow",attrs:{type:"bottom"}},[i("v-uni-view",{staticClass:"info-show"},[i("v-uni-view",{staticClass:"cuIcon-roundclosefill",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.popupManger("infoShow","close")}}}),i("v-uni-view",{staticClass:"info-img-show"},[i("v-uni-image",{staticClass:"info-img",attrs:{src:o("6614"),mode:"widthFix"}}),i("v-uni-view",{staticClass:"info-img-hint"},[t._v("--缩颈线")])],1),i("v-uni-view",{staticClass:"info-show-hint"},[t._v("缩颈线为分割上方弧度区与主体罐身的参考线，为保证质量，缩颈线上方请尽量避免出现人像或过于复杂的设计，以免发生变形。")])],1)],1)],1)},n=[];o.d(e,"b",(function(){return a})),o.d(e,"c",(function(){return n})),o.d(e,"a",(function(){return i}))},"44ec":function(t,e,o){"use strict";o.r(e);var i=o("32b8"),a=o("aebd");for(var n in a)"default"!==n&&function(t){o.d(e,t,(function(){return a[t]}))}(n);o("5433");var s,l=o("f0c5"),c=Object(l["a"])(a["default"],i["b"],i["c"],!1,null,"853f213e",null,!1,i["a"],s);e["default"]=c.exports},5433:function(t,e,o){"use strict";var i=o("247e"),a=o.n(i);a.a},"54f8":function(t,e,o){"use strict";o.r(e);o("a4d3"),o("e01a"),o("d28b"),o("e260"),o("d3b7"),o("3ca3"),o("ddb0"),o("a630"),o("fb6a"),o("25f0");function i(t,e){(null==e||e>t.length)&&(e=t.length);for(var o=0,i=new Array(e);o<e;o++)i[o]=t[o];return i}function a(t,e){if(t){if("string"===typeof t)return i(t,e);var o=Object.prototype.toString.call(t).slice(8,-1);return"Object"===o&&t.constructor&&(o=t.constructor.name),"Map"===o||"Set"===o?Array.from(o):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?i(t,e):void 0}}function n(t){if("undefined"===typeof Symbol||null==t[Symbol.iterator]){if(Array.isArray(t)||(t=a(t))){var e=0,o=function(){};return{s:o,n:function(){return e>=t.length?{done:!0}:{done:!1,value:t[e++]}},e:function(t){throw t},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,n,s=!0,l=!1;return{s:function(){i=t[Symbol.iterator]()},n:function(){var t=i.next();return s=t.done,t},e:function(t){l=!0,n=t},f:function(){try{s||null==i["return"]||i["return"]()}finally{if(l)throw n}}}}o.d(e,"default",(function(){return n}))},6614:function(t,e,o){t.exports=o.p+"static/img/suojing.e321f2a5.png"},7472:function(t,e,o){"use strict";(function(t){var i=o("ee27");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(o("54f8")),n=i(o("057e")),s=i(o("70f3")),l={components:{customerService:n.default,UniPopup:s.default},data:function(){return{curStep:1,curTabIndex:0,curColorIndex:0,curColor:"",classPath:["企业","全部"],textSize:"35",curOrientType:0,textAngle:"0",templateImg:"",imgRotate:["0","90","180","270"],imgRotateIndex:0,textChoosed:"",textChoosedTemp:"",textChoosedVertical:!1,stepShowDetail:!0,stepShowDetailFlag:!0,styleList:[{id:0,title:"年会聚会",hint:"深海蓝",check:1,img:"../../../static/styleOmit.png"},{id:1,title:"年会聚会",hint:"天空蓝",check:0,img:"../../../static/styleOmit.png"},{id:2,title:"年会聚会",hint:"活力蓝",check:0,img:"../../../static/styleOmit.png"}],customStep:["选择模板","上传照片","编辑内容","定制完成"],textColor:[{id:0,hex:"#FFFFFF"},{id:1,hex:"#CCCCCC"},{id:2,hex:"#6B6B6B"},{id:3,hex:"#424242"},{id:4,hex:"#FFEC0a"},{id:5,hex:"#FFE0F0"},{id:6,hex:"#8A71FF"},{id:7,hex:"#658D6B"},{id:8,hex:"#FD663B"},{id:9,hex:"#C61F31"}]}},methods:{changeStyle:function(t,e){var o,i=(0,a.default)(this.styleList);try{for(i.s();!(o=i.n()).done;){var n=o.value;n.check&&this.$set(n,"check",!1)}}catch(s){i.e(s)}finally{i.f()}t.check?this.$set(t,"check",!1):this.$set(t,"check",!0)},nextStepBtn:function(){this.curStep+=1},checkBtn:function(){t("log","[+] 确认定制"," at pages/index/custom/customStep.vue:351")},resetStyle:function(){this.$refs.resetTemplate.open()},resetBtn:function(t){"cancel"==t||"check"==t&&(this.curStep=1),this.$refs.resetTemplate.close()},imgUplaod:function(){var t=this;uni.chooseImage({count:1,sizeType:["original","compressed"],sourceType:["album"],success:function(e){t.templateImg=e.tempFilePaths[0],t.imgRotateIndex=0}})},changeTextSize:function(t){this.textSize=t.detail.value},changeTextAngle:function(t){this.textAngle=t.detail.value},changeTabStep2:function(t,e){this.curTabIndex=e},changeTextColor:function(t,e){this.curColorIndex=e,this.curColor=t.hex},changeOrient:function(t){this.curOrientType=t,this.textChoosedVertical=0!==t},popupManger:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"";"close"==e?this.$refs[t].close():this.$refs[t].open()},rotateImg:function(){3===this.imgRotateIndex?this.imgRotateIndex=0:this.imgRotateIndex+=1},checkText:function(){this.textChoosed=this.textChoosedTemp},clsoeStepShowDetail:function(){var t=this;this.stepShowDetail&&clearTimeout(this.stepShowDetailFlag),this.stepShowDetail=!0,this.stepShowDetailFlag=setTimeout((function(){t.stepShowDetail=!1}),2e3)},clearInfo:function(t){"text"==t?this.textChoosed="":"img"==t&&(this.templateImg="")},togo:function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};this.commonJs.togo(t,e)}},onShow:function(){this.clsoeStepShowDetail()}};e.default=l}).call(this,o("0de9")["log"])},aebd:function(t,e,o){"use strict";o.r(e);var i=o("7472"),a=o.n(i);for(var n in i)"default"!==n&&function(t){o.d(e,t,(function(){return i[t]}))}(n);e["default"]=a.a},ba4e:function(t,e,o){var i=o("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 绿色层级 */\r\n/* 灰色层级 */\r\n/* 红色层级 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.result-wrap[data-v-853f213e]{height:%?600?%;background-color:#f8f8f8;position:relative;overflow:hidden}.result-wrap .model-show[data-v-853f213e]{position:relative}.result-wrap .model-show.model-show-step-2[data-v-853f213e]{position:absolute}.result-wrap .model-show.model-show-step-2 .img-wrap[data-v-853f213e]{width:%?320?%;height:%?160?%;overflow:hidden}.result-wrap .model-show.model-show-step-2 .img-wrap .img-choosed[data-v-853f213e]{width:%?320?%}.result-wrap .model-show.model-show-step-3[data-v-853f213e]{overflow:hidden}.result-wrap .model-show.model-show-step-3 .text-wrap[data-v-853f213e]{position:relative;display:inline-block}.result-wrap .model-show.model-show-step-3 .text-wrap .text-choosed[data-v-853f213e]{display:inline-block;word-break:break-all;line-height:1;padding:%?10?% %?20?%;border:%?2?% solid #fff}.result-wrap .model-show.model-show-step-3 .text-wrap .cuIcon-roundclosefill[data-v-853f213e]{position:absolute;top:%?-26?%;left:%?-26?%;color:#fff;font-size:%?50?%}.result-wrap .model-show.model-show-step-3 .text-wrap.text-wrap-vertical[data-v-853f213e]{-webkit-writing-mode:vertical-lr;writing-mode:vertical-lr}.result-wrap .custom-step[data-v-853f213e]{position:absolute;top:%?20?%;left:%?20?%}.result-wrap .custom-step .custom-step-item[data-v-853f213e]{height:%?68?%;line-height:%?68?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:relative}.result-wrap .custom-step .custom-step-item[data-v-853f213e]:not(:last-child)::before{content:"";width:%?5?%;height:88%;background-color:#009a44;position:absolute;top:56%;left:%?5?%}.result-wrap .custom-step .custom-step-item .step-dot[data-v-853f213e]{width:%?8?%;height:%?8?%;border:%?2?% solid #009a44;border-radius:50%}.result-wrap .custom-step .custom-step-item .step-dot.step-dot-checked[data-v-853f213e]{border-radius:%?5?%;background-color:#009a44;height:%?6?%}.result-wrap .custom-step .custom-step-item .step-name[data-v-853f213e]{color:#c5c5c5;margin-left:%?10?%;font-size:%?24?%}.result-wrap .custom-step .custom-step-item .step-name.step-name-checked[data-v-853f213e]{color:#009a44}.result-wrap .custom-step .custom-step-icon[data-v-853f213e]{background-color:#fff;border-radius:50%;width:%?60?%;height:%?60?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.result-wrap .custom-step .custom-step-icon .custom-step-icon-green[data-v-853f213e]{width:%?10?%;height:%?10?%;background-color:#009a44;border:%?2?% solid #009a44;border-radius:50%}.result-wrap .custom-step .custom-step-icon .custom-step-icon-white[data-v-853f213e]{width:%?10?%;height:%?10?%;background-color:#fff;border:%?2?% solid #009a44;border-radius:50%;margin-top:%?6?%}.result-wrap .custom-appearence-desc[data-v-853f213e]{position:absolute;top:%?20?%;right:0;background-color:#ddeee4;border-radius:%?50?% 0 0 %?50?%;padding:%?12?% %?20?%}.result-wrap .custom-appearence-desc .cuIcon-list[data-v-853f213e]{background-color:#009a44;border-radius:50%;color:#fff;line-height:1;padding:%?8?%;font-size:%?24?%}.result-wrap .custom-appearence-desc .desc-text[data-v-853f213e]{margin-left:%?6?%}.btn[data-v-853f213e]{width:%?320?%;border-radius:%?50?%;height:%?80?%;line-height:%?80?%;text-align:center;font-size:%?30?%;margin:0 auto}.btn-green[data-v-853f213e]{border:%?2?% solid #009a44;background-color:#009a44;color:#fff}.btn-white[data-v-853f213e]{color:#009a44;border:%?2?% solid #009a44}.btn-grey[data-v-853f213e]{color:#c0c4cd;border:%?2?% solid #c0c4cd}.control-panel[data-v-853f213e]{padding:%?20?%}.control-panel .control-panel-step .next-step[data-v-853f213e]{margin-top:%?50?%}.control-panel .control-panel-step .function-btn[data-v-853f213e]{margin-top:%?28?%}.control-panel .control-panel-step-1 .style-choose .style-choose-item[data-v-853f213e]{display:inline-block;margin:%?10?%}.control-panel .control-panel-step-1 .style-choose .style-choose-item .item-img-wrap[data-v-853f213e]{position:relative}.control-panel .control-panel-step-1 .style-choose .style-choose-item .item-img-wrap .item-title[data-v-853f213e]{position:absolute;top:%?10?%;left:%?20?%;padding:%?2?% %?8?%;z-index:2;font-size:%?26?%;background-color:rgba(0,0,0,.3);color:#fff;border-radius:%?10?%}.control-panel .control-panel-step-1 .style-choose .style-choose-item .item-img-wrap .item-check[data-v-853f213e]{position:absolute;bottom:%?10?%;right:%?10?%;z-index:2;font-size:%?42?%}.control-panel .control-panel-step-1 .style-choose .style-choose-item .item-img-wrap .item-check.cuIcon-round[data-v-853f213e]{color:#eee}.control-panel .control-panel-step-1 .style-choose .style-choose-item .item-img-wrap .item-check.cuIcon-roundcheckfill[data-v-853f213e]{color:#009a44;background-color:#fff;border-radius:50%;line-height:1;bottom:%?16?%}.control-panel .control-panel-step-1 .style-choose .style-choose-item .item-img-wrap .item-img[data-v-853f213e]{width:%?150?%;height:%?150?%;border-radius:%?10?%}.control-panel .control-panel-step-1 .style-choose .style-choose-item .item-hint[data-v-853f213e]{text-align:center;font-size:%?26?%}.control-panel .control-panel-step-2 .step-2-img-wrap[data-v-853f213e]{position:relative}.control-panel .control-panel-step-2 .step-2-img-wrap .step-2-img-choose[data-v-853f213e]{background-color:#f7f7f7;width:%?180?%;height:%?180?%;border-radius:%?16?%;border:%?2?% dashed #c0c4cd;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;margin:0 auto;position:relative}.control-panel .control-panel-step-2 .step-2-img-wrap .step-2-img-choose .cuIcon-add[data-v-853f213e]{font-size:%?60?%;color:#bcbcc3}.control-panel .control-panel-step-2 .step-2-img-wrap .step-2-img-choose .img-choose-text[data-v-853f213e]{color:#c0c4cd;font-size:%?30?%}.control-panel .control-panel-step-2 .step-2-img-wrap .step-2-img-choose .step-2-img-choose-checked[data-v-853f213e]{width:%?180?%;height:%?180?%;border-radius:%?16?%}.control-panel .control-panel-step-2 .step-2-img-wrap .step-2-img-choose .step-2-img-choose-checked-hint[data-v-853f213e]{position:absolute;bottom:0;height:%?36?%;width:%?180?%;background-color:hsla(0,0%,100%,.6);z-index:9;text-align:center;font-size:%?26?%}.control-panel .control-panel-step-2 .step-2-img-wrap .step-2-img-rotate[data-v-853f213e]{position:absolute;color:#92cfad;border-radius:50%;box-shadow:0 %?5?% %?30?% %?-2?% #92cfad;line-height:1;font-size:%?42?%;padding:%?12?% %?12?% %?16?% %?18?%;right:%?118?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%)}.control-panel .control-panel-step-2 .relative-hint-popup[data-v-853f213e]{text-align:center;color:#999;margin:%?30?% 0 %?10?% 0}.control-panel .control-panel-step-2 .relative-hint-popup .cuIcon-info[data-v-853f213e]{color:#999;font-weight:700;margin-right:%?6?%}.control-panel .control-panel-step-2 .relative-hint[data-v-853f213e]{margin:0 auto;text-align:center;width:%?460?%;color:#009a44;font-size:%?30?%}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item[data-v-853f213e]{display:inline-block;width:33%;text-align:center}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text[data-v-853f213e]{display:inline-block;vertical-align:top;border-bottom:%?5?% solid #fff;height:%?80?%;padding-top:%?8?%}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text .icon-text[data-v-853f213e]{border:%?5?% dotted #333;font-size:%?26?%;padding:%?6?%;margin-right:%?10?%;position:relative;top:%?-5?%;border-radius:%?6?%}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text .icon-textColor[data-v-853f213e]{margin-right:%?10?%;padding:%?6?%;border:%?5?% solid #333;border-radius:%?6?%}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text .icon-typesetting[data-v-853f213e]{margin-right:%?10?%;font-size:%?50?%;position:relative;top:%?8?%;line-height:.8}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text.step-3-tab-item-text-checked[data-v-853f213e]{color:#009a44;border-bottom:%?5?% solid #009a44}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text.step-3-tab-item-text-checked .icon-text[data-v-853f213e]{color:#009a44;border:%?5?% dotted #009a44}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text.step-3-tab-item-text-checked .icon-textColor[data-v-853f213e]{color:#009a44;border:%?5?% solid #009a44}.control-panel .control-panel-step-3 .step-3-tab .step-3-tab-item .step-3-tab-item-text.step-3-tab-item-text-checked .icon-typesetting[data-v-853f213e]{color:#009a44}.control-panel .control-panel-step-3 .step-3-tab-1 .step-3-add-text[data-v-853f213e]{margin:%?60?% 0;display:-webkit-box;display:-webkit-flex;display:flex;height:%?80?%;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.control-panel .control-panel-step-3 .step-3-tab-1 .step-3-add-text *[data-v-853f213e]{box-sizing:border-box}.control-panel .control-panel-step-3 .step-3-tab-1 .step-3-add-text .step-3-add-text-input-wrap .step-3-add-text-input[data-v-853f213e]{height:100%;background-color:#eee;border-radius:%?50?% 0 0 %?50?%;text-align:center;border:%?2?% solid #c0c4cd}.control-panel .control-panel-step-3 .step-3-tab-1 .step-3-add-text .step-3-add-text-check[data-v-853f213e]{background-color:#009a44;border-radius:0 %?50?% %?50?% 0;width:%?118?%;text-align:center;font-size:%?50?%;line-height:%?80?%}.control-panel .control-panel-step-3 .step-3-tab-1 .step-3-add-text .step-3-add-text-check .cuIcon-check[data-v-853f213e]{color:#fff;position:relative;left:%?-5?%}.control-panel .control-panel-step-3 .adjust-text-angle[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-size[data-v-853f213e]{display:-webkit-box;display:-webkit-flex;display:flex}.control-panel .control-panel-step-3 .adjust-text-angle .adjust-text-left[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-size .adjust-text-left[data-v-853f213e]{position:relative;top:%?20?%;left:%?20?%}.control-panel .control-panel-step-3 .adjust-text-angle .adjust-text-left .adjust-text-title[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-size .adjust-text-left .adjust-text-title[data-v-853f213e]{color:#666}.control-panel .control-panel-step-3 .adjust-text-angle .adjust-text-left .adjust-text-reset[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-size .adjust-text-left .adjust-text-reset[data-v-853f213e]{color:#c0c4cd;border:%?2?% solid #c0c4cd;border-radius:%?50?%;text-align:center;width:%?68?%;font-size:%?24?%;line-height:1;padding:%?6?% %?8?%;margin-top:%?10?%}.control-panel .control-panel-step-3 .adjust-text-angle .adjust-text-right[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-size .adjust-text-right[data-v-853f213e]{width:%?568?%;position:relative;left:%?28?%}.control-panel .control-panel-step-3 .adjust-text-angle .adjust-text-right .adjust-text-angle-show[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-angle .adjust-text-right .adjust-text-size-show[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-size .adjust-text-right .adjust-text-angle-show[data-v-853f213e],\r\n.control-panel .control-panel-step-3 .adjust-text-size .adjust-text-right .adjust-text-size-show[data-v-853f213e]{width:%?476?%;margin:0 auto;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color[data-v-853f213e]{display:-webkit-box;display:-webkit-flex;display:flex;margin-top:%?60?%}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color *[data-v-853f213e]{box-sizing:border-box}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color .text-color-hint[data-v-853f213e]{width:%?80?%;color:#666}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color .text-color-choose[data-v-853f213e]{width:%?670?%}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color .text-color-choose .text-color-choose-item[data-v-853f213e]{display:inline-block;width:%?80?%;height:%?80?%;border-radius:50%;margin:0 %?20?% %?12?% %?20?%;position:relative}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color .text-color-choose .text-color-choose-item[data-v-853f213e]:first-child{border:%?2?% solid #c0c4cd!important}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color .text-color-choose .text-color-choose-item.text-color-choose-item-checked[data-v-853f213e]{border:%?5?% solid #009a44!important;box-shadow:0 0 %?30?% 0 #009a44}.control-panel .control-panel-step-3 .step-3-tab-2 .text-color .text-color-choose .text-color-choose-item.text-color-choose-item-checked .white-circle[data-v-853f213e]{position:absolute;width:%?72?%;height:%?72?%;border:%?6?% solid #fff;border-radius:50%}.control-panel .control-panel-step-3 .step-3-tab-3 .step-3-text-orient[data-v-853f213e]{margin:%?50?% 0;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.control-panel .control-panel-step-3 .step-3-tab-3 .step-3-text-orient .text-orient-item[data-v-853f213e]{border:%?2?% solid #f7f7f7;padding:%?50?% 0;border-radius:%?100?%;width:%?320?%;text-align:center;color:#999;position:relative}.control-panel .control-panel-step-3 .step-3-tab-3 .step-3-text-orient .text-orient-item .text-orient-item-text[data-v-853f213e]{font-size:%?32?%;position:absolute;background-color:#fff;width:%?220?%;left:%?50?%;top:%?-10?%;height:%?116?%;line-height:%?116?%}.control-panel .control-panel-step-3 .step-3-tab-3 .step-3-text-orient .text-orient-item.text-orient-item-checked .text-orient-item-text[data-v-853f213e]{color:#009a44}.control-panel .control-panel-step-3 .relative-hint-popup[data-v-853f213e]{text-align:center;color:#999;margin:%?60?% 0 %?10?% 0}.control-panel .control-panel-step-3 .relative-hint-popup .cuIcon-info[data-v-853f213e]{color:#999;font-weight:700;margin-right:%?6?%}.control-panel .control-panel-step-4 .cube-placeholder-wrap[data-v-853f213e]{text-align:center;margin:%?60?% 0}.control-panel .control-panel-step-4 .cube-placeholder-wrap .cube-placeholder[data-v-853f213e]{width:%?118?%;height:%?118?%;line-height:%?118?%;font-size:%?90?%;border-radius:50%;display:inline-block;color:#92cfad;box-shadow:0 %?6?% %?50?% %?-5?% #92cfad}.control-panel .control-panel-step-4 .cube-placeholder-wrap .cube-placeholder-text[data-v-853f213e]{font-size:%?30?%;margin-top:%?46?%}.reset-template[data-v-853f213e]{background-color:#fff;width:%?560?%;border-radius:%?12?%;padding-top:%?36?%}.reset-template .reset-template-title[data-v-853f213e]{text-align:center;font-size:%?32?%;letter-spacing:%?2?%}.reset-template .reset-template-subtitle[data-v-853f213e]{text-align:center;color:#666;margin:%?20?% 0 %?30?% 0}.reset-template .reset-template-btn[data-v-853f213e]{border-top:%?2?% solid #f7f7f7}.reset-template .reset-template-btn .reset-template-btn-item[data-v-853f213e]{display:inline-block;width:%?280?%;height:%?80?%;line-height:%?80?%;box-sizing:border-box;font-size:%?36?%;text-align:center}.reset-template .reset-template-btn .reset-template-btn-cancel[data-v-853f213e]{color:#009a44}.reset-template .reset-template-btn .reset-template-btn-check[data-v-853f213e]{border-left:%?2?% solid #f7f7f7}.info-show[data-v-853f213e]{padding:%?20?%;background-color:#fff;border-radius:%?16?% %?16?% 0 0}.info-show .cuIcon-roundclosefill[data-v-853f213e]{text-align:right;font-size:%?50?%;color:#bcbcc3}.info-show .info-img-show[data-v-853f213e]{display:-webkit-box;display:-webkit-flex;display:flex}.info-show .info-img-show .info-img[data-v-853f213e]{width:%?466?%}.info-show .info-img-show .info-img-hint[data-v-853f213e]{position:relative;top:%?45?%;margin-left:%?30?%;font-size:%?30?%;color:#666}.info-show .info-show-hint[data-v-853f213e]{margin:%?20?% 0;letter-spacing:%?2?%;font-size:%?30?%}',""]),t.exports=e}}]);