

let validationButton = document.getElementById('validation-button');
let validationCodeContainer = document.getElementById('validation-code-container')
validationButton.addEventListener('click', (e) => {
    e.preventDefault();

    let celNumber = document.getElementById('celnumber').value;

    let url = `http://127.0.0.1:8000/send-sms/${celNumber}`
    fetch(url,{
        headers:{
            "X-CSRF-Token":document.querySelector('input[name="_token"]').value
        },
        method:"post"
    }).then(function(response){
        if(response.ok){
            alert("O código de SMS foi enviado com sucesso")
            validationCodeContainer.classList.remove('d-none')
        }
        console.log(response.body)
    });

    })