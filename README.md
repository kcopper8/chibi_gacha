# 치비툴 가챠 기능


[치비툴](http://canto.btool.kr/info) 에 가챠 굴림을 추가하는 코드입니다.

## 설치방법
설치방법은 치비툴 [1.10.8.4 버전](http://canto.btool.kr/index.php?mid=chibi&category=131&document_srl=41364) 을 기준으로 설명합니다.

### 파일 업로드
`[gacha.lib.php](./blob/master/gacha.lib.php)` 파일을 치비툴 루트 디렉토리로 복사합니다.

### comment.submit.php 수정
가챠굴림 결과가 댓글로 저장되도록 코드를 추가합니다.

```
// lib/comment.submit.php 파일의 107 라인 [1.10.8.4 버전 기준]
}
echo "<br/>".$children;
  $query = "INSERT INTO `chibi_comment` (`idx`,`cid`,`pic_no`,`no`,`children`,`depth`, ...
  mysqli_query($chibi_conn, $query);
  /****** 추가한 코드 ********/
  
  if(empty($_POST['ht_gacha'])==false){
    include_once('../gacha.lib.php');
    gacha_chibi_comment_insert($chibi_conn, $cid, $pic_no, $no, $name);
  }
  
  /****** 추가한 코드 ********/
  echo "<script>alert('등록 완료!!');
```
댓글 저장 HTTP request 의 파라메터에 `ht_gacha` 가 포함되어 있을 때 가챠를 굴린 결과 댓글을 추가하는 코드입니다.

### 스킨 replyForm.php 수정
`ht_gacha` 파라메터를 추가할 수 있는 체크박스를 만들기 위해 댓글 입력폼을 수정합니다.

```
// skin/default/layout.php 파일의 323 라인 [1.10.8.4 버전 기준]
// 개인 제작 스킨 중에는 이 부분을 replyForm.php 라는 이름의 별도 파일로 분리하는 경우도 있습니다.
// layout.php 안에 없다면 'dice' 로 검색해서 위치를 찾아보세요.
<label class="checkbox inline">
<input type="checkbox" id="op['more']" name="op[more]" value="more" class="checkbox">more
</label>
<!-- *********** 추가한 코드 ********** -->

<label class="checkbox inline">
<input type="checkbox" id="ht_gacha" name="ht_gacha" value="ht_gacha" class="checkbox">가챠 굴리기
</label>

<!-- *********** 추가한 코드 ********** -->
<label class="checkbox inline">
<input type="checkbox" id="op['dice']" name="op[dice]" value="dice" class="checkbox">dice
</label>
<label class="checkbox inline">
```

### 스킨 layout.php 수정
가챠 결과 댓글을 일반 댓글과 다르게 보이도록 변경합니다.

꼭 필요한 작업은 아니지만 이 작업을 거치지 않았을 때, 가챠 결과를 속이려는 사용자가 가챠 기능을 통해 달린 댓글과 유사한 모양으로 댓글을 입력한 결과와 구분하기 쉽도록 댓글 부분을 수정해줍니다. 아래의 코드는 한가지 예시이며, 스킨 제작과 같은 방식으로 자유롭게 꾸밀 수 있습니다.

```
// skin/default/layout.php 파일의 215 라인 [1.10.8.4 버전 기준]
 
<p class="name">
  <!--// 소속아이콘이 있을 경우 출력//-->
  <?php if(empty($bbs->op->inst)==false) echo position($comment->op->position,$bbs->op->inst,$bbs->op->position); ?>
  <!-- *********** 추가한 코드 ********** -->

  <!--// 코멘트에 가챠가 있을 경우 아이콘 이미지 출력//-->
  <?php if(empty($comment->op->gacha)==false) echo '<img src="https://openclipart.org/download/185039/1381937672.svg" style="height:40px;width:40px;" />'; ?>
  <!-- *********** 추가한 코드 ********** -->
  <!--// 소속아이콘이 있을 경우 출력//-->
  <b><?=$comment->name?></b>

```
