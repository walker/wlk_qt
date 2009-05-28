<?php

$plugin['version'] = '0.9.8';
$plugin['author'] = 'Walker Hamilton';
$plugin['author_uri'] = 'http://www.walkerhamilton.com';
$plugin['description'] = 'A quicktime embed plugin for all those new H264 movies. (With Javascript Option!)';

$plugin['type'] = 0; 

@include_once(dirname(dirname(__FILE__)).'/zem_tpl.php');

if (0) {
?>
# --- BEGIN PLUGIN HELP ---
h1. wlk_qt

v0.9.7 : Fixed a problem whereby it wouldn't properly detect movies because of if:else statement order.

This plugin is an integration of the Quicktime Player.
This plugin embeds quicktime videos in your textpattern forms.



h2. Installation

Since you can read this help, you have installed the plugin to txp.
Did you activate it?



h2. Usage

Place the wlk_qt tag in an article, form or page.

Go to _admin_ -> _preferences_ -> _advanced preferences_ and create a custom form field called video.

embed the tag in your article or page form.

@
<txp:wlk_qt width="320" height="240" />
@

It takes a few options:

* *width* - specifies width. defaults to 320px.
* *height* - specifies height. defaults to 256px. (take your movie size and add 16px if you want the controller to display properly)
* *controller* - can be true or false. defaults to true. this means, it displays the controller by default.
* *autoplay* - can be true or false. defaults to true. this means the movie starts playing automatically.
* *qttype* - can be set to the mime-type you want. defaults to 'video/quicktime'. you can find more mime-types someplace like..... "here":http://www.webmaster-toolkit.com/mime-types.shtml
* *scale* - can be set to 'ToFit', 'Aspect', or _n_ (_n_ being a number that determines the scaling factor). To Fit fits it to the width and height you specify. Aspect fits it within the width and height you specify while presevering the aspect ratio.
* *js* - can be set to true or false. defaults to false. if it's true, then the quicktime movie is embedded in the page using javascript...this is a bit more complicated. Make sure you read about this option later. (It's helpful for Eolas disabled browsers)
* *bgcolor* - can be set to a hex value or a simple color name, e.g. "red" or "5a5a5a". If not set, does not get written to page. Does not get written to page if the value is not a hex value or one of the supported colors found on "this page":http://developer.apple.com/documentation/QuickTime/Conceptual/QTScripting_HTML/QTScripting_HTML_Document/chapter_1000_section_3.html.
* *mvname* - can be set to a str that names the movie (for tageting from another movie). If not set, does not get written to page.
* *fov* - Can be set to a number (0-360) that indicates the initial field of view for QTVR. If not set, does not get written to page.
* *node* - Can be set to a number that indicates the initial node of a multinode QTVR movie. If not set, does not get written to page.
* *pan* - Can be set to a number (0-360) that indicates initial pan degrees. If not set, does not get written to page.
* *tilt* - Can be set to a number (0-360) that indicates initial tilt degrees. If not set, does not get written to page.
* *kioskmode* - If you specify this in the wlk_qt txp tag, it turns it on. If you want it to remain off, just don't specify it.
* *loop* - Allows you to set the movie or audio to loop. Can be set to true or palindrome (beats me), or false. Outputs no loop settings if you don't specify it.
* *fieldname* - This allows you to specify a particular custom_field by name that wlk_qt should check in to see if a movie is set. (defaults to "video")
* *postermovie* - This is used if you want to specify a particular postermovie. Poster movies are single second, looping movies or image files (png or jpeg) that are clicked on to load the "main" movie. Set this to the id# of the image or file you want to use. If you're using an image rather than a looping movie make sure you specify pmtype="image/quicktime" in the wlk_qt tag.
* *pmtype* - This can be used to specify what type of poster movie you'll be using to front your movies (This is optional). If not set and a postermovie is specified, it uses "video/quicktime" by default.
* *pmfieldname* - This allows you to specify a particular custom_field by name that wlk_qt should check in to see if a movie is set. (defaults to "poster")



h2. Embedding a specific movie in a page:

If you specify video as an attribute you can use the file number from the files tab (if you uploaded the video in this manner) 

@
<txp:wlk_qt video="6" />
@

or you can use the full path to the file.

@
<txp:wlk_qt video="http://www.mydomain.com/movies/mymovie.mov" />
@



h2. Attaching a movie to an article:

In this case, you use that custom 'video' field and enter the full path or your file number in that field. The the form being used to output the article simply needs <txp:wlk_qt /> in it somewhere (with any other attributes you want to specify - e.g. width, height, autoplay).

My form looks like this:

@
<h3><txp:permlink><txp:title /></txp:permlink></h3>
<txp:wlk_qt width="320" height="240" />
<txp:body />
@

p. That outputs the article on the page where this form is used with the title of the article as a header then the movie as 320px by 240px, then the text that was in the body field of the article form.

h2. Embedding with Javascript.

You must first upload "wlk_qt.js":http://dev.signalfade.com/txp/wlk_js.zip to your server. (you'll need to download it, unzip it, then upload it)

Then put this in the header of the page you wish to display movies on (every page....yes):

@
<txp:wlk_scriptlink path="http://mydomain.com/the/path/to/wlk_qt.js" />
@

Where you find "http://mydomain.com/the/path/to/wlk_qt.js", make sure you put the full path to the file.

Then, like normal, put the txp:wlk_qt into the form where you normally would with all the options you normally use:

@
<h3><txp:permlink><txp:title /></txp:permlink></h3>
<txp:wlk_qt width="320" height="240" js="true" />
<txp:body />
@

Notice that you have to add @js="true"@ to the options.

p.s. If you use javascript embed.....it automatically gets a div with id="wlk_qt_video" wrapped around it!

Hopefully you're rockin' javascript embeds now! &#42;fingers crossed&#42;

# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---
function wlk_qt($atts) {
		global $prefs;
		global $permlink_mode;
		global $thisarticle;

		$colorArr = array('BLACK','GREEN','SILVER','LIME','GRAY','OLIVE','WHITE','YELLOW','MAROON','NAVY','RED','BLUE','PURPLE','TEAL','FUCHSIA','AQUA');

		extract(lAtts(array(
			'video'=>'',
			'postermovie'=>'',
			'pmset'=>'true',
			'width'=> (!empty($prefs['wlk_qt_width']))?$prefs['wlk_qt_width']:'320',
			'height'=> (!empty($prefs['wlk_qt_height']))?$prefs['wlk_qt_height']:'256',
			'scale'=> (!empty($prefs['wlk_qt_scale']))?$prefs['wlk_qt_scale']:'tofit',
			'controller'=>(!empty($prefs['wlk_qt_controller']))?$prefs['wlk_qt_controller']:'true',
            'autoplay'=>(!empty($prefs['wlk_qt_autoplay']))?$prefs['wlk_qt_autoplay']:'true',
			'qttype'=>(!empty($prefs['wlk_qt_qttype']))?$prefs['wlk_qt_qttype']:'video/quicktime',
			'pmtype'=>(!empty($prefs['wlk_qt_pmtype']))?$prefs['wlk_qt_pmtype']:'video/quicktime',
			'js'=>(!empty($prefs['wlk_qt_qttype']))?$prefs['wlk_qt_js']:'false',
			'fieldname'=>(!empty($prefs['wlk_qt_fieldname']))?$prefs['wlk_qt_fieldname']:'video',
			'bgcolor'=>(!empty($prefs['wlk_qt_bgcolor']))?$prefs['wlk_qt_bgcolor']:'',
			'mvname'=>(!empty($prefs['wlk_qt_mvname']))?$prefs['wlk_qt_mvname']:'',
			'node'=>(!empty($prefs['wlk_qt_node']))?$prefs['wlk_qt_node']:'',
			'pan'=>(!empty($prefs['wlk_qt_pan']))?$prefs['wlk_qt_pan']:'',
			'tilt'=>(!empty($prefs['wlk_qt_tilt']))?$prefs['wlk_qt_tilt']:'',
			'fov'=>(!empty($prefs['wlk_qt_fov']))?$prefs['wlk_qt_fov']:'',
			'kioskmode'=>(!empty($prefs['wlk_qt_kioskmode']))?'true':'false',
			'loop'=>(!empty($prefs['wlk_qt_loop']))?$prefs['wlk_qt_loop']:'',
			'pmfieldname'=>(!empty($prefs['wlk_qt_pmfieldname']))?$prefs['wlk_qt_pmfieldname']:'postermovie',
            'debug'=> (!empty($prefs['wlk_qt_debug']))?'true':'false'
		),$atts));

		if($mvname!='')
		{
			$rmchrarr = array('&', '*', '^', '%', '!', '@', '#', '$', '{', '}', '[', ']', '<', '>', '\\', '/');
			$mvnamefinal = str_replace($rmchrarr, "", $mvname);
		}


		/* 
		
			Checking to make sure the video is set and works
		
		*/
		if(!empty($video)){
			if(preg_match("/\//", $video)){
					if(file_exists($video)){
						$video = hu.$video;
						$msg[] = 'txp:wlk_qt: video='.$atts['video'].'. File found and used with txp pref path from root '.$prefs["path_from_root"];
					}
					else{
						$video = $video;
						$msg[] = 'txp:wlk_qt Warning: video='.$atts['video'].'. Plugin can\'t verify file existension. Make sure that given file '.$atts['video'].' exists and has vaild path.';
					}
			}
			else{
				if(is_numeric($video)){
					$video = wlk_qt_get_file('id="'.addslashes($video).'"');
					$msg[] =($video == false)?'txp:wlk_qt Error: video='.$atts['video'].'. No file with this id is stored in txp.':'';
				} else {
					$video = wlk_qt_get_file('filename="'.addslashes($video).'"');
					$msg[] =($video == false)?'txp:wlk_qt Error: video='.$atts['video'].'. No file with this name is stored in txp.':'';
				}
			}
		} else {
			if(isset($thisarticle[$fieldname]))
			{
				if(!empty($thisarticle[$fieldname])){
					if(is_numeric($thisarticle[$fieldname])){
							$video = wlk_qt_get_file('id="'.addslashes($thisarticle[$fieldname]).'"');
							$msg[] =($video == false)?'txp:wlk_qt Error: CustomField['.$fieldname.']='.$thisarticle[$fieldname].'. No file with this id is stored in txp.':'';
					} else if(preg_match("/\//", $thisarticle[$fieldname])) {
						if(file_exists($thisarticle[$fieldname])){
							$video = hu.$thisarticle[$fieldname];
							$msg[] = 'txp:wlk_qt: CustomField['.$fieldname.']='.$thisarticle[$fieldname].'. File found and used with txp pref path from root'.$prefs["path_from_root"];
						} else {
							$video = $thisarticle[$fieldname];
							$msg[] = 'txp:wlk_qt Warning: CustomField[video]='.$thisarticle[$fieldname].'. Make sure that given file exists and has vaild path.';
						}
					} else {
						$video = wlk_qt_get_file('filename="'.addslashes($thisarticle[$fieldname]).'"');
						$msg[] =($video == false)?'txp:wlk_qt Error: CustomField['.$fieldname.']='.$thisarticle[$fieldname].'. No file with this name is stored in txp.':'';
					}

				}
				else
					$msg[] = 'txp:wlk_qt Error: No video defined. Use the attribute video or a custom-field named video to define a video file.';
			}
			else
				$msg[] = 'txp:wlk_qt Error: No video defined. Use the attribute video or a custom-field named video to define a video file.';
		}
		//End video check
		
		/*
		
			Checking For PosterMovie being set
		
		*/
		if(!empty($postermovie)){
			if(preg_match("/\//", $postermovie)){
					if(file_exists($postermovie)){
						$postermovie = hu.$postermovie;
						$msg[] = 'txp:wlk_qt: postermovie='.$atts['postermovie'].'. File found and used with txp pref path from root'.$prefs["path_from_root"];
					}
					else{
						$postermovie = $postermovie;
						$msg[] = 'txp:wlk_qt Warning: postermovie='.$atts['postermovie'].'. Plugin can\'t verify file existence. Make sure that given file '.$atts['postermovie'].' exists and has vaild path.';
					}
			}
			else{
				if(is_numeric($postermovie)){
					$postermovie = wlk_qt_get_front('id="'.addslashes($postermovie).'"', $pmtype);
					$msg[] =($postermovie == false)?'txp:wlk_qt Error: video='.$atts['postermovie'].'. No image or file with this id is stored in txp.':'';
				}
				else{
					$postermovie = wlk_qt_get_front('name="'.addslashes($postermovie).'"', $pmtype);
					$msg[] =($postermovie == false)?'txp:wlk_qt Error: video='.$atts['postermovie'].'. No image or file with this name is stored in txp.':'';
				}
			}
		} else {
			if(!empty($thisarticle[$pmfieldname])){
				if(preg_match("/\//", $thisarticle[$pmfieldname])){
					if(file_exists($thisarticle[$pmfieldname])){
						$postermovie = hu.$thisarticle[$pmfieldname];
						$msg[] = 'txp:wlk_qt: CustomField['.$pmfieldname.']='.$thisarticle[$pmfieldname].'. File found and used with txp pref path from root'.$prefs["path_from_root"];
					}
					else{
						$postermovie = $thisarticle[$pmfieldname];
						$msg[] = 'txp:wlk_qt Warning: CustomField['.$pmfieldname.']='.$thisarticle[$pmfieldname].'. Make sure that given file exists and has vaild path.';
					}
				}
				else{
					if(is_numeric($thisarticle[$pmfieldname])){
						$postermovie = wlk_qt_get_front('id="'.addslashes($thisarticle[$pmfieldname]).'"', $pmtype);
						$msg[] =($postermovie == false)?'txp:wlk_qt Error: CustomField['.$pmfieldname.']='.$thisarticle[$pmfieldname].'. No file with this id is stored in txp.':'';
					}
					else{
						$postermovie = wlk_qt_get_front('name="'.addslashes($thisarticle[$pmfieldname]).'"', $pmtype);
						$msg[] =($postermovie == false)?'txp:wlk_qt Error: CustomField['.$pmfieldname.']='.$thisarticle[$pmfieldname].'. No file with this name is stored in txp.':'';
					}

				}

			} else {
				$pmset = false;
			}
		}
		//end postermovie check
		
		if($debug == 'false' && $video != '' && $js != 'true'){
            $out[]='<OBJECT CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab#version=7,0,0,0" WIDTH="'.$width.'" HEIGHT="'.$height.'">';
            $out[]='<PARAM NAME="scale" VALUE="'.$scale.'">';
            
            if($pmset)
            {
            	$out[]='<PARAM NAME="controller" VALUE="false">';
            	$out[]='<PARAM NAME="autoplay" VALUE="True">';
            	$out[]='<PARAM NAME="href" VALUE="'.$video.'">';
            	$out[]='<PARAM NAME="target" VALUE="myself">';
            	$out[]='<PARAM NAME="src" VALUE="'.$postermovie.'">';
            	$out[]='<PARAM NAME="type" VALUE="video/quicktime">';
            } else {
            	$out[]='<PARAM NAME="controller" VALUE="'.$controller.'">';
            	$out[]='<PARAM NAME="autoplay" VALUE="'.$autoplay.'">';
            	$out[]='<PARAM NAME="src" VALUE="'.$video.'">';
            	$out[]='<PARAM NAME="type" VALUE="'.$qttype.'">';
            }
            
            $out[]='<PARAM NAME="target" VALUE="myself">';
            if($bgcolor!='')
            {
            	if(in_array(strtoupper($bgcolor), $colorArr)) {
            		$out[]='<PARAM NAME="bgcolor" VALUE="'.strtolower($bgcolor).'">';
            	} else if(strlen($bgcolor)=='6') {
            		$out[]='<PARAM NAME="bgcolor" VALUE="#'.strtoupper($bgcolor).'">';
            	}
            }
            if(isset($mvnamefinal))
            {
            	$out[]='<PARAM NAME="moviename" VALUE="'.$mvnamefinal.'">';
            }
            if($node!='')
            {
            	if(is_numeric($node))
            	{
            		$out[]='<PARAM NAME="node" VALUE="'.$node.'">';
            	}
            }
            if($pan!='')
            {
            	if(is_numeric($pan))
            	{
            		$out[]='<PARAM NAME="pan" VALUE="'.$pan.'">';
            	}
            }
            if($kioskmode=='true')
            {
            	$out[]='<PARAM NAME="kioskmode" VALUE="true">';
            }
            if($tilt!='')
            {
            	if(is_numeric($tilt))
            	{
            		$out[]='<PARAM NAME="tilt" VALUE="'.$tilt.'">';
            	}
            }
            if($fov!='')
            {
            	if(is_numeric($fov))
            	{
            		$out[]='<PARAM NAME="fov" VALUE="'.$fov.'">';
            	}
            }
            if($loop!='')
            {
				$out[]='<PARAM NAME="loop" VALUE="'.$loop.'">';
            }
            $out[]='<PARAM NAME="pluginspage" VALUE="http://www.apple.com/quicktime/download/index.html">';
            $out[]='<EMBED WIDTH="'.$width.'" HEIGHT="'.$height.'" SCALE="'.$scale.'" TARGET="myself"';
            if($pmset)
            {
            	$out[] =' AUTOPLAY="true" SRC="'.$postermovie.'" HREF="'.$video.'" type="'.$pmtype.'" CONTROLLER="false"';
            	if($pmtype=='video/quicktime')
				{
					$out[] =' LOOP="true"';
				}
            } else {
            	$out[] =' AUTOPLAY="'.$autoplay.'" SRC="'.$video.'" type="'.$qttype.'" CONTROLLER="'.$controller.'"';
            	if($loop!='')
				{
					$out[] =' LOOP="'.$loop.'"';
				}
            }
            if($bgcolor!='')
            {
            	if(in_array(strtoupper($bgcolor), $colorArr)) {
            		$out[]=' BGCOLOR="'.strtolower($bgcolor).'"';
            	} else if(strlen($bgcolor)=='6') {
            		$out[]=' BGCOLOR="#'.strtoupper($bgcolor).'"';
            	}
            }
            if(isset($mvnamefinal))
            {
            	$out[]=' MOVIENAME="'.$mvnamefinal.'"';
            }
            if($node!='')
            {
            	if(is_numeric($node))
            	{
            		$out[]=' NODE="'.$node.'"';
            	}
            }
            if($pan!='')
            {
            	if(is_numeric($pan))
            	{
            		$out[]=' PAN="'.$pan.'"';
            	}
            }
            if($tilt!='')
            {
            	if(is_numeric($tilt))
            	{
            		$out[]=' TILT="'.$tilt.'"';
            	}
            }
            if($fov!='')
            {
            	if(is_numeric($fov))
            	{
            		$out[]=' FOV="'.$fov.'"';
            	}
            }
            if($kioskmode=='true')
            {
            	$out[]=' KIOSKMODE="true"';
            }
            $out[]=' PLUGINSPAGE="http://www.apple.com/quicktime/download/index.html"></EMBED>';
            $out[]='</OBJECT>';
			if($debug)
			{
				echo implode("",$msg);
			}
return implode("",$out);
		} else if($debug == 'false' && $video != '' && $js == 'true'){
			$out = '<div id="wlk_qt_video">';
            $out .= '
            		<script type="text/javascript">
            		';
            $out .= "write_wlkqt('$video', '$width', '$height', '$controller', '$scale', '$qttype', '$autoplay',";
            if($bgcolor!='')
            {
            	if(in_array(strtoupper($bgcolor), $colorArr)) {
            		$out .=" '".strtolower($bgcolor)."',";
            	} else if(strlen($bgcolor)=='6') {
            		$out .=" '#".strtoupper($bgcolor)."',";
            	} else { $out .= ' null,'; }
            } else { $out .= ' null,'; }
            if(isset($mvnamefinal))
            {
            	$out .= " '".$mvnamefinal."'";
            } else { $out .= ' null,'; }
            if($node!='')
            {
            	if(is_numeric($node))
            	{
            		$out .=" '".$node."',";
            	} else { $out .= ' null,'; }
            } else { $out .= ' null,'; }
            if($pan!='')
            {
            	if(is_numeric($pan))
            	{
            		$out .=" '".$pan."',";
            	} else { $out .= ' null,'; }
            } else { $out .= ' null,'; }
            if($tilt!='')
            {
            	if(is_numeric($tilt))
            	{
            		$out .=" '".$tilt."',";
            	} else { $out .= ' null,'; }
            } else { $out .= ' null,'; }
            if($fov!='')
            {
            	if(is_numeric($fov))
            	{
            		$out .=" '".$fov."',";
            	} else { $out .= ' null,'; }
            } else { $out .= ' null,'; }
            if($kioskmode=='true')
            {
            	$out .=" 'true',";
           	} else { $out .= ' null,'; }
            if($pmset)
            {
            	if($pmtype=='video/quicktime')
            	{
            		$out .=" 'true',";
            	} else {
            		$out .=" 'false',";
            	}
            } else {
				if($loop!='')
				{
					$out .=" '".$loop."',";
				} else { $out .= ' null,'; }
			}
            if($pmtype!='')
            {
				$out .=" '".$pmtype."',";
            } else { $out .= ' null,'; }
            if($postermovie!='')
            {
				$out .=" '".$postermovie."'";
            } else { $out .= ' null'; }

            $out .= ");";
            $out .= '
            		</script></div>
            		';
            return $out;
		}
	}

	function wlk_scriptlink($atts) {
		$jsembed = '<script type="text/javascript" src="'.$atts['path'].'"></script>';
		return $jsembed;
	}

//-----------------------------------
//				Get File
//------------------------------------

    function wlk_qt_get_file($where)
    {
		global $permlink_mode;
		$thisfile = fileDownloadFetchInfo($where);
		if(!empty($thisfile['filename']))
        {
			//$player = ($permlink_mode == 'messy') ?
				//hu.'index.php?s=file_download&amp;id='.$thisfile['id']:
				//hu.gTxt('file_download').'/'.$thisfile['id'];
$player = hu.'files/'.$thisfile['filename'];
		}
		else
        {
			$player = false;
		}
		return $player;
	}

    function wlk_qt_get_front($where, $thetype)
    {
		global $permlink_mode, $txpcfg;
		if($thetype=='video/quicktime') {
			$thisfile = fileDownloadFetchInfo($where);
			$player = hu.'files/';
		} else if($thetype=='image/quicktime') {
			$query = safe_query('SELECT id, ext FROM '.$txpcfg['table_prefix'].'txp_image WHERE '.$where.' LIMIT 1');
			$imgrow = mysql_fetch_row($query);
			$thisfile = array('filename'=>$imgrow[0].$imgrow[1]);
			$player = hu.'images/';
		}
		if(!empty($thisfile['filename']))
        {
			$player .= $thisfile['filename'];
		} else {
			$player = false;
		}
		return $player;
	}


	
# --- END PLUGIN CODE ---

?>