<?php
opcache_reset();
?>
<?php
session_start();
?>
<!--endtop-->


<nowiki>
<?php
if (empty($_SESSION['counter']))
        $_SESSION['counter'] = 1;
else
        $_SESSION['counter']++;
?>
</nowiki>

You've reloaded this page <?php echo $_SESSION['counter']; ?> times.

