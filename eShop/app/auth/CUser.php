<?php
namespace app\auth;

class CUser {
	protected $pdo;
	private $login = 'anonim';
	private $token;
	private $user_id;
	private $user_name;
	private $user_mail;
	private $user_phone;
	private $user_addres;
	private $is_auth = false; 
	private $permissions = [	
		'view_profile'=> false
	];
	private $avatar;

	public function __construct($pdo) {
		$this->pdo = $pdo;
		if(!$this->isAuth()) {
			if(isset($_POST['email']) 
				&& isset($_POST['name']) 
				&& isset($_POST['addres']) 
				&& isset($_POST['phone'])
				&& isset($_POST['login'])
				&& isset($_POST['password']))
			{
				$this->registration();
			}
			if(isset($_POST['login']) && isset($_POST['password'])) {
				$this->login($_POST['login'], $_POST['password']);
			}
		}
	}
	// проверка авторизован ли пользователь
	public function isAuth() {
		if($this->user_id !== null) {
			return true;
		}
		if(
			isset($_SESSION['user']) &&
			isset($_SESSION['user']['token']) &&
			isset($_SESSION['user']['login']) &&
			$_SESSION['user']['token'] === md5(
						$_SESSION['user']['login']
						.$_SERVER['REMOTE_ADDR']
						.$_SERVER['HTTP_USER_AGENT']
			)
		) {
			$this->user_id = $_SESSION['user']['user_id'];
			$this->login = $_SESSION['user']['login'];
			$this->user_name = $_SESSION['user']['name'];
			$this->user_addres = $_SESSION['user']['addres'];
			$this->user_phone = $_SESSION['user']['phone'];
			$this->user_mail = $_SESSION['user']['mail'];
			$this->avatar = $_SESSION['user']['avatar'];
			return true;
		}
		return false;
	}
	
	public function login($login,$password) {
		$query = "SELECT  * FROM `user` WHERE login = :login AND password = :password;";
		$password = md5($password);
		$resalt = $this->pdo->prepare($query); 
		$resalt->bindParam('login', $login); 
		$resalt->bindParam('password', $password); 
		$resalt->execute();
		$resalt = $resalt->fetchAll();

		if(count($resalt) >0) {
			$this->login = $login;
			$this->user_id = $resalt[0]['id']; 
			$this->user_name = $resalt[0]['name'];
			$this->user_mail = $resalt[0]['email'];
			$this->user_phone = $resalt[0]['phone'];
			$this->user_addres = $resalt[0]['addres'];
			if(isset($resalt[0]['avatar_type'])) {
				$this->avatar = $this->user_id .".". $resalt[0]['avatar_type']; 
			}
			$this->token = md5($login . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
			
			$_SESSION['user'] = [
					'login' => $this->login,
					'user_id' => $this->user_id,
					'token' => $this->token,
					'name' => $this->user_name,
					'addres' => $this->user_addres,
					'phone' => $this->user_phone,
					'mail' => $this->user_mail,
					'avatar' => $this->avatar
			];
		} 
	}
	// регистрация
	public function registration() {
		$query = "SELECT * FROM `user` WHERE login='".$_POST['login']."';";
		$result = $this->pdo->query($query)->fetchAll();
		if(count($result) == 0 ) {
			$query = "INSERT INTO `user` VALUES (NULL,'".$_POST['login']."','"
														.md5($_POST['password'])."','"
														.$_POST['email']."','"
														.$_POST['phone']."','"
														.$_POST['name']."','"
														.$_POST['addres']."',
														NULL);"; 
			$this->pdo->query($query); //добавляем пользователя в базу `user`
			$this->login($_POST['login'],md5($_POST['password']));
		} else {
			print("<script language=javascript>window.alert('такой логин уже есть');</script>");
		}
	}
	//смена личных данных пользователя
	public function changeSettings() {
		if(isset($_POST['confirmPass']) && $_POST['confirmPass'] !== "" && $this->confirmPass($_POST['confirmPass'])) {
			if(isset($_POST['repeatConfirmPass']) && $this->confirmPass($_POST['repeatConfirmPass'])) {
				if(isset($_POST['changeLogin']) && $_POST['changeLogin'] !== $this->getLogin()) {
					$query = "UPDATE `user` SET login = '".$_POST['changeLogin']."' WHERE id = ".$this->user_id.";";
					$this->pdo->query($query);
					$this->login = $_POST['changeLogin'];
					$_SESSION['user']['login'] = $_POST['changeLogin'];
					$_SESSION['user']['token'] = md5(
						$_SESSION['user']['login']
						.$_SERVER['REMOTE_ADDR']
						.$_SERVER['HTTP_USER_AGENT']);
				}
				if(isset($_POST['changeName']) && $_POST['changeName'] !== $this->getUserName()) {
					$query = "UPDATE `user` SET name = '".$_POST['changeName']."' WHERE id = ".$this->user_id.";";
					$this->pdo->query($query);
					$this->user_name = $_POST['changeName'];
					$_SESSION['user']['name'] = $_POST['changeName'];
				}
				if(isset($_POST['changePhone']) && $_POST['changePhone'] !== $this->getPhone()) {
					$query = "UPDATE `user` SET phone = '".$_POST['changePhone']."' WHERE id = ".$this->user_id.";";
					$this->pdo->query($query);
					$this->user_phone = $_POST['changePhone'];
					$_SESSION['user']['phone'] = $_POST['changePhone'];
				}
				if(isset($_POST['changeMail']) && $_POST['changeMail'] !== $this->getMail()) {
					$query = "UPDATE `user` SET email = '".$_POST['changeMail']."' WHERE id = ".$this->user_id.";";
					$this->pdo->query($query);
					$this->user_mail = $_POST['changeMail'];
					$_SESSION['user']['mail'] = $_POST['changeMail'];
				}
				if(isset($_POST['changeAddres']) && $_POST['changeAddres'] !== $this->getAddres()) {
					$query = "UPDATE `user` SET addres = '".$_POST['changeAddres']."' WHERE id = ".$this->user_id.";";
					$this->pdo->query($query);
					$this->user_addres = $_POST['changeAddres'];
					$_SESSION['user']['addres'] = $_POST['changeAddres'];

				}
				if(isset($_FILES['changeAvatar']) && $_FILES['changeAvatar']['size'] !== 0) {
					if(!empty($this->avatar)) {
						if(file_exists(__DIR__."/../../public/assets/images/avatars/". $this->avatar)) {
							unlink(__DIR__."/../../public/assets/images/avatars/". $this->avatar);
						}
					}
					//формируем новое имя файла
					$new_path_file = __DIR__."/../../public/assets/images/avatars/".$this->user_id.".";
					$old_file_name = $_FILES['changeAvatar']['name'];
					$old_file_name = explode('.', $old_file_name);
					$ext = array_pop($old_file_name);
					$new_path_file .= $ext;
					move_uploaded_file($_FILES['changeAvatar']['tmp_name'], $new_path_file);
					$query = "UPDATE `user` SET avatar_type = '".$ext."' WHERE id = {$this->user_id};";
					$this->pdo->query($query);
					$this->avatar = $this->user_id . '.' . $ext;
					$_SESSION['user']['avatar'] = $this->avatar;


				}
				if(isset($_POST['changePass']) && $_POST['changePass'] !== '' && strlen($_POST['changePass']) > 2) {
					$query = "UPDATE `user` SET password ='".md5($_POST['changePass'])."' WHERE id=".$this->user_id.";";
					$this->pdo->query($query);
				}
			}
		}
	}
	//проверить(сравнить) введенный пароль в форме смены личных данных
	private function confirmPass($pass_for_confirm) {
		$pass_for_confirm = md5($pass_for_confirm);
		$query = "SELECT password FROM `user` WHERE id = ".$this->user_id.";";
		$pass = $this->pdo->query($query)->fetchAll();
		if($pass[0]['password'] === $pass_for_confirm)
			return true;
		else {
			echo "<script>alert('не верный пароль');</script>";
			return false;
		}
	}
	
	public function logout() {
		$this->user_id = null;
		$this->token = null;
		$this->login = 'anonim';
		unset($_SESSION['user']);
	}
	public function getUserName() {
		return $this->user_name;
	}
	public function getLogin() {
		return $this->login;
	}
	public function getUserId() {
		return $this->user_id;
	}
	public function getAddres() {
		return $this->user_addres;
	}
	public function getMail() {
		return $this->user_mail;
	}
	public function getPhone() {
		return $this->user_phone;
	}
	public function getAvatar() {
		return $this->avatar;
	}
}