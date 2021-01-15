<?php
    include_once("includes/header.php");
?>
<div class='textboxContainer'>
    <input type="text" class="searchInput" placeholder="Search videos">
</div>
<div class="results">

    <script>
        $(function() {
            var username = '<?php echo $userLoggedIn; ?>';
            var timer;

            $(".searchInput").keyup(function() {
                clearTimeout(timer);
                timer = setTimeout(function(){
                    var val = $(".searchInput").val();
                    if (val != ""){
                        $.post("ajax/getSearchResults.php", {term: val, username: username}, 
                        function(data) {
                            $(".results").html(data);
                        })
                        // console.log(val);
                    }
                    else {
                        $(".results").html("");
                    }
                }, 500);
            })
        })
    </script>