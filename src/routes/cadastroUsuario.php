  <?php
    include '../db/conectabanco.php';
   $nome = $_POST['nome'];
   $email = $_POST['email'];
   $senha = $_POST['senha'];
   $data_nascimento = $_POST['data_nascimento'];
   $telefone_celular = $_POST['telefone_celular'];
   $bairro = $_POST['bairro'];
   $numero = $_POST['numero'];
   $complemento = $_POST['complemento'];
   $cep = $_POST['cep'];
   $logradouro= $_POST['logradouro'];
    //R1
    $pattern = "/^[\w\W\d.\-\_]{3,}[@][\w\W]{3,}[.](com|com.br|edu.br)$/";
    $result = preg_match($pattern,$email);
    if($result == false){
        echo"Email Invalido";
        return;
    }

    $sql_code = "SELECT email FROM usuarios WHERE email = '$email'";
    $sql_query = $mysqli->query($sql_code);
    
    $quantidade = $sql_query->num_rows;

    if($quantidade == 1){ 
        echo "Email já Cadastrado!";
        return;
    }
    //R2
    /*   $pattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d].\S{10,}$/";
    
    $result = preg_match($pattern,$senha);
    if($result == false){
        header("location: http://localhost/expenseCalculation/src/pages/cadastroUsuario.php");
    }  */
    //R5
    function crypto($valor) {
        $ordenado = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X","Y","Z","a","b","c","d","e","f","g","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","1","2","3","4","5","6","7","8","9"];
    
        $desordenado = ["4","3","2","1","0","K","J","I","H","G","F","E","D","C","B","A","z","y","x","w","v","u","t","s","r","q","p","Z","Y","X","W","V","U","T","S","R","Q","P","O","N","M","L","o","n","m","l","k","j","i","h","g","f","e","d","c","b","a","9","8","7","6","5"];

        $valorCriptografado = array();
        
        $armazenaSplit = str_split($valor);
        
        foreach ($armazenaSplit as $valor) {
            $armazenaSearch = array_search($valor, $ordenado);
            array_push($valorCriptografado, $desordenado[$armazenaSearch]);
        }
        return implode($valorCriptografado);
    }

    $criptedPassword = crypto($senha); 

   
 
   $insertUser = "INSERT INTO usuarios (nome, email,senha, data_nascimento, telefone_celular)
    VALUES ('$nome', '$email', '$criptedPassword', '$data_nascimento', '$telefone_celular')";

    // ($hoje = date('d/m/Y H:i') > $expiracao_senha)

    if ($mysqli->query($insertUser) == true) {
        $last_id = $mysqli->insert_id;

        var_dump($last_id);
   $insertEndereco = "INSERT INTO enderecos (usuario_id, cep, logradouro, numero, bairro, complemento)
    VALUES ('$last_id', '$cep', '$logradouro', '$numero', '$bairro', '$complemento')";

    $mysqli->query($insertEndereco);
        
        $data_criacao =  date("Y-m-d H:i:s");

        $insertHistorico = "INSERT INTO historico_senha (usuario_id, senha, data_criacao)
        VALUES ('$last_id', '$criptedPassword', '$data_criacao')";
        
        $mysqli->query($insertHistorico);

    } else {
        echo "Error: <br>" . $mysqli->error;
        exit;
    }

    header("location: http://localhost/expenseCalculation/src/pages/");

  ?>