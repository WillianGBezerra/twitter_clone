<?php
	namespace App\Controllers;

	//Recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

    class AppController extends Action {

        public function timeline() {

			
			$this->validaAutenticacao();
				
			//recuperar tweets
			$tweet = Container::getModel('Tweet');

			$tweet->__set('id_usuario', $_SESSION['id']);
			$tweets = $tweet->getAll(); 
			// echo '<pre>';
			// print_r($tweets);
			// echo '</pre>';

			$this->view->tweets = $tweets;

			$this->render('timeline');	
		}

		public function tweet() {

			session_start();
				$tweet = Container::getModel('tweet');

				$tweet->__set('tweet', $_POST['tweet']);
				$tweet->__set('id_usuario', $_SESSION['id']);

				$tweet->salvar();

				header('Location: /timeline');
		}

		public function validaAutenticacao() {

			session_start();
			
			if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
				header('Location: /?login=erro');
			}
			
		}

		public function quemSeguir() {

			$this->validaAutenticacao();
		
			$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

			// echo '<br/><br/><br/><br/><br/><br/><br/>';
			// echo 'Pesquisando por: '.$pesquisarPor;
			
			$usuarios = array();

			if($pesquisarPor != '') {

				$usuario = Container::getModel('Usuario');
				$usuario->__set('nome', $pesquisarPor);
				$usuario->__set('id', $_SESSION['id']);
				$usuarios = $usuario->getAll();

				// echo '<pre>';
				// print_r($usuarios);
				// echo '</pre>';
			}	

			$this->view->usuarios = $usuarios;

			$this->render('quemSeguir');
		}
    }
?>