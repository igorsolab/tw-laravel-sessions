$("#cep").mask("00000-000");

var cep = document.getElementById('cep');

cep.addEventListener("keyup", function(e) {
    if(cep.value.length == 9){
        autoComplete(cep.value);
    }
    if(cep.value.length == 0){
        document.getElementById('logradouro').value = ""
        document.getElementById('bairro').value = ""
        document.getElementById('cidade').value = ""
        document.getElementById('estado').value = ""
    }
})

function autoComplete(cep){
    let url = `http://127.0.0.1:8000/cep/${cep}`

    fetch(url)
    .then(function(response) {
        if(response.ok)
        {
            response.json().then(function(endereco)
            {
                document.getElementById('logradouro').value = endereco.endereco + ", Nº:";
                document.getElementById('bairro').value = endereco.bairro;
                document.getElementById('cidade').value = endereco.cidade;
                document.getElementById('estado').value = endereco.uf;
            })
        }
    })
}