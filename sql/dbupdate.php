<#1>
<?php
require_once("./Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/class.ilOwnCloudPlugin.php");
$pl = ilOwnCloudPlugin::getInstance();

$fields = array(
    'id' => array(
        'type' => 'integer',
        'length' => 8,
        'notnull' => true
    ),
    'base_uri' => array(
        'type' => 'text',
        'length' => 256
    ),
    'username' => array(
        'type' => 'text',
        'length' => 256
    ),
    'password' => array(
        'type' => 'text',
        'length' => 256
    ),
    'proxy' => array(
        'type' => 'text',
        'length' => 256
    ),
);
global $ilDB;
if (!$ilDB->tableExists($pl->getPluginTableName())) {
    $ilDB->createTable($pl->getPluginTableName(), $fields);
    $ilDB->addPrimaryKey($pl->getPluginTableName(), array( "id" ));
}
?>
<#2>
<?php
require_once("./Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/class.ownclConfig.php");
$config = new ownclConfig();
$config->initDB();
?>
<#3>
<?php
require_once("./Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/class.ownclConfig.php");
$config = new ownclConfig();
$config->setValue(ownclConfig::F_TITLE, 'OwnCloud');
$config->setValue(ownclConfig::F_DESCRIPTION, 'Anbindung des Cloud-Dienstes OwnCloud');
?>
<#4>
<?php
require_once("./Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/class.ownclConfig.php");
$config = new ownclConfig();
if ($base_url = $config->getValue(ownclConfig::F_BASEURL)) {
	if ($pos = strpos($base_url, '/'.ownclConfig::DEFAULT_WEBDAV_PATH)) {
		$config->setValue(ownclConfig::F_SERVER_URL, substr($base_url, 0, $pos));
	} else {
		$config->setValue(ownclConfig::F_SERVER_URL, $base_url);
	}
}
?>
<#5>
<?php
//if (!$ilDB->tableColumnExists('cld_cldh_owncld_props', 'access_token')) {
//	$ilDB->addTableColumn('cld_cldh_owncld_props', 'access_token', array(
//		'type' => 'text',
//		'length' => 2000,
//	));
//}
//
//if (!$ilDB->tableColumnExists('cld_cldh_owncld_props', 'refresh_token')) {
//	$ilDB->addTableColumn('cld_cldh_owncld_props', 'refresh_token', array(
//		'type' => 'text',
//		'length' => 2000,
//	));
//}
//
//if (!$ilDB->tableColumnExists('cld_cldh_owncld_props', 'valid_through')) {
//	$ilDB->addTableColumn('cld_cldh_owncld_props', 'valid_through', array(
//		'type' => 'integer',
//		'length' => 8,
//	));
//}
//
//if (!$ilDB->tableColumnExists('cld_cldh_owncld_props', 'validation_user_id')) {
//	$ilDB->addTableColumn('cld_cldh_owncld_props', 'validation_user_id', array(
//		'type' => 'integer',
//		'length' => 8,
//	));
//}
?>
<#6>
<?php
if ($ilDB->tableColumnExists('cld_cldh_owncld_props', 'access_token')) {
	$ilDB->dropTableColumn('cld_cldh_owncld_props', 'access_token');
}

if ($ilDB->tableColumnExists('cld_cldh_owncld_props', 'refresh_token')) {
	$ilDB->dropTableColumn('cld_cldh_owncld_props', 'refresh_token');
}

if ($ilDB->tableColumnExists('cld_cldh_owncld_props', 'valid_through')) {
	$ilDB->dropTableColumn('cld_cldh_owncld_props', 'valid_through');
}

if ($ilDB->tableColumnExists('cld_cldh_owncld_props', 'validation_user_id')) {
	$ilDB->dropTableColumn('cld_cldh_owncld_props', 'validation_user_id');
}
?>
<#7>
<?php
require_once 'Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/Auth/Token/class.ownclOAuth2UserToken.php';
ownclOAuth2UserToken::updateDB();
?>
<#8>
<?php
include_once("./Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/class.ilOwnCloudPlugin.php");
$plugin_object = ilOwnCloudPlugin::getInstance();
/** @var $ilDB ilDBInterface */
if (!$ilDB->tableColumnExists('cld_cldh_owncld_props', 'allow_open_in_owncloud')) {
	$ilDB->addTableColumn(
			'cld_cldh_owncld_props',
			'allow_open_in_owncloud',
			[
				'type' => 'integer',
                'length' => 1
			]
		);
}
?>
<#9>
<?php
require_once("./Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/class.ownclConfig.php");
$conf = new ownclConfig();
$conf->setValue(
		ownclConfig::F_COLLABORATION_APP_INTEGRATION . '_' . ownclConfig::F_COLLABORATION_APP_FORMATS,
		'xls,xlsx,doc,docx,dot,dotx,odt,ott,rtf,txt,pdf,pdfa,html,epub,xps,djvu,djv,ppt,pptx'
);
?>
<#10>
<?php
require_once("./Customizing/global/plugins/Modules/Cloud/CloudHook/OwnCloud/classes/class.ownclConfig.php");
$conf = new ownclConfig();
$conf->setValue(ownclConfig::F_BASE_DIRECTORY, '/ILIASshare');
?>
<#11>
<?php
// rename rbac operation if already existent (from onedrive plugin)
global $DIC;
$query = $DIC->database()->query('SELECT * FROM rbac_operations WHERE operation = "cld_cldh_exod_asl_open_msoffice"');
if ($res = $DIC->database()->fetchAssoc($query)) {
    require_once("./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php");
    if (ilDBUpdateNewObjectType::getCustomRBACOperationId('edit_in_online_editor')) {
        $DIC->database()->query('DELETE FROM rbac_operations WHERE ops_id = ' . $res['ops_id']);
    } else {
        $DIC->database()->query('UPDATE rbac_operations SET operation = "edit_in_online_editor", description = "edit in online editor" WHERE ops_id = ' . $res['ops_id']);
    }
} else {
    //Adding a new Permission ("Open in Online Editor")
    require_once("./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php");
    $cld_type_id = ilDBUpdateNewObjectType::getObjectTypeId('cld');
    if ($cld_type_id) {
        $open_collaboration_app = ilDBUpdateNewObjectType::addCustomRBACOperation('edit_in_online_editor', 'edit in online editor', 'object', 280);
        if ($open_collaboration_app) {
            ilDBUpdateNewObjectType::addRBACOperation($cld_type_id, $open_collaboration_app);
        }
    }
}
?>
