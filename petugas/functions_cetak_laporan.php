<?php
function no_resub()
{?>
   <script>
    if (window.history.replaceState) {
        window.history.replaceState(null,null,window.location.href);
    }
   </script>
<?php
}
?>