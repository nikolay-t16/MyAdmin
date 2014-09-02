function logaout() {

}
function checkmail(value) {
	reg = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
	if (!value.match(reg))
		return false;
	else
		return true;
}
function validate_form(o, text) {
	t = true;
	id = $(o).attr('id');
	$('#' + id + ' input[type="text"], #' + id + ' textarea').each(function(i, e) {
		if ($(e).val() == '') {
			alert('Не все обяззательные поля заполнены');
			$(e).focus();
			t = false
			return false;
		}

	})
	if (t)
		alert(text);
	return t;
}
function ChekField(obj) {
	if (!obj.val()) {
		alert('Не все обязательные поля заполнены');
		obj.focus();
		return false;
	}

	return true;
}