class Pesquisa{
	constructor(idPeriodoAtual, periodoAtual, idPeriodo, idCat, nome_prd, nome_cat, nome_b, urlPdfAtual, urlPdf) {
		this.idPeriodoAtual = idPeriodoAtual;
		this.periodoAtual = periodoAtual;
		this.idPeriodo = idPeriodo;
		this.idCat = idCat;
		this.nome_prd = nome_prd;
		this.nome_cat = nome_cat;
		this.nome_b = nome_b;
		this.urlPdfAtual = urlPdfAtual;
		this.urlPdf = urlPdf;
	}

	getIdPeriodoAtual(){
		return this.idPeriodoAtual;
	}
	setIdPeriodoAtual(id){
		this.idPeriodoAtual = id;
	}
	getPeriodoAtual(){
		return this.periodoAtual;
	}
	setPeriodoAtual(nome){
		this.periodoAtual = nome;
	}
	getIdPeriodo(){
		return this.idPeriodo;
	}
	setIdPeriodo(id){
		this.idPeriodo = id;
	}
	getPeriodo(){
		return this.nome_prd;
	}
	setPeriodo(nome){
		this.nome_prd = nome;
	}
	getIdCat(){
		return this.idCat;
	}
	setIdCat(id){
		this.idCat = id;
	}
	getCategoria(){
		return this.nome_cat;
	}
	setCategoria(nome){
		this.nome_cat = nome;
		this.bread(this.getPeriodo(), this.getCategoria(), this.getBusca());
	}

	getBusca(){
		return this.nome_b;
	}
	setBusca(str){
		if (str != '') {
			this.nome_b = str;
		}else{
			this.nome_b = document.getElementById("busca").value.replace(/\//g, "").trim();
		}
		document.getElementById("busca").value = '';
		this.bread(this.getPeriodo(), this.getCategoria(), this.getBusca());
		this.pesquisar(this.getIdPeriodo(), this.getPeriodo());
	}

	getUrlPdfAtual(){
		return this.urlPdfAtual;
	}
	setUrlPdfAtual(url){
		this.urlPdfAtual = url;
	}

	getUrlPdf(){
		return this.urlPdf;
	}
	setUrlPdf(url){
		this.urlPdf = url;
	}

	pesquisar(idPeriodo, nomePeriodo) {
		if (idPeriodo == ''){
			idPeriodo = this.getIdPeriodoAtual();
			nomePeriodo = this.getPeriodoAtual();
		}
		this.setPeriodo(nomePeriodo);//importante para adicionar na caixa de ítens filtro
		var xhttp;  
		if (idPeriodo == "") {
			document.getElementById("accordion-1").innerHTML = "";
			return;
		}
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("accordion-1").innerHTML = this.responseText;
			}
		};
		if (this.getPeriodo() == '') {
			this.bread(nomePeriodo, this.getCategoria(), this.getBusca());
			alert('nomePeriodo: ', nomePeriodo);
		}else{
			this.bread(this.getPeriodo(), this.getCategoria(), this.getBusca());
		}
		var idCategoria='', strBusca='';
		if(this.getIdCat() == ''){
			idCategoria='None';
		}else{
			idCategoria = this.getIdCat();
		}
		if(this.getBusca() == ''){
			strBusca='None';
		}else{
			strBusca = this.getBusca().replace(/\\/g, "");
		}
		xhttp.open("POST", "add/"+idPeriodo+"/"+idCategoria+"/"+strBusca, true);
		//adicionar na caixa com os dados do periodo
		xhttp.send();
		this.showProxEve();
	}

	// função para preencher os ítens pesquisados
	bread(nome_prd, nome_cat, nome_b) {

		var breadcrumb = '';
		if(nome_prd != '' || nome_cat != '' || nome_b != ''){
			breadcrumb += '<ol class="breadcrumb" style="margin: 10px 0;">';
			if(nome_prd != ''){
				breadcrumb += '<li class="breadcrumb-item"><a href="#retirar" title="Retirar filtro" onclick="pes.getRetira(\'periodo\')"><span style="text-transform: uppercase">'+this.getPeriodo()+'</span> <i class="typcn typcn-delete"></i></a></li>';
			}
			if(nome_cat != ''){
				breadcrumb += '<li class="breadcrumb-item"><a href="#retirar" title="Retirar filtro" onclick="pes.getRetira(\'categoria\')"><span>'+this.getCategoria()+'</span> <i class="typcn typcn-delete"></i></a></li>';
			}
			if(nome_b != ''){
				breadcrumb += '<li class="breadcrumb-item"><a href="#retirar" title="Retirar filtro" onclick="pes.getRetira(\'busca\')"><span>'+this.getBusca()+'</span> <i class="typcn typcn-delete"></i></a></li>';
			}
			breadcrumb += '</ol>';
		}
		$('#breadcrumb').html(breadcrumb); // enviar à div#breadcrumb as tags escritas acima
	}

	getRetira(metodo){
		switch (metodo){
			case 'periodo':
				this.pesquisar(this.getIdPeriodoAtual(), this.getPeriodoAtual());
				this.setUrlPdf(this.getUrlPdfAtual());
				this.setPeriodo('');
				this.setIdPeriodo('');

				break;
			case 'categoria':
				this.setIdCat('');
				this.setCategoria('');
				this.pesquisar(this.getIdPeriodo(), this.getPeriodo());

				break;
			case 'busca':
				this.setBusca('');
				this.pesquisar(this.getIdPeriodo(), this.getPeriodo());
				//document.getElementById('card-categorias').style.display = "none"; // exibir o card de categorias
				break;
		}
		this.bread(this.getPeriodo(), this.getCategoria(), this.getBusca());
	}
	showProxEve(){
		if ( (this.getCategoria() == '') && (this.getBusca() == '') 
			&& ((this.getPeriodo() == '') || (this.getPeriodo() == this.getPeriodoAtual())) ) {
			var p;
			p = new XMLHttpRequest();
			p.onload = function() {
				document.getElementById("showProxEventos").innerHTML = this.responseText;
			}
			p.open("POST", "showProxEvento/"+this.getIdPeriodoAtual(), true);
			p.send();
		}else{
			document.getElementById("showProxEventos").innerHTML = '';
		}
	}

	abrirPdf() {
		open(this.getUrlPdf(), "_blank", "toolbar=yes,scrollbars=no,resizable=no,top=200,left=200,width=1000,height=450");
	}
}
let pes = new Pesquisa('', '', '', '', '', '', '', '', '');

function setTexto() {
	var fb = document.forms[1];
	var txt = "";
	var i;
	for (i = 0; i < fb.length; i++) {
		if (fb[i].checked) {
			txt = txt + fb[i].value;
		}
	}
	if (txt == '1') {
		document.getElementById("order").innerHTML = 'Não foi útil';
	}else if (txt == '2') {
		document.getElementById("order").innerHTML = 'Não gostei';
	}else if (txt == '3') {
		document.getElementById("order").innerHTML = 'Razoável';
	}else if (txt == '4'){
		document.getElementById("order").innerHTML = 'Gostei';
	}else{
		document.getElementById("order").innerHTML = 'Foi muito útil';
	}
}

function validar() {
	document.getElementById("btnSubmit").disabled = false; 
}

