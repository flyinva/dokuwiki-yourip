<?php
/**
 * DokuWiki Plugin yourip (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Artem Sidorenko <artem@2realities.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_yourip extends DokuWiki_Syntax_Plugin {
    public function getType() {
        return 'substition';
    }

    public function getPType() {
        return 'block';
    }

    public function getSort() {
        return 99;
    }


    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~YOURIP_.*?~~',$mode,'plugin_yourip');
    }

    public function handle($match, $state, $pos, &$handler){
        $data = array("yourip_type"=>"");
        $match = substr($match, 9, -2);

        if ($match == 'BOX')
            $data['yourip_type'] = 'box';
        elseif ($match == 'LINE')
            $data['yourip_type'] = 'line';

        return $data;
    }

    public function render($mode, &$renderer, $data) {
        if($mode != 'xhtml') return false;

        $ip = getenv ("REMOTE_ADDR");
        $type=false;
        if (substr_count($ip,":") > 1 && substr_count($ip,".") == 0)
            $type='ipv6';
        else
            $type='ipv4';

        #show the things, here info in the box
        $text=false;
        if($data['yourip_type']=="box"){
            $text="<div id='yourip' class='$type'>";
            if($type=='ipv6')
                $text .= "You've got IPv6! <br/>IPv6 connection from <a href='http://www.sixxs.net/tools/ipv6calc/'>$ip</a>";
            else
                $text .= "<a href='http://www.sixxs.net/signup/create/'>Haven't got IPv6? Sign up for free IPv6!</a> <br/>IPv4 connection from <a href='http://www.sixxs.net/tools/ipv6calc/'>$ip</a>";
            $text .="</div>";
            $renderer->doc .= $text;
            return true;

        #info as line
        }elseif($data['yourip_type']=="line"){
            $text="<p id='yourip' class='$type'>";
            if($type=='ipv6')
                $text .= "IPv6 connection from <a href='http://www.sixxs.net/tools/ipv6calc/'>$ip</a>";
            else
                $text .= "IPv4 connection from <a href='http://www.sixxs.net/tools/ipv6calc/'>$ip</a>";
            $text .="</p>";
            $renderer->doc .= $text;
            return true;
        }
            else return false;
    }
}

// vim:ts=4:sw=4:et:
