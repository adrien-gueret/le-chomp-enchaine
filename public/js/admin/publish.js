window.addEventListener("load", function()
{
    var forms = document.querySelectorAll('[data-publish]');
    for (var i = 0; i < forms.length; i++)
    {
        var form = forms[i];
        var publish = form.getAttribute('data-publish') == 1;

        form.addEventListener('submit', function(e) {
            if( ! confirm('Voulez-vous vraiment ' + (publish ? '' : 'dé') + 'publier cet article ?'))
                e.preventDefault();
        });
    }
});