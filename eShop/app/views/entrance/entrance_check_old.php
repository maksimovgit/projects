<?php
	if (!empty($_POST['login']) ) {
        if(!strpos($_POST['login'],':') ) {
            $login = $_POST['login'];
            $pass = md5($_POST['password']);
            if(isset($_POST['name']) && isset($_POST['mail']) ) { 
                if(!strpos($_POST['name'],':') && !strpos($_POST['mail'],':')) {
                    $otherParams = [];
                    $otherParams['name'] = $_POST['name'];
                    $otherParams['mail'] = $_POST['mail'];
                    //регистрация
                    $user->registerUser($login, $pass, $otherParams);
                } else
                    print("<script language=javascript>window.alert('недопустимые данные ввода');</script>");
            }
            else   //если name и mail не введены значит пытаемся авторизоваться 
                $user->login($login, $pass);
        }
        else {
            print("<script language=javascript>window.alert('недопустимые данные ввода');</script>");
        }
    }
    if (!empty($_GET['logout'])) { 
        //если значение в $_GET['logout'] есть, то выходим
        $user->logout(); 
    }
?>