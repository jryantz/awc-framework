var phone = document.getElementById('phone');

phone.onkeyup = function() {
    var foo = phone.value.split('-').join('');
    foo = foo.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join('-');
    phone.value = foo;
};