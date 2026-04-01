<div align="center">
  <img src="https://salesprime.com.br/wp-content/uploads/2024/09/thumbnail-sales.jpg" alt="UTM Generator" width="100%">
</div>

# 🛸 UTM Generator Pro | Multi-Domain Edition 
Sistema inteligente de gerenciamento, geração e rastreamento de links com Parâmetros UTM, criado para centralizar operações massivas de Growth & Vendas Multimarca (Sales Prime & Prosperus Club).

[![Version](https://img.shields.io/badge/Version-3.0%20(SalesPrime)-blueViolet?style=for-the-badge)](https://github.com/devsalesprime/utm)
[![PHP Version](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Architecture](https://img.shields.io/badge/Architecture-AJAX%20&%20Nginx%20Proxy-success?style=for-the-badge)](https://nginx.org/)
[![Database](https://img.shields.io/badge/MySQL-Datacenter-orange?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

---

## 🎯 Por que a nossa arquitetura importa? (Marketing & Engineering Aligned)
Quando operações comerciais de escala disparam milhares de campanhas ativas nos canais digitais de mídias (LinkedIn, Insta Mkt, Tráfego Pago, SDRs), o erro humano em planilhas de Links mata dezenas de métricas.

O **UTM Generator Pro** padroniza todas as requisições em uma plataforma viva:
* **Padronização Tática:** Extermínio dos erros de digitação. Estrutura forçada com "Regras do Colchetes" para compatibilidade exata.
* **Tracking Submerso de Cliques (Silent Analytics):** Não apenas geramos, mas hospedamos e protegemos cada salto de URL via proxy reverso no Nginx, registrando IP de cliques remotamente em nossa base centralizada cross-domain.
* **Governança Multimarcas:** Funcionalidade V3 exclusiva — O sistema separa estritamente qual membro da equipe (Auth Roles) tem permissão visual e de geração dentro dos portfólios corporativos selecionados (`salesprime.com.br` vs `prosperusclub.com.br`).

## 🚀 Principais Features V3.0 ("Sales Prime" Release)
- 🌓 **Tema Dark/Light Nativo:** Design premium "Glassmorphism" desenvolvido minuciosamente em CSS puro (`tema.css` e `theme-salesprime.css`), preservando a fadiga visual dos times de tráfego noturnos.
- ⚡ **SPA-Feeling & Modais Híbridos:** A experiência não atualiza páginas o tempo inteiro. Operações CRUD de usuários, permissões e domínio ocorrem nativamente na tela via API AJAX.
- 🌐 **Roteamento Centralizado em Múltiplos Domínios:** Scripts nativos interceptam as URLs encurtadas na porta 443 da VPS secundária e direcionam via `proxy_pass` para a nossa API Master, registrando métricas em tempo real — sem latência e sem quebra de SSL.
- 🗃️ **Tabela de Histórico Server-Side:** Capacidade para absorver +500.000 linhas de histórico de links graças à paginação nativa de banco aliada à cláusula Limit/Offset e Query Builder.
- 📱 **QR Code Engine Instantâneo:** Geração unida de QrCode com o avatar do domínio central inserido ao centro a partir do carregamento dinâmico.

---

## 🏗️ Folder Architecture
O código fonte adotou um *Layer Pattern* amigável focado em manutenção modular e redução de "Spaghetti Code".

```text
/utm-generator
│
├── api/                   # Interface de Comunicação (POST/GET) REST 
│   ├── create_utm.php     # Endpoint de injeção em banco das novas URLs
│   ├── permissions.php    # Middleware que gerencia Auth em domínios via roles   
│   └── dashboard_data.php # Agregador de JSON Data-driven para gráficos KPI
│
├── assets/                # Design System UI 
│   ├── css/               # Modularidade em botões, seletor flex-box e theming
│   ├── js/                # Controladores do Switch de temas, modais e Preview App
│   └── images/            # Logo Vectors & QR Code Thumbnails
│
├── includes/              # Componentes Centrais Renderizados no Server
│   ├── db.php             # Gerador singleton do PDO MySQL Driver com Charset Seguro
│   └── table_history.php  # Paginador HTML robusto anti-XSS c/ Filter Building 
│
└── /*.php                 # Core Views App (index.php, dashboard.php, generate.php, go.php) 
