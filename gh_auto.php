<?php
if ( $_POST['payload'] ) {
  shell_exec( 'git reset --hard HEAD && git pull --rebase 2>&1' );
}
?>
oy