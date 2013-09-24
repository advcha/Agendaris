/*
   Milonic DHTML Menu Frames Based Navigation Module  mm_navframe.js version 2.3 - November 17 2009
   Original version written by Kevin Clements
   This module is only compatible with the Milonic DHTML Menu version 5.62 or higher

   Copyright 2007 (c) Milonic Solutions Limited. All Rights Reserved.
   This is a commercial software product, please visit http://www.milonic.com/ for more information.
   
   This code should be loaded into the "nav" or "navigation" frame, where the Main Menu will be displayed.
*/

var mm_si;
var mm_tf;

function openSubmenu(){
	mm_si = _itemRef;
	if(_mi[mm_si][34]=='disabled')return;
	mm_tf = parent.frames[_mi[mm_si][35]];
	var subTop, subLeft;
	var selectedPos = $D($c("el" + mm_si));
	var mNum=mm_tf.$h(_mi[mm_si][3])
	var menuObj = mm_tf.$c("menu" + mNum);
	if(menuObj){
		subTop=selectedPos[0]+_subOffsetTop+mm_tf._sT;
		subLeft=selectedPos[1]+_subOffsetLeft+mm_tf._sL;
  		var Fm=mm_tf._m[mNum];
		if(Fm[2])subTop+=$x(Fm[2]);
  		if(Fm[3])subLeft+=$x(Fm[3]);
		_ofMT=0;
		if(ns7&&mm_tf.fixMozillaZIndex){
			subTop-=mm_tf._sT;
			subLeft-=mm_tf._sL;
		}

		mm_tf._mi.opener=mm_si
		mm_tf._mi.openerF=window

		mm_tf.popup(_mi[mm_si][3],2,subTop,subLeft);
		if(_mi[mm_si][62]&&_mi[mm_si][62].match("openSubmenu\(\)"))e$(_itemRef)
	}
}

function closeSubmenu(){
	setTimeout(function(){
		if(mm_tf&&mm_tf._itemRef==-1){
			if(_itemRef==-1){
				mm_tf.popdown();
			}
			else if(!_mi[_itemRef][3]){
				mm_tf.$Z()
			}
		}
	},200)
}

function resetFramesMenus(){
	if(mm_tf)mm_tf.$Z();
}

/* Add this code to the bottom of your "BODY" menu_data.js file to enable main menu item highlighting 


function hFPI(_iFFrame){
	var pFi=parent.frames[_iFFrame]
	
	if(!pFi.$h)return
	
	for(var _c=0;_c<_cip.length;_c++){
		var _ci=_cip[_c];
		var i=$Ff(_ci);
		if(i==-1)i=_ci
		if(i+" "!=$u){
			while(i!=-1){
				var I=_mi[i]
				_omni=i
				i=$Ff(i);
				if(i==_omni||i+" "==$u)i=-1
			}
			menuName=_m[_mi[_omni][0]][1]
			P=parent.frames[1]
			
			PM=P.$h(menuName)
			
			for(var a=0;a<P._mi.length;a++){
				if(P._mi[a][3]==menuName)
				{
					_iFItem=a;
					pFi.$Cw(pFi._mi[_iFItem],_iFItem);
					setTimeout(function(){pFi.e$(_iFItem)},20);
					setTimeout(function(){pFi.d$(_iFItem)},50);
				}
			}
		}
	}
}

function highlightFramesParentItems(){
	for(var a=0;a<parent.frames.length;a++)hFPI(a)
}
		
highlightFramesParentItems()




*/