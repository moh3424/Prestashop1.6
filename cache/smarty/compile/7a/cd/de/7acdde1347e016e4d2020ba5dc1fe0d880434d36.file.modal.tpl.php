<?php /* Smarty version Smarty-3.1.19, created on 2019-08-18 13:09:22
         compiled from "C:\xampp\htdocs\prestashop\administration\themes\default\template\helpers\modules_list\modal.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12071143065d5931e29e99f4-24087720%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7acdde1347e016e4d2020ba5dc1fe0d880434d36' => 
    array (
      0 => 'C:\\xampp\\htdocs\\prestashop\\administration\\themes\\default\\template\\helpers\\modules_list\\modal.tpl',
      1 => 1565715223,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12071143065d5931e29e99f4-24087720',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5d5931e2a10af8_05219494',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d5931e2a10af8_05219494')) {function content_5d5931e2a10af8_05219494($_smarty_tpl) {?><div class="modal fade" id="modules_list_container">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title"><?php echo smartyTranslate(array('s'=>'Recommended Modules and Services'),$_smarty_tpl);?>
</h3>
			</div>
			<div class="modal-body">
				<div id="modules_list_container_tab_modal" style="display:none;"></div>
				<div id="modules_list_loader"><i class="icon-refresh icon-spin"></i></div>
			</div>
		</div>
	</div>
</div>
<?php }} ?>
