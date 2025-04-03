<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="language" content="pt-BR">
    <title>Gerador de UTM</title>
    <meta name="description" content="Gerador de UTMs da equipe Sales Prime">
    <meta name="robots" content="none">
    <meta name="author" content="Rugemtugem">
    <meta name="keywords" content="#geradordeutm #utm #salesprime">

    <meta property="og:type" content="page">
    <meta property="og:url" content="https://salesprime.com.br/utm/">
    <meta property="og:title" content="Gerador de UTM">
    <meta property="og:image" content="https://salesprime.com.br/wp-content/uploads/2024/09/thumbnail-sales.jpg">
    <meta property="og:description" content="Gerador de UTMs da equipe Sales Prime">

    <meta property="article:author" content="Rugemtugem">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@">
    <meta name="twitter:title" content="Gerador de UTM">
    <meta name="twitter:creator" content="@">
    <meta name="twitter:description" content="Gerador de UTMs da equipe Sales Prime">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="style.css?v2" rel="stylesheet">
    <script src="script.js?v2"></script>
    <!-- Jquery JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
</head>

<body>
    <div class="p-3 fixed-top mt-5" style="width: 10%;">
        <div id="themeSwitch" class="theme-switch-container light-active">
            <div class="theme-switch-bg"></div>
            <div class="theme-switch-option light-option active">
                <i class="bi bi-sun"></i>
                <span>Light</span>
            </div>
            <div class="theme-switch-option dark-option">
                <i class="bi bi-moon-stars-fill"></i>
                <span>Dark</span>
            </div>
        </div>
    </div>
    <div class="container mt-2">
        <!-- Botões de Login e Cadastro no topo -->
        <?php if (!isset($_SESSION['username'])): ?>
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-dark border-light me-2" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="showLoginTab()">Entrar</button>
                <button class="btn btn-dark " data-bs-toggle="modal" data-bs-target="#loginModal" onclick="showRegisterTab()">Cadastro</button>
            </div>
        <?php else: ?>
            <div class="z-3 float-md-none float-sm-end">
                <span class="me-3">Usuário: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <form action="logout.php" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger btn-sm">Sair</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Modal de Login e Cadastro -->
        <div class="modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Autenticação do usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success'];
                                unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Abas de Navegação -->
                        <ul class="nav nav-tabs" id="loginTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Cadastro</button>
                            </li>
                        </ul>

                        <!-- Conteúdo das Abas -->
                        <div class="tab-content" id="loginTabContent">
                            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <!-- Formulário de Login -->
                                <form action="login.php" method="POST" class="mt-3">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Senha</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="p-2">
                                            <button type="submit" name="login" class="btn btn-light">Entrar</button>
                                        </div>
                                        <div class="ms-auto p-2">
                                            <a href="https://salesprime.com.br" class="btn btn-danger ms-auto">Sair</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                <!-- Formulário de Cadastro -->
                                <form action="login.php" method="POST" class="mt-3">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Senha</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <p class="small fw-light">Verifique se a senha tem pelo menos 8 caracteres</p>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="p-2"><button type="submit" name="register" class="btn btn-light">Cadastrar</button></div>
                                        <div class="ms-auto p-2"><a href="https://salesprime.com.br" class="btn btn-danger ms-auto">Sair</a></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h1 class="text-center">Gerador de UTM</h1>
        </div>
        <!-- Formulário para desktop -->
        <div class="container mt-2">
            <form id="utmForm" action="generate.php" method="POST" class="mt-4">
                <!-- Verifica se o usuário está logado -->
                <?php $isDisabled = !isset($_SESSION['username']) ? 'disabled' : ''; ?>

                <div class="input-group mb-3">
                    <span class="input-group-text p5" id="website_url" <?php if($isDisabled) echo 'data-tooltip-disabled="Precisa estar logado para gerar UTMs"'; ?>><i class="bi bi-link me-1"></i> URL do site:</span>
                    <input type="text" class="form-control theme-input disabled-field" placeholder="Insira sua url válida" aria-label="website_url" aria-describedby="website_url" id="website_url" name="website_url" required <?php echo $isDisabled; ?>>
                </div>

                <!-- Novo campo: Selecionar Perfil -->
                <div class="input-group mb-3">
                    <div class="btn-group w-100" role="group">
                        <span class="input-group-text p5 rounded-end-0"><i class="bi bi-person me-1"></i> Perfil:</span>
                        <input type="radio" class="btn-check disabled-field" name="profile" id="profile_sales" value="Sales_Prime" checked <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="profile_sales">Sales Prime</label>

                        <input type="radio" class="btn-check disabled-field" name="profile" id="profile_podvender" value="Podvender" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="profile_podvender">Podvender</label>

                        <input type="radio" class="btn-check disabled-field" name="profile" id="profile_prosperus" value="Prosperus" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="profile_prosperus">Prosperus</label>

                        <input type="radio" class="btn-check disabled-field" name="profile" id="profile_lumiere" value="Lumiere" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="profile_lumiere">Lumière</label>

                        <input type="radio" class="btn-check disabled-field" name="profile" id="profile_dani" value="Dani_Martins" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="profile_dani">Dani Martins</label>
                    </div>
                </div>

                <!-- Campo UTM Campaign atualizado -->
                <div class="input-group mb-3">
                    <div class="btn-group w-100 campaign" role="group" aria-label="Campaign type">
                        <span class="input-group-text p5 rounded-end-0"><i class="bi bi-megaphone me-1"></i> UTM Campaing:</span>
                        <!-- Botão Perpétuo -->
                        <input type="radio" class="btn-check disabled-field" name="utm_campaign" id="campaign_perpetuo" value="Perpetuo" checked <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="campaign_perpetuo">
                            <i class="bi bi-infinity icon-animate"></i> Perpétuo
                        </label>

                        <!-- Botão Conversão Direta -->
                        <input type="radio" class="btn-check disabled-field" name="utm_campaign" id="campaign_conversao" value="Conversao_Direta" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="campaign_conversao">
                            <i class="bi bi-arrow-right-circle icon-animate"></i> Conversão Direta
                        </label>

                        <!-- Botão Lançamento -->
                        <input type="radio" class="btn-check disabled-field" name="utm_campaign" id="campaign_lancamento" value="Lancamento" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="campaign_lancamento">
                            <i class="bi bi-rocket-takeoff icon-animate"></i> Lançamento
                        </label>

                        <!-- Botão Evento -->
                        <input type="radio" class="btn-check disabled-field" name="utm_campaign" id="campaign_evento" value="Evento" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="campaign_evento">
                            <i class="bi bi-calendar-event icon-animate"></i> Evento
                        </label>

                        <!-- Botão Outros -->
                        <input type="radio" class="btn-check disabled-field" name="utm_campaign" id="campaign_outro" value="Outro" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="campaign_outro">
                            <i class="bi bi-three-dots icon-animate"></i> Outros
                        </label>
                    </div>
                </div>

                <!-- Campo de texto para "Outros" (inicialmente oculto) -->
                <div id="campaign_other_container" class="input-group mb-3 d-none">
                    <span class="input-group-text p5 rounded-end-0"><i class="bi bi-pencil me-1"></i> Especificar:</span>
                    <input type="text" class="form-control theme-input" id="utm_campaign_other" name="utm_campaign_other" placeholder="Digite o nome da campanha">
                </div>
                <!-- Campo UTM Source atualizado para botões -->
                <div class="input-group mb-3">
                    <div class="btn-group w-100" role="group">
                        <span class="input-group-text p5 rounded-end-0"><i class="bi bi-menu-up me-1"></i> UTM Source:</span>
                        <input type="radio" class="btn-check disabled-field" name="utm_source" id="source_yt" value="yt" checked <?php echo $isDisabled; ?>>
                        <label class="btn source_yt btn-outline-secondary" for="source_yt"><i class="bi bi-youtube"></i></label>

                        <input type="radio" class="btn-check disabled-field" name="utm_source" id="source_ig" value="ig" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="source_ig"><i class="bi bi-instagram"></i></label>

                        <input type="radio" class="btn-check disabled-field" name="utm_source" id="source_wtt" value="wtt" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="source_wtt"><i class="bi bi-whatsapp"></i></label>

                        <input type="radio" class="btn-check disabled-field" name="utm_source" id="source_fb" value="fb" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="source_fb"><i class="bi bi-facebook"></i></label>

                        <input type="radio" class="btn-check disabled-field" name="utm_source" id="source_spot" value="spot" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="source_spot"><i class="bi bi-spotify"></i></label>

                        <input type="radio" class="btn-check disabled-field" name="utm_source" id="source_msg" value="msg" <?php echo $isDisabled; ?>>
                        <label class="btn btn-outline-secondary" for="source_msg"><i class="bi bi-send"></i></label>
                    </div>
                </div>

                <!-- Campo UTM Medium atualizado -->
                <div class="input-group mb-3">
                    <span class="input-group-text p5" id="utm_medium"><i class="bi bi-link-45deg me-1"></i> UTM Medium:</span>
                    <input type="text" class="form-control theme-input disabled-field" placeholder="Ex: Instagram_Bio, Instagram_Stories, Youtube_Video" aria-label="utm_medium" aria-describedby="utm_medium" id="utm_medium" name="utm_medium" required <?php echo $isDisabled; ?>>
                </div>

                <!-- Campo UTM Term atualizado -->
                <div class="input-group mb-3">
                    <span class="input-group-text p5" id="utm_term"><i class="bi bi-key me-1"></i> UTM Term:</span>
                    <input type="text" class="form-control theme-input disabled-field" placeholder="Ex: [VIDEO][NOME], [ESTATICO][NOME], [EPISODIO][CONVIDADO], [CONTATO], [RELACIONAMENTO]" aria-label="utm_term" aria-describedby="utm_term" id="utm_term" name="utm_term" required <?php echo $isDisabled; ?>>
                </div>

                <!-- Campo Nome Personalizado mantido -->
                <div class="input-group mb-3">
                    <span class="input-group-text p5" id="custom_name"><i class="bi bi-pencil me-1"></i> Nome Personalizado:</span>
                    <input type="text" class="form-control theme-input disabled-field" placeholder="Nome Personalizado da url encurtada (opcional)" aria-label="custom_name" aria-describedby="custom_name" id="custom_name" name="custom_name" <?php echo $isDisabled; ?>>
                </div>

                <button type="submit" class="btn btn-dark border-light mb-3 disabled-field" <?php echo $isDisabled; ?>>Gerar UTM</button>
            </form>
        </div>

        <p>
            <a data-bs-toggle="collapse" href="#sobreUTM" class="theme-link" role="button" aria-expanded="false" aria-controls="sobreUTM" onclick="this.querySelector('i').classList.toggle('bi-caret-down-fill'); this.querySelector('i').classList.toggle('bi-caret-right-fill');">
                <i class="bi bi-caret-right-fill"></i> Sobre links de UTM
            </a>
        </p>
        <!-- Mostra texto sobre UTMs -->
        <div class="collapse mt-3" id="sobreUTM">
            <div class="card card-body">
                <span class="link-dark"> link UTM é um tipo de URL que inclui parâmetros que identificam a fonte, o meio, a campanha, o termo e o conteúdo de uma visita ao site. Ele é usado para rastrear a eficácia de campanhas de marketing e determinar quais canais estão direcionando mais tráfego para um site.</span>
                <table class="table table-bordered table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Parâmetro</th>
                            <th>Propósito</th>
                            <th>Exemplos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="utm-referencia">utm_source</span></td>
                            <td>A utm SOURCE é preenchida no tráfego de forma automática. Portanto, no orgânico será utilizado o mesmo padrão que é usado pela META e pelo GOOGLE. Essas utms serão:</td>
                            <td><span class="utm-referencia">ig = Instagram</span>
                                <span class="utm-referencia">fb = Facebook</span>
                                <span class="utm-referencia">msg = Mensagem do Facebook</span>
                                <span class="utm-referencia">adwords = Anúncios do Google</span>
                                <span class="utm-referencia">in = LinkedIn</span>
                                <span class="utm-referencia">email = E-mails</span>
                                <span class="utm-referencia">yt = YouTube</span>
                                <span class="utm-referencia">cpc = Anúncios do Google</span>
                                <span class="utm-referencia">plat = Plataforma Sou Prosperus</span>
                                <span class="utm-referencia">wpp = WhatsApp</span>
                                <span class="utm-referencia">og = Orgânico</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="utm-referencia">utm_medium</span></td>
                            <td>Assim como a SOURCE, a MEDIUM também é preenchida de forma automática pela META e GOOGLE. Portanto também iremos seguir a nomenclatura própria. Somente se atentar para os pontos: a) Precisa-se começar com caractere maiúsculo. b) os espaços precisam ser preenchidos por "_".</td>
                            <td><span class="utm-referencia">Instagram_Reels</span>
                                <span class="utm-referencia">Linkedin_Bio</span>
                                <span class="utm-referencia">Instagram_Feed</span>
                                <span class="utm-referencia">Linkedin_Msg</span>
                                <span class="utm-referencia">Instagram_Story</span>
                                <span class="utm-referencia">Email_Mkt</span>
                                <span class="utm-referencia">Instagram_Bio</span>
                                <span class="utm-referencia">Email_News</span>
                                <span class="utm-referencia">Instagram_Transmissao</span>
                                <span class="utm-referencia">Email_Promo</span>
                                <span class="utm-referencia">Instagram_Post</span>
                                <span class="utm-referencia">Email_Remarketing</span>
                                <span class="utm-referencia">Youtube_Video</span>
                                <span class="utm-referencia">Plataforma_Banner</span>
                                <span class="utm-referencia">Youtube_Shorts</span>
                                <span class="utm-referencia">Plataforma_Acao</span>
                                <span class="utm-referencia">Linkedin_Post</span>
                                <span class="utm-referencia">WhatsApp_Mkt</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="utm-referencia">utm_campaign</span></td>
                            <td>Dentro da utm CAMPAIGN, teremos as informações de qual time está trabalhando aquela utm, tendo as variáveis entre a V4 Company e o time de marketing da Sales Prime, seguido do tipo de evento e ação que será trabalhado, por exemplo, lançamento, perpétuo, venda sem sino, imersão, prosperidade real, entre outros. Ainda na mesma UTM, seguimos com o nome dessa ação/momento, o mês em que será trabalhado, se é inbound ou outbound, o nome do perfil que será trabalhado, por exemplo, Dani Martins, Sales Prime, Claudio Rosa e outros, e o período em que a ação está, se é open to cart, captação, etc.
                                A nomenclatura segue, obrigatoriamente, sendo preenchida no modelo abaixo.
                                Importante ressaltar que todo espaço entre palavras precisa ser preenchido com "_", não deve ser colocado acento ou caractere especial, tudo precisa estar preenchido entre colchetes e, necessariamente, precisa começar com caractere maiúsculo.
                            </td>
                            <td><span class="utm-referencia">[Time][Evento][Nome_Do_Evento][Mes][In/Outbound][Perfil][Periodo]</span></td>
                        </tr>
                        <tr>
                            <td><span class="utm-referencia">utm_term</span></td>
                            <td>A utm TERM é a única utm que será personalizável, pois é onde será detalhado por qual tarefa veio o contato. Porém ainda segue um padrão de:
                                resumo_dia_00 (resumo: um breve resumo de, no máximo, três palavras explicando o que é aquela tarefa, seguido da palavra dia, seguido do dia em que foi feito aquela ação.
                                Por exemplo: Em um dia serão enviadas 3 mensagens no WhatsApp, logo, a utm TERM será
                            </td>
                            <td><span class="utm-referencia">whats01_dia_10</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h2 class="mt-5">Histórico de URLs</h2>
        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text" id="searchInput"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInputField" class="form-control" placeholder="Buscar UTM ou Nome Personalizado" aria-label="Buscar UTM" aria-describedby="searchInput">
                </div>
            </div>
            <div class="input-group col mb-2">
                <span class="input-group-text" id="dateFilterLabel"><i class="bi bi-calendar"></i></span>
                <input type="date" id="dateFilter" class="form-control" aria-label="Filtrar por data" aria-describedby="dateFilterLabel">
            </div>
            <div class="input-group col mb-2">
                <span class="input-group-text" id="clicksFilterLabel"><i class="bi bi-list-ol"></i></span>
                <input type="number" id="clicksFilter" class="form-control" placeholder="Filtrar por número de cliques" aria-label="Filtrar por número de cliques" aria-describedby="clicksFilterLabel">
            </div>
        </div>
        <!-- Tabela de URLs -->
        <div class="table-responsive">
            <table class="table table-bordered table-light">
            <thead>
                <tr>
                <th><i class="bi bi-qr-code"></i> QR Code</th>
                <th><i class="bi bi-link"></i> Link Original com UTM</th>
                <th><i class="bi bi-link-45deg"></i> Link Encurtado</th>
                <?php if (isset($_SESSION['username'])): ?>
                    <th><i class="bi bi-hand-index"></i> Clicks</th>
                    <th><i class="bi bi-trash"></i> Excluir</th>
                    <th><i class="bi bi-calendar2-check"></i> Data de Geração</th>
                    <th><i class="bi bi-person"></i> Usuário</th>
                <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obter todas as URLs ordenadas pela data de geração em ordem decrescente
                $query = $pdo->query("SELECT * FROM urls ORDER BY generation_date DESC");
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                // Gerar a URL do QR Code
                $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=1500x1500&data=' . urlencode("https://salesprime.com.br/utm/" . $row['shortened_url']);

                // Formatar a data para o formato dia-mês-ano hora:minutos
                $formattedDate = date('d-m-Y H:i', strtotime($row['generation_date']));

                // Exibir a linha da tabela com os dados da URL
                echo "<tr>
            <td data-label='QR Code' class='text-center align-middle'>
                <span data-bs-toggle='tooltip' title='Clique para abrir o QRCode'>
                <img src='" . $qrCodeUrl . "' alt='QR Code' style='width: 50px; height: 50px; cursor: pointer;' data-bs-toggle='modal' data-bs-target='#qrModal" . $row['shortened_url'] . "'>
                </span>
                <div class='modal fade' id='qrModal" . $row['shortened_url'] . "' tabindex='-1' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>QR Code</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body text-center'>
                    <div style='position: relative; display: inline-block;'>
                    <img id='qrCodeImage" . $row['shortened_url'] . "' src='" . $qrCodeUrl . "' alt='QR Code' style='width: 300px; height: 300px;'>
                    <img id='logoOverlay" . $row['shortened_url'] . "' src='' alt='Logo' style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; display: none;'>
                    </div>
                    <div class='mt-3'>
                    <label for='logoSelect" . $row['shortened_url'] . "'>Escolha uma logo para aplicar no QR Code:</label>
                    <select id='logoSelect" . $row['shortened_url'] . "' class='form-select mt-2' onchange='applyLogo(\"" . $row['shortened_url'] . "\")'>
                    <option value=''>Nenhuma</option>
                    <option value='images/logo_prosperus_club.png'>Logo Prosperus Club</option>
                    <option value='images/logo_sales_prime.png'>Logo Sales Prime</option>
                    </select>
                    </div>
                    <div class='mt-3'>
                    <button class='btn btn-dark border-light' onclick='downloadQRCodeWithLogo(\"" . $row['shortened_url'] . "\", \"https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data=" . urlencode("https://salesprime.com.br/utm/" . $row['shortened_url']) . "\")'>
                    <i class='bi bi-download' aria-hidden='true'></i> Baixar QRCode
                    </button>
                    </div>
                    <script>
                    function downloadQRCodeWithLogo(shortenedUrl, qrCodeUrl) {
                    var logo = document.getElementById('logoSelect' + shortenedUrl).value;
                    var canvas = document.createElement('canvas');
                    var context = canvas.getContext('2d');
                    var qrImage = new Image();
                    qrImage.crossOrigin = 'Anonymous';
                    qrImage.onload = function() {
                    canvas.width = 1000;
                    canvas.height = 1000;
                    context.drawImage(qrImage, 0, 0, 1000, 1000);
                    if (logo) {
                        var logoImage = new Image();
                        logoImage.crossOrigin = 'Anonymous';
                        logoImage.onload = function() {
                        var logoSize = canvas.width * 0.25; // 25% do tamanho do QR Code
                        var x = (canvas.width - logoSize) / 2;
                        var y = (canvas.height - logoSize) / 2;
                        context.drawImage(logoImage, x, y, logoSize, logoSize);
                        var link = document.createElement('a');
                        link.href = canvas.toDataURL();
                        link.download = shortenedUrl + '_with_logo.png';
                        link.click();
                        };
                        logoImage.src = logo;
                    } else {
                        var link = document.createElement('a');
                        link.href = canvas.toDataURL();
                        link.download = shortenedUrl + '.png';
                        link.click();
                    }
                    };
                    qrImage.src = qrCodeUrl;
                    }
                    </script>
                </div>
                </div>
                </div>
                </div>
                <script>
                function applyLogo(shortenedUrl) {
                var logo = document.getElementById('logoSelect' + shortenedUrl).value;
                var logoOverlay = document.getElementById('logoOverlay' + shortenedUrl);
                if (logo) {
                logoOverlay.src = logo;
                logoOverlay.style.display = 'block';
                } else {
                logoOverlay.style.display = 'none';
                }
                }
                </script>
            </td>
            <td data-label='Link Original com UTM' class='col-lg-4'>
                <a href='" . htmlspecialchars($row['long_url']) . "' target='_blank' class='link-dark' data-bs-toggle='tooltip' title='Copiar'>
                " . htmlspecialchars($row['long_url']) . "
                </a>
                <br><i class='bi bi-clipboard copy-icon' data-bs-toggle='tooltip' title='Copiar' onclick='copyToClipboard(this, \"" . htmlspecialchars($row['long_url']) . "\")'></i>
            </td>
            <td data-label='Link Encurtado' class='align-middle'>
                <a href='/utm/" . $row['shortened_url'] . "' target='_blank' class='link-dark' data-bs-toggle='tooltip' title='Copiar'>
                https://salesprime.com.br/utm/" . $row['shortened_url'] . "
                </a>
                <br><i class='bi bi-clipboard copy-icon' data-bs-toggle='tooltip' title='Copiar' onclick='copyToClipboard(this, \"https://salesprime.com.br/utm/" . $row['shortened_url'] . "\")'></i>
            </td>";
                if (isset($_SESSION['username'])) {
                    echo "
            <td data-label='Clicks' class='text-center align-middle'>" . ($row['clicks'] ?? 0) . "</td>
            <td data-label='Excluir' class='text-center align-middle'>
                <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>
                <i class='bi bi-trash'></i>
                </button>
            </td>
            <td data-label='Data da UTM' class='text-center align-middle'>" . $formattedDate . "</td>
            <td data-label='Usuário' class='text-center align-middle'>" . htmlspecialchars($row['username']) . "</td>";
                }
                echo "</tr>";
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>
    <?php if ($showModal): ?>
        <script>
            // Inicializa e exibe o modal
            document.addEventListener('DOMContentLoaded', function() {
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                loginModal.show();
            });
        </script>
        <!-- Modal de Login -->
        <div class="modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Autenticação do usuário</h5>
                    </div>
                    <div class="modal-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error'];
                                unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success'];
                                unset($_SESSION['success']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Abas de Navegação -->
                        <ul class="nav nav-tabs" id="loginTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Cadastro</button>
                            </li>
                        </ul>

                        <!-- Conteúdo das Abas -->
                        <div class="tab-content" id="loginTabContent">
                            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <!-- Formulário de Login -->
                                <form action="login.php" method="POST" class="mt-3">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Senha</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="p-2">
                                            <button type="submit" name="login" class="btn btn-light">Entrar</button>
                                        </div>
                                        <div class="ms-auto p-2">
                                            <a href="https://salesprime.com.br" class="btn btn-danger ms-auto">Sair</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                <!-- Formulário de Cadastro -->
                                <form action="login.php" method="POST" class="mt-3">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Senha</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <p class="small fw-light">Verifique se a senha tem pelo menos 8 caracteres</p>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <div class="p-2"><button type="submit" name="register" class="btn btn-light">Cadastrar</button></div>
                                        <div class="ms-auto p-2"><a href="https://salesprime.com.br" class="btn btn-danger ms-auto">Sair</a></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Remover os botões de fechar -->
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- Bootstrap Bundle com Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    <script>
        // Função para exibir a aba de login
        function showLoginTab() {
            const loginTab = document.getElementById('login-tab');
            const registerTab = document.getElementById('register-tab');
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            document.getElementById('login').classList.add('show', 'active');
            document.getElementById('register').classList.remove('show', 'active');
        }

        // Função para exibir a aba de cadastro
        function showRegisterTab() {
            const loginTab = document.getElementById('login-tab');
            const registerTab = document.getElementById('register-tab');
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            document.getElementById('register').classList.add('show', 'active');
            document.getElementById('login').classList.remove('show', 'active');
        }

        // Exibir mensagem de sucesso e abrir aba de login após cadastro
        <?php if (isset($_SESSION['success'])): ?>
            document.addEventListener('DOMContentLoaded', function () {
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
                showLoginTab();
            });
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function() {
    <?php if($isDisabled): ?>
    // Tooltip para elementos desabilitados individuais
    const disabledElements = document.querySelectorAll('.disabled-field[disabled]');
    disabledElements.forEach(el => {
        new bootstrap.Tooltip(el, {
            title: "Precisa estar logado para gerar UTMs",
            placement: 'top'
        });
    });
    
    // Tooltip para grupos de botões (como os radios)
    const disabledGroups = document.querySelectorAll('.btn-group:has(.disabled-field[disabled])');
    disabledGroups.forEach(el => {
        new bootstrap.Tooltip(el, {
            title: "Precisa estar logado para gerar UTMs",
            placement: 'top'
        });
    });

    <?php endif; ?>
});
    </script>
</body>

</html>