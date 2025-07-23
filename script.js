// Refatorado por desenvolvedor sênior

// Inicialização
const initTheme = () => {
  const themeSwitch = document.getElementById("themeSwitch");
  const body = document.body;
  const table = document.querySelector("table");
  const tableRows = document.querySelectorAll("table tbody tr");
  const links = document.querySelectorAll(".theme-link");
  const modals = document.querySelectorAll(".modal-content");
  const lightOption = themeSwitch.querySelector(".light-option");
  const darkOption = themeSwitch.querySelector(".dark-option");
  const inputs = document.querySelectorAll(".theme-input");

  const applyTheme = (isDark) => {
    themeSwitch.classList.toggle("dark-active", isDark);
    themeSwitch.classList.toggle("light-active", !isDark);
    lightOption.classList.toggle("active", !isDark);
    darkOption.classList.toggle("active", isDark);
    body.classList.toggle("bg-dark", isDark);
    body.classList.toggle("text-light", isDark);
    table?.classList.toggle("table-dark", isDark);

    tableRows.forEach(row => row.classList.toggle("text-light", isDark));

    inputs.forEach(input => {
      input.classList.toggle("dark-input", isDark);
      input.classList.toggle("dark-placeholder", isDark);
      input.classList.toggle("light-input", !isDark);
      input.classList.toggle("light-placeholder", !isDark);
    });

    links.forEach(link => {
      link.classList.toggle("dark-link", isDark);
      link.classList.toggle("light-link", !isDark);
    });

    modals.forEach(modal => {
      modal.classList.toggle("bg-dark", isDark);
      modal.classList.toggle("text-light", isDark);
    });

    localStorage.setItem("darkMode", isDark);
  };

  const isDark = localStorage.getItem("darkMode") === "true";
  applyTheme(isDark);

  lightOption.addEventListener("click", () => applyTheme(false));
  darkOption.addEventListener("click", () => applyTheme(true));
};

// Tooltips
const initTooltips = () => {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
};

// Copiar texto
const copyToClipboard = (icon, text) => {
  navigator.clipboard.writeText(text).then(() => {
    const tooltip = bootstrap.Tooltip.getInstance(icon);
    tooltip.setContent({ ".tooltip-inner": "Copiado!" });
    tooltip.show();
    icon.classList.replace("bi-clipboard", "bi-clipboard-check");

    setTimeout(() => {
      tooltip.setContent({ ".tooltip-inner": "Copiar" });
      tooltip.hide();
      icon.classList.replace("bi-clipboard-check", "bi-clipboard");
    }, 2000);
  }).catch(err => console.error("Erro ao copiar: ", err));
};

// Função para sanitizar nome personalizado (mesma lógica do PHP)
const sanitizeCustomName = (name) => {
  if (!name) return '';
  
  // Remove espaços no início e fim
  name = name.trim();
  
  // Converte para minúsculas
  name = name.toLowerCase();
  
  // Remove acentos
  name = name.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  
  // Substitui espaços por hífens
  name = name.replace(/\s+/g, '-');
  
  // Remove caracteres que não sejam letras, números, hífens ou underscores
  name = name.replace(/[^a-z0-9\-_]/g, '');
  
  // Remove múltiplos hífens consecutivos
  name = name.replace(/-+/g, '-');
  
  // Remove hífens no início e fim
  name = name.replace(/^[-_]+|[-_]+$/g, '');
  
  // Limita o tamanho a 50 caracteres
  name = name.substring(0, 50);
  
  return name;
};

// Validação em tempo real do nome personalizado
const initCustomNameValidation = () => {
  const customNameInput = document.getElementById('custom_name');
  if (!customNameInput) return;

  // Criar elemento para mostrar a prévia
  const previewElement = document.createElement('div');
  previewElement.id = 'custom_name_preview';
  previewElement.className = 'form-text mt-1';
  previewElement.style.display = 'none';
  customNameInput.parentNode.appendChild(previewElement);

  // Função para atualizar a prévia
  const updatePreview = () => {
    const originalValue = customNameInput.value;
    const sanitizedValue = sanitizeCustomName(originalValue);
    
    if (originalValue && sanitizedValue !== originalValue) {
      previewElement.innerHTML = `
        <i class="bi bi-info-circle text-warning"></i> 
        <span class="text-warning">Será ajustado para:</span> 
        <strong class="text-success">${sanitizedValue || '(vazio - será gerado automaticamente)'}</strong>
      `;
      previewElement.style.display = 'block';
    } else if (originalValue && sanitizedValue) {
      previewElement.innerHTML = `
        <i class="bi bi-check-circle text-success"></i> 
        <span class="text-success">Nome válido!</span>
      `;
      previewElement.style.display = 'block';
    } else {
      previewElement.style.display = 'none';
    }
  };

  // Adicionar eventos
  customNameInput.addEventListener('input', updatePreview);
  customNameInput.addEventListener('blur', updatePreview);
  
  // Validação inicial
  updatePreview();
};

// Confirmação de exclusão
const initDeleteConfirmation = () => {
  document.querySelectorAll(".delete-btn").forEach(button => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;

      if (!document.getElementById("confirmDeleteModal")) {
        document.body.insertAdjacentHTML("beforeend", `
          <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content bg-danger-subtle border-0">
                <div class="modal-header text-dark border-danger">
                  <h5 class="modal-title">Confirmar Exclusão</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="alert alert-danger border-0 m-0 h5 text-center">
                    Tem certeza que deseja excluir esta UTM?<br>
                    <strong class="lh-lg text-danger">Esta ação é irreversível!</strong>
                  </div>
                  <div class="form-group mt-3">
                    <label for="deletePassword" class="form-label text-danger">
                      Antes de inserir a senha para deletar, confirme abaixo se está ciente.
                    </label>
                    <input type="password" class="form-control" id="deletePassword" placeholder="Digite sua senha" disabled>
                  </div>
                  <div class="form-check mt-3 fw-bolder">
                    <input class="form-check-input" type="checkbox" id="confirmRadio">
                    <label class="form-check-label text-danger" for="confirmRadio">Estou ciente e quero continuar</label>
                  </div>
                </div>
                <div class="modal-footer border-danger">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-danger" id="confirmDelete" disabled>Excluir</button>
                </div>
              </div>
            </div>
          </div>`);
      }

      const modal = new bootstrap.Modal(document.getElementById("confirmDeleteModal"));
      modal.show();

      const confirmCheckbox = document.getElementById("confirmRadio");
      const deletePassword = document.getElementById("deletePassword");
      const confirmDeleteBtn = document.getElementById("confirmDelete");

      confirmCheckbox.addEventListener("change", () => {
        const enabled = confirmCheckbox.checked;
        deletePassword.disabled = !enabled;
        confirmDeleteBtn.disabled = !enabled;
      });

      const confirmDeleteHandler = () => {
        fetch("delete.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id, password: deletePassword.value })
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) document.querySelector(`button[data-id='${id}']`).closest("tr").remove();
            else alert(data.error || "Erro ao excluir.");

            modal.hide();
            modal.dispose();
            document.getElementById("confirmDeleteModal").remove();
            location.reload();
          });

        confirmDeleteBtn.removeEventListener("click", confirmDeleteHandler);
      };

      confirmDeleteBtn.addEventListener("click", confirmDeleteHandler, { once: true });
    });
  });
};

// Filtro de busca e data
const initSearchFilters = () => {
  const searchInput = document.getElementById('searchInputField');
  const dateFilter = document.getElementById('dateFilter');
  const clicksFilter = document.getElementById('clicksFilter');

  const filterTable = () => {
    const searchVal = searchInput.value.toLowerCase();
    const dateVal = dateFilter.value;
    const clicksVal = clicksFilter.value;
    const rows = document.querySelectorAll('.table-responsive table tbody tr');

    rows.forEach(row => {
      const cells = row.querySelectorAll('td');
      let match = !searchVal || Array.from(cells).some(cell => {
        // Ignora células de ação (ícones, botões)
        if (cell.querySelector('.copy-icon') || cell.querySelector('.delete-btn')) return false;
        return cell.textContent.toLowerCase().includes(searchVal);
    });
      if (dateVal && match) {
        const dateCell = row.querySelector('td[data-label="Data da UTM"]');
        if (dateCell) {
          const [d, m, y] = dateCell.textContent.trim().split(' ')[0].split('-');
          const rowDate = new Date(`${y}-${m}-${d}`);
          const filterDate = new Date(dateVal);
          match = rowDate.toDateString() === filterDate.toDateString();
        } else match = false;
      }

      if (clicksVal && match) {
        const clicks = parseInt(row.querySelector('td[data-label="Clicks"]').textContent.trim(), 10);
        match = clicks >= parseInt(clicksVal, 10);
      }

      row.style.display = match ? '' : 'none';
    });
  };

  searchInput.addEventListener('keyup', filterTable);
  dateFilter.addEventListener('change', filterTable);
  clicksFilter.addEventListener('input', filterTable);
};

// Mostrar campo "Outro" dinamicamente
const initCampaignOther = () => {
  const campaignRadios = document.querySelectorAll('input[name="utm_campaign"]');
  const otherContainer = document.getElementById('campaign_other_container');
  const otherInput = document.getElementById('utm_campaign_other');

  if (!campaignRadios.length || !otherContainer || !otherInput) return;

  const toggleOther = (radio) => {
    const isOther = radio.value === 'Outro';
    otherContainer.classList.toggle('d-none', !isOther);
    otherInput.required = isOther;
    if (isOther) setTimeout(() => otherInput.focus(), 100);
  };

  campaignRadios.forEach(radio => {
    radio.addEventListener('change', () => toggleOther(radio));
    if (radio.checked) toggleOther(radio);
  });
};

// Inicialização principal
window.addEventListener("DOMContentLoaded", () => {
  initTheme();
  initTooltips();
  initDeleteConfirmation();
  initSearchFilters();
  initCampaignOther();
  initCustomNameValidation();
});

