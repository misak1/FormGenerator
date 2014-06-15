/****************************************************************
* 機　能： 値変更スライダー
****************************************************************/
var dY = 10;			//バーに対するスライダーの表示位置(y)
var dX1 = 11;			//バーに対するスライダーの表示位置(x,最も左)
var dX2 = 202;			//バーに対するスライダーの表示位置(x,最も右)

var valx = 230;			//バーに対する数値を表示する位置(x)
var valy = 10;			//バーに対する数値を表示する位置(y)

var minValue = 0;		//最小値
var maxValue = 6;		//最大値
var iniValue = 6;		//初期値

var M_flag = false;
var mdx,mdlayx,mmx,mmlayx;
var xmax,xmin;

var aryParamKey = ["1時間", "2時間", "4時間 ","6時間","8時間","12時間","24時間"];
var aryParamValue = ["3600", "7200", "14400 ","21600","28800","43200","86400"];

function initSilder(){

	// 位置修正
	var left = GetLeft(document.getElementById("main00"));
	var top = GetTop(document.getElementById("main00"));
	var iniX = left;			//バーの初期位置(x)
	var iniY = top;			//バーの初期位置(y)
	
	dx = dX2 - dX1;
	x = dx * ((iniValue - minValue) / (maxValue - minValue));
	xmin = iniX + dX1;
	xmax = iniX + dX2;
	if(document.all){
		document.all("iLf").onmousedown = Mdown;
		document.onmouseup = Mup;
		document.onmousemove = Mmove;
		document.all("iLf").style.posLeft = xmin + x;
		document.all("iLf").style.posTop = iniY + dY;
		document.all("iLb").style.posLeft = iniX;
		document.all("iLb").style.posTop = iniY;
		document.all("iLv").style.posLeft = iniX + valx;
		document.all("iLv").style.posTop = iniY + valy;
		document.all("iLv").innerHTML = iniValue;
		document.all("iLpv").innerHTML = aryParamKey[iniValue];;
	}else if(document.layers){
	}else if(document.getElementById){
		document.getElementById("iLf").onmousedown = Mdown;
		document.onmouseup = Mup;
		document.onmousemove = Mmove;
		document.getElementById("iLf").style.left = xmin + x;
		document.getElementById("iLf").style.top = iniY + dY;
		document.getElementById("iLb").style.left = iniX;
		document.getElementById("iLb").style.top = iniY;
		document.getElementById("iLv").style.left = iniX + valx;
		document.getElementById("iLv").style.top = iniY + valy;
		document.getElementById("iLv").innerHTML = iniValue;
		document.getElementById("iLpv").innerHTML = aryParamKey[iniValue];
	}
}

function Mdown(e){
	if(document.all){
		mdx = event.x;
		mdlayx = document.all("iLf").style.posLeft;
	}else if(document.layers){
	}else if(document.getElementById){
		mdx = parseInt(e.pageX);
		mdlayx = parseInt(document.getElementById("iLf").style.left);
	}
	M_flag = true;
	return false;
}

function Mup(e){
	M_flag = false;
	return false;
}

function Mmove(e){
	if(document.all){
		if(M_flag){
			mmx = event.x;
			mmlayx = (mmx - mdx) + mdlayx;
			if(mmlayx < xmin) mmlayx = xmin;
			if(mmlayx > xmax) mmlayx = xmax;
			x = Math.floor((mmlayx - xmin) / dx * (maxValue - minValue) + minValue);
			document.all("iLf").style.posLeft = mmlayx;
			document.all("iLv").innerHTML = x;
			document.all("iLpv").innerHTML = aryParamKey[x];
		}
		event.returnValue = false;
	}else if(document.layers){
	}else if(document.getElementById){
		if(M_flag){
			mmx = parseInt(e.pageX);
			mmlayx = (mmx - mdx) + mdlayx;
			if(mmlayx < xmin) mmlayx = xmin;
			if(mmlayx > xmax) mmlayx = xmax;
			x = Math.floor((mmlayx - xmin) / dx * (maxValue - minValue) + minValue);
			document.getElementById("iLf").style.left = mmlayx;
			document.getElementById("iLv").innerHTML = x;
			document.getElementById("iLpv").innerHTML = aryParamKey[x];
		}
		return false;
	}
}