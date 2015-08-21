/*
  ================================================
  PVII Scroll Magic scripts
  Copyright (c) 2007 Project Seven Development
  www.projectseven.com
  Version:  1.0.5 - script build: 1-23
  ================================================
 */

var p7VSCi=false,p7VSCctl=[],p7vscobj,p7vscofY,p7vscuA=navigator.userAgent.toLowerCase();
function P7_setVSC(){
	var h;
	if(!document.getElementById){
		return;
	}
	h='\n<st'+'yle type="text/css">\n';
	h+='.p7VSC_scrollbox {overflow:hidden;}\n';
	h+='.p7VSC_scrolling {position:absolute;}\n';
	h+='.p7VSCdragchannel, .p7VSCtoolbar{display: block !important;}\n';
	h+='</s'+'tyle>';
	document.write(h);
}
function P7_VSCaddLoad(){
	if(!document.getElementById){
		return;
	}
	if(window.addEventListener){
		window.addEventListener("load",P7_initVSC,false);
	}
	else if(window.attachEvent){
		window.attachEvent("onload",P7_initVSC);
	}
	else if(typeof window.onload=='function'){
		var p7vloadit=onload;
		window.onload=function(){
			p7vloadit();
			P7_initVSC();
		};
	}
	else{
		window.onload=P7_initVSC;
	}
	p7VSCi=true;
}
P7_setVSC();
function P7_opVSC(){
	var h='',hh,b,cn;
	if(!document.getElementById){
		return;
	}
	p7VSCctl[p7VSCctl.length]=arguments;
	hh=arguments[6];
	b=arguments[0];
	cn=b.replace("b","cn");
	h='\n<st'+'yle type="text/css">\n';
	h+='#'+b+'{height:'+hh+'px;}\n';
	h+='#'+cn+'{height:'+hh+'px;}\n';
	h+='</s'+'tyle>';
	document.write(h);
	if(!p7VSCi){
		P7_VSCaddLoad();
	}
}
function P7_initVSC(){
	var i,j,tB,d,sD,t,oh,dB,pp,dD,h,sf;
	for(i=0;i<p7VSCctl.length;i++){
		tB=document.getElementById(p7VSCctl[i][0]);
		if(tB){
			tB.p7opt=p7VSCctl[i];
			tB.p7acdv='';
			tB.p7dragbar=false;
			tB.p7resume='no';
			tB.p7status='none';
			tB.p7box=tB.id;
			d=tB.id.replace('b','d')+'_c'+tB.p7opt[8];
			sD=document.getElementById(d);
			if(sD){
				t=tB.p7opt[9];
				sD.style.top=t+'px';
				tB.p7acdv=d;
				if(t<tB.offsetHeight*-1){
					tB.p7dir='down';
				}
				else{
					tB.p7dir='up';
				}
				oh=tB.p7opt[6];
				d=tB.id.replace("b","dc");
				dB=document.getElementById(d);
				if(dB){
					pp=dB.parentNode.childNodes;
					for(j=0;j<pp.length;j++){
						if(pp[j].nodeName=='DIV'&&pp[j]!=dB){
							h=pp[j].offsetHeight;
							if(!h||h===0){
								if(p7vscuA.indexOf("applewebkit")>-1){
									sf=P7_fixSafDB(tB);
								}
								h=parseInt(P7_getPropValue(pp[j].getElementsByTagName("A")[0],'height','height'),10);
							}
							h=(h>0)?h:0;
							oh-=h;
						}
					}
					dB.style.height=oh+"px";
					if(sf){
						sf.style.display="none";
					}
				}
				if(tB.p7opt[7]==1){
					tB.onmouseover=function(){
						if(this.p7status=='moving'){
							this.p7resume='yes';
						}
						P7_VSCpause(this,1);
					};
					tB.onmouseout=function(){
						if(this.p7resume=='yes'){
							P7_VSCplay(this,1);
						}
					};
				}
				dD=getBoxChild(tB.id,"a",true);
				if(dD){
					dD.p7status='show';
					dD.onclick=function(){
						return P7_VSCshowall(this);
					};
				}
				dD=getBoxChild(tB.id,"db",true);
				dD=getBoxChild(tB.id,"dc",true);
				if(dD){
					dDa=dD.getElementsByTagName("A")[0];
					tB.p7dragbar=d;
					tB.p7dragbar=d;
					if(tB.p7opt[14]===1){
						dDa.removeAttribute("href");
					}
					else{
						dDa.onmousedown=P7_VSCeng;
						dDa.onkeydown=P7_VSCkey;
						dDa.onkeyup=P7_VSCkeyup;
						dD.onmousedown=P7_VSCeng;
					}
					P7VSCsetDrag(tB);
				}
				dD=getBoxChild(tB.id,"du",true);
				if(dD){
					dD.onmousedown=function(){
						P7_VSCmoveUp(this);
					};
					dD.onmouseup=function(){
						P7_VSCpause(this);
					};
					dD.onkeydown=P7_VSCkey;
					dD.onkeyup=P7_VSCkeyup;
				}
				dD=getBoxChild(tB.id,"dd",true);
				if(dD){
					dD.onmousedown=function(){
						P7_VSCmoveDown(this);
					};
					dD.onmouseup=function(){
						P7_VSCpause(this);
					};
					dD.onkeydown=P7_VSCkey;
					dD.onkeyup=P7_VSCkeyup;
				}
				dD=getBoxChild(tB.id,"bu",true);
				if(dD){
					dD.onmousedown=function(){
						P7_VSCmoveUp(this);
					};
					dD.onkeydown=P7_VSCkey;
					if(tB.p7opt[3]<3){
						dD.onmouseup=function(){
							P7_VSCpause(this);
						};
						dD.onkeyup=P7_VSCkeyup;
					}
				}
				dD=getBoxChild(tB.id,"bd",true);
				if(dD){
					dD.onmousedown=function(){
						P7_VSCmoveDown(this);
					};
					dD.onkeydown=P7_VSCkey;
					if(tB.p7opt[3]<3){
						dD.onmouseup=function(){
							P7_VSCpause(this);
						};
						dD.onkeyup=P7_VSCkeyup;
					}
				}
				dD=getBoxChild(tB.id,"bpp",true);
				if(dD){
					dD.onmousedown=function(){
						P7_VSCpp(this);
					};
					dD.onkeydown=P7_VSCppkey;
				}
				tB.accum=0;
				tB.autostarting=false;
				tB.p7vscMode='manual';
				if(tB.p7opt[10]==1){
					tB.p7vscMode='auto';
					tB.p7status='moving';
					tB.p7VSCtimer=setTimeout("P7_VSCplay('"+tB.id+"')",tB.p7opt[11]);
				}
			}
		}
	}
	P7_VSCaddEvts();
}
function getBoxChild(bx,rp,fl){
	var d,ret;
	d=bx.replace("b",rp);
	ret=document.getElementById(d);
	if(ret&&fl){
		ret.p7box=bx;
	}
	return ret;
}
function P7_VSCaddEvts(){
	if(window.addEventListener){
		document.addEventListener("mousemove",P7_VSCdrg,false);
		document.addEventListener("mouseup",P7_VSCrel,false);
		document.addEventListener("DOMMouseScroll",P7_VSCwheel,false);
		if(window.opera || p7vscuA.indexOf("applewebkit")>-1){
			document.addEventListener("mousewheel",P7_VSCwheel,false);
		}
	}
	else if(window.attachEvent){
		document.attachEvent("onmousemove",P7_VSCdrg);
		document.attachEvent("onmouseup",P7_VSCrel);
		document.attachEvent("onmousewheel",P7_VSCwheel);
	}
	else{
		document.onmousemove=P7_VSCdrg;
		document.onmouseup=P7_VSCrel;
	}
}
function P7_VSCshowall(a){
	var b,tB,tD,tC,tT,mv;
	b=a.p7box;
	tB=document.getElementById(b);
	tD=document.getElementById(tB.p7acdv);
	tC=getBoxChild(tB.id,"cn");
	tT=getBoxChild(tB.id,"tb");
	mv=tB.p7status;
	if(a.p7status=="show"){
		P7_VSCpause(b);
		tB.p7restore=mv;
		a.p7status="restore";
		a.innerHTML="Restore Scroller";
		a.setAttribute("title","Restore Scroller");
		tB.style.height="auto";
		tD.style.position="static";
		if(tC){
			tC.style.visibility="hidden";
		}
		if(tT){
			tT.style.visibility="hidden";
		}
	}
	else{
		a.p7status="show";
		a.innerHTML="Show All";
		a.setAttribute("title","Show All Scroller Content");
		tB.style.height=tB.p7opt[6]+"px";
		tD.style.position="absolute";
		if(tC){
			tC.style.visibility="visible";
		}
		if(tT){
			tT.style.visibility="visible";
		}
		if(tB.p7restore=='moving'){
			P7_VSCplay(tB);
		}
	}
	return false;
}
function P7_VSCplay(b,ov){
	var tB,tS,t,ct,bh,sh,dy;
	if(typeof(b)=='object'){
		b=b.p7box;
	}
	tB=document.getElementById(b);
	tB.p7vscMode='auto';
	P7_VSCpause(b,ov);
	tS=document.getElementById(tB.p7acdv);
	bh=tB.offsetHeight;
	sh=tS.offsetHeight;
	t=bh-sh;
	dy=tB.p7opt[2];
	if(t>=0){
		return;
	}
	ct=parseInt(tS.style.top,10);
	if(ct==t){
		if(tB.p7opt[3]===0 || tB.p7opt[3]==3){
			ct=0;
			P7_VSCmoveTo(tB.p7box,ct);
			dy=(tB.p7opt[3]==3)?tB.p7opt[13]:1000;
		}
	}
	t=(tB.p7dir=='up')?t:0;
	if(tB.p7opt[3]==2){
		t=t-bh;
		if(ct<t){
			ct=bh;
		}
		else if(ct>bh){
			ct=bh;
		}
		tS.style.top=ct+"px";
		tB.p7dir='up';
	}
	if(tB.p7opt[3]>2){
		var m=true;
		var x=tB.p7opt[12];
		while (m){
			if(ct>x){
				m=false;
				if(tB.p7dir=='up'){
					tB.accum=(x+tB.p7opt[12])-ct;
				}
				else{
					tB.accum=ct - x;
				}
			}
			if(x<=(tB.offsetHeight-tS.offsetHeight)){
				m=false;
			}
			x-=tB.p7opt[12];
		}
	}
	P7_VSCspp(b,'play');
	if(tB.p7VSCtimer){
		clearTimeout(tB.p7VSCtimer);
	}
	tB.p7VSCtimer=setTimeout("P7_VSCscroll('"+tB.id+"',"+ct+","+t+","+false+")",dy);
}
function P7_VSCpp(b){
	var a,cl;
	if(typeof(b)=='object'){
		b=b.p7box;
	}
	a=getBoxChild(b,"bpp");
	cl=a.className;
	if(a.className=='pause'){
		a.className='play';
		P7_VSCpause(b);
	}
	else{
		a.className='pause';
		P7_VSCplay(b);
	}
}
function P7_VSCspp(b,m){
	var a=getBoxChild(b,"bpp");
	if(a&&a.className&&a.className==m){
		a.className=(m=='play')?'pause':'play';
	}
}
function P7_VSCpause(b,ov){
	if(typeof(b)=='object'){
		b=b.p7box;
	}
	var dB=document.getElementById(b);
	if(dB.p7VSCtimer){
		clearTimeout(dB.p7VSCtimer);
		dB.p7status='stopped';
	}
	if(ov!=1){
		dB.p7resume='no';
	}
	P7_VSCspp(b,'pause');
}
function P7_VSCctrl(op,b,y){
	if(op=='pause'){
		P7_VSCpause(b);
	}
	else if(op=='play'){
		P7_VSCplay(b);
	}
	else if(op=='scrollUp'){
		P7_VSCmoveUp(b);
	}
	else if(op=='scrollDown'){
		P7_VSCmoveDown(b);
	}
	else if(op=='panelUp'){
		P7_VSCmoveBy(b,'up');
	}
	else if(op=='panelDown'){
		P7_VSCmoveBy(b,'down');
	}
	else if(y&&op=='moveBy'){
		P7_VSCmoveBy(b,y);
	}
	else if(y&&op=='goTo'){
		P7_VSCmoveTo(b,y);
	}
	else if(op=='goToElement'){
		P7_VSCmovetoId(b);
	}
}
function P7_VSCmovetoId(d){
	var tB,tS,ct,tD,pp,tt,y=0,m=false,bx;
	pp=document.getElementById(d);
	while(pp){
		y+=pp.offsetTop;
		if(pp.className&&pp.className=='p7VSC_scrolling'){
			m=true;
			break;
		}
		pp=pp.offsetParent;
	}
	if(m){
		tB=pp.parentNode;
		tS=document.getElementById(tB.p7acdv);
		ct=parseInt(tS.style.top,10);
		tt=ct-y;
		P7_VSCmoveTo(tB.id,tt);
	}
}
function P7_VSCwheel(evt){
	var g,m=false,r=true,delta=0,s,tS;
	evt=(evt)?evt:event;
	g=(evt.target)?evt.target:evt.srcElement;
	while(g){
		if(g.id&&g.id.indexOf("p7VSCb_")>-1){
			m=true;
			break;
		}
		g=g.parentNode;
	}
	if(m){
		tS=document.getElementById(g.p7acdv);
		if(tS.offsetHeight>g.offsetHeight){
			r=false;
			if(evt.wheelDelta){
				delta=evt.wheelDelta/120;
				if(window.opera&&parseFloat(navigator.appVersion)<9.20){
					delta=delta*-1;
				}
			}
			else if(evt.detail){
				delta= -evt.detail/3;
			}
			s=delta*16;
			P7_VSCmoveBy(g.id,s);
			if(evt.preventDefault){
				evt.preventDefault();
			}
		}
	}
	return r;
}
function P7_VSCmoveBy(b,y){
	var tS,t,tB,rr;
	tB=document.getElementById(b);
	if(tB.p7status!="stopped"){
		P7_VSCpause(b);
	}
	tS=document.getElementById(tB.p7acdv);
	rr=tB.offsetHeight-tS.offsetHeight;
	if(rr>=0){
		return;
	}
	if(y=='down'){
		y=tB.offsetHeight*-1;
	}
	if(y=='up'){
		y=tB.offsetHeight;
	}
	if(rr<0){
		t=parseInt(tS.style.top,10);
		t+=y;
		t=(t<=rr)?rr:t;
		t=(t>=0)?0:t;
		tS.style.top=t+"px";
		if(tB.p7dragbar){
			P7VSCsetDrag(tB);
		}
	}
}
function P7_VSCmoveTo(b,y){
	var tB,tS,rr,t;
	P7_VSCpause(b);
	tB=document.getElementById(b);
	tS=document.getElementById(tB.p7acdv);
	rr=tB.offsetHeight-tS.offsetHeight;
	if(rr>=0){
		return;
	}
	if(y=='start'){
		y=0;
	}
	else if(y=='end'){
		y=rr;
	}
	if(rr<0){
		t=parseInt(tS.style.top,10);
		y=(y<=rr)?rr:y;
		y=(y>=0)?0:y;
		tS.style.top=y+"px";
		if(tB.p7dragbar){
			P7VSCsetDrag(tB);
		}
	}
}
function P7_VSCmoveUp(b){
	var tS,t,tB,fl=1,a;
	if(typeof(b)=='object'){
		a=b;
		b=b.p7box;
	}
	P7_VSCpause(b);
	tB=document.getElementById(b);
	tS=document.getElementById(tB.p7acdv);
	if(tS.offsetHeight<=tB.offsetHeight){
		return;
	}
	if(tB.p7opt[3]>2){
		if(a&&a.id&&a.id.indexOf("p7VSCbu_")>-1){
			fl=2;
		}
	}
	P7_VSCscroll(tB.id,parseInt(tS.style.top,10),0,fl);
}
function P7_VSCmoveDown(b){
	var tS,t,tB,fl=1,a;
	P7_VSCpause(b);
	if(typeof(b)=='object'){
		a=b;
		b=b.p7box;
	}
	tB=document.getElementById(b);
	tS=document.getElementById(tB.p7acdv);
	t=tB.offsetHeight-tS.offsetHeight;
	if(t>=0){
		return;
	}
	if(tB.p7opt[3]>2){
		if(a&&a.id&&a.id.indexOf("p7VSCbd_")>-1){
			fl=2;
		}
	}
	P7_VSCscroll(tB.id,parseInt(tS.style.top,10),t,fl);
}
function P7_VSCscroll(b,ct,tt,dd){
	var fr,dy,dB,dD,nt,dr,r,m=true,op;
	if(!dd){
		dd=false;
	}
	dB=document.getElementById(b);
	dD=document.getElementById(dB.p7acdv);
	dB.p7status='moving';
	op=dB.p7opt[3];
	r=dB.offsetHeight-dD.offsetHeight;
	if(r>=0){
		return;
	}
	if(!dd){
		fr=dB.p7opt[1];
		dy=dB.p7opt[2];
	}
	else{
		fr=dB.p7opt[4];
		dy=dB.p7opt[5];
	}
	if(tt!==0){
		if(op>2&&dd!==1){
			dB.accum+=fr;
			if(dB.accum>=dB.p7opt[12]){
				fr-=dB.accum-dB.p7opt[12];
				dB.accum=0;
				m=false;
			}
		}
		ct-=fr;
		if(ct<=tt){
			ct=tt;
			m=false;
		}
	}
	else{
		if(dd!=1&&op>2){
			dB.accum+=fr;
			if(dB.accum>=dB.p7opt[12]){
				fr-=dB.accum-dB.p7opt[12];
				dB.accum=0;
				m=false;
			}
		}
		ct+=fr;
		if(ct>=tt){
			ct=tt;
			m=false;
		}
	}
	dD.style.top=ct+"px";
	if(dB.p7dragbar){
		P7VSCsetDrag(dB);
	}
	if(!m&&dd!==1){
		if(op>2){
			dB.accum=0;
			dy=dB.p7opt[13];
			if(dd!==2){
				if(ct!==0&&ct!=r){
					m=true;
				}
			}
			if(op==4 && (ct===0||ct==r)){
				op=1;
			}
		}
		if(op==1){
			tt=(ct===0)?r:0;
			dB.p7dir=(tt===0)?'down':'up';
			if(dd!==2){
				m=true;
			}
		}
		else if(op==2){
			ct=dB.offsetHeight;
			dB.p7dir='up';
			m=true;
		}
	}
	if(m){
		dB.p7VSCtimer=setTimeout("P7_VSCscroll('"+b+"',"+ct+","+tt+","+dd+")",dy);
	}
	else{
		dB.p7status='stopped';
		P7_VSCpause(dB.p7box);
	}
}
function P7_VSCkey(evt){
	var tg,m=true;
	evt=(evt)?evt:event;
	tg=(evt.target)?evt.target:evt.srcElement;
	if(tg&&tg.p7box){
		if(evt.keyCode==38){
			P7_VSCmoveUp(tg.p7box);
			m=false;
		}
		else if(evt.keyCode==40){
			P7_VSCmoveDown(tg.p7box);
			m=false;
		}
		else if(evt.keyCode==33||evt.keyCode==37||(evt.keyCode==32&&evt.shiftKey)){
			P7_VSCmoveBy(tg.p7box,'up');
			m=false;
		}
		else if(evt.keyCode==34||evt.keyCode==39||evt.keyCode==32){
			P7_VSCmoveBy(tg.p7box,'down');
			m=false;
		}
		else if(evt.keyCode==36){
			P7_VSCmoveTo(tg.p7box,'start');
			m=false;
		}
		else if(evt.keyCode==35){
			P7_VSCmoveTo(tg.p7box,'end');
			m=false;
		}
		if(!m){
			if(evt.preventDefault){
				evt.preventDefault();
			}
		}
	}
	return m;
}
function P7_VSCkeyup(evt){
	evt=(evt)?evt:event;
	tg=(evt.target)?evt.target:evt.srcElement;
	if(tg&&tg.p7box){
		if(evt.keyCode!=9&&evt.keyCode!=16){
			P7_VSCpause(tg.p7box);
		}
	}
}
function P7_VSCppkey(evt){
	var tg;
	evt=(evt)?evt:event;
	tg=(evt.target)?evt.target:evt.srcElement;
	if(tg&&tg.p7box){
		if(evt.keyCode==13){
			P7_VSCpp(tg.p7box);
		}
	}
}
function P7_VSCeng(evt){
	var tg,y,tD,g,ot=0,pp,yy,oh,m=true,dr;
	evt=(evt)?evt:event;
	p7vscobj=null;
	tg=(evt.target)?evt.target:evt.srcElement;
	g=tg.parentNode;
	if(evt.clientY){
		if(tg&&tg.id&&tg.id.indexOf('p7VSCdc_')>-1){
			g=document.getElementById(tg.id.replace("dc","db"));
			P7_VSCpause(g.p7box);
			oh=tg.offsetHeight;
			pp=tg;
			while(pp){
				ot+=pp.offsetTop;
				pp=pp.offsetParent;
			}
			y=(evt.clientY+document.documentElement.scrollTop+document.body.scrollTop)-ot;
			dr='down';
			if(y<=g.offsetTop){
				dr='up';
			}
			P7_VSCmoveBy(g.p7box,dr);
			m=false;
		}
		else if(g&&g.id&&g.id.indexOf('p7VSCdb_')>-1){
			p7vscobj=g;
			P7_VSCpause(g.p7box);
			y=(p7vscobj.offsetTop)?p7vscobj.offsetTop:0;
			p7vscofY=evt.clientY-y;
			m=false;
			if(!document.addEventListener&&document.attachEvent){
				g.setCapture();
			}
		}
	}
	return m;
}
function P7_VSCdrg(evt){
	evt=(evt)?evt:event;
	var m=true;
	if(p7vscobj){
		if(evt.clientY){
			P7_VSCshift(p7vscobj,(evt.clientY-p7vscofY));
		}
		evt.cancelBubble=true;
		m=false;
	}
	return m;
}
function P7_VSCrel(){
	if(p7vscobj){
		if(!document.addEventListener&&document.attachEvent){
			p7vscobj.releaseCapture();
		}
		p7vscobj=null;
	}
}
function P7_VSCshift(obj,y){
	var tC,d,b,bT,s,sT,bh,sh,p,yy,r,rr;
	d=obj.id.replace("db","dc");
	tC=document.getElementById(d);
	b=obj.id.replace("db","b");
	bT=document.getElementById(b);
	r=tC.offsetHeight-obj.offsetHeight;
	y=(y<=0)?0:y;
	y=(y>=r)?r:y;
	s=bT.p7acdv;
	sT=document.getElementById(s);
	rr=bT.offsetHeight-sT.offsetHeight;
	if(rr>=0){
		y=0;
		rr=0;
	}
	p=y/r;
	yy=parseInt(rr*p,10);
	obj.style.top=y+"px";
	sT.style.top=yy+"px";
}
function P7VSCsetDrag(sB){
	var dC,s,dB,y,rr,r,p,sD;
	if(sB.p7dragbar){
		dC=document.getElementById(sB.p7dragbar);
		s=dC.id.replace("dc","db");
		dB=document.getElementById(s);
		sD=document.getElementById(sB.p7acdv);
		y=parseInt(sD.style.top,10);
		rr=sB.offsetHeight-sD.offsetHeight;
		r=dC.offsetHeight-dB.offsetHeight;
		p=y/rr;
		yy=parseInt(r*p,10);
		yy=(yy<=0)?0:yy;
		yy=(yy>=r)?r:yy;
		if(!isNaN(yy)){
			dB.style.top=yy+"px";
		}
	}
}
function P7_getPropValue(ob,prop,prop2){
	var h,v=null;
	if(ob){
		if(ob.currentStyle){
			v=eval('ob.currentStyle.'+prop);
		}
		else if(document.defaultView.getComputedStyle(ob,"")){
			v=document.defaultView.getComputedStyle(ob,"").getPropertyValue(prop2);
		}
		else{
			v=eval("ob.style."+prop);
		}
	}
	return v;
}
function P7_fixSafDB(bx){
	var s,d,pm=false;
	s=bx.id.replace("b","");
	pp=document.getElementById(s);
	pp=pp.parentNode;
	while(pp){
		d=P7_getPropValue(pp,'display','display');
		if(!d || d=='none'){
			if(!pp.id || pp.id.indexOf("p7VSC")==-1){
				pm=pp;
				pp.style.display="block";
				break;
			}
		}
		if(pp.nodeName=='BODY'){
			break;
		}
		pp=pp.parentNode;
	}
	return pm;
}