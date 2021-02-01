<?php
	header('Content-Type: text/html; charset=utf-8');
	//header('Content-Type: application/json');

	include 'head.php';
  include 'mainHeader.php';
	include 'header.php';
	include 'footer.php';
	include 'js.php';
	include 'session.php';


	$http_host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$url = explode('.', $request_uri);



	if(!isset($_SESSION["id"]) and $url[0] != "/event_winner" and $url[0] != "/event_winner2")
	{
		?>
	  <script type="text/javascript">alert("로그인 해주세요!")
	  window.location.href="index.php"</script>
	  <?
	}


	class Layout
	{
		public $CssJsF,$JsF,
		       $head,$mainHeader,$sideMenu,$footer,$js,$barcode_style;

    //head에 들어갈 css and js
		public function CssJsFile($CssJsF)
		{
			$this->CssJsF=$CssJsF;
		}

		//하단에 들어갈 js파일
		public function JsFile($JsF)
		{
			$this->JsF=$JsF;
		}

    //head 출력
		public function head($head)
		{
			$this->head = $head;
			echo $this->head.$this->CssJsF."</head>";
		}

    //메인 header
		public function mainHeader($mainHeader)
		{
			$this->mainHeader = $mainHeader;
			echo $this->mainHeader;
		}

		//header
		public function header($header)
		{
			$this->header = $header;
			echo $this->header;
		}

    //footer
		public function footer($footer)
		{
			$this->footer = $footer;
			echo $this->footer;
		}

    //하단 스크립트
		public function js($js)
		{
			$this->footer = $js;
			echo $this->footer.$this->JsF;
		}

		// public function barcode_style($barcode_style)
		// {
		// 	$this->barcode_style = $barcode_style;
		// 	echo $this->barcode_style;
		// }
	}
?>
