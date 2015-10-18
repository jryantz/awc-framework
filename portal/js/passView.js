function passView() {
    var pass = document.getElementById('pass').attributes['type'].value;

    if(pass == 'password') {
        document.getElementById('pass').attributes['type'].value = 'text';
    } else {

        document.getElementById('pass').attributes['type'].value = 'password';
    }
}