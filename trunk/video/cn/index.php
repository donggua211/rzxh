<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/css.css" rel="stylesheet" type="text/css" />
<title>实时预览</title>
<style type="text/css" media="all" title="Default"> 
      @import url("../css/slider.css");
    </style>
<script language="javascript"> 
var m_userinfo = '<?php echo $_GET['ip'] ?> <?php echo $_GET['user'] ?> <?php echo $_GET['pwd'] ?> <?php echo $_GET['port'] ?>';	  //从服务器获取当前注册用户信息
var m_szDesip = "";                       //注册用户信息中的IP
var m_iPort = 0;                          //注册用户信息中的端口号
var m_szUser = "";                        //注册用户信息中的用户名
var m_szPwd = "";                         //注册用户信息中的密码
 
var m_szLanguage = "cn";                  //当前网页语言
var m_iUserID = -1;                       //注册控件返回值
var m_szDeviceType = "";                  //设备类型
var m_iChannelNum = 0;                    //设备通道总数
var m_iHaveIpc = 0 ;                      //数字通道总数
var m_iZeroChanNum = 0;                   //设备零通道总数add20100303
var m_iTalkNum = 0;                       //语音对讲通道总数
var m_IPCInfo = "";                       //IPC通道信息
var m_bChannelPlay = new Array();		  //通道是否在预览
var m_bChannelRecord = new Array();      //通道是否在录像
var m_iWindowChannel = new Array();      //窗口对应的通道
var m_iChannelWindow = new Array();      //通道对应的窗口
var m_iSpeed = new Array();              //当前窗口的云台速度
var m_bptzAuto = new Array();            //当前窗口的云台自转
var m_bptzLight = new Array();           //当前窗口的灯光
var m_bptzWiper = new Array();           //当前窗口的雨刷
var m_bTalk = 0;                          //当前页面是否在对讲
var m_iTalkingNO = 0;                     //当前对讲通道NO
var m_iSetUrl = 0;            			   //判断是否切换语言
for(var i=0;i<64;i++)
{
	m_bChannelPlay[i] = 0;
 	m_bChannelRecord[i] = 0;
    m_iWindowChannel[i] = -1;
    m_iChannelWindow[i] = -1;
    m_iSpeed[i] = 4;
    m_bptzAuto[i] = 0;
    m_bptzLight[i] = 0;
    m_bptzWiper[i] = 0;
}
  
var szIPCInfo = "";                      //存储数字通道启用的所有信息
var m_szDigitChanNo = new Array();      //数字通道号
var m_szDeviceID = new Array();         //设备ID
var m_szServerEnable = new Array();     //设备是否启用
var m_szChanEnable = new Array();       //数字通道是否启用
var m_szDeviceIP = new Array();         //设备IP
var m_szDeviceUserName = new Array();   //登录设备用户名
var m_szDevicePsw = new Array();        //登录设备密码
var m_szDevicePort = new Array();       //端口号
var m_szDeviceChannel = new Array();    //设备通道号
for(var i=0;i<32;i++)
{
	m_szDigitChanNo[i] = "";
	m_szDeviceID[i] = "";
	m_szServerEnable[i] = "";
	m_szChanEnable[i] = "";
	m_szDeviceIP[i] = "";
	m_szDeviceUserName[i] = "";
	m_szDevicePsw[i] = "";
	m_szDevicePort[i] = "";
	m_szDeviceChannel[i] = "";
}
var acookie = document.cookie;
var acookiename = acookie.split("=")[0];	
</script>
<script type="text/javascript" src="../js/geterror.js"></script>
<script type="text/javascript" src="../js/slider_extras.js"></script>
<script type="text/javascript" src="../js/preview.js"></script>
</head>
<body onLoad="InitOCX()" style="overflow:auto" onscroll="WorkAround();" onbeforeunload="javascript:UnloadCancel();">
<input id="CurSelWnd" type=hidden  value='0'>
<input id="CurWndChannel" type=hidden  value='-1'>
<input id="CurWndRecord" type=hidden  value='0'>
<input id="RealplayHandle" type=hidden  value='-1'>
<input id="Bright" type=hidden  value='0'>
<input id="Contrast" type=hidden  value='0'>
<input id="Saturation" type=hidden  value='0'>
<input id="Hue" type=hidden  value='0'>
<div id="page_container">
 <div id="main_body">
	<div id="main_body_left" align="left">
		<div class="DeviceDiv" id="Camera0" ><nobr>&nbsp;&nbsp;<img src="../images/talk.gif"  id="TalkImg" onClick="Talk()" alt="对讲" style="cursor:hand"/>&nbsp;<span id="Camera0Text" style="cursor:hand"  onClick="RealPlayDevice()" >&nbsp;</span><nobr></div>
	</div>
	<div id="main_body_right">
      <div id="main_body_right_left" align="center">
		<object classid="clsid:5A418331-514E-4C54-B526-6AC3C135FFD2" codebase="" standby="Waiting..." id="NetVideoActiveX" width="100%" height="95%" name="ocx" align="center" >
		</object>
		<script type="text/JavaScript"> 
			var Netocx = document.getElementById("NetVideoActiveX");
			Netocx.lLanguageType = 0;
		</script>
      </div>
      <div id="main_body_right_right" align="left">
      <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                  <td width="8"></td>
                  <td><table width="221" height="215" cellpadding="0" cellspacing="0">
                      <tr>
                        <td height="108" valign="top"><table id="Table_01" width="249" height="127" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td colspan="15"><img src="../images/ptz/ptz_01.gif" width="248" height="24" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="24" alt="" /></td>
                            </tr>
                            <tr>
                              <td colspan="4"><img src="../images/ptz/ptz_02.gif" width="51" height="10" alt="" /></td>
                              <td rowspan="2"><img src="../images/ptz/ptz_up.gif" width="24" height="24" alt="" onMouseDown="ptzTurnUpDowm()" onMouseUp="ptzTurnUpUp()" id="ptzUp" style="cursor:hand"/></td>
                              <td colspan="5"><img src="../images/ptz/ptz_04.gif" width="48" height="10" alt="" /></td>
                              <td rowspan="2"><img src="../images/ptz/zoomin.gif" width="25" height="24" alt="" id="ptzZoomIn" style="cursor:hand" onMouseDown="ptzZoomInDown()" onMouseUp="ptzZoomInUp()"/></td>
                              <td rowspan="9" width="43" height="74" bgcolor="#2C363D"><table width="43" height="74" cellspacing="0" cellpadding="0" align="center">
                                  <tr>
                                    <td align="center">调焦</td>
                                  </tr>
                                  <tr align="center">
                                    <td>聚焦</td>
                                  </tr>
                                  <tr align="center">
                                    <td>光圈</td>
                                  </tr>
                                </table></td>
                              <td rowspan="2"><img src="../images/ptz/zoomout.gif" width="25" height="24" alt="" id="ptzZoomOut" style="cursor:hand" onMouseDown="ptzZoomOutDown()" onMouseUp="ptzZoomOutUp()"/></td>
                              <td colspan="2" rowspan="10"><img src="../images/ptz/ptz_08.gif" width="32" height="77" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="10" alt="" /></td>
                            </tr>
                            <tr>
                              <td colspan="2" rowspan="3"><img src="../images/ptz/ptz_09.gif" width="27" height="24" alt="" /></td>
                              <td colspan="2" rowspan="3"><img src="../images/ptz/ptz_left_up.gif" width="24" height="24" alt="" onMouseDown="ptzTurnleftUpDowm()" onMouseUp="ptzTurnleftUpUp()" id="ptzLeftUp" style="cursor:hand"/></td>
                              <td colspan="2" rowspan="3"><img src="../images/ptz/ptz_up_right.gif" width="24" height="24" alt="" id="ptzRightUp" style="cursor:hand" onMouseDown="ptzTurnRightUpDowm()" onMouseUp="ptzTurnRightUpUp()"/></td>
                              <td colspan="3" rowspan="3"><img src="../images/ptz/ptz_12.gif" width="24" height="24" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="14" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="2"><img src="../images/ptz/ptz_13.gif" width="24" height="10" alt="" /></td>
                              <td><img src="../images/ptz/ptz_14.gif" width="25" height="1" alt="" /></td>
                              <td><img src="../images/ptz/ptz_15.gif" width="25" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="1" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="2"><img src="../images/ptz/focusnear.gif" width="25" height="24" alt="" onMouseDown="ptzFocusInDown()" onMouseUp="ptzFocusInUp()" id="ptzFocusIn" style="cursor:hand"/></td>
                              <td rowspan="2"><img src="../images/ptz/focusfar.gif" width="25" height="24" alt="" onMouseDown="ptzFocusOutDown()" onMouseUp="ptzFocusOutUp()" id="ptzFocusOut" style="cursor:hand"/></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="9" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="10"><img src="../images/ptz/ptz_18.gif" width="21" height="68" alt="" /></td>
                              <td colspan="2" rowspan="3"><img src="../images/ptz/ptz_left.gif" width="24" height="24" alt="" id="ptzLeft" style="cursor:hand" onMouseDown="ptzTurnLeftDowm()" onMouseUp="ptzTurnLeftUp()"/></td>
                              <td colspan="3" rowspan="3"><img src="../images/ptz/ptz_auto.gif" width="37" height="24" alt="" onClick="ptzAuto()" id="ptzAuto" style="cursor:hand"/></td>
                              <td colspan="3" rowspan="3"><img src="../images/ptz/ptz_right.gif" width="24" height="24" alt="" id="ptzRight" style="cursor:hand" onMouseDown="ptzTurnRightDown()" onMouseUp="ptzTurnRightUp()"/></td>
                              <td rowspan="6"><img src="../images/ptz/ptz_22.gif" width="17" height="43" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="15" alt="" /></td>
                            </tr>
                            <tr>
                              <td><img src="../images/ptz/ptz_23.gif" width="25" height="1" alt="" /></td>
                              <td><img src="../images/ptz/ptz_24.gif" width="25" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="1" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="3"><img src="../images/ptz/irissmall.gif" width="25" height="24" alt="" onMouseDown="ptzIrisOpenDown()" onMouseUp="ptzIrisOpenUp()" id="ptzIrisOpen" style="cursor:hand"/></td>
                              <td rowspan="3"><img src="../images/ptz/irislarge.gif" width="25" height="24" alt="" onMouseDown="ptzIrisCloseDown()" onMouseUp="ptzIrisCloseUp()" id="ptzIrisClose" style="cursor:hand"/></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="8" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="7"><img src="../images/ptz/ptz_27.gif" width="6" height="44" alt="" /></td>
                              <td colspan="2" rowspan="4"><img src="../images/ptz/ptz_down_left.gif" width="24" height="24" alt="" id="ptzLeftDown" style="cursor:hand" onMouseDown="ptzTurnLeftDownDowm()" onMouseUp="ptzTurnLeftDownUp()"/></td>
                              <td><img src="../images/ptz/ptz_29.gif" width="24" height="6" alt="" /></td>
                              <td colspan="2" rowspan="4"><img src="../images/ptz/ptz_right_down.gif" width="24" height="24" alt="" onMouseDown="ptzTurnRightDownDown()" onMouseUp="ptzTurnRightDownUp()" id="ptzRightDown" style="cursor:hand"/></td>
                              <td colspan="2" rowspan="3"><img src="../images/ptz/ptz_31.gif" width="7" height="19" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="6" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="4"><img src="../images/ptz/ptz_down.gif" width="24" height="24" alt="" id="ptzDown" style="cursor:hand" onMouseDown="ptzTurnDownDown()" onMouseUp="ptzTurnDownUp()"/></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="10" alt="" /></td>
                            </tr>
                            <tr>
                              <td colspan="3"><img src="../images/ptz/ptz_33.gif" width="93" height="3" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="3" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="4"><img src="../images/ptz/ptz_34.gif" width="2" height="25" alt="" /></td>
                              <td colspan="6" rowspan="3"><div id="sliderDemo1" style="height:13px;" align="left"></div>
                                <script type="text/javascript">       
		  var spd;
		  var sliderImage1 = new neverModules.modules.slider(
          {targetId: "sliderDemo1",
          sliderCss: "imageslider1",
          barCss: "imageBar1",
          min: 1,
          max: 7,
          hints: "云台速度"
          }); 
          sliderImage1.onchange = function () {
           spd= sliderImage1.getValue();
		   var OCX = document.getElementById("NetVideoActiveX"); 
		   OCX.SetPTZSpeed(spd);
          };
          sliderImage1.create();
          sliderImage1.setValue(4);
        </script></td>
                              <td rowspan="4"><img src="../images/ptz/ptz_36.gif" width="22" height="25" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="5" alt="" /></td>
                            </tr>
                            <tr>
                              <td colspan="2" rowspan="3"><img src="../images/ptz/ptz_37.gif" width="24" height="20" alt="" /></td>
                              <td colspan="2" rowspan="3"><img src="../images/ptz/ptz_38.gif" width="24" height="20" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="6" alt="" /></td>
                            </tr>
                            <tr>
                              <td rowspan="2"><img src="../images/ptz/ptz_39.gif" width="24" height="14" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="2" alt="" /></td>
                            </tr>
                            <tr>
                              <td colspan="6"><img src="../images/ptz/ptz_40.gif" width="125" height="12" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="1" height="12" alt="" /></td>
                            </tr>
                            <tr>
                              <td><img src="../images/ptz/spacer.gif" width="21" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="6" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="18" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="6" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="24" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="7" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="17" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="2" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="5" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="17" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="25" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="43" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="25" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="10" height="1" alt="" /></td>
                              <td><img src="../images/ptz/spacer.gif" width="22" height="1" alt="" /></td>
                              <td></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td height="30" align="right"><table width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="37%">&nbsp;</td>
                              <td width="12%"><img src="../images/ptz/light.gif" width="23" height="23" onClick="ptzLight()" id="ptzLight" style="cursor:hand"></td>
                              <td width="15%" style="padding-top:4px">灯光</td>
                              <td width="13%"><img src="../images/ptz/wiper.gif" width="23" height="23" onClick="ptzWiper()" id="ptzWiper" style="cursor:hand"></td>
                              <td width="23%" style="padding-top:4px">雨刷</td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td><table width="241" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="171" align="right"><input name="hujunp" style="WIDTH: 1px; height:1px" value=""><SELECT style=" margin-left:0; max-height:100px;FONT-SIZE: 12px;WIDTH: 130px; height:20px" id="Preset" >
                                </select>
                                &nbsp;</td>
                              <td align="left" width="63"><img src="../images/ptz/go.gif" align="absmiddle" onMouseDown="ExcutePresetDown()" onMouseUp="ExcutePresetUp()" id="ExcutePreset" style="cursor:hand" alt="调用"></td>
                              <td width="7"></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td><br>
                          &nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="255" cellspacing="0" cellpadding="0"  border="0" background="../images/ptz/colorbackground.gif" style="background-repeat:no-repeat">
                            <tr>
                              <td colspan="3" height="20">&nbsp;</td>
                            </tr>
                            <tr>
                              <td width="10" valign="top">&nbsp;</td>
                              <td width="228" height="130" valign="top" background="../images/ptz/bgcolor.jpg"><table width="210"  height="150" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="54"  align="right">亮度&nbsp;</td>
                                    <td width="108"><table width="99" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td align="center" width="11" style="display:none"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri1" onClick="SetVideoParamBri(1)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri2" onClick="SetVideoParamBri(2)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri3" onClick="SetVideoParamBri(3)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri4" onClick="SetVideoParamBri(4)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri5" onClick="SetVideoParamBri(5)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri6" onClick="SetVideoParamBri(6)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri7" onClick="SetVideoParamBri(7)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri8" onClick="SetVideoParamBri(8)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri9" onClick="SetVideoParamBri(9)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Bri10" onClick="SetVideoParamBri(10)" style="cursor:hand;"></td>
                                        </tr>
                                      </table></td>
                                    <td width="26"><img src="../images/ptz/colorsmall.gif"  id="Brightness" onMouseDown="downSetVideoParamBri()"  onmouseup="upSetVideo()" style="cursor:hand;"/></td>
                                    <td width="18"><img src="../images/ptz/color.gif" id="Brightnesstwo" onMouseDown="downSetVideoParamBritwo()"  onmouseup="upSetVideotwo()" style="cursor:hand;"/></td>
                                  </tr>
                                  <tr>
                                    <td width="54"  align="right">对比度&nbsp;</td>
                                    <td width="108"><table width="99" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td align="center" width="11" style="display:none"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con1" onClick="SetVideoParamCon(1)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con2" onClick="SetVideoParamCon(2)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con3" onClick="SetVideoParamCon(3)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con4" onClick="SetVideoParamCon(4)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con5" onClick="SetVideoParamCon(5)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con6" onClick="SetVideoParamCon(6)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con7" onClick="SetVideoParamCon(7)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con8" onClick="SetVideoParamCon(8)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con9" onClick="SetVideoParamCon(9)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Con10" onClick="SetVideoParamCon(10)" style="cursor:hand;"></td>
                                        </tr>
                                      </table></td>
                                    <td width="26"><img src="../images/ptz/colorsmall.gif" id="dbd" onMouseDown="downSetVideoParamCon()"  onmouseup="upSetVideo()" style="cursor:hand;"/></td>
                                    <td width="18"><img src="../images/ptz/color.gif"  id="dbdtwo" onMouseDown="downSetVideoParamContwo()"  onmouseup="upSetVideotwo()" style="cursor:hand;"/></td>
                                  </tr>
                                  <tr>
                                    <td width="54"  align="right">饱和度&nbsp;</td>
                                    <td width="108"><table width="99" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td align="center" width="11" style="display:none"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat1" onClick="SetVideoParamSat(1)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat2" onClick="SetVideoParamSat(2)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat3" onClick="SetVideoParamSat(3)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat4" onClick="SetVideoParamSat(4)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat5" onClick="SetVideoParamSat(5)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat6" onClick="SetVideoParamSat(6)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat7" onClick="SetVideoParamSat(7)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat8" onClick="SetVideoParamSat(8)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat9" onClick="SetVideoParamSat(9)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Sat10" onClick="SetVideoParamSat(10)" style="cursor:hand;"></td>
                                        </tr>
                                      </table></td>
                                    <td width="26"><img src="../images/ptz/colorsmall.gif" id="bhd" onMouseDown="downSetVideoParamSat()"  onmouseup="upSetVideo()" style="cursor:hand;"/></td>
                                    <td width="18"><img src="../images/ptz/color.gif" id="bhdtwo" onMouseDown="downSetVideoParamSattwo()"  onmouseup="upSetVideotwo()" style="cursor:hand;"/></td>
                                  </tr>
                                  <tr>
                                    <td width="54"  align="right">色调&nbsp;</td>
                                    <td width="108"><table width="99" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td align="center" width="11" style="display:none"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue1" onClick="SetVideoParamHue(1)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue2" onClick="SetVideoParamHue(2)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue3" onClick="SetVideoParamHue(3)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue4" onClick="SetVideoParamHue(4)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue5" onClick="SetVideoParamHue(5)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue6" onClick="SetVideoParamHue(6)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue7" onClick="SetVideoParamHue(7)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue8" onClick="SetVideoParamHue(8)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue9" onClick="SetVideoParamHue(9)" style="cursor:hand;"></td>
                                          <td align="center"><img src="../images/ptz/color0.gif" align="absmiddle" id="Hue10" onClick="SetVideoParamHue(10)" style="cursor:hand;"></td>
                                        </tr>
                                      </table></td>
                                    <td width="26"><img src="../images/ptz/colorsmall.gif" id="sd" onMouseDown="downSetVideoParamHue()"  onmouseup="upSetVideo()" style="cursor:hand;"/></td>
                                    <td width="18"><img src="../images/ptz/color.gif" id="sdtwo" onMouseDown="downSetVideoParamHuetwo()"  onmouseup="upSetVideotwo()" style="cursor:hand;"/></td>
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="right"><img src="../images/ptz/setdefault.gif"  id="VideoDefault" style="cursor:hand" onMouseDown="SetVideoDefaultDown()" onMouseUp="SetVideoDefaultUp()" alt="恢复默认值"/></td>
                                  </tr>
                                </table></td>
                              <td width="17" valign="top">&nbsp;</td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              </div>
  </div>
		 <form action="" method="post" name="UrlForm">
            <tr>
              <td  style="color:#000000"  align="right"><input type="hidden" name="url"></td>
            </tr>
          </form>
</div>
<div id="sidebar"  style="display:none">
<table width="160" cellspacing="0" cellpadding="0" height="130" align="center" >
  <tr>
    <td align="center" height="20" bgcolor="#52575B" background="../images/log2.gif"></td>
  </tr>
  <tr>
    <td align="center" height="40" style="color:#000000"><input name="Num1" type="checkbox"  value="0" onClick="SelectAllFile(1)" >&nbsp;语音对讲通道1</td>
  </tr>
  <tr>
    <td align="center" height="40" valign="top" style="color:#000000"><input name="Num2" type="checkbox"  value="0" onClick="SelectAllFile(2)" >&nbsp;语音对讲通道2</td>
  </tr>
   <tr>
    <td align="center" ><input  type="button"  name="talkbutton" class="buttonTalk" value="对 讲" onClick="Sure()"/>&nbsp;<input  type="button"  name="deletebutton"  class="buttonTalk" value="取 消"    onClick="Delete()"/></td>
  </tr>
</table>
</div>
</body>
</html>
<script for=NetVideoActiveX event="GetSelectWndInfo(SelectWndInfo)"> 
    //alert(RealplayInfo);
	var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async="false"
    xmlDoc.loadXML(SelectWndInfo)	
	CurSelWnd.value = xmlDoc.documentElement.childNodes[0].childNodes[0].nodeValue;  
    iWndChannel = parseInt(xmlDoc.documentElement.childNodes[1].childNodes[0].nodeValue);    //选中窗口对应的通道
	if(parseInt(iWndChannel) >= 32)
	{
	   	  CurWndChannel.value = m_iChannelNum + parseInt(iWndChannel) - 32; 
	}
	else
	{
		CurWndChannel.value =  iWndChannel;
	}
	//alert(CurWndChannel.value)
	var bRecord = parseInt(xmlDoc.documentElement.childNodes[3].childNodes[0].nodeValue);
	RealplayHandle.value = xmlDoc.documentElement.childNodes[4].childNodes[0].nodeValue;
	Bright.value = xmlDoc.documentElement.childNodes[5].childNodes[0].childNodes[0].nodeValue;
	Contrast.value = xmlDoc.documentElement.childNodes[5].childNodes[1].childNodes[0].nodeValue;
	Saturation.value = xmlDoc.documentElement.childNodes[5].childNodes[2].childNodes[0].nodeValue;
	Hue.value = xmlDoc.documentElement.childNodes[5].childNodes[3].childNodes[0].nodeValue;	
//	iPreWndChannel = parseInt(CurWndChannel.value);
    var k = m_iWindowChannel[parseInt(CurSelWnd.value)];
	// alert(m_iWindowChannel[parseInt(CurSelWnd.value)]);
	for(var i=0;i<(m_iChannelNum + m_iHaveIpc + m_iZeroChanNum);i++)
	{
		var ObjColor = document.all.item("Selected"+(i+1)+"color");
	    if(i==k&&m_bChannelPlay[k]==1)
	    { 		
		 	ObjColor.style.color="#FFCC00"; 
		 	SetFontColor(-2);
	    }
	    else 
	    {
		    ObjColor.style.color="#ffffff";
	    }
	}
    if(parseInt(CurWndChannel.value) < 0)
	{	   
	 //  SetFontColor(parseInt(CurWndChannel.value)-1);
        for(var i=0;i<(m_iChannelNum + m_iHaveIpc + m_iZeroChanNum);i++)
	    {
	   		var ObjColor = document.all.item("Selected"+(i+1)+"color");
		    ObjColor.style.color="#ffffff";
        }
	    document.getElementById("ptzAuto").src="../images/ptz/ptz_auto.gif";
	    document.getElementById("ptzLight").src="../images/ptz/light.gif";
	    document.getElementById("ptzWiper").src="../images/ptz/wiper.gif";	
	}
	else
	{
	    SetptzBtnState();
	 }
	ShowVideoEffect();
	ptzSpeed();
</script>
<script for=NetVideoActiveX event="GetAllWndInfo(RealplayInfo)"> 
	//alert(RealplayInfo);
	var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
	var iChannelNum = -1;
    xmlDoc.async="false"
    xmlDoc.loadXML(RealplayInfo)
	for(var i=0; i<64; i++)
	{
	     m_bChannelPlay[i] = 0;
	     m_bChannelRecord[i] = 0;
	     m_iWindowChannel[i] = 0;
	     //m_bptzAuto[i]=0;
	}
    for(var j=0; j<25;j++)
	{
	     iChannelNum = xmlDoc.documentElement.childNodes[j].childNodes[1].childNodes[0].nodeValue;  //窗口对应通道号
		 bView = xmlDoc.documentElement.childNodes[j].childNodes[2].childNodes[0].nodeValue;        //窗口是否有预览
		 //CurWndChannel.value = bView;
		 //alert(bView)
		 if(iChannelNum >= 32)
		 {
	   	  	  iChannelNum = m_iChannelNum + iChannelNum - 32; 
		 }
		 iChannelNum = parseInt(iChannelNum);
	     if(bView > 0)
	     {
		  	//m_bChannelPlay[iChannelNum-1] = 1;
		      m_bChannelPlay[iChannelNum] = 1;
		//  m_bptzAuto[iChannelNum]=1;
		  	  m_iWindowChannel[j] = iChannelNum;
		      bRecord = xmlDoc.documentElement.childNodes[j].childNodes[3].childNodes[0].nodeValue;
		      if(bRecord == 1)
		      {
		      	    m_bChannelRecord[iChannelNum] = 1;
					document.all.item("Record"+(iChannelNum + 1)+"Img").src="../images/tree_33.gif";
					document.all.item("Record"+(iChannelNum + 1)+"Img").alt = "停止录像";
		      }
			  else
			  {
		      	    m_bChannelRecord[iChannelNum] = 0;
					document.all.item("Record"+(iChannelNum + 1)+"Img").src="../images/tree_3.gif";
					document.all.item("Record"+(iChannelNum + 1)+"Img").alt = "录像";
			  }
		      if(parseInt(CurSelWnd.value) == j)
		      {   
		      		var OCX = document.getElementById("NetVideoActiveX"); 
		      		CurWndChannel.value = iChannelNum;
			  		RealplayHandle.value = xmlDoc.documentElement.childNodes[j].childNodes[4].childNodes[0].nodeValue;
		   	  		Bright.value = xmlDoc.documentElement.childNodes[25].childNodes[0].childNodes[0].nodeValue;
	          		Contrast.value = xmlDoc.documentElement.childNodes[25].childNodes[1].childNodes[0].nodeValue;
	          		Saturation.value = xmlDoc.documentElement.childNodes[25].childNodes[2].childNodes[0].nodeValue;
	          		Hue.value = xmlDoc.documentElement.childNodes[25].childNodes[3].childNodes[0].nodeValue;	
	          		ShowVideoEffect();
		       }
			   SetptzBtnState();
	       }
	       else
	       {
		   		if(parseInt(CurSelWnd.value) == j)
		  		{
		      		CurWndChannel.value = -1;
			  		RealplayHandle.value = -1;
		  	  		document.all.item("ptzAuto").src="../images/ptz/ptz_auto.gif";
		      		document.all.item("ptzLight").src="../images/ptz/light.gif";
		      		document.all.item("ptzWiper").src="../images/ptz/wiper.gif";
              		ShowVideoEffect();  		
		   		}   
	        }	       		      
	 }
	 for(var k=0; k<(m_iChannelNum + m_iHaveIpc + m_iZeroChanNum); k++)
	 {
	   	   if(m_bChannelPlay[k] == 1)
	       {
		     	document.all.item("Camera"+(k+1)+"Img").src="../images/tree_22.gif";	
				document.all.item("Camera"+(k+1)+"Img").alt = "停止预览";   
	         	var  l=m_iWindowChannel[parseInt(CurSelWnd.value)];
  			 	for(var j=0;j<(m_iChannelNum + m_iHaveIpc + m_iZeroChanNum);j++)
			 	{
					var ObjColor = document.all.item("Selected"+(j+1)+"color");
					if(j==l)
	  				{ 		
					 	ObjColor.style.color="#FFCC00"; 
		 			 	SetFontColor(-2);
	   				}
	 				else 
	  				{
					 	ObjColor.style.color="#ffffff";
	     		 	}
			  	}
			}
	    	else
	    	{
		     	document.all.item("Camera"+(k+1)+"Img").src="../images/tree_2.gif";	
		     	document.all.item("Record"+(k+1)+"Img").src="../images/tree_3.gif";
				document.all.item("Camera"+(k+1)+"Img").alt = "预览";	
		     	document.all.item("Record"+(k+1)+"Img").alt = "录像";		
			}
	  }
</script>
<script for=NetVideoActiveX event="GetRecordState(RecordInfo)"> 
	//alert(RecordInfo)
   	var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async="false"
    xmlDoc.loadXML(RecordInfo)
	var iChannelNum = -1;
	for(var i=0; i<25; i++)
	{
	   iChannelNum = xmlDoc.documentElement.childNodes[i].childNodes[1].childNodes[0].nodeValue;
	   m_bChannelRecord[iChannelNum] = xmlDoc.documentElement.childNodes[i].childNodes[2].childNodes[0].nodeValue;
	   k = parseInt(iChannelNum);
	   if(m_bChannelPlay[k]==1)
	   {
	  	  if(m_bChannelRecord[k]==1)
	      {  
		   		document.all.item("Record"+(k+1)+"Img").src="../images/tree_33.gif";
				document.all.item("Record"+(k+1)+"Img").alt = "停止录像";
	      }
		  else
		  {
				document.all.item("Record"+(k+1)+"Img").src="../images/tree_3.gif";
				document.all.item("Record"+(k+1)+"Img").alt = "录像";
		  }
	   }
	}
</script>
<script for=NetVideoActiveX event="GetTalkState()"> 
    m_bTalk =0;
	document.all.item("TalkImg").src="../images/talk.gif";
	document.all.item("TalkImg").alt="对讲";
</script>
<script for=NetVideoActiveX event="GetCtrlPTZState(bPtzState)"> 
    if(bPtzState)
	{
		document.all.item("ptzAuto").src="../images/ptz/ptz_auto.gif";
		m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
	}else
	{
		alert("控制云台失败!");
	}
</script>