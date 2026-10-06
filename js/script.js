document.addEventListener("DOMContentLoaded", function(){
    document.querySelectorAll(".delete-btn").forEach(function(btn){
        btn.addEventListener("click", function(e){
            if(!confirm("Are you sure you want to delete this task?")) e.preventDefault();
        });
    });
});