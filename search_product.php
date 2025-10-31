<input type="text" id="search-box" placeholder="Search by name, tag, or price">
<div id="results"></div>

<script>
document.getElementById('search-box').addEventListener('input', function(){
    let query = this.value.trim();
    if(query.length === 0){ document.getElementById('results').innerHTML=''; return; }
    
    let xhr = new XMLHttpRequest();
    xhr.open('GET','search_action.php?query='+encodeURIComponent(query),true);
    xhr.onload = function(){ document.getElementById('results').innerHTML = this.responseText; }
    xhr.send();
});
</script>
