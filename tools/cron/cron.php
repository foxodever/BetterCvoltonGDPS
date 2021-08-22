<title>Cron</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<script src="https://code.jquery.com/jquery-latest.js"></script>
<script>
    function autoban() {
        $("#1").html("Starting AutoBan...");
        var old = $("#1").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "autoban.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#1").html(old + "<br />AutoBan finished<hr>");
                fixcps();
            } else {
                $("#1").html(result + "<hr>");
                fixcps();
            }
        });
      }
    function fixcps() {
        $("#2").html("Fixing cps...");
        var old = $("#2").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "fixcps.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#2").html(old + "<br />cps fixed!<hr>");
                fixnames();
            } else {
                $("#2").html(result + "<hr>");
                fixnames();
            }
        });
      }
    function fixnames() {
        $("#3").html("Fixing names...");
        var old = $("#3").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "fixnames.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#3").html(old + "<br />names fixed!<hr>");
                friends();
            } else {
                $("#3").html(result + "<hr>");
                friends();
            }
        });
      }
    function friends() {
        $("#4").html("Fixing friends...");
        var old = $("#4").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "friendsLeaderboard.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#4").html(old + "<br />friends fixed!<hr>");
                rbl();
            } else {
                $("#4").html(result + "<hr>");
                rbl();
            }
        });
      }
    function rbl() {
        $("#5").html("Cleaning...");
        var old = $("#5").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "removeBlankLevels.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#5").html(old + "<br />Cleaned!<hr>Cron ended!");
            } else {
                $("#5").html(result + "<hr>");
            }
        });
      }
</script>
<p id="1"><button onclick="autoban()">Start cron</button></p>
<p id="2"></p><p id="3"></p><p id="4"></p><p id="5"></p>
