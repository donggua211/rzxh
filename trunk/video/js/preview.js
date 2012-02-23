document.charset = "utf-8";
var m_szAltTips1;
var m_szAltTips2;
var m_iAllChanTotal = 0;
var m_szZeroPtzTips = "";
if(m_szLanguage == "cn")
{
	m_szZeroPtzTips = "该通道不支持此功能！"; 
}
else
{
	m_szZeroPtzTips = "The channel does not support this feature."; 
}
if(GetNowTimeYears() == 1)
{
	top.location.href="index.php";	
}
/*************************************************
Function:		InitOCX
Description:	初始化控件
Input:			无			
Output:			无
return:			无				
*************************************************/
function InitOCX()
{

	if(m_userinfo == 30)
	{
		var expires = new Date();
		expires.setTime(expires.getTime() - 1000000);
		document.cookie = acookiename + "=" + escape(acookie) + "; expires=" + expires.toGMTString() + "; path=/"; 
		return;	
	}
	else
	{
		m_szDesip = m_userinfo.split(" ")[0];
		m_iPort = parseInt(m_userinfo.split(" ")[3]);
		m_szUser = m_userinfo.split(" ")[1];
		m_szPwd = m_userinfo.split(" ")[2];
	}
	var szDecName = "";
   	var szServerInfo = "";
   	var iChannelNum = 0;
   	var iStartChannel = -1;   
   	var w = window.screen.availwidth;
   	var h = window.screen.availheight;
   	document.UrlForm.url.value=document.URL;
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	PlayOCX.strURL=UrlForm.url.value;
   	m_iUserID = PlayOCX.Login(m_szDesip,m_iPort,m_szUser,m_szPwd);
	if(m_szLanguage == "cn")
   	{
		szTips = "连接服务器失败！"; 
		szTips1 = "获取IPC配置失败！";
	}
	if(m_szLanguage == "en")
	{
		szTips = "Failed to connect to server.";
		szTips1 = "Failed to get IPC configuration.";
	}
	if(m_iUserID == -1)
   	{
    	alert(szTips);
	  	window.location.href="index.php";
	  	return;
    }
   	else
   	{
    	szDecName = PlayOCX.GetServerName();
		szDecName = szDecName.replace(/\s/g,"&nbsp;"); 
		if(szDecName == "")
		{
			szDecName = "Embedded Net DVR";	
		}
	  	document.getElementById("Camera0Text").innerHTML="&nbsp;"+szDecName;  
		//document.getElementById("curruser").innerHTML=" "+m_szUser;
	  	szServerInfo = PlayOCX.GetServerInfo();
	  	var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
      	xmlDoc.async = "false";
      	xmlDoc.loadXML(szServerInfo);
	  	iChannelNum = xmlDoc.documentElement.childNodes[0].childNodes[0].nodeValue;
	  	m_szDeviceType = xmlDoc.documentElement.childNodes[1].childNodes[0].nodeValue;
	  	m_iChannelNum = parseInt(iChannelNum);
	  	iStartChannel = xmlDoc.documentElement.childNodes[6].childNodes[0].nodeValue;
		if(xmlDoc.documentElement.childNodes[9].hasChildNodes())
		{
			m_iZeroChanNum = parseInt(xmlDoc.documentElement.childNodes[9].childNodes[0].nodeValue);
		}
	 	// GetDecList(iStartChannel);	
	  	//PlayOCX.GetRegeditInfo(); 
	  	m_iTalkNum = xmlDoc.documentElement.childNodes[7].childNodes[0].nodeValue;
		
		if(m_iTalkNum == 0)
		{
			document.all.item("TalkImg").src="../images/tree.gif";
			document.all.item("TalkImg").alt = "";
		}
	
		//alert(m_szDeviceType)
      	if(m_szDeviceType == "90" || m_szDeviceType == "95" ||m_szDeviceType == "65" || m_szDeviceType == "76" || m_szDeviceType == "77" || m_szDeviceType == "253" || m_szDeviceType == "97" || m_szDeviceType == "96")
	  	{
	        var bGetIP = PlayOCX.GetIPParaCfg();
   			if( bGetIP == false)
   			{
	    		 //WhatError(PlayOCX);
	  			alert(szTips1);
	   			//return;
   			}
   			else
   			{
   				szChannelInfo = PlayOCX.GetIPCConfig();
   				var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
   				xmlDoc.async="false"
   				xmlDoc.loadXML(szChannelInfo)
   				nodes = xmlDoc.documentElement.childNodes;
   				var k = nodes.length;  //xml所有节点数
   				var ItemN = xmlDoc.getElementsByTagName("DIGITCHAN");
   				var j = ItemN.length;//获取item节点个数
   				m_iItemN =j;
  				var h = k - j;  //计算从第几个开始是数字通道信息
   				for(var i=0; i<j; i++)
   				{        
	   				m_szDigitChanNo[i] = xmlDoc.documentElement.childNodes[h+i].childNodes[0].childNodes[0].nodeValue;
	   				if(xmlDoc.documentElement.childNodes[h+i].childNodes[4].hasChildNodes())
	   				{
						m_szDeviceIP[i] = xmlDoc.documentElement.childNodes[h+i].childNodes[4].childNodes[0].nodeValue;
						szIPCInfo =  szIPCInfo + "A"+ m_szDigitChanNo[i];
	   				}
				}
				m_IPCInfo = szIPCInfo;
		    }
	  	}
	  	GetDecList(iStartChannel);	
	  	PlayOCX.GetRegeditInfo(); 
    }
    InitPresetPoint();
	if(m_szLanguage == "cn")
   	{
		m_szAltTips1 = "无此权限！";  
		m_szAltTips2 = "操作失败或没有权限！";
	}
	if(m_szLanguage == "en")
	{
		m_szAltTips1 = "No enough privilege.";
		m_szAltTips2 = "Operation failed or no enough privilege.";
	}
	hujunp.focus();
}
/*************************************************
Function:		WorkAround
Description:	滚动条拖动时对控件进行刷新
Input:			无			
Output:			无
return:			无				
*************************************************/
function WorkAround()
{
    window.document.all.item("NetVideoActiveX").style.display = "none"
    window.document.all.item("NetVideoActiveX").style.display = ""
}
/*************************************************
Function:		GoAway
Description:	注销退出登录页面
Input:			无			
Output:			无
return:			无				
*************************************************/
function GoAway()
{
	if(m_szLanguage == "cn")
   	{
		Warning =confirm("是否注销？");   
	}
	if(m_szLanguage == "en")
	{
		Warning =confirm("Are you sure you want to logout ?");
	}
    if (Warning)
    {
	    window.location.href="exit.asp";
    }
}
/*************************************************
Function:		MM_jumpMenu
Description:	中英文语言切换
Input:			无			
Output:			无
return:			无				
*************************************************/
function MM_jumpMenu(url)
{ 
   	var sum = 0 ;    // 正在播放通道的总数
	if(m_szLanguage == "cn")
   	{
		szName = "离开页面将放弃当前操作！";   
	}
	if(m_szLanguage == "en")
	{
		szName = "After closing this page, all current operations will give up.";
	}
	for(var i=0;i<64;i++)
    {
       sum = sum + m_bChannelPlay[i];
    }
	if(m_bTalk == 1 || sum >= 1 )
	{
		   var warning = confirm(szName);
		   if(!warning)
		   {
				document.getElementById("menu1").value = "preview.asp";  
			}
			else
			{
				m_iSetUrl = 1;	
				this.location.href = url;
			}
	}
	else
	{
		this.location.href = url;
	}
}
/*************************************************
Function:		UnloadCancel
Description:	离开页面时提示操作
Input:			无			
Output:			无
return:			无				
*************************************************/
function UnloadCancel()
{
    if(m_iSetUrl == 1)
	{
		return;	
	}
	var sum = 0 ;    // 正在播放通道的总数
	if(m_szLanguage == "cn")
   	{
		szName = "离开页面将放弃当前操作！";   
	}
	if(m_szLanguage == "en")
	{
		szName = "After closing this page, all current operations will give up.";
	}
	for(var i=0;i<64;i++)
    {
       sum = sum + m_bChannelPlay[i];
    }
	if(m_bTalk == 1 || sum >= 1 )
	{
	   window.event.returnValue = szName; 
    }
}
/*************************************************add: 2009-03-20
Function:		findPosition
Description:	获取html元素的绝对坐标
Input:			oElement ：元素ID			
Output:			无
return:			无				
*************************************************/
function findPosition(oElement) 
{
	var x2 = 0;
	var y2 = 0;
	var width = oElement.offsetWidth;
	var height = oElement.offsetHeight;
	  	//alert(width + "=" + height);
	if( typeof( oElement.offsetParent ) != 'undefined' ) 
	{
		var posY = 0
		for( var posX = 0 ; oElement; oElement = oElement.offsetParent ) 
		{
		  	posX =  posX +oElement.offsetLeft;
		  	posY = posY + oElement.offsetTop;      
		}
		x2 = posX + width;
		y2 = posY + height;
		return [ posX, posY ,x2, y2];		
	} 
	else
	{
		x2 = oElement.x + width;
		  y2 = oElement.y + height;
		 return [ oElement.x, oElement.y, x2, y2];
	  }
}

/*************************************************add: 2009-03-20
Function:		SelectAllFile
Description:	选中语音通道
Input:			num : 通道序号			
Output:			无
return:			无				
*************************************************/
function SelectAllFile(num)
{
	    if(document.getElementById("Num"+num).checked == false)
		{
		     m_iTalkingNO = 0;
			 return;
		}
		for(var i=1;i < 3; i ++)
    	{  
        	if(i == num)
			{
			   document.getElementById("Num"+i).checked = true;
    	    }
			else
			{
			   document.getElementById("Num"+i).checked = false;
			}
		}
		m_iTalkingNO = num;
}
/************************************************* add: 2009-03-20
Function:		Sure
Description:	确定选择进行对讲
Input:			无			
Output:			无
return:			无				
*************************************************/ 
function Sure()
{  
	if(m_szLanguage == "cn")
   	{
		szTips1 = "你没有选择语音通道！"; 
		szTips2 = "停止对讲"; 
		szTips3 = "对讲失败！"; 
	}
	if(m_szLanguage == "en")
	{
		szTips1 = "Please select the voice talking.";
		szTips2 = "Stop Talk"; 
		szTips3 = "Voice talking failed."; 
	}	
    if(m_iTalkingNO == 0)
	{
	    alert(szTips1);
		return;
	}
	var PlayOCX = document.getElementById("NetVideoActiveX");
	var iTalk = PlayOCX.StartTalk(m_iTalkingNO);
	if(iTalk)
	{
		document.all.item("TalkImg").src="../images/talkstop.gif";
		document.all.item("TalkImg").alt = szTips2;
		m_bTalk =1;
	}
	else
	{
		alert(szTips3);
		return ;
	}
	document.getElementById("sidebar").style.display = "none";
}
/*************************************************add: 2009-03-20
Function:		Delete
Description:	放弃对讲
Input:			无			
Output:			无
return:			无				
*************************************************/
function Delete()
{   
    m_iTalkingNO = 0;
	document.getElementById("sidebar").style.display = "none";
}
/*************************************************add: 2009-03-20
Function:		ShowTalkdiv
Description:	弹出对讲页面
Input:			无			
Output:			无
return:			无				
*************************************************/
function ShowTalkdiv()
{
		 var szID = document.getElementById("TalkImg");
		 var iweightX = findPosition(szID)[0];
		 var iweightY = findPosition(szID)[3];
		 document.getElementById("sidebar").style.top = iweightY + 8;
		 document.getElementById("sidebar").style.left= iweightX;
		 for(var i=1;i < 3; i ++)
    	 {  
			   document.getElementById("Num"+i).checked = false;
		 }
		 m_iTalkingNO = 0;
		 document.getElementById("sidebar").style.display = "";
}
/************************************************* modify: 2009-03-20
Function:		Talk
Description:	语言对讲
Input:			无			
Output:			无
return:			无				
*************************************************/
function Talk()
{
	if(m_szLanguage == "cn")
   	{
		szTips = "对讲失败！"; 
		szTips1 = "对讲"; 
		szTips2 = "停止对讲"; 
	}
	if(m_szLanguage == "en")
	{
		szTips = "Voice talking failed.";
		szTips1 = "Voice Talking"; 
		szTips2 = "Stop Talking"; 
	}
		
	if(m_iTalkNum == 0)
	{
		RealPlayDevice();
		return;
	}
		
   	var PlayOCX = document.getElementById("NetVideoActiveX");
	if(document.getElementById("sidebar").style.display == "")
	{
		return;	
	}
   	if(m_bTalk == 0 && (document.getElementById("sidebar").style.display) == "none")
   	{
       	if(m_iTalkNum <= 1)
	   	{
	       	var iTalk = PlayOCX.StartTalk(1);
			if(iTalk)
			{
				document.all.item("TalkImg").src="../images/talkstop.gif";
				document.all.item("TalkImg").alt = szTips2;
				m_bTalk =1;
			}
			else
		    {
				alert(szTips);
				return ;
			}
	   	}
	   	else
	   	{
			ShowTalkdiv();
	   	}
	}
	else 
	{
	 	document.all.item("TalkImg").src="../images/talk.gif";
		document.all.item("TalkImg").alt = szTips1;
	  	PlayOCX.StopTalk();
      	m_bTalk =0;
	 }
}
/*************************************************
Function:		InitPresetPoint
Description:	添加预置点
Input:			无			
Output:			无
return:			无				
*************************************************/
function InitPresetPoint()
{
	var szTemp="";
	if(m_szLanguage == "cn")
   	{
		szName = "预置点";   
	}
	if(m_szLanguage == "en")
	{
		szName = "Preset";
	}
	for(var i = 0; i < 256; i++)
	{
		if(i<9)
	   	{
	    	szTemp = szName + " 0" + (i+1);
	   	}
	  	else
	  	{
	       	szTemp = szName + " " + (i+1);
       	}
	  	document.getElementById("Preset").options.add(new Option(szTemp,i)); 
   	}
}
/*************************************************
Function:		GetDecList
Description:	获取设备通道表
Input:			iStartChannel :起始通道			
Output:			无
return:			无				
*************************************************/
function GetDecList(iStartChannel)
{ 
	var innerHTML = document.getElementById("main_body_left").innerHTML
	if(m_szLanguage == "cn")
   	{
		szAltName0 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		szAltName1 = "预览";
		szAltName2 = "录像"; 
		szAltName3 = "零通道"; 
	}
	if(m_szLanguage == "en")
	{
		szAltName0 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		szAltName1 = "Preview";
		szAltName2 = "Record"; 
		szAltName3 = "ZeroChannel"; 
	}
	var PlayOCX = document.getElementById("NetVideoActiveX");   
   	for(var i=0; i<m_iChannelNum; i++)
   	{        
		var szChannelName = PlayOCX.GetChannelName(i);
		szChannelName = szChannelName.replace(/\s/g,"&nbsp;");
		if(szChannelName == "")
		{
			if(i < 9)
			{
				szChannelName = "Camera 0" + (i + 1);
			}
			else
			{
				szChannelName = "Camera " + (i + 1);
			}		
		}
		innerHTML = innerHTML + "<div class='CameraDiv'  id='Camera"+(i+1)+"'><nobr>"+szAltName0+"<img src='../images/tree_2.gif' align='absmiddle' id='Camera"+(i+1)+"Img' onClick='SelectedChannel("+i+")'  style='cursor:hand' alt="+szAltName1+">&nbsp;<img src='../images/tree_3.gif' align='absmiddle' id='Record"+(i+1)+"Img' onClick='SelectedRecord("+i+")' alt="+szAltName2+" style='cursor:hand'><a  id='Selected"+(i+1)+"color' onClick='SetColor("+i+")' href='#' class='aurl'>&nbsp;"+szChannelName+"</a><nobr></div>" ;
   	}
   	if(m_IPCInfo != "")
   	{
		var iHaveIpc = m_IPCInfo.split("A").length; // 32 -->33  IPC通道
		m_iHaveIpc = parseInt(iHaveIpc-1);
		var IpNum;
		for(var j=1; j<iHaveIpc; j++)
		{         	         
			var szIPChannelName = PlayOCX.GetChannelName(j+m_iChannelNum -1);
			szIPChannelName = szIPChannelName.replace(/\s/g,"&nbsp;"); 
			IpNum = parseInt(m_IPCInfo.split("A")[j]) + 1;
			if(szIPChannelName == "")
			{
				if(IpNum < 9)
				{
					szIPChannelName = "IPCamera 0" + IpNum;
				}
				else
				{
					szIPChannelName = "IPCamera " + IpNum;
				}		
			}
			innerHTML = innerHTML + "<div class='CameraDiv'  id='Camera"+(j+m_iChannelNum)+"'><nobr>"+szAltName0+"<img src='../images/tree_2.gif' align='absmiddle' id='Camera"+(j+m_iChannelNum)+"Img' onClick='SelectedChannel("+(j+m_iChannelNum -1)+")'  style='cursor:hand' alt="+szAltName1+">&nbsp;<img src='../images/tree_3.gif' align='absmiddle' id='Record"+(j+m_iChannelNum)+"Img' onClick='SelectedRecord("+(j+m_iChannelNum -1)+")' alt="+szAltName2+" style='cursor:hand'><a  id='Selected"+(j+m_iChannelNum)+"color' onClick='SetColor("+(j+m_iChannelNum-1)+")' href='#' class='aurl'>&nbsp;"+szIPChannelName+"</a><nobr></div>" 
		}
	}
	
	var iTotal = m_iChannelNum + m_iHaveIpc + 1;     //add20100308
	for(var u = 0; u < m_iZeroChanNum; u ++)
	{
		innerHTML = innerHTML + "<div class='CameraDiv'  id='Camera"+(u + iTotal)+"'><nobr>"+szAltName0+"<img src='../images/tree_2.gif' align='absmiddle' id='Camera"+(u + iTotal)+"Img' onClick='SelectedChannel("+(u + iTotal -1)+")'  style='cursor:hand' alt="+szAltName1+">&nbsp;<img src='../images/tree_3.gif' align='absmiddle' id='Record"+(u + iTotal)+"Img' onClick='SelectedRecord("+(u + iTotal -1)+")' alt="+szAltName2+" style='cursor:hand'><a  id='Selected"+(u + iTotal)+"color' onClick='SetColor("+(u + iTotal -1)+")' href='#' class='aurl'>&nbsp;"+ szAltName3 +" "+ (u + 1) +"</a><nobr></div>"; 
	}
	m_iAllChanTotal = m_iChannelNum + m_iHaveIpc + m_iZeroChanNum;
	document.getElementById("main_body_left").innerHTML = innerHTML;
}
/*************************************************
Function:		RealPlayDevice
Description:	全部预览
Input:			无			
Output:			无
return:			无				
*************************************************/
function RealPlayDevice()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 	
	for(var j=0;j<(m_iChannelNum + m_iHaveIpc + m_iZeroChanNum);j++)
	{
		if(m_bChannelPlay[j] == 1)
		{
			StopPlay(j);
		}
	}  
	var iHaveWindow=PlayOCX.GetCurrentWndNumber();   //当前窗口画面数
	var iGoWindow=((m_iChannelNum + m_iHaveIpc + m_iZeroChanNum)>iHaveWindow)?iHaveWindow:(m_iChannelNum + m_iHaveIpc + m_iZeroChanNum); //实际一次预览通道数目
	if(!PlayOCX.StartRealPlayAll())
	{
		return;
	}
	for(var i=0;i<iGoWindow;i++)
	{    
		var ObjImg = document.all.item("Camera"+(i+1)+"Img");
 		ObjImg.src="../images/tree_22.gif";
     	m_bChannelPlay[i] = 1;		
	    m_bChannelRecord[i] = 0;
		m_iWindowChannel[i] = i; 
		m_iChannelWindow[i] = i;		 
	}	
	SetFontColor(-1);
}
/*************************************************
Function:		SetFontColor
Description:	设置字体颜色
Input:			iNum :	通道号		
Output:			无
return:			无				
*************************************************/
function SetFontColor(iNum)
{
	for(var j = -1; j < (m_iChannelNum + m_iHaveIpc + m_iZeroChanNum); j++)
   	{
    	if(j == iNum)
	  	{
        	document.getElementById("Camera"+(j+1)).style.color="#FFCC00";  
	  	}
	  	else
	  	{
	    	document.getElementById("Camera"+(j+1)).style.color="#ffffff"; 
      	}
	}
}
/*************************************************
Function:		SetFontColor
Description:	设置选中通道字体颜色
Input:			i : 通道号			
Output:			无
return:			无				
*************************************************/
function SetColor(i)
{   
	for(var j = 0; j < (m_iChannelNum + m_iHaveIpc + m_iZeroChanNum); j++)
	{
		var ObjColor = document.all.item("Selected"+(j+1)+"color");
		if(j==i)
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
/*************************************************
Function:		SelectedChannel
Description:	预览
Input:			iChannelNum : 通道号			
Output:			无
return:			无				
*************************************************/
function SelectedChannel(iChannelNum)
{
	if(m_szLanguage == "cn")
   	{
		szTips = "预览失败！";
		szTips1 = "停止预览"; 
		szTips2 = "该通道不支持此功能！"; 
	}
	if(m_szLanguage == "en")
	{
		szTips = "Preview failed.";
		szTips1 = "Stop Preview";
		szTips2 = "The Channel does not support this function."; 
	}
	var PlayWindow = iChannelNum;
	var OCX = document.getElementById("NetVideoActiveX");   
	iPreWndChannel = parseInt(CurWndChannel.value);
    var ObjImg = document.all.item("Camera"+(iChannelNum+1)+"Img");
	if(m_bChannelPlay[iChannelNum] == 0)
	{	 	
		 if(iPreWndChannel >= 0)
		 {
		     StopPlay(iPreWndChannel);  
		 }      
		 if(!OCX.StartRealPlay(iChannelNum, CurSelWnd.value))
		 {
		  	var iErrorValue = OCX.GetError();
			if(iErrorValue == 13)
			{
				alert(m_szAltTips1);
			}
			else if(iErrorValue == 91)
			{
				alert(szTips2);
			}
			else
			{
				alert(szTips);
			}
			return;
		 }
		 ObjImg.src="../images/tree_22.gif";
		 ObjImg.alt = szTips1;
		 m_bChannelPlay[iChannelNum] = 1;
		 m_bChannelRecord[iChannelNum] = 0; 
		 m_iWindowChannel[parseInt(CurSelWnd.value)] = iChannelNum;
		 m_iChannelWindow[iChannelNum] = CurSelWnd.value;
         SetColor(iChannelNum);
	}
	else    
	{ 
	    StopPlay(iChannelNum);
	}  
}
/*************************************************
Function:		SelectedRecord
Description:	开始录像
Input:			iChannelNum : 通道号			
Output:			无
return:			无				
*************************************************/
function SelectedRecord(iChannelNum)
{
	if(m_szLanguage == "cn")
   	{
		szTips = "录像失败！"; 
		szTips1 = "停止录像";
	}
	if(m_szLanguage == "en")
	{
		szTips = "Record failed.";
		szTips1 = "Stop Record";
	}
   	var OCX = document.getElementById("NetVideoActiveX");   
   	var ObjImg = document.all.item("Record"+(iChannelNum+1)+"Img");
   	if(m_bChannelRecord[iChannelNum] == 0)
	{	 
		if(m_bChannelPlay[iChannelNum]) 			
		{
			if(OCX.StartRecord(iChannelNum))
		    {
		   		ObjImg.src="../images/tree_33.gif"; 
				ObjImg.alt = szTips1;
		    	m_bChannelRecord[iChannelNum] = 1; 
			}
			else
			{
				//WhatError(OCX);
				alert(szTips);
			}
	    }
	}
	else    
	{ 
	    StopRecord(iChannelNum);
	}    
}
/*************************************************
Function:		StopPlay
Description:	停止预览
Input:			iChannelNum : 通道号			
Output:			无
return:			无				
*************************************************/
function StopPlay(iChannelNum)
{
	if(m_szLanguage == "cn")
   	{
		szTips1 = "预览";
	}
	if(m_szLanguage == "en")
	{
		szTips1 = "Preview";
	}
	var OCX = document.getElementById("NetVideoActiveX"); 
	if(m_bChannelRecord[iChannelNum] == 1)
	{
		StopRecord(iChannelNum);
	}
	OCX.StopRealPlay(iChannelNum);
	document.all.item("Camera"+(iChannelNum+1)+"Img").src = "../images/tree_2.gif";
	document.all.item("Camera"+(iChannelNum+1)+"Img").alt = szTips1;
	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	document.all.item("ptzLight").src = "../images/ptz/light.gif";
	document.all.item("ptzWiper").src = "../images/ptz/wiper.gif";
	m_bChannelPlay[iChannelNum] = 0;
	m_bChannelRecord[iChannelNum] = 0;
	m_iWindowChannel[parseInt(CurSelWnd.value)] = 0;  
}
/*************************************************
Function:		StopRecord
Description:	停止录像
Input:			iChannelNum : 通道号			
Output:			无
return:			无				
*************************************************/
function StopRecord(iChannelNum)
{
	if(m_szLanguage == "cn")
   	{
		szTips1 = "录像";
	}
	if(m_szLanguage == "en")
	{
		szTips1 = "Record";
	}
    var OCX = document.getElementById("NetVideoActiveX"); 
	OCX.StopRecord(iChannelNum);
	document.all.item("Record"+(iChannelNum+1)+"Img").src = "../images/tree_3.gif";
	document.all.item("Record"+(iChannelNum+1)+"Img").alt = szTips1;
	m_bChannelRecord[iChannelNum] = 0;  
}
/*************************************************
Function:		ptzSpeed
Description:	云台速度
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzSpeed()
{  
   m_iSpeed[CurSelWnd.value] = spd;
}
/*************************************************
Function:		ptzAuto
Description:	云台自转操作
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzAuto()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX");
   	ptzSpeed();
   	var s=m_iSpeed[CurSelWnd.value];
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	if(PlayOCX.ptzCtrlStop(10,s))
	  	{
	  		document.all.item("ptzAuto").src="../images/ptz/ptz_auto.gif";
	  	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
       	}
	}
   	else
   	{
    	if(PlayOCX.ptzCtrlStart(10,s))
		{
	  		document.all.item("ptzAuto").src = "../images/ptz/ptz_autosel.gif";
	  		m_bptzAuto[parseInt(CurWndChannel.value)] = 1;
       	}
	   	else
	   	{	    
	    	var Error = PlayOCX.GetError();
		  	if(Error == 13)
		  	{
		    	alert(m_szAltTips1); 
	         	return;
		  	}
		  	else
		  	{
	        	alert(m_szAltTips2); 
	         	return;
		  	}
	    }  
	}   
}
/*************************************************
Function:		ptzTurnUpDowm
Description:	鼠标按下云台向上操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnUpDowm()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(0,s))
   	{
   		document.all.item("ptzUp").src = "../images/ptz/ptz_upsel.gif";
   		document.getElementById("ptzUp").setCapture();
    }
    else
	{	    
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	}
}
/*************************************************
Function:		ptzTurnUpUp
Description:	鼠标释放云台向上操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnUpUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(0,s);
   	document.all.item("ptzUp").src = "../images/ptz/ptz_up.gif";
   	document.getElementById("ptzUp").releaseCapture();
}
/*************************************************
Function:		ptzTurnDownDown
Description:	鼠标按下云台向下操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnDownDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
  	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
	}
   	if(PlayOCX.ptzCtrlStart(1,s))
   	{
   		document.all.item("ptzDown").src = "../images/ptz/ptz_downsel.gif";
   		document.getElementById("ptzDown").setCapture();
    }
	else
	{	    
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	}
}
/*************************************************
Function:		ptzTurnDownUp
Description:	鼠标释放云台向下操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnDownUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(1,s);
   	document.all.item("ptzDown").src = "../images/ptz/ptz_down.gif";
   	document.getElementById("ptzDown").releaseCapture();
}
/*************************************************
Function:		ptzTurnLeftDowm
Description:	鼠标按下云台向左操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnLeftDowm()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)]=0;
   	}
   	if(PlayOCX.ptzCtrlStart(2,s))
   	{
   		document.all.item("ptzLeft").src = "../images/ptz/ptz_leftsel.gif";
   		document.getElementById("ptzLeft").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzTurnLeftUp
Description:	鼠标释放云台向左操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnLeftUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX");
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(2,s);
   	document.all.item("ptzLeft").src = "../images/ptz/ptz_left.gif";
   	document.getElementById("ptzLeft").releaseCapture();
}
/*************************************************
Function:		ptzTurnRightDown
Description:	鼠标按下云台向右操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnRightDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)-1] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(3,s))
   	{
    	document.all.item("ptzRight").src = "../images/ptz/ptz_rightsel.gif";
   		document.getElementById("ptzRight").setCapture();
    }
	else
	{
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		} 
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	}
}
/*************************************************
Function:		ptzTurnRightUp
Description:	鼠标释放云台向右操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnRightUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX");
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(3,s);
   	document.all.item("ptzRight").src = "../images/ptz/ptz_right.gif";
   	document.getElementById("ptzRight").releaseCapture();
}
/*************************************************
Function:		ptzZoomInDown
Description:	鼠标按下云台焦距变小操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzZoomInDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(4,s))
   	{
   		document.all.item("ptzZoomIn").src = "../images/ptz/zoominsel.gif";
   		document.getElementById("ptzZoomIn").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzZoomInUp
Description:	鼠标释放云台焦距变小操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzZoomInUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(4,s);
   	document.all.item("ptzZoomIn").src = "../images/ptz/zoomin.gif";
   	document.getElementById("ptzZoomIn").releaseCapture();
}
/*************************************************
Function:		ptzZoomOutDown
Description:	鼠标按下云台焦距变大操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzZoomOutDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(5,s))
   	{
   		document.all.item("ptzZoomOut").src="../images/ptz/zoomoutsel.gif";

		document.getElementById("ptzZoomOut").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzZoomOutUp
Description:	鼠标释放云台焦距变大操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzZoomOutUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(5,s);
   	document.all.item("ptzZoomOut").src = "../images/ptz/zoomout.gif";
   	document.getElementById("ptzZoomOut").releaseCapture();
}
/*************************************************
Function:		ptzFocusInDown
Description:	鼠标按下云台焦点变近操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzFocusInDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(6,s))
   	{
   		document.all.item("ptzFocusIn").src="../images/ptz/focusnearsel.gif";
   		document.getElementById("ptzFocusIn").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzFocusInUp
Description:	鼠标释放云台焦点变近操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzFocusInUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX");
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value]; 
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(6,s);
   	document.all.item("ptzFocusIn").src = "../images/ptz/focusnear.gif";
   	document.getElementById("ptzFocusIn").releaseCapture();
}
/*************************************************
Function:		ptzFocusOutDown
Description:	鼠标按下云台焦点变远操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzFocusOutDown() 
{ 
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(7,s))
   	{
   		document.all.item("ptzFocusOut").src="../images/ptz/focusfarsel.gif";
   		document.getElementById("ptzFocusOut").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzFocusOutUp
Description:	鼠标释放云台焦点变远操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzFocusOutUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(7,s);
   	document.all.item("ptzFocusOut").src = "../images/ptz/focusfar.gif";
   	document.getElementById("ptzFocusOut").releaseCapture();
}
/*************************************************
Function:		ptzIrisOpenDown
Description:	鼠标按下云台光圈变大操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzIrisOpenDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(9,s))
   	{
   		document.all.item("ptzIrisOpen").src = "../images/ptz/irissmallsel.gif";
    	document.getElementById("ptzIrisOpen").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzIrisOpenUp
Description:	鼠标释放云台光圈变大操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzIrisOpenUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(9,s);
   	document.all.item("ptzIrisOpen").src = "../images/ptz/irissmall.gif";
   	document.getElementById("ptzIrisOpen").releaseCapture();
}
/*************************************************
Function:		ptzIrisCloseDown
Description:	鼠标按下云台光圈变小操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzIrisCloseDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(8,s))
   	{
   		document.all.item("ptzIrisClose").src = "../images/ptz/irislargesel.gif";
    	document.getElementById("ptzIrisClose").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzIrisCloseUp
Description:	鼠标释放云台光圈变小操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzIrisCloseUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(8,s);
   	document.all.item("ptzIrisClose").src = "../images/ptz/irislarge.gif";
    document.getElementById("ptzIrisClose").releaseCapture();
}
/*************************************************
Function:		ptzLight
Description:	云台灯光操作
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzLight()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
   	if(m_bptzLight[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(11,s);
	  	document.all.item("ptzLight").src = "../images/ptz/light.gif";
	  	m_bptzLight[parseInt(CurWndChannel.value)] = 0;
   	}
   	else
   	{
    	if(PlayOCX.ptzCtrlStart(11,s))
	  	{
	  		document.all.item("ptzLight").src = "../images/ptz/lightsel.gif";
	  		m_bptzLight[parseInt(CurWndChannel.value)] = 1;   
       	}
	  	else
	 	{	
	    	var Error = PlayOCX.GetError();
			if(Error == 13)
			{
		    	alert(m_szAltTips1); 
	        	return;
			}
			else
			{
	        	alert(m_szAltTips2); 
	        	return;
			}
	  	}
   	}   
}
/*************************************************
Function:		ptzWiper
Description:	云台雨刷操作
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzWiper()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
   	if(m_bptzWiper[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(12,s);
	  	document.all.item("ptzWiper").src = "../images/ptz/wiper.gif";
	  	m_bptzWiper[parseInt(CurWndChannel.value)] = 0;
   	}
   	else
   	{
    	if(PlayOCX.ptzCtrlStart(12,s))
	  	{
	  		document.all.item("ptzWiper").src = "../images/ptz/wipersel.gif";
	  		m_bptzWiper[parseInt(CurWndChannel.value)] = 1;   
      	}
	  	else
	  	{		
	    	var Error = PlayOCX.GetError();
		  	if(Error == 13)
		  	{
		    	alert(m_szAltTips1); 
	         	return;
		  	}
		  	else
		  	{
	        	alert(m_szAltTips2); 
	          	return;
		   	}
	   	}
   	}   
}
/*************************************************
Function:		ptzTurnleftUpDowm
Description:	鼠标按下云台左上操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnleftUpDowm()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(13,s))
   	{
   		document.all.item("ptzLeftUp").src = "../images/ptz/ptz_left_upsel.gif";
   		document.getElementById("ptzLeftUp").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzTurnleftUpUp
Description:	鼠标释放云台左上操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnleftUpUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(13,s);
   	document.all.item("ptzLeftUp").src = "../images/ptz/ptz_left_up.gif";
   	document.getElementById("ptzLeftUp").releaseCapture();
}
/*************************************************
Function:		ptzTurnRightUpDowm
Description:	鼠标按下云台右上操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnRightUpDowm()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(14,s))
   	{
   		document.all.item("ptzRightUp").src = "../images/ptz/ptz_up_rightsel.gif";
   		document.getElementById("ptzRightUp").setCapture();
    }
	else
	{  
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzTurnRightUpUp
Description:	鼠标释放云台右上操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnRightUpUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(14,s);
   	document.all.item("ptzRightUp").src = "../images/ptz/ptz_up_right.gif";
   	document.getElementById("ptzRightUp").releaseCapture();
}
/*************************************************
Function:		ptzTurnLeftDownDowm
Description:	鼠标按下云台左下操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnLeftDownDowm()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(15,s))
   	{
    	document.all.item("ptzLeftDown").src = "../images/ptz/ptz_down_leftsel.gif";
      	document.getElementById("ptzLeftDown").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzTurnLeftDownUp
Description:	鼠标释放云台左下操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnLeftDownUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX");
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(15,s);
   	document.all.item("ptzLeftDown").src = "../images/ptz/ptz_down_left.gif";
   	document.getElementById("ptzLeftDown").releaseCapture();
}
/*************************************************
Function:		ptzTurnRightDownDown
Description:	鼠标按下云台右下操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnRightDownDown()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if(PlayOCX.ptzCtrlStart(16,s))
   	{
   		document.all.item("ptzRightDown").src = "../images/ptz/ptz_right_downsel.gif";
      	document.getElementById("ptzRightDown").setCapture();
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		ptzTurnRightDownUp
Description:	鼠标释放云台右下操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ptzTurnRightDownUp()
{   
	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	PlayOCX.ptzCtrlStop(16,s);
   	document.all.item("ptzRightDown").src = "../images/ptz/ptz_right_down.gif";
   	document.getElementById("ptzRightDown").releaseCapture();
}
/*************************************************
Function:		ExcutePresetDown
Description:	鼠标按下调用预置点操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ExcutePresetDown()
{  
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var iPresetIndex = document.getElementById("Preset").selectedIndex;
   	var PlayOCX = document.getElementById("NetVideoActiveX");
	ptzSpeed();
   	var s = m_iSpeed[CurSelWnd.value];
	if(m_bptzAuto[parseInt(CurWndChannel.value)])
   	{
    	PlayOCX.ptzCtrlStop(10,s);
	  	document.all.item("ptzAuto").src = "../images/ptz/ptz_auto.gif";
	  	m_bptzAuto[parseInt(CurWndChannel.value)] = 0;
   	}
   	if( PlayOCX.ptzCtrlGotoPreset(iPresetIndex+1))
   	{
    	document.all.item("ExcutePreset").src = "../images/ptz/gosel.gif"; 
       	document.getElementById("ExcutePreset").setCapture();   
   	}
   	else
   	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	}
}
/*************************************************
Function:		ExcutePresetUp
Description:	鼠标释放调用预置点操作按钮
Input:			无			
Output:			无
return:			无				
*************************************************/
function ExcutePresetUp()
{
	document.all.item("ExcutePreset").src = "../images/ptz/go.gif"; 
   	document.getElementById("ExcutePreset").releaseCapture();
}
/*************************************************
Function:		ShowVideoEffect
Description:	显示视频参数
Input:			无			
Output:			无
return:			无				
*************************************************/
function ShowVideoEffect()
{
	if(parseInt(CurWndChannel.value) == -1)
	{  
       for(var j=1;j<=10;j++)
       {     
	    	document.all.item("Bri"+j).src = "../images/ptz/color0.gif";
			document.all.item("Con"+j).src = "../images/ptz/color0.gif";
			document.all.item("Sat"+j).src = "../images/ptz/color0.gif";
			document.all.item("Hue"+j).src = "../images/ptz/color0.gif";
	   }	
	}
	else
	{   
		for(var i=1;i<=10;i++)
		{
		    if(i<=parseInt(Bright.value))
		    {
				document.all.item("Bri"+i).src = "../images/ptz/color00.gif";
			}
		    else
		    {
				document.all.item("Bri"+i).src = "../images/ptz/color0.gif";
			}
		    if(i<=parseInt(Contrast.value))
		    {
				document.all.item("Con"+i).src = "../images/ptz/color00.gif";
			}
		    else
		    {
				document.all.item("Con"+i).src = "../images/ptz/color0.gif";
			}
		    if(i<=parseInt(Saturation.value))
		    {
				document.all.item("Sat"+i).src = "../images/ptz/color00.gif";
			}
		    else
		    {
				document.all.item("Sat"+i).src = "../images/ptz/color0.gif";
			}
		    if(i<=parseInt(Hue.value))
		    {
				document.all.item("Hue"+i).src = "../images/ptz/color00.gif";
			}
		    else
		    {
				document.all.item("Hue"+i).src = "../images/ptz/color0.gif";
			}
		}
	}
}
/*************************************************
Function:		SetVideoParamBri
Description:	设置视频参数的亮度
Input:			iBright :	亮度值		
Output:			无
return:			无				
*************************************************/
function SetVideoParamBri(iBright)
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	Bright.value = iBright;
   	if(PlayOCX.SetVideoEffect(iBright,iContrast,iSaturation,iHue))
   	{
    	for(i=1;i<=10;i++)
      	{
        	if(i<=iBright)
	     	{
				document.all.item("Bri"+i).src = "../images/ptz/color00.gif";
		  	}
	     	else
	     	{
				document.all.item("Bri"+i).src = "../images/ptz/color0.gif";
		  	}
       	}
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 } 
}
/*************************************************
Function:		SetVideoParamCon
Description:	设置视频参数的对比度
Input:			iContrast :	对比度值		
Output:			无
return:			无				
*************************************************/
function SetVideoParamCon(iContrast)
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iBright = parseInt(Bright.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	Contrast.value = iContrast;
   	if(PlayOCX.SetVideoEffect(iBright,iContrast,iSaturation,iHue))
   	{
    	for(i=1;i<=10;i++)
      	{
        	if(i<=iContrast)
	     	{
				document.all.item("Con"+i).src = "../images/ptz/color00.gif";
		 	}
	     	else
	     	{
				document.all.item("Con"+i).src = "../images/ptz/color0.gif";
		  	}
       	}
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		SetVideoParamSat
Description:	设置视频参数的饱和度
Input:			iSaturation :	饱和度值		
Output:			无
return:			无				
*************************************************/
function SetVideoParamSat(iSaturation)
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iBright = parseInt(Bright.value);
   	var iContrast = parseInt(Contrast.value);
   	var iHue = parseInt(Hue.value);
   	Saturation.value = iSaturation;
   	if(PlayOCX.SetVideoEffect(iBright,iContrast,iSaturation,iHue))
   	{
    	for(i=1;i<=10;i++)
      	{
        	if(i<=iSaturation)
	     	{
				document.all.item("Sat"+i).src = "../images/ptz/color00.gif";
		 	}
	     	else
	     	{
				document.all.item("Sat"+i).src = "../images/ptz/color0.gif";
		 	}
      	}
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		SetVideoParamHue
Description:	设置视频参数的色调
Input:			iHue :	色调值		
Output:			无
return:			无				
*************************************************/
function SetVideoParamHue(iHue)
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iBright = parseInt(Bright.value);
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	Hue.value = iHue;
   	if(PlayOCX.SetVideoEffect(iBright,iContrast,iSaturation,iHue))
   	{
    	for(i=1;i<=10;i++)
      	{
        	if(i<=iHue)
	     	{
				document.all.item("Hue"+i).src = "../images/ptz/color00.gif";
		 	}
	     	else
	     	{
				document.all.item("Hue"+i).src = "../images/ptz/color0.gif";
		 	}
       	}
    }
	else
	{	
	    var Error = PlayOCX.GetError();
		if(Error == 13)
		{
		    alert(m_szAltTips1); 
	        return;
		}
		else
		{
	        alert(m_szAltTips2); 
	        return;
		}
	 }
}
/*************************************************
Function:		SetVideoDefaultDown
Description:	鼠标按下设置视频参数为默认值的按钮
Input:			无		
Output:			无
return:			无				
*************************************************/
function SetVideoDefaultDown()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	if(!(PlayOCX.SetVideoEffect("6","6","6","6")))
   	{
       var Error = PlayOCX.GetError();
       if(Error == 13)
       {
	       alert(m_szAltTips1); 
	       return;
        }
    }
	else
	{
	    for(var i=1;i<=10;i++)
      	{
        	if(i <= 6)
	     	{
				document.all.item("Bri"+i).src = "../images/ptz/color00.gif";
				document.all.item("Con"+i).src = "../images/ptz/color00.gif";
				document.all.item("Sat"+i).src = "../images/ptz/color00.gif";
				document.all.item("Hue"+i).src = "../images/ptz/color00.gif";
		  	}
	     	else
	     	{
				document.all.item("Bri"+i).src = "../images/ptz/color0.gif";
				document.all.item("Con"+i).src = "../images/ptz/color0.gif";
				document.all.item("Sat"+i).src = "../images/ptz/color0.gif";
				document.all.item("Hue"+i).src = "../images/ptz/color0.gif";
		  	}
       	}
		Bright.value = 6;
		Contrast.value = 6;
		Saturation.value = 6;
		Hue.value = 6;
	}
   	document.all.item("VideoDefault").src = "../images/ptz/setdefaultsel.gif";
   	document.getElementById("VideoDefault").setCapture(); 
}
/*************************************************
Function:		SetVideoDefaultUp
Description:	鼠标释放设置视频参数为默认值的按钮
Input:			无		
Output:			无
return:			无				
*************************************************/
function SetVideoDefaultUp()
{
	document.all.item("VideoDefault").src = "../images/ptz/setdefault.gif";
    document.getElementById("VideoDefault").releaseCapture();
}
/*************************************************
Function:		downSetVideoParamBri
Description:	鼠标按下设置视频参数的亮度减小
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamBri()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	var iBright = parseInt(Bright.value);
   	if(iBright > 1)
   	{
    	if(!(PlayOCX.SetVideoEffect( iBright - 1,iContrast,iSaturation,iHue)))
	   	{	   
	    	var Error = PlayOCX.GetError();
		   	if(Error == 13)
		   	{
		    	alert(m_szAltTips1); 
	           	return;
		   	}
		  	else
		   	{
	        	alert(m_szAltTips2); 
	           	return;
		    }
	    } 
       i = iBright;
	   document.all.item("Bri"+i).src = "../images/ptz/color0.gif";
	   document.all.item("Brightness").src = "../images/ptz/colorsmallsel.gif";
	   document.getElementById("Brightness").setCapture();
	   if(iBright>1)
	   { 
		    Bright.value --;
	   }     
    }
 
}
/*************************************************
Function:		downSetVideoParamBritwo
Description:	鼠标按下设置视频参数的亮度增加
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamBritwo()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast=parseInt(Contrast.value);
   	var iSaturation=parseInt(Saturation.value);
   	var iHue=parseInt(Hue.value);
   	var iBright=parseInt(Bright.value);
   	if(iBright<=9)
   	{
       if(!(PlayOCX.SetVideoEffect( iBright + 1,iContrast,iSaturation,iHue)))
	   {	   
	       var Error = PlayOCX.GetError();
		   if(Error == 13)
		   {
		       alert(m_szAltTips1); 
	           return;
		   }
		  	else
		   {
	          	alert(m_szAltTips2); 
	           	return;
		    }
	    } 
	    i = iBright + 1;
		document.all.item("Bri"+i).src="../images/ptz/color00.gif";
		document.all.item("Brightnesstwo").src="../images/ptz/colorsel.gif";
	    document.getElementById("Brightnesstwo").setCapture();
		if(iBright<10)
		{
			Bright.value++;
		}
    }
}
/*************************************************
Function:		upSetVideo
Description:	释放鼠标按下设置视频参数亮度的减小
Input:			无		
Output:			无
return:			无				
*************************************************/
function upSetVideo()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	document.all.item("Brightness").src = "../images/ptz/colorsmall.gif";
   	document.getElementById("Brightness").releaseCapture();
   	document.all.item("dbd").src = "../images/ptz/colorsmall.gif";
   	document.getElementById("dbd").releaseCapture();
   	document.all.item("bhd").src = "../images/ptz/colorsmall.gif";
   	document.getElementById("bhd").releaseCapture();
   	document.all.item("sd").src = "../images/ptz/colorsmall.gif";
   	document.getElementById("sd").releaseCapture();
}
/*************************************************
Function:		upSetVideotwo
Description:	释放鼠标按下设置视频参数的增大
Input:			无		
Output:			无
return:			无				
*************************************************/
function upSetVideotwo()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
        return;
   	}
   	document.all.item("Brightnesstwo").src = "../images/ptz/color.gif";
   	document.getElementById("Brightnesstwo").releaseCapture();
   	document.all.item("dbdtwo").src = "../images/ptz/color.gif";
   	document.getElementById("dbdtwo").releaseCapture();
    document.all.item("bhdtwo").src = "../images/ptz/color.gif";
   	document.getElementById("bhdtwo").releaseCapture();
    document.all.item("sdtwo").src = "../images/ptz/color.gif";
   	document.getElementById("sdtwo").releaseCapture();
}
/*************************************************
Function:		downSetVideoParamCon
Description:	鼠标按下设置视频参数的对比度减小
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamCon()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	var iBright = parseInt(Bright.value);
   	if(iContrast>1)
   	{
    	if(!(PlayOCX.SetVideoEffect( iBright,iContrast - 1,iSaturation,iHue)))
	   	{	   
	    	var Error = PlayOCX.GetError();
		   	if(Error == 13)
		   	{
		    	alert(m_szAltTips1); 
	           	return;
		   	}
		  	else
		   	{
	        	alert(m_szAltTips2); 
	           	return;
		    }
	    }  
	    i=iContrast;
	    document.all.item("Con"+i).src = "../images/ptz/color0.gif";
		document.all.item("dbd").src = "../images/ptz/colorsmallsel.gif";
	    document.getElementById("dbd").setCapture();
		if(iContrast > 1 )
		{ 
			Contrast.value --;
		}
   	} 
}
/*************************************************
Function:		downSetVideoParamContwo
Description:	鼠标按下设置视频参数的对比度增加
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamContwo()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	var iBright = parseInt(Bright.value);
   	if(iContrast<=9)
   	{
    	if(!(PlayOCX.SetVideoEffect( iBright,iContrast + 1,iSaturation,iHue)))
	   	{	   
	    	var Error = PlayOCX.GetError();
		   	if(Error == 13)
		   	{
		    	alert(m_szAltTips1); 
	           	return;
		   	}
		  	else
		   	{
	        	alert(m_szAltTips2); 
	           	return;
		    }
	    } 
        i = iContrast + 1;
	    document.all.item("Con"+i).src = "../images/ptz/color00.gif";
		document.all.item("dbdtwo").src = "../images/ptz/colorsel.gif";
	    document.getElementById("dbdtwo").setCapture();
	    if(iContrast<10)
		{
			  Contrast.value++;
		}
  } 
 
}
/*************************************************
Function:		downSetVideoParamSat
Description:	鼠标按下设置视频参数的饱和度减小
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamSat()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	var iBright = parseInt(Bright.value);
   	if(iSaturation>1)
   	{
    	if(!(PlayOCX.SetVideoEffect( iBright,iContrast,iSaturation - 1,iHue)))
	   	{	   
	    	var Error = PlayOCX.GetError();
		   	if(Error == 13)
		   	{
		    	alert(m_szAltTips1); 
	           	return;
		   	}
		  	else
		   	{
	        	alert(m_szAltTips2); 
	           	return;
		    }
	    } 
        i=iSaturation;
		document.all.item("Sat"+i).src = "../images/ptz/color0.gif";
		document.all.item("bhd").src = "../images/ptz/colorsmallsel.gif";
	    document.getElementById("bhd").setCapture();
	    if(iSaturation>1)
		{
			  Saturation.value --;
        } 
    }	
}
/*************************************************
Function:		downSetVideoParamSattwo
Description:	鼠标按下设置视频参数的饱和度增加
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamSattwo()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	var iBright = parseInt(Bright.value);
   	if(iSaturation<=9)
   	{
    	if(!(PlayOCX.SetVideoEffect( iBright,iContrast,iSaturation + 1,iHue)))
	   	{	   
	    	var Error = PlayOCX.GetError();
		   	if(Error == 13)
		   	{
		    	alert(m_szAltTips1); 
	           	return;
		   	}
		  	else
		   	{
	        	alert(m_szAltTips2); 
	           	return;
		    }
	    } 
        i=iSaturation + 1;
		document.all.item("Sat"+i).src = "../images/ptz/color00.gif";
		document.all.item("bhdtwo").src = "../images/ptz/colorsel.gif";
	    document.getElementById("bhdtwo").setCapture();
		if(iSaturation<10)
	    {
			  Saturation.value ++;
		}
    }
}
/*************************************************
Function:		downSetVideoParamHue
Description:	鼠标按下设置视频参数的色调减小
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamHue()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	var iBright = parseInt(Bright.value);
   	if(iHue>1)
   	{
    	if(!(PlayOCX.SetVideoEffect( iBright,iContrast,iSaturation,iHue - 1)))
	   	{	   
	    	var Error = PlayOCX.GetError();
		   	if(Error == 13)
		   	{
		    	alert(m_szAltTips1); 
	           	return;
		   	}
		  	else
		   	{
	        	alert(m_szAltTips2); 
	           	return;
		    }
	    } 
        i=iHue;
		document.all.item("Hue"+i).src = "../images/ptz/color0.gif";
		document.all.item("sd").src = "../images/ptz/colorsmallsel.gif";
	    document.getElementById("sd").setCapture();
		if(iHue>1)
	    {
			 Hue.value --;
	    }
    }	
}
/*************************************************
Function:		downSetVideoParamHuetwo
Description:	鼠标按下设置视频参数的色调增加
Input:			无		
Output:			无
return:			无				
*************************************************/
function downSetVideoParamHuetwo()
{
	var iStart = m_iAllChanTotal - m_iZeroChanNum; 
	var iStop = parseInt(CurWndChannel.value);
	if(parseInt(RealplayHandle.value) < 0 || iStop >= iStart)
   	{
		if(iStop >= iStart)
		{
			alert(m_szZeroPtzTips);	
		}
        return;
   	}
   	var PlayOCX = document.getElementById("NetVideoActiveX"); 
   	var iContrast = parseInt(Contrast.value);
   	var iSaturation = parseInt(Saturation.value);
   	var iHue = parseInt(Hue.value);
   	var iBright = parseInt(Bright.value);
   	if(iHue<=9)
   	{
    	if(!(PlayOCX.SetVideoEffect( iBright,iContrast,iSaturation,iHue + 1)))
	   	{	   
	    	var Error = PlayOCX.GetError();
		   	if(Error == 13)
		   	{
		    	alert(m_szAltTips1); 
	           	return;
		   	}
		  	else
		   	{
	        	alert(m_szAltTips2); 
	           	return;
		    }
	    } 
        i=iHue + 1;
		document.all.item("Hue"+i).src = "../images/ptz/color00.gif";
		document.all.item("sdtwo").src = "../images/ptz/colorsel.gif";
	    document.getElementById("sdtwo").setCapture();
		if(iHue<10)
	    {
			  Hue.value ++;
	    } 
    }
}
/*************************************************
Function:		SetptzBtnState
Description:	云台自转、灯光雨刷状态显示
Input:			无		
Output:			无
return:			无				
*************************************************/
function SetptzBtnState()
{
	if(m_bptzAuto[parseInt(CurWndChannel.value)])
	{
		document.all.item("ptzAuto").src="../images/ptz/ptz_autosel.gif";
	}
   	else
	{
		document.all.item("ptzAuto").src="../images/ptz/ptz_auto.gif";
	}   
   	if(m_bptzLight[parseInt(CurWndChannel.value)])
	{
		document.all.item("ptzLight").src="../images/ptz/lightsel.gif";
	}
   	else
	{
		document.all.item("ptzLight").src="../images/ptz/light.gif";
	}   
   	if(m_bptzWiper[parseInt(CurWndChannel.value)])
	{
		document.all.item("ptzWiper").src="../images/ptz/wipersel.gif";
	}   
   	else
	{
		document.all.item("ptzWiper").src="../images/ptz/wiper.gif";
	}   		
}
/**********************************
Function:		DecryptHj
Description:	解密
Input:			str：需要解密的字符	
				pwd: 解密密码
Output:			无
return:			unescape(enc_str)：返回解密后的字符		
***********************************/
function DecryptHj(str, pwd)
{
    if(str == "")
	{
		return "";
    }
	if(!pwd || pwd=="")
	{ 
		var pwd="hujun"; 
	}
    pwd = escape(pwd);
    if(str == null || str.length < 8)
	{
    	alert("A salt value could not be extracted from the encrypted message because it's length is too short. The message cannot be decrypted.");
        return;
    }
    if(pwd == null || pwd.length <= 0) 
	{
        alert("Please enter a password with which to decrypt the message.");
        return;
    }
    var prand = "";
    for(var I=0; I<pwd.length; I++) 
	{
        prand += pwd.charCodeAt(I).toString();
    }
    var sPos = Math.floor(prand.length / 5);
    var mult = parseInt(prand.charAt(sPos) + prand.charAt(sPos*2) + prand.charAt(sPos*3) + prand.charAt(sPos*4) + prand.charAt(sPos*5));
    var incr = Math.round(pwd.length / 2);
    var modu = Math.pow(2, 31) - 1;
    var salt = parseInt(str.substring(str.length - 8, str.length), 16);
    str = str.substring(0, str.length - 8);
    prand += salt;
    while(prand.length > 10) 
	{
        prand = (parseInt(prand.substring(0, 10)) + parseInt(prand.substring(10, prand.length))).toString();
    }
    prand = (mult * prand + incr) % modu;
    var enc_chr = "";
    var enc_str = "";
    for(var I=0; I<str.length; I+=2) 
	{
        enc_chr = parseInt(parseInt(str.substring(I, I+2), 16) ^ Math.floor((prand / modu) * 255));
        enc_str += String.fromCharCode(enc_chr);
        prand = (mult * prand + incr) % modu;
    }
    return unescape(enc_str);
}