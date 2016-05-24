<?php
/**
 * chibi gacha
 *
 * @author kcopper8 <kcopper8@gmail.com>
 * @version 0.1.0
 * */

/* **********************************************************************
 * 여기서부터는 설정값들이 기록되는 영역입니다.
 * **********************************************************************/

/**
 * 가챠 결과 댓글의 작성자명
 */
$gacha_chibi_comment_name = '가챠시스템';

/**
 * 가챠 결과 댓글의 비밀번호
 */
$gacha_chibi_comment_passwd = 'staff';

/**
 * 가챠 결과 댓글의 메모
 */
$gacha_chibi_comment_memo = 'SYSTEM';

/**
 * 가챠 결과 댓글의 소속
 */
$gacha_chibi_comment_position = '올드요크';

/**
 * 가챠 결과 댓글의 내용 템플릿입니다.
 *
 * [name] : 가챠를 굴린 사용자의 이름이 들어갑니다.
 * [item_name] : 가챠 결과 당첨된 아이템의 이름이 들어갑니다.
 */
$gacha_chibi_comment_message = '"[name]" 님의 가챠 결과: [[item_name]] 이(/가) 당첨되었습니다. 축하합니다.';

/**
 * 가챠 상품 설정입니다.
 *
 * 2차원 배열로 구성되어 있습니다.
 * 각각 상품의 상세 정보가 배열, 그 배열들을 하나로 묶은 또 하나의 배열입니다.
 *
 * 각각의 상품 상세 정보 배열은 다음과 같이 구성되어 있습니다.
 *
 * [상품아이디, 상품이름, 상품확률수]
 *
 * 상품아이디는 각 상품을 구분하기 위한 값입니다. 영문과 숫자로 이루어진 값을
 * 다른 상품과 겹치지 않도록 지정해주세요. 추후에 각 아이템의 이미지를 만들 계획이
 * 있으시다면, 그 이미지 파일의 이름을 이 상품 아이디와 같게 만드시는 것을 권해 드립니다.
 *
 * 스킨에서 댓글을 표시할 때, 가챠가 있는 댓글은 $comment->op->gacha 값 안에 이
 * 상품 아이디 값이 들어 있습니다.

 * 상품 이름은 상품 이름입니다. 가챠 당첨 결과 댓글 등에 사용됩니다.
 *
 * 상품 확률수는 해당 상품이 당첨될 경우의 수입니다. 각 상품이 당첨될 확률은
 *
 *    해당 상품의 상품확률수 / 전체 상품의 상품확률수의 합
 *
 * 이 됩니다. 예를 들어 3 개의 상품이 있을 때 각각의 당첨 확률을 10%, 20%, 70% 로
 * 설정하고 싶다면, 상품확률수를 각각 1, 2, 7 로 설정하시면 됩니다.
 * 10, 20, 70으로 설정해도 무방합니다.
 */
$gacha_items = [
  ['dw', '던전 월드', 1],
  ['aw', '아포칼립스 월드', 2],
  ['fc', '페이트 코어', 1],
  ['tb', '고마워요! 대소동 해결단!', 1],
  ['po', '폴라리스', 1],
  ['wm', '고민해결! 마법서점', 3],
  ['fi', '피아스코', 1]
];

/* **********************************************************************
 * 여기까지 설정값들이 기록되는 영역입니다.
 * **********************************************************************/

 define('GACHA_ITEM_KEY_ID', 0);
 define('GACHA_ITEM_KEY_NAME', 1);
 define('GACHA_ITEM_KEY_RATE', 2);

 function gacha_validate_items($gacha_items) {
   $item_keys = [];
   $rate_sum = 0;

   foreach($gacha_items as $item) {
     if (!is_array($item) || count($item) != 3) {
       return "Each item must be array and has 3 items.";
     }

     if (in_array($item[GACHA_ITEM_KEY_ID], $item_keys)) {
       return "Duplicated key $item[GACHA_ITEM_KEY_ID]";
     }
     $item_keys[] = $item[GACHA_ITEM_KEY_ID];

     if (!is_int($item[GACHA_ITEM_KEY_RATE]) || $item[GACHA_ITEM_KEY_RATE] < 0) {
       return "Item's Rate must be number and greater than zero. : ". $item[GACHA_ITEM_KEY_ID];
     }

     $rate_sum += $item[GACHA_ITEM_KEY_RATE];
   }

   if ($rate_sum <= 0) {
     return "Rate's sum must be greater than zero.";
   }
 }


 function gacha_pick_item($gacha_items, $rate_index) {
   $current_rate_sum = 0;

   foreach($gacha_items as $item) {
     if ($item[GACHA_ITEM_KEY_RATE] < 1) {
       continue;
     }

     $current_rate_sum += $item[GACHA_ITEM_KEY_RATE];

     if ($rate_index < $current_rate_sum) {
       return $item;
     }
   }
 }


 function gacha_sum_rate($gacha_items) {
   $rate_sum = 0;

   foreach($gacha_items as $item) {
     $rate_sum += $item[GACHA_ITEM_KEY_RATE];
   }

   return $rate_sum;
 }


 function gacha_draw() {
   if (func_num_args() > 0) {
     $gacha_items = func_get_arg(0);
   }

   if (!isset($gacha_items)) {
     $gacha_items = $GLOBALS['gacha_items'];
   }

   $gacha_index = rand(0, gacha_sum_rate($gacha_items) - 1);
   return gacha_pick_item($gacha_items, $gacha_index);
 }


 function gacha_chibi_comment_insert($chibi_conn, $cid, $pic_no, $no, $name) {
  $item = gacha_draw();

 	$gacha_op = [];
 	$gacha_op['gacha'] = $item[GACHA_ITEM_KEY_ID];
 	$gacha_op['position'] = $GLOBALS['gacha_chibi_comment_position'];
 	$gacha_op = serialize($gacha_op);

  $gacha_name = $GLOBALS['gacha_chibi_comment_name'];
 	$gacha_passwd = $GLOBALS['gacha_chibi_comment_passwd'];
 	$gacha_memo = $GLOBALS['gacha_chibi_comment_memo'];
 	$gacha_comment = str_replace(['[name]', '[item_name]'], [$name, $item[GACHA_ITEM_KEY_NAME]], $GLOBALS['gacha_chibi_comment_message']);

  $query = "INSERT INTO `chibi_comment` (`cid`,`pic_no`,`no`,`children`,`depth`, `name`, `passwd`, `rtime`, `comment`, `memo`, `hpurl`, `ip`, `op`)VALUES('".mysql_real_escape_string($cid)."','".mysql_real_escape_string($pic_no)."','".mysql_real_escape_string($no+1)."','1','1','".mysql_real_escape_string($gacha_name)."','".mysql_real_escape_string(md5($gacha_passwd))."','".time()."','".mysql_real_escape_string($gacha_comment)."','".mysql_real_escape_string($gacha_memo)."','','127.0.0.1','".mysql_real_escape_string($gacha_op)."')";
  return mysql_query($query,$chibi_conn);
 }

?>
