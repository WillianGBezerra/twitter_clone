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

			$this->getInfoUsuario();

			$this->render('timeline');	
		}

		public function getInfoUsuario() {
			$usuario = Container::getModel('Usuario');
			$usuario->__set('id', $_SESSION['id']);
			$this->view->info_usuario = $usuario->getInfoUsuario();
			$this->view->total_tweets = $usuario->getTotalTweets();
			$this->view->total_seguindo = $usuario->getTotalSeguindo();
			$this->view->total_seguidores = $usuario->getTotalSeguidores();
		}

		public function tweet() {

			session_start();
				$tweet = Container::getModel('tweet');

				$tweet->__set('tweet', $_POST['tweet']);
				$tweet->__set('id_usuario', $_SESSION['id']);

				$tweet->salvar();

				header('Location: /timeline');
		}

		public function deletarTweet() {

			session_start();
				$tweet = Container::getModel('tweet');
				$id = isset($_GET['id']) ? $_GET['id'] : '';

				$tweet->__set('id', $_POST['id']);

				$tweet->deletar();

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

			$this->getInfoUsuario();

			$this->render('quemSeguir');
		}

		public function acao() {
			
			$this->validaAutenticacao();

			//echo '<br/><br/><br/><br/>';
			$pesquisa = $_GET['pesquisarPor'];
			$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
			$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';
			
			$usuario = Container::getModel('Usuario');
			$usuario->__set('id', $_SESSION['id']);

			if($acao == 'seguir') {
				$usuario->seguirUsuario($id_usuario_seguindo);
				echo 'Seguir';
			} else if($acao == 'deixar_de_seguir') {
				$usuario->deixarSeguirUsuario($id_usuario_seguindo);
				echo 'Deixar de seguir';
				print_r($id_usuario_seguindo);
			}

		header('Location: /quem_seguir');
		}
    }
?>