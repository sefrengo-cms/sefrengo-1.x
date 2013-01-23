<?xml version="1.0" encoding="utf-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title>Browser Content</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<style type="text/css" media="all">
#rbInnerBody{
padding:0;
margin:0;
}
#rbInnerTableHead{
font-family:ms-sans-serif, sans-serif, verdana, helvetica, arial, geneva;
font-size: 10px;
height:20px;
color:#666666;
background-color:#F7F7F7;
}
.rbInnerTableVerticalSpacer{
height:3px;
}
.rbInnerTableHorizontalSpacer{
width:8px;
}
.rbInnerCatRow{
cursor:pointer;
}
.rbInnerCat{
padding:2px 0px 2px 0px;
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
padding-left:5px;

}
.rbInnerCat a{
display:block;
color: black;
text-decoration:none;
}
.rbInnerCatDesc{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
color:#666666;
}
.rbInnerItemRow{
cursor:pointer;
}
.rbInnerItem{
padding:3px 0px 3px 0px;
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
padding-left:5px;
}
.rbInnerItem a{
display:block;
color: black;
text-decoration:none;
}
.rbInnerItemDesc{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
color:#666666;
}
.rbInnerDetailCat{
float:left;
border:1px solid #ffce63;
width:270px;
height:130px;
margin:12px 0px 0px 12px;
overflow:auto;
cursor:pointer;
}
.rbInnerDetailCatHead{
background-color: #ffeec9;
}
.rbInnerDetailCatHeadTitel{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
color:black;
font-weight:bold;
}
.rbInnerDetailCatBody{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
color:#666666;
padding:3px;
}
.rbInnerDetailItem{
float:left;
border:1px solid #DBE3EF;
width:270px;
height:130px;
margin:12px 0px 0px 12px;
overflow:auto;
cursor:pointer;
}
.rbInnerDetailItemHead{
background-color: #E8ECF3;
}
.rbInnerDetailItemHeadTitel{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
color:black;
font-weight:bold;
}
.rbInnerDetailItemBody{
font-family:verdana, helvetica, arial, geneva, sans-serif;
font-size: 11px;
color:#666666;
padding:3px;
}
</style>
<script type="text/javascript">
var global = "123";


function init(){
	//alert(global);
	rbi = new RessourceBaseInfos();
	window.parent.rb.setCsrValues(rbi);
}


function RessourceBaseInfos(){
	//name of the ressource
	this.ressourceName = '{rbRessourceName}';
	
	this.pathwayNames = new Array({rbPathwayNames});
	this.pathwayUrls = new Array({rbPathwayURLs});
	this.pathwaySelectedUrl = '{rbPathwaySelectedURL}';
	
	this.pathwayUpEneabled = {rbPathwayOneUpIsActive};
	this.pathwayUpUrl = '{rbPathwayUpURL}';
	
	this.detailSwitchEneabled = {rbDetailSwitchIsActive};
	this.detailSwitchCurrentView = '{rbCurrentDetailSwitchView}';// 'list' or 'detail' are possible
	this.detailSwitchUrl = '{rbDetailSwitchURL}';

}

</script>
</head>
<body onload="init()" id="rbInnerBody">
{rbTest}
{rbCatTplStart}
<!-- BEGIN rbCats -->
{rbCatTpl}
<!-- END rbCats -->
{rbCatTplEnd}
{rbItemTplStart}
<!-- BEGIN rbItems -->
{rbItemTpl}
<!-- END rbItems -->
{rbItemTplEnd}
</body>
</html>