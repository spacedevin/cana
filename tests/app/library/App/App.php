<?php

/**
 * Cana application class
 *
 * @author	Devin Smith <devins@devin-smith.com>
 * @date	2009.06.11
 *
 */


class App_App extends Cana_App {

	public function init($params = null) {
		set_exception_handler([$this, 'exception']);

		$params['postInitSkip'] = true;
		$params['env'] = 'local';

		parent::init($params);

		$config = $this->config();


		$this->config($config);


		$this
			->config($config)
			->postInit($params);
	}

	public function defaultExceptionHandler($e) {
		$this->config()->db = null;

		foreach($e->getTrace() as $k=>$v){
			if ($v['function'] == "include" || $v['function'] == "include_once" || $v['function'] == "require_once" || $v['function'] == "require"){
				$backtracels[] = "#".$k." ".$v['function']."(".$v['args'][0].") called at [".$v['file'].":".$v['line']."]";
			} else {
				$backtracels[] = "#".$k." ".$v['function']."() called at [".$v['file'].":".$v['line']."]";
			}
		}

		if (getenv('HEROKU')) {
			$stderr = fopen('php://stderr', 'w');

			fwrite($stderr, 'PHP EXCEPTION: '.$e->getMessage()."\n");

			foreach ($backtracels as $l) {
				fwrite($stderr, $l."\n");
			}

			fwrite($stderr, "\n");
			fclose($stderr);
		}

		if ($this->env == 'live') {
			echo
				'<title>Error</title><style>body {font-family: sans-serif; }.wrapper{ width: 400px; margin: 0 auto; margin-top: 25px;}</style>'.
				'<div class="wrapper">'.
				'<h1>Crunchbutton</h1>'.
				'<p style="color: #666;">HEY! Your broke it! No just kidding. There was some sort of error we did not expect. An admin has been notified.</p>'.
				'<br><p style="background: #fff7e0; color: #ff0000; padding: 3px;">Error: '.$e->getMessage().
				'</div>';
			mail('trest@arzynik.com','CRUNCHBUTTON CRITICAL ERROR',$e->getMessage());
			exit;
		} else {
			echo "\n<br />".$e->getMessage()."\n<br /><pre>";
			foreach ($backtracels as $l) {
				echo $l.'<br>';
			}
			exit(1);
		}
	}


	public function displayPage($page = null) {
		if (is_null($page)) {
			$page = $this->pages();
			$page = isset($page[0]) ? $page[0] : '';
			switch ($page) {
				case '':
					$pageName = Cana::config()->defaults->page;
					break;
				default:
					$pageName = implode('/',$this->pages());
					break;

			}
		} else {
			$pageName = $page;
		}

		if (getenv('DEBUG')) {
			error_log('>> DISPLAYING PAGE: '.$pageName);
		}

		try {
			parent::displayPage($pageName == 'error' ? 'home' : $pageName);
		} catch (Exception $e) {
			$this->exception($e);
		}

		return $this;
	}

	public function exception($e) {
		$fn = $this->exceptionHandler();
		if ($fn) {
			$fn($e);
		} else {
			$this->defaultExceptionHandler($e);
		}
	}

	public function exceptionHandler($fn = null) {
		if (!is_null($fn)) {
			$this->_exceptionHandler = $fn;
		}
		return $this->_exceptionHandler;
	}


	public function buildView($params = array()) {
		// domain level setup


		$params['theme'][] = $this->config()->defaults->version.'/'.$this->config()->defaults->theme.'/';
		if (is_array($themes = json_decode($this->config()->site->theme,'array'))) {
			$themes = array_reverse($themes);
			foreach ($themes as $theme) {
				$params['theme'][] = $this->config()->defaults->version.'/'.$theme.'/';
			}
		} else {
			$params['theme'][] = $this->config()->defaults->version.'/'.$this->config()->site->theme.'/';
		}

		if (isset($this->config()->site->version)) {
			$params['theme'][] = $this->config()->site->version.'/'.$this->config()->defaults->theme.'/';
		}
		if (is_array($themes = json_decode($this->config()->site->theme,'array'))) {
			$themes = array_reverse($themes);
			foreach ($themes as $theme) {
				$params['theme'][] = $this->config()->site->version.'/'.$theme.'/';
			}
		} elseif (isset($this->config()->site->version)) {
			$params['theme'][] = $this->config()->site->version.'/'.$this->config()->site->theme.'/';
		}
		$stack = array_reverse($params['theme']);
		$params['layout'] =  $this->config()->defaults->layout;

		foreach ($stack as $theme) {
			$this->controllerStack($theme);
		}

		parent::buildView($params);

		if ($this->config()->viewExport) {
			$this->view()->export = true;
		}

		return $this;
	}

	public function getTheme($config = null) {
		$config = $config ? $config : $this->config();

		if (is_array($themes = json_decode($config->site->brand,'array'))) {
			return $themes;
		} else {
			return $config->site->brand;
		}
	}

	public function crypt($crypt = null) {
		if (is_null($crypt)) {
			return $this->_crypt = new Cana_Crypt($this->config()->crypt->key);
		} else {
			return $this->_crypt;
		}
	}

	public function buildAcl($db = null) {
		$this->acl(new Crunchbutton_Acl($db, $this->auth()));
		return $this;
	}

	public function getEnv($d = true) {
		if (c::user()->debug) {
			$env = 'dev';
		} elseif (c::env() == 'live' || c::env() == 'crondb') {
			$env = 'live';
		} elseif ($d === true) {
			$env = 'dev';
		} else {
			$env = c::env();
		}
		return $env;
	}

}
