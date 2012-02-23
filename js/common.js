function UrlDecode(str)
{
	var ret="";
	for(var i=0;i<str.length;i++){
		var chr = str.charAt(i);
		if(chr == "+"){
			ret+=" ";
		}else if(chr=="%"){
			var asc = str.substring(i+1,i+3);
			if(parseInt("0x"+asc)>0x7f){
				ret+=asc2str(parseInt("0x"+asc+str.substring(i+4,i+6)));
				i+=5;
			}else{
				ret+=asc2str(parseInt("0x"+asc));
				i+=2;
			}
		}else{
			ret+= chr;
		}
	}
	return ret;
}