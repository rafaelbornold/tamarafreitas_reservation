# Area de reservas - Descrição do sistema

HTML , CSS , JAVASCRIPT , PHP , MYSQL

O sistema consiste em: 

- PAGINA DE CADASTRO e PAGAMENTO (acessível ao público): https://www.tamarafreitas.com/reservas/

     - Uma página informativa com os dados sobre a reserva , preços, recomendações, contra-indicações, etc;

     - Formulácom com os campos: NOME , SOBRENOME , DOCUMENTO , EMAIL , TELEFONE , DEISPONIBILIDADE (2 checkbox) , ITEM DA RESERVA (3 checkbox);

     - O sistema deve receber as infromações do formulário, receber o pagamento através da API da plataforma de pagamento (STRIPE) e cadastrar os registros ocupando as vagas        disponíveis,  nas condições:

        - Verificar se todos os campos do formulário foram preenchidos corretamente;
        - Verificar se há vagas disponíveis antes de fazer o cadastro e pagamento;
        - Impedir duplicidade, verificando se a pessoa já realizou anteriormente um pagamento para o item selecionado (conferindo pelo numero do documento);
        - Evitar "overbooking" das vagas disponíveis, tomando em conta que o acesso é realizado ao mesmo tempo por milhares de pessoas;
        - Janelas de diálogo informativo sobre o status do cadastro e confirmação do pagamento;

        - Não é necessário criação de login;
        - As vagas são separadas por meses (por exemplo: Abril-15vagas Maio-12vagas Junho-7vagas);
        - Os preços para a reservas são iguais, independente do item e tipo de cliente (pagamento através do sistema);
        - Os preços finais para cada item é diferente (mas não serão pagos atraves do sistema , serão pagos pessoalmente no momento da realização do serviço);
        - Os preços dos ites são diferentes para novos clientes e clientes retorno;


- PAGINA DE CONSULTA (Acessível somente por pessoas autorizadas): https://www.tamarafreitas.com/reservas/consulta.php?login=AcessoTamaraFreitas

    - O acesso pode ser atraves de uma senha fixa única (não há necessidade de cadastro de usuários);
    - Listagem do cadastro de reserva com as informações dos registros;
    - Campo de Pesquisa; 
    - Exportar lista em EXCEL;


- O MESMO SISTEMA DEVE SERVIR PARA A REALIZAÇÃO DAS RESERVAS DE NOVOS CLIENTES E PARA CLIENTES DE RETORNO (EM CADASTROS SEPARADOS):
       
     - Novos clientes são os que realizarão a compra e o serviço pela primeira vez;
     - Cliente de retorno são clientes que já realizaram o serviço, e estão retornando para um ajuste/repasso.
     - A diferença entre clientes novos e retorno não será identificado pelo sistema. Serão abertas, em épocas diferentes, as vagas para cada tipo de cliente separadamente.
 
 
- SOBRE O PRODUTO (tanto novos quanto retorno): 

  - ITENS: 
  
        - Micropigmentação de Sobrancelhas;
        - Micropigmentação de Labios;
        - Micropigmentação de Olhos (Eyeliner);
  
  
  - DISPONIBILIDADE NA AGENDA:

        - Manhãs;
        - Tardes;
  
  - As vagas são separadas por meses (por exemplo: Abril-15vagas Maio-12vagas Junho-7vagas);
  - Os preços para a reservas são iguais, independente do item e tipo de cliente (pagamento através do sistema);
  - Os preços finais para cada item é diferente (mas não serão pagos atraves do sistema , serão pagos pessoalmente no momento da realização do serviço);
  - Os preços dos ites são diferentes para novos clientes e clientes retorno;
  
