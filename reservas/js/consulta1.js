

selectedTable = document.getElementById('selectedTable');
selectedTable.addEventListener("change", changeTable);

function changeTable(){

    var pesquisa = document.getElementById('campo_pesquisa');
    var condicionBasica = document.getElementById('selectedTable');
    window.location.href = "consulta1.php?login=AcessoTamaraFreitas&pesquisa="+pesquisa.value+"&table="+condicionBasica.value;

}

function clearSearch(){

    var pesquisa = document.getElementById('campo_pesquisa');
    pesquisa.value = '';

}

