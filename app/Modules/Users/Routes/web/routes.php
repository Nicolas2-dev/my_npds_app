<?php
/**
 * Npds - Modules/Users/Routes/web/routes.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */

use Npds\Routing\Router;

/**
 * user main
 */
Router::get('user', 'App\Modules\Users\Controllers\Front\UserMain@index');


/**
 * user Home
 */
Router::get('user/edithome', 'App\Modules\Users\Controllers\Front\UserHome@edit_home');

/**
 * user home submit
 */
Router::post('user/savehome', 'App\Modules\Users\Controllers\Front\UserHome@save_home');


/**
 * user journal
 */
Router::get('user/editjournal', 'App\Modules\Users\Controllers\Front\UserJournal@edit_journal');

/**
 * user journal submit
 */
Router::post('user/savejournal', 'App\Modules\Users\Controllers\Front\UserJournal@save_journal');


/**
 * user journal
 */
Router::get('user/chgtheme', 'App\Modules\Users\Controllers\Front\UserTheme@chgt_heme');

/**
 * user journal submit
 */
Router::post('user/savetheme', 'App\Modules\Users\Controllers\Front\UserTheme@save_theme');


/**
 * user Edite User
 */
Router::get('user/edituser', 'App\Modules\Users\Controllers\Front\UserEdit@edit_user');

/**
 * user Edite Save
 */
Router::post('user/saveuser', 'App\Modules\Users\Controllers\Front\UserEdit@save_user');


/**
 * user password
 */
Router::get('user/forgetpassword', 'App\Modules\Users\Controllers\Front\UserPassword@forget_password');

/**
 * user valide password
 */
Router::post('user/forget/password', 'App\Modules\Users\Controllers\Front\UserPassword@mail_password');

/**
 * user password submit
 */
Router::get('user/validpasswd', 'App\Modules\Users\Controllers\Front\UserPassword@valid_password');

/**
 * user update password 
 */
Router::post('user/updatepasswd', 'App\Modules\Users\Controllers\Front\UserPassword@update_password');


/**
 * user password
 */
Router::get('user/newuser', 'App\Modules\Users\Controllers\Front\UserNews@only_new_user');

/**
 * 
 */
Router::post('user/newuser', 'App\Modules\Users\Controllers\Front\UserNews@confirm_new_user');

/**
 * 
 */
Router::post('user/finish', 'App\Modules\Users\Controllers\Front\UserNews@finish_new_user');


/**
 * user login
 */
Router::get('user/login', array(
    'filters'   => 'check.user.login',
    'uses'      => 'App\Modules\Users\Controllers\Front\UserLogin@login'
));

/**
 * user login submit
 */
Router::post('user/login', 'App\Modules\Users\Controllers\Front\UserLogin@submit');
 
/**
 * user login submit
 */
Router::get('user/login/auto', 'App\Modules\Users\Controllers\Front\UserLogin@submit');

/**
 * user logout
 */
Router::get('user/logout', array(
    'filters'   => 'check.user.logout',
    'uses'      => 'App\Modules\Users\Controllers\Front\UserLogin@logout'
));
