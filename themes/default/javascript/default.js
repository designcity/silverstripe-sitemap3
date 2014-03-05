/*
	Developed by Robert Nyman
	Code/licensing: http://code.google.com/p/dlite/
	Documentation: http://www.robertnyman.com/dlite
	Version 1.0
*/	
var dLite=function(){var E=/*@cc_on!@*/false;var B=0;var H=false;var A=null;var G=[];var C={};var D=function(){for(var J=0,I=G.length;J<I;J++){try{G[J]();}catch(K){}}G=[];};var F=function(){if(H){return ;}H=true;D();};return{init:function(){window.elm=window.elm||this.elm;window.elmsByClass=window.elmsByClass||this.elmsByClass;window.addClass=window.addClass||this.addClass;window.removeClass=window.removeClass||this.removeClass;window.addEvent=window.addEvent||this.addEvent;window.removeEvent=window.removeEvent||this.removeEvent;window.stopDefault=window.stopDefault||this.stopDefault;window.cancelBubbling=window.cancelBubbling||this.cancelBubbling;window.DOMReady=window.DOMReady||this.DOMReady;if(document.addEventListener){document.addEventListener("DOMContentLoaded",F,false);}if(E){if(document.getElementById){document.write('<script id="ieScriptLoad" defer src="//:"><\/script>');document.getElementById("ieScriptLoad").onreadystatechange=function(){if(this.readyState==="complete"){F.call(this);}};}}if(/KHTML|WebKit|iCab/i.test(navigator.userAgent)){A=setInterval(function(){if(/loaded|complete/i.test(document.readyState)){F.call(this);clearInterval(A);}},10);}window.onload=F;},elm:function(I){return document.getElementById(I);},elmsByClass:function(J,I,K){if(document.getElementsByClassName){this.elmsByClass=function(Q,T,P){P=P||document;var L=P.getElementsByClassName(Q),S=(T)?new RegExp("\\b"+T+"\\b","i"):null,M=[],O;for(var N=0,R=L.length;N<R;N+=1){O=L[N];if(!S||S.test(O.nodeName)){M.push(O);}}return M;};}else{if(document.evaluate){this.elmsByClass=function(U,X,T){X=X||"*";T=T||document;var N=U.split(" "),V="",R="http://www.w3.org/1999/xhtml",W=(document.documentElement.namespaceURI===R)?R:null,O=[],L,M;for(var P=0,Q=N.length;P<Q;P+=1){V+="[contains(concat(' ', @class, ' '), ' "+N[P]+" ')]";}try{L=document.evaluate(".//"+X+V,T,W,0,null);}catch(S){L=document.evaluate(".//"+X+V,T,null,0,null);}while((M=L.iterateNext())){O.push(M);}return O;};}else{this.elmsByClass=function(W,Z,V){Z=Z||"*";V=V||document;var P=W.split(" "),Y=[],L=(Z==="*"&&V.all)?V.all:V.getElementsByTagName(Z),U,R=[],T;for(var Q=0,M=P.length;Q<M;Q+=1){Y.push(new RegExp("(^|\\s)"+P[Q]+"(\\s|$)"));}for(var O=0,X=L.length;O<X;O+=1){U=L[O];T=false;for(var N=0,S=Y.length;N<S;N+=1){T=Y[N].test(U.className);if(!T){break;}}if(T){R.push(U);}}return R;};}}return this.elmsByClass(J,I,K);},addClass:function(K,J){var I=K.className;if(!new RegExp(("(^|\\s)"+J+"(\\s|$)"),"i").test(I)){K.className=I+(I.length?" ":"")+J;}},removeClass:function(K,J){var I=new RegExp(("(^|\\s)"+J+"(\\s|$)"),"i");K.className=K.className.replace(I,function(L){var M="";if(new RegExp("^\\s+.*\\s+$").test(L)){M=L.replace(/(\s+).+/,"$1");}return M;}).replace(/^\s+|\s+$/g,"");},addEvent:function(K,I,J){if(document.addEventListener){this.addEvent=function(N,L,M){N.addEventListener(L,M,false);};}else{this.addEvent=function(P,L,N){if(!P.uniqueHandlerId){P.uniqueHandlerId=B++;}var O=false;if(N.attachedElements&&N.attachedElements[L+P.uniqueHandlerId]){O=true;}if(!O){if(!P.events){P.events={};}if(!P.events[L]){P.events[L]=[];var M=P["on"+L];if(M){P.events[L].push(M);}}P.events[L].push(N);P["on"+L]=dLite.handleEvent;if(!N.attachedElements){N.attachedElements={};}N.attachedElements[L+P.uniqueHandlerId]=true;}};}return this.addEvent(K,I,J);},handleEvent:function(I){var M=I||event;var N=M.target||M.srcElement||document;while(N.nodeType!==1&&N.parentNode){N=N.parentNode;}M.eventTarget=N;var J=this.events[M.type].slice(0);var L=J.length-1;if(L!==-1){for(var K=0;K<L;K++){J[K].call(this,M);}return J[K].call(this,M);}},removeEvent:function(K,I,J){if(document.addEventListener){this.removeEvent=function(N,L,M){N.removeEventListener(L,M,false);};}else{this.removeEvent=function(P,L,O){if(P.events){var M=P.events[L];if(M){for(var N=0;N<M.length;N++){if(M[N]===O){delete M[N];M.splice(N,1);}}}O.attachedElements[L+P.uniqueHandlerId]=null;}};}return this.removeEvent(K,I,J);},stopDefault:function(I){I.returnValue=false;if(I.preventDefault){I.preventDefault();}},cancelBubbling:function(I){I.cancelBubble=true;if(I.stopPropagation){I.stopPropagation();}},DOMReady:function(){for(var J=0,I=arguments.length,K;J<I;J++){K=arguments[J];if(!K.DOMReady&&!C[K]){if(typeof K==="string"){C[K]=true;K=new Function(K);}K.DOMReady=true;G.push(K);}}if(H){D();}}};}();dLite.init();

/* 
	Sitemap Styler v0.1
	written by Alen Grakalic, provided by Css Globe (cssglobe.com)
	visit http://cssglobe.com/lab/sitemap_styler/
*/
this.sitemapstyler = function(){
	var sitemap = elm("sitemap");
	if(sitemap){
		this.listItem = function(li){
			if(li.getElementsByTagName("ul").length > 0){
				var ul = li.getElementsByTagName("ul")[0];
				ul.style.display = "none";
				var span = document.createElement("span");
				span.className = "collapsed";
				span.onclick = function(){
					ul.style.display = (ul.style.display == "none") ? "block" : "none";
					this.className = (ul.style.display == "none") ? "collapsed" : "expanded";
				};
				li.appendChild(span);
			};
		};
		
		var items = sitemap.getElementsByTagName("li");
		for(var i=0;i<items.length;i++){
			listItem(items[i]);
		};
		
	};	
};

DOMReady(function() {sitemapstyler();});