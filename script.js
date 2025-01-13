// Script para mudar tema
document.addEventListener("DOMContentLoaded", function () {
  // Seleciona elementos necessários
  const themeSwitch = document.getElementById("themeSwitch");
  const body = document.body;
  const table = document.querySelector("table");
  const tableRows = document.querySelectorAll("table tbody tr");
  const links = document.querySelectorAll(".theme-link");
  const modals = document.querySelectorAll(".modal-content");

  // Inicializa tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Obter as opções de tema
  const lightOption = themeSwitch.querySelector(".light-option");
  const darkOption = themeSwitch.querySelector(".dark-option");
  const inputs = document.querySelectorAll(".theme-input");

  // Verifica o estado inicial do tema
  if (localStorage.getItem("darkMode") === "true") {
    // Ativa o tema escuro
    darkOption.classList.add("active");
    lightOption.classList.remove("active");
    themeSwitch.classList.add("dark-active");
    body.classList.add("bg-dark", "text-light");
    table.classList.add("table-dark");
    tableRows.forEach((row) => {
      row.classList.add("text-light");
    });
    inputs.forEach((input) => {
      input.classList.add("dark-input", "dark-placeholder");
      input.classList.remove("light-input", "light-placeholder");
    });
    links.forEach((link) => {
      link.classList.add("dark-link");
      link.classList.remove("light-link");
    });
    modals.forEach((modal) => {
      modal.classList.add("bg-dark", "text-light");
    });
  } else {
    // Ativa o tema claro
    lightOption.classList.add("active");
    darkOption.classList.remove("active");
    themeSwitch.classList.add("light-active");
    body.classList.remove("bg-dark", "text-light");
    table.classList.remove("table-dark");
    tableRows.forEach((row) => {
      row.classList.remove("text-light");
    });
    inputs.forEach((input) => {
      input.classList.add("light-input", "light-placeholder");
      input.classList.remove("dark-input", "dark-placeholder");
    });
    links.forEach((link) => {
      link.classList.add("light-link");
      link.classList.remove("dark-link");
    });
    modals.forEach((modal) => {
      modal.classList.remove("bg-dark", "text-light");
    });
  }

  // Alternar para Light Theme
  lightOption.addEventListener("click", function () {
    themeSwitch.classList.add("light-active");
    themeSwitch.classList.remove("dark-active");
    lightOption.classList.add("active");
    darkOption.classList.remove("active");
    body.classList.remove("bg-dark", "text-light");
    table.classList.remove("table-dark");
    tableRows.forEach((row) => {
      row.classList.remove("text-light");
    });
    inputs.forEach((input) => {
      input.classList.add("light-input", "light-placeholder");
      input.classList.remove("dark-input", "dark-placeholder");
    });
    links.forEach((link) => {
      link.classList.add("light-link");
      link.classList.remove("dark-link");
    });
    modals.forEach((modal) => {
      modal.classList.remove("bg-dark", "text-light");
    });

    // Salvar no localStorage
    localStorage.setItem("darkMode", "false");
  });

  // Alternar para Dark Theme
  darkOption.addEventListener("click", function () {
    themeSwitch.classList.add("dark-active");
    themeSwitch.classList.remove("light-active");
    darkOption.classList.add("active");
    lightOption.classList.remove("active");
    body.classList.add("bg-dark", "text-light");
    table.classList.add("table-dark");
    tableRows.forEach((row) => {
      row.classList.add("text-light");
    });
    inputs.forEach((input) => {
      input.classList.add("dark-input", "dark-placeholder");
      input.classList.remove("light-input", "light-placeholder");
    });
    links.forEach((link) => {
      link.classList.add("dark-link");
      link.classList.remove("light-link");
    });

    // Salvar no localStorage
    localStorage.setItem("darkMode", "true");
  });
});

// Função para copiar texto para a área de transferência
function copyToClipboard(icon, text) {
  navigator.clipboard.writeText(text).then(
    function () {
      // Mostrar tooltip
      var tooltip = bootstrap.Tooltip.getInstance(icon);
      tooltip.setContent({
        ".tooltip-inner": "Copiado!",
      });
      tooltip.show();

      // Alterar ícone para clipboard-check
      icon.classList.remove("bi-clipboard");
      icon.classList.add("bi-clipboard-check");

      // Ocultar tooltip após 2 segundos e reverter ícone e tooltip
      setTimeout(function () {
        tooltip.setContent({
          ".tooltip-inner": "Copiar",
        });
        tooltip.hide();
        icon.classList.remove("bi-clipboard-check");
        icon.classList.add("bi-clipboard");
      }, 2000);
    },
    function (err) {
      console.error("Erro ao copiar o link: ", err);
    }
  );
}

// Script para confirmar exclusão
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".delete-btn").forEach(function (button) {
    button.addEventListener("click", function () {
      var id = this.getAttribute("data-id");

      // Criar o modal HTML se não existir
      if (!document.getElementById("confirmDeleteModal")) {
        var modalHtml = `
          <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content bg-danger-subtle border-0">
                <div class="modal-header text-dark border-danger">
                  <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="alert alert-danger border-0 m-0 h5 text-center" role="alert">
                    Tem certeza que deseja excluir esta UTM? <br><strong class="lh-lg text-danger">Esta ação é irreversível!</strong>
                  </div>
                  <div class="form-group mt-3">
                    <label for="deletePassword" class="form-label text-danger">Antes de inserir a senha para deletar, confirme abaixo se está ciente.</label>
                    <input type="password" class="form-control" id="deletePassword" placeholder="Digite sua senha" disabled>
                  </div>
                  <div class="form-check mt-3 fw-bolder">
                    <input class="form-check-input" type="checkbox" id="confirmRadio" name="confirmRadio" value="confirm">
                    <label class="form-check-label text-danger" for="confirmRadio">Estou ciente e quero continuar</label>
                  </div>
                </div>
                <div class="modal-footer border-danger">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-danger" id="confirmDelete" disabled>Excluir</button>
                </div>
              </div>
            </div>
          </div>
        `;
        // Adicionar modal ao body
        document.body.insertAdjacentHTML("beforeend", modalHtml);
      }

      var modal = new bootstrap.Modal(
        document.getElementById("confirmDeleteModal")
      );
      modal.show();

      // Habilitar/desabilitar input de senha e botão de exclusão
      document.getElementById("confirmRadio").addEventListener("change", function () {
        var deletePassword = document.getElementById("deletePassword");
        var confirmDelete = document.getElementById("confirmDelete");
        if (this.checked) {
          deletePassword.disabled = false;
          confirmDelete.disabled = false;
        } else {
          deletePassword.disabled = true;
          confirmDelete.disabled = true;
        }
      });

      // Confirmar exclusão
      document.getElementById("confirmDelete").addEventListener(
        "click",
        function confirmDeleteHandler() {
          var password = document.getElementById("deletePassword").value;

          fetch("delete.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ id: id, password: password }),
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                document
                  .querySelector(`button[data-id='${id}']`)
                  .closest("tr")
                  .remove();
              } else {
                alert(data.error || "Erro ao excluir a entrada.");
              }
              modal.hide();
              modal.dispose(); // Descartar o modal corretamente
              document.getElementById("confirmDeleteModal").remove(); // Remover modal do DOM
              location.reload(); // Atualizar a página para refletir a exclusão
            });

          // Remover o event listener para evitar múltiplas ligações
          document
            .getElementById("confirmDelete")
            .removeEventListener("click", confirmDeleteHandler);
        },
        { once: true }
      ); // Garantir que o evento seja disparado apenas uma vez
    });
  });
});

// Script para busca de UTMs
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById('searchInputField');
  const dateFilter = document.getElementById('dateFilter');
  const clicksFilter = document.getElementById('clicksFilter');

  function filterTable() {
    const searchValue = searchInput.value.toLowerCase();
    const dateValue = dateFilter.value;
    const clicksValue = clicksFilter.value;
    const table = document.querySelector('.table-responsive table');
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
      const cells = row.querySelectorAll('td');
      let match = true;

      // Filtro de busca por UTM
      if (searchValue) {
        match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchValue));
      }

      // Filtro de data
      if (dateValue && match) {
        // Use o seletor com base no valor correto do atributo data-label
        const dateCell = row.querySelector('td[data-label="Data da UTM"]');
        if (dateCell) {
          const rawDateText = dateCell.textContent.trim(); // Obtemos o texto da célula

          // Separar a parte da data e converter para formato padrão ISO (yyyy-mm-dd)
          const rawDateParts = rawDateText.split(' ')[0].split('-'); // ["08", "01", "2025"]
          const rowDate = new Date(`${rawDateParts[2]}-${rawDateParts[1]}-${rawDateParts[0]}`); // yyyy-mm-dd

          // Data do filtro no formato "yyyy-mm-dd"
          const filterDate = new Date(dateValue); // Garantido como yyyy-mm-dd

          // Comparar apenas a parte da data, ignorando horários
          match = rowDate.toISOString().split('T')[0] === filterDate.toISOString().split('T')[0];
        } else {
          match = false; // Caso a célula não seja encontrada
        }
      }


      // Filtro de número de cliques
      if (clicksValue && match) {
        const clicksCell = row.querySelector('td[data-label="Clicks"]');
        const rowClicks = parseInt(clicksCell.textContent.trim(), 10);
        match = rowClicks >= parseInt(clicksValue, 10);
      }

      row.style.display = match ? '' : 'none';
    });
  }

  // Adicionar event listeners para filtros
  searchInput.addEventListener('keyup', filterTable);
  dateFilter.addEventListener('change', filterTable);
  clicksFilter.addEventListener('input', filterTable);
});