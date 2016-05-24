<pre>
<?php
 $gacha_items2 = [
   ['dw', '1던전 월드', 1],
   ['aw', '1아포칼립스 월드', 2],
   ['fc', '1페이트 코어', 1],
   ['tb', '1고마워요! 대소동 해결단!', 1],
   ['po', '1폴라리스', 1],
   ['wm', '1고민해결! 마법서점', 3],
   ['fi', '1피아스코', 1]
 ];

include('gacha.lib.php');
print_r($gacha_items);

 function report($ret, $expect) {
  echo "\n";
  echo "########################################################\n";
  echo "EXPECTED: $expect \n";

  if ($ret) {
    echo 'ACTIVE  : fail : '. $ret. "\n";
  } else {
    echo "ACTIVE  : SUCCESS\n";
  }
  echo "########################################################\n";
  echo "\n";
 }

 $item = gacha_draw($gacha_items2);
 print_r($item);

 report(gacha_sum_rate($gacha_items) != 10, 'SUM is 10');
 report(gacha_pick_item($gacha_items, 0)[GACHA_ITEM_KEY_ID] != 'dw', 'SUCCESS 0 dw');
 report(gacha_pick_item($gacha_items, 1)[GACHA_ITEM_KEY_ID] != 'aw', 'SUCCESS 1 aw');
 report(gacha_pick_item($gacha_items, 2)[GACHA_ITEM_KEY_ID] != 'aw', 'SUCCESS 2 aw');
 report(gacha_pick_item($gacha_items, 3)[GACHA_ITEM_KEY_ID] != 'fc', 'SUCCESS 3 fc');
 report(gacha_pick_item($gacha_items, 6)[GACHA_ITEM_KEY_ID] != 'wm', 'SUCCESS 6 wm');
 report(gacha_pick_item($gacha_items, 7)[GACHA_ITEM_KEY_ID] != 'wm', 'SUCCESS 7 wm');
 report(gacha_pick_item($gacha_items, 8)[GACHA_ITEM_KEY_ID] != 'wm', 'SUCCESS 8 wm');
 report(gacha_pick_item($gacha_items, 9)[GACHA_ITEM_KEY_ID] != 'fi', 'SUCCESS 9 fi');
 report(gacha_pick_item($gacha_items, 10), 'no value');

  report(gacha_validate_items([
    [], []
  ]), 'fail : Each item must be array and has 3 items.');

  report(gacha_validate_items([
    ['dw', '던전월드', 1], ['fc', '페이트 코어', 2], ['dw', '가짜 던전 월드', 3]
  ]), 'fail : Duplicated');

  report(gacha_validate_items([
    ['dw', '던전월드', 1], ['fc', '페이트 코어', 'ㅎ'], ['d3', '가짜 던전 월드', 3]
  ]), 'fail : Rate is not number');

  report(gacha_validate_items([
    ['dw', '던전월드', 1], ['fc', '페이트 코어', 3], ['d3', '가짜 던전 월드', -1]
  ]), 'fail : Rate is equal to one');

  report(gacha_validate_items([
    ['dw', '던전월드', 0], ['fc', '페이트 코어', 0], ['d3', '가짜 던전 월드', 0]
  ]), 'fail : Rate\'s sum must be greater than zero');

  report(gacha_validate_items($gacha_items), 'SUCCESS');
 ?>
</pre>
