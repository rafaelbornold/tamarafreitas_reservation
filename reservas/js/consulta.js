

selectedTable = document.getElementById('selectedTable');
selectedTable.addEventListener("change", changeTable);

function changeTable(){

    var pesquisa = document.getElementById('campo_pesquisa');
    var condicionBasica = document.getElementById('selectedTable');
    window.location.href = "consulta.php?login=AcessoTamaraFreitas&pesquisa="+pesquisa.value+"&condicionBasica="+condicionBasica.value;

}

function clearSearch(){

    var pesquisa = document.getElementById('campo_pesquisa');
    pesquisa.value = '';

}

