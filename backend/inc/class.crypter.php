<?PHP
// File: $Id: class.crypter.php 28 2008-05-11 19:18:49Z mistral $
// +----------------------------------------------------------------------+
// | Version: Sefrengo $Name:  $                                          
// +----------------------------------------------------------------------+
// | Copyright (c) 2005 - 2007 sefrengo.org <info@sefrengo.org>           |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License                 |
// |                                                                      |
// | This program is subject to the GPL license, that is bundled with     |
// | this package in the file LICENSE.TXT.                                |
// | If you did not receive a copy of the GNU General Public License      |
// | along with this program write to the Free Software Foundation, Inc., |
// | 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA               |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// +----------------------------------------------------------------------+
// + Autor: $Author: mistral $
// +----------------------------------------------------------------------+
// + Revision: $Revision: 28 $
// +----------------------------------------------------------------------+
// + Description:
// +----------------------------------------------------------------------+
// + Changes:
// +----------------------------------------------------------------------+
// + ToDo:
// +----------------------------------------------------------------------+

class crypter{

   var $key;

   function crypter($clave) {
      $this->key = $clave;
   }

   function setKey($clave) {
      $this->key = $clave;
   }

   function keyED($txt) {
      $encrypt_key = md5($this->key);
      $ctr=0;
      $tmp = "";
      for ($i=0;$i<strlen($txt);$i++) {
         if ($ctr==strlen($encrypt_key)) $ctr=0;
         $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
         $ctr++;
      }
      return $tmp;
   }

   function encryptkey($txt){
      srand((double)microtime()*1000000);
      $encrypt_key = md5(rand(0,32000));
      $ctr=0;
      $tmp = "";
      for ($i=0;$i<strlen($txt);$i++){
         if ($ctr==strlen($encrypt_key)) $ctr=0;
         $tmp.= substr($encrypt_key,$ctr,1) .
             (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
         $ctr++;
      }
      return base64_encode($this->keyED($tmp));
   }

   function decryptkey($txt) {
      $txt = $this->keyED(base64_decode($txt));
      $tmp = "";
      for ($i=0;$i<strlen($txt);$i++){
         $md5 = substr($txt,$i,1);
         $i++;
         $tmp.= (substr($txt,$i,1) ^ $md5);
      }
      return $tmp;
   }
}
?>
