/*global $*/
/*global jQuery*/
/*global localAccess*/

function edit_mode() {
    var inputs = document.getElementsByClassName("account-overview-input");
    for(var i = 0; i < inputs.length; i++)
    {
       inputs[i].classList.remove("read-only");
       inputs[i].removeAttribute("readonly");
    }
    
    var radios = document.getElementsByClassName("radio-button");
    for(var i = 0; i < radios.length; i++)
    {
       radios[i].removeAttribute("disabled");
    }
    
    if(jQuery("input[name='allow_marketing']:checked").val()=='1'){
        var commPref = document.getElementsByClassName("comm-pref");
        for(var i = 0; i < commPref.length; i++)
        {
           commPref[i].removeAttribute("disabled");
        }
    }
    
    document.getElementById('btn_demo_edit').style.display = 'none';
    document.getElementById('btn_demo_cancel').style.display = 'inline-block';
    document.getElementById('btn_demo_save').style.display = 'inline-block';
    /*document.getElementById('btn_demo_edit_mobile').style.display = 'none';
    document.getElementById('btn_demo_cancel_mobile').style.display = 'inline-block';
    document.getElementById('btn_demo_save_mobile').style.display = 'inline-block';*/
}

function CancelUpdate() {
   location.reload();
}

