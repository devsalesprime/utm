# Gerador de UTM Sales Prime

## 📝 Descrição do Projeto

Este é um sistema web para geração e gerenciamento de URLs com parâmetros UTM (Urchin Tracking Module). Ele permite que os usuários criem links personalizados com UTMs para rastrear a eficácia de suas campanhas de marketing, além de encurtar URLs e gerenciar um histórico de links gerados. O sistema foi desenvolvido para a equipe Sales Prime, visando otimizar o processo de criação e monitoramento de links de marketing.

## ✨ Funcionalidades

- **Geração de UTMs Personalizadas**: Crie URLs com `utm_campaign`, `utm_source`, `utm_medium`, `utm_content` e `utm_term`.
- **Encurtamento de URLs**: Gera códigos curtos únicos para URLs longas, facilitando o compartilhamento.
- **Nome Personalizado para URLs Encurtadas**: Permite definir um nome personalizado para o link encurtado, tornando-o mais amigável e fácil de lembrar.
- **Sanitização Automática de Nomes Personalizados**: Corrige automaticamente espaços, acentos e caracteres especiais em nomes personalizados para garantir URLs válidas e compatíveis.
- **Validação em Tempo Real**: Feedback visual instantâneo sobre a sanitização do nome personalizado durante a digitação.
- **Histórico de URLs Geradas**: Armazena e exibe um histórico completo de todas as URLs com UTMs criadas, incluindo detalhes como data de geração, cliques e comentários.
- **QR Code Dinâmico**: Geração de QR Codes para cada link encurtado, com a opção de adicionar logos personalizadas (Sales Prime, Prosperus Club).
- **Contador de Cliques**: Monitora o número de cliques em cada URL encurtada.
- **Sistema de Autenticação**: Login e cadastro de usuários para acesso seguro às funcionalidades.
- **Painel Administrativo**: (Para usuários administradores) Gerenciamento de usuários e URLs.
- **Filtragem e Busca**: Ferramentas para buscar e filtrar URLs no histórico por termo, data e número de cliques.
- **Tema Claro/Escuro**: Opção de alternar entre tema claro e escuro para melhor experiência do usuário.
- **Comentários nas UTMs**: Adicione descrições ou observações para cada UTM gerada.

## 🚀 Tecnologias Utilizadas

O projeto utiliza uma pilha de tecnologias web padrão para garantir robustez e facilidade de manutenção:

- **Backend**: PHP
- **Banco de Dados**: MySQL (utilizando PDO para conexão)
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3
- **Ícones**: Bootstrap Icons
- **Biblioteca JavaScript**: jQuery (para algumas funcionalidades)

## ⚙️ Instalação e Configuração

Para configurar e executar o sistema em seu ambiente local, siga os passos abaixo:

### Pré-requisitos

Certifique-se de ter os seguintes softwares instalados em seu sistema:

- **Servidor Web**: Apache ou Nginx
- **PHP**: Versão 7.4 ou superior (com extensões `pdo_mysql`, `iconv`)
- **MySQL**: Versão 5.7 ou superior

### Passos de Instalação

1.  **Clone o Repositório (ou faça o download dos arquivos):**
    ```bash
    git clone <URL_DO_SEU_REPOSITORIO>
    cd gerador-utm
    ```
    *Se você recebeu os arquivos diretamente, descompacte-os em seu diretório de servidor web (ex: `htdocs` do Apache ou `www` do Nginx).* 

2.  **Configurar o Banco de Dados:**
    a. Crie um banco de dados MySQL para o projeto (ex: `utm_generator`).
    b. Edite o arquivo `db.php` com as credenciais do seu banco de dados:
    ```php
    <?php
    $host = 'localhost'; // Ou o IP do seu servidor de banco de dados
    $db = 'utm_generator'; // Nome do seu banco de dados
    $user = 'root'; // Seu usuário do MySQL
    $pass = ''; // Sua senha do MySQL

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro de conexão com o banco de dados: " . $e->getMessage());
    }
    ?>
    ```
    c. O sistema criará a tabela `urls` automaticamente na primeira execução do `generate.php` se ela não existir. A estrutura da tabela é:
    ```sql
    CREATE TABLE urls (
        id INT AUTO_INCREMENT PRIMARY KEY,
        original_url TEXT NOT NULL,
        long_url TEXT NOT NULL,
        shortened_url VARCHAR(255) NOT NULL UNIQUE,
        username VARCHAR(255) NOT NULL,
        comment TEXT,
        generation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        clicks INT DEFAULT 0
    );
    ```
    *Nota: A coluna `clicks` e `comment` podem ser adicionadas posteriormente via `ALTER TABLE` se não existirem.*

3.  **Configurar o Servidor Web:**
    Certifique-se de que seu servidor web (Apache/Nginx) esteja configurado para servir os arquivos PHP do diretório do projeto.

4.  **Ajustar o `.htaccess` (para URLs amigáveis):**
    O arquivo `.htaccess` (fornecido) é crucial para o funcionamento das URLs encurtadas. Certifique-se de que ele esteja no diretório raiz do projeto e que o módulo `mod_rewrite` esteja habilitado no Apache.
    ```apache
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ go.php?code=$1 [L]
    ```

5.  **Acessar o Sistema:**
    Abra seu navegador e acesse a URL onde o projeto está hospedado (ex: `http://localhost/gerador-utm/` ou `http://seusite.com/utm/`).

## 💡 Como Usar

1.  **Login/Cadastro**: Se você não estiver logado, utilize os botões 
"Entrar" ou "Cadastro" no canto superior direito para acessar o sistema.
2.  **Gerar UTM**: Preencha os campos do formulário:
    -   **URL do site**: A URL base para a qual você deseja gerar a UTM.
    -   **Perfil**: Selecione o perfil associado à UTM (Sales Prime, Podvender, Prosperus, etc.).
    -   **Origem do Tráfego**: Escolha entre Orgânico ou Tráfego Pago.
    -   **UTM Campaign**: Selecione uma campanha pré-definida ou especifique uma nova.
    -   **UTM Source**: Selecione a fonte de tráfego (YouTube, Instagram, WhatsApp, etc.).
    -   **UTM Medium**: Insira o meio da campanha (ex: `Instagram_Bio`, `Youtube_Video`).
    -   **UTM Term**: Detalhe o termo da campanha (ex: `[VIDEO][NOME]`).
    -   **Nome Personalizado**: (Opcional) Digite um nome amigável para sua URL encurtada. O sistema irá sanitizá-lo automaticamente, removendo espaços, acentos e caracteres especiais. Você verá uma prévia do nome ajustado.
    -   **Comentário**: (Opcional) Adicione uma descrição ou observação sobre esta UTM.
3.  **Visualizar e Copiar**: Após gerar, a URL encurtada e a URL completa com UTM serão exibidas na tabela de histórico. Você pode copiá-las facilmente.
4.  **QR Code**: Clique no ícone de QR Code na tabela para visualizar e baixar o QR Code da sua URL encurtada, com a opção de adicionar logos.
5.  **Histórico**: A tabela abaixo do formulário exibe todas as UTMs geradas. Você pode buscar por UTM ou nome personalizado, filtrar por data e por número de cliques.
6.  **Excluir UTM**: Usuários logados podem excluir suas UTMs do histórico clicando no ícone da lixeira. É necessária a confirmação da senha para exclusão.

## 🛠️ Melhorias Recentes

As seguintes melhorias foram implementadas para aprimorar a usabilidade e a robustez do sistema:

### Sanitização Avançada de Nomes Personalizados

Anteriormente, o sistema permitia que nomes personalizados para URLs encurtadas contivessem caracteres que poderiam causar problemas de compatibilidade ou estética em URLs. Agora, uma função de sanitização robusta foi adicionada para garantir que todos os nomes personalizados sejam amigáveis para URLs (slug-friendly).

**Como funciona:**

-   **No Frontend (JavaScript)**: Ao digitar no campo "Nome Personalizado", o usuário recebe feedback em tempo real. Espaços são convertidos para hífens, acentos são removidos e caracteres especiais são eliminados. Uma prévia do nome sanitizado é exibida, e se o nome original for alterado, um alerta de confirmação é mostrado antes do envio do formulário.
-   **No Backend (PHP)**: A mesma lógica de sanitização é aplicada no servidor para garantir a consistência e segurança dos dados antes de serem armazenados no banco de dados. Isso previne qualquer entrada inválida que possa ter passado pela validação do frontend ou em casos de submissão direta.

**Regras de Sanitização Aplicadas:**

1.  Remoção de espaços no início e fim da string.
2.  Conversão de toda a string para minúsculas.
3.  Remoção de acentos e caracteres especiais (ex: `ç` vira `c`, `ã` vira `a`).
4.  Substituição de múltiplos espaços por um único hífen (`-`).
5.  Remoção de caracteres que não sejam letras (a-z), números (0-9), hífens (`-`) ou underscores (`_`).
6.  Remoção de múltiplos hífens consecutivos, substituindo-os por um único hífen.
7.  Remoção de hífens ou underscores no início e no fim da string.
8.  Limitação do comprimento do nome sanitizado para 50 caracteres para evitar URLs excessivamente longas.

**Exemplos de Transformação:**

| Entrada do Usuário        | Resultado Sanitizado         |
| :------------------------ | :--------------------------- |
| `Minha Campanha 2024!`    | `minha-campanha-2024`        |
| `  Promoção de Verão  `   | `promocao-de-verao`          |
| `Black Friday @#$%`       | `black-friday`               |
| `Campanha---Especial`     | `campanha-especial`          |
| `TESTE_com_Acentos_ação`  | `teste_com_acentos_acao`     |

### Melhoria no Fluxo de Geração de UTM

Corrigido um problema onde, após a sanitização do nome personalizado e a exibição de um alerta informativo, a UTM não era gerada imediatamente ou a página não era atualizada corretamente. Agora, o sistema garante que:

-   O alerta informativo sobre a sanitização é exibido.
-   A UTM é criada com o nome sanitizado, mesmo após o alerta.
-   O redirecionamento para a página `index.php` ocorre de forma fluida, garantindo que o histórico de URLs seja atualizado e visível sem a necessidade de um refresh manual.

## 🐛 Solução de Problemas

-   **Página em branco após gerar UTM**: Se a página ficar em branco após a geração, verifique se o `generate.php` está redirecionando corretamente para `index.php` e se não há nenhum `echo` ou saída antes do `header('Location: ...')`.
-   **Problemas com URLs amigáveis**: Verifique se o módulo `mod_rewrite` está habilitado no seu servidor Apache e se o arquivo `.htaccess` está configurado corretamente.
-   **Erros de banco de dados**: Certifique-se de que as credenciais em `db.php` estão corretas e que o usuário do banco de dados tem permissões para criar e manipular tabelas.

## 🤝 Contribuição

Contribuições são bem-vindas! Se você encontrar um bug ou tiver uma ideia para uma nova funcionalidade, sinta-se à vontade para abrir uma issue ou enviar um pull request.

1.  Faça um fork do projeto.
2.  Crie uma nova branch (`git checkout -b feature/nova-funcionalidade`).
3.  Faça suas alterações e commit (`git commit -am 'Adiciona nova funcionalidade'`).
4.  Envie para a branch original (`git push origin feature/nova-funcionalidade`).
5.  Abra um Pull Request.

## 📄 Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

---

