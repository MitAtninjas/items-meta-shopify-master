<html>
<head>
<script src="js/jquery.js"></script>
<script>
$(document).ready(function(){
  $("button").click(function(){
    // alert("The button was clicked.");
    $.get('https://dummy.restapiexample.com/api/v1/employees',function(result,state){
        console.warn(result)
        
    })
  });
});
</script>
</head>
<body>
    <h1>Profile List</h1>
    <button>Fetch ajax data</button>
    <p></p>
</body>
</html>