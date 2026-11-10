// Único arquivo JavaScript do sistema.

// ---------- Funções auxiliares (usadas por vários formulários) ----------

// Mostra o texto de erro no <span id="erro-CAMPO">
function mostrarErro(campo, texto) {
    var el = document.getElementById('erro-' + campo);
    if (el) {
        el.textContent = texto;
    }
}

// Apaga todas as mensagens de erro de um formulário
function limparErros(form) {
    var spans = form.querySelectorAll('.erro-campo');
    for (var i = 0; i < spans.length; i++) {
        spans[i].textContent = '';
    }
}

// Confere o formato do e-mail
function emailValido(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// ---------- Cadastro de usuário ----------

function validarCadastro() {
    var form = document.getElementById('form-cadastro');
    var nome = form.nome.value.trim();
    var email = form.email.value.trim();
    var senha = form.senha.value;
    var confirmar = form.confirmar_senha.value;
    var ok = true;

    limparErros(form);

    if (nome === '') {
        mostrarErro('nome', 'Informe o nome.');
        ok = false;
    } else if (nome.length > 100) {
        mostrarErro('nome', 'O nome deve ter no máximo 100 caracteres.');
        ok = false;
    }

    if (email === '') {
        mostrarErro('email', 'Informe o e-mail.');
        ok = false;
    } else if (!emailValido(email)) {
        mostrarErro('email', 'Informe um e-mail válido.');
        ok = false;
    }

    if (senha === '') {
        mostrarErro('senha', 'Informe a senha.');
        ok = false;
    } else if (senha.length < 6) {
        mostrarErro('senha', 'A senha deve ter pelo menos 6 caracteres.');
        ok = false;
    }

    if (confirmar === '') {
        mostrarErro('confirmar_senha', 'Confirme a senha.');
        ok = false;
    } else if (senha !== confirmar) {
        mostrarErro('confirmar_senha', 'As senhas não são iguais.');
        ok = false;
    }

    // Retornar false cancela o envio do formulário
    return ok;
}
