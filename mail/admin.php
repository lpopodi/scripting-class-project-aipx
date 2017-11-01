<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "lpopodi@decibel-media.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "1f0280" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onClick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'AE21' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1EQxlCGVqRxVgDRBoYHR2mIouJTBFpYG0ICEUWC2gVAZPI7otaOjVs1cqspcjuA6trRbUjNBQoNgVVDKwuAFOM0QFdTDSUNTQgNGAQhB8VIRb3AQA48Muv5twd5QAAAABJRU5ErkJggg==',
			'F5F1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDA1qRxQIaRBpYGximYhELRRMLAYrB9IKdFBo1denS0FVLkd0X0MDQ6IpQh0dMBIsYaysrhhgjyN7QgEEQflSEWNwHABfTzQDpgN2BAAAAAElFTkSuQmCC',
			'3973' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDA0IdkMQCprC2MjQEOgQgq2wVaXRoCGgQQRabAhQDiyLctzJq6dKspauWZiG7bwpjoMMUhgZU8xgaHQIYUM1rZWl0dEAVA7mFtYERxS1gNzcwoLh5oMKPihCL+wCTFc0Lu5t3TAAAAABJRU5ErkJggg==',
			'3CD3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7RAMYQ1lDGUIdkMQCprA2ujY6OgQgq2wVaXBtCGgQQRabItLAChQLQHLfyqhpq5auilqahew+VHVw81jRzcNiBza3YHPzQIUfFSEW9wEAu73OPCVvIZIAAAAASUVORK5CYII=',
			'88A2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYQximMEx1QBITmcLayhDKEBCAJBbQKtLo6OjoIIKmjrUhoEEEyX1Lo1aGLV0VBYQI90HVNTqgmecaGtDKgC7WEDCFAdOOAHQ3szYEhoYMgvCjIsTiPgCQO82AkgILiQAAAABJRU5ErkJggg==',
			'332C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7RANYQxhCGaYGIIkFTBFpZXR0CBBBVtnK0OjaEOjAgiw2BSgKFEN238qoVWGrVmZmobgPpK6V0YEBzTyHKVjEAhhR7AC7xYEBxS0gN7OGBqC4eaDCj4oQi/sA36XKR40ZxSUAAAAASUVORK5CYII=',
			'B961' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGVqRxQKmsLYyOjpMRRFrFWl0bXAIRVUHEoPrBTspNGrp0tSpq5Yiuy9gCmOgq6MDqh2tDEC9AWhiLJhiELegiEHdHBowCMKPihCL+wA+NM3l5gjO/wAAAABJRU5ErkJggg==',
			'C567' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WENEQxlCGUNDkMREWkUaGB0dGkSQxAIaRRpYG9DEGkRCWME0wn1Rq6YuXTp11cosJPcB5RtdHR1aGVD0AsUaAqYwoNoBEgtgQHELayujo6MDqpsZQ4BuRhEbqPCjIsTiPgCcMsxb8pMtAAAAAABJRU5ErkJggg==',
			'CF72' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WENEQ11DA6Y6IImJtIoAyYCAACSxgEaQWKCDCLJYA5DX6NAgguS+qFVTw1YtBdEI94HVTQGpRNMbwNDKgGYHowNQJZpbWEEqUdwMEmMMDRkE4UdFiMV9AHdezOPtjzPPAAAAAElFTkSuQmCC',
			'B2B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDGaY6IIkFTGFtZW10CAhAFmsVaXRtCHQQQVHH0OiKUAd2UmjUqqVLQ1dNzUJyH1DdFEzzGAJY0c1rZXTAEJvC2oCuNzRANNQVzc0DFX5UhFjcBwAkps6CsKAwfgAAAABJRU5ErkJggg==',
			'0106' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMEx1QBJjDWAMYAhlCAhAEhOZAhR1dHQQQBILaGUIYG0IdEB2X9RSEIpMzUJyH1QdinkwvSIodjCA7RBBcQsDhlsYHVhD0d08UOFHRYjFfQDMMMi8cIeQHQAAAABJRU5ErkJggg==',
			'52AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkMYQximMLQiiwU0sLYyhDJMdUARE2l0dHQICEASCwxgaHRtCHQQQXJf2LRVS5euisyahuy+VoYprAh1MLEA1tDA0BBkO1oZHdDViQB1oouxBoiGuqKbN0DhR0WIxX0AeC3MSNaAG74AAAAASUVORK5CYII=',
			'0588' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1EQxlCGaY6IImxBog0MDo6BAQgiYlMEWlgbQh0EEESC2gVCUFSB3ZS1NKpS1eFrpqaheS+gFaGRkc080BirmjmAe3AEGMNYG1FdwujA2MIupsHKvyoCLG4DwB4YMuxc9HnvgAAAABJRU5ErkJggg==',
			'4B92' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpI37poiGMIQyTHVAFgsRaWV0dAgIQBJjDBFpdG0IdBBBEmOdItLK2hDQIILkvmnTpoatzIxaFYXkvgCgOoaQgEZkO0JDRYD8gFZUt4g0OgJVo4mB3YLpZsbQkMEQftSDWNwHAG4+zHbtfuoTAAAAAElFTkSuQmCC',
			'C5D7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WENEQ1lDGUNDkMREWkUaWBsdGkSQxAIagWINAahiDSIhILEAJPdFrZq6dOmqqJVZSO4Dyje6NgS0MqDoBYtNYUC1AyQWwIDiFtZW1kZHB1Q3M4YA3YwiNlDhR0WIxX0A6cnNYSo0DWMAAAAASUVORK5CYII=',
			'E1EE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAT0lEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHUMDkMQCGhgDWBsYHRhQxFixiDEgi4GdFBq1Kmpp6MrQLCT3oamjWCw0hDUU3c0DFX5UhFjcBwAs3cgsfrvcvAAAAABJRU5ErkJggg==',
			'CCAF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WEMYQxmmMIaGIImJtLI2OoQyOiCrC2gUaXB0dEQVaxBpYG0IhImBnRS1atqqpasiQ7OQ3IemDiEWGohhhyuaOpBb0MVAbkY3b6DCj4oQi/sALAvLeASU3UwAAAAASUVORK5CYII=',
			'259B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WANEQxlCGUMdkMREpog0MDo6OgQgiQW0ijSwNgQ6iCDrbhUJAYkFILtv2tSlKzMjQ7OQ3RfA0OgQEohiHqMDUAzNPNYGkUZHNDGgra3obgkNZQxBd/NAhR8VIRb3AQBz7srWdSyaUwAAAABJRU5ErkJggg==',
			'847F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WAMYWllDA0NDkMREpjBMZWgIdEBWF9DKEIouJjKF0ZWh0REmBnbS0qilS1ctXRmaheQ+kSkirQxTGNHMEw11CEAXY2hldGBEswPovgZUMbCb0cQGKvyoCLG4DwA/pMmHtgS7tgAAAABJRU5ErkJggg==',
			'759A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGVpRRFtFGhgdHaY6oImxNgQEBCCLTREJYW0IdBBBdl/U1KUrMyOzpiG5j9GBodEhBK4ODFkbgGINgaEhSGIiDSKNjg2o6gIaWFsZHR3RxBhDGEIZUcQGKvyoCLG4DwAgXctQpVFjIAAAAABJRU5ErkJggg==',
			'4B8F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpI37poiGMIQyhoYgi4WItDI6Ojogq2MMEWl0bQhEEWOdgqIO7KRp06aGrQpdGZqF5L6AKZjmhYZimscwBasYhl6om1HFBir8qAexuA8AymLJgXP2XzwAAAAASUVORK5CYII=',
			'A47F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YWllDA0NDkMRYAximMjQEOiCrE5nCEIouFtDK6MrQ6AgTAzspaunSpauWrgzNQnJfQKtIK8MURhS9oaGioQ4BjGjmMbQyOmCKsTYQFhuo8KMixOI+AEOiyc8xAnvUAAAAAElFTkSuQmCC',
			'82B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGaY6IImJTGFtZW10CAhAEgtoFWl0bQh0EEFRx9Do2ugIEwM7aWnUqqVLQ1dFhSG5D6huCtC8qSIo5jEEsDYENKCKMToAxdDsYG1AdwtrgGioK5qbByr8qAixuA8AFFLM78tyYzUAAAAASUVORK5CYII=',
			'3531' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7RANEQxlDGVqRxQKmiDSwNjpMRVHZKgKSCUURmyISwtDoANMLdtLKqKlLV01dtRTFfVOAqhDqoOYBxRoC0MREMMQCprC2sqLpFQ1gDAG6OTRgEIQfFSEW9wEAKHDNEGOyJUcAAAAASUVORK5CYII=',
			'42E3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpI37pjCGsIY6hDogi4WwtrI2MDoEIIkxhog0ugJpESQx1ikMYLEAJPdNm7Zq6dLQVUuzkNwXMIVhCitCHRiGhjIEsKKZB3SLA6YYawO6WximiIa6ort5oMKPehCL+wC53MvhQ7J9NwAAAABJRU5ErkJggg==',
			'26D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGVqRxUSmsLayNjpMdUASC2gVaWRtCAgIQNbdKtLA2hDoIILsvmnTwpauisyahuy+ANFWJHVgyOgg0uiKJsbaABJDtQNoA4ZbQkMx3TxQ4UdFiMV9AD9EzFGUjM+ZAAAAAElFTkSuQmCC',
			'19B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGaY6IImxOrC2sjY6BAQgiYk6iDS6NgQ6CKDoBYo1Ojogu29l1tKlqaErU7OQ3Ae0IxCoDsU8RgcGsHkiKGIsWMSwuCUE080DFX5UhFjcBwBNhMn1w84zZwAAAABJRU5ErkJggg==',
			'A501' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMLQii7EGiDQwhDJMRRYTmSLSwOjoEIosFtAqEsIKJJHdF7V06tKlQBLZfUAVja4IdWAYGoopBjSv0dHRAU2MtRXoFjQxxhCgm0MDBkH4URFicR8AZ3bMvyCvZb0AAAAASUVORK5CYII=',
			'B0ED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QgMYAlhDHUMdkMQCpjCGsDYwOgQgi7WytoLERFDUiTS6IsTATgqNmrYyNXRl1jQk96Gpg5qHTQybHZhuwebmgQo/KkIs7gMAe9zLoN19aMEAAAAASUVORK5CYII=',
			'0A24' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGRoCkMRYAxhDGB0dGpHFRKawtrI2BLQiiwW0ijQ6NARMCUByX9TSaSuzVmZFRSG5D6yuldEBVa9oqMMUxtAQFDuA6gLQ3SLS6OiAKsboINLoGhqAIjZQ4UdFiMV9AJD2zYxT1n0AAAAAAElFTkSuQmCC',
			'6209' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM3QQQqAIBCF4efCG9h9xkX7CTTI00yQN7AjtPGURRBotSxqZveD+DHIlxH8aV/xaVYOCTMVzSQd4cFcNJ7MaK0lUzbB2Ep3tJ00hLwsOYS+8LmEpIXn6m0Eb03qpkhZqv7YLHK2aG48ncxf3e/BvfGtC03MGQnWb0sAAAAASUVORK5CYII=',
			'A12F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGUNDkMRYAxgDGB0dHZDViUxhDWBtCEQRC2gF6kWIgZ0UtXRV1KqVmaFZSO4Dq2tlRNEbGgoUm8KIaV4AphijA7oYayhrKKpbBir8qAixuA8A4cfHPFU/YscAAAAASUVORK5CYII=',
			'DA4E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYAhgaHUMDkMQCpjCGMLQ6OiCrC2hlbWWYii4m0ugQCBcDOylq6bSVmZmZoVlI7gOpc21E1ysa6hoaiGkeuropmGKhAWAxFDcPVPhREWJxHwBnrc1yYPpViwAAAABJRU5ErkJggg==',
			'75E8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDHaY6IIu2ijSwNjAEBGCIMTqIIItNEQlBUgdxU9TUpUtDV03NQnIfowNDoyuaeUB9QDFU80QaRDDEAhpYW9HdEtDAGILh5gEKPypCLO4DAE/9y55VBb+7AAAAAElFTkSuQmCC',
			'8136' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAhhDGaY6IImJTGEMYG10CAhAEgtoBapsCHQQQFHHEMDQ6OiA7L6lUauiVk1dmZqF5D6oOjTzGMDmiRAQA+lFdwtrAGsoupsHKvyoCLG4DwAnFcqfznP9hQAAAABJRU5ErkJggg==',
			'A5D2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nM3QvRGAIAyG4VBkg7gPjX28I43ThCIbwAg0TOlPYzgt9U6+7ingPaDfjsKf9klfiJOgQI3OkEkxR2ZnVHbTJZIzNkqorOT61lZb6+u+q48N8qyc/RsipxmM9x1WRkM7WkYLCSVI+sH/vbiHvg0rkM4NcfS1nwAAAABJRU5ErkJggg==',
			'AE3F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB1EQxmBMARJjDVApIG10dEBWZ3IFBEgGYgiFtAKFEOoAzspaunUsFVTV4ZmIbkPTR0YhobiMA+LGLpbAlrBbkYRG6jwoyLE4j4AEaPKqcU4GC4AAAAASUVORK5CYII=',
			'28BF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUNDkMREprC2sjY6OiCrC2gVaXRtCEQRY2hFUQdx07SVYUtDV4ZmIbsvANM8RgdM81gbMMVEGjD1hoaC3YzqlgEKPypCLO4DAG8zyeVUuvomAAAAAElFTkSuQmCC',
			'0CC1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB0YQxlCHVqRxVgDWBsdHQKmIouJTBFpcG0QCEUWC2gVaWBtYIDpBTspaum0VUtXgRDCfWjqcIpB7cDmFhQxqJtDAwZB+FERYnEfAAaDzBuAvC6BAAAAAElFTkSuQmCC',
			'B16C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGaYGIIkFTGEMYHR0CBBBFmtlDWBtcHRgQVHHABRjdEB2X2jUqqilU1dmIbsPrM7R0YEBxTyQ3kCsYuh2oLslNIA1FN3NAxV+VIRY3AcArejKNYx2rxUAAAAASUVORK5CYII=',
			'A70E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMIYGIImxBjA0OoQyOiCrE5nC0Ojo6IgiFtDK0MraEAgTAzspaumqaUtXRYZmIbkPqC4ASR0YhgLNRxcLAJrGiGGHSAMDmlvAYmhuHqjwoyLE4j4AtJTKWUStwvcAAAAASUVORK5CYII=',
			'2229' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGaY6IImJTGFtZXR0CAhAEgtoFWl0bQh0EEHW3crQ6IAQg7hp2qqlq1ZmRYUhuy+AYQpQ7VRkvYwOYNEGZDFWiCiKHSJQUWS3hIaKhrqGBqC4eaDCj4oQi/sAaUHKsIeYbxkAAAAASUVORK5CYII=',
			'35BA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7RANEQ1lDGVqRxQKmiDSwNjpMdUBW2QoUawgICEAWmyISwtro6CCC5L6VUVOXLg1dmTUN2X1TGBpdEeqg5gHFGgJDQ1DtAImhqAuYwtrKiqZXNIAxhDWUEdW8AQo/KkIs7gMAZX7MPADMKCQAAAAASUVORK5CYII=',
			'A3F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDA6Y6IImxBoi0sjYwBAQgiYlMYWh0BaoWQRILaGUAqoOLgZ0UtXRV2NLQVVFhSO6DqGOYiqw3NBRkHtBcVPNAYmh2YLoloBXoZqB5yG4eqPCjIsTiPgBdhcvXIgvN1AAAAABJRU5ErkJggg==',
			'2E8B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WANEQxlCGUMdkMREpog0MDo6OgQgiQW0ijSwNgQ6iCDrbkVRB3HTtKlhq0JXhmYhuy8A0zxGB0zzWBswxUQaMPWGhmK6eaDCj4oQi/sAdx3KCIG0GuAAAAAASUVORK5CYII=',
			'5637' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYQxhDGUNDkMQCGlhbWRsdGkRQxEQawSSSWGAAkAdUF4DkvrBp08JWTV21MgvZfa2irUB1rSg2t4qAdE5BFguAiAUgi4lMAbnF0QFZjDUA7GYUsYEKPypCLO4DAE2pzMemi0jQAAAAAElFTkSuQmCC',
			'6DB7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGUNDkMREpoi0sjY6NIggiQW0iDS6NgSgijUAxYDqApDcFxk1bWVq6KqVWUjuC5kCVteKbG9AK9i8KVjEAhgw3OLogMXNKGIDFX5UhFjcBwCYA83pJYfvwwAAAABJRU5ErkJggg==',
			'BA1B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYAhimMIY6IIkFTGEMYQhhdAhAFmtlbWUEiomgqBNpdJgCVwd2UmjUtJVZ01aGZiG5D00d1DzRUJAYinmtEHV47IC6WaTRMdQRxc0DFX5UhFjcBwDods0iyhQ48QAAAABJRU5ErkJggg==',
			'6C89' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQxlCGaY6IImJTGFtdHR0CAhAEgtoEWlwbQh0EEEWaxBpYAQqFEFyX2TUtFWrQldFhSG5L2QKSJ3DVBS9rSINrGATUMVcGwJQ7MDmFmxuHqjwoyLE4j4Avp/MuWZ/yQgAAAAASUVORK5CYII=',
			'87B9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANEQ11DGaY6IImJTGFodG10CAhAEgtoBYo1BDqIoKprZW10hImBnbQ0atW0paGrosKQ3AdUF8Da6DBVBMU8RgfWhoAGVDHWBqAYmh0iDaxobmENAIqhuXmgwo+KEIv7AE1hzQZXZjZKAAAAAElFTkSuQmCC',
			'58E0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDHVqRxQIaWFtZGximOqCIiTS6NjAEBCCJBQaA1DE6iCC5L2zayrCloSuzpiG7rxVFHVQMZB6qWEArph0iUzDdwhqA6eaBCj8qQizuAwC978u6FJvKtAAAAABJRU5ErkJggg==',
			'84E4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYWllDHRoCkMREpjBMZW1gaEQWC2hlCAWKtaKqY3QFik0JQHLf0qilS5eGroqKQnKfyBSRVtYGRgdU80RDXRsYQ0NQ7QCqY0B3C4YYNjcPVPhREWJxHwA5gc0YbWwe0AAAAABJRU5ErkJggg==',
			'BCF9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QgMYQ1lDA6Y6IIkFTGFtdG1gCAhAFmsVaXBtYHQQQVEn0sCKEAM7KTRq2qqloauiwpDcB1HHMFUEzTygWAO6GNBeNDsw3QJ2M9A8ZDcPVPhREWJxHwDPfM16FkrozAAAAABJRU5ErkJggg==',
			'2370' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANYQ1hDA1qRxUSmiAD5AVMdkMSAKhodGgICApB1twJho6ODCLL7pq0KW7V0ZdY0ZPcFANVNYYSpA0NGB6B5AahirA0g0xhQ7BBpEGkFiqO4JTQU6GaQ7YMg/KgIsbgPAKkFy4yG8tUQAAAAAElFTkSuQmCC',
			'C8F4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDAxoCkMREWllbWRsYGpHFAhpFGl0bGFpRxBrA6qYEILkvatXKsKWhq6KikNwHUcfogKoXZB5jaAimHdjcgiIGdjOa2ECFHxUhFvcBACgzzcQ5EC70AAAAAElFTkSuQmCC',
			'600C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WAMYAhimMEwNQBITmcIYwhDKECCCJBbQwtrK6OjowIIs1iDS6NoQ6IDsvsioaStTV0VmIbsvZAqKOojeVmximHZgcws2Nw9U+FERYnEfAKNPyvp7rZQRAAAAAElFTkSuQmCC',
			'D70C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgNEQx2mMEwNQBILmMLQ6BDKECCCLNbK0Ojo6OjAgirWytoQ6IDsvqilq6YtXRWZhew+oLoAJHVQMUYHTDHWBkZ0O6YAXYHmllAQD83NAxV+VIRY3AcA8R3MpDTOHOoAAAAASUVORK5CYII=',
			'5DF9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDA6Y6IIkFNIi0sjYwBASgijW6NjA6iCCJBQagiIGdFDZt2srU0FVRYcjuawWpY5iKrBcq1oAsFgARQ7FDZAqmW1gDgG4Gmofs5oEKPypCLO4DAK1fzHQ6fhBFAAAAAElFTkSuQmCC',
			'D4D2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QgMYWllDGaY6IIkFTGGYytroEBCALNbKEMraEOgggiLG6MraENAgguS+qKVAsCoKCBHuC2gVaQWqa0Sxo1U01BVkKqodIHVTGFDd0gpyC6abGUNDBkH4URFicR8APjDOnqylRi8AAAAASUVORK5CYII=',
			'B130' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhDGVqRxQKmMAawNjpMdUAWa2UNAJIBASjqGAIYGh0dRJDcFxq1KmrV1JVZ05Dch6YOah5QrCEQiximHehuCQ1gDUV380CFHxUhFvcBADkMzDzOFdFGAAAAAElFTkSuQmCC',
			'EF0C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkNEQx2mMEwNQBILaBBpYAhlCBBBE2N0dHRgQRNjbQh0QHZfaNTUsKWrIrOQ3YemDq8YNjvQ3RIaAuShuXmgwo+KEIv7ACO6zCGZ64EaAAAAAElFTkSuQmCC',
			'130D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB1YQximMIY6IImxOoi0MoQyOgQgiYk6MDQ6Ojo6iKDoZWhlbQiEiYGdtDJrVdjSVZFZ05Dch6YOJtboikUM0w4sbgnBdPNAhR8VIRb3AQDqeMgM7CziOAAAAABJRU5ErkJggg==',
			'358C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RANEQxlCGaYGIIkFTBFpYHR0CBBBVtkq0sDaEOjAgiw2RSSE0dHRAdl9K6OmLl0VujILxX1TGBodEeqg5jE0ugLNQxUTAYsh2xEwhbUV3S2iAYwh6G4eqPCjIsTiPgBg88rNPIt15AAAAABJRU5ErkJggg==',
			'8B61' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WANEQxhCGVqRxUSmiLQyOjpMRRYLaBVpdG1wCEVXx9oA1wt20tKoqWFLp65aiuw+sDpHh1ZM8wIIikHdgiIGdXNowCAIPypCLO4DALXNzLsxogWxAAAAAElFTkSuQmCC',
			'E256' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDHaY6IIkFNLC2sjYwBASgiIk0ujYwOgigiDE0uk5ldEB2X2jUqqVLMzNTs5DcB1Q3haEhEM08hgCgmIMIihijAyuGGGsDo6MDit7QENFQh1AGFDcPVPhREWJxHwDOSsyrdDAzQwAAAABJRU5ErkJggg==',
			'D01A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QgMYAhimMLQiiwVMYQxhCGGY6oAs1sraChQNCEARE2l0mMLoIILkvqil01ZmgRCS+9DUIYuFhqDZwYCuDuQWNDGQmxlDHVHEBir8qAixuA8Ac1nMTi4IlZ8AAAAASUVORK5CYII=',
			'39EB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDHUMdkMQCprC2sjYwOgQgq2wVaXQFiokgi02BiAUguW9l1NKlqaErQ7OQ3TeFMdAVwzwGTPNaWTDEsLkFm5sHKvyoCLG4DwB4bMrJ9mDtWgAAAABJRU5ErkJggg==',
			'59E6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDHaY6IIkFNLC2sjYwBASgiIk0ujYwOgggiQUGQMSQ3Rc2benS1NCVqVnI7mtlDASqQzGPoZUBrFcE2Y5WFgwxkSmYbmENwHTzQIUfFSEW9wEAmWPLl+1mnDsAAAAASUVORK5CYII=',
			'4D2F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpI37poiGMIQyhoYgi4WItDI6Ojogq2MMEWl0bQhEEWOdItLogBADO2natGkrs1ZmhmYhuS8ApK6VEUVvaChQbAqqGANIXQCGGFAnuphoCGsoqlsGLPyoB7G4DwA/hsnAP+wYsgAAAABJRU5ErkJggg==',
			'DD31' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7QgNEQxhDGVqRxQKmiLSyNjpMRRFrFWl0aAgIxRBrdIDpBTspaum0lVlTVy1Fdh+aOmTzCItB3IIiBnVzaMAgCD8qQizuAwCSbs/Az5DJAwAAAABJRU5ErkJggg==',
			'DB30' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QgNEQxhDGVqRxQKmiLSyNjpMdUAWaxVpdGgICAhAFWtlaHR0EEFyX9TSqWGrpq7MmobkPjR1SOYFYhFDswOLW7C5eaDCj4oQi/sA30LPKuTiLu0AAAAASUVORK5CYII=',
			'81C2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCHaY6IImJTGEMYHQICAhAEgtoZQ1gbRB0EEFRxwAUA9JI7lsatSpqKZCOQnIfVF2jA4p5YLFWBgwxgSkMaHaA3ILqZtZQhlDH0JBBEH5UhFjcBwC5hMor58R9awAAAABJRU5ErkJggg==',
			'0A29' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGaY6IImxBjCGMDo6BAQgiYlMYW1lbQh0EEESC2gVaXRAiIGdFLV02sqslVlRYUjuA6trZZiKqlc01GEK0FwUO4DqAhhQ7GANEGl0BLoR2S2MDiKNrqEBKG4eqPCjIsTiPgAqhMuowH6P6wAAAABJRU5ErkJggg==',
			'F839' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkMZQxhDGaY6IIkFNLC2sjY6BASgiIk0OjQEOoigqWNodISJgZ0UGrUybNXUVVFhSO6DqHOYKoJhHpDEFMOwA9MtmG4eqPCjIsTiPgC+Gc5c/rmr+wAAAABJRU5ErkJggg==',
			'5615' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nM2QMQ6AIAxF24Eb1Pt0ca8JMHAaHLgBcgc5pehU0VET+reX/PyXQn1chJHyi5+zaCGjE8UkmgQWGW6MVuzYIhRbd2bl50vxtewhaL80JcgQSS8nWrljcjFkzSibsyvaz0gzcbzxAP/7MC9+B4L6ywhoBLPSAAAAAElFTkSuQmCC',
			'5365' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QkNYQxhCGUMDkMQCGkRaGR0dHRhQxBgaXRtQxQIDGFpZGxhdHZDcFzZtVdjSqSujopDd1wpU5+jQIIJscyvIvAAUsQCwWKADspjIFJBbHAKQ3ccaAHIzw1SHQRB+VIRY3AcASEPLjREjttYAAAAASUVORK5CYII=',
			'8F45' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANEQx0aHUMDkMREpog0MLQ6OiCrC2gFik1FFQOrC3R0dUBy39KoqWErMzOjopDcB1LH2ujQIIJmHivQVnQxhkZHBxF0OxodApDdxxoAFpvqMAjCj4oQi/sAn9bMtj8m+6IAAAAASUVORK5CYII=',
			'F5FD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDA0MdkMQCGkQaWBsYHQKwiImgioUgiYGdFBo1denS0JVZ05DcBzSn0RVDLzYxESxirK2YbmEE2Yvi5oEKPypCLO4DAOEvy/o6x55ZAAAAAElFTkSuQmCC',
			'35E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7RANEQ1lDHUMDkMQCpog0sDYwOqCobMUiNkUkBCjm6oDkvpVRU5cuDV0ZFYXsvikMja5AWgTFPGxiIkAxRgdksYAprK2sDQwByO4TDWAMYQ11mOowCMKPihCL+wA2WMrPpunUjgAAAABJRU5ErkJggg==',
			'E3B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7QkNYQ1hDGaY6IIkFNIi0sjY6BASgiDE0ujYEOgigigHVOToguy80alXY0tCVqVlI7oOqw2qeCEExTLdgc/NAhR8VIRb3AQD6Zc2xsqrYLwAAAABJRU5ErkJggg==',
			'39AC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7RAMYQximMEwNQBILmMLayhDKECCCrLJVpNHR0dGBBVlsikija0OgA7L7VkYtXZq6KjILxX1TGAOR1EHNY2h0DUUXYwGbh2wHyC2sDQEobgG5GSiG4uaBCj8qQizuAwCsAMv64wA99AAAAABJRU5ErkJggg==',
			'3E40' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7RANEQxkaHVqRxQKmiDQwtDpMdUBW2QoUm+oQEIAsBlIX6OggguS+lVFTw1ZmZmZNQ3YfUB1rI1wd3DzW0EAMMaBbUOwAu6UR1S3Y3DxQ4UdFiMV9AHiYzG/V7xMMAAAAAElFTkSuQmCC',
			'7769' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGaY6IIu2MjQ6OjoEBKCJuTY4Ooggi01haGVtYISJQdwUtWra0qmrosKQ3MfowBDA6ugwFVkvSB9rQ0ADspgIUBQohmIHSAUjmlvAutDdPEDhR0WIxX0Abx7LofJgJnIAAAAASUVORK5CYII=',
			'3FE8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7RANEQ11DHaY6IIkFTBFpYG1gCAhAVtkKEmN0EEEWQ1UHdtLKqKlhS0NXTc1Cdh+x5mERw+YW0QCgGJqbByr8qAixuA8ASozLbw8w4/8AAAAASUVORK5CYII=',
			'00C9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCHaY6IImxBjCGMDoEBAQgiYlMYW1lbRB0EEESC2gVaXQFmiCC5L6opdNWpgKpMCT3QdQxTMXUCzQXww4BFDuwuQWbmwcq/KgIsbgPAJDyyvT3smtmAAAAAElFTkSuQmCC',
			'38D2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7RAMYQ1hDGaY6IIkFTGFtZW10CAhAVtkq0ujaEOgggiwGUtcQ0CCC5L6VUSvDlq6KAkIk90HUNTpgmBfQyoApNoUBi1sw3cwYGjIIwo+KEIv7AHX8zS+i2jwvAAAAAElFTkSuQmCC',
			'40FC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpI37pjAEsIYGTA1AFgthDGFtYAgQQRIDirSyNjA6sCCJsU4RaXQFiiG7b9q0aStTQ1dmIbsvAFUdGIaGYooxTMG0g2EKplvAbm5gQHXzQIUf9SAW9wEAMR/J27paopwAAAAASUVORK5CYII=',
			'2C7F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDA0NDkMREprA2OjQEOiCrC2gVaUAXYwCKMTQ6wsQgbpo2bdWqpStDs5DdFwBUN4URRS+jA1AsAFWMtUGkwdEBVUykgbXRtQFVLDQU6GY0sYEKPypCLO4DAJGWybUuNJ0LAAAAAElFTkSuQmCC',
			'F93E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkMZQxhDGUMDkMQCGlhbWRsdHRhQxEQaHRoCMcUQ6sBOCo1aujRr6srQLCT3BTQwBjpgmMeAxTwWLGLY3ILp5oEKPypCLO4DAKJmzJLbVITUAAAAAElFTkSuQmCC',
			'7C4F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMZQxkaHUNDkEVbWRsdWh0dUFS2ijQ4TEUTmyLSwBAIF4O4KWraqpWZmaFZSO5jdBBpYG1E1cvaABQLDUQREwFCBzR1AUCdmGJgN6O6ZYDCj4oQi/sAsXDK+AAbKp4AAAAASUVORK5CYII=',
			'2C59' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDHaY6IImJTGFtdG1gCAhAEgtoFWlwbWB0EEHWDRRjnQoXg7hp2rRVSzOzosKQ3RcAUhEwFVkvRFdAA7IYawPIjgAUO4A2NDo6OqC4JTSUMZQhlAHFzQMVflSEWNwHAMrxy+lBzEtOAAAAAElFTkSuQmCC',
			'1E8C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB1EQxlCGaYGIImxOog0MDo6BIggiYkCxVgbAh1YUPSC1Dk6ILtvZdbUsFWhK7OQ3YemDi4GMg+bGKYdaG4JwXTzQIUfFSEW9wEAOnjHfn0uUBMAAAAASUVORK5CYII=',
			'98A6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQximMEx1QBITmcLayhDKEBCAJBbQKtLo6OjoIIAixtrK2hDogOy+aVNXhi1dFZmaheQ+VlewOhTzGIDmuYYGOoggiQmAxBpQxUBuYW0IQNELcjNQDMXNAxV+VIRY3AcADhDMSn4crVkAAAAASUVORK5CYII=',
			'E215' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkMYQximMIYGIIkFNLC2MoQwOjCgiIk0OmKIMTQ6TGF0dUByX2jUqqWrpq2MikJyH1DdFCBsEEHVG4ApBjR/CqMDqhgrSHcAsvtCQ0RDHUMdpjoMgvCjIsTiPgC8jcwI1VHbCAAAAABJRU5ErkJggg==',
			'2FF7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WANEQ11DA0NDkMREpog0sIJoJLGAVkwxBqhYALL7pk0NWxq6amUWsvsCwOpake1ldACLTUFxSwNYLABZTAQsxuiALBYaiik2UOFHRYjFfQB7VsqBW95gCQAAAABJRU5ErkJggg==',
			'A4D8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YWllDGaY6IImxBjBMZW10CAhAEhOZwhDK2hDoIIIkFtDK6MraEABTB3ZS1FIgWBU1NQvJfQGtIq1I6sAwNFQ01BXDPKBbsImhuQUshubmgQo/KkIs7gMAR8zNZtPaWmQAAAAASUVORK5CYII=',
			'73C2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkNZQxhCHaY6IIu2irQyOgQEBKCIMTS6Ngg6iCCLTWFoZQXSIsjui1oVthRMIdzH6ABW14hsB4jvCjIVSUwELCYwBVksoAHiFlQxkJsdQ0MGQfhREWJxHwDW0Mv2M5Dt6QAAAABJRU5ErkJggg==',
			'AECC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB1EQxlCHaYGIImxBogAxQOAJEJMZIpIA2uDoAMLklhAK0iM0QHZfVFLp4YtXbUyC9l9aOrAMDQUUwyiDtMOdLcEtGK6eaDCj4oQi/sAeY7LFuVnSY8AAAAASUVORK5CYII=',
			'C635' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WEMYQxhDGUMDkMREWllbWRsdHZDVBTSKNDI0BKKKNYg0MDQ6ujoguS9q1bSwVVNXRkUhuS+gQbSVodEBpBpZL1AkAFWsESQW6CCC4RaHAGT3QdzMMNVhEIQfFSEW9wEAl5/MwuvQgHAAAAAASUVORK5CYII=',
			'BB80' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7QgNEQxhCGVqRxQKmiLQyOjpMdUAWaxVpdG0ICAjAUOfoIILkvtCoqWGrQldmTUNyH5o6JPMCsYhhswPVLdjcPFDhR0WIxX0AECLNv9WG2OcAAAAASUVORK5CYII='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>