//Wlk_qt function
//This is v1, PosterMovie added, items cleaned up.....This is version 0.9, kioskmode added....v 0.8 was a failure.......v 0.7 was QTVR stuff......name change only v 0.5 ....adding in JS embed that actually works! (but sucks to use as it no longer involves just one tag)

//love, walker.

function write_wlkqt(video, width, height, controller, scale, qttype, autoplay, bgcolor, mvname, node, pan, tilt, fov, kiosk, loop, pmtype, postermovie)
{
	str = '<OBJECT CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B"  CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab#version=7,0,0,0" WIDTH="'+width+'" HEIGHT="'+height+'"><PARAM NAME="scale" VALUE="'+scale+'"><PARAM NAME="type" VALUE="'+qttype+'"><PARAM NAME="target" VALUE="myself"><PARAM NAME="pluginspage" VALUE="http://www.apple.com/quicktime/download/index.html">';
	if(postermovie!=null)
	{
		str = str+'<PARAM NAME="src" VALUE="'+postermovie+'"><PARAM NAME="href" VALUE="'+video+'"><PARAM NAME="autoplay" VALUE="'+autoplay+'"><PARAM NAME="controller" VALUE="'+controller+'"><PARAM NAME="loop" VALUE="true">';
	} else {
		str = str+'<PARAM NAME="src" VALUE="'+video+'">'+'<PARAM NAME="autoplay" VALUE="'+autoplay+'">'+'<PARAM NAME="controller" VALUE="'+controller+'">';
		if(loop!=null)
		{
			str = str+'<PARAM NAME="loop" VALUE="'+loop+'">';
		}
	}
	if(bgcolor!=null)
	{
		str = str+'<PARAM NAME="bgcolor" VALUE="'+bgcolor+'">';
	}
	if(mvname!=null)
	{
		str = str+'<PARAM NAME="moviename" VALUE="'+mvname+'">';
	}
	if(node!=null)
	{
		str = str+'<PARAM NAME="node" VALUE="'+node+'">';
	}
	if(pan!=null)
	{
		str = str+'<PARAM NAME="pan" VALUE="'+pan+'">';
	}
	if(tilt!=null)
	{
		str = str+'<PARAM NAME="tilt" VALUE="'+tilt+'">';
	}
	if(fov!=null)
	{
		str = str+'<PARAM NAME="fov" VALUE="'+fov+'">';
	}
	if(kiosk=='true')
	{
		str = str+'<PARAM NAME="kioskmode" VALUE="true">';
	}
	str = str+'<EMBED WIDTH="'+width+'" HEIGHT="'+height+'" SCALE="'+scale+'" TARGET="myself" type="'+qttype+'"';
	if(postermovie!=null)
	{
		str = str+' SRC="'+postermovie+'" HREF="'+video+'" CONTROLLER="false" LOOP="true" AUTOPLAY="true"';
	} else {
		str = str+' SRC="'+video+'" CONTROLLER="'+controller+'" AUTOPLAY="'+autoplay+'"';
		if(loop!=null)
		{
			str = str+' LOOP="'+loop+'"';	
		}
	}
	if(bgcolor!=null)
	{
		str = str+' BGCOLOR="'+bgcolor+'"';
	}
	if(mvname!=null)
	{
		str = str+' MOVIENAME="'+mvname+'"';	
	}
	if(node!=null)
	{
		str = str+' NODE="'+node+'"';	
	}
	if(pan!=null)
	{
		str = str+' PAN="'+pan+'"';	
	}
	if(tilt!=null)
	{
		str = str+' TILT="'+tilt+'"';	
	}
	if(fov!=null)
	{
		str = str+' FOV="'+fov+'"';	
	}
	if(kiosk=='true')
	{
		str = str+' KIOSKMODE="true"';	
	}
	str = str+' PLUGINSPAGE="http://www.apple.com/quicktime/download/index.html"></EMBED></OBJECT>';
	document.write(str);
}