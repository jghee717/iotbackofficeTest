<?php

   class DBC
   {
      public $conn; //pdo 객체 생성용 필드
      public $result; //쿼리 실행 결과 필드

      public function DBI() //DB IN (접속)
      {
        $this->conn = new PDO('mysql:host=iotdidsystem.cafe24.com;dbname=smarthome_test', 'testuser', 'iotest2@');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->exec("SET NAMES 'utf8'");
      }

      public function DBQ($q) //DB QUERY IN (쿼리 투척)
      {
         $this->result = $this->conn->prepare($q);
      }

      public function DBE() //DB QUERY Execute (쿼리 실행)
      {
         $this->result->execute();
      }

      public function DBO() //DB OUT (종료)
      {
         $this->conn = null;
         $this->result = null;
      }

      public function DBF() //DB FETCH (결과 출력)
      {
         return $this->result->fetch(PDO::FETCH_ASSOC);
      }

      public function DBN()
      {
         return $this->result->fetch_num();
      }

      public function resultRow() //rowcount (실행 결과 행 개수)
      {
         return $this->result->rowCount();
      }

      public function lastId() //insert 된 마지막 컬럼 PK값을 출력 (AI+PK)
      {
         return $this->conn->lastInsertId();
      }
      public function DBP() //DB QUERY IN (쿼리 투척)
      {
         return $this->result->fetchAll();
      }
   }
/*
<기본 사용법>
   $conn = new DBC; //PDO 객체 생성 (객체를 생성해야 DB클래스 기능(함수) 사용 가능합니다.)

   try{
      $conn->DBI(); //DB 접속

      $query ="select * from a"; //변수에 쿼리 저장

      $conn->DBQ($query); //쿼리 전달(매개변수로 쿼리 전달해야 됩니다.)
      $conn->DBE(); //쿼리 실행

      //조회된 row결과의 개수나 수정된 row결과의 개수를 출력(어디에 쓸지는 알아서 생각해 보세요)
      $rowcnt = $conn->resultRow();
      echo "db result row : ".$rowcnt."<br/>";

        //쿼리 결과 가져오기
      while($row = $conn->DBF()) {
         echo $row['idx']."  ".$row['name']."<br/>";
      }

   }catch(PDOException $e){
      echo "Error: " . $e->getMessage();
   }

   $conn->DBO(); // db객체 해제 (종료)

*/

/*
<왜 try catch를 사용해?>
   try{
      에러가 일어날 수 있는 명령어들 (DB접근문 모두)
   }catch(PDOException $e){
      echo "Error: " . $e->getMessage();
   }
->굳이 꼭 안써도 사용은 접근은 가능한데 PDO방식의 권장 방식으로 따르는게 좋음
  코드 상 설계가 힘든 경우 사용 하지 않아도 됩니다.
*/

/*
<준비된 문 및 바운드 매개 변수>
//준비된 명령문은 동일한 (또는 유사한) SQL 문을 반복적으로 고효율로 실행하는 데 사용되는 기능입니다.
작동방식
   1. 준비 : SQL 문 템플리트가 작성되어 데이터베이스로 송신됩니다.
      매개 변수 ( "?"로 표시)라는 특정 값이 지정되지 않은 채로 있습니다. 예 : INSERT INTO MyGuests VALUES (?,?,?)
   2. 데이터베이스는 SQL 문 템플리트에서 구문 분석, 컴파일 및 쿼리 최적화를 수행하고 결과를 실행하지 않고 저장합니다.
   3. Execute : 나중에 응용 프로그램이 값을 매개 변수에 바인드하고 데이터베이스가 명령문을 실행합니다.
      응용 프로그램은 다른 값으로 원하는만큼 여러 번 명령문을 실행할 수 있습니다.
장점
   - 준비된 명령문은 쿼리 준비가 한 번만 수행되므로 구문 분석 시간이 줄어 듭니다 (명령문이 여러 번 실행 되더라도)
   - 바운드 매개 변수는 전체 쿼리가 아닌 매번 매개 변수 만 보내야하므로 서버에 대한 대역폭을 최소화합니다.
   - Prepared statements는 나중에 다른 프로토콜을 사용하여 전송되는 매개 변수 값이 올바르게 이스케이프 될 필요가 없으므로
     SQL injection에 매우 유용합니다. 원래 명령문 템플리트가 외부 입력에서 파생되지 않으면 SQL 삽입이 발생할 수 없습니다.

<바운드 매개변수를 이용한 insert>
//필수는 아니나... 보안과 다중 처리시 유용합니다.
-> insert만 정의했지만 update select insert 모두 적용 가능합니다.
-> 자세한 설명은 없습니다. 그냥 패턴에 적응하세요.

   $conn = new DBC;

   try{
      $conn->DBI();
       $query ="insert into a(name) values(:name)"; //prepare query 생성 (바인드 변수 앞에는 :를 붙입니다.)
      $conn->DBQ($query);

      $conn->result->bindParam(':name', $name); //바인드 변수로 들어갈 변수 지정

      // insert a row
      $name = "앙리";
      $conn->DBE();

      // insert another row (다중 insert)
      $name = "박지성";
      $conn->DBE();

      $name = "구르퀴프";
      $conn->DBE();

   }catch(PDOException $e){
      echo "Error: " . $e->getMessage();
   }

   $conn->DBO();
*/

/*
<SQL 기본 (이 정돈 알고 갑시다... 사람 이라면)>
DB작동 과정 : Parse -> Bind -> Execute -> Fetch

   SELECT column_name(s) FROM table_name

   INSERT INTO table_name (column1, column2, column3,...)
   VALUES (value1, value2, value3,...)

   DELETE FROM table_name
   WHERE some_column = some_value

   UPDATE table_name
   SET column1=value, column2=value2,...
   WHERE some_column=some_value
*/
?>
