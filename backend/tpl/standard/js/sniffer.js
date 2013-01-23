<!--
var agt=navigator.userAgent.toLowerCase();
var is_major = parseInt(navigator.appVersion);
var is_minor = parseFloat(navigator.appVersion);
var is_nav  = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1) && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1) && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
var is_nav2 = (is_nav && (is_major == 2));
var is_nav3 = (is_nav && (is_major == 3));
var is_nav4 = (is_nav && (is_major == 4));
var is_nav4up = (is_nav && (is_major >= 4));
var is_navonly = (is_nav && ((agt.indexOf(";nav") != -1) || (agt.indexOf("; nav") != -1)));
var is_nav6 = (is_nav && (is_major == 5));
var is_nav6up = (is_nav && (is_major >= 5));
var is_gecko = (agt.indexOf('gecko') != -1);
var is_ie = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
var is_ie3 = (is_ie && (is_major < 4));
var is_ie4 = (is_ie && (is_major == 4) && (agt.indexOf("msie 4")!=-1));
var is_ie4up = (is_ie && (is_major >= 4));
var is_ie5 = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.0")!=-1) );
var is_ie5_5 = (is_ie && (is_major == 4) && (agt.indexOf("msie 5.5") !=-1));
var is_ie5up = (is_ie && !is_ie3 && !is_ie4);
var is_ie5_5up =(is_ie && !is_ie3 && !is_ie4 && !is_ie5);
var is_ie6 = (is_ie && (is_major == 4) && (agt.indexOf("msie 6.")!=-1));
var is_ie6up = (is_ie && !is_ie3 && !is_ie4 && !is_ie5 && !is_ie5_5);
var is_aol = (agt.indexOf("aol") != -1);
var is_aol3 = (is_aol && is_ie3);
var is_aol4 = (is_aol && is_ie4);
var is_aol5 = (agt.indexOf("aol 5") != -1);
var is_aol6 = (agt.indexOf("aol 6") != -1);
var is_opera = (agt.indexOf("opera") != -1);
var is_opera2 = (agt.indexOf("opera 2") != -1 || agt.indexOf("opera/2") != -1);
var is_opera3 = (agt.indexOf("opera 3") != -1 || agt.indexOf("opera/3") != -1);
var is_opera4 = (agt.indexOf("opera 4") != -1 || agt.indexOf("opera/4") != -1);
var is_opera5 = (agt.indexOf("opera 5") != -1 || agt.indexOf("opera/5") != -1);
var is_opera5up = (is_opera && !is_opera2 && !is_opera3 && !is_opera4);

var is_webtv = (agt.indexOf("webtv") != -1);
var is_TVNavigator = ((agt.indexOf("navio") != -1) || (agt.indexOf("navio_aoltv") != -1));
var is_AOLTV = is_TVNavigator;
var is_hotjava = (agt.indexOf("hotjava") != -1);
var is_hotjava3 = (is_hotjava && (is_major == 3));
var is_hotjava3up = (is_hotjava && (is_major >= 3));
var is_js;
if (is_nav2 || is_ie3) is_js = 1.0;
else if (is_nav3) is_js = 1.1;
else if (is_opera5up) is_js = 1.3;
else if (is_opera) is_js = 1.1;
else if ((is_nav4 && (is_minor <= 4.05)) || is_ie4) is_js = 1.2;
else if ((is_nav4 && (is_minor > 4.05)) || is_ie5) is_js = 1.3;
else if (is_hotjava3up) is_js = 1.4;
else if (is_nav6 || is_gecko) is_js = 1.5;
else if (is_nav6up) is_js = 1.5;
else if (is_ie5up) is_js = 1.3
else is_js = 0.0;
var is_win = ((agt.indexOf("win")!=-1) || (agt.indexOf("16bit")!=-1));
var is_win95 = ((agt.indexOf("win95")!=-1) || (agt.indexOf("windows 95")!=-1));
var is_win16 = ((agt.indexOf("win16")!=-1) || (agt.indexOf("16bit")!=-1) || (agt.indexOf("windows 3.1")!=-1) || (agt.indexOf("windows 16-bit")!=-1));
var is_win31 = ((agt.indexOf("windows 3.1")!=-1) || (agt.indexOf("win16")!=-1) || (agt.indexOf("windows 16-bit")!=-1));
var is_winme = ((agt.indexOf("win 9x 4.90")!=-1));
var is_win2k = ((agt.indexOf("windows nt 5.0")!=-1));
var is_win98 = ((agt.indexOf("win98")!=-1) || (agt.indexOf("windows 98")!=-1));
var is_winnt = ((agt.indexOf("winnt")!=-1) || (agt.indexOf("windows nt")!=-1));
var is_win32 = (is_win95 || is_winnt || is_win98 || ((is_major >= 4) && (navigator.platform == "Win32")) || (agt.indexOf("win32")!=-1) || (agt.indexOf("32bit")!=-1));

var is_os2 = ((agt.indexOf("os/2")!=-1) || (navigator.appVersion.indexOf("OS/2")!=-1) || (agt.indexOf("ibm-webexplorer")!=-1));
var is_mac = (agt.indexOf("mac")!=-1);
if (is_mac && is_ie5up) is_js = 1.4;
var is_mac68k = (is_mac && ((agt.indexOf("68k")!=-1) || (agt.indexOf("68000")!=-1)));
var is_macppc = (is_mac && ((agt.indexOf("ppc")!=-1) || (agt.indexOf("powerpc")!=-1)));
var is_sun = (agt.indexOf("sunos")!=-1);
var is_sun4 = (agt.indexOf("sunos 4")!=-1);
var is_sun5 = (agt.indexOf("sunos 5")!=-1);
var is_suni86 = (is_sun && (agt.indexOf("i86")!=-1));
var is_irix = (agt.indexOf("irix") !=-1);
var is_irix5 = (agt.indexOf("irix 5") !=-1);
var is_irix6 = ((agt.indexOf("irix 6") !=-1) || (agt.indexOf("irix6") !=-1));
var is_hpux = (agt.indexOf("hp-ux")!=-1);
var is_hpux9 = (is_hpux && (agt.indexOf("09.")!=-1));
var is_hpux10 = (is_hpux && (agt.indexOf("10.")!=-1));
var is_aix = (agt.indexOf("aix") !=-1);
var is_aix1 = (agt.indexOf("aix 1") !=-1);
var is_aix2 = (agt.indexOf("aix 2") !=-1);
var is_aix3 = (agt.indexOf("aix 3") !=-1);
var is_aix4 = (agt.indexOf("aix 4") !=-1);
var is_linux = (agt.indexOf("inux")!=-1);
var is_sco = (agt.indexOf("sco")!=-1) || (agt.indexOf("unix_sv")!=-1);
var is_unixware = (agt.indexOf("unix_system_v")!=-1);
var is_mpras = (agt.indexOf("ncr")!=-1);
var is_reliant = (agt.indexOf("reliantunix")!=-1);
var is_dec = ((agt.indexOf("dec")!=-1) || (agt.indexOf("osf1")!=-1) || (agt.indexOf("dec_alpha")!=-1) || (agt.indexOf("alphaserver")!=-1) || (agt.indexOf("ultrix")!=-1) || (agt.indexOf("alphastation")!=-1));
var is_sinix = (agt.indexOf("sinix")!=-1);
var is_freebsd = (agt.indexOf("freebsd")!=-1);
var is_bsd = (agt.indexOf("bsd")!=-1);
var is_unix = ((agt.indexOf("x11")!=-1) || is_sun || is_irix || is_hpux || is_sco ||is_unixware || is_mpras || is_reliant || is_dec || is_sinix || is_aix || is_linux || is_bsd || is_freebsd);
var is_vms = ((agt.indexOf("vax")!=-1) || (agt.indexOf("openvms")!=-1));

function getCookie(name){
	var dc=document.cookie;
	var prefix=name + "=";
	var begin=dc.indexOf("; " + prefix);
	if (begin==-1){
		begin=dc.indexOf(prefix);
		if (begin!=0) return null;
	} else begin += 2;
	var end=document.cookie.indexOf(";",begin);
	if (end==-1) end=dc.length;
	return unescape(dc.substring(begin + prefix.length,end))
}
function setCookie(name,value,expires,path,domain,secure){
	document.cookie=escape(name) + '=' + escape(value) + (expires ? '; EXPIRES=' + expires.toGMTString() : '') + (path ? '; PATH=' + path : '') + (domain ? '; DOMAIN=' + domain : '') + (secure ? '; SECURE' : '')
}
function delCookie(name, path, domain, secure){
	document.cookie=escape(name) + '=null; EXPIRES=' + new Date(0).toGMTString() + (path ? '; PATH=' + path : '') + (domain ? '; DOMAIN=' + domain : '') + (secure ? '; SECURE' : '')
}
var i_cookies = false;
var i_jscookies = false;
var i_metacookies = false;
i_cookies = navigator.cookieEnabled;
setCookie("browserspy_js", 1, 0, '/');
if (getCookie("browserspy_js")) i_jscookies = true;
if (getCookie("browserspy_meta")) i_metacookies = true;
delCookie("browserspy_js");
delCookie("browserspy_meta");

document.write('<input type="hidden" name="sid_sniffer" value="'+is_major+','+is_minor+','+is_nav+','+is_nav2+','+is_nav3+','+is_nav4+','+is_nav4up+','+is_navonly+','+is_nav6+','+is_nav6up+','+is_gecko+','+is_ie+','+is_ie3+','+is_ie4+','+is_ie4up+','+is_ie5+','+is_ie5_5+','+is_ie5up+','+is_ie5_5up+','+is_ie6+','+is_ie6up+','+is_aol+','+is_aol3+','+is_aol4+','+is_aol5+','+is_aol6+','+is_opera+','+is_opera2+','+is_opera3+','+is_opera4+','+is_opera5+','+is_opera5up+','+is_webtv+','+is_TVNavigator+','+is_AOLTV+','+is_hotjava+','+is_hotjava3+','+is_hotjava3up+','+is_js+','+is_win+','+is_win95+','+is_win16+','+is_win31+','+is_winme+','+is_win2k+','+is_win98+','+is_winnt+','+is_win32+','+is_os2+','+is_mac+','+is_mac68k+','+is_macppc+','+is_sun+','+is_sun4+','+is_sun5+','+is_suni86+','+is_irix+','+is_irix5+','+is_irix6+','+is_hpux+','+is_hpux9+','+is_hpux10+','+is_aix+','+is_aix1+','+is_aix2+','+is_aix3+','+is_aix4+','+is_linux+','+is_sco+','+is_unixware+','+is_mpras+','+is_reliant+','+is_dec+','+is_sinix+','+is_freebsd+','+is_bsd+','+is_unix+','+is_vms+','+i_cookies+','+i_jscookies+','+i_metacookies+'">');
//-->