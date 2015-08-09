window.addEventListener("load", function()
{
    var deleteInputs = document.querySelectorAll('input[type="hidden"][name="__method__"][value="DELETE"]');
    for (var i = 0; i < deleteInputs.length; i++)
    {
        var form = deleteInputs[i].parentNode;
        
        form.addEventListener('submit', function(e){
            if( ! confirm("Voulez-vous vraiment supprimer cet élément ?"))
                e.preventDefault();
        });
    }
});