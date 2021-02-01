<?php
/****************************************************************************
					Created on 2009. 5. 25.
					Last Modify 2009. 5. 27.
					Author	:	붉은고래 (code_007골뱅이naver.com)
****************************************************************************/
/* ######################### M a n u a l #########################################################
- paging 객체 생성시 파라미터 값을 넣지 않으면 기본 셋팅값으로 선택됨
+ paging 기본 셋팅값은 현재페이지=1, 리스트수 = 10, 블럭단위 = 10, 총 게시물 수 = 0

- getVar()	: 객체(변수)의 값에 접근할 수 있음
* setVar()	: 객체의 값을 설정 할 수 있음 (코드 주석해제후 사용 but 사용 권장하지 않음. 92번라인)
- setAuto()	: 접근 불가. 페이징 알고리즘 연산 메서드
- setUrl()	: get값으로 넘길 변수=값 을 셋팅하면 해당 값을 가지고 다님. post방식 지원 안함

- setDisplay()	: 화면에 보여질 페이징 화면을 바꾸고 셋팅할 수 있음
+ setDisplay 메서드가 지원하는 파라미터에 대한 설명
 - full		: 기본 디스플레이 => [1]234 [다음], 풀모드=> [처음][이전][1]234[다음][끝]
 - class	: 반환되는 html 코드의 <a 태그에 class이름을 지정 할 수 있음
 - id		: 반환되는 html 코드의 <a 태그에 id 이름을 지정 할 수 있음
 - next_btn	: [다음] 버튼을 바꿀수 있음
 - prev_btn	: [이전] 버튼을 바꿀수 있음
 - start_btn: [처음] 버튼을 바꿀수 있음
 - end_btn	: [끝] 버튼을 바꿀수 있음
 * class나, id값을 주어 디자인을 하는것 보다는 DOM모델에 의해 접근해서 디자인 하는것을 추천함
 * 현재 페이지를 나타내는 text는 <span>태그로 감싸져 있음

- showPage()	: 사용자 화면에 보여질 html코드(페이징 부분)가 만들어지는 메서드
+ showPage() 메서드가 호출되면, setUrl, setDisplay메서드의 값은 변경할 수 없음
##################################################################################################

### 아래코드는 test할 수 있는 example 소스 코드이다.
	requir_once("해당 소스가 저장된 파일 경로");

	$total_row = 100;					// db에 저장된 게시물의 레코드 총 갯수 값. 현재 값은 테스트를 위한 값
	$list = 4;							// 화면에 보여지 게시물 갯수
	$block = 5;							// 화면에 보여질 블럭 단위 값[1]~[5]
	$page = new paging($_GET['page'], $list, $block, $total_row);
	$page->setUrl("content=forum");		// get값으로 가지고 다닐 변수가 있을시.
	$limit = $page->getVar("limit");	// 가져올 레코드의 시작점을 구하기 위해 값을 가져온다. 내부로직에 의해 계산된 값
	$page->setDisplay("prev_btn", "[prev]"); // [이전]버튼을 [prev] text로 변경
	$page->setDisplay("next_btn", "<img src='#' border=0>"); // 이와 같이 버튼을 이미지로 바꿀수 있음
	$page->setDisplay("full");
	$paging = $page->showPage();

	$sql = "SELECT * FROM forum ORDER BY idx DESC LIMIT $limit, $list";
	$result = mysql_query($sql);
	$num = $total_row - $limit; 		// 가상넘버링 사용시
	while($row = mysql_fetch_array($result)){

		..........(리스트처리)

		$num--;
	}

	echo $paging; //하단 페이징 화면 출력

*/

class paging{
	//--default obj--//
	private $page;					// 현재 페이지
	private $list;					// 화면에 보여질 게시물 갯수
	private $block;					// 하단에 보여질 페이징 갯수 블럭단위 [1]~[5]
	private $limit;					// 게시물 가져올 스타트 페이지
	private $total_row;				// 전체 게시물 row 수

	private $total_page;			// 전체 페이지 수
	private $total_block;			// 전체 블럭 갯수
	private $now_block;				// 현재 블럭
	private $start_page;			// 블럭 이동시 스타트 지점 객체
	private $end_page;				// 블럭의 끝 페이지
	private $is_next = false;		// 다음 페이지 이동을 위한 객체
	private $is_prev = false;		// 이전 페이지 이동을 위한 객체

	//--display(style) obj--//
	private $next_btn	= "[다음]";	// default 다음 이동 버튼
	private $prev_btn	= "[이전]";	// default 이전 이동 버튼
	private $end_btn	= "[끝]";	// default 끝 이동 버튼
	private $start_btn	= "[처음]";	// default 처음 이동 버튼
	private $display_class;			// <a 태그내의 class를 지정할 때
	private $display_id;			// <a 태그내의 id를 지정할 때
	private $display_mode = false;	// 기본 디스플레이 => [1]234 [다음], 풀모드=> [처음][이전][1]234[다음][끝]
	private $display_confirm = false;	// setDisplay 메서드 호출 확인값

	//--etc obj--/
	private $url_confirm = false;	// setUrl 메서드 호출 확인값
	private $html;					// 최종 결과물 리턴 객체


	public function paging($page=1, $list=10, $block=10, $total_row=0){	// --default init setting

		if(!$page)	$this->page = 1;
		else		$this->page = $page;
		$this->list = $list;
		$this->block = $block;
		$this->total_row = $total_row;
		$this->limit = ($this->page - 1) * $this->list;
		$this->url = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . "?";
		$this->setAuto();
	}

	public function getVar($name){

		if(gettype($this->$name) == "NULL"){
			echo "<script type=\"text/javascript\">alert('" . $name . " 객체는 없습니다.\\n얻고자 하시는 객체명을 확인해주세요.');</script>";
			return;
		}
		else	return $this->$name;

	}

	/* 사용하는 유저가 어쩔수 없이 객체의 값을 직접적으로 바꿔줘야 할 경우..아래 주석을 풀고 사용하면 됨
	 * 객체의 값을 마음대로 컨트롤 가능하나..이 메서드를 사용함으로 인해, 오류가 발생할 확률이 높다고 생각함
	public function setVar($name, $val){

		if(!is_numeric($val)){
			echo "<script type=\"text/javascript\">alert('setVar()메서드는 숫자만 허용합니다.');</script>";
			return;
		}
		else	$this->$name = $val;

	}*/

	private function setAuto(){

		$this->total_page = ceil($this->total_row / $this->list);
		$this->total_block = ceil($this->total_page / $this->block);
		$this->now_block = ceil($this->page / $this->block);

		$this->start_page = ($this->now_block - 1) * $this->block + 1;
		$this->end_page = $this->start_page + $this->block - 1;

		if($this->end_page > $this->total_page) $this->end_page = $this->total_page;
		if($this->now_block < $this->total_block) { $this->is_next = true; }
		if($this->now_block > 1) { $this->is_prev = true; }

	}

	public function setUrl($get=false){

		if($this->url_confirm == true){

			echo "<script type=\"text/javascript\">alert('setUrl 메서드는 showPage 메서드 이전에 셋팅하셔야 합니다.');</script>";
			return;

		}
		else if($get){

			$this->url = $this->url . $get ."&";
			$this->url_confirm = true;

		}
		else{

			echo "<script type=\"text/javascript\">alert('unknown error!!');</script>";

		}

	}

	public function setDisplay($name, $val=false){

		if($this->display_confirm == true){

			echo "<script type=\"text/javascript\">alert('setDisplay 메서드는 showPage 메서드 이전에 셋팅하셔야 합니다.');</script>";
			return;

		}
		switch($name){

			case "full"		:	$this->display_mode = true;
			break;

			case "class"	:	$this->display_class = " class=\"{$val}\"";
			break;

			case "id"		:	$this->display_id = " id=\"{$val}\"";
			break;

			case "next_btn"	:	$this->next_btn = $val;
			break;

			case "prev_btn"	:	$this->prev_btn = $val;
			break;

			case "end_btn"	:	$this->end_btn = $val;
			break;

			case "start_btn"	:	$this->start_btn = $val;
			break;

			default :	echo "<script type=\"text/javascript\">alert('[$name] is undefined Object!!');</script>";
			break;

		}

	}

	public function showPage(){

		//이 메서드를 호출하는 순간 setting은 할 수 없게 만듬
		$this->url_confirm = true;
		$this->display_confirm = true;

		if($this->display_mode && ($this->page != 1)){
			//$this->html =  "<a href=\"http://{$this->url}page=1\">{$this->start_btn}</a> ";
			$this->html =  "<li class='page-item'><a class='page-link' href=\"http://{$this->url}page=1\">{$this->start_btn}</a> </li>";
		}
		if($this->is_prev){
			$go_prev = $this->start_page - 1;
			//$this->html .=  "<a href=\"http://{$this->url}page=$go_prev\">{$this->prev_btn}</a> ";
			$this->html .=  "<li class='page-item'><a class='page-link' href=\"http://{$this->url}page=$go_prev\">{$this->prev_btn}</a></li> ";
		}

		for($i = $this->start_page; $i <= $this->end_page; $i++){
			if($i == $this->page){
				//$this->html .= "<span>$i</span>";
				$this->html .= "<li class='page-item active' id='page[$i]'><a class='page-link'><span >$i</span></a></li>";
			}else{
				//$this->html .= " <a href=\"http://{$this->url}page=$i\"{$this->display_class}{$this->display_id}>{$i}</a> ";
				$this->html .= " <li class='page-item' id='page[$i]'><a class='page-link' href=\"http://{$this->url}page=$i\"{$this->display_class}{$this->display_id}>{$i}</a></li> ";
			}
		}

		if($this->is_next){
			$go_next = $this->start_page + $this->block;
        	//$this->html .= " <a href=\"http://{$this->url}page=$go_next\">{$this->next_btn}</a>";
			$this->html .= " <li class='page-item'><a class='page-link' href=\"http://{$this->url}page=$go_next\">{$this->next_btn}</a></li>";
    	}
    	if($this->display_mode && ($this->page != $this->total_page)){
        	//$this->html .= " <a href=\"http://{$this->url}page=$this->total_page\">{$this->end_btn}</a>";
			$this->html .= " <li class='page-item'><a class='page-link' href=\"http://{$this->url}page=$this->total_page\">{$this->end_btn}</a></li>";
    	}

    	return $this->html;
	}

	// public function ModalshowPage(){
	//
	// 	//이 메서드를 호출하는 순간 setting은 할 수 없게 만듬
	// 	$this->url_confirm = true;
	// 	$this->display_confirm = true;
	//
	// 	if($this->display_mode && ($this->page != 1)){
	// 		//$this->html =  "<a href=\"http://{$this->url}page=1\">{$this->start_btn}</a> ";
	// 		$this->html =  "<li class='page-item'><a hresf=\"\">{$this->start_btn}</a> </li>";
	// 	}
	// 	if($this->is_prev){
	// 		$go_prev = $this->start_page - 1;
	// 		//$this->html .=  "<a href=\"http://{$this->url}page=$go_prev\">{$this->prev_btn}</a> ";
	// 		$this->html .=  "<li class='page-item'><a href=\"\">{$this->prev_btn}</a></li> ";
	// 	}
	//
	// 	for($i = $this->start_page; $i <= $this->end_page; $i++){
	// 		if($i == $this->page){
	// 			//$this->html .= "<span>$i</span>";
	// 			$this->html .= "<li class='page-item' id='page[$i]'><span>$i</span></li>";
	// 		}else{
	// 			//$this->html .= " <a href=\"http://{$this->url}page=$i\"{$this->display_class}{$this->display_id}>{$i}</a> ";
	// 			$this->html .= " <li class='page-item' id='page[$i]'><a href=\"\"{$this->display_class}{$this->display_id}>{$i}</a></li> ";
	// 		}
	// 	}
	//
	// 	if($this->is_next){
	// 		$go_next = $this->start_page + $this->block;
  //       	//$this->html .= " <a href=\"http://{$this->url}page=$go_next\">{$this->next_btn}</a>";
	// 		$this->html .= " <li class='page-item'><a href=\"\">{$this->next_btn}</a></li>";
  //   	}
  //   	if($this->display_mode && ($this->page != $this->total_page)){
  //       	//$this->html .= " <a href=\"http://{$this->url}page=$this->total_page\">{$this->end_btn}</a>";
	// 		$this->html .= " <li class='page-item'><a href=\"\">{$this->end_btn}</a></li>";
  //   	}
	//
  //   	return $this->html;
	// }

}
?>
