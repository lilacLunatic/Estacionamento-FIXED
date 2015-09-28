<?php

function checkSession(){
	session_start();
	if(!empty($_SESSION)){
		if (isset($_SESSION['admin']) && $_SESSION['admin'] == true){
			return 'admin';
		} else {
			return 'funcionario';
		}
	} else{
		return false;
	}
}
?>