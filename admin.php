<?php
/*
  Plugin Panier PayPal Pour Piwigo
  Copyright (C) 2011 www.queguineur.fr — Tous droits réservés.
  
  Ce programme est un logiciel libre ; vous pouvez le redistribuer ou le
  modifier suivant les termes de la “GNU General Public License” telle que
  publiée par la Free Software Foundation : soit la version 3 de cette
  licence, soit (à votre gré) toute version ultérieure.
  
  Ce programme est distribué dans l’espoir qu’il vous sera utile, mais SANS
  AUCUNE GARANTIE : sans même la garantie implicite de COMMERCIALISABILITÉ
  ni d’ADÉQUATION À UN OBJECTIF PARTICULIER. Consultez la Licence Générale
  Publique GNU pour plus de détails.
  
  Vous devriez avoir reçu une copie de la Licence Générale Publique GNU avec
  ce programme ; si ce n’est pas le cas, consultez :
  <http://www.gnu.org/licenses/>.
*/
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');
global $template;
include_once(PHPWG_ROOT_PATH .'admin/include/tabsheet.class.php');
load_language('plugin.lang', PPPPP_PATH);
$my_base_url = get_admin_plugin_menu_link(__FILE__);
include_once (PPPPP_PATH.'/constants.php');

// onglets
if (!isset($_GET['tab']))
    $page['tab'] = 'currency';
else
    $page['tab'] = $_GET['tab'];

$tabsheet = new tabsheet();
$tabsheet->add('currency',
               l10n('Currency'),
               $my_base_url.'&amp;tab=currency');
$tabsheet->add('size',
               l10n('Size'),
               $my_base_url.'&amp;tab=size');
$tabsheet->add('shipping',
               l10n('Shipping cost'),
               $my_base_url.'&amp;tab=shipping');			   
$tabsheet->select($page['tab']);
$tabsheet->assign();

switch($page['tab']){
 case 'currency':
  $array_currency=array(
  'AUD'=>'Australian Dollar',
  'BRL'=>'Brazilian Real',
  'CAD'=>'Canadian Dollar',
  'CZK'=>'Czech Koruna',
  'DKK'=>'Danish Krone',
  'EUR'=>'Euro',
  'HKD'=>'Hong Kong Dollar',
  'HUF'=>'Hungarian Forint',
  'ILS'=>'Israeli New Sheqel',
  'JPY'=>'Japanese Yen',
  'MYR'=>'Malaysian Ringgit',
  'MXN'=>'Mexican Peso',
  'NOK'=>'Norwegian Krone',
  'NZD'=>'New Zealand Dollar',
  'PHP'=>'Philippine Peso',
  'PLN'=>'Polish Zloty',
  'GBP'=>'Pound Sterling',
  'SGD'=>'Singapore Dollar',
  'SEK'=>'Swedish Krona',
  'CHF'=>'Swiss Franc',
  'TWD'=>'Taiwan New Dollar',
  'THB'=>'Thai Baht',
  'USD'=>'U.S. Dollar'
  );
  if(isset($_POST['currency'])){
   $currency=$_POST['currency'];
   $query='UPDATE '.PPPPP_CONFIG_TABLE.' SET value = \''.$currency.'\' WHERE param = \'currency\';';
   pwg_query($query);
   $page['infos']=l10n('Data updated');
   }
 
  $query='SELECT value FROM '.PPPPP_CONFIG_TABLE.' WHERE param = \'currency\';';
  $result = pwg_query($query);
  $row = mysql_fetch_array($result);
  $template->assign('ppppp_currency',$row[0]);
  
  $template->assign('ppppp_array_currency',$array_currency);
  break;
 
 case 'size':
  if(isset($_POST['delete'])and is_numeric($_POST['delete'])){
   $delete_id=$_POST['delete'];
   $query='DELETE FROM '.PPPPP_SIZE_TABLE.' WHERE id = '.$delete_id.';';
   pwg_query($query);
   $page['infos']=l10n('Data deleted');
   }
  else if (isset($_POST['size'])and isset($_POST['price'])and is_numeric($_POST['price'])){
   $size=$_POST['size'];
   $price=$_POST['price'];
   $query='INSERT into '.PPPPP_SIZE_TABLE.' (size,price) values (\''.$size.'\',\''.$price.'\');';
   @$res=pwg_query($query);
   if($res==1)
    $page['infos']=l10n('Data appened');
   else
    $page['errors']=l10n('Error');
  }
  $query='SELECT * FROM '.PPPPP_SIZE_TABLE.';';
  $result = pwg_query($query);
  while($row = mysql_fetch_array($result)){
   $template->append('ppppp_array_size',$row);
  }
  break;

 case 'shipping':
  if(isset($_POST['fixed_shipping'])and is_numeric($_POST['fixed_shipping'])){
   $fixed_shipping=$_POST['fixed_shipping'];
   $query='UPDATE '.PPPPP_CONFIG_TABLE.' SET value = \''.$fixed_shipping.'\' WHERE param = \'fixed_shipping\';';
   pwg_query($query);
   $page['infos']=l10n('Data updated');
   }
  $query='SELECT value FROM '.PPPPP_CONFIG_TABLE.' WHERE param = \'fixed_shipping\';';
  $result = pwg_query($query);
  $row = mysql_fetch_array($result);
  $template->assign('ppppp_fixed_shipping',$row[0]);
  break;
 }

$template->set_filenames(array('plugin_admin_content' => dirname(__FILE__) . '/admin.tpl')); 
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>