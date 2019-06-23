<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Models\User;

$user = new User();

// Create user
// $user->create([
//     'username' => 'tooshort06',
//     'password' => password_hash('1234', PASSWORD_DEFAULT),
//     'fullname' => 'Christopher P. Vistal'
// ]);

// Get all records of user.
// print_r($user->get());

/**
  *  Will pick a user randomly.
 */
// print_r($user->getOne());

// Find user
/*$user->find(18);
echo $user->username;
echo $user->password;
echo $user->fullname;*/

// Update user
/*$user->find(18);
$user->username = 'user1';
$user->password = password_hash('12345', PASSWORD_DEFAULT);
$user->fullname = 'Chris P. Vistal';
$user->update();*/

// Delete user
// $user->find(19)->delete();

