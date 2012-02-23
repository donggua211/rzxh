document.charset = "utf-8";
/*************************************************
Function:		GetNowTimeYears
Description:	获取当前日期年份
Input:			无			
Output:			无
return:			无				
*************************************************/
function GetNowTimeYears()
{
	if(m_szLanguage == "cn")
   	{
		szTips = "为了正常使用本软件，请将系统日期年限设置在1971-2037范围内！";
	}
	if(m_szLanguage == "en")
	{
		szTips = "In order to use this software normally, please setup the system date between year 1971 and 2037.";
	}	
	var myDate = new Date();
	var iYear = myDate.getFullYear();        
	if(iYear < 1971 || iYear > 2037)
	{
		alert(szTips);
		return 1;
	}
	return 0;
}
/*************************************************
Function:		WhatError
Description:	操作失败的具体原因
Input:			无			
Output:			无
return:			iErrorValue :返回错误值				
*************************************************/
function WhatError(OCX)
{
	var iErrorValue;
	iErrorValue = OCX.GetError();
	if(m_szLanguage == "cn")
   	{
		szTips1 = "用户名密码错误！"; 
		szTips2 = "权限不足！";
		szTips3 = "没有初始化！"; 
		szTips4 = "通道号错误！";
		szTips5 = "连接到DVR的客户端个数超过最大！"; 
		szTips6 = "版本不匹配！";
		szTips7 = "连接服务器失败！"; 
		szTips8 = "向服务器发送失败！";
		szTips9 = "从服务器接收数据失败！"; 
		szTips10 = "从服务器接收数据超时！";
		szTips11 = "传送的数据有误！"; 
		szTips12 = "调用次序错误！";
		szTips13 = "无此权限！"; 
		szTips14 = "DVR命令执行超时！";
		szTips15 = "串口号错误！"; 
		szTips16 = "报警端口错误！";
		szTips17 = "参数错误！"; 
		szTips18 = "服务器通道处于错误状态！";
		szTips19 = "没有硬盘！"; 
		szTips20 = "硬盘号错误！";
		szTips21 = "服务器硬盘满！"; 
		szTips22 = "服务器硬盘出错！";
		szTips23 = "服务器不支持！"; 
		szTips24 = "服务器忙！";
		szTips25 = "服务器修改不成功！"; 
		
		szTips26 = "密码输入格式不正确！";
		szTips27 = "硬盘正在格式化，不能启动操作！"; 
		szTips28 = "DVR资源不足！";
		szTips29 = "DVR操作失败！"; 
		szTips30 = "打开PC声音失败！";
		szTips31 = "服务器语音对讲被占用！"; 
		szTips32 = "时间输入不正确！";
		szTips33 = "回放时服务器没有指定的文件！"; 
		szTips34 = "创建文件出错！";
		szTips35 = "打开文件出错！"; 
		szTips36 = "上次的操作还没有完成！";
		szTips37 = "获取当前播放的时间出错！"; 
		szTips38 = "播放出错！";
		szTips39 = "文件格式不正确！"; 
		szTips40 = "路径错误！";
		szTips41 = "资源分配错误！"; 
		szTips42 = "声卡模式错误！";
		szTips43 = "缓冲区太小！"; 
		szTips44 = "创建SOCKET出错！";
		szTips45 = "设置SOCKET出错！"; 
		szTips46 = "个数达到最大！";
		szTips47 = "用户不存在！"; 
		szTips48 = "写FLASH出错！";
		szTips49 = "DVR升级失败！"; 
		szTips50 = "解码卡已经初始化过！";
		szTips51 = "播放器中错误！"; 
		szTips52 = "用户数达到最大！";
		szTips53 = "获得客户端的IP地址或物理地址失败！"; 
		szTips54 = "该通道没有编码！";
		szTips55 = "IP地址不匹配！"; 
		szTips56 = "MAC地址不匹配！";
		szTips57 = "升级文件语言不匹配！"; 
		szTips58 = "本地显卡不支持！";	

	}
	if(m_szLanguage == "en")
	{
		szTips1 = "User name or password error."; 
		szTips2 = "Not enough privilege.";
		szTips3 = "Device is not inited."; 
		szTips4 = "Channel no. error.";
		szTips5 = "Exceed max DVR client connection."; 
		szTips6 = "Version unmatch.";
		szTips7 = "Connect server failed."; 
		szTips8 = "Send data to server failed.";
		szTips9 = "Get data from server failed."; 
		szTips10 = "Timeout when getting data from server.";
		szTips11 = "Error in sending data."; 
		szTips12 = "Calling reference error.";
		szTips13 = "No enough privilege."; 
		szTips14 = "DVR operation timeout.";
		szTips15 = "Serial port no. error."; 
		szTips16 = "Alarm port error.";
		szTips17 = "Parameter error."; 
		szTips18 = "Channel status error on server.";
		szTips19 = "No hard disk."; 
		szTips20 = "Hard disk no. error.";
		szTips21 = "Server hard disk full."; 
		szTips22 = "Server hard disk error.";
		szTips23 = "Not supported by server."; 
		szTips24 = "Server busy.";
		szTips25 = "Server modification failed."; 
		
		szTips26 = "Password format error.";
		szTips27 = "Cannot start operation when formatting hard disk."; 
		szTips28 = "Not enough DVR resource.";
		szTips29 = "DVR operation failed."; 
		szTips30 = "Open PC sound failed.";
		szTips31 = "Voice talking  has already been occupied on server."; 
		szTips32 = "Time input error.";
		szTips33 = "No relative files on server."; 
		szTips34 = "Create file error.";
		szTips35 = "Open file failed."; 
		szTips36 = "Last operation not completed.";
		szTips37 = "Get current time position error."; 
		szTips38 = "Error during playback.";
		szTips39 = "File format error."; 
		szTips40 = "Path error.";
		szTips41 = "Resource allocation error."; 
		szTips42 = "Sound adpater mode error.";
		szTips43 = "Not enough buffer size."; 
		szTips44 = "Create SOCKET error.";
		szTips45 = "Set SOCKET error."; 
		szTips46 = "Reach maximum number.";
		szTips47 = "User name does not exist."; 
		szTips48 = "Flash writing error.";
		szTips49 = "DVR upgrading failed."; 
		szTips50 = "MDI card already inited.";
		szTips51 = "Player error."; 
		szTips52 = "Reach max user number.";
		szTips53 = "Get client IP or MAC failed."; 
		szTips54 = "No encoding on this channel.";
		szTips55 = "IP unmatch ."; 
		szTips56 = "MAC unmatch .";
		szTips57 = "Firmware language unmatch ."; 
		szTips58 = "Not supported by local display adapter.";	
	}
	switch(iErrorValue) 
	{
	     case 0:
			  //alert("操作失败！");    
		      break;
	     case 1:
			  alert(szTips1);
			  break;			   
	     case 2:	               
			  alert(szTips2);
			  break;
	     case 3:
			  alert(szTips3);
		      break;		 
	     case 4:
			  alert(szTips4);   
		      break;
	     case 5:
			  alert(szTips5);
			  break;			   
	     case 6:	               
			  alert(szTips6);
			  break;
	     case 7:
			  alert(szTips7);
		      break;
	     case 8:
			  alert(szTips8);   
		      break;
	     case 9:
			  alert(szTips9);
			  break;			   
	     case 10:	               
			  alert(szTips10);
			  break;
	     case 11:
			  alert(szTips11);
		      break;		 
	     case 12:
			  alert(szTips12);  
		      break;
	     case 13:
			  alert(szTips13);
			  break;			   
	     case 14:	               
			  alert(szTips14);
			  break;
	     case 15:
			  alert(szTips15);
		      break;
	     case 16:	               
			  alert(szTips16);
			  break;
	     case 17:
			  alert(szTips17);
		      break;		 
	     case 18:
			  alert(szTips18);  
		      break;
	     case 19:
			  alert(szTips19);
			  break;			   
	     case 20:	               
			  alert(szTips20);
			  break;
	     case 21:
			  alert(szTips21);
		      break;	
	     case 22:	               
			  alert(szTips22);
			  break;
	     case 23:
			  alert(szTips23);
		      break;		 
	     case 24:
			  alert(szTips24);  
		      break;
	     case 25:
			  alert(szTips25);
			  break;			   
	     case 26:	               
			  alert(szTips26);
			  break;
	     case 27:
			  alert(szTips27);
		      break;
	     case 28:	               
			  alert(szTips28);
			  break;
	     case 29:
			  alert(szTips29);
		      break;		 
	     case 30:
			  alert(szTips30);
		      break;	 
		 case 31:
			  alert(szTips31);
		      break;	
	     case 32:	               
			  alert(szTips32);
			  break;
	     case 33:
			  alert(szTips33);
		      break;		 
	     case 34:
			  alert(szTips34);   
		      break;
	     case 35:
			  alert(szTips35);
			  break;			   
	     case 36:	               
			  alert(szTips36);
			  break;
	     case 37:
			  alert(szTips37);
		      break;
	     case 38:	               
			  alert(szTips38);
			  break;
	     case 39:
			  alert(szTips39);
		      break;		 
	     case 40:
			  alert(szTips40);
		      break;
		 case 41:
			  alert(szTips41);
		      break;	
	     case 42:	               
			  alert(szTips42);
			  break;
	     case 43:
			  alert(szTips43);
		      break;		 
	     case 44:
			  alert(szTips44);   
		      break;
	     case 45:
			  alert(szTips45);
			  break;			   
	     case 46:	               
			  alert(szTips46);
			  break;
	     case 47:
			  alert(szTips47);
		      break;
	     case 48:	               
			  alert(szTips48);
			  break;
	     case 49:
			  alert(szTips49);
		      break;		 
	     case 50:
			  alert(szTips50);
		      break;
		 case 51:
			  alert(szTips51);
		      break;	
	     case 52:	               
			  alert(szTips52);
			  break;
	     case 53:
			  alert(szTips53);
		      break;		 
	     case 54:
			  alert(szTips54);   
		      break;
	     case 55:
			  alert(szTips55);
			  break;			   
	     case 56:	               
			  alert(szTips56);
			  break;
	     case 57:
			  alert(szTips57);
		      break;
	     case 58:	               
			  alert(szTips58);
			  break;
	}
	return iErrorValue;
}