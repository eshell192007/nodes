(function(f){function B(a,b,d){var c=a[0],g=/er/.test(d)?_indeterminate:/bl/.test(d)?n:k,e=d==_update?{checked:c[k],disabled:c[n],indeterminate:"true"==a.attr(_indeterminate)||"false"==a.attr(_determinate)}:c[g];if(/^(ch|di|in)/.test(d)&&!e)y(a,g);else if(/^(un|en|de)/.test(d)&&e)q(a,g);else if(d==_update)for(var f in e)e[f]?y(a,f,!0):q(a,f,!0);else if(!b||"toggle"==d){if(!b)a[_callback]("ifClicked");e?c[_type]!==r&&q(a,g):y(a,g)}}function y(a,b,d){var c=a[0],g=a.parent(),e=b==k,v=b==_indeterminate,
w=b==n,t=v?_determinate:e?z:"enabled",G=l(a,t+u(c[_type])),C=l(a,b+u(c[_type]));if(!0!==c[b]){if(!d&&b==k&&c[_type]==r&&c.name){var x=a.closest("form"),p='input[name="'+c.name+'"]',p=x.length?x.find(p):f(p);p.each(function(){this!==c&&f(this).data(m)&&q(f(this),b)})}v?(c[b]=!0,c[k]&&q(a,k,"force")):(d||(c[b]=!0),e&&c[_indeterminate]&&q(a,_indeterminate,!1));E(a,e,b,d)}c[n]&&l(a,_cursor,!0)&&g.find("."+D).css(_cursor,"default");g[_add](C||l(a,b)||"");g.attr("role")&&!v&&g.attr("aria-"+(w?n:k),"true");
g[_remove](G||l(a,t)||"")}function q(a,b,d){var c=a[0],g=a.parent(),e=b==k,f=b==_indeterminate,m=b==n,t=f?_determinate:e?z:"enabled",q=l(a,t+u(c[_type])),r=l(a,b+u(c[_type]));if(!1!==c[b]){if(f||!d||"force"==d)c[b]=!1;E(a,e,t,d)}!c[n]&&l(a,_cursor,!0)&&g.find("."+D).css(_cursor,"pointer");g[_remove](r||l(a,b)||"");g.attr("role")&&!f&&g.attr("aria-"+(m?n:k),"false");g[_add](q||l(a,t)||"")}function F(a,b){if(a.data(m)){a.parent().html(a.attr("style",a.data(m).s||""));if(b)a[_callback](b);a.off(".i").unwrap();
f(_label+'[for="'+a[0].id+'"]').add(a.closest(_label)).off(".i")}}function l(a,b,f){if(a.data(m))return a.data(m).o[b+(f?"":"Class")]}function u(a){return a.charAt(0).toUpperCase()+a.slice(1)}function E(a,b,f,c){if(!c){if(b)a[_callback]("ifToggled");a[_callback]("ifChanged")[_callback]("if"+u(f))}}var m="iCheck",D=m+"-helper",r="radio",k="checked",z="un"+k,n="disabled";_determinate="determinate";_indeterminate="in"+_determinate;_update="update";_type="type";_click="click";_touch="touchbegin.i touchend.i";
_add="addClass";_remove="removeClass";_callback="trigger";_label="label";_cursor="cursor";_mobile=/ipad|iphone|ipod|android|blackberry|windows phone|opera mini|silk/i.test(navigator.userAgent);f.fn[m]=function(a,b){var d='input[type="checkbox"], input[type="'+r+'"]',c=f(),g=function(a){a.each(function(){var a=f(this);c=a.is(d)?c.add(a):c.add(a.find(d))})};if(/^(check|uncheck|toggle|indeterminate|determinate|disable|enable|update|destroy)$/i.test(a))return a=a.toLowerCase(),g(this),c.each(function(){var c=
f(this);"destroy"==a?F(c,"ifDestroyed"):B(c,!0,a);f.isFunction(b)&&b()});if("object"!=typeof a&&a)return this;var e=f.extend({checkedClass:k,disabledClass:n,indeterminateClass:_indeterminate,labelHover:!0},a),l=e.handle,w=e.hoverClass||"hover",t=e.focusClass||"focus",u=e.activeClass||"active",C=!!e.labelHover,x=e.labelHoverClass||"hover",p=(""+e.increaseArea).replace("%","")|0;if("checkbox"==l||l==r)d='input[type="'+l+'"]';-50>p&&(p=-50);g(this);return c.each(function(){var a=f(this);F(a);var c=this,
b=c.id,g=-p+"%",d=100+2*p+"%",d={position:"absolute",top:g,left:g,display:"block",width:d,height:d,margin:0,padding:0,background:"#fff",border:0,opacity:0},g=_mobile?{position:"absolute",visibility:"hidden"}:p?d:{position:"absolute",opacity:0},l="checkbox"==c[_type]?e.checkboxClass||"icheckbox":e.radioClass||"i"+r,A=f(_label+'[for="'+b+'"]').add(a.closest(_label)),v=!!e.aria,z=m+"-"+Math.random().toString(36).substr(2,6),h='<div class="'+l+'" '+(v?'role="'+c[_type]+'" ':"");v&&A.each(function(){h+=
'aria-labelledby="';this.id?h+=this.id:(this.id=z,h+=z);h+='"'});h=a.wrap(h+"/>")[_callback]("ifCreated").parent().append(e.insert);d=f('<ins class="'+D+'"/>').css(d).appendTo(h);a.data(m,{o:e,s:a.attr("style")}).css(g);e.inheritClass&&h[_add](c.className||"");e.inheritID&&b&&h.attr("id",m+"-"+b);"static"==h.css("position")&&h.css("position","relative");B(a,!0,_update);if(A.length)A.on(_click+".i mouseover.i mouseout.i "+_touch,function(b){var d=b[_type],e=f(this);if(!c[n]){if(d==_click){if(f(b.target).is("a"))return;
B(a,!1,!0)}else C&&(/ut|nd/.test(d)?(h[_remove](w),e[_remove](x)):(h[_add](w),e[_add](x)));if(_mobile)b.stopPropagation();else return!1}});a.on(_click+".i focus.i blur.i keyup.i keydown.i keypress.i",function(b){var d=b[_type];b=b.keyCode;if(d==_click)return!1;if("keydown"==d&&32==b)return c[_type]==r&&c[k]||(c[k]?q(a,k):y(a,k)),!1;if("keyup"==d&&c[_type]==r)!c[k]&&y(a,k);else if(/us|ur/.test(d))h["blur"==d?_remove:_add](t)});d.on(_click+" mousedown mouseup mouseover mouseout "+_touch,function(b){var d=
b[_type],e=/wn|up/.test(d)?u:w;if(!c[n]){if(d==_click)B(a,!1,!0);else{if(/wn|er|in/.test(d))h[_add](e);else h[_remove](e+" "+u);if(A.length&&C&&e==w)A[/ut|nd/.test(d)?_remove:_add](x)}if(_mobile)b.stopPropagation();else return!1}})})}})(window.jQuery||window.Zepto);