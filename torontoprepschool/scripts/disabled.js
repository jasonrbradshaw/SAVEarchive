// https://stackoverflow.com/questions/35990122/to-enable-form-fields-when-one-of-the-radio-button-checked-disable-fileds-when

function enable(enabled){
  var citizenship = document.getElementById('citizenship'),
      doe = document.getElementById('doe');
      language = document.getElementById('language');
  if(enabled){
    citizenship.removeAttribute('disabled');
    doe.removeAttribute('disabled');
    language.removeAttribute('disabled');
  } else {
    citizenship.setAttribute('disabled','disabled');
    doe.setAttribute('disabled','disabled');
    language.setAttribute('disabled','disabled');
  }
}
