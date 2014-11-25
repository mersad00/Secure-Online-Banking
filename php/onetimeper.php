<?php
require_once '../PhpRbac/src/PhpRbac/Rbac.php';
ini_set('display_errors', 'On');
$rbac = new \PhpRbac\Rbac();

//don't call this method it resets all roles and permissions
//$rbac->reset(true);

/* DEFAULT SETTINGS

//default roles creation
$client_role_id = $rbac->Roles->add('client', 'User can inteact with client pages');
$employee_role_id = $rbac->Roles->add('employee', 'Employee activities including activation and confirmation');
$admin_role_id = $rbac->Roles->add('admin', 'Admin activities including activation and confirmation');
//end of default roles


//default permissions declaration
$admin_perm_id = $rbac->Permissions->add('admin-permission', 'all admin permissions');
$client_perm_id = $rbac->Permissions->add('client-permission', 'all client permissions');
$employee_perm_id = $rbac->Permissions->add('employee-permission', 'all employee permissions');

$rbac->Permissions->assign($admin_role_id, $admin_perm_id);
$rbac->Permissions->assign($client_role_id, $client_perm_id);
$rbac->Permissions->assign($employee_role_id, $employee_perm_id);
//end of default permissions

//assign admin and alice default roles
// 11 is admin's id
$rbac->Users->assign($admin_role_id, 11);
$rbac->Users->assign($employee_role_id, 11);
//1 is alice's id
$rbac->Users->assign($client_role_id, 1);
//end of user's role assignment

*/ //End Of default settings



//$rbac->enforce('employee-permission', 1);
//$rbac->enforce('admin-permission', 11);
?>