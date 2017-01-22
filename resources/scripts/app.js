import $ from 'jquery';
import 'materialize';

$(() => {
  $('#reg_form').submit(() => {

    // toast user
    Materialize.toast('generating...', 6000);

    // format data to send
    let form_data = {};
    form_data.regno = $('#reg_no').val();
    form_data.offset = $('#offset').val();
    form_data = JSON.stringify(form_data);

    // Send the request
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'api/excel');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.responseType = 'blob';

    xhr.onload = function(e) {
      if (this.status == 200) {
        Materialize.toast('done.', 4000);
        let link = document.createElement('a');
        document.body.appendChild(link);
        link.href = window.URL.createObjectURL(this.response);
        link.download = "results.xlsx";
        link.click();

        //adding some delay in removing the dynamically created link solved the problem in FireFox
        setTimeout(function() {window.URL.revokeObjectURL(url);},0);
      }
      else {
        Materialize.toast('Invalid data!', 4000);
      }
    }

    xhr.send(form_data);

    return false;
  });
});
