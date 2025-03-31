function n_adminSignIn(){
    var username = document.getElementById("username");
    var password = document.getElementById("password");
 
    var f = new FormData();
    f.append("username", username.value);
    f.append("password", password.value);

    var r = new XMLHttpRequest();

    r.onreadystatechange = function() {
        if (r.readyState == 4) {
            var t = r.responseText;
            if (t == "Success") {
                window.location = "dashboard.php";


            } else {
                alert(t);
            }
        }
    };


    r.open("POST", "adminSignInProcess.php", true);
    r.send(f);

}

function sendEmail(username, email, password) {
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "sendAdminInvite.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("username=" + username + "&email=" + email + "&password=" + password);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var t = xhr.responseText;
            alert(t);
            var messageDiv = document.querySelector(".succWrap");
            messageDiv.innerHTML = "SUCCESS: "+t;
        } else {
            alert(t);
            var errorDiv = document.querySelector(".errorWrap");
            errorDiv.innerHTML = "Error: " + t;
        }
    };
}
