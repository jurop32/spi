<?php
/*
 * class for user login
 */

class authenticator{
    
    /**
     * Holds the last login message 
     */
    private static $lastmessage = '';
    
    /**
     * Constructor 
     */
    private static function conectdb(){
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'configuration.inc.php';
        require_once ADMIN_ABSDIRPATH.'serverscripts/connect.php';
    }
    
    /**
     * Logs in the user
     */
    public static function login($registry){
        authenticator::conectdb();
        $result = false;
        if (isset($_POST['login_meno']) && $_POST['login_meno'] != '' && isset($_POST['login_heslo']) && $_POST['login_heslo'] != '') {
            $result = authenticator::loginWithNamePass($_POST['login_meno'],$_POST['login_heslo'],$registry);
        }else if(isset($_COOKIE[md5(PAGE_URL.'admin_name')]) && isset($_COOKIE[md5(PAGE_URL.'admin_password')])){
            $result = authenticator::loginWithCookie($_COOKIE[md5(PAGE_URL.'admin_name')],$_COOKIE[md5(PAGE_URL.'admin_password')]);
        }
        return $result;
    }
    
    /**
     * Logs out the user
     */
    public static function logout(){
        authenticator::conectdb();
        authenticator::logOutUser();
    }

    /*
     * Sets the last message for login
     */
    public static function setLastMessage($lastmessage){
        authenticator::$lastmessage = $lastmessage;
    }
    
    /**
     * Prints the login form
     */
    public static function printLoginForm($registry){
        $markup = '
        <div id="loginform" class="ui-widget ui-corner-all">
            <div class="ui-widget-header ui-corner-all">
                '.$registry['configuration']->getSetting('PAGE_TITLEPREFIX').'
                <span class="ui-icon ui-icon-key right"></span>
            </div>
            <form action="'.$_SESSION[PAGE_SESSIONID]['lastrequestaddress'].'" method="post" class="ui-widget-content ui-corner-all">
                <p>
                    '.$registry['languager']->getText('authenticator','name').': <br />
                    <input type="text" value="" name="login_meno" class="ui-autocomplete-input ui-widget-content ui-corner-all" /><br />
                    '.$registry['languager']->getText('authenticator','password').': <br />
                    <input type="password" value="" name="login_heslo" class="ui-autocomplete-input ui-widget-content ui-corner-all" /><br />
                    <br />
                    <input type="submit" value="'.$registry['languager']->getText('authenticator','enter').'" class="ui-button ui-widget ui-state-default ui-corner-all" />
                </p>';
        
        if (authenticator::$lastmessage != '') {
            $markup .= '<div class="ui-state-error ui-corner-all"><p>'.authenticator::$lastmessage.'</p></div>';
            authenticator::$lastmessage = '';
        }
        
        $markup .= '</form></div>';
        
        return $markup;
    }
    
    /**
     * Login user with username and password
     */
    private static function loginWithNamePass($username,$password,$registry){
        $languager = $registry['languager'];
        $configuration = $registry['configuration'];
        $result = false;
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE username='" . $username . "' AND password='".md5($password)."'");
        $user = $temp->fetchAll();
        if (count($user) == 1) {
            $user = $user[0];
            if($user['denylogin'] == 0){
                if(!$configuration->getSetting('AUTH_SINGLEPCSIGNIN') || ($configuration->getSetting('AUTH_SINGLEPCSIGNIN') && $user['loggedin'] == 0)){

                    //getting user privileges from group
                    $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERGROUPS WHERE id=" . $user['usergroup']);
                    $group = $temp->fetchAll();

                    if(count($group) == 1){
                        $group = $group[0];

                        if($group['denylogin'] == 0){
                            $privileges = array();
                            $usergroupid = $group['id'];
                            unset($group['id']);
                            $usergroup = $group['name'];
                            unset($group['name']);
                            unset($group['denylogin']);
                            foreach($group as $name => $value){
                                $privileges[$name] = str_split($value,1);
                            }
                            $privileges['categoryaccess'] = $user['categoryaccess'];

                            dibi::query("UPDATE " . DB_TABLEPREFIX . "USERS SET loggedin=1 WHERE id=" . $user['id']);
                            setcookie(md5(PAGE_URL.'admin_name'),md5($user['username']) , time()+60*60*24*365,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
                            setcookie(md5(PAGE_URL.'admin_password'),$user['password'] , time()+60*60*24*365,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
                            $_SESSION[PAGE_SESSIONID] = array(
                                'id' => $user['id'],
                                'privileges' => $privileges,
                                'username' => $user['username'],
                                'fullname' => $user['fronttitles'].' '.$user['firstname'].' '.$user['surename'].' '.$user['endtitles'],
                                'firstname' => $user['firstname'],
                                'usergroupid' => $usergroupid,
                                'usergroup' => $usergroup
                                );
                            $result = true;
                        }else{
                            authenticator::$lastmessage = $languager->getText('authenticator','msg1');
                        }
                    }else{
                        authenticator::$lastmessage = $languager->getText('authenticator','msg2');
                    }
                }else{
                    authenticator::$lastmessage = $languager->getText('authenticator','msg3');
                }
            }else{
                authenticator::$lastmessage = $languager->getText('authenticator','msg4');
            }
        } else {
            authenticator::$lastmessage = $languager->getText('authenticator','msg5');
        }
        
        return $result;
    }
    
    /**
     * Login user with cookie
     */
    private static function loginWithCookie($username,$password){
        $result = false;
        $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERS WHERE cookiesecret='" . $username . "' AND password='" . $password."'");
        $user = $temp->fetchAll();
        if (count($user) == 1) {
            $user = $user[0];

            if($user['denylogin'] == 0){
                //getting user privileges from group
                $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERGROUPS WHERE id=" . $user['usergroup']);
                $group = $temp->fetchAll();

                if(count($group) == 1){
                    $group = $group[0];

                    if($group['denylogin'] == 0){
                        $privileges = array();
                        $usergroupid = $group['id'];
                        unset($group['id']);
                        $usergroup = $group['name'];
                        unset($group['name']);
                        unset($group['denylogin']);
                        foreach($group as $name => $value){
                            $privileges[$name] = str_split($value,1);
                        }
                        $privileges['categoryaccess'] = $user['categoryaccess'];

                        dibi::query("UPDATE " . DB_TABLEPREFIX . "USERS SET loggedin=1 WHERE id=" . $user['id']);
                        setcookie(md5(PAGE_URL.'admin_name'),md5($user['username']) , time()+60*60*24*365,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
                        setcookie(md5(PAGE_URL.'admin_password'),$user['password'] , time()+60*60*24*365,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
                        $_SESSION[PAGE_SESSIONID] = array(
                            'id' => $user['id'],
                            'privileges' => $privileges,
                            'username' => $user['username'],
                            'fullname' => $user['fronttitles'].' '.$user['firstname'].' '.$user['surename'].' '.$user['endtitles'],
                            'firstname' => $user['firstname'],
                            'usergroupid' => $usergroupid,
                            'usergroup' => $usergroup
                            );
                        $result = true;
                    }else{
                        dibi::query("UPDATE " . DB_TABLEPREFIX . "USERS SET loggedin=0 WHERE id=" . $user['id']);
                        setcookie(md5(PAGE_URL.'admin_name'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
                        setcookie(md5(PAGE_URL.'admin_password'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
                    }
                }
            }else{
                dibi::query("UPDATE " . DB_TABLEPREFIX . "USERS SET loggedin=0 WHERE id=" . $user['id']);
                setcookie(md5(PAGE_URL.'admin_name'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
                setcookie(md5(PAGE_URL.'admin_password'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
            }
        }else{
            setcookie(md5(PAGE_URL.'admin_name'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
            setcookie(md5(PAGE_URL.'admin_password'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
        }
        
        return $result;
    }
    
    /**
     * Log out the user 
     */
    private static function logOutUser(){
        if(isset($_SESSION[PAGE_SESSIONID]['id'])){
    
            dibi::query("UPDATE " . DB_TABLEPREFIX . "USERS SET loggedin=0 WHERE id=" . $_SESSION[PAGE_SESSIONID]['id']);

            setcookie(md5(PAGE_URL.'admin_name'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);
            setcookie(md5(PAGE_URL.'admin_password'),'', time()-3600,'/',$_SERVER['HTTP_HOST'],FALSE,TRUE);

            unset($_SESSION[PAGE_SESSIONID]);
        }
    }

    /*
     * Refresh the user privileges in the session
     */
    public static function refreshUserData(){
        if (isset($_SESSION[PAGE_SESSIONID]['id']) && isset($_SESSION[PAGE_SESSIONID]['id'])) {
            authenticator::conectdb();
            $temp = dibi::query("SELECT usergroup,denylogin,categoryaccess FROM " . DB_TABLEPREFIX . "USERS WHERE id=".$_SESSION[PAGE_SESSIONID]['id']);
            $user = $temp->fetchAll();
            if (count($user) != 1){
                authenticator::logout();
            }else{
                $user = $user[0];

                $temp = dibi::query("SELECT * FROM " . DB_TABLEPREFIX . "USERGROUPS WHERE id=" . $user['usergroup']);
                $group = $temp->fetchAll();

                if(count($group) != 1){
                    authenticator::logout();
                }else{
                    $group = $group[0];

                    if($user['denylogin'] == 1 || $group['denylogin'] == 1){
                        authenticator::logout();
                    }else{

                        $privileges = array();
                        unset($group['id']);
                        unset($group['name']);
                        unset($group['denylogin']);
                        foreach($group as $name => $value){
                            $privileges[$name] = str_split($value,1);
                        }
                        $privileges['categoryaccess'] = $user['categoryaccess'];

                        $_SESSION[PAGE_SESSIONID]['privileges'] = $privileges;
                    }
                }
            }
        }
    }
}