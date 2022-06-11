$(document).ready(function(){
    console.log("Olá")    
})
function cadastrar(){
        with(cadastro){
            let ordem = cadastro.ordem.value
            let data_entrega = cadastro.data_entrega.value
            let status = cadastro.status.value
            let descricao = cadastro.descricao.value

            if(ordem != '' && data_entrega != '' && status != '' && descricao != ''){
                let dados = {
                    'ordem': ordem,
                    'descricao': descricao, 
                    'data_entrega': data_entrega, 
                    'status': status
                }

                let caminho = "/ordens/insert"

                $.ajax({
                    type: "post",
                    url: caminho,
                    data: dados,
                    beforeSend: function(){
                        console.log("Enviando...");
                    },
                    success: function(msg){
                        console.log(msg)
                    }
                })

            }
        
    }
}
